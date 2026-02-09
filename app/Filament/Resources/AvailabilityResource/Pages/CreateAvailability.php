<?php

namespace App\Filament\Resources\AvailabilityResource\Pages;

use App\Filament\Resources\AvailabilityResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;
use Illuminate\Validation\ValidationException;
use App\Services\AvailabilityService;


class CreateAvailability extends CreateRecord
{
    protected static string $resource = AvailabilityResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        try {
            AvailabilityService::checkOverlap(
                $data['doctor_id'],
                $data['day_of_week'],
                $data['start_time'],
                $data['end_time']
            );
        } catch (ValidationException $e) {
            Notification::make()
                ->title('Chevauchement détecté')
                ->body('Ce médecin a déjà une disponibilité sur ce créneau.')
                ->danger()
                ->send();

            throw $e;
        }

        return $data;
    }

    /**
     * Redirige vers la liste après création
     */
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function handleRecordCreation(array $data): \Illuminate\Database\Eloquent\Model
    {
        try {
            return parent::handleRecordCreation($data);
        } catch (ValidationException $e) {
            Notification::make()
                ->title('Chevauchement détecté')
                ->body('Ce médecin a déjà une disponibilité sur ce créneau.')
                ->danger()
                ->send();

            throw $e;
        }
    }
}
