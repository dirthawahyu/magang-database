<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayrollDetail extends Model
{
    use HasFactory;

    protected $table = 'payroll_line';

    protected $fillable = [
        'id_payroll_header',
        'id_master_category',
        'nominal',
        'note',
    ];

    public function masterCategory()
    {
        return $this->belongsTo(MasterCategory::class, 'id_master_category');
    }

    public function payroll()
    {
        return $this->belongsTo(Payroll::class, 'id_payroll_header');
    }
}
