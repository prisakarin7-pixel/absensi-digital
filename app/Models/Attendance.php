<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $table = 'attendance';

    protected $fillable = [
        'student_id',
        'tanggal',
        'waktu',
        'status',
        'qr_location_id',
        'token',
        'latitude',
        'longitude',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function qrLocation()
    {
        return $this->belongsTo(QrLocation::class, 'qr_location_id');
    }
}