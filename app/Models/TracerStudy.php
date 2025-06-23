<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TracerStudy extends Model
{
    use HasFactory;

    protected $table = 'tracerstudy';

    protected $fillable = [
        'id_alumni',
        'tanggal_isi',
        'bekerja',
        'nama_perusahaan',
        'jabatan',
        'alamat_pekerjaan',
        'status_kerja',
        'relevansi_pekerjaan',
        'pekerjaan',
        'gaji',
        'saran',
    ];

    protected $casts = [
        'tanggal_isi' => 'date',
        'gaji' => 'decimal:2',
    ];

    // Relasi ke model Alumni
    public function alumni()
    {
        return $this->belongsTo(Alumni::class, 'id_alumni');
    }
}