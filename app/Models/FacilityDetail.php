<?php

namespace App\Models;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

use Illuminate\Database\Eloquent\Model;

class FacilityDetail extends Model
{
     use LogsActivity;
    protected $table = 'facility_details';

    protected $fillable = [
        'facility_id',
        'type',
        'license_number',
        'accreditation',
        'tax_id',
        'total_beds',
        'total_dept',
        'description',
    ];

    public function facility()
    {
        return $this->belongsTo(User::class, 'facility_id');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()                // log all fields
            ->logOnlyDirty()          // log only changed values
            ->useLogName('facility details'); // custom log name
    }
}
