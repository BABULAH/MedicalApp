<?php

namespace App\Filament\Resources\AppointmentReasonResource\Pages;

use App\Filament\Resources\AppointmentReasonResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAppointmentReason extends EditRecord
{
    protected static string $resource = AppointmentReasonResource::class;

    protected static ?string $title = 'Modifier le motif de rendez-vous';
   
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('back')
                ->label('Retour à la liste')
                ->url($this->getResource()::getUrl('index'))
                ->icon('heroicon-o-arrow-left'),
        ];
    }
}
