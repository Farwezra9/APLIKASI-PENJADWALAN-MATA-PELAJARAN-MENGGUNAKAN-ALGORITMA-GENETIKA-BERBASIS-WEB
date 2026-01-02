@extends('admin.layouts.adminmaster')
@section('content')
<div class="page-heading">
        <div class="page-title">
            <div class="row">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Master Data</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Guru</li>
                    </ol>
                </nav>
            </div>
        </div>

                <!-- Tambah & Update -->
                <section class="section">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Form Guru</h3>
                </div>
                <div class="card-body">
                <form id="form-guru" class="form form-horizontal" data-parsley-validate>
                <input type="hidden" id="guru-id" name="guru-id">
                    <div class="form-body">
                        <div class="row">
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                <label for="nip">NIP</label>
                                <input type="number" class="form-control" id="nip" name="nip" placeholder="NIP" data-parsley-required="true" data-parsley-error-message="NIP tidak boleh kosong.">
                            </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                <label for="nama">Nama</label>
                                <input type="text" class="form-control" id="nama" name="nama" placeholder="Nama Guru" data-parsley-required="true" data-parsley-error-message="Nama guru tidak boleh kosong.">
                            </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" id="email" name="email" placeholder="Email Guru" data-parsley-required="true" data-parsley-error-message="Email guru tidak boleh kosong.">
                            </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                <label for="pangkat">Jabatan</label>
                                <input type="text" class="form-control" id="pangkat" name="pangkat" placeholder="Jabatan Guru">
                            </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                <label for="alamat">Alamat</label>
                                <textarea class="form-control" id="alamat" name="alamat" placeholder="Alamat guru" rows="3"></textarea>
                            </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                <label for="notelp">No Telephone</label>
                                <input type="number" class="form-control" id="notelp" name="notelp" class="form-control" placeholder="No Telephone Guru">
                            </div>
                            </div>
                        </div>
                        <div class="col-12 d-flex justify-content-end">
                            <button type="submit" id="BtnSimpan" class="btn btn-primary ms-1">
                            <i class="bi bi-plus-square-fill"></i> Simpan
                            </button>
                            <button type="button" id="BtnReset" class="btn btn-secondary ms-1">
                            <i class="bi bi-dash-square-fill"></i> Reset
                            </button>
                        </div>
                    </div>
                </form>
                </div>
            </div>
        </section>
        <!-- Tambah & Update End -->

          <!-- Modal import -->
    <div class="modal fade text-left" id="modal-import" tabindex="-1" role="dialog" aria-labelledby="myModalLabel160" aria-hidden="true" style="display:none;">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title white" id="myModalLabel160">FORM IMPORT GURU</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i data-feather="x"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="import-guru" class="form form-horizontal" enctype="multipart/form-data" data-parsley-validate>
                        <div class="form-body">
                            <div class="row">
                                    <div class="mb-3">
                                        <label for="formFile" class="form-label">Pilih File Data Guru</label>
                                        <input class="form-control" type="file" name="file" id="file">
                                    </div>
                                <div class="modal-footer">
                                    <button type="submit" id="BtnImport" class="btn btn-primary ms-1">
                                        <i class="bx bx-check d-block d-sm-none"></i>
                                        <span class="d-sm-block d-none">Import</span>
                                    </button>
                                    <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
                                        <i class="bx bx-x d-block d-sm-none"></i>
                                        <span class="d-none d-sm-block">Tutup</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
        <!-- Modal Import End -->

        <!-- Datatables -->
        <section class="section">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">Data Guru</h3>
            <div class="d-flex">
                <button class="btn btn-primary" id="cetakBtn">
                    <i class="bi bi-printer-fill"></i> Cetak
                </button>
                <button class="btn btn-primary ms-3" onclick="showImportModal();">
                    <i class="bi bi-cloud-arrow-up-fill"></i> Import
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped" style="width: 100%;" id="tabel-guru">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th class="text-center">NIP</th>
                            <th class="text-center">Nama</th>
                            <th class="text-center">Email</th>
                            <th class="text-center">Jabatan</th>
                            <th class="text-center">Alamat</th>
                            <th class="text-center">No Telp</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</section>

        <!-- Datatables end -->
    </div>
@endsection
    @section('scripts')
    <script>
        $(document).ready(function () {
            var table = $('#tabel-guru').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('guru.index') }}",
                    type: 'GET'
                },
                columns: [
                  {
                        data: null,
                        name: 'id',
                        render: function (data, type, row, meta) {
                            // Menggunakan nomor urut berdasarkan posisi baris di seluruh data
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    },
                    { data: 'nip', name: 'nip', className: 'text-center' },
                    { data: 'nama', name: 'nama' },
                    { data: 'email', name: 'email' },
                    { data: 'pangkat', name: 'pangkat' },
                    { data: 'alamat', name: 'alamat' },
                    { data: 'notelp', name: 'notelp' },
                    {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                }
                ],
                order: [[0, 'asc']]
            });
            $(document).on('click', '.edit', function () {
              var id = $(this).attr('id');
                $.ajax({
                    url: '/guru/' + id + '/edit',
                    type: 'GET',
                    success: function (response) {
                    // Populate form fields with data for editing
                    $('#guru-id').val(response.id);
                    $('#nip').val(response.nip);
                    $('#nama').val(response.nama);
                    $('#email').val(response.email);
                    $('#pangkat').val(response.pangkat);
                    $('#alamat').val(response.alamat);
                    $('#notelp').val(response.notelp);
                    updateButton('Update');
                    $('#form-guru').parsley().reset();
                    },
                    error: function (xhr, status, error) {
                        showErrorAlert('Terjadi kesalahan saat mengambil data guru.');
                    }
                });
            });

            $(document).on('click', '.delete', function () {
            var id = $(this).attr('id');
            swal.fire({
                title: "Hapus data guru",
                text: "Anda yakin untuk menghapus data ini?",
                icon: 'question',
                showCancelButton: true,
                showCloseButton: false,
                cancelButtonColor: '#999',
                confirmButtonColor: '#435EBE',
                confirmButtonText: 'Ya',
                cancelButtonText: 'Batal'
            }).then(function(res) {
                if (res.value) {
                    // The AJAX request should be inside the then function
                    $.ajax({
                        url: '/guru/' + id,
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function (response) {
                            showSuccessAlert(response.success);
                            var table = $('#tabel-guru').DataTable();
                            table.ajax.reload();
                        },
                        error: function (xhr, status, error) {
                            showErrorAlert('Terjadi kesalahan saat menghapus guru.');
                        }
                    });
                }
            });
        });
        });

        function showSuccessAlert(message) {
            Swal.fire({
                title: 'Berhasil',
                text: message,
                icon: 'success',
                timer: 1000,
                timerProgressBar: true,
                showConfirmButton: false
            });
        }

        function showErrorAlert(message) {
            Swal.fire({
                title: 'Terjadi Kesalahan.',
                text: message,
                icon: 'error',
                showConfirmButton: true,
                showCloseButton: true
            });
        }
        function updateButton(text) {
            // Update the text of BtnSimpan
            $('#BtnSimpan').html('<i class="bi bi-plus-square-fill"></i> ' + text);
        }
        $(document).on('click', '#BtnReset', function () {
            resetForm();
        });

        // Function to reset the form fields to the "Tambah" state
        function resetForm() {
            $('#form-guru').parsley().reset();
            updateButton('Simpan');
            $('#form-guru')[0].reset();
            $('#guru-id').val('');
            // Add additional code to reset other form fields if needed
        }

        $('#form-guru').submit(function (event) {
            saveOrUpdateGuru(event);
        });
        function showImportModal() {
            $('#modal-import').modal('show');
            $('#import-guru').trigger('reset');
        }
        function saveOrUpdateGuru(event) {
        event.preventDefault();

        // Validate the form using Parsley
        if ($('#form-guru').parsley().validate()) {
            var id = $('#guru-id').val();
            var url = id ? '/guru/' + id : '{{ route("guru.store") }}';
            var method = id ? 'PUT' : 'POST';

            var formData = $('#form-guru').serialize();

            $.ajax({
                url: url,
                type: method,
                data: formData + '&_token={{ csrf_token() }}',
                success: function (response) {
                    showSuccessAlert(response.success);

                    var table = $('#tabel-guru').DataTable();
                    table.ajax.reload();
                    resetForm();
                },
                error: function (xhr, status, error) {
                    showErrorAlert('Terjadi kesalahan saat menyimpan data guru.');
                }
            });
        }
    }
    $('#cetakBtn').click(function () {
            Swal.fire({
                title: "Konfirmasi",
                text: "Cetak data guru?",
                icon: 'question',
                showCancelButton: true,
                showCloseButton: false,
                cancelButtonColor: '#999',
                confirmButtonColor: '#435EBE',
                confirmButtonText: 'Ya',
                cancelButtonText: 'Batal'
            }).then(function (result) {
                if (result.value) {
                    window.location = "{{ route('cetak.guru') }}";
                    Swal.fire({
                    icon: 'success',
                    text: 'Data guru berhasil dicetak!',
                    title: "Sukses",
                    showConfirmButton: true
                });
                }
            });
        });
        $('#import-guru').submit(function (event) {
            event.preventDefault();

            var formData = new FormData(this);
            formData.append('_token', '{{ csrf_token() }}');  // Tambahkan token CSRF ke FormData

            $.ajax({
                url: "{{ route('guru.import') }}",
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function (response) {
                    showSuccessAlert(response.success);
                    $('#modal-import').modal('hide');
                    $('#tabel-guru').DataTable().ajax.reload();
                },
                error: function (xhr, status, error) {
                    showErrorAlert('Terjadi kesalahan saat mengimport data guru.');
                }
            });
        });
    </script>
 @endsection