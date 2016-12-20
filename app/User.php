<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

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
        'stauts',
        'wishlist',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Get groups, where this user is a member off.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function groups()
    {
        return $this->belongsToMany('App\Group')->withPivot('buddy_id');
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
            ->withPivot('buddy_id')->first();
    }
}
