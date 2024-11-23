<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * 
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone_number',
        'role',
        'profile_picture',
        'date_of_birth',
    ];

    /**
     * 
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * 
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'date_of_birth' => 'date',
        'deleted_at' => 'datetime',
    ];

    /**
     * 
     */
    public function consultations()
    {
        return $this->hasMany(Consultation::class);
    }

    /**
     * 
     */
    public function feedbacks()
    {
        return $this->hasMany(Feedback::class);
    }
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
    /**
     * 
     */
    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    /**
     * 
     */
    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    /**
     * 
     *
     * @return string
     */
    public function getProfilePictureUrlAttribute()
    {
        return $this->profile_picture
            ? asset('storage/' . $this->profile_picture)
            : asset('/images/default-profile.png');
    }

    /**
     *
     *
     * @return bool
     */
    public function isDeleted()
    {
        return $this->trashed();
    }
}
