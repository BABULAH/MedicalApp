<?php

namespace App\Filament\Resources\SpecialiteResource\Pages;

use App\Filament\Resources\SpecialiteResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateSpecialite extends CreateRecord
{
    protected static string $resource = SpecialiteResource::class;

    /**
     * Redirection après création
     */
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    /**
     * Message de succès personnalisé
     */
    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Spécialité créée avec succès';
    }

    /**
     * Autorisation (optionnel)
     */
    protected function canCreate(): bool
    {
        return true; // mettre une condition si besoin (ex: auth()->user()->is_admin)
    }
}
