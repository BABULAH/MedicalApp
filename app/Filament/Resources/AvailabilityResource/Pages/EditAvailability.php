<?php

namespace App\Filament\Resources\AvailabilityResource\Pages;

use App\Enums\DayOfWeek;
use App\Filament\Resources\AvailabilityResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;
use Illuminate\Validation\ValidationException;
use App\Services\AvailabilityService;

class EditAvailability extends EditRecord
{
    protected static string $resource = AvailabilityResource::class;

    /**
     * Muter les données avant l'enregistrement
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        try {
            AvailabilityService::checkOverlap(
                $data['doctor_id'],
                $data['day_of_week'],
                $data['start_time'],
                $data['end_time'],
                $this->record->id // exclut l'enregistrement en cours
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
     * Actions affichées dans l’en-tête
     */
    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    /**
     * Redirection après modification
     */
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    /**
     * Message de succès personnalisé
     */
    protected function getSavedNotificationTitle(): ?string
    {
        return 'Disponibilité mise à jour avec succès';
    }

    /**
     * Optionnel : transformer l'état pour pré-remplir le formulaire
     * Ici, DayOfWeek stocke les valeurs en base, donc pas besoin de conversion
     */
    protected function mutateFormDataBeforeFill(array $data): array
    {
        // $data['day_of_week'] contient déjà 'monday', 'tuesday', etc.
        return $data;
    }
}
