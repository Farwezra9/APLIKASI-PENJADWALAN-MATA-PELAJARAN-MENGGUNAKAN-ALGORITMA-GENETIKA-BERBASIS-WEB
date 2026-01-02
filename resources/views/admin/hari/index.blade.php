@extends('admin.layouts.adminmaster')
@section('content')
        <div class="page-heading">
            <div class="page-title">
                <div class="row">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Master Data</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Hari</li>
                        </ol>
                    </nav>
                </div>
            </div>

         <!-- Tambah & Update -->
        <section class="section">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Form Hari</h3>
                </div>
                <div class="card-body">
                <form id="form-hari" class="form form-horizontal" data-parsley-validate>
                <input type="hidden" id="hari-id" name="hari-id">
                    <div class="form-body">
                        <div class="row">
                            <div class="col-md-5 col-12">
                            <div class="form-group">
                                <label for="kode_hari">Kode Hari</label>
                                <input type="number" class="form-control" id="kode_hari" name="kode_hari" placeholder="Kode Hari" data-parsley-required="true" data-parsley-error-message="Kode hari tidak boleh kosong.">
                            </div>
                            </div>
                            <div class="col-md-7 col-12">
                                <div class="form-group">
                                <label for="nama_hari">Nama Hari</label>
                                <input type="text" class="form-control" id="nama_hari" name="nama_hari" placeholder="Nama hari" data-parsley-required="true" data-parsley-error-message="Nama hari tidak boleh kosong.">
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

        <!-- Datatables -->
        <section class="section">
            <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">Data Hari</h3>
        </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped" style="width: 100%;" id="tabel-hari">
                            <thead>
                                <tr>
                                <th>Kode</th>
                                <th>Hari</th>
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
            var table = $('#tabel-hari').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('hari.index') }}",
                    type: 'GET'
                },
                columns: [
                    { data: 'kode_hari', name: 'kode_hari' },
                    { data: 'nama_hari', name: 'nama_hari' },
                    {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false,
                    className: 'text-center'
                }
                ],
                order: [[0, 'asc']]
            });
            $(document).on('click', '.edit', function () {
                var id = $(this).attr('id');
                $.ajax({
                    url: '/hari/' + id + '/edit',
                    type: 'GET',
                    success: function (response) {
                    // Populate form fields with data for editing
                    $('#hari-id').val(response.id);
                    $('#kode_hari').val(response.kode_hari);
                    $('#nama_hari').val(response.nama_hari);
                    updateButton('Update');
                    $('#form-hari').parsley().reset();
                    },
                    error: function (xhr, status, error) {
                        showErrorAlert('Terjadi kesalahan saat mengambil data hari.');
                    }
                });
            });

            $(document).on('click', '.delete', function () {
            var id = $(this).attr('id');
            swal.fire({
                title: "Hapus data hari",
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
                        url: '/hari/' + id,
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function (response) {
                            showSuccessAlert(response.success);
                            var table = $('#tabel-hari').DataTable();
                            table.ajax.reload();
                        },
                        error: function (xhr, status, error) {
                            showErrorAlert('Terjadi kesalahan saat menghapus hari.');
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
                title: 'Kesalahan',
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
            $('#form-hari').parsley().reset();
            updateButton('Simpan');
            $('#form-hari')[0].reset();
            $('#hari-id').val('');
            // Add additional code to reset other form fields if needed
        }

        $('#form-hari').submit(function (event) {
            saveOrUpdateHari(event);
        });

        function saveOrUpdateHari(event) {
        event.preventDefault();

        // Validate the form using Parsley
        if ($('#form-hari').parsley().validate()) {
            var id = $('#hari-id').val();
            var url = id ? '/hari/' + id : '{{ route("hari.store") }}';
            var method = id ? 'PUT' : 'POST';

            var formData = $('#form-hari').serialize();

            $.ajax({
                url: url,
                type: method,
                data: formData + '&_token={{ csrf_token() }}',
                success: function (response) {
                    showSuccessAlert(response.success);

                    var table = $('#tabel-hari').DataTable();
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