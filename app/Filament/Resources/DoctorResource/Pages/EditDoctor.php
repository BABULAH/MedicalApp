<?php

namespace App\Filament\Resources\DoctorResource\Pages;

use App\Filament\Resources\DoctorResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Auth\Access\AuthorizationException;
use Filament\Notifications\Notification;

class EditDoctor extends EditRecord
{
    protected static string $resource = DoctorResource::class;

    protected static ?string $title = 'Modifier le médecin';

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('back')
                ->label('Retour à la liste des médecins')
                ->url($this->getResource()::getUrl('index'))
                ->icon('heroicon-o-arrow-left'),
        ];
    }

    protected function authorizeAccess(): void
    {
        try {
            parent::authorizeAccess();
        } catch (AuthorizationException $e) {

            Notification::make()
                ->title('Accès refusé')
                ->body($e->getMessage() ?: 'Action non autorisée.')
                ->danger()
                ->send();

            redirect()->route('filament.admin.resources.doctors.index')->send();
            exit;
        }
    }

    
}
