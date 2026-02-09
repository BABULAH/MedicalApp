<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TimeSlotResource\Pages;
use App\Filament\Resources\TimeSlotResource\RelationManagers;
use App\Models\TimeSlot;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use App\Models\User;
use App\Models\Availability;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Set;
use Illuminate\Support\Facades\Auth;

class TimeSlotResource extends Resource
{
    protected static ?string $model = TimeSlot::class;

    protected static ?string $navigationIcon = 'heroicon-o-clock';
    protected static ?string $navigationLabel = 'Créneaux';
    protected static ?string $pluralModelLabel = 'Créneaux';
    protected static ?string $modelLabel = 'Créneau';
    protected static ?string $navigationGroup = 'Gestion des Rendez-vous';


    public static function form(Form $form): Form
    {
        return $form->schema([

Select::make('availability_id')
    ->label('Disponibilité')
    ->options(function () {
        $user = auth()->user();

        return Availability::query()
            ->where('is_active', true)
            ->where('establishment_id', $user->establishment_id)
            ->whereHas('timeSlots', function ($q) {
                $q->where('is_booked', false);
            })
            ->with(['doctor', 'doctor.establishment'])
            ->get()
            ->mapWithKeys(fn ($availability) => [
                $availability->id => sprintf(
                    '%s (%s - %s) | Dr %s | %s',
                    ucfirst($availability->day_of_week),
                    $availability->start_time->format('H:i'),
                    $availability->end_time->format('H:i'),
                    $availability->doctor->full_name,
                    $availability->doctor->establishment->name
                )
            ]);
    })
    ->searchable()
    ->required(),
        TimePicker::make('start_time')
            ->label('Heure de début')
            ->required(),

        TimePicker::make('end_time')
            ->label('Heure de fin')
            ->required(),

        Toggle::make('is_booked')
            ->label('Réservé ?')
            ->default(false),

        ]);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('availability.day_of_week')
                    ->label('Disponibilité')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('establishment.name')
                    ->label('Établissement')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('start_time')
                    ->label('Début')
                    ->time('H:i')
                    ->sortable(),

                Tables\Columns\TextColumn::make('end_time')
                    ->label('Fin')
                    ->time('H:i')
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_booked')
                    ->label('Réservé')
                    ->boolean()
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Créé le')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('start_time')
            ->filters([

                // Filtre Établissement (visible uniquement pour le super admin)
                Tables\Filters\SelectFilter::make('establishment_id')
                    ->label('Établissement')
                    ->relationship('establishment', 'name')
                    ->visible(fn () => auth()->user()->role === 'super_admin'),

                // Filtre Médecin
               Tables\Filters\SelectFilter::make('doctor_id')
                ->label('Médecin')
                ->options(fn () =>
                    User::query()
                        ->where('role', 'doctor')
                        ->when(auth()->user()->role !== 'super_admin',
                            fn ($query) => $query->where('establishment_id', auth()->user()->establishment_id)
                        )
                        ->get()
                        ->mapWithKeys(fn ($user) => [$user->id => $user->full_name]) // <- ici on utilise l'accessor
                ),

                // Filtre Jour de la semaine
                Tables\Filters\SelectFilter::make('day_of_week')
                    ->label('Jour')
                    ->options([
                        'monday'    => 'Lundi',
                        'tuesday'   => 'Mardi',
                        'wednesday' => 'Mercredi',
                        'thursday'  => 'Jeudi',
                        'friday'    => 'Vendredi',
                        'saturday'  => 'Samedi',
                        'sunday'    => 'Dimanche',
                    ]),

                // Filtre Créneaux réservés seulement
                Tables\Filters\Filter::make('is_booked')
                    ->label('Réservé')
                    ->query(fn($query) => $query->where('is_booked', true)),
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

    public static function getRelations(): array
    {
        return [
            // RelationManagers\AppointmentsRelationManager::class, // décommente si tu veux voir le rendez-vous lié
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListTimeSlots::route('/'),
            'create' => Pages\CreateTimeSlot::route('/create'),
            'edit'   => Pages\EditTimeSlot::route('/{record}/edit'),
        ];
    }
}
