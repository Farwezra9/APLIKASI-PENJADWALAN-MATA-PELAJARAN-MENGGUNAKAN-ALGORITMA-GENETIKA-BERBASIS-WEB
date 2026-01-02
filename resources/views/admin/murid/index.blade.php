@extends('admin.layouts.adminmaster')
@section('content')
<div class="page-heading">
        <div class="page-title">
            <div class="row">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Master Data</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Murid</li>
                    </ol>
                </nav>
            </div>
        </div>

        <!-- Tambah & Update -->
        <section class="section">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Form Murid</h3>
                </div>
                <div class="card-body">
                <form id="form-murid" class="form form-horizontal" data-parsley-validate>
                <input type="hidden" id="murid-id" name="murid-id">
                    <div class="form-body">
                        <div class="row">
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                <label for="nisn">NISN</label>
                                <input type="number" class="form-control" id="nisn" name="nisn" placeholder="NISN" data-parsley-required="true" data-parsley-error-message="NISN tidak boleh kosong.">
                            </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                <label for="nama">Nama Murid</label>
                                <input type="text" class="form-control" id="nama" name="nama" placeholder="Nama Murid" data-parsley-required="true" data-parsley-error-message="Nama murid tidak boleh kosong.">
                            </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                <label for="kelas">Kelas</label>
                                <select class="selectpicker" data-style="btn-outline-light" id="kelas" name="kelas" selected data-parsley-required="true" data-parsley-error-message="Kelas murid harus dipilih.">
                                <option value="" disabled selected>- Pilih Kelas Murid -</option>
                                @php
                                    $kelasByJurusan = $dataKelas->groupBy('id_jurusan');
                                @endphp

                                @foreach ($kelasByJurusan as $idJurusan => $kelasJurusan)
                                    @php
                                        $jurusan = $kelasJurusan->first()->jurusan->nama_jurusan;
                                    @endphp

                                    <optgroup label="{{ $jurusan }}">
                                        @foreach ($kelasJurusan as $kelas)
                                            <option value="{{ $kelas->id }}" data-subtext="Tingkat {{ $kelas->tingkat }}">{{ $kelas->nama_kelas }}</option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                            </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label>Jenis Kelamin</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="jk" id="jkLaki" value="Laki-laki" required data-parsley-errors-container="#jkError" data-parsley-required-message="Silakan pilih jenis kelamin.">
                                        <label class="form-check-label" for="jkLaki">Laki-laki</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="jk" id="jkPerempuan" value="Perempuan">
                                        <label class="form-check-label" for="jkPerempuan">Perempuan</label>
                                    </div>
                                    <div id="jkError" class="text-danger mt-1"></div>
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" id="email" name="email" placeholder="Email Murid" data-parsley-required="true" data-parsley-error-message="Email murid tidak boleh kosong.">
                            </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                <label for="alamat">Alamat</label>
                                <textarea class="form-control" id="alamat" name="alamat" placeholder="Alamat murid" rows="1"></textarea>
                            </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                <label for="notelp">No Telephone</label>
                                <input type="number" class="form-control" id="notelp" name="notelp" class="form-control" placeholder="No Telephone Murid">
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
                        <h5 class="modal-title white" id="myModalLabel160">FORM IMPORT MURID</h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <i data-feather="x"></i>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="import-murid" class="form form-horizontal" enctype="multipart/form-data" data-parsley-validate>
                            <div class="form-body">
                                <div class="row">
                                        <div class="mb-3">
                                            <label for="formFile" class="form-label">Pilih File Data Murid</label>
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
                    <h3 class="card-title">Data Murid</h3>
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
                        <table class="table table-striped" style="width: 100%;" id="tabel-murid">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th class="text-center">NISN</th>
                                    <th class="text-center">Nama</th>
                                    <th class="text-center">Kelas</th>
                                    <th class="text-center">Jenis Kelamin</th>
                                    <th class="text-center">Email</th>
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
            var table = $('#tabel-murid').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('murid.index') }}",
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
                    { data: 'nisn', name: 'nisn', className: 'text-center'},
                    { data: 'nama', name: 'nama' },
                    { data: 'kelas', name: 'kelas', className: 'text-center' },
                    { data: 'jk', name: 'jk', className: 'text-center' },
                    { data: 'email', name: 'email' },
                    { data: 'alamat', name: 'alamat' },
                    { data: 'notelp', name: 'notelp' },
                    { data: 'action', name: 'action',  className: 'text-center', orderable: false, searchable: false }
                ],
                order: [[0, 'asc']]
            });
            $(document).on('click', '.edit', function () {
              var id = $(this).attr('id');
                $.ajax({
                    url: '/murid/' + id + '/edit',
                    type: 'GET',
                    success: function (response) {
                    // Populate form fields with data for editing
                    $('#murid-id').val(response.id);
                    $('#nisn').val(response.nisn);
                    $('#nama').val(response.nama);
                    if (response.jk === 'Laki-laki') {
                    $('#jkLaki').prop('checked', true);
                    } else if (response.jk === 'Perempuan') {
                        $('#jkPerempuan').prop('checked', true);
                    }
                    $('#email').val(response.email);
                    $('#alamat').val(response.alamat);
                    $('#notelp').val(response.notelp);
                    $('#kelas').selectpicker('val', response.id_kelas);
                    updateButton('Update');
                    $('#form-murid').parsley().reset();
                    },
                    error: function (xhr, status, error) {
                        showErrorAlert('Terjadi kesalahan saat mengambil data murid.');
                    }
                });
            });

            $(document).on('click', '.delete', function () {
            var id = $(this).attr('id');
            swal.fire({
                title: "Hapus data murid",
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
                        url: '/murid/' + id,
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function (response) {
                            showSuccessAlert(response.success);
                            var table = $('#tabel-murid').DataTable();
                            table.ajax.reload();
                        },
                        error: function (xhr, status, error) {
                            showErrorAlert('Terjadi kesalahan saat menghapus murid.');
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
                title: 'Terjadi Kesalahan.',
                text: message,
                icon: 'error',
                showConfirmButton: false,
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
            $('#form-murid').parsley().reset();
            updateButton('Simpan');
            $('#form-murid')[0].reset();
            $('#murid-id').val('');
            $('#kelas').val('').selectpicker('refresh');
            $('input[name="jk"]').prop('checked', false);
            // Add additional code to reset other form fields if needed
        }

        $('#form-murid').submit(function (event) {
            saveOrUpdateMurid(event);
        });
        function showImportModal() {
            $('#modal-import').modal('show');
            $('#import-murid').trigger('reset');
        }
        function saveOrUpdateMurid(event) {
        event.preventDefault();

        // Validate the form using Parsley
        if ($('#form-murid').parsley().validate()) {
            var id = $('#murid-id').val();
            var url = id ? '/murid/' + id : '{{ route("murid.store") }}';
            var method = id ? 'PUT' : 'POST';

            var formData = $('#form-murid').serialize();

            $.ajax({
                url: url,
                type: method,
                data: formData + '&_token={{ csrf_token() }}',
                success: function (response) {
                    showSuccessAlert(response.success);

                    var table = $('#tabel-murid').DataTable();
                    table.ajax.reload();
                    resetForm();
                },
                error: function (xhr, status, error) {
                    showErrorAlert('Terjadi kesalahan saat menyimpan data murid.');
                }
            });
        }
    }
    $('#cetakBtn').click(function () {
            Swal.fire({
                title: "Konfirmasi",
                text: "Cetak data murid?",
                icon: 'question',
                showCancelButton: true,
                showCloseButton: false,
                cancelButtonColor: '#999',
                confirmButtonColor: '#435EBE',
                confirmButtonText: 'Ya',
                cancelButtonText: 'Batal'
            }).then(function (result) {
                if (result.value) {
                    window.location = "{{ route('cetak.murid') }}";
                    Swal.fire({
                    icon: 'success',
                    text: 'Data murid berhasil dicetak!',
                    title: "Sukses",
                    showConfirmButton: true
                });
                }
            });
        });

        $('#import-murid').submit(function (event) {
            event.preventDefault();

            var formData = new FormData(this);
            formData.append('_token', '{{ csrf_token() }}');  // Tambahkan token CSRF ke FormData

            $.ajax({
                url: "{{ route('murid.import') }}",
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function (response) {
                    showSuccessAlert(response.success);
                    $('#modal-import').modal('hide');
                    $('#tabel-murid').DataTable().ajax.reload();
                },
                error: function (xhr, status, error) {
                    showErrorAlert('Terjadi kesalahan saat mengimpor data murid.');
                }
            });
        });

    </script>
 @endsection