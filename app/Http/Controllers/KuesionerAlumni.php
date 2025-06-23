<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TracerStudy;
use App\Models\Alumni;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class KuesionerAlumni extends Controller
{
    /**
     * Display the tracer study form
     */
    public function index()
    {
        // Get alumni data if user is logged in
        $alumni = null;
        if (Auth::check()) {
            $alumni = Alumni::where('id', Auth::id())->first();
        }
        
        return view('components.kuesioner', compact('alumni'));
    }

    /**
     * Store tracer study data
     */
    public function create(Request $request)
    {
        // Validation rules
        $rules = [
            'nama' => 'required|string|max:255',
            'no_hp' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'tahun_lulus' => 'required|integer|min:2000|max:2024',
            'alamat' => 'required|string|max:500',
            'status_pekerjaan' => 'required|in:1,2,3,4,5',
            'relevansi_kurikulum' => 'required|in:sangat_relevan,relevan,cukup,tidak_relevan,sangat_tidak_relevan',
            'saran' => 'nullable|string|max:1000'
        ];

        // Dynamic validation based on work status
        switch ($request->status_pekerjaan) {
            case '1': // Bekerja full time/part time
                $rules = array_merge($rules, [
                    'nama_perusahaan' => 'required|string|max:255',
                    'jabatan' => 'required|string|max:255',
                    'alamat_pekerjaan' => 'required|string|max:500',
                    'gaji' => 'required|string|max:100',
                    'integritas' => 'required|in:sangat_baik,baik,cukup,kurang_baik,tidak_baik',
                    'keahlian' => 'required|in:sangat_baik,baik,cukup,kurang_baik,tidak_baik',
                    'kemampuan' => 'required|in:sangat_baik,baik,cukup,kurang_baik,tidak_baik',
                    'penguasaan' => 'required|in:sangat_baik,baik,cukup,kurang_baik,tidak_baik',
                    'komunikasi' => 'required|in:sangat_baik,baik,cukup,kurang_baik,tidak_baik',
                    'kerja_tim' => 'required|in:sangat_baik,baik,cukup,kurang_baik,tidak_baik',
                    'pengembangan' => 'required|in:sangat_baik,baik,cukup,kurang_baik,tidak_baik'
                ]);
                break;
            
            case '2': // Belum memungkinkan bekerja
                $rules = array_merge($rules, [
                    'alasan_tidak_bekerja' => 'required|string|max:255',
                    'rencana_cari_kerja' => 'required|in:tidak,ya_ada_rencana'
                ]);
                break;
            
            case '3': // Wiraswasta
                $rules = array_merge($rules, [
                    'nama_usaha' => 'required|string|max:255',
                    'bidang_usaha' => 'required|string|max:255',
                    'alamat_usaha' => 'required|string|max:500'
                ]);
                break;
            
            case '4': // Melanjutkan pendidikan
                $rules = array_merge($rules, [
                    'nama_instansi' => 'required|string|max:255',
                    'jurusan' => 'required|string|max:255',
                    'jenjang' => 'required|string|max:100',
                    'tahun_masuk' => 'required|integer|min:2000|max:2024',
                    'alamat_instansi' => 'required|string|max:500'
                ]);
                break;
            
            case '5': // Tidak kerja tetapi sedang mencari kerja
                $rules = array_merge($rules, [
                    'cara_mencari_kerja' => 'required|string|max:255',
                    'jumlah_lamaran' => 'required|integer|min:0',
                    'jumlah_panggilan' => 'required|integer|min:0'
                ]);
                break;
        }

        // Validate the request
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            // Prepare data for storage
            $tracerData = [
                'id_alumni' => Auth::id(),
                'nama' => $request->nama,
                'no_hp' => $request->no_hp,
                'email' => $request->email,
                'tahun_lulus' => $request->tahun_lulus,
                'alamat' => $request->alamat,
                'status_pekerjaan' => $request->status_pekerjaan,
                'relevansi_kurikulum' => $request->relevansi_kurikulum,
                'saran' => $request->saran,
                'created_at' => now(),
                'updated_at' => now()
            ];

            // Add specific data based on work status
            switch ($request->status_pekerjaan) {
                case '1': // Bekerja
                    $tracerData = array_merge($tracerData, [
                        'nama_perusahaan' => $request->nama_perusahaan,
                        'jabatan' => $request->jabatan,
                        'alamat_pekerjaan' => $request->alamat_pekerjaan,
                        'gaji' => $request->gaji,
                        'integritas' => $request->integritas,
                        'keahlian' => $request->keahlian,
                        'kemampuan' => $request->kemampuan,
                        'penguasaan' => $request->penguasaan,
                        'komunikasi' => $request->komunikasi,
                        'kerja_tim' => $request->kerja_tim,
                        'pengembangan' => $request->pengembangan
                    ]);
                    break;
                
                case '2': // Belum bekerja
                    $tracerData = array_merge($tracerData, [
                        'alasan_tidak_bekerja' => $request->alasan_tidak_bekerja,
                        'rencana_cari_kerja' => $request->rencana_cari_kerja
                    ]);
                    break;
                
                case '3': // Wiraswasta
                    $tracerData = array_merge($tracerData, [
                        'nama_usaha' => $request->nama_usaha,
                        'bidang_usaha' => $request->bidang_usaha,
                        'alamat_usaha' => $request->alamat_usaha
                    ]);
                    break;
                
                case '4': // Melanjutkan pendidikan
                    $tracerData = array_merge($tracerData, [
                        'nama_instansi' => $request->nama_instansi,
                        'jurusan' => $request->jurusan,
                        'jenjang' => $request->jenjang,
                        'tahun_masuk' => $request->tahun_masuk,
                        'alamat_instansi' => $request->alamat_instansi
                    ]);
                    break;
                
                case '5': // Mencari kerja
                    $tracerData = array_merge($tracerData, [
                        'cara_mencari_kerja' => $request->cara_mencari_kerja,
                        'jumlah_lamaran' => $request->jumlah_lamaran,
                        'jumlah_panggilan' => $request->jumlah_panggilan
                    ]);
                    break;
            }

            // Check if user already submitted tracer study
            $existingTracer = TracerStudy::where('id_alumni', Auth::id())->first();
            
            if ($existingTracer) {
                // Update existing record
                TracerStudy::where('id_alumni', Auth::id())->update($tracerData);
                $message = 'Data tracer study berhasil diperbarui!';
            } else {
                // Create new record
                TracerStudy::create($tracerData);
                $message = 'Terima kasih! Data tracer study berhasil disimpan.';
            }

            DB::commit();

            return redirect()->back()->with('success', $message);

        } catch (\Exception $e) {
            DB::rollback();
            
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menyimpan data. Silakan coba lagi.')
                ->withInput();
        }
    }

    /**
     * Show success page
     */
    public function success()
    {
        return view('tracer.success');
    }

    /**
     * Show tracer study results (for admin)
     */
    public function results()
    {
        // Check if user is admin
        if (!Auth::check() || !Auth::user()->is_admin) {
            abort(403, 'Unauthorized access');
        }

        $tracerStudies = TracerStudy::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $statistics = [
            'total_responses' => TracerStudy::count(),
            'working' => TracerStudy::where('status_pekerjaan', '1')->count(),
            'unemployed' => TracerStudy::where('status_pekerjaan', '2')->count(),
            'entrepreneur' => TracerStudy::where('status_pekerjaan', '3')->count(),
            'continuing_education' => TracerStudy::where('status_pekerjaan', '4')->count(),
            'job_seeking' => TracerStudy::where('status_pekerjaan', '5')->count(),
        ];

        return view('tracer.results', compact('tracerStudies', 'statistics'));
    }

    /**
     * Export tracer study data to Excel
     */
    public function export()
    {
        // Check if user is admin
        if (!Auth::check() || !Auth::user()->is_admin) {
            abort(403, 'Unauthorized access');
        }

        $tracerStudies = TracerStudy::all();
        
        // You can use Laravel Excel package here
        // return Excel::download(new TracerStudyExport($tracerStudies), 'tracer-study-' . date('Y-m-d') . '.xlsx');
        
        // For now, return JSON for testing
        return response()->json($tracerStudies);
    }

    /**
     * Get user's tracer study data
     */
    public function getUserData()
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $tracerStudy = TracerStudy::where('user_id', Auth::id())->first();
        
        if (!$tracerStudy) {
            return response()->json(['message' => 'No data found'], 404);
        }

        return response()->json($tracerStudy);
    }

    /**
     * Delete tracer study data
     */
    public function destroy($id)
    {
        // Check if user is admin
        if (!Auth::check() || !Auth::user()->is_admin) {
            abort(403, 'Unauthorized access');
        }

        try {
            $tracerStudy = TracerStudy::findOrFail($id);
            $tracerStudy->delete();

            return redirect()->back()->with('success', 'Data berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus data.');
        }
    }
}