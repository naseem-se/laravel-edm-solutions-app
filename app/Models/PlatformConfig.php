<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlatformConfig extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $guarded = [];

    public static function getValue($key, $default = null)
    {
        // Fetch the first row
        $config = self::first();

        if (!$config) {
            return $default;
        }

        // Check if the column exists dynamically
        if (!array_key_exists($key, $config->toArray())) {
            return $default;
        }

        return $config->$key;
    }
}
