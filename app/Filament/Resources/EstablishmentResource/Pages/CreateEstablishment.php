<?php

namespace App\Filament\Resources\EstablishmentResource\Pages;

use App\Filament\Resources\EstablishmentResource;
use Filament\Resources\Pages\CreateRecord;

class CreateEstablishment extends CreateRecord
{
    protected static string $resource = EstablishmentResource::class;

    /**
     * Redirection après création
     */
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    /**
     * Message de succès
     */
    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Établissement créé avec succès';
    }

    /**
     * Autorisation (optionnel)
     */
    protected function canCreate(): bool
    {
        return true; // condition si besoin
    }
}
