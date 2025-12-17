<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class ShiftInvitation extends Model
{
    use LogsActivity;
   protected $fillable = ['shift_id', 'worker_id', 'facility_id', 'status', 'token', 'expires_at'];

    public function shift()
    {
        return $this->belongsTo(Shift::class);
    }

    public function worker()
    {
        return $this->belongsTo(User::class, 'worker_id');
    }

    public function facility()
    {
        return $this->belongsTo(User::class, 'facility_id');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()                // log all fields
            ->logOnlyDirty()          // log only changed values
            ->useLogName('shift invitation'); // custom log name
    }
}
