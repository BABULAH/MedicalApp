<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Doctor;
use Illuminate\Auth\Access\Response;

class DoctorPolicy
{
    // public function viewAny(User $user): Response
    // {
    //     return $user->hasAnyRole(['super_admin', 'admin'])
    //         ? Response::allow()
    //         : Response::deny('Vous n’avez pas accès aux médecins.');
    // }

    // public function view(User $user, Doctor $doctor): Response
    // {
    //     return $user->hasRole('super_admin')
    //         || $doctor->establishment_id === $user->establishment_id
    //         ? Response::allow()
    //         : Response::deny('Ce médecin n’appartient pas à votre établissement.');
    // }

    // public function create(User $user): Response
    // {
    //     return $user->hasAnyRole(['super_admin', 'admin'])
    //         ? Response::allow()
    //         : Response::deny('Création non autorisée.');
    // }

    // public function update(User $user, Doctor $doctor): Response
    // {
    //     return $this->view($user, $doctor);
    // }

    // public function delete(User $user, Doctor $doctor): Response
    // {
    //     return $this->view($user, $doctor);
    // }
}
