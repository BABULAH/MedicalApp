<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DoctorResource\Pages;
use App\Models\Doctor;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use App\Models\Speciality;
use Illuminate\Database\Eloquent\Builder;

/* =======================
 | FORMS
 ======================= */
use Filament\Forms\Components\{
    TextInput,
    Textarea,
    Select,
    Hidden,
    Section as FormSection
};

/* =======================
 | TABLE
 ======================= */
use Filament\Tables;
use Filament\Tables\Columns\{
    TextColumn,
    ImageColumn
};
use Filament\Tables\Filters\SelectFilter;

/* =======================
 | INFOLIST
 ======================= */
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Section as InfoSection;
use Filament\Infolists\Components\{
    TextEntry,
    ImageEntry,
    IconEntry
};

class DoctorResource extends Resource
{
    protected static ?string $model = Doctor::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationLabel = 'Médecins';
    protected static ?string $modelLabel = 'Médecin';
    protected static ?string $pluralModelLabel = 'Médecins';
    protected static ?string $navigationGroup = 'Gestion médicale';



    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        $user = auth()->user();

        // Super admin → accès global
        if ($user->hasRole('super_admin')) {
            return $query;
        }

        // Admin établissement → accès restreint
        return $query->where('establishment_id', $user->establishment_id);
    }





    /* ==========================================================
     | FORM
     ========================================================== */
    public static function form(Form $form): Form
    {
        return $form->schema([

            /* =====================================================
            | COMPTE UTILISATEUR (OBLIGATOIRE)
            ===================================================== */
            FormSection::make('Compte utilisateur')
                ->schema([
                    TextInput::make('user.first_name')
                        ->label('Prénom')
                        ->required(),

                    TextInput::make('user.last_name')
                        ->label('Nom')
                        ->required(),

                    TextInput::make('user.email')
                        ->label('Email de connexion')
                        ->email()
                        ->unique('users', 'email')
                        ->required(),

                    TextInput::make('user.password')
                        ->label('Mot de passe')
                        ->password()
                        ->required()
                        ->visibleOn('create')
                        ->dehydrateStateUsing(fn ($state) => bcrypt($state)),
                ])
                ->columns(2),

            /* =====================================================
            | INFORMATIONS MÉDICALES
            ===================================================== */
            FormSection::make('Informations générales')
                ->schema([
                    TextInput::make('registration_number')
                        ->label('Numéro d’inscription')
                        ->maxLength(255)
                        ->required(),

                    Textarea::make('bio')
                        ->label('Biographie')
                        ->rows(4),
                ]),

            /* =====================================================
            | SPÉCIALITÉ & ÉTABLISSEMENT
            ===================================================== */
        FormSection::make('Spécialité & Établissement')
    ->schema([

        // Établissement (super_admin uniquement)
        Select::make('establishment_id')
            ->label('Établissement')
            ->relationship('establishment', 'name')
            ->required()
            ->visible(fn () => auth()->user()->hasRole('super_admin'))
            ->reactive(),

        // Établissement auto pour admin
        Hidden::make('establishment_id')
            ->default(fn () => auth()->user()->establishment_id)
            ->dehydrated(true) // 🔴 OBLIGATOIRE
            ->visible(fn () => !auth()->user()->hasRole('super_admin')),

        // Spécialité
        Select::make('speciality_id')
            ->label('Spécialité')
            ->required()
            ->searchable()
            ->options(function (callable $get) {
                $establishmentId = auth()->user()->hasRole('super_admin')
                    ? $get('establishment_id')
                    : auth()->user()->establishment_id;

                return \App\Models\Speciality::where('establishment_id', $establishmentId)
                    ->pluck('name', 'id')
                    ->toArray();
            })
            ->reactive(),

        TextInput::make('experience_years')
            ->label('Années d’expérience')
            ->numeric()
            ->required(),
    ])
    ->columns(2),
            /* =====================================================
            | CONTACT
            ===================================================== */
            FormSection::make('Contact')
                ->schema([
                    TextInput::make('phone')
                        ->label('Téléphone')
                        ->tel()
                        ->required(),

                    TextInput::make('email')
                        ->label('Email professionnel')
                        ->email()
                        ->required(),
                ])
                ->columns(2),

            /* =====================================================
            | ADRESSE
            ===================================================== */
            FormSection::make('Adresse')
                ->schema([
                    Select::make('locality_id')
                        ->label('Localité')
                        ->relationship('locality', 'name') // 'locality' = relation dans le modèle Doctor
                        ->required()
                        ->searchable(),

                    TextInput::make('address')
                        ->label('Adresse')
                        ->required(), 

                    TextInput::make('latitude')
                        ->label('Latitude')
                        ->required(),

                    TextInput::make('longitude')
                        ->label('Longitude')
                        ->required(),
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
                ImageColumn::make('photo')
                    ->label('Photo')
                    ->circular(),

                TextColumn::make('user.first_name')
                    ->label('Prénom')
                    ->searchable(),

                TextColumn::make('user.last_name')
                    ->label('Nom')
                    ->searchable(),

                TextColumn::make('speciality.name')
                    ->label('Spécialité'),

                TextColumn::make('establishment.name')
                    ->label('Établissement'),
                    // ->toggleable(isToggledHiddenByDefault: auth()->user()->role !== 'super_admin'),


                TextColumn::make('status')
                    ->badge()
                    ->color(fn ($state) => $state === 'actif' ? 'success' : 'danger'),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'actif' => 'Actif',
                        'inactif' => 'Inactif',
                    ]),

                SelectFilter::make('speciality_id')
                    ->relationship('speciality', 'name'),

                SelectFilter::make('establishment_id')
                    ->relationship('establishment', 'name')
                    ->visible(fn () => auth()->user()->role === 'super_admin'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->defaultSort('created_at', 'desc');
    }

    /* ==========================================================
     | INFOLIST
     ========================================================== */
    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([

            InfoSection::make('Médecin')
                ->schema([
                    ImageEntry::make('photo')
                        ->circular()
                        ->height(120),

                    TextEntry::make('full_name')
                        ->label('Nom complet')
                        ->weight('bold'),

                    TextEntry::make('speciality.name')
                        ->label('Spécialité'),

                    TextEntry::make('experience_years')
                        ->label('Années d’expérience'),
                ]),

            InfoSection::make('Contact')
                ->schema([
                    TextEntry::make('phone'),
                    TextEntry::make('email'),
                    TextEntry::make('address'),
                    TextEntry::make('locality.name'),
                ])
                ->columns(2),

            InfoSection::make('Tarification')
                ->schema([
                    TextEntry::make('consultation_price')->money('XOF'),
                    TextEntry::make('emergency_price')->money('XOF'),
                ])
                ->columns(2),

            InfoSection::make('Statut')
                ->schema([
                    TextEntry::make('status')
                        ->badge()
                        ->color(fn ($state) => $state === 'actif' ? 'success' : 'danger'),

                    IconEntry::make('is_verified')->boolean(),
                ])
                ->columns(2),
        ]);
    }

    /* ==========================================================
     | MULTI-TENANT (SÉCURITÉ)
     ========================================================== */
    public static function modifyQueryUsing(Builder $query): Builder
    {
        $user = auth()->user();

        // 🔒 Admin établissement → voir uniquement son établissement
        if (! $user->hasRole('super_admin')) {
            $query->where('establishment_id', $user->establishment_id);
        }

        return $query;
    }


    /* ==========================================================
     | PAGES
     ========================================================== */
    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListDoctors::route('/'),
            'create' => Pages\CreateDoctor::route('/create'),
            'edit'   => Pages\EditDoctor::route('/{record}/edit'),
            'view'   => Pages\ViewDoctor::route('/{record}'),
        ];
    }
}
