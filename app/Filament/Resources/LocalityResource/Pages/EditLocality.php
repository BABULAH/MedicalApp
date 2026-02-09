<?php

namespace App\Filament\Resources\LocalityResource\Pages;

use App\Filament\Resources\LocalityResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLocality extends EditRecord
{
    protected static string $resource = LocalityResource::class;

    /**
     * Actions affichées dans l’en-tête
     */
    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->label('Supprimer')
                ->successNotificationTitle('Localité supprimée avec succès'),
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
     * Message de succès après édition
     */
    protected function getSavedNotificationTitle(): ?string
    {
        return 'Localité mise à jour avec succès';
    }

    /**
     * Autorisation (optionnel)
     */
    protected function canEdit(): bool
    {
        return true; // condition si besoin (ex: auth()->user()->is_admin)
    }
}
