<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QrLocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_lokasi',
        'kode_lokasi',
        'latitude',
        'longitude',
        'radius',
        'status',
    ];

    public function qrTokens()
    {
        return $this->hasMany(QrToken::class, 'qr_location_id');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'qr_location_id');
    }
}