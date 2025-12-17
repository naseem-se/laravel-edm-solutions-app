<?php

namespace App\Models;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use LogsActivity;
    protected $fillable = ['worker_id', 'facility_id', 'comment', 'rating'];

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
            ->useLogName('review'); // custom log name
    }
}
