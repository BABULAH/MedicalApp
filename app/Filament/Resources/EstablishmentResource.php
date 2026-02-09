<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EstablishmentResource\Pages;
use App\Models\Establishment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

use Filament\Forms\Components\{
    TextInput,
    Textarea,
    Select,
    Section
};

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;

class EstablishmentResource extends Resource
{
    protected static ?string $model = Establishment::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office';
    protected static ?string $navigationLabel = 'Établissements';
    protected static ?string $pluralModelLabel = 'Établissements';
    protected static ?string $navigationGroup = 'Paramètres';

    /* ==========================================================
     | VISIBILITÉ DANS LE MENU
     | Seul le super admin voit ce menu
     ========================================================== */
    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()?->role === 'super_admin';
    }

    /* ==========================================================
     | FORMULAIRE
     ========================================================== */
    public static function form(Form $form): Form
    {
        return $form->schema([

            Section::make('Informations générales')
                ->schema([

                    TextInput::make('name')
                        ->label('Nom de l’établissement')
                        ->required()
                        ->maxLength(255),

                    Select::make('type')
                        ->label('Type')
                        ->options([
                            'hopital'    => 'Hôpital',
                            'clinique'   => 'Clinique',
                            'cabinet'    => 'Cabinet médical',
                            'pharmacie'  => 'Pharmacie',
                        ])
                        ->required(),

                    Textarea::make('address')
                        ->label('Adresse')
                        ->rows(2)
                        ->required(),

                    Select::make('locality_id')
                        ->label('Localité')
                        ->relationship('locality', 'name')
                        ->searchable()
                        ->preload()
                        ->required(),

                ]),

            Section::make('Contact & Localisation')
                ->schema([

                    TextInput::make('phone')
                        ->label('Téléphone')
                        ->tel(),

                    TextInput::make('email')
                        ->label('Email')
                        ->email(),

                    TextInput::make('latitude')
                        ->numeric(),

                    TextInput::make('longitude')
                        ->numeric(),
                ])
                ->columns(2),
        ]);
    }

    /* ==========================================================
     | TABLE
     ========================================================== */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('name')
                    ->label('Établissement')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('type')
                    ->label('Type')
                    ->badge()
                    ->sortable(),

                TextColumn::make('locality.name')
                    ->label('Localité')
                    ->searchable(),

                TextColumn::make('phone')
                    ->label('Téléphone'),

                TextColumn::make('email')
                    ->label('Email'),

                TextColumn::make('created_at')
                    ->label('Créé le')
                    ->date('d/m/Y')
                    ->sortable(),
            ])
            ->filters([

                SelectFilter::make('type')
                    ->label('Type')
                    ->options([
                        'hopital'   => 'Hôpital',
                        'clinique'  => 'Clinique',
                        'cabinet'   => 'Cabinet médical',
                        'pharmacie' => 'Pharmacie',
                    ]),

                SelectFilter::make('locality_id')
                    ->label('Localité')
                    ->relationship('locality', 'name'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->visible(fn () => auth()->user()->role === 'super_admin'),

                Tables\Actions\DeleteAction::make()
                    ->visible(fn () => auth()->user()->role === 'super_admin'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->visible(fn () => auth()->user()->role === 'super_admin'),
                ]),
            ]);
    }

    /* ==========================================================
     | MULTI-TENANT (LE PLUS IMPORTANT)
     ========================================================== */
    public static function modifyQueryUsing(Builder $query): Builder
    {
        $user = auth()->user();

        // 🔐 Les admins normaux ne voient QUE leur établissement
        if ($user->role !== 'super_admin') {
            $query->where('id', $user->establishment_id);
        }

        return $query;
    }

    /* ==========================================================
     | AUTORISATIONS CRUD
     ========================================================== */
    public static function canCreate(): bool
    {
        return auth()->user()->role === 'super_admin';
    }

    /* ==========================================================
     | PAGES
     ========================================================== */
    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListEstablishments::route('/'),
            'create' => Pages\CreateEstablishment::route('/create'),
            'edit'   => Pages\EditEstablishment::route('/{record}/edit'),
        ];
    }
}
