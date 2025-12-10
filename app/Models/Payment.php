<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Payment extends Model
{
    use HasFactory;
    use LogsActivity;



    protected $fillable = [
        'user_id',
        'recipient_id',
        'shift_id',
        'payment_intent_id',
        'payment_method_id',
        'transfer_id',
        'amount',
        'platform_fee',
        'recipient_amount',
        'currency',
        'status',
        'transfer_status',
        'description',
        'metadata',
        'paid_at',
    ];

    protected $casts = [
        'metadata' => 'array',
        'paid_at' => 'datetime',
        'amount' => 'decimal:2',
        'platform_fee' => 'decimal:2',
        'recipient_amount' => 'decimal:2',
    ];

    public function payer()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function recipient()
    {
        return $this->belongsTo(User::class, 'recipient_id');
    }

    public function shift()
    {
        return $this->belongsTo(Shift::class);
    }
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()                // log all fields
            ->logOnlyDirty()          // log only changed values
            ->useLogName('payments'); // custom log name
    }
}
