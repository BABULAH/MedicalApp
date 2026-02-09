<?php

namespace App\Filament\Resources\TimeSlotResource\Pages;

use App\Filament\Resources\TimeSlotResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTimeSlot extends CreateRecord
{
    protected static string $resource = TimeSlotResource::class;

    protected static ?string $title = 'Ajouter un nouveau créneau horaire';

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('back')
                ->label('Retour à la liste des créneaux')
                ->url($this->getResource()::getUrl('index'))
                ->icon('heroicon-o-arrow-left'),
        ];
    }
}
