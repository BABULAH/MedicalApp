<?php

namespace App\Filament\Resources\EstablishmentResource\Pages;

use App\Filament\Resources\EstablishmentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEstablishment extends EditRecord
{
    protected static string $resource = EstablishmentResource::class;

    /**
     * Actions affichées dans l’en-tête
     */
    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->label('Supprimer')
                ->successNotificationTitle('Établissement supprimé avec succès'),
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
     * Message de succès après mise à jour
     */
    protected function getSavedNotificationTitle(): ?string
    {
        return 'Établissement mis à jour avec succès';
    }

    /**
     * Autorisation (optionnel)
     */
    protected function canEdit(): bool
    {
        return true; // condition si besoin
    }
}
