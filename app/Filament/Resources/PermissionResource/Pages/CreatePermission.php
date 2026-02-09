<?php

namespace App\Filament\Resources\PermissionResource\Pages;

use App\Filament\Resources\PermissionResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePermission extends CreateRecord
{
    protected static string $resource = PermissionResource::class;
    protected static ?string $title = 'Ajouter une permission';
    protected static ?string $navigationLabel = 'Ajouter une permission';
    protected static ?string $navigationIcon = 'heroicon-o-plus';

    

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('back')
                ->label('Retour')
                ->url($this->getResource()::getUrl('index'))
                ->icon('heroicon-o-arrow-left'),
        ];
    }

    public static function canAccess(array $parameters = []): bool
     {
          return auth()->user()?->hasRole('super_admin');
     }


}
