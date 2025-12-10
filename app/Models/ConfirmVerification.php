<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class ConfirmVerification extends Model
{
    use HasFactory;
    use LogsActivity;



    protected $table = 'confirm_verifications';

    protected $guarded = [];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()                // log all fields
            ->logOnlyDirty()          // log only changed values
            ->useLogName('confirm_verification'); // custom log name
    }
}
