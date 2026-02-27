<?php

namespace App\Filament\Resources\DoctorResource\Pages;

use Illuminate\Support\Str;
use App\Filament\Resources\DoctorResource;
use App\Models\{Doctor, User};
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
class CreateDoctor extends CreateRecord
{
    protected static string $resource = DoctorResource::class;

    protected static ?string $title = 'Ajouter un nouveau médecin';

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    

 protected function afterCreate(): void
{
    $user = $this->record->user;

    if ($user) {
        $user->syncRoles(['doctor']);
        $user->update(['role' => 'doctor']); // ✅ sécurité double
    }
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

protected function mutateFormDataBeforeCreate(array $data): array
{
    // 1️⃣ Corriger establishment_id EN PREMIER
    if (!auth()->user()->hasRole('super_admin')) {
        $data['establishment_id'] = auth()->user()->establishment_id;
    }

    // 2️⃣ Créer le User avec role = 'doctor' directement
    $user = User::create([
        'first_name'       => $data['user']['first_name'],
        'last_name'        => $data['user']['last_name'],
        'email'            => $data['user']['email'],
        'password'         => $data['user']['password'],
        'establishment_id' => $data['establishment_id'],
        'role'             => 'doctor', // ✅ colonne role dans users
    ]);

    // 3️⃣ Spatie → model_has_roles
    $user->assignRole('doctor');

    // 4️⃣ Lier le doctor au user
    $data['user_id'] = $user->id;
    unset($data['user']);

    return $data;
}

}
