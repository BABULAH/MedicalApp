<?php

namespace App\Filament\Resources\PermissionResource\Pages;

use App\Filament\Resources\PermissionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;

class EditPermission extends EditRecord
{
    protected static string $resource = PermissionResource::class;
    protected static ?string $title = 'Modifier une permission';
    protected static ?string $navigationLabel = 'Modifier une permission';
    protected static ?string $navigationIcon = 'heroicon-o-pencil';

    


    public static function canAccess(array $parameters = []): bool
    {
        return auth()->user()?->hasRole('super_admin');
    }

    public function getTitle(): string
    {
        return 'Modifier une permission';
    }

    protected function beforeDelete(): void
    {
        if (in_array($this->record->name, ['manage_users', 'manage_doctors', 'manage_settings'])) {
            Notification::make()
                ->title('Action interdite')
                ->body('Cette permission ne peut pas être supprimée.')
                ->danger()
                ->send();

            $this->halt();
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
