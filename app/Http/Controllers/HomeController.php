<?php

namespace App\Http\Controllers;

use App\Models\Alumni;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use App\Models\TracerStudy;

class HomeController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Data untuk grafik (semua role)
        $tahun = range(2021, 2025);
        $alumniData = $this->getAlumniData($tahun);
        $kuisonerData = $this->getKuisionerData($tahun);

        // Jika admin/superadmin
        if ($user && in_array($user->role, ['admin', 'superadmin'])) {
            $countMahasiswa = $this->getMahasiswaCount();
            $countDosen = $this->getDosenCount();
            $countAlumni = $this->getAlumniCount();
            $statistikAlumni = $this->getStatistikBekerja();

            return view('admin.admin-dashboard', compact(
                'countMahasiswa',
                'countDosen',
                'countAlumni',
                'statistikAlumni',
                'tahun',
                'alumniData',
                'kuisonerData'
            ));
        }

        // Jika alumni
        $alumni = Alumni::where('id_users', $user->id)->first();
        $hasFilledTracer = false;

        if ($alumni) {
            $hasFilledTracer = TracerStudy::where('id_alumni', $alumni->id)->exists();
        }

        // statusTracer: 'sudah' / 'belum' → dikirim ke Blade
        $statusTracer = $hasFilledTracer ? 'sudah' : 'belum';

        return view('main', compact('tahun', 'alumniData', 'kuisonerData', 'statusTracer'));
    }

    private function getMahasiswaCount()
    {
        $key = env('OASE_API_KEY');
        $count = 0;

        for ($tahun = 2020; $tahun <= 2025; $tahun++) {
            $res = Http::get('https://api.oase.poltektegal.ac.id/api/web/mahasiswa', [
                'key' => $key,
                'tahun_angkatan' => $tahun
            ]);

            if ($res->successful() && isset($res['data'])) {
                $count += count($res['data']);
            }
        }

        return $count;
    }

    private function getDosenCount()
    {
        $key = env('OASE_API_KEY');
        $count = 0;
        $kodeProdi = '09';
        $tahunAkademikList = ['20201', '20211', '20221', '20231', '20241', '20251'];

        foreach ($tahunAkademikList as $kodeTA) {
            $res = Http::get('https://api.oase.poltektegal.ac.id/api/web/dosen', [
                'key' => $key,
                'kd_prodi' => $kodeProdi,
                'kode_tahun_akademik' => $kodeTA
            ]);

            if ($res->successful() && isset($res['data'])) {
                $count += count($res['data']);
            }
        }

        return $count;
    }

    private function getAlumniCount()
    {
        $key = env('OASE_API_KEY');
        $count = 0;

        for ($tahun = 2020; $tahun <= 2025; $tahun++) {
            $res = Http::get('https://api.oase.poltektegal.ac.id/api/web/alumni', [
                'key' => $key,
                'tahun_angkatan' => $tahun
            ]);

            if ($res->successful() && isset($res['data'])) {
                $count += count($res['data']);
            }
        }

        return $count;
    }

    private function getStatistikBekerja()
    {
        $bekerja = TracerStudy::where('status_kerja', 'aktif')->count();
        $belum = TracerStudy::where('status_kerja', 'tidak_aktif')->count();
        $total = $bekerja + $belum;
    
        return [
            'Bekerja' => $total ? round(($bekerja / $total) * 100, 1) . '%' : '0%',
            'Belum Bekerja' => $total ? round(($belum / $total) * 100, 1) . '%' : '0%',
            'Wirausaha' => 'Tidak tersedia' // ganti jika ada data wirausaha
        ];
    }
    

    private function getAlumniData($tahun)
    {
        $raw = DB::table('alumni')
            ->selectRaw('tahun_lulus as tahun, COUNT(*) as total')
            ->whereBetween('tahun_lulus', [$tahun[0], end($tahun)])
            ->groupBy('tahun_lulus')
            ->pluck('total', 'tahun')
            ->toArray();

        return array_map(fn($t) => $raw[$t] ?? 0, $tahun);
    }

    private function getKuisionerData($tahun)
    {
        $raw = DB::table('tracerstudy')
            ->join('alumni', 'tracerstudy.id_alumni', '=', 'alumni.id')
            ->selectRaw('alumni.tahun_lulus as tahun, COUNT(*) as total')
            ->whereBetween('alumni.tahun_lulus', [$tahun[0], end($tahun)])
            ->groupBy('alumni.tahun_lulus')
            ->pluck('total', 'tahun')
            ->toArray();

        return array_map(fn($t) => $raw[$t] ?? 0, $tahun);
    }
}
