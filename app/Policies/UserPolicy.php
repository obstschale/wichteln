<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the user.
     *
     * @param User $authUser
     * @param User $user
     * @return mixed
     */
    public function view(User $authUser, User $user)
    {
        return $authUser == $user;
    }

    /**
     * Determine whether the user can create users.
     *
     * @param  User  $authUser
     * @return mixed
     */
    public function create(User $authUser)
    {
        return true;
    }

    /**
     * Determine whether the user can update the user.
     *
     * @param  User  $authUser
     * @param  User  $user
     * @return mixed
     */
    public function update(User $authUser, User $user)
    {
        return $authUser == $user;
    }

    /**
     * Determine whether the user can delete the user.
     *
     * @param  User  $authUser
     * @param  User  $user
     * @return mixed
     */
    public function delete(User $authUser, User $user)
    {
        return $authUser == $user;
    }
}
