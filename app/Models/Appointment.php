<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'lawyer_id', 'appointment_date', 'status'];

    public function user() {
         return $this->belongsTo(User::class);
         }
    public function lawyer() {
         return $this->belongsTo(Lawyer::class); 
        }
}

