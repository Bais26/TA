<?php

namespace App\Http\Controllers;

use App\Models\Alumni;
use App\Models\TracerStudy;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class KuesionerAlumniController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $alumni = Alumni::where('id_users', auth()->user()->id)->first();
        return view('components.kuesioner', compact('alumni'));
    }

    public function store(Request $request)
    {
        try {
            // Validasi data input
            $validated = $request->validate([
                'nama' => ['required', 'string', 'max:255'],
                'no_hp' => ['required'],
                'email' => ['required', 'email', 'max:255'],
                'tahun_lulus' => ['required', 'integer'],
                'alamat' => ['required', 'string', 'max:255'],
                'bekerja' => ['required', 'string', 'in:ya,tidak'],
                'nama_perusahaan' => ['nullable', 'string', 'max:255'],
                'jabatan' => ['nullable', 'string', 'max:255'],
                'alamat_pekerjaan' => ['nullable', 'string', 'max:255'],
                'gaji' => ['nullable', 'string', 'max:255'],
                'relevansi_kurikulum' => ['required', 'string'],
                'saran' => ['nullable', 'string', 'max:500'],
            ]);

            DB::beginTransaction();

            // Ambil user yang login
            $user = auth()->user();

            // Cek data alumni
            $alumni = Alumni::firstOrCreate(
                ['id_users' => $user->id],
                [
                    'nama_lengkap' => $validated['nama'],
                    'no_hp' => $validated['no_hp'],
                    'tahun_lulus' => $validated['tahun_lulus'],
                    'alamat' => $validated['alamat'],
                ]
            );

            // Update data alumni jika sudah ada
            $alumni->update([
                'nama_lengkap' => $validated['nama'],
                'no_hp' => $validated['no_hp'],
                'tahun_lulus' => $validated['tahun_lulus'],
                'alamat' => $validated['alamat'],
            ]);

            // Update email user
            $user->update(['email' => $validated['email']]);

            // Buat atau update tracer study
            $tracer = TracerStudy::updateOrCreate(
                ['id_alumni' => $alumni->id],
                [
                    'tanggal_isi' => now(),
                    'bekerja' => $validated['bekerja'],
                    'nama_perusahaan' => $validated['nama_perusahaan'],
                    'jabatan' => $validated['jabatan'],
                    'alamat_pekerjaan' => $validated['alamat_pekerjaan'],
                    'gaji' => $validated['gaji'] ? str_replace(['Rp', '.', ',', ' '], '', $validated['gaji']) : null,
                    'status_kerja' => $validated['bekerja'] === 'ya' ? 'aktif' : 'tidak_aktif',
                    'relevansi_pekerjaan' => $validated['relevansi_kurikulum'],
                    'saran' => $validated['saran'],
                ]
            );

            DB::commit();

            return redirect()->route('home')->with('success', 'Kuesioner berhasil disimpan!');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withErrors(['error' => 'Gagal menyimpan data: ' . $e->getMessage()]);
        }
    }


    public function destroy($id)
    {
        $tracer = TracerStudy::findOrFail($id);
        $tracer->delete();

        return response()->json(['message' => 'Data berhasil dihapus.']);
    }
}
