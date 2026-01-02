@extends('admin.layouts.adminmaster')
@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Master Data</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Mata Pelajaran</li>
                    </ol>
                </nav>
            </div>
        </div>

        <!-- Tambah & Update -->
        <section class="section">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Form Mata Pelajaran</h3>
                </div>
                <div class="card-body">
                <form id="form-mapel" class="form form-horizontal" data-parsley-validate>
                <input type="hidden" id="pelajaran-id" name="pelajaran-id">
                    <div class="form-body">
                        <div class="row">
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                <label for="kode_pel">Kode</label>
                                <input type="text" class="form-control" id="kode_pel" name="kode_pel" placeholder="Kode" data-parsley-required="true" data-parsley-error-message="Kode tidak boleh kosong." onkeyup="this.value = this.value.toUpperCase();">
                            </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                <label for="nama_pel">Mata Pelajaran</label>
                                <input type="text" class="form-control" id="nama_pel" name="nama_pel" placeholder="Nama Mata Pelajaran" data-parsley-required="true" data-parsley-error-message="Mata pelajaran tidak boleh kosong.">
                            </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                <label for="jenis">Jenis</label>
                                <select class="selectpicker" data-style="btn-outline-light" id="jenis" name="jenis" data-parsley-required="true" data-parsley-error-message="Jenis mata pelajaran harus dipilih.">
                                    <option value="" disabled selected>- Pilih Jenis Mata Pelajaran -</option>
                                    <option value="TEORI">TEORI</option>
                                    <option value="PRODUKTIF">PRODUKTIF</option>
                                </select>
                            </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                <label for="id_jurusan">Jurusan</label>
                                <select class="selectpicker" data-style="btn-outline-light" id="jurusan" name="id_jurusan" selected data-parsley-required="true" data-parsley-error-message="jurusan mata pelajaran harus dipilih.">
                                    <option value="" disabled selected>- Pilih jurusan Mata Pelajaran -</option>
                                    @foreach ($dataJurusan as $jurusan)
                                        <option value="{{ $jurusan->id }}">{{ $jurusan->nama_jurusan }}</option>
                                    @endforeach
                                </select>
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

        <!-- Datatables Mata Pelajaran -->
        <section class="section">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Data Mata Pelajaran</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped" style="width: 100%;" id="tabel-mapel">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Kode</th>
                                    <th>Nama Pelajaran</th>
                                    <th>Jenis</th>
                                    <th>Jurusan</th>
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
                var table = $('#tabel-mapel').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('pelajaran.index') }}",
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
                    { data: 'kode_pel', name: 'kode_pel' },
                    { data: 'nama_pel', name: 'nama_pel' },
                    { data: 'jenis', name: 'jenis' },
                    { data: 'nama_jurusan', name: 'nama_jurusan' },
                    {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false,
                    className: 'text-center'
                }
                ],
                order: [[1, 'asc']]
            });
            $(document).on('click', '.edit', function () {
            var id = $(this).attr('id');
            $.ajax({
                url: '/pelajaran/' + id + '/edit',
                type: 'GET',
                success: function (response) {
                    // Populate form fields with data for editing
                    $('#pelajaran-id').val(response.id);
                    $('#kode_pel').val(response.kode_pel);
                    $('#nama_pel').val(response.nama_pel);
                    $('#jenis').selectpicker('val', response.jenis);
                    $('#jurusan').selectpicker('val', response.id_jurusan);
                    updateButton('Update');
                    $('#form-mapel').parsley().reset();
                },
                error: function (xhr, status, error) {
                    showErrorAlert('Terjadi kesalahan saat mengambil data pelajaran.');
                }
            });
        });

            $(document).on('click', '.delete', function () {
            var id = $(this).attr('id');
            swal.fire({
                title: "Hapus data mata pelajaran",
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
                        url: '/pelajaran/' + id,
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function (response) {
                            showSuccessAlert(response.success);
                            var table = $('#tabel-mapel').DataTable();
                            table.ajax.reload();
                        },
                        error: function (xhr, status, error) {
                            showErrorAlert('Terjadi kesalahan saat menghapus pelajaran.');
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
            $('#form-mapel').parsley().reset();
            updateButton('Simpan');
            $('#form-mapel')[0].reset();
            $('#pelajaran-id').val('');
            $('#jenis').val('').selectpicker('refresh');
            $('#jurusan').val('').selectpicker('refresh');
            // Add additional code to reset other form fields if needed
        }

        $('#form-mapel').submit(function (event) {
            saveOrUpdateMapel(event);
        });

        function saveOrUpdateMapel(event) {
        event.preventDefault();

        // Validate the form using Parsley
        if ($('#form-mapel').parsley().validate()) {
            var id = $('#pelajaran-id').val();
            var url = id ? '/pelajaran/' + id : '{{ route("pelajaran.store") }}';
            var method = id ? 'PUT' : 'POST';

            var formData = $('#form-mapel').serialize();

            $.ajax({
                url: url,
                type: method,
                data: formData + '&_token={{ csrf_token() }}',
                success: function (response) {
                    showSuccessAlert(response.success);

                    var table = $('#tabel-mapel').DataTable();
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
