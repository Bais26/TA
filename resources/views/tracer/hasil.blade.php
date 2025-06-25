@extends('layouts.admin')

@section('content')
<div class="container py-5">
    <!-- Header -->
    <div class="mb-5">
        <h1 class="fw-bold text-dark mb-2">Dashboard Alumni Survey</h1>
        <p class="text-muted">Analisis kompetensi dan kinerja alumni</p>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-5 g-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="text-primary mb-2">
                                <i class="bi bi-people fs-1"></i>
                            </div>
                            <h6 class="text-muted mb-1">Total Alumni</h6>
                            <h2 class="fw-bold text-dark">{{ $totalAlumni }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="text-success mb-2">
                                <i class="bi bi-check-circle fs-1"></i>
                            </div>
                            <h6 class="text-muted mb-1">Sudah Mengisi</h6>
                            <h2 class="fw-bold text-success">{{ $sudahMengisi }}</h2>
                        </div>
                        <div class="text-end">
                            <small class="text-muted">
                                {{ $totalAlumni > 0 ? round(($sudahMengisi / $totalAlumni) * 100, 1) : 0 }}%
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="text-warning mb-2">
                                <i class="bi bi-clock fs-1"></i>
                            </div>
                            <h6 class="text-muted mb-1">Belum Mengisi</h6>
                            <h2 class="fw-bold text-warning">{{ $belumMengisi }}</h2>
                        </div>
                        <div class="text-end">
                            <small class="text-muted">
                                {{ $totalAlumni > 0 ? round(($belumMengisi / $totalAlumni) * 100, 1) : 0 }}%
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Survey Results -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0 py-4">
            <div class="d-flex align-items-center">
                <i class="bi bi-bar-chart text-primary me-3 fs-4"></i>
                <div>
                    <h4 class="fw-bold mb-1">Hasil Survei Kompetensi Alumni</h4>
                    <small class="text-muted">Tabel analisis berdasarkan indikator penilaian</small>
                </div>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="border-0 py-3 px-4 fw-semibold">Indikator</th>
                            <th class="border-0 py-3 text-center fw-semibold">
                                Tidak Baik<br>
                                <small class="text-danger">(1)</small>
                            </th>
                            <th class="border-0 py-3 text-center fw-semibold">
                                Kurang Baik<br>
                                <small class="text-warning">(2)</small>
                            </th>
                            <th class="border-0 py-3 text-center fw-semibold">
                                Cukup<br>
                                <small class="text-secondary">(3)</small>
                            </th>
                            <th class="border-0 py-3 text-center fw-semibold">
                                Baik<br>
                                <small class="text-info">(4)</small>
                            </th>
                            <th class="border-0 py-3 text-center fw-semibold">
                                Sangat Baik<br>
                                <small class="text-success">(5)</small>
                            </th>
                            <th class="border-0 py-3 text-center fw-semibold">Responden</th>
                            <th class="border-0 py-3 text-center fw-semibold">Rata-Rata</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($hasil as $row)
                        <tr>
                            <td class="py-4 px-4 fw-medium border-0">{{ $row['label'] }}</td>
                            <td class="text-center py-4 border-0">
                                <span class="badge bg-light text-dark">{{ $row['rekap'][1] }}</span>
                            </td>
                            <td class="text-center py-4 border-0">
                                <span class="badge bg-light text-dark">{{ $row['rekap'][2] }}</span>
                            </td>
                            <td class="text-center py-4 border-0">
                                <span class="badge bg-light text-dark">{{ $row['rekap'][3] }}</span>
                            </td>
                            <td class="text-center py-4 border-0">
                                <span class="badge bg-light text-dark">{{ $row['rekap'][4] }}</span>
                            </td>
                            <td class="text-center py-4 border-0">
                                <span class="badge bg-light text-dark">{{ $row['rekap'][5] }}</span>
                            </td>
                            <td class="text-center py-4 border-0 fw-semibold">{{ $row['jumlah_responden'] }}</td>
                            <td class="text-center py-4 border-0">
                                <div>
                                    <span class="badge fw-semibold
                                        @if($row['rata_rata'] >= 4.5) bg-success text-white
                                        @elseif($row['rata_rata'] >= 3.5) bg-primary text-white
                                        @elseif($row['rata_rata'] >= 2.5) bg-secondary text-white
                                        @elseif($row['rata_rata'] >= 1.5) bg-warning text-dark
                                        @else bg-danger text-white
                                        @endif
                                    ">
                                        {{ $row['rata_rata'] }}
                                    </span>
                                    <div class="small text-muted mt-1">{{ $row['keterangan'] }}</div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Conclusion -->
    <div class="mt-4">
        <div class="alert alert-primary border-0 shadow-sm" role="alert">
            <div class="d-flex align-items-center">
                <i class="bi bi-info-circle me-3 fs-4"></i>
                <div>
                    <h6 class="fw-bold mb-1">Kesimpulan</h6>
                    <p class="mb-0">
                        Berdasarkan hasil survei, alumni Politeknik tergolong
                        <strong>{{ $kesimpulanKategori }}</strong> dengan rata-rata nilai
                        <strong>{{ $kesimpulanRataRata }}</strong>.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Legend -->
    <div class="mt-3">
        <div class="card border-0 bg-light">
            <div class="card-body py-3">
                <small class="text-muted">
                    <i class="bi bi-info-circle me-1"></i>
                    <strong>Skala:</strong> 1 = Tidak Baik, 2 = Kurang Baik, 3 = Cukup, 4 = Baik, 5 = Sangat Baik
                </small>
            </div>
        </div>
    </div>
</div>

<style>
.card {
    transition: box-shadow 0.15s ease;
}

.card:hover {
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1) !important;
}

.table tbody tr:hover {
    background-color: rgba(0, 123, 255, 0.02);
}

.badge {
    font-size: 0.875rem;
    padding: 0.5rem 0.75rem;
}

/* Clean, minimal styling */
.shadow-sm {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075) !important;
}

/* Responsive */
@media (max-width: 768px) {
    .table {
        font-size: 0.875rem;
    }

    .card-body {
        padding: 1.5rem !important;
    }
}
</style>
@endsection
