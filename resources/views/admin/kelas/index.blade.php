@extends('admin.layouts.adminmaster')
@section('content')
        <div class="page-heading">
            <div class="page-title">
                <div class="row">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Master Data</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Kelas</li>
                        </ol>
                    </nav>
                </div>
            </div>

        <!-- Tambah & Update -->
        <section class="section">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Form Kelas</h3>
                </div>
                <div class="card-body">
                    <form id="form-kelas" class="form form-horizontal" data-parsley-validate>
                        <input type="hidden" id="kelas-id" name="kelas-id">
                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-4 col-12">
                                    <div class="form-group">
                                        <label for="nama_kelas">Nama Kelas</label>
                                        <input type="text" class="form-control" id="nama_kelas" name="nama_kelas" placeholder="Nama Kelas" data-parsley-required="true" data-parsley-error-message="Nama kelas tidak boleh kosong." onkeyup="this.value = this.value.toUpperCase();">
                                    </div>
                                </div>
                                <div class="col-md-4 col-12">
                                    <div class="form-group">
                                        <label for="tingkat">Tingkat</label>
                                        <select class="selectpicker" data-style="btn-outline-light" id="tingkat" name="tingkat" data-parsley-required="true" data-parsley-error-message="Tingkat kelas harus dipilih.">
                                            <option value="" disabled selected>- Pilih Tingkat Kelas -</option>
                                            <option value="10">10</option>
                                            <option value="11">11</option>
                                            <option value="12">12</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4 col-12">
                                    <div class="form-group">
                                        <label for="id_jurusan">Jurusan</label>
                                        <select class="selectpicker" data-style="btn-outline-light" id="jurusan" name="id_jurusan" selected data-parsley-required="true" data-parsley-error-message="Jurusan kelas harus dipilih.">
                                            <option value="" disabled selected>- Pilih Jurusan kelas -</option>
                                            @foreach ($dataJurusan as $jurusan)
                                            <option value="{{ $jurusan->id }}">{{ $jurusan->nama_jurusan }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 d-flex justify-content-end">
                                <button type="submit" id="BtnSimpan" class="btn btn-primary ms-1">
                                <i class="bi bi-plus-square-fill"></i> Simpan
                                </button>
                                <button type="button" id="BtnReset" class="btn btn-secondary ms-1">
                                <i class="bi bi-dash-square-fill"></i> Reset
                                </button>
                                </div>
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
            <h3 class="card-title">Data Kelas</h3>
        </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped" style="width: 100%;" id="tabel-kelas">
                            <thead>
                                <tr>
                                <th>#</th>
                                <th class="text-center">Kelas</th>
                                <th class="text-center">Tingkat</th>
                                <th class="text-center">Jurusan</th>
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
            var table = $('#tabel-kelas').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('kelas.index') }}",
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
                    { data: 'nama_kelas', name: 'nama_kelas',  className: 'text-center' },
                    { data: 'tingkat', name: 'tingkat',  className: 'text-center' },
                    { data: 'nama_jurusan', name: 'nama_jurusan' },
                    {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false,
                    className: 'text-center'
                }
                ],
                order: [[3, 'asc'],[2, 'asc'],[1, 'asc']]
            });

            $(document).on('click', '.edit', function () {
                var id = $(this).attr('id');
                $.ajax({
                    url: '/kelas/' + id + '/edit',
                    type: 'GET',
                    success: function (response) {
                    // Populate form fields with data for editing
                    $('#kelas-id').val(response.id);
                    $('#nama_kelas').val(response.nama_kelas);
                    $('#tingkat').selectpicker('val', response.tingkat);
                    $('#jurusan').selectpicker('val', response.id_jurusan);
                    updateButton('Update');
                    $('#form-kelas').parsley().reset();
                },
                    error: function (xhr, status, error) {
                        showErrorAlert('Terjadi kesalahan saat mengambil data kelas.');
                    }
                });
            });

            $(document).on('click', '.delete', function () {
            var id = $(this).attr('id');
            swal.fire({
                title: "Hapus data kelas",
                text: "Anda yakin untuk menghapus data ini?",
                icon: 'question',
                showCancelButton: true,
                showCloseButton: false,
                cancelButtonColor: '#001473',
                confirmButtonColor: '#d33',
                confirmButtonText: 'Ya',
                cancelButtonText: 'Batal'
            }).then(function(res) {
                if (res.value) {
                    // The AJAX request should be inside the then function
                    $.ajax({
                        url: '/kelas/' + id,
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function (response) {
                            showSuccessAlert(response.success);
                            var table = $('#tabel-kelas').DataTable();
                            table.ajax.reload();
                        },
                        error: function (xhr, status, error) {
                            showErrorAlert('Terjadi kesalahan saat menghapus kelas.');
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
                timer: 1500,
                timerProgressBar: true,
                showConfirmButton: false
            });
        }

        function showErrorAlert(message) {
            Swal.fire({
                title: 'Terjadi Kesalahan',
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
            $('#form-kelas').parsley().reset();
            updateButton('Simpan');
            $('#form-kelas')[0].reset();
            $('#kelas-id').val('');
            $('#tingkat').val('').selectpicker('refresh');
            $('#jurusan').val('').selectpicker('refresh');
            // Add additional code to reset other form fields if needed
        }

        $('#form-kelas').submit(function (event) {
            saveOrUpdateKelas(event);
        });

        function saveOrUpdateKelas(event) {
        event.preventDefault();

        // Validate the form using Parsley
        if ($('#form-kelas').parsley().validate()) {
            var id = $('#kelas-id').val();
            var url = id ? '/kelas/' + id : '{{ route("kelas.store") }}';
            var method = id ? 'PUT' : 'POST';

            var formData = $('#form-kelas').serialize();

            $.ajax({
                url: url,
                type: method,
                data: formData + '&_token={{ csrf_token() }}',
                success: function (response) {
                    showSuccessAlert(response.success);

                    var table = $('#tabel-kelas').DataTable();
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