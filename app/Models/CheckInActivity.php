<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CheckinActivity extends Model
{
    use HasFactory;

    protected $table = 'checkin_activity'; 

    protected $fillable = [
        'id_user',
        'time',
        'type',
        'status',
        'latitude',
        'longtitude',
    ];

  
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}