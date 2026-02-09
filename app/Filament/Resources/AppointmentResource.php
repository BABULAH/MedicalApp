<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AppointmentResource\Pages;
use App\Filament\Resources\AppointmentResource\RelationManagers;
use App\Models\Appointment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Actions\Action;
use Filament\Forms\Components\Textarea;

class AppointmentResource extends Resource
{
    protected static ?string $model = Appointment::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

// public static function canViewAny(): bool
// {
//     return auth()->check() && auth()->user()->hasRole('doctor');
// }

public static function canCreate(): bool
{
    return false;
}


public static function getEloquentQuery(): Builder
{
    $user = auth()->user();

    if (! $user || ! $user->doctor) {
        return parent::getEloquentQuery()->whereRaw('1 = 0');
    }

    return parent::getEloquentQuery()
        ->where('doctor_id', $user->doctor->id)
        ->where('establishment_id', $user->doctor->establishment_id);
}




public static function table(Table $table): Table
{
    return $table
        ->columns([
            TextColumn::make('date')->date(),
            TextColumn::make('timeSlot.start_time')->label('Heure'),
            TextColumn::make('user.name')->label('Patient'),
            TextColumn::make('reason.name')->label('Motif'),
            BadgeColumn::make('status')
                ->colors([
                    'warning' => 'pending',
                    'success' => 'confirmed',
                    'danger' => 'cancelled',
                ]),
        ])
        ->actions([
            Action::make('confirm')
                ->label('Valider')
                ->color('success')
                ->visible(fn ($record) => $record->status === 'pending')
                ->action(fn ($record) => $record->update(['status' => 'confirmed'])),

            Action::make('cancel')
                ->label('Annuler')
                ->color('danger')
                ->form([
                    Textarea::make('doctor_comment')
                        ->label('Motif de l’annulation')
                        ->required(),
                ])
                ->action(function ($record, array $data) {
                    $record->update([
                        'status' => 'cancelled',
                        'doctor_comment' => $data['doctor_comment'],
                        'cancelled_by' => 'doctor',
                    ]);
                }),
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
            'index' => Pages\ListAppointments::route('/'),
            'create' => Pages\CreateAppointment::route('/create'),
            'edit' => Pages\EditAppointment::route('/{record}/edit'),
        ];
    }
}
