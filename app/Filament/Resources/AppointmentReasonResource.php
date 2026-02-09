<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AppointmentReasonResource\Pages;
use App\Models\AppointmentReason;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;  
use Illuminate\Database\Eloquent\Builder;

class AppointmentReasonResource extends Resource
{
    protected static ?string $model = AppointmentReason::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationLabel = 'Motifs de rendez-vous';
    protected static ?string $modelLabel = 'Motif de rendez-vous';
    protected static ?string $pluralModelLabel = 'Motifs de rendez-vous';
    protected static ?string $navigationGroup = 'Gestion des rendez-vous';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nom du motif')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),

                Forms\Components\Textarea::make('description')
                    ->label('Description')
                    ->rows(4)
                    ->maxLength(1000)
                    ->columnSpanFull(),

                Forms\Components\Select::make('establishment_id')
                    ->label('Établissement')
                    ->relationship('establishment', 'name')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->default(fn () => auth()->user()->establishment_id)
                    ->disabled(fn () => auth()->user()->role !== 'super_admin')
                    ->visible(fn () => auth()->user()->role === 'super_admin')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Motif')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('description')
                    ->label('Description')
                    ->limit(100)
                    ->wrap(),

                Tables\Columns\TextColumn::make('establishment.name')
                    ->label('Établissement')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Créé le')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('name')
            ->filters([
                Tables\Filters\SelectFilter::make('establishment_id')
                    ->label('Établissement')
                    ->relationship('establishment', 'name')
                    ->visible(fn () => auth()->user()->role === 'super_admin'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        $user = auth()->user();

        // Super-admin : accès global
        if ($user->hasRole('super_admin')) {
            return $query;
        }

        // Admin établissement : uniquement son établissement
        return $query->where('establishment_id', $user->establishment_id);
    }

    

    public static function getRelations(): array
    {
        return [
            AppointmentReasonResource\RelationManagers\AppointmentsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListAppointmentReasons::route('/'),
            'create' => Pages\CreateAppointmentReason::route('/create'),
            'edit'   => Pages\EditAppointmentReason::route('/{record}/edit'),
        ];
    }
}
