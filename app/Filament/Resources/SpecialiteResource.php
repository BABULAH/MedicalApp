<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SpecialiteResource\Pages;
use App\Filament\Resources\SpecialiteResource\RelationManagers;
use App\Models\Speciality;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SpecialiteResource extends Resource
{
    protected static ?string $model = Speciality::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationLabel = 'Spécialités';
    protected static ?string $modelLabel = 'Spécialité';
    protected static ?string $pluralModelLabel = 'Spécialités'; 
    protected static ?string $navigationGroup = 'Gestion des médecins';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nom de la spécialité')
                    ->required()
                    ->maxLength(255),
                    
                Forms\Components\Textarea::make('description')
                    ->label('Description')
                    ->required()
                    ->maxLength(1000)
                    ->rows(5) // définit le nombre de lignes visibles
                    ->columnSpanFull(), // prend toute la largeur disponible
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Spécialité')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('description')
                    ->label('Description')
                    ->searchable()
                    ->sortable()
                    ->wrap(), // <-- ceci permet le retour à la ligne,
                //
            ])
            ->filters([
                //la gestion des filtres
                Tables\Filters\SelectFilter::make('name')
                    ->label('Spécialité')
                    ->options(
                        Speciality::query()
                            ->distinct()
                            ->pluck('name', 'name')
                            ->toArray()
                    ),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSpecialites::route('/'),
            'create' => Pages\CreateSpecialite::route('/create'),
            'edit' => Pages\EditSpecialite::route('/{record}/edit'),
        ];
    }
}
