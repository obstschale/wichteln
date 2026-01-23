<?php

namespace App;

use App\Mail\InformAboutDeletion;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class Group extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'date',
        'status',
        'join_token',
    ];

    /**
     * Get all users of group.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany('App\User')->withPivot('status', 'buddy_id', 'wishlist', 'is_admin');
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
        return $this
            ->belongsToMany('App\User')
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

    public function scopeNotInformed(Builder $query): Builder
    {
        return $query->where('isInformedDeletion', '=', 0);
    }

    public function scopeForDeletion(Builder $query): Builder
    {
        return $query->where('isInformedDeletion', '=', 1)->whereDate('updated_at', '<', Carbon::now()->subWeeks(1));
    }

    public function scopeOlderThan(Builder $query, int $value, string $unit): Builder
    {
        return $query->whereDate('updated_at', '<', Carbon::now()->subUnit($unit, $value));
    }

    public function scopeStarted(Builder $query): Builder
    {
        return $query->where('status', 'started');
    }

    public function scopeCreated(Builder $query): Builder
    {
        return $query->where('status', 'created');
    }

    public function scopeDateReached(Builder $query): Builder
    {
        return $query->whereDate('date', '<', Carbon::now());
    }

    public function informAboutDeletion(): void
    {
        $admin = $this->users->filter(function (User $user) {
            return $user->isAdminInGroup($this);
        })->first();

        if ($admin instanceof User) {
            Mail::to($admin)->queue(new InformAboutDeletion($admin, $this));
        }

        $this->isInformedDeletion = true;
        $this->save();
    }

    public function generateJoinToken(): string
    {
        $this->join_token = Str::random(32);
        $this->save();

        return $this->join_token;
    }

    public function joinUrl(): ?string
    {
        if (!$this->join_token) {
            return null;
        }

        return route('join.form', $this->join_token);
    }

    public function invitedUsers()
    {
        return $this
            ->belongsToMany('App\User')
            ->wherePivot('status', 'invited')
            ->withPivot('status', 'buddy_id', 'wishlist');
    }
}
