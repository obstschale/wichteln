<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Statistic extends Model
{
    public const ACCOUNTS = 'accounts';
    public const CREATED_GROUPS = 'created_groups';

    protected $fillable = [
        'name',
        'count'
    ];

    private static  function latest(string $type): self {
        $latest = self::where('name', '=', $type)->latest()->first();
        return $latest ?? self::create(['name' => $type, 'count' => 0]);
    }

    public static function accounts(): self {
        return self::latest(self::ACCOUNTS);
    }

    public static function addCreatedGroup(): void {
        $latest = self::latest(self::CREATED_GROUPS);

        if (Carbon::now()->isSameMonth($latest->created_at)) {
          $latest->count += 1;
          $latest->save();
        } else {
          $latest = new Statistic([
            'name' => self::CREATED_GROUPS,
            'count' => 1,
          ]);
        }

        $latest->save();
    }
}
