<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;
use Filament\Actions;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;
   protected static ?string $title = 'Créer un nouvel utilisateur';

    /**
     * Redirection après création
     */
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


    

    /**
     * Message de succès personnalisé
     */
    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->title('Utilisateur créé')
            ->body('Le compte utilisateur a été créé avec succès.')
            ->success();
    }
}
