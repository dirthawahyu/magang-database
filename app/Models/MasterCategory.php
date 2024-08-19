<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterCategory extends Model
{
    use HasFactory;

    protected $table = 'master_category';

    protected $fillable = [
        'type',
        'name',
    ];

    public function payrolls()
    {
        return $this->hasMany(Payroll::class, 'id_master_category');
    }

    public function payrollDetails()
    {
        return $this->hasMany(PayrollDetail::class, 'id_master_category');
    }
}
