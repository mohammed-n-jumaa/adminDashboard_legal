<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory;

    protected $fillable = ['lawyer_id', 'user_id', 'rating', 'feedback'];

    public function user() {
         return $this->belongsTo(User::class); 
        }
    public function lawyer() {
         return $this->belongsTo(Lawyer::class); 
        }
}