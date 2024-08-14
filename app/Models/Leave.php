<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Leave extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang digunakan oleh model.
     * Secara default, Laravel akan menggunakan nama tabel plural (misalnya "leaves").
     * Jika Anda ingin menggunakan nama tabel custom, Anda bisa mendefinisikannya di sini.
     */
    protected $table = 'leaves';

    /**
     * Jika tabel tidak memiliki kolom `created_at` dan `updated_at`.
     * Atur menjadi `false` jika tidak menggunakan kolom timestamps.
     */
    public $timestamps = false;

    /**
     * Kolom yang bisa diisi secara massal (mass assignable).
     * Daftar semua kolom yang dapat diisi oleh model ini.
     */
    protected $fillable = [
        'id_user',
        'id_category',
        'reason_for_leave',
        'start_date',
        'end_date',
        'status'
    ];

    /**
     * Definisikan relasi ke model User.
     * Leave "belongs to" User.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    /**
     * Definisikan relasi ke model Category.
     * Leave "belongs to" Category.
     */
    public function category()
    {
        return $this->belongsTo(Category::class, 'id_category');
    }
}
