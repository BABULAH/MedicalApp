<?php

namespace App\Filament\Resources\SpecialiteResource\Pages;

use App\Filament\Resources\SpecialiteResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSpecialite extends EditRecord
{
    protected static string $resource = SpecialiteResource::class;

    /**
     * Actions affichées dans l’en-tête
     */
    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->label('Supprimer')
                ->successNotificationTitle('Spécialité supprimée avec succès'),
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
        return 'Spécialité mise à jour avec succès';
    }

    /**
     * Autorisation (optionnel)
     */
    protected function canEdit(): bool
    {
        return true; // condition si besoin (ex: auth()->user()->is_admin)
    }
}
