<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Builder;


class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationLabel = 'Utilisateurs';
    protected static ?string $pluralModelLabel = 'Utilisateurs';
    protected static ?string $modelLabel = 'Utilisateur';
    protected static ?string $navigationGroup = 'Gestion des comptes';


    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        // Si l'utilisateur connecté n'est pas super admin
        if (auth()->user()->role !== 'super_admin') {
            // Il ne voit que les utilisateurs de son établissement
            $query->where('establishment_id', auth()->user()->establishment_id);
        }

        return $query;
    }
    public static function form(Form $form): Form
    {
        
        return $form
            ->schema([
                Forms\Components\Section::make('Informations personnelles')
                    ->schema([
                        Forms\Components\TextInput::make('first_name')
                            ->label('Prénom')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('last_name')
                            ->label('Nom')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true),

                        Forms\Components\TextInput::make('phone')
                            ->label('Téléphone')
                            ->tel()
                            ->maxLength(20),

                       Forms\Components\Select::make('gender')
                            ->label('Genre')
                            ->options([
                                'male'   => 'Homme',
                                'female' => 'Femme',
                            ])
                            ->nullable(),

                        Forms\Components\DatePicker::make('date_of_birth')
                            ->label('Date de naissance')
                            ->nullable(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Localisation')
                    ->schema([
                        Forms\Components\TextInput::make('address')
                            ->label('Adresse')
                            ->maxLength(255),

                        Forms\Components\Select::make('locality_id')
                            ->label('Localité')
                            ->relationship('locality', 'name')
                            ->searchable()
                            ->preload(),

                        Forms\Components\TextInput::make('latitude')
                            ->numeric(),

                        Forms\Components\TextInput::make('longitude')
                            ->numeric(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Sécurité & rôle')
                    ->schema([
                        Forms\Components\Select::make('role')
                            ->label('Rôle')
                            ->required()
                            ->options([
                                'admin' => 'Administrateur',
                                'doctor' => 'Médecin',
                                'patient' => 'Patient',
                            ])
                            ->disabled(fn ($record) =>
                                $record && auth()->id() === $record->id
                            )
                            ->dehydrated(fn ($record) =>
                                ! ($record && auth()->id() === $record->id)
                            ),

                        Forms\Components\Select::make('establishment_id')
                            ->label('Établissement')
                            ->relationship('establishment', 'name')
                            ->searchable()
                            ->required()
                            ->visible(fn () => auth()->user()->role === 'super_admin')
                            ->default(fn () =>
                                auth()->user()->role === 'admin'
                                    ? auth()->user()->establishment_id
                                    : null
                            )
                            ->dehydrateStateUsing(function ($state) {
                                if (auth()->user()->role === 'admin') {
                                    return auth()->user()->establishment_id;
                                }

                                return $state;
                            }),

                        Forms\Components\TextInput::make('password')
                            ->label('Mot de passe')
                            ->password()
                            ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                            ->required(fn (string $context) => $context === 'create')
                            ->dehydrated(fn ($state) => filled($state))
                            ->maxLength(255),
                    ])
                    ->columns(2),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nom complet')
                    ->sortable()
                    ->searchable(query: function ($query, $search) {
                        $query->where('first_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%")
                            ->orWhere('phone', 'like', "%{$search}%");
                    }),


                Tables\Columns\TextColumn::make('email')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('phone')
                    ->label('Téléphone')
                    ->searchable(),

                Tables\Columns\TextColumn::make('establishment.name')
                    ->label('Établissement')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\BadgeColumn::make('role')
                    ->label('Rôle')
                    ->colors([
                        'danger' => 'admin',
                        'warning' => 'doctor',
                        'success' => 'patient',
                    ]),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Créé le')
                    ->dateTime('d/m/Y')
                    ->sortable(),
            ])
            ->filters([
                // Filtrer par rôle
                Tables\Filters\SelectFilter::make('role')
                    ->label('Rôle')
                    ->options([
                        'admin' => 'Administrateur',
                        'doctor' => 'Médecin',
                        'patient' => 'Patient',
                    ]),

                // Filtrer par établissement, visible uniquement pour le super admin
                Tables\Filters\SelectFilter::make('establishment')
                    ->label('Établissement')
                    ->relationship('establishment', 'name')
                    ->visible(fn () => auth()->user()->role === 'super_admin'),
            ])

            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
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
            // AppointmentsRelationManager::class,
            // ReviewsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
