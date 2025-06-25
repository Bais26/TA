@extends('layouts.admin')

@section('content')
<div class="container py-4">

    <div class="row mb-4 g-4">
        <div class="col-md-4">
            <div class="card shadow h-100 border-0">
                <div class="card-body text-center">
                    <div class="mb-2">
                        <i class="bi bi-people-fill display-4 text-primary"></i>
                    </div>
                    <h6 class="text-muted">Total Alumni</h6>
                    <h2 class="fw-bold">{{ $totalAlumni }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow h-100 border-0">
                <div class="card-body text-center">
                    <div class="mb-2">
                        <i class="bi bi-journal-check display-4 text-success"></i>
                    </div>
                    <h6 class="text-muted">Sudah Mengisi</h6>
                    <h2 class="fw-bold text-success">{{ $sudahMengisi }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow h-100 border-0">
                <div class="card-body text-center">
                    <div class="mb-2">
                        <i class="bi bi-journal-x display-4 text-danger"></i>
                    </div>
                    <h6 class="text-muted">Belum Mengisi</h6>
                    <h2 class="fw-bold text-danger">{{ $belumMengisi }}</h2>
                </div>
            </div>
        </div>
    </div>

    <h4 class="mb-4 fw-semibold">
        <i class="bi bi-bar-chart-fill text-primary"></i>
        Tabel Hasil Survei Kompetensi Alumni
    </h4>
    <div class="table-responsive">
        <table class="table table-hover table-bordered align-middle shadow-sm bg-white rounded">
            <thead class="table-primary text-center align-middle">
                <tr>
                    <th>Indikator</th>
                    <th>Tidak Baik<br><span class="badge bg-danger">(1)</span></th>
                    <th>Kurang Baik<br><span class="badge bg-warning text-dark">(2)</span></th>
                    <th>Cukup<br><span class="badge bg-secondary">(3)</span></th>
                    <th>Baik<br><span class="badge bg-info text-dark">(4)</span></th>
                    <th>Sangat Baik<br><span class="badge bg-success">(5)</span></th>
                    <th>Jumlah Responden</th>
                    <th>Rata-Rata</th>
                </tr>
            </thead>
            <tbody class="text-center">
                @foreach($hasil as $row)
                <tr>
                    <td class="text-start fw-semibold">{{ $row['label'] }}</td>
                    <td>{{ $row['rekap'][1] }}</td>
                    <td>{{ $row['rekap'][2] }}</td>
                    <td>{{ $row['rekap'][3] }}</td>
                    <td>{{ $row['rekap'][4] }}</td>
                    <td>{{ $row['rekap'][5] }}</td>
                    <td>{{ $row['jumlah_responden'] }}</td>
                    <td>
                        <span class="badge
                            @if($row['rata_rata'] >= 4.5) bg-success
                            @elseif($row['rata_rata'] >= 3.5) bg-info text-dark
                            @elseif($row['rata_rata'] >= 2.5) bg-secondary
                            @elseif($row['rata_rata'] >= 1.5) bg-warning text-dark
                            @else bg-danger
                            @endif
                        ">
                            {{ $row['rata_rata'] }}
                        </span>
                        <div class="small text-muted">{{ $row['keterangan'] }}</div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-2">
        <small class="text-muted">
            <i class="bi bi-info-circle"></i>
            Nilai: 1 = Tidak Baik, 2 = Kurang Baik, 3 = Cukup, 4 = Baik, 5 = Sangat Baik.
        </small>
    </div>
</div>
@endsection
