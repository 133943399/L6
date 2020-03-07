<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProductPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function before($user, $ability)
    {
        return true;
    }

    public function viewAny(User $user)
    {
    }

    public function view(User $user)
    {
    }

    public function create(User $user)
    {
    }

    public function update(User $user)
    {
    }

    public function delete(User $user)
    {
        return $user->group == 0;
    }
}
