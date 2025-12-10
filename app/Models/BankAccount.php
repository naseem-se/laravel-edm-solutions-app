<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BankAccount extends Model
{
    use HasFactory;
    use LogsActivity;
    protected $table = 'bank_accounts';
    protected $fillable = [
        'user_id',
        'bank_name',
        'account_holder_name',
        'account_number',
        'routing_number',
    ];

    protected $guarded = [];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()                // log all fields
            ->logOnlyDirty()          // log only changed values
            ->useLogName('bank_accounts'); // custom log name
    }
}
