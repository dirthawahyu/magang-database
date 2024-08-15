<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    use HasFactory;

    protected $table = 'payroll_header';

    protected $fillable = [
        'id_employee',
        'id_master_category',
        'payroll_date',
        'net_amount',
    ];

    public function masterCategory()
    {
        return $this->belongsTo(MasterCategory::class, 'id_master_category');
    }
}