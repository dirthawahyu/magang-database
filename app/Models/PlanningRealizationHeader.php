<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlanningRealizationHeader extends Model
{
    use HasFactory;

    // Nama tabel yang terkait dengan model ini
    protected $table = 'planning_realization_header';

    // Kolom yang bisa diisi secara massal (mass assignable)
    protected $fillable = [
        'id_business_trip',
        'id_category_expenditure',
        'nominal',
        'type',
        'photo_proof',
        'keterangan',
    ];

    // Accessor untuk kolom 'type'
    public function getTypeAttribute($value)
    {
        return $value == 0 ? 'Realisasi' : 'Estimasi';
    }

    // Relasi dengan tabel 'business_trip'
    public function businessTrip()
    {
        return $this->belongsTo(BusinessTrip::class, 'id_business_trip');
    }

    // Relasi dengan tabel 'category_expenditure'
    public function categoryExpenditure()
    {
        return $this->belongsTo(CategoryExpenditure::class, 'id_category_expenditure');
    }
}