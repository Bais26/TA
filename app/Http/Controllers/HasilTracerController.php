<?php

namespace App\Http\Controllers;

use App\Models\Alumni;
use App\Models\tracer_pengguna;
use App\Models\User;
use Illuminate\Http\Request;

class HasilTracerController extends Controller
{
    public function index()
    {
        // Mapping string ke angka
        $nilaiMap = [
            'tidak_baik'   => 1,
            'kurang_baik'  => 2,
            'cukup'        => 3,
            'baik'         => 4,
            'sangat_baik'  => 5,
        ];

        // Indikator
        $indikator = [
            'integritas'   => 'Integritas',
            'keahlian'     => 'Keahlian',
            'kemampuan'    => 'Kemampuan',
            'penguasaan'   => 'Penguasaan',
            'komunikasi'   => 'Komunikasi',
            'kerja_tim'    => 'Kerja Tim',
            'pengembangan' => 'Pengembangan Diri'
        ];

        // Statistik alumni (asumsi tabel user = semua alumni)
        $totalAlumni   = Alumni ::count();
        $sudahMengisi  = tracer_pengguna::count();
        $belumMengisi  = $totalAlumni - $sudahMengisi;

        // Hasil rekap tiap indikator
        $hasil = [];
        foreach ($indikator as $field => $label) {
            $data = tracer_pengguna::select($field)->get()->pluck($field)->map(function ($v) use ($nilaiMap) {
                return $nilaiMap[strtolower($v)] ?? 0;
            });

            // Rekap jumlah per kategori
            $rekap = [
                1 => $data->where(fn($v) => $v == 1)->count(),
                2 => $data->where(fn($v) => $v == 2)->count(),
                3 => $data->where(fn($v) => $v == 3)->count(),
                4 => $data->where(fn($v) => $v == 4)->count(),
                5 => $data->where(fn($v) => $v == 5)->count(),
            ];

            $jumlahResponden = $data->filter()->count(); // Hindari 0
            $rataRata = $jumlahResponden ? round($data->sum() / $jumlahResponden, 2) : 0;
            $keterangan = $this->getKategoriNilai($rataRata);

            $hasil[] = [
                'label' => $label,
                'rekap' => $rekap,
                'jumlah_responden' => $jumlahResponden,
                'rata_rata' => $rataRata,
                'keterangan' => $keterangan,
            ];
        }

        return view('tracer.hasil', compact('totalAlumni', 'sudahMengisi', 'belumMengisi', 'hasil'));
    }

    // Fungsi keterangan kategori nilai
    private function getKategoriNilai($nilai)
    {
        if ($nilai >= 4.5) return 'Sangat Baik';
        if ($nilai >= 3.5) return 'Baik';
        if ($nilai >= 2.5) return 'Cukup';
        if ($nilai >= 1.5) return 'Kurang Baik';
        if ($nilai > 0)    return 'Tidak Baik';
        return '-';
    }
}
