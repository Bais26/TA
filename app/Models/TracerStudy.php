<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TracerStudy extends Model
{
    use HasFactory;

    protected $table = 'tracer_studies';
    
    protected $fillable = [
        'id_alumni',
        'nama',
        'no_hp',
        'email',
        'tahun_lulus',
        'alamat',
        'status_pekerjaan',
        'nama_perusahaan',
        'jabatan',
        'alamat_pekerjaan',
        'gaji',
        'integritas',
        'keahlian',
        'kemampuan',
        'penguasaan',
        'komunikasi',
        'kerja_tim',
        'pengembangan',
        'cara_mencari_kerja',
        'jumlah_lamaran',
        'jumlah_panggilan',
        'alasan_tidak_bekerja',
        'rencana_cari_kerja',
        'nama_usaha',
        'bidang_usaha',
        'alamat_usaha',
        'nama_instansi',
        'jurusan',
        'jenjang',
        'tahun_masuk',
        'alamat_instansi',
        'relevansi_kurikulum',
        'saran'
    ];

    public function alumni()
    {
        return $this->belongsTo(Alumni::class, 'id_alumni');
    }
    
}