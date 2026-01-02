@extends('admin.layouts.adminmaster')
@section('content')
        <div class="page-heading">
            <div class="page-title">
                <div class="row">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Master Data</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Jurusan</li>
                        </ol>
                    </nav>
                </div>
            </div>

        <!-- Tambah & Update -->
        <section class="section">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Form Jurusan</h3>
                </div>
                <div class="card-body">
                <form id="form-jurusan" class="form form-horizontal" data-parsley-validate>
                <input type="hidden" id="jurusan-id" name="jurusan-id">
                    <div class="form-body">
                        <div class="row">
                            <div class="col-md-2">
                                    <label for="nama_jurusan">Nama Jurusan</label>
                            </div>
                            <div class="col-md-10 form-group">
                                <input type="text" class="form-control" id="nama_jurusan" name="nama_jurusan" class="form-control" placeholder="Nama Jurusan" data-parsley-required="true" data-parsley-error-message="Nama jurusan tidak boleh kosong.">
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

        <!-- Datatables Jurusan -->
        <section class="section">
            <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">Data Jurusan</h3>
        </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped" style="width: 100%;" id="tabel-jurusan">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nama Jurusan</th>
                                    <th text="center">Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </section>
        <!-- Datatables jurusan end -->
        </div>
        @endsection
   
@section('scripts')
    <script>
        $(document).ready(function () {
            $('#form-tambah-jurusan').parsley();
            $('#form-edit-jurusan').parsley();
            var table = $('#tabel-jurusan').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('jurusan.index') }}",
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
                    { data: 'nama_jurusan', name: 'nama_jurusan' },
                    {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false,
                    className: 'text-center'
                }
                ],
            });

            $(document).on('click', '.edit', function () {
                var id = $(this).attr('id');
                $.ajax({
                    url: '/jurusan/' + id + '/edit',
                    type: 'GET',
                    success: function (response) {
                    // Populate form fields with data for editing
                    $('#jurusan-id').val(response.id);
                    $('#nama_jurusan').val(response.nama_jurusan);
                    updateButton('Update');
                    $('#form-jurusan').parsley().reset();
                    },
                    error: function (xhr, status, error) {
                        showErrorAlert('Terjadi kesalahan saat mengambil data jurusan.');
                    }
                });
            });
            $(document).on('click', '.delete', function () {
                var id = $(this).attr('id');
            swal.fire({
                title: "Hapus Data Jurusan",
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
                        url: '/jurusan/' + id,
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function (response) {
                            showSuccessAlert(response.success);
                            var table = $('#tabel-jurusan').DataTable();
                            table.ajax.reload();
                        },
                        error: function (xhr, status, error) {
                            showErrorAlert('Terjadi kesalahan saat menghapus jurusan.');
                        }
                    });
                }
            });
        });
        });

        function showSuccessAlert(message) {
            Swal.fire({
                title: 'Sukses',
                text: message,
                icon: 'success',
                timer: 1500,
                timerProgressBar: true,
                showConfirmButton: false
            });
        }

        function showErrorAlert(message) {
            Swal.fire({
                title: 'Error',
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
            $('#form-jurusan').parsley().reset();
            updateButton('Simpan');
            $('#form-jurusan')[0].reset();
            $('#jurusan-id').val('');
            // Add additional code to reset other form fields if needed
        }

        $('#form-jurusan').submit(function (event) {
            saveOrUpdateJurusan(event);
        });

        function saveOrUpdateJurusan(event) {
        event.preventDefault();

        // Validate the form using Parsley
        if ($('#form-jurusan').parsley().validate()) {
            var id = $('#jurusan-id').val();
            var url = id ? '/jurusan/' + id : '{{ route("jurusan.store") }}';
            var method = id ? 'PUT' : 'POST';

            var formData = $('#form-jurusan').serialize();

            $.ajax({
                url: url,
                type: method,
                data: formData + '&_token={{ csrf_token() }}',
                success: function (response) {
                    showSuccessAlert(response.success);

                    var table = $('#tabel-jurusan').DataTable();
                    table.ajax.reload();
                    resetForm();
                },
                error: function (xhr, status, error) {
                    var errors = xhr.responseJSON.error;
                    var errorMessages = '';

                    for (var key in errors) {
                        errorMessages += errors[key];
                    }

                    showErrorAlert(errorMessages);
                }
            });
        }
    }
    </script>
 @endsection