<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;
    use LogsActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'specialities' => 'array',
        ];
    }

    public function facilityDetail()
    {
        return $this->hasOne(FacilityDetail::class, 'facility_id');
    }
    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    public function claimShifts()
    {
        return $this->hasOne(ClaimShift::class, 'user_id');
    }

    public function activeShifts()
    {
        return $this->hasMany(Shift::class, 'user_id')->where('status', 1);
    }

    public function shifts()
    {
        return $this->hasMany(Shift::class, 'user_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'user_id');
    }

    public function workerReviews()
    {
        return $this->hasMany(Review::class, 'worker_id');
    }

    public function facilityReviews()
    {
        return $this->hasMany(Review::class, 'facility_id');
    }

    public function bankAccount()
    {
        return $this->hasOne(BankAccount::class, 'user_id');
    }
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()                // log all fields
            ->logOnlyDirty()          // log only changed values
            ->useLogName('user'); // custom log name
    }
}
