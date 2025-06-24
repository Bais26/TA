<!DOCTYPE html>
<html lang="id">
<head>
    @extends('layouts.admin')

@section('content')
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Hasil Tracer Study Alumni</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
    <style>

        .dashboard-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(15px);
            border-radius: 25px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
            margin: 20px 0;
            overflow: hidden;
        }

        .header-dashboard {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            color: white;
            padding: 40px 30px;
            text-align: center;
            position: relative;
        }

        .header-dashboard::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 20"><defs><radialGradient id="a" cx="50%" cy="0%" r="50%"><stop offset="0%" stop-color="white" stop-opacity="0.1"/><stop offset="100%" stop-color="white" stop-opacity="0"/></radialGradient></defs><rect width="100" height="20" fill="url(%23a)"/></svg>');
        }

        .header-dashboard h1 {
            font-size: 2.8rem;
            font-weight: 700;
            margin-bottom: 15px;
            text-shadow: 0 3px 6px rgba(0, 0, 0, 0.3);
            position: relative;
            z-index: 1;
        }

        .stats-overview {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 25px;
            padding: 30px;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        }

        .stat-card {
            background: white;
            border-radius: 20px;
            padding: 30px;
            text-align: center;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #4facfe 0%, #00f2fe 100%);
        }

        .stat-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.2);
        }

        .stat-number {
            font-size: 3rem;
            font-weight: 700;
            color: #2a5298;
            margin-bottom: 10px;
        }

        .stat-label {
            font-size: 1.1rem;
            color: #6c757d;
            font-weight: 500;
        }

        .chart-section {
            padding: 30px;
        }

        .chart-container {
            background: white;
            border-radius: 20px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .chart-container:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }

        .chart-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: #2a5298;
            margin-bottom: 20px;
            text-align: center;
        }

        .table-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            margin: 30px 0;
        }

        .table-title {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            color: white;
            padding: 20px 30px;
            font-size: 1.4rem;
            font-weight: 600;
            margin: 0;
        }

        .custom-table {
            margin: 0;
        }

        .custom-table thead th {
            background: #f8f9fa;
            color: #495057;
            font-weight: 600;
            border: none;
            padding: 15px;
        }

        .custom-table tbody td {
            padding: 15px;
            border-color: #e9ecef;
        }

        .custom-table tbody tr:hover {
            background: rgba(79, 172, 254, 0.05);
        }

        .badge-custom {
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: 500;
        }

        .badge-bekerja { background: #d4edda; color: #155724; }
        .badge-wirausaha { background: #fff3cd; color: #856404; }
        .badge-tidak-bekerja { background: #f8d7da; color: #721c24; }

        .competency-bar {
            height: 8px;
            border-radius: 4px;
            background: #e9ecef;
            overflow: hidden;
            margin-top: 5px;
        }

        .competency-fill {
            height: 100%;
            border-radius: 4px;
            background: linear-gradient(90deg, #4facfe 0%, #00f2fe 100%);
            transition: width 0.3s ease;
        }

        .competency-score {
            font-weight: 600;
            color: #2a5298;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in {
            animation: fadeInUp 0.6s ease-out;
        }

        .filter-section {
            background: white;
            border-radius: 15px;
            padding: 20px 30px;
            margin-bottom: 30px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .btn-filter {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            padding: 10px 20px;
            border-radius: 25px;
            transition: all 0.3s ease;
        }

        .btn-filter:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
            color: white;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="dashboard-container animate-fade-in">
            <!-- Header -->
            <div class="header-dashboard">
                <i class="fas fa-chart-line fa-3x mb-3"></i>
                <h1>Dashboard Tracer Study Alumni 2025</h1>
                <p class="lead">Politeknik Harapan Bersama - Analisis Perkembangan Karir Alumni</p>
            </div>

            <!-- Stats Overview -->
            <div class="stats-overview">
                <div class="stat-card animate-fade-in">
                    <div class="stat-number">20</div>
                    <div class="stat-label">Total Responden</div>
                </div>
                <div class="stat-card animate-fade-in">
                    <div class="stat-number">85%</div>
                    <div class="stat-label">Tingkat Pekerjaan</div>
                </div>
                <div class="stat-card animate-fade-in">
                    <div class="stat-number">4,2</div>
                    <div class="stat-label">Rata-rata Kompetensi</div>
                </div>
                <div class="stat-card animate-fade-in">
                    <div class="stat-number">92%</div>
                    <div class="stat-label">Kepuasan Kurikulum</div>
                </div>
            </div>

            <style>
                .chart-container {
                    min-height: 100%;
                    display: flex;
                    flex-direction: column;
                    justify-content: space-between;
                }
            </style>
            
            <div class="container my-4 chart-section">
                <div class="row g-4">
            
                    <!-- 1. Status Pekerjaan -->
                    <div class="col d-flex">
                        <div class="chart-container border rounded">
                            <h5 class="chart-title mb-3">
                                <i class="fas fa-briefcase text-primary me-2"></i>Status Pekerjaan Alumni
                            </h5>
                            <div class="row border p-3">
                                <div class="col-6">
                                    <canvas id="employmentChart" height="50"></canvas>
                                </div>
                                <div class="col-6 d-flex flex-column justify-content-center ">
                                    <div class="mb-2"><span class="badge bg-primary me-2">●</span> <strong>Bekerja:</strong> 12 orang (60%)</div>
                                    <div class="mb-2"><span class="badge bg-warning text-dark me-2">●</span> <strong>Wirausaha:</strong> 5 orang (25%)</div>
                                    <div class="mb-2"><span class="badge bg-danger me-2">●</span> <strong>Tidak Bekerja:</strong> 3 orang (15%)</div>
                                </div>
                            </div>
                        </div>
                    </div>
            
                    <!-- 2. Kompetensi -->
                    <div class="col-md-6 d-flex">
                        <div class="chart-container border rounded p-3 w-100">
                            <h5 class="chart-title mb-3">
                                <i class="fas fa-star text-warning me-2"></i>Analisis Kompetensi Alumni
                            </h5>
                            <canvas id="competencyChart" height="200"></canvas>
                        </div>
                    </div>
            
                    <!-- 3. Distribusi Gaji -->
                    <div class="col-md-6 d-flex">
                        <div class="chart-container border rounded p-3 w-100">
                            <h5 class="chart-title mb-3">
                                <i class="fas fa-money-bill-wave text-success me-2"></i>Distribusi Gaji Alumni
                            </h5>
                            <canvas id="salaryChart" height="200"></canvas>
                        </div>
                    </div>
            
                    <!-- 4. Relevansi Kurikulum -->
                    <div class="col-md-6 d-flex">
                        <div class="chart-container border rounded p-3 w-100">
                            <h5 class="chart-title mb-3">
                                <i class="fas fa-graduation-cap text-info me-2"></i>Relevansi Kurikulum
                            </h5>
                            <div class="row">
                                <div class="col-8">
                                    <canvas id="curriculumChart" height="200"></canvas>
                                </div>
                                <div class="col-4 d-flex flex-column justify-content-center">
                                    <div class="mb-2">
                                        <small class="text-muted">Sangat Relevan</small>
                                        <div class="progress">
                                            <div class="progress-bar bg-success" style="width: 45%"></div>
                                        </div>
                                        <span class="competency-score small">9 alumni (45%)</span>
                                    </div>
                                    <div class="mb-2">
                                        <small class="text-muted">Relevan</small>
                                        <div class="progress">
                                            <div class="progress-bar bg-info" style="width: 35%"></div>
                                        </div>
                                        <span class="competency-score small">7 alumni (35%)</span>
                                    </div>
                                    <div class="mb-2">
                                        <small class="text-muted">Cukup Relevan</small>
                                        <div class="progress">
                                            <div class="progress-bar bg-warning" style="width: 20%"></div>
                                        </div>
                                        <span class="competency-score small">4 alumni (20%)</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            
                </div>
            </div>
            

            <!-- Data Table -->
            <div class="table-container animate-fade-in">
                <h3 class="table-title"><i class="fas fa-table me-2"></i>Data Lengkap Alumni</h3>
                <div class="table-responsive">
                    <table class="table custom-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Tahun Lulus</th>
                                <th>Status</th>
                                <th>Perusahaan/Usaha</th>
                                <th>Jabatan</th>
                                <th>Gaji</th>
                                <th>Kompetensi</th>
                            </tr>
                        </thead>
                        <tbody id="alumniTableBody">
                            <!-- Data will be populated by JavaScript -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Dummy data for 20 alumni
        const alumniData = [
            {nama: "Ahmad Fauzi", tahun: 2023, status: "Bekerja", perusahaan: "PT Teknologi Digital", jabatan: "Frontend Developer", gaji: "Rp 6.500.000", kompetensi: 4.2},
            {nama: "Siti Nurhaliza", tahun: 2022, status: "Wirausaha", perusahaan: "Kedai Kopi Modern", jabatan: "Founder", gaji: "Rp 4.200.000", kompetensi: 4.5},
            {nama: "Budi Santoso", tahun: 2023, status: "Bekerja", perusahaan: "CV Maju Bersama", jabatan: "System Analyst", gaji: "Rp 5.800.000", kompetensi: 4.1},
            {nama: "Dewi Kartika", tahun: 2022, status: "Bekerja", perusahaan: "Bank Mandiri", jabatan: "IT Support", gaji: "Rp 7.200.000", kompetensi: 4.3},
            {nama: "Rizky Pratama", tahun: 2023, status: "Wirausaha", perusahaan: "Digital Marketing Agency", jabatan: "CEO", gaji: "Rp 8.500.000", kompetensi: 4.6},
            {nama: "Maya Sari", tahun: 2022, status: "Tidak Bekerja", perusahaan: "-", jabatan: "-", gaji: "-", kompetensi: 3.8},
            {nama: "Andi Wijaya", tahun: 2023, status: "Bekerja", perusahaan: "Gojek Indonesia", jabatan: "Backend Developer", gaji: "Rp 9.500.000", kompetensi: 4.7},
            {nama: "Lestari Wati", tahun: 2022, status: "Bekerja", perusahaan: "Shopee Indonesia", jabatan: "Data Analyst", gaji: "Rp 8.200.000", kompetensi: 4.4},
            {nama: "Hendro Susilo", tahun: 2023, status: "Wirausaha", perusahaan: "Toko Online Fashion", jabatan: "Owner", gaji: "Rp 5.500.000", kompetensi: 4.0},
            {nama: "Ratna Dewi", tahun: 2022, status: "Bekerja", perusahaan: "Telkomsel", jabatan: "Network Engineer", gaji: "Rp 7.800.000", kompetensi: 4.2},
            {nama: "Agus Setiawan", tahun: 2023, status: "Bekerja", perusahaan: "BCA", jabatan: "Software Engineer", gaji: "Rp 8.800.000", kompetensi: 4.5},
            {nama: "Fitri Handayani", tahun: 2022, status: "Tidak Bekerja", perusahaan: "-", jabatan: "-", gaji: "-", kompetensi: 3.5},
            {nama: "Doni Kurniawan", tahun: 2023, status: "Bekerja", perusahaan: "Tokopedia", jabatan: "Product Manager", gaji: "Rp 12.000.000", kompetensi: 4.8},
            {nama: "Indah Permata", tahun: 2022, status: "Wirausaha", perusahaan: "Catering Sehat", jabatan: "Founder", gaji: "Rp 6.200.000", kompetensi: 4.1},
            {nama: "Bambang Tri", tahun: 2023, status: "Bekerja", perusahaan: "Astra International", jabatan: "IT Consultant", gaji: "Rp 7.500.000", kompetensi: 4.3},
            {nama: "Nur Azizah", tahun: 2022, status: "Bekerja", perusahaan: "Pertamina", jabatan: "System Administrator", gaji: "Rp 8.900.000", kompetensi: 4.4},
            {nama: "Wahyu Hidayat", tahun: 2023, status: "Wirausaha", perusahaan: "Aplikasi Mobile", jabatan: "Co-Founder", gaji: "Rp 7.800.000", kompetensi: 4.6},
            {nama: "Sri Mulyani", tahun: 2022, status: "Bekerja", perusahaan: "Unilever", jabatan: "Data Scientist", gaji: "Rp 11.200.000", kompetensi: 4.9},
            {nama: "Joko Susanto", tahun: 2023, status: "Tidak Bekerja", perusahaan: "-", jabatan: "-", gaji: "-", kompetensi: 3.6},
            {nama: "Erna Sari", tahun: 2022, status: "Bekerja", perusahaan: "Google Indonesia", jabatan: "Cloud Engineer", gaji: "Rp 15.500.000", kompetensi: 5.0}
        ];

        // Populate table
        function populateTable() {
            const tbody = document.getElementById('alumniTableBody');
            alumniData.forEach((alumni, index) => {
                const statusBadge = alumni.status === 'Bekerja' ? 'badge-bekerja' : 
                                   alumni.status === 'Wirausaha' ? 'badge-wirausaha' : 'badge-tidak-bekerja';
                
                const row = `
                    <tr>
                        <td>${index + 1}</td>
                        <td><strong>${alumni.nama}</strong></td>
                        <td>${alumni.tahun}</td>
                        <td><span class="badge ${statusBadge}">${alumni.status}</span></td>
                        <td>${alumni.perusahaan}</td>
                        <td>${alumni.jabatan}</td>
                        <td>${alumni.gaji}</td>
                        <td>
                            <div class="competency-bar">
                                <div class="competency-fill" style="width: ${alumni.kompetensi * 20}%"></div>
                            </div>
                            <span class="competency-score">${alumni.kompetensi}/5.0</span>
                        </td>
                    </tr>
                `;
                tbody.innerHTML += row;
            });
        }

        // Employment Status Chart
        const employmentCtx = document.getElementById('employmentChart').getContext('2d');
        new Chart(employmentCtx, {
            type: 'doughnut',
            data: {
                labels: ['Bekerja', 'Wirausaha', 'Tidak Bekerja'],
                datasets: [{
                    data: [12, 5, 3],
                    backgroundColor: ['#28a745', '#ffc107', '#dc3545'],
                    borderWidth: 0,
                    hoverOffset: 10
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });

        // Competency Chart
        const competencyCtx = document.getElementById('competencyChart').getContext('2d');
        new Chart(competencyCtx, {
            type: 'radar',
            data: {
                labels: ['Etika', 'Keahlian', 'Teknologi', 'Teamwork', 'Komunikasi', 'Pengembangan'],
                datasets: [{
                    label: 'Rata-rata Kompetensi',
                    data: [4.3, 4.2, 4.1, 4.4, 4.0, 4.2],
                    borderColor: '#4facfe',
                    backgroundColor: 'rgba(79, 172, 254, 0.2)',
                    borderWidth: 3,
                    pointBackgroundColor: '#4facfe',
                    pointBorderColor: '#fff',
                    pointHoverBackgroundColor: '#fff',
                    pointHoverBorderColor: '#4facfe'
                }]
            },
            options: {
                responsive: true,
                scales: {
                    r: {
                        beginAtZero: true,
                        max: 5,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });

        // Salary Distribution Chart
        const salaryCtx = document.getElementById('salaryChart').getContext('2d');
        new Chart(salaryCtx, {
            type: 'bar',
            data: {
                labels: ['< 5 Juta', '5-7 Juta', '7-9 Juta', '9-12 Juta', '> 12 Juta'],
                datasets: [{
                    label: 'Jumlah Alumni',
                    data: [2, 6, 5, 3, 1],
                    backgroundColor: ['#ff6b6b', '#4ecdc4', '#45b7d1', '#96ceb4', '#ffeaa7'],
                    borderRadius: 8,
                    borderSkipped: false
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });

        // Curriculum Relevance Chart
        const curriculumCtx = document.getElementById('curriculumChart').getContext('2d');
        new Chart(curriculumCtx, {
            type: 'horizontalBar',
            data: {
                labels: ['Sangat Relevan', 'Relevan', 'Cukup Relevan', 'Kurang Relevan', 'Tidak Relevan'],
                datasets: [{
                    label: 'Jumlah Alumni',
                    data: [9, 7, 4, 0, 0],
                    backgroundColor: ['#28a745', '#17a2b8', '#ffc107', '#fd7e14', '#dc3545'],
                    borderRadius: 6,
                    borderSkipped: false
                }]
            },
            options: {
                responsive: true,
                indexAxis: 'y',
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    x: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            populateTable();
        });
    </script>
</body>
@endsection
</html>