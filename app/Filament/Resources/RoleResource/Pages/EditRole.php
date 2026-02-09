<?php

namespace App\Filament\Resources\RoleResource\Pages;

use App\Filament\Resources\RoleResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;

class EditRole extends EditRecord
{
    protected static string $resource = RoleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    // <-- Ici : méthode beforeDelete() avec notification
    protected function beforeDelete(): void
    {
        if ($this->record->name === 'super_admin') {
            Notification::make()
                ->title('Action interdite')
                ->body('Le rôle super admin ne peut pas être supprimé.')
                ->danger()
                ->send();

            $this->halt(); // stop la suppression
        }
    }


    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

   public static function canAccess(array $parameters = []): bool
{
    return auth()->user()?->hasRole('super_admin');
}


    
}
