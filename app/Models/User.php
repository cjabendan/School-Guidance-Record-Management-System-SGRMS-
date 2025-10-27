<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Laravel\Fortify\TwoFactorAuthenticatable;


/**
 * @property \Illuminate\Database\Eloquent\Relations\BelongsToMany $blockedBy
 * @property \Illuminate\Database\Eloquent\Relations\BelongsToMany $blocks
 */

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'first_name',
        'middle_name',
        'last_name',
        'suffix',
        'email',
        'google_id',
        'google_email',
        'google_token',
        'google_refresh_token',
        'contact_num',
        'sex',
        'bod',
        'address',
        'profile_image',
        'password',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'two_factor_confirmed_at',
        'role',
        'profile_image',
        'status',
        'activation_token',
        'activation_token_expires_at',
    ];

    protected $hidden = [
        'password',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'two_factor_confirmed_at',
        'google_token',
        'google_refresh_token',
        'remember_token',
        'activation_token',
        'login_token',
    ];


    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->take(2)
            ->map(fn($word) => Str::substr($word, 0, 1))
            ->implode('');
    }

    public function student()
    {
        return $this->hasOne(Student::class, 'user_id');
    }

    public function counselor()
    {
        return $this->hasOne(Counselor::class, 'user_id');
    }

    public function parentProfile()
    {

        return $this->hasOne(ParentModel::class, 'user_id');
    }

    public function admin()
    {
        return $this->hasOne(Admins::class, 'user_id');
    }

    public function lastMessage()
    {
        return $this->hasOne(ChatMessage::class, 'sender_id')
            ->orWhere('receiver_id', $this->id)
            ->latest();
    }

    public function blocks()
    {
        return $this->belongsToMany(User::class, 'user_blocks', 'blocker_id', 'blocked_id');
    }

    public function blockedBy()
    {
        return $this->belongsToMany(User::class, 'user_blocks', 'blocked_id', 'blocker_id');
    }

    protected function isOnline(): Attribute
    {
        return Attribute::make(
            get: function () {
                
                $activity_threshold_minutes = 5;

            
                $threshold_timestamp = Carbon::now()->subMinutes($activity_threshold_minutes)->getTimestamp();

             
                return DB::table('sessions')
                 
                    ->where('user_id', $this->id)
                 
                    ->where('last_activity', '>=', $threshold_timestamp)
                    ->exists();
            },
        );
    }
}
