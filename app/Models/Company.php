<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    // Nama tabel jika tidak sesuai dengan penamaan default
    protected $table = 'company';

    // Kolom yang dapat diisi secara massal
    protected $fillable = [
        'name',
        'latitude',
        'longitude',
    ];

    // Relasi dengan model lain jika ada
    public function companyCities()
    {
        return $this->hasMany(CompanyCity::class, 'id_company');
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}