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
        'name'
    ];

    // public function leaves()
    // {
    //     return $this->hasMany(Leave::class, 'id_master_category');
    // }

    public function payroll()
    {
        return $this->hasMany(Payroll::class, 'id_master_category');
    }

}