<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $table = 'employee';
    protected $fillable = [
        'id_user',
        'id_role',
        'id_employee_group',
        'id_company',
        'fcm_token',
        'status',
        'tax_status',
        'nip',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'id_company');
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

}