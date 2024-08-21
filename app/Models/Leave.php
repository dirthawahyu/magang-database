<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Leave extends Model
{
    use HasFactory;

    protected $table = 'leave';

    protected $fillable = [
        'id_user',
        'id_master_category',
        'reason_for_leave',
        'start_date',
        'end_date',
        'status',
    ];

    // Relasi ke model User
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    // Relasi ke model MasterCategory
    public function leaveCategory()
    {
        return $this->belongsTo(MasterCategory::class, 'id_leave_category');
    }
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'id_user', 'id');
    }
}