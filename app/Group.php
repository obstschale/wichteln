<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'date',
        'status',
    ];


    /**
     * Get all users of group.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany('App\User')
            ->withPivot('status', 'buddy_id', 'wishlist');
    }

    /**
     * See if the group started the game.
     *
     * @return bool
     */
    public function started()
    {
        return $this->status === 'started';
    }

    /**
     * Get all users who approved participation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function approvedUsers()
    {
        return $this->belongsToMany('App\User')
            ->wherePivot('status', 'approved')
            ->withPivot('status', 'buddy_id', 'wishlist');
    }


    /**
     * @return User
     */
    public function admin()
    {
        return $this->users()->wherePivot('is_admin', 1)->first();
    }
}
