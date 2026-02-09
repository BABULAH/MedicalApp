<?php

namespace App\Filament\Resources\TimeSlotResource\Pages;

use App\Filament\Resources\TimeSlotResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTimeSlot extends EditRecord
{
    protected static string $resource = TimeSlotResource::class;

    protected static ?string $title = 'Modifier le créneau horaire';

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
