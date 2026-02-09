<?php

namespace App\Filament\Resources\PermissionResource\Pages;

use App\Filament\Resources\PermissionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPermissions extends ListRecords
{
    protected static string $resource = PermissionResource::class;
    protected static ?string $title = 'Permissions';
    protected static ?string $navigationLabel = 'Permissions';
    protected static ?string $navigationIcon = 'heroicon-o-key';

    public static function canAccess(array $parameters = []): bool
    {
        return auth()->user()?->hasRole('super_admin');
    }

    public function getTitle(): string
    {
        return 'Permissions';
    }

    


    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
