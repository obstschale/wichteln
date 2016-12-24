<?php

namespace App\Policies;

use App\User;
use App\Group;
use Illuminate\Auth\Access\HandlesAuthorization;

class GroupPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the group.
     *
     * @param  \App\User  $user
     * @param  \App\Group  $group
     * @return mixed
     */
    public function view(User $user, Group $group)
    {
        return $user->belongsToGroup($group);
    }

    /**
     * Determine whether the user can create groups.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the group.
     *
     * @param  \App\User  $user
     * @param  \App\Group  $group
     * @return mixed
     */
    public function update(User $user, Group $group)
    {
        return $user->isAdminInGroup($group);
    }

    /**
     * Determine whether the user can delete the group.
     *
     * @param  \App\User  $user
     * @param  \App\Group  $group
     * @return mixed
     */
    public function delete(User $user, Group $group)
    {
        return $user->isAdminInGroup($group);
    }

    /**
     * Determine whether the user can view the member(s) of the group.
     *
     * @param User $user
     * @param Group $group
     * @return bool
     */
    public function viewMembers(User $user, Group $group)
    {
        return $user->belongsToGroup($group);
    }

    /**
     * Determine whether the user can create new members of the group.
     *
     * @param User $user
     * @param Group $group
     * @return mixed
     */
    public function createMember(User $user, Group $group)
    {
        return $user->isAdminInGroup($group);
    }

    /**
     * Determine whether the user can update members of the group.
     *
     * @param User $user
     * @param Group $group
     * @param User $member
     * @return bool
     */
    public function updateMember(User $user, Group $group, User $member)
    {
        // Member to update is not part of group
        if (! $member->belongsToGroup($group)) {
            return false;
        }

        // User is admin in group and may update member
        if ($user->isAdminInGroup($group)) {
            return true;
        }

        // User who makes request wants to update his record
        if ($user == $member) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete members of the group.
     *
     * @param User $user
     * @param Group $group
     * @return mixed
     */
    public function deleteMember(User $user, Group $group)
    {
        return $user->isAdminInGroup($group);
    }
}
