<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeGroup extends Model
{
    use HasFactory;

    protected $table = 'employee_group';

    protected $fillable = [
        'id',
        'code',
        'basic_salary'
    ];
}
