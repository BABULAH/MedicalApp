<?php

namespace App\Filament\Resources\LocalityResource\Pages;

use App\Filament\Resources\LocalityResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLocalities extends ListRecords
{
    protected static string $resource = LocalityResource::class;

    /**
     * Actions dans l’en-tête
     */
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Ajouter une localité'),
        ];
    }

    /**
     * Titre de la page (optionnel)
     */
    // protected function getTitle(): string
    // {
    //     return 'Liste des localités';
    // }
}
