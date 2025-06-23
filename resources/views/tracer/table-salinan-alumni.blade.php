@extends('layouts.admin')

@section('content')

    @php
        $role = Auth::user()->role;
        // $tahun_angkatan = ['2025','2024','2023','2022','2021','2020'];
    @endphp

    <meta name="user-role" content="{{ $role }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="bg-white border-bottom shadow-sm py-4" style="background: linear-gradient(90deg, #f4fcff 65%, #e8fdfa 100%);">
        <div class="content d-flex flex-column flex-sm-row justify-content-between align-items-center">
            <div>
                <h1 class="h3 fw-bold text-primary mb-1"><i class="fa fa-clipboard-list me-2"></i>Data Salinan Tracer Alumni
                </h1>
                <p class="text-muted mb-0">Kelola data tracer alumni secara efisien dan profesional.</p>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="block block-rounded border-0 shadow-sm mb-4">
            <!-- FILTER DAN EXPORT BUTTON -->
            <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2"
                style="padding:1rem 1rem 0 1rem;">
                <div class="d-flex flex-wrap align-items-center gap-2">
                    <label class="fw-semibold mb-0" for="filter-tahun">Filter Tahun Angkatan:</label>
                    <select class="form-select form-select-sm" style="width:auto;min-width:110px;" id="filter-tahun">
                        <option value="">Semua</option>
                        @if (isset($tahun_angkatan))
                            @foreach ($tahun_angkatan as $th)
                                <option value="{{ $th }}">{{ $th }}</option>
                            @endforeach
                        @else
                            @for ($th = date('Y'); $th >= 2015; $th--)
                                <option value="{{ $th }}">{{ $th }}</option>
                            @endfor
                        @endif
                    </select>
                    <button class="btn btn-sm btn-secondary fw-semibold" id="btnDownloadExcel"><i
                            class="fa fa-file-excel me-1"></i> Excel</button>
                    <button class="btn btn-sm btn-secondary fw-semibold" id="btnDownloadPdf"><i
                            class="fa fa-file-pdf me-1"></i> PDF</button>
                    <button class="btn btn-sm btn-secondary fw-semibold" id="btnDownloadPrint"><i
                            class="fa fa-print me-1"></i> Cetak</button>
                </div>
            </div>
            <div class="block-content block-content-full pt-0">
                <div class="table-responsive rounded">
                    <table class="table table-bordered table-striped table-hover align-middle text-center small"
                        id="datatable" style="background: #fff;">
                        <thead class="table-light sticky-top" style="z-index:5;">
                            <tr>
                                <th>#</th>
                                <th>Tanggal Mengisi</th>
                                <th>Nama Alumni</th>
                                <th>Perusahaan</th>
                                <th>Relevansi</th>
                                <th>Status</th>
                                <th>Jabatan</th>
                                <th>Gaji</th>
                                <th>Alamat</th>
                                <th>Saran</th>
                                <th>Dibuat</th>
                                <th>Diperbarui</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Detail -->
    <div class="modal fade" id="modalDetail" tabindex="-1" aria-labelledby="modalDetailLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content shadow">
                <div class="modal-header" style="background: linear-gradient(90deg, #2293d4 40%, #34d0b6 100%);">
                    <h5 class="modal-title fw-bold text-white" id="modalDetailLabel"><i
                            class="fa fa-user-graduate me-1"></i> Detail Tracer Alumni</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-borderless table-hover">
                        <tbody id="detailContent"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css" />
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

    <script>
        $(document).ready(function() {
            const userRole = $('meta[name="user-role"]').attr('content');
            const table = $('#datatable').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                searching: true,
                ajax: {
                    url: "{{ route('listtraceralumni.index') }}",
                    data: function(d) {
                        d.tahun = $('#filter-tahun').val();
                    }
                },
                dom: 'Bfrtip',
                buttons: [{
                        extend: 'excelHtml5',
                        className: 'd-none',
                        exportOptions: {
                            columns: ':not(:last-child)'
                        },
                        filename: 'Rekap_Tracer_Alumni',
                        title: 'Rekap Data Tracer Alumni'
                    },
                    {
                        extend: 'pdfHtml5',
                        className: 'd-none',
                        exportOptions: {
                            columns: ':not(:last-child)'
                        },
                        orientation: 'landscape',
                        pageSize: 'A4',
                        filename: 'Rekap_Tracer_Alumni',
                        title: 'Rekap Data Tracer Alumni'
                    },
                    {
                        extend: 'print',
                        className: 'd-none',
                        exportOptions: {
                            columns: ':not(:last-child)'
                        },
                        title: 'Rekap Data Tracer Alumni'
                    }
                ],
                columns: [{
                        data: 'id',
                        render: (data, type, row, meta) => meta.row + 1
                    },
                    {
                        data: 'tanggal_isi',
                        render: data => data ? new Date(data).toLocaleDateString('id-ID') : '-'
                    },
                    {
                        data: 'alumni.nama_lengkap',
                        name: 'alumni.nama_lengkap',
                        render: data => data ?? '-'
                    },
                    {
                        data: 'nama_perusahaan',
                        render: data => data || 'Belum diisi'
                    },
                    {
                        data: 'relevansi_pekerjaan',
                        render: data => data || '-'
                    },
                    {
                        data: 'bekerja',
                        render: data => data || '-'
                    },
                    {
                        data: 'jabatan',
                        render: data => data || '-'
                    },
                    {
                        data: 'gaji',
                        render: data => data || '-'
                    },
                    {
                        data: 'alamat_pekerjaan',
                        render: data => data || '-'
                    },
                    {
                        data: 'saran',
                        render: data => data || '-'
                    },
                    {
                        data: 'created_at',
                        render: data => new Date(data).toLocaleString('id-ID')
                    },
                    {
                        data: 'updated_at',
                        render: data => new Date(data).toLocaleString('id-ID')
                    },
                    {
                        data: null,
                        orderable: false,
                        searchable: false,
                        render: function(data) {
                            return `
                        <div class="dropdown">
                            <button class="btn btn-sm btn-light border rounded-circle shadow-sm dropdown-toggle py-1 px-2" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fa fa-ellipsis-v"></i>
                            </button>
                            <ul class="dropdown-menu text-start">
                                <li>
                                    <a class="dropdown-item btn-view"
                                        href="#"
                                        data-tanggal="${data.tanggal_isi}"
                                        data-nama="${data.alumni?.nama_lengkap ?? '-'}"
                                        data-perusahaan="${data.nama_perusahaan || '-'}"
                                        data-relevansi="${data.relevansi_pekerjaan || '-'}"
                                        data-status="${data.bekerja || '-'}"
                                        data-jabatan="${data.jabatan || '-'}"
                                        data-gaji="${data.gaji || '-'}"
                                        data-alamat="${data.alamat_pekerjaan || '-'}"
                                        data-saran="${data.saran || '-'}"
                                        data-created="${data.created_at}"
                                        data-updated="${data.updated_at}">
                                        <i class="fa fa-eye me-1 text-info"></i> Detail
                                    </a>
                                </li>
                                <li>
    <a class="dropdown-item btn-edit" href="/listtraceralumni/${data.id}/edit">
        <i class="fa fa-edit me-1 text-warning"></i> Edit
    </a>
</li>

                                ${userRole === 'admin' ? `
                                        <li>
                                            <a class="dropdown-item btn-delete" href="#" data-id="${data.id}">
                                                <i class="fa fa-trash-alt me-1 text-danger"></i> Hapus
                                            </a>
                                        </li>` : ''}
                            </ul>
                        </div>`;
                        }
                    }
                ],
                order: [
                    [0, 'desc']
                ]
            });

            // Filter tahun angkatan
            $('#filter-tahun').on('change', function() {
                table.ajax.reload();
            });

            // Export buttons
            $('#btnDownloadExcel').click(function() {
                table.button('.buttons-excel').trigger();
            });
            $('#btnDownloadPdf').click(function() {
                table.button('.buttons-pdf').trigger();
            });
            $('#btnDownloadPrint').click(function() {
                table.button('.buttons-print').trigger();
            });

            // Modal detail
            $('#datatable').on('click', '.btn-view', function() {
                const fields = {
                    Tanggal: $(this).data('tanggal'),
                    Alumni: $(this).data('nama'),
                    Perusahaan: $(this).data('perusahaan'),
                    Relevansi: $(this).data('relevansi'),
                    Status: $(this).data('status'),
                    Jabatan: $(this).data('jabatan'),
                    Gaji: $(this).data('gaji'),
                    Alamat: $(this).data('alamat'),
                    Saran: $(this).data('saran'),
                    Dibuat: new Date($(this).data('created')).toLocaleString('id-ID'),
                    Diperbarui: new Date($(this).data('updated')).toLocaleString('id-ID')
                };
                let html = '';
                for (const key in fields) {
                    html += `<tr><th class="text-start w-25">${key}</th><td>${fields[key]}</td></tr>`;
                }
                $('#detailContent').html(html);
                $('#modalDetail').modal('show');
            });

            $(document).ready(function () {
        // Setup CSRF token for AJAX
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Hapus data
        $('#datatable').on('click', '.btn-delete', function () {
            const id = $(this).data('id');

            Swal.fire({
                title: 'Yakin ingin menghapus?',
                text: 'Data tidak bisa dikembalikan setelah dihapus!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/listtraceralumni/${id}`,
                        type: 'DELETE',
                        success: function (res) {
                            $('#datatable').DataTable().ajax.reload();
                            Swal.fire('Terhapus!', res.message, 'success');
                        },
                        error: function (xhr) {
                            Swal.fire('Gagal', 'Tidak dapat menghapus data.', 'error');
                        }
                    });
                }
            });
        });
    });
        });
    </script>
    <style>
        .block-header {
            min-height: 60px;
        }

        .btn.btn-secondary {
            font-weight: 600;
        }

        .dataTables_filter label {
            font-weight: 600;
        }

        .dataTables_filter input {
            min-width: 150px;
            border-radius: 6px;
        }

        #datatable th,
        #datatable td {
            vertical-align: middle;
        }

        #datatable thead th {
            background: #eaf6ff;
            color: #2293d4;
            font-weight: 700;
            letter-spacing: .01em;
        }

        .dataTables_wrapper .dataTables_filter {
            float: right;
            margin-bottom: 1rem;
        }

        .dataTables_wrapper .dataTables_length,
        .dataTables_wrapper .dataTables_info {
            float: left;
            margin-top: .25rem;
        }

        @media (max-width: 767.98px) {

            .block-header,
            .content {
                padding-left: 0.5rem !important;
                padding-right: 0.5rem !important;
            }

            #datatable th,
            #datatable td {
                font-size: .95rem;
            }
        }
    </style>
@endsection
