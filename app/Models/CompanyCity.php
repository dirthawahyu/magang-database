<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyCity extends Model
{
    use HasFactory;

    // Nama tabel jika tidak sesuai dengan penamaan default
    protected $table = 'company_city';

    // Kolom yang dapat diisi secara massal
    protected $fillable = [
        'id_company',
        'id_city',
        'address',
        'pic',
        'pic_role',
        'pic_phone'
    ];

    // Contoh relasi dengan model lain
    public function company()
    {
        return $this->belongsTo(Company::class, 'id_company');
    }

    public function city()
    {
        return $this->belongsTo(City::class, 'id_city');
    }
}
