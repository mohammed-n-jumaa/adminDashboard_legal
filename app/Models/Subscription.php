<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = ['lawyer_id', 'plan', 'start_date', 'end_date', 'price'];

    public function lawyer()
    {
        return $this->belongsTo(Lawyer::class, 'lawyer_id');
    }
}
