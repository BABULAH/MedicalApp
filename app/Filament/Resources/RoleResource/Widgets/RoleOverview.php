<?php

namespace App\Filament\Resources\RoleResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;
use Spatie\Permission\Models\Role;
use App\Models\User;

class RoleOverview extends BaseWidget
{
    protected function getCards(): array
    {
        return [
            Card::make('Total des rôles', Role::count()),
            Card::make('Super admins', User::role('super_admin')->count()),
            Card::make('Admins', User::role('admin')->count()),
            Card::make('Médecins', User::role('doctor')->count()),
            Card::make('Utilisateurs', User::role('user')->count()),
            Card::make('Total des utilisateurs', User::count()),
        ];
    }
}
