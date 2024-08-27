<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessTrip extends Model
{
    use HasFactory;

    // Nama tabel
    protected $table = 'business_trip';

    // Kolom yang dapat diisi secara massal
    protected $fillable = [
        'id_company',
        'note',
        'photo_document',
        'status',
        'phone_number',
        'start_date',
        'end_date',
        'extend_day',
        'pic_company',
        'pic_role',
    ];

    // Relasi dengan model lain
    public function company()
    {
        return $this->belongsTo(Company::class, 'id_company');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'trip_detail', 'id_business_trip', 'id_user');
    }

}