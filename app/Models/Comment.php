<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['consultation_id', 'user_id', 'lawyer_id', 'content'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function lawyer()
    {
        return $this->belongsTo(User::class, 'lawyer_id');
    }

    public function consultation()
    {
        return $this->belongsTo(Consultation::class);
    }
}
