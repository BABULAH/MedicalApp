<?php

namespace App\Filament\Resources\DoctorResource\Pages;

use App\Filament\Resources\DoctorResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Models\Doctor;
use App\Models\User;

use Illuminate\Support\Str;
class CreateDoctor extends CreateRecord
{
    protected static string $resource = DoctorResource::class;

    protected static ?string $title = 'Ajouter un nouveau médecin';

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    // protected function mutateFormDataBeforeCreate(array $data): array
    // {
    //     do {
    //         $registrationNumber = strtoupper(Str::random(12));
    //     } while (Doctor::where('registration_number', $registrationNumber)->exists());

    //     $data['registration_number'] = $registrationNumber;

    //     return $data;
    // }


    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('back')
                ->label('Retour à la liste des médecins')
                ->url($this->getResource()::getUrl('index'))
                ->icon('heroicon-o-arrow-left'),
        ];
    }

    protected  function mutateFormDataBeforeCreate(array $data): array
    {
        // 1️⃣ Créer le User
        $user = User::create([
            'first_name'       => $data['user']['first_name'],
            'last_name'        => $data['user']['last_name'],
            'email'            => $data['user']['email'],
            'password'         => $data['user']['password'],
            'establishment_id' => $data['establishment_id'],
        ]);

          if (! auth()->user()->hasRole('super_admin')) {
                $data['establishment_id'] = auth()->user()->establishment_id;
            }

        // 2️⃣ Attribuer le rôle doctor (Spatie)
        $user->assignRole('doctor');

        // 3️⃣ Lier le doctor au user
        $data['user_id'] = $user->id;

        unset($data['user']);

        return $data;
    }
}
