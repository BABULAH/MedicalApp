<?php

namespace App\Filament\Resources\EstablishmentResource\Pages;

use App\Filament\Resources\EstablishmentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEstablishments extends ListRecords
{
    protected static string $resource = EstablishmentResource::class;

    /**
     * Actions affichées dans l’en-tête
     */
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Ajouter un établissement'),
        ];
    }

    /**
     * Titre de la page (optionnel)
     */
    // protected function getTitle(): string
    // {
    //     return 'Liste des établissements';
    // }
}
