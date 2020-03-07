<?php

namespace App\Policies;

use App\User;
use App\Shop;
use Illuminate\Auth\Access\HandlesAuthorization;

class ShopPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    public function before($user, $ability)
    {
        return true;
    }

    public function viewAny(User $user)
    {
        return $user->group >= 0;
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
