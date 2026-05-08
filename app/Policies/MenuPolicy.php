<?php

namespace App\Policies;

use App\Models\Menu;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class MenuPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole(['super_admin', 'admin']);
    }

    public function view(User $user, Menu $menu): bool
    {
        return $user->hasRole(['super_admin', 'admin']);
    }

    public function create(User $user): bool
    {
        return $user->hasRole(['super_admin', 'admin']);
    }

    public function update(User $user, Menu $menu): bool
    {
        return $user->hasRole(['super_admin', 'admin']) && !$menu->is_permanent;
    }

    public function delete(User $user, Menu $menu): bool
    {
        return $user->hasRole(['super_admin', 'admin']) && !$menu->is_permanent;
    }

    public function restore(User $user, Menu $menu): bool
    {
        return $user->hasRole(['super_admin', 'admin']);
    }

    public function forceDelete(User $user, Menu $menu): bool
    {
        return $user->hasRole('super_admin');
    }
}
