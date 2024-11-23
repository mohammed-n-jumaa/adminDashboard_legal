<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lawyer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone_number',
        'password',
        'date_of_birth',
        'gender',
        'specialization',
        'profile_picture',
        'lawyer_certificate',
        'syndicate_card',
    ];
    

    protected $casts = [
        'date_of_birth' => 'date',
    ];

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function feedback()
    {
        return $this->hasMany(Feedback::class);
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function legalLibrary()
    {
        return $this->hasMany(LegalLibrary::class);
    }
}
