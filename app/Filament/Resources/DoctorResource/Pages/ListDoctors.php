<?php

namespace App\Filament\Resources\DoctorResource\Pages;

use App\Filament\Resources\DoctorResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDoctors extends ListRecords
{
    protected static string $resource = DoctorResource::class;


    protected static ?string $title = 'Liste des médecins';

    protected static ?string $navigationLabel = 'Médecins';

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $modelLabel = 'Médecin';

    protected static ?string $pluralModelLabel = 'Médecins';

    protected static ?string $navigationGroup = 'Gestion des médecins';

    protected static ?int $navigationSort = 1;

    protected static ?string $slug = 'medecins';

    protected static ?string $breadcrumb = 'Médecins';

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Ajouter un médecin'),
        ];
    }
}
