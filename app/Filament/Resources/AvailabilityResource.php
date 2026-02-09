<?php

namespace App\Filament\Resources;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;
use App\Filament\Resources\AvailabilityResource\Pages;
use App\Models\{Availability, Doctor};
use App\Services\AvailabilityService;
use App\Enums\DayOfWeek;
use Filament\{Forms, Tables};
use Filament\Forms\Form;
use Filament\Forms\Components\{Section, Select, TimePicker, Toggle};
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables\Columns\{IconColumn, TextColumn};
use Filament\Tables\Filters\SelectFilter;

class AvailabilityResource extends Resource
{
    protected static ?string $model = Availability::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';
    protected static ?string $navigationLabel = 'Disponibilités';
    protected static ?string $modelLabel = 'Disponibilité';
    protected static ?string $pluralModelLabel = 'Disponibilités';
    protected static ?string $navigationGroup = 'Gestion des rendez-vous';
    protected static ?int $navigationSort = 2;


    



    /* ==========================================================
     | VALIDATIONS MÉTIER
     ========================================================== */
    public static function mutateFormDataBeforeCreate(array $data): array
    {
        AvailabilityService::checkOverlap(
            $data['doctor_id'],
            $data['day_of_week'],
            $data['start_time'],
            $data['end_time']
        );

        return $data;
    }

    public static function mutateFormDataBeforeSave(array $data, $record = null): array
    {
        AvailabilityService::checkOverlap(
            $data['doctor_id'],
            $data['day_of_week'],
            $data['start_time'],
            $data['end_time'],
            $record?->id
        );

        return $data;
    }

    /* ==========================================================
     | FORM
     ========================================================== */
    public static function form(Form $form): Form
    {
        return $form->schema([

            Section::make('Disponibilité')
                ->schema([

                    Select::make('doctor_id')
                        ->label('Médecin')
                        ->searchable()
                        ->required()
                        ->options(fn () =>
                            Doctor::query()
                                ->when(auth()->user()->role !== 'super_admin', fn ($q) =>
                                    $q->where('establishment_id', auth()->user()->establishment_id)
                                )
                                ->with('user') // charger la relation user
                                ->get()
                                ->mapWithKeys(fn ($doctor) => [
                                    $doctor->id => $doctor->full_name
                                ])
                                ->toArray()
                        ),

                    Select::make('day_of_week')
                        ->label('Jour de la semaine')
                        ->options(
                            collect(DayOfWeek::cases())
                                ->mapWithKeys(fn ($day) => [
                                    $day->value => $day->label()
                                ])
                        )
                        ->required(),

                    TimePicker::make('start_time')
                        ->label('Heure de début')
                        ->seconds(false)
                        ->required(),

                    TimePicker::make('end_time')
                        ->label('Heure de fin')
                        ->seconds(false)
                        ->required()
                        ->afterStateUpdated(function ($state, $get) {
                            if ($state <= $get('start_time')) {
                                throw ValidationException::withMessages([
                                    'end_time' => 'L’heure de fin doit être supérieure à l’heure de début.',
                                ]);
                            }
                        }),

                    Toggle::make('is_active')
                        ->label('Active')
                        ->default(true),
                ]),
        ]);
    }

    /* ==========================================================
     | TABLE
     ========================================================== */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('doctor.full_name')
                    ->label('Médecin')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('doctor.establishment.name')
                    ->label('Établissement')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('day_of_week')
                    ->label('Jour')
                    ->formatStateUsing(fn ($state) => DayOfWeek::from($state)->label())
                    ->sortable(),

                TextColumn::make('start_time')
                    ->label('Début')
                    ->time('H:i'),

                TextColumn::make('end_time')
                    ->label('Fin')
                    ->time('H:i'),

                IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),

                TextColumn::make('created_at')
                    ->label('Créé le')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([

                SelectFilter::make('doctor_id')
    ->label('Médecin')
    ->options(fn () =>
        Doctor::query()
            ->when(
                auth()->user()->role !== 'super_admin',
                fn ($q) =>
                    $q->where('establishment_id', auth()->user()->establishment_id)
            )
            ->with('user')
            ->get()
            ->mapWithKeys(fn ($doctor) => [
                $doctor->id => $doctor->full_name
            ])
            ->toArray()
    ),


                SelectFilter::make('day_of_week')
                    ->label('Jour')
                    ->options(
                        collect(DayOfWeek::cases())
                            ->mapWithKeys(fn ($day) => [
                                $day->value => $day->label()
                            ])
                    ),

                Tables\Filters\Filter::make('active')
                    ->label('Actives seulement')
                    ->query(fn (Builder $query) =>
                        $query->where('is_active', true)
                    ),

                SelectFilter::make('establishment_id')
                    ->label('Établissement')
                    ->relationship('establishment', 'name')
                    ->visible(fn () =>
                        auth()->user()->role === 'super_admin'
                    ),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->defaultSort('created_at', 'desc');
    }

    /* ==========================================================
     | MULTI-TENANT
     ========================================================== */
       public static function getEloquentQuery(): Builder
        {
            $query = parent::getEloquentQuery();
            $user = auth()->user();

            // Super-admin → accès global
            if ($user->hasRole('super_admin')) {
                return $query;
            }

            // Admin établissement → seulement ses médecins
            return $query->whereHas('doctor', function (Builder $q) use ($user) {
                $q->where('establishment_id', $user->establishment_id);
            });
        }
    /* ==========================================================
     | PAGES
     ========================================================== */
    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListAvailabilities::route('/'),
            'create' => Pages\CreateAvailability::route('/create'),
            'edit'   => Pages\EditAvailability::route('/{record}/edit'),
        ];
    }
}
