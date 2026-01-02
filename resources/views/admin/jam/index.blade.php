@extends('admin.layouts.adminmaster')
@section('content')
        <div class="page-heading">
            <div class="page-title">
                <div class="row">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Master Data</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Jam</li>
                        </ol>
                    </nav>
                </div>
            </div>

         <!-- Tambah & Update -->
        <section class="section">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Form Jam</h3>
                </div>
                <div class="card-body">
                <form id="form-jam" class="form form-horizontal" data-parsley-validate>
                <input type="hidden" id="jam-id" name="jam-id">
                    <div class="form-body">
                    <div class="row">
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                <label for="kode_jam">Kode Jam</label>
                                <input type="number" class="form-control" id="kode_jam" name="kode_jam" placeholder="Kode jam" data-parsley-required="true" data-parsley-error-message="Kode jam tidak boleh kosong.">
                            </div>
                            </div>
                            <div class="col-md-6 col-12">
                            <div class="form-group">
                                <label for="jam_mulai">Jam Mulai</label>
                                <input type="time" class="form-control" id="jam_mulai" name="jam_mulai" class="form-control" placeholder="Jam Mulai" data-parsley-required="true" data-parsley-error-message="Jam mulai tidak boleh kosong.">
                            </div>
                            </div>
                            <div class="col-md-6 col-12">
                            <div class="form-group">
                                <label for="jam_selesai">Jam Selesai</label>
                                <input type="time" class="form-control" id="jam_selesai" name="jam_selesai" class="form-control" placeholder="Jam Selesai" data-parsley-required="true" data-parsley-error-message="Jam selesai tidak boleh kosong.">
                            </div>
                            </div>
                            <div class="col-md-6 col-12">
                            <div class="form-group">
                                <label for="jeda">Jeda Jam</label>
                                <select class="selectpicker" data-style="btn-outline-light" id="jeda" name="jeda">
                                <option value="">- Pilih Jeda Jam -</option>
                                <option value="Upacara">Upacara</option>
                                <option value="Istirahat">Istirahat</option>
                                <option value="Jumat">Sholat Jumat</option>
                                <option value="Terakhir">Terakhir</option>
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

        <!-- Datatables -->
        <section class="section">
            <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">Data jam</h3>
            <button class="btn btn-primary" onclick="showAddModal();">
                <i class="bi bi-plus-square-fill"></i> Tambah
            </button>
        </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped" style="width: 100%;" id="tabel-jam">
                            <thead>
                                <tr>
                                <th>#</th>
                                <th>Range Jam</th>
                                <th>Jeda</th>
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
        // Datatable Jam
        $('#tabel-jam').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('jam.index') }}",
            columns: [
                { data: 'kode_jam', name: 'kode_jam' },
                { data: 'range_jam', name: 'range_jam' },
                { data: 'jeda', name: 'jeda' },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false,
                    className: 'text-center'
                }
            ]
        });
        $(document).on('click', '.edit', function () {
            var id = $(this).attr('id');
            $.ajax({
                url: '/jam/' + id + '/edit',
                type: 'GET',
                success: function (response) {
                    // Populate form fields with data for editing
                    $('#jam-id').val(response.id);
                    $('#kode_jam').val(response.kode_jam);

                    var range_jam = response.range_jam.split('-');
                    $('#jam_mulai').val(range_jam[0]);
                    $('#jam_selesai').val(range_jam[1]);

                    $('#jeda').selectpicker('val', response.jeda);
                    $('#form-jam').parsley().reset();
                },
                error: function (xhr, status, error) {
                    showErrorAlert('Terjadi kesalahan saat mengambil data jam.');
                }
            });
        });
        $(document).on('click', '.delete', function () {
            var id = $(this).attr('id');
            swal.fire({
                title: "Hapus data jam",
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
                        url: '/jam/' + id,
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function (response) {
                            showSuccessAlert(response.success);
                            var table = $('#tabel-jam').DataTable();
                            table.ajax.reload();
                        },
                        error: function (xhr, status, error) {
                            showErrorAlert('Terjadi kesalahan saat menghapus jam.');
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
            $('#form-jam').parsley().reset();
            updateButton('Simpan');
            $('#form-jam')[0].reset();
            $('#jam-id').val('');
            $('#jam-mulai').val('');
            $('#jam-selesai').val('');
            $('#jeda').val('').selectpicker('refresh');
            // Add additional code to reset other form fields if needed
        }

        $('#form-jam').submit(function (event) {
            saveOrUpdateJam(event);
        });

        function saveOrUpdateJam(event) {
        event.preventDefault();

        // Validate the form using Parsley
        if ($('#form-jam').parsley().validate()) {
            var id = $('#jam-id').val();
            var url = id ? '/jam/' + id : '{{ route("jam.store") }}';
            var method = id ? 'PUT' : 'POST';
            var jeda = $('#jeda').val();
            var kode_jam = $('#kode_jam').val();
            var jam_mulai = $('#jam_mulai').val();
            var jam_selesai = $('#jam_selesai').val();
            
            // Combine jam_mulai and jam_selesai to range_jam
            var range_jam = jam_mulai + '-' + jam_selesai;

            var data = {
                kode_jam: kode_jam,
                range_jam: range_jam,
                jeda: jeda,
                _token: '{{ csrf_token() }}'
            };
            $.ajax({
                url: url,
                type: method,
                data: data,
                success: function (response) {
                    showSuccessAlert(response.success);

                    var table = $('#tabel-jam').DataTable();
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
