<?php

namespace App\Http\Controllers;

use App\Models\Alumni;
use App\Models\tracer_pengguna;
use App\Models\TracerStudy;
use Illuminate\Http\Request;

class TracerStudyController extends Controller
{
    // Menampilkan semua data tracer
    public function index(Request $request)
    {
        $query = tracer_pengguna::query();

        // Filter berdasarkan prodi jika ada
        if ($request->has('prodi')) {
            $query->byProdi($request->prodi);
        }

        // Filter berdasarkan tahun jika ada
        if ($request->has('tahun')) {
            $query->byYear($request->tahun);
        }

        $data = $query->latest()->paginate(10);

        // INI BAGIAN TERPENTING: ambil data alumni yang login
        $user = auth()->user();
        $alumni = Alumni::where('id_users', $user->id)->first();

        return view('components.kuesioner-pengguna', compact('data', 'alumni'));
    }


    // Menampilkan form input
    public function create()
    {
        return view('components.kuesioner-pengguna');
    }

    // Menyimpan data baru
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string',
            'prodi' => 'required|string',
            'jabatan' => 'nullable|string',
            'integritas' => 'required|string',
            'keahlian' => 'required|string',
            'kemampuan' => 'required|string',
            'penguasaan' => 'required|string',
            'komunikasi' => 'required|string',
            'kerja_tim' => 'required|string',
            'pengembangan' => 'required|string',
            'nama_atasan' => 'nullable|string',
            'nip_atasan' => 'nullable|string',
            'posisi_jabatan_atasan' => 'nullable|string',
            'nama_perusahaan' => 'nullable|string',
            'alamat_perusahaan' => 'nullable|string',
            'saran' => 'nullable|string'
        ]);
    
        $user = auth()->user(); // ambil user login
    
        tracer_pengguna::create([
            'user_id' => $user->id,
            'nama' => $request->nama,
            'alamat' => $request->alamat,
            'prodi' => $request->prodi,
            'jabatan' => $request->jabatan,
            'integritas' => $request->integritas,
            'keahlian' => $request->keahlian,
            'kemampuan' => $request->kemampuan,
            'penguasaan' => $request->penguasaan,
            'komunikasi' => $request->komunikasi,
            'kerja_tim' => $request->kerja_tim,
            'pengembangan' => $request->pengembangan,
            'nama_atasan' => $request->nama_atasan,
            'nip_atasan' => $request->nip_atasan,
            'posisi_jabatan_atasan' => $request->posisi_jabatan_atasan,
            'nama_perusahaan' => $request->nama_perusahaan,
            'alamat_perusahaan' => $request->alamat_perusahaan,
            'saran' => $request->saran
        ]);
    
        return redirect()->back()->with('success', 'Data berhasil disimpan.');
    }
    
    // Menampilkan detail satu data
    // public function show($id)
    // {
    //     $data = tracer_pengguna::findOrFail($id);
    //     return view('tracer.pengguna.detail-salinan-table', compact('data'));
    // }
    public function showPengguna($id)
    {
        $pengguna = tracer_pengguna::where('user_id', $id)->first(); // atau ->get() jika banyak

        return view('alumni.detail-pengguna', compact('pengguna'));
    }
    public function showStudy($id)
{
    $tracer = TracerStudy::with('alumni')
        ->where('id_alumni', $id)
        ->firstOrFail();

    return view('alumni.detail-study', compact('tracer'));
}

    
    
    
    
    

    // Menampilkan form edit
    public function edit($id)
    {
        $data = tracer_pengguna::findOrFail($id);
        return view('tracer.edit', compact('data'));
    }

    // Memperbarui data
    public function update(Request $request, $id)
    {
        $data = tracer_pengguna::findOrFail($id);

        $data->update($request->all());

        return redirect()->route('tracer.index')->with('success', 'Data berhasil diperbarui.');
    }

    // Menghapus data
    public function destroy($id)
    {
        $data = tracer_pengguna::findOrFail($id);
        $data->delete();

        return redirect()->route('tracer.index')->with('success', 'Data berhasil dihapus.');
    }
}