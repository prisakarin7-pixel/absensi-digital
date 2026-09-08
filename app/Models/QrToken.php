<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QrToken extends Model
{
    use HasFactory;

    protected $fillable = [
        'qr_location_id',
        'token',
        'tanggal',
        'waktu_mulai',
        'waktu_berakhir',
        'status',
    ];

    public function qrLocation()
    {
        return $this->belongsTo(QrLocation::class, 'qr_location_id');
    }
}