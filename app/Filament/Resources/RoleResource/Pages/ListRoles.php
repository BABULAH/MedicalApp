<?php

namespace App\Filament\Resources\RoleResource\Pages;

use App\Filament\Resources\RoleResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRoles extends ListRecords
{
    protected static string $resource = RoleResource::class;

    protected static ?string $title = 'Rôles';

    public static function canAccess(array $parameters = []): bool
    {
        return auth()->user()?->hasRole('super_admin');
    }



    public function getTitle(): string
    {
        return 'Rôles';
    }

    protected function getHeaderWidgets(): array
    {
        return [
            RoleResource\Widgets\RoleOverview::class,
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
