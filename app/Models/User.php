<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'last_name',
        'middle_name',
        'email',
        'password',
        'phone_number',
        'password_changed',
    ];

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
            'password_changed' => 'boolean',
            'suspension_until' => 'datetime',
        ];
    }

    public function getFullNameAttribute()
{
    return "{$this->first_name} {$this->last_name}";
}
    public static function getPBENUser()
    {
        return self::where('email', 'pben@bpsu.edu.ph')->first();
    }

    /**
     * Check if the user is currently suspended
     */
    public function isSuspended(): bool
    {
        return $this->role === 'suspended' && 
               $this->suspension_until && 
               $this->suspension_until->isFuture();
    }

    /**
     * Check if the user's suspension has expired
     */
    public function suspensionExpired(): bool
    {
        return $this->role === 'suspended' && 
               $this->suspension_until && 
               $this->suspension_until->isPast();
    }

    /**
     * Suspend the user for a given duration
     */
    public function suspend(int $hours): void
    {
        $this->role = 'suspended';
        $this->suspension_until = now()->addHours($hours);
        $this->save();
    }

    /**
     * Lift the suspension if it has expired
     */
    public function liftExpiredSuspension(): void
    {
        if ($this->suspensionExpired()) {
            $this->role = 'student'; // or whatever the default role should be
            $this->suspension_until = null;
            $this->save();
        }
    }
}
