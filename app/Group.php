<?php

namespace App;

use App\Mail\InformAboutDeletion;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;

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

    public function scopeNotInformed(Builder $query): Builder {
        return $query->where('isInformedDeletion', '=', 0);
    }

    public function scopeOlderThan(Builder $query, int $value, string $unit): Builder {
        return $query->whereDate('updated_at', '<', Carbon::now()->subUnit($unit, $value));
    }

    public function scopeStarted(Builder $query): Builder {
        return $query->where('status', 'started');
    }

    public function scopeCreated(Builder $query): Builder {
        return $query->where('status', 'created');
    }

    public function scopeDateReached(Builder $query): Builder {
        return $query->whereDate('date', '<', Carbon::now());
    }

    public function informAboutDeletion(): void {
        $admin = $this->users->filter(function(User $user) {
          return $user->isAdminInGroup($this);
        })->first();

        if ($admin instanceof User) {
          Mail::to($admin)->queue(new InformAboutDeletion($admin, $this));
        }

        $this->isInformedDeletion = true;
        $this->save();
    }
}
