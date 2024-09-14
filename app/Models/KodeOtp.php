<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KodeOtp extends Model
{
    use HasFactory;

    protected $table = 'kode_otp';
    protected $fillable = ['id_user', 'kode_otp', 'expired_at'];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}