<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'api_token',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',  'api_token',
    ];

    /**
     * Get groups, where this user is a member off.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function groups()
    {
        return $this->belongsToMany('App\Group')
                    ->withPivot('buddy_id', 'wishlist', 'token');
    }

    /**
     * Get buddy of user from specific group.
     *
     * @param Group $group
     * @return mixed
     */
    public function buddy(Group $group)
    {
        return $this->belongsToMany('App\Group')
            ->wherePivot('group_id', $group->id)
            ->withPivot('buddy_id')->first()->buddy;
    }

    public function saveBuddy(Group $group, $id)
    {
        DB::table('group_user')
            ->where('group_id', $group->id)
            ->where('user_id', $this->id)
            ->update(['buddy_id' => $id]);
    }

    /**
     * Policy-Like check to see if a user is part of a given group.
     *
     * @param Group $group
     * @return bool
     */
    public function belongsToGroup(Group $group)
    {
        $res = $this->groups()->where('id', $group->id)->get();

        if (count($res) > 0) {
            return true;
        }

        return false;
    }

    /**
     * Get pivot tables data for this user of a given group.
     *
     * @param Group $group
     * @return mixed
     */
    public function pivotDataFor(Group $group)
    {
        return DB::table('group_user')
            ->select('status', 'wishlist', 'is_admin', 'token')
            ->where('group_id', $group->id)
            ->where('user_id', $this->id)
            ->first();
    }

    /**
     * Get token for user of a group.
     *
     * @uses \App\User::pivotDataFor()
     * @param Group $group
     * @return mixed
     */
    public function approveToken(Group $group)
    {
        return $this->pivotDataFor($group)->token;
    }

    /**
     * Update approve token of this user for a given group.
     *
     * @param Group $group
     * @param $token
     */
    public function saveApproveToken(Group $group, $token)
    {
        DB::table('group_user')
            ->where('group_id', $group->id)
            ->where('user_id', $this->id)
            ->update(['token' => $token]);
    }

    /**
     * Get is_admin property of this user for a given group.
     *
     * @uses \App\User::pivotDataFor()
     * @param Group $group
     * @return mixed
     */
    public function isAdminInGroup(Group $group)
    {
        return $this->pivotDataFor($group) === null ? false: $this->pivotDataFor($group)->is_admin;
    }

    /**
     * Get status of this user for a given group.
     *
     * @uses \App\User::pivotDataFor()
     * @param Group $group
     * @return mixed
     */
    public function status(Group $group)
    {
        return $this->pivotDataFor($group)->status;
    }

    /**
     * Update status of this user for a given group.
     *
     * @param Group $group
     * @param $status
     */
    public function saveStatus(Group $group, $status)
    {
        DB::table('group_user')
            ->where('group_id', $group->id)
            ->where('user_id', $this->id)
            ->update(['status' => $status]);
    }

    /**
     * Get wishlist of this user for a given group.
     *
     * @uses \App\User::pivotDataFor()
     * @param Group $group
     * @return mixed
     */
    public function wishlist(Group $group)
    {
        return $this->pivotDataFor($group)->status;
    }

    /**
     * Update wishlist of this user for a given group.
     *
     * @param Group $group
     * @param $wishlist
     */
    public function saveWishlist(Group $group, $wishlist)
    {
        DB::table('group_user')
            ->where('group_id', $group->id)
            ->where('user_id', $this->id)
            ->update(['wishlist' => $wishlist]);
    }

}
