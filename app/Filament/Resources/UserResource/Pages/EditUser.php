<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected static ?string $title = 'Modifier l\'utilisateur';

    protected function getRedirectUrl(): string
    {
        return static::$resource::getUrl('index');
    }
    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('back')
                ->label('Retour à la liste des utilisateurs')
                ->url($this->getResource()::getUrl('index'))
                ->icon('heroicon-o-arrow-left'),
        ];
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Utilisateur mis à jour';
    }
}
