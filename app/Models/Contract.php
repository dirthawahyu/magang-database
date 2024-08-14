<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    use HasFactory;

    protected $table = 'employment_contracts';

    protected $fillable = [
        'id_company',
        'id_employee_group',
        'id_user',
        'status_employee',
        'start_date',
        'end_date',
        'status',
    ];
}
