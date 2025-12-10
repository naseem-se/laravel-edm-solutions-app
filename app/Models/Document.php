<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Document extends Model
{
    use HasFactory;
    protected $table = 'documents';
    use LogsActivity;

    protected $fillable = [
        'user_id',
        'document',
        'type',
        'status',
    ];

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function scopeExpired($query)
    {
        return $query->where('created_at', '<', now()->subYear());
    }

    public function scopeExpiringsoon($query)
    {
        return $query->where('created_at', '>', now()->subYear())
                    ->where('created_at', '<', now()->subYear()->addDays(50));
    }

    public function scopeActive($query)
    {
        return $query->where('created_at', '>', now()->subYear()->addDays(50));
    }
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()                // log all fields
            ->logOnlyDirty()          // log only changed values
            ->useLogName('documents'); // custom log name
    }
}
