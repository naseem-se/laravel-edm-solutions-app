<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Shift extends Model
{
    use HasFactory;
    use LogsActivity;


    protected $table = 'shifts';

    protected $guarded = [];

    public function claimShift()
    {
        return $this->hasOne(ClaimShift::class, 'shift_id');
    }


    public function confirmVerification()
    {
        return $this->hasOne(ConfirmVerification::class, 'shift_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()                // log all fields
            ->logOnlyDirty()          // log only changed values
            ->useLogName('shifts'); // custom log name
    }
}
