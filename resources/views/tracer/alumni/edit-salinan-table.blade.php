<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Data Tracer Alumni</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('assets/media/favicons/logo_phb.png') }}">
    <style>
        body {
            background: linear-gradient(135deg, #f9f9fa 0%, #ffffff 100%);
            min-height: 100vh;
            padding: 20px 0;
        }
        .questionnaire-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.08);
            overflow: hidden;
        }
        .header-section {
            background: linear-gradient(135deg, #1763a5 0%, #085ddd 100%);
            color: white;
            padding: 40px 30px;
            text-align: center;
            position: relative;
        }
        .header-section h1 {
            font-size: 2.2rem;
            font-weight: 700;
            margin-bottom: 10px;
            text-shadow: 0 2px 4px rgba(0,0,0,0.18);
            position: relative;
            z-index: 1;
        }
        .section-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.08);
            margin-bottom: 25px;
            overflow: hidden;
        }
        .section-header {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            color: white;
            padding: 18px 25px;
            font-size: 1.15rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .section-body {
            padding: 25px 25px;
        }
        .form-label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 7px;
        }
        .form-control, .form-select {
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 11px 14px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }
        .form-control:focus, .form-select:focus {
            border-color: #4facfe;
            box-shadow: 0 0 0 0.15rem rgba(79, 172, 254, 0.16);
        }
        .btn-submit {
            background: linear-gradient(135deg, #132ad6 0%, #1f12ce 100%);
            border: none;
            padding: 12px 35px;
            border-radius: 50px;
            font-size: 1.1rem;
            font-weight: 600;
            color: white !important;
            transition: all 0.3s ease;
            box-shadow: 0 8px 25px rgba(220, 222, 233, 0.3);
        }
        .btn-submit:hover {
            background: linear-gradient(135deg, #4563eb 0%, #667eea 100%);
            color: white !important;
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(102,126,234,0.12);
        }
    </style>
</head>
<body>
    <div class="container mt-3">
        <div class="questionnaire-container animate-fade-in mb-5">
            <!-- Header -->
            <div class="header-section">
                <i class="fas fa-user-edit fa-2x mb-3"></i>
                <h1>Edit Data Tracer Alumni</h1>
                <p class="mb-0">Perbarui data tracer alumni dengan lebih mudah dan tampilan modern.</p>
            </div>

            <div class="p-4">
                <a href="{{ route('listtracerpengguna.index') }}" class="btn btn-secondary mb-4">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
                <form action="{{ route('listtracerpengguna.update', $data->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Informasi Personal -->
                    <div class="section-card">
                        <div class="section-header">
                            <i class="fas fa-user"></i> Informasi Pribadi
                        </div>
                        <div class="section-body">
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label class="form-label"><i class="fas fa-id-card text-primary"></i> Nama</label>
                                    <input type="text" name="nama" class="form-control" value="{{ $data->nama }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label"><i class="fas fa-graduation-cap text-primary"></i> Program Studi</label>
                                    <input type="text" name="prodi" class="form-control" value="{{ $data->prodi }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label"><i class="fas fa-map-marker-alt text-primary"></i> Alamat</label>
                                    <input type="text" name="alamat" class="form-control" value="{{ $data->alamat }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label"><i class="fas fa-briefcase text-primary"></i> Jabatan</label>
                                    <input type="text" name="jabatan" class="form-control" value="{{ $data->jabatan }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Survey Kompetensi Lulusan -->
                    <div class="section-card">
                        <div class="section-header">
                            <i class="fas fa-chart-bar"></i> Survey Kompetensi Lulusan
                        </div>
                        <div class="section-body">
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label class="form-label">Integritas</label>
                                    <select name="integritas" class="form-select" required>
                                        <option value="" disabled>-- Pilih Level --</option>
                                        <option value="sangat_baik" {{ $data->integritas == 'sangat_baik' ? 'selected' : '' }}>Sangat Baik</option>
                                        <option value="baik" {{ $data->integritas == 'baik' ? 'selected' : '' }}>Baik</option>
                                        <option value="cukup" {{ $data->integritas == 'cukup' ? 'selected' : '' }}>Cukup</option>
                                        <option value="kurang_baik" {{ $data->integritas == 'kurang_baik' ? 'selected' : '' }}>Kurang Baik</option>
                                        <option value="tidak_baik" {{ $data->integritas == 'tidak_baik' ? 'selected' : '' }}>Tidak Baik</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Keahlian</label>
                                    <select name="keahlian" class="form-select" required>
                                        <option value="" disabled>-- Pilih Level --</option>
                                        <option value="sangat_baik" {{ $data->keahlian == 'sangat_baik' ? 'selected' : '' }}>Sangat Baik</option>
                                        <option value="baik" {{ $data->keahlian == 'baik' ? 'selected' : '' }}>Baik</option>
                                        <option value="cukup" {{ $data->keahlian == 'cukup' ? 'selected' : '' }}>Cukup</option>
                                        <option value="kurang_baik" {{ $data->keahlian == 'kurang_baik' ? 'selected' : '' }}>Kurang Baik</option>
                                        <option value="tidak_baik" {{ $data->keahlian == 'tidak_baik' ? 'selected' : '' }}>Tidak Baik</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Kemampuan</label>
                                    <select name="kemampuan" class="form-select" required>
                                        <option value="" disabled>-- Pilih Level --</option>
                                        <option value="sangat_baik" {{ $data->kemampuan == 'sangat_baik' ? 'selected' : '' }}>Sangat Baik</option>
                                        <option value="baik" {{ $data->kemampuan == 'baik' ? 'selected' : '' }}>Baik</option>
                                        <option value="cukup" {{ $data->kemampuan == 'cukup' ? 'selected' : '' }}>Cukup</option>
                                        <option value="kurang_baik" {{ $data->kemampuan == 'kurang_baik' ? 'selected' : '' }}>Kurang Baik</option>
                                        <option value="tidak_baik" {{ $data->kemampuan == 'tidak_baik' ? 'selected' : '' }}>Tidak Baik</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Penguasaan Bidang</label>
                                    <select name="penguasaan" class="form-select" required>
                                        <option value="" disabled>-- Pilih Level --</option>
                                        <option value="sangat_baik" {{ $data->penguasaan == 'sangat_baik' ? 'selected' : '' }}>Sangat Baik</option>
                                        <option value="baik" {{ $data->penguasaan == 'baik' ? 'selected' : '' }}>Baik</option>
                                        <option value="cukup" {{ $data->penguasaan == 'cukup' ? 'selected' : '' }}>Cukup</option>
                                        <option value="kurang_baik" {{ $data->penguasaan == 'kurang_baik' ? 'selected' : '' }}>Kurang Baik</option>
                                        <option value="tidak_baik" {{ $data->penguasaan == 'tidak_baik' ? 'selected' : '' }}>Tidak Baik</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Komunikasi</label>
                                    <select name="komunikasi" class="form-select" required>
                                        <option value="" disabled>-- Pilih Level --</option>
                                        <option value="sangat_baik" {{ $data->komunikasi == 'sangat_baik' ? 'selected' : '' }}>Sangat Baik</option>
                                        <option value="baik" {{ $data->komunikasi == 'baik' ? 'selected' : '' }}>Baik</option>
                                        <option value="cukup" {{ $data->komunikasi == 'cukup' ? 'selected' : '' }}>Cukup</option>
                                        <option value="kurang_baik" {{ $data->komunikasi == 'kurang_baik' ? 'selected' : '' }}>Kurang Baik</option>
                                        <option value="tidak_baik" {{ $data->komunikasi == 'tidak_baik' ? 'selected' : '' }}>Tidak Baik</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Kerja Tim</label>
                                    <select name="kerja_tim" class="form-select" required>
                                        <option value="" disabled>-- Pilih Level --</option>
                                        <option value="sangat_baik" {{ $data->kerja_tim == 'sangat_baik' ? 'selected' : '' }}>Sangat Baik</option>
                                        <option value="baik" {{ $data->kerja_tim == 'baik' ? 'selected' : '' }}>Baik</option>
                                        <option value="cukup" {{ $data->kerja_tim == 'cukup' ? 'selected' : '' }}>Cukup</option>
                                        <option value="kurang_baik" {{ $data->kerja_tim == 'kurang_baik' ? 'selected' : '' }}>Kurang Baik</option>
                                        <option value="tidak_baik" {{ $data->kerja_tim == 'tidak_baik' ? 'selected' : '' }}>Tidak Baik</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Pengembangan Diri</label>
                                    <select name="pengembangan" class="form-select" required>
                                        <option value="" disabled>-- Pilih Level --</option>
                                        <option value="sangat_baik" {{ $data->pengembangan == 'sangat_baik' ? 'selected' : '' }}>Sangat Baik</option>
                                        <option value="baik" {{ $data->pengembangan == 'baik' ? 'selected' : '' }}>Baik</option>
                                        <option value="cukup" {{ $data->pengembangan == 'cukup' ? 'selected' : '' }}>Cukup</option>
                                        <option value="kurang_baik" {{ $data->pengembangan == 'kurang_baik' ? 'selected' : '' }}>Kurang Baik</option>
                                        <option value="tidak_baik" {{ $data->pengembangan == 'tidak_baik' ? 'selected' : '' }}>Tidak Baik</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Informasi Perusahaan -->
                    <div class="section-card">
                        <div class="section-header">
                            <i class="fas fa-building"></i> Informasi Perusahaan
                        </div>
                        <div class="section-body">
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label class="form-label">Nama Perusahaan</label>
                                    <input type="text" name="nama_perusahaan" class="form-control" value="{{ $data->nama_perusahaan }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Alamat Perusahaan</label>
                                    <input type="text" name="alamat_perusahaan" class="form-control" value="{{ $data->alamat_perusahaan }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Nama Atasan</label>
                                    <input type="text" name="nama_atasan" class="form-control" value="{{ $data->nama_atasan }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">NIP Atasan</label>
                                    <input type="text" name="nip_atasan" class="form-control" value="{{ $data->nip_atasan }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Posisi Jabatan Atasan</label>
                                    <input type="text" name="posisi_jabatan_atasan" class="form-control" value="{{ $data->posisi_jabatan_atasan }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Saran & Komentar -->
                    <div class="section-card">
                        <div class="section-header">
                            <i class="fas fa-comments"></i> Saran & Komentar
                        </div>
                        <div class="section-body">
                            <label class="form-label mb-3">
                                <i class="fas fa-edit text-primary"></i>
                                Berikan saran atau kritik untuk perbaikan kampus atau tracer study
                            </label>
                            <textarea name="saran" class="form-control" rows="4" placeholder="Tulis saran/kritik di sini...">{{ $data->saran }}</textarea>
                        </div>
                    </div>

                    <div class="text-center mb-3">
                        <button type="submit" class="btn btn-submit">
                            <i class="fas fa-save"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>
