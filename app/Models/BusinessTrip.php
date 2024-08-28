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
        'id_company_city',
        'note',
        'photo_document',
        'status',
        'phone_number',
        'start_date',
        'end_date',
        'extend_day',
    ];

    // Relasi dengan model CompanyCity
    public function companyCity()
    {
        return $this->belongsTo(CompanyCity::class, 'id_company_city');
    }

    // Relasi many-to-many dengan model User
    public function users()
    {
        return $this->belongsToMany(User::class, 'trip_detail', 'id_business_trip', 'id_user');
    }
}
