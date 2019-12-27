<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Statistic extends Model
{
    public const ACCOUNTS = 'accounts';

    protected $fillable = [
        'name',
        'count'
    ];

    public static function accounts(): self {
        $latest = self::latest()->first();

        return $latest ?? self::create(['name' => self::ACCOUNTS, 'count' => 0]);
    }
}
