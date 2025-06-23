<!DOCTYPE html>
<html lang="id">

@include('components.admin.head')

<body>
    <div id="page-container"
        class="sidebar-o sidebar-dark enable-page-overlay side-scroll page-header-fixed main-content-narrow">

        @include('components.admin.admin-header')
        @include('components.admin.sidebar')
        @include('components.admin.side-overlay')

        <main id="main-container">

            <div class="bg-body-light border-bottom py-3">
                <div class="content d-flex flex-column flex-sm-row justify-content-between align-items-center">
                    <div>
                        <h1 class="h3 fw-bold mb-0">Data Dosen</h1>
                    </div>
                </div>
            </div>

            <div class="content">
                <div class="block block-rounded border shadow-sm">
                    <div class="block-content block-content-full">
                        <div class="mb-4">
                            <label for="filter-tahun" class="form-label fw-semibold">Filter Tahun Akademik:</label>
                            <select id="filter-tahun" class="form-select w-auto d-inline-block">
                                <option value="">Memuat data...</option>
                            </select>
                        </div>

                        <div class="table-responsive">
                            <table
                                class="table table-bordered table-striped table-hover align-middle js-dataTable-full w-100">
                                <thead class="table-light text-center">
                                    <tr>
                                        <th>Kode Dosen</th>
                                        <th>Nama Dosen</th>
                                        <th>Prodi</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </main>

        @include('components.admin.footer')
    </div>

    {{-- Modal Detail Dosen --}}
    <div class="modal fade" id="modalDetailDosen" tabindex="-1" aria-labelledby="modalDetailLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content shadow-lg">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="modalDetailLabel">Detail Dosen</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <p><strong>Kode Dosen:</strong> <span id="detail-kode"></span></p>
                    <p><strong>Nama Dosen:</strong> <span id="detail-nama"></span></p>
                    <p><strong>Program Studi:</strong> <span id="detail-prodi"></span></p>
                    <p><strong>Status:</strong> <span id="detail-status"></span></p>
                </div>
            </div>
        </div>
    </div>

    {{-- Scripts --}}
    <script src="assets/js/lib/jquery.min.js"></script>
    <script src="assets/js/oneui.app.min.js"></script>
    <script src="assets/js/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="assets/js/plugins/datatables-bs5/js/dataTables.bootstrap5.min.js"></script>
    <script src="assets/js/plugins/datatables-buttons/dataTables.buttons.min.js"></script>
    <script src="assets/js/plugins/datatables-buttons-bs5/js/buttons.bootstrap5.min.js"></script>
    <script src="assets/js/plugins/datatables-buttons-jszip/jszip.min.js"></script>
    <script src="assets/js/plugins/datatables-buttons-pdfmake/pdfmake.min.js"></script>
    <script src="assets/js/plugins/datatables-buttons-pdfmake/vfs_fonts.js"></script>
    <script src="assets/js/plugins/datatables-buttons/buttons.html5.min.js"></script>
    <script src="assets/js/plugins/datatables-buttons/buttons.print.min.js"></script>

    <script>
        $(document).ready(function () {
            // Filter tahun akademik
            $.ajax({
                url: "{{ route('api.tahun-akademik') }}",
                type: 'GET',
                success: function (response) {
                    let select = $('#filter-tahun');
                    select.empty();

                    if (response.status && response.data.length) {
                        response.data.forEach(item => {
                            const selected = item.status == 1 ? 'selected' : '';
                            select.append(`<option value="${item.kode}" ${selected}>${item.tahun_akademik}</option>`);
                        });
                        $('.js-dataTable-full').DataTable().ajax.reload();
                    } else {
                        select.append(`<option value="">Tidak ada data</option>`);
                    }
                },
                error: function () {
                    $('#filter-tahun').html('<option value="">Gagal memuat</option>');
                }
            });

            $('#filter-tahun').on('change', function () {
                $('.js-dataTable-full').DataTable().ajax.reload();
            });

            // DataTable config
            $('.js-dataTable-full').DataTable({
                paging: true,
                searching: true,
                ordering: true,
                responsive: true,
                autoWidth: false,
                pageLength: 5,
                dom: "<'row mb-3'" +
                    "<'col-md-6 d-flex align-items-center gap-2'B>" +
                    "<'col-md-6 text-end'f>>" +
                    "<'row'<'col-sm-12 table-responsive'tr>>" +
                    "<'row'<'col-sm-5'i><'col-sm-7'p>>",
                buttons: [
                    {
                        extend: 'excelHtml5',
                        className: 'btn btn-outline-success btn-sm',
                        text: '<i class="fa fa-file-excel me-1"></i> Excel',
                        exportOptions: { columns: ':not(:last-child)' }
                    },
                    {
                        extend: 'pdfHtml5',
                        className: 'btn btn-outline-danger btn-sm',
                        text: '<i class="fa fa-file-pdf me-1"></i> PDF',
                        orientation: 'landscape',
                        pageSize: 'A4',
                        exportOptions: { columns: ':not(:last-child)' }
                    },
                    {
                        extend: 'print',
                        className: 'btn btn-outline-info btn-sm',
                        text: '<i class="fa fa-print me-1"></i> Cetak',
                        exportOptions: { columns: ':not(:last-child)' }
                    }
                ],
                ajax: {
                    url: '/api/dosen',
                    type: 'GET',
                    data: function (d) {
                        d.kode_tahun_akademik = $('#filter-tahun').val();
                    },
                    dataSrc: function (json) {
                        let return_data = [];

                        if (json.status && json.data.length) {
                            $.each(json.data, function (i, item) {
                                return_data.push({
                                    'kd_dosen': item.kd_dosen || '',
                                    'name': item.name || '',
                                    'prodi': item.prodi || '-',
                                    'status': item.status || '-',
                                    'aksi': `<button class="btn btn-sm btn-outline-primary btn-detail"
                                                data-kode="${item.kd_dosen}"
                                                data-nama="${item.name}"
                                                data-prodi="${item.prodi}"
                                                data-status="${item.status}">
                                                <i class="fa fa-eye me-1"></i>
                                            </button>`
                                });
                            });
                        }
                        return return_data;
                    }
                },
                columns: [
                    { data: 'kd_dosen' },
                    { data: 'name' },
                    { data: 'prodi' },
                    { data: 'status' },
                    { data: 'aksi', orderable: false }
                ]
            });

            // Modal event listener
            $(document).on('click', '.btn-detail', function () {
                $('#detail-kode').text($(this).data('kode'));
                $('#detail-nama').text($(this).data('nama'));
                $('#detail-prodi').text($(this).data('prodi'));
                $('#detail-status').text($(this).data('status'));
                $('#modalDetailDosen').modal('show');
            });
        });
    </script>

</body>
</html>
