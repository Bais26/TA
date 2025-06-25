<?php

namespace App\Http\Controllers;

use App\Models\Alumni;
use App\Models\TracerStudy;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class TracerAlumniController extends Controller
{
    // Menampilkan halaman dengan DataTables
    public function index()
    {
        $totalAlumni = Alumni::count();

        // Total yang sudah mengisi TracerStudy
        // Asumsi: alumni_id adalah foreign key ke Alumni
        $sudahMengisi = TracerStudy::distinct('id_alumni')->count('id_alumni');

        // Yang belum mengisi = total - sudah mengisi
        $belumMengisi = $totalAlumni - $sudahMengisi;

        if (request()->ajax()) {
            // Ambil data tracer study beserta relasi alumni dan users
            $tracer = TracerStudy::with('alumni.users')->get();

            // Total alumni (misal primary key-nya 'id')


            return DataTables::of($tracer)
                ->addColumn('nama_alumni', function ($row) {
                    return $row->alumni && $row->alumni->users
                        ? $row->alumni->users->name
                        : '-';
                })
                ->addColumn('action', function ($row) {
                    $editUrl = route('listtraceralumni.edit', $row->id);
                    $deleteUrl = route('listtraceralumni.destroy', $row->id);
                    return '
                        <a href="' . $editUrl . '" class="btn btn-sm btn-warning"><i class="fa fa-edit"></i></a>
                        <form action="' . $deleteUrl . '" method="POST" style="display:inline-block;" onsubmit="return confirm(\'Yakin ingin hapus?\')">
                            ' . csrf_field() . method_field('DELETE') . '
                            <button class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></button>
                        </form>
                    ';
                })

                ->rawColumns(['action'])
                ->make(true);
        }

        return view('tracer.table-salinan-alumni', compact('totalAlumni', 'sudahMengisi', 'belumMengisi'));
    }

    // Contoh fungsi lain untuk data alumni (jika dibutuhkan)
    public function getData()
    {
        if (request()->ajax()) {
            $alumni = Alumni::with('users')->get();

            return DataTables::of($alumni)
                ->addColumn('nama', function ($row) {
                    return $row->users ? $row->users->name : '-';
                })
                ->make(true);
        }
    }

    public function show($id)
    {
        $data = TracerStudy::findOrFail($id);
        return view('tracer.alumni.detail-salinan-table', compact('data'));
    }

    /**
     * Menampilkan form edit data tracer
     */
    public function edit($id)
    {
        $data = TracerStudy::findOrFail($id);
        return view('tracer.alumni.edit-salinan-table', compact('data'));
    }

    // Update
    public function update(Request $request, $id)
    {


        $tracer = TracerStudy::findOrFail($id);
        $tracer->update($request->all());

        return redirect()->back()->with('success', 'Data berhasil diperbarui.');
    }

    // Hapus
    public function destroy($id)
    {
        $tracer = TracerStudy::findOrFail($id);
        $tracer->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil dihapus.'
        ]);
    }
}
