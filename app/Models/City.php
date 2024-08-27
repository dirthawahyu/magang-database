<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;

    // Nama tabel jika tidak sesuai dengan penamaan default
    protected $table = 'city';

    // Kolom yang dapat diisi secara massal
    protected $fillable = [
        'name',
    ];

    // Relasi dengan model lain jika ada
    public function companyCities()
    {
        return $this->hasMany(CompanyCity::class, 'id_city');
    }
}

