@extends('admin.layouts.adminmaster')
@section('content')
<div class="page-heading">
        <div class="page-title">
            <div class="row">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Master Data</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Mengajar</li>
                    </ol>
                </nav>
            </div>
        </div>

        <!-- Datatables -->
        <section class="section">
            <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Data Tugas Guru Mengajar</h3>
                    <div class="d-flex">
                        <button class="btn btn-primary" onclick="showAddModal();">
                            <i class="bi bi-plus-square-fill"></i> Tambah
                        </button>
                        &nbsp;&nbsp;&nbsp;
                        <button class="btn btn-primary" id="cetakBtn">
                            <i class="bi bi-printer-fill"></i> Cetak
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped" style="width: 100%;" id="tabel-mengajar">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Guru</th>
                                    <th>Total SKS</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </section>
        <!-- Datatables end -->

<!-- Modal lihat -->
<div class="modal fade text-left" id="modal-lihat-mengajar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel160" aria-hidden="true" style="display:none;">
    <div class="modal-dialog modal-dialog-scrollable modal-full" role="document">
    <div class="modal-content">
    <div class="modal-header bg-primary">
                <h5 class="modal-title white" id="myModalLabel160">DATA DETAIL MENGAJAR</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <i data-feather="x"></i>
                </button>
            </div>
            <div class="modal-body">
            <div class="table-responsive">
                        <table class="table table-striped" style="width: 100%;" id="tabel-lihat-mengajar">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Guru</th>
                                    <th>Mata Pelajaran</th>
                                    <th>SKS</th>
                                    <th>Semester</th>
                                    <th>Kelas</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
                                <i class="bx bx-x d-block d-sm-none"></i>
                                <span class="d-none d-sm-block">Tutup</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
<!-- Modal lihat End -->

<!-- Modal tambah -->
<div class="modal fade text-left" id="modal-tambah-mengajar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel160" aria-hidden="true" style="display:none;">
    <div class="modal-dialog modal-dialog-scrollable modal-full" role="document">
    <div class="modal-content">
    <div class="modal-header bg-primary">
                <h5 class="modal-title white" id="myModalLabel160">FORM TAMBAH MENGAJAR</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <i data-feather="x"></i>
                </button>
            </div>
            <div class="modal-body">
                <form id="form-tambah-mengajar" class="form form-horizontal" data-parsley-validate>
                    <div class="form-body">
                    <input type="hidden" name="kelas">
                    <input type="hidden" id="defaultKelas">
                        <div class="row">
                        <div class="col-md-4">
                                <label for="guru">Guru</label>
                            </div>
                            <div class="col-md-8 form-group">
                            <select class="selectpicker" data-style="btn-outline-light" data-live-search="true" id="guru" name="guru" data-parsley-required="true" title="-Pilih Guru-" data-parsley-error-message="Guru harus dipilih." data-size="8">
                            @foreach ($dataGuru as $guru)
                                <option value="{{ $guru->id }}" data-subtext="{{ $guru->title }}" title="{{ $guru->nama }},{{ $guru->title }}">{{ $guru->nama }}</option>
                            @endforeach
                            </select>
                            </div>
                            <div class="col-md-4">
                                <label for="mapel">Mata Pelajaran</label>
                            </div>
                            <div class="col-md-8 form-group">
                            <select class="selectpicker" data-style="btn-outline-light" data-live-search="true" id="mapel" name="mapel" data-parsley-required="true" title="-Pilih Mata Pelajaran-" data-parsley-error-message="Mata pelajaran harus dipilih." data-size="6">
                            @php
                            $cek = '';
                            @endphp
                            @foreach ($dataPelajaran as $key => $value)
                                @if ($value['jurusan'] !== $cek)
                                    @if ($cek != '')
                                        </optgroup>
                                    @endif
                                    <optgroup label="{{ $value['jurusan'] }}">
                                @endif
                                <option value="{{ $value['id'] }}">{{ $value['nama_pel'] }}</option>
                                @php
                                $cek = $value['jurusan'];
                                @endphp
                            @endforeach
                            </optgroup>
                            </select>
                            </div>
                            <div class="col-md-4">
                                <label for="sks">SKS</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <input type="number" class="form-control" id="sks" name="sks" class="form-control" placeholder="SKS" data-parsley-required="true" data-parsley-error-message="SKS tidak boleh kosong.">
                            </div>
                            <div class="col-md-4">
                                <label for="semester">Semester</label>
                            </div>
                            <div class="col-md-8 form-group">
                            <select class="selectpicker" data-style="btn-outline-light" id="semester" name="semester" title="Semester" data-parsley-required="true" data-parsley-error-message="Semester tidak boleh kosong.">
                                <option value="" disabled selected>- Pilih Semester-</option>  
                                <option value="GANJIL">GANJIL</option>
                                <option value="GENAP">GENAP</option>
                            </select>
                            </div>
                            <div class="col-md-4">
                                <label for="inputkelas">Kelas</label>
                            </div>
                            <div class="col-md-8 form-group">
                            <select class="selectpicker" data-style="btn-outline-light" id="inputkelas" name="id_kelas" data-parsley-required="true" data-parsley-error-message="Kelas Mengajar harus dipilih." data-size="6" multiple>
             
                            </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" id="BtnSimpan" class="btn btn-primary ms-1">
                                <i class="bx bx-check d-block d-sm-none"></i>
                                <span class="d-sm-block d-none">Simpan</span>
                            </button>
                            <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
                                <i class="bx bx-x d-block d-sm-none"></i>
                                <span class="d-none d-sm-block">Tutup</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Modal tambah End -->

<!-- Modal Edit -->
<div class="modal fade text-left" id="modal-edit-mengajar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel160" aria-hidden="true" style="display:none;">
    <div class="modal-dialog modal-dialog-scrollable modal-full" role="document">
    <div class="modal-content">
    <div class="modal-header bg-success">
                <h5 class="modal-title white" id="myModalLabel160">FORM EDIT MENGAJAR</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <i data-feather="x"></i>
                </button>
            </div>
            <div class="modal-body">
                <form id="form-edit-mengajar" class="form form-horizontal" data-parsley-validate>
                <input type="hidden" id="mengajar-id" name="mengajar-id">
                <input type="hidden" name="kelas">
                <input type="hidden" id="defaultKelas">
                    <div class="form-body">
                        <div class="row">
                        <div class="col-md-4">
                                <label for="editguru">Guru</label>
                            </div>
                            <div class="col-md-8 form-group">
                            <select class="selectpicker" data-style="btn-outline-light" id="editguru" name="editguru" data-parsley-required="true" title="-Pilih Guru-" data-parsley-error-message="Guru harus dipilih." data-size="8">
                            @foreach ($dataGuru as $guru)
                                <option value="{{ $guru->id }}" data-subtext="{{ $guru->title }}" title="{{ $guru->nama }},{{ $guru->title }}">{{ $guru->nama }}</option>
                            @endforeach
                            </select>
                            </div>
                            <div class="col-md-4">
                                <label for="mapel">Mata Pelajaran</label>
                            </div>
                            <div class="col-md-8 form-group">
                            <select class="selectpicker" data-style="btn-outline-light" id="editmapel" name="mapel" data-parsley-required="true" title="-Pilih Mata Pelajaran-" data-parsley-error-message="Mata pelajaran harus dipilih." data-size="6">
                            @php
                            $cek = '';
                            @endphp
                            @foreach ($dataPelajaran as $key => $value)
                                @if ($value['jurusan'] !== $cek)
                                    @if ($cek != '')
                                        </optgroup>
                                    @endif
                                    <optgroup label="{{ $value['jurusan'] }}">
                                @endif
                                <option value="{{ $value['id'] }}">{{ $value['nama_pel'] }}</option>
                                @php
                                $cek = $value['jurusan'];
                                @endphp
                            @endforeach
                            </optgroup>
                            </select>
                            </div>
                            <div class="col-md-4">
                                <label for="editsks">SKS</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <input type="number" class="form-control" id="editsks" name="editsks" class="form-control" placeholder="SKS" data-parsley-required="true" data-parsley-error-message="SKS tidak boleh kosong.">
                            </div>
                            <div class="col-md-4">
                                <label for="editsemester">Semester</label>
                            </div>
                            <div class="col-md-8 form-group">
                            <select class="selectpicker" data-style="btn-outline-light" id="editsemester" name="editsemester" title="Semester" data-parsley-required="true" data-parsley-error-message="Semester tidak boleh kosong.">
                                <option value="" disabled selected>- Pilih Semester-</option>  
                                <option value="GANJIL">GANJIL</option>
                                <option value="GENAP">GENAP</option>
                            </select>
                            </div>
                            <div class="col-md-4">
                                <label for="kelas">Kelas</label>
                            </div>
                            <div class="col-md-8 form-group">
                            <select class="selectpicker" data-style="btn-outline-light" id="kelas" name="id_kelas" data-parsley-required="true" data-parsley-error-message="Kelas Mengajar harus dipilih." data-size="6" multiple>
             
                            </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" id="BtnEdit" class="btn btn-success ms-1">
                                <i class="bx bx-check d-block d-sm-none"></i>
                                <span class="d-sm-block d-none">Update</span>
                            </button>
                            <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
                                <i class="bx bx-x d-block d-sm-none"></i>
                                <span class="d-none d-sm-block">Tutup</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Modal edit End -->
</div>
@endsection
    @section('scripts')
    <script>
        var dataMengajar = <?php echo json_encode($dataMengajar); ?>;
        $(document).ready(function () {
            //tabel mengajar
            var tableMengajar = $('#tabel-mengajar').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('mengajar.index') }}",
            columns: [
                {
                    data: null,
                    name: 'id',
                    render: function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },
                { data: 'guru.nama', name: 'guru.nama' },
                { data: 'sks', name: 'sks' },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false,
                    className: 'text-center'
                }
            ],
            drawCallback: function (settings) {
                // Group by teacher's name
                var api = this.api();
                var rows = api.rows({ page: 'current' }).nodes();
                var groupedData = {};

                api.column(1, { page: 'current' }).data().each(function (name, index) {
                    if (!groupedData[name]) {
                        groupedData[name] = {
                            sks: 0
                        };
                    }

                    var sks = parseFloat(api.column(2).data()[index]);
                    groupedData[name].sks += isNaN(sks) ? 0 : sks;

                    $('td:eq(1)', rows[index]).html(name); // Update teacher's name cell
                    $('td:eq(2)', rows[index]).html(groupedData[name].sks); // Update sks cell
                });
            }
        });

            // edit event
            $(document).on('click', '.edit', function () {
                var id = $(this).attr('id');
                $.ajax({
                    url: '/mengajar/' + id + '/edit',
                    type: 'GET',
                    success: function (response) {
                        showEditModal(response);
                    },
                    error: function (xhr, status, error) {
                        showErrorAlert('Terjadi kesalahan saat mengambil data mengajar.');
                    }
                });
            });
            $(document).on('click', '.lihat', function () {
                var id = $(this).attr('id');
                $.ajax({
                    url: '/mengajar/' + id + '/lihat',
                    type: 'GET',
                    success: function (response) {
                        showLihatModal(response);
                    },
                    error: function (xhr, status, error) {
                        showErrorAlert('Terjadi kesalahan saat mengambil data mengajar.');
                    }
                });
            });
                    //event klik delete
                    $(document).on('click', '.delete', function () {
                    var id = $(this).attr('id');
                    swal.fire({
                        title: "Warning",
                        text: "Anda yakin untuk menghapus data ini?",
                        icon: 'warning',
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
                                url: '/mengajar/' + id,
                                type: 'DELETE',
                                data: {
                                    _token: '{{ csrf_token() }}'
                                },
                                success: function (response) {
                                    showSuccessAlert(response.success);
                                    var tableMengajar = $('#tabel-mengajar').DataTable();
                                    tableMengajar.ajax.reload();
                                    closeModal();
                                },
                                error: function (xhr, status, error) {
                                    showErrorAlert('Terjadi kesalahan saat menghapus mengajar.');
                                }
                            });
                        }
                    });
                });

                $(document).on('click', '.deleteAll', function () {
                    var id = $(this).attr('id');
                    swal.fire({
                        title: "Warning",
                        text: "Anda yakin untuk menghapus semua data mengajar ini?",
                        icon: 'warning',
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
                                url: '/mengajar/' + id + '/deleteall',
                                type: 'post',
                                data: {
                                    _token: '{{ csrf_token() }}'
                                },
                                success: function (response) {
                                    showSuccessAlert(response.success);
                                    var tableMengajar = $('#tabel-mengajar').DataTable();
                                    tableMengajar.ajax.reload();
                                    closeModal();
                                },
                                error: function (xhr, status, error) {
                                    showErrorAlert('Terjadi kesalahan saat menghapus semua data mengajar.');
                                }
                            });
                        }
                    });
                });
                

            $('#form-tambah-mengajar').submit(function (event) {
                saveMengajar(event);
            });
            $('#form-edit-mengajar').submit(function (event) {
                updateMengajar(event);
            });
            $('#modal-close-btn').click(function () {
                closeModal();
            });
        });
           // Function to display success alert
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

        // Function to display error alert
        function showErrorAlert(message) {
            Swal.fire({
                title: 'Terjadi Kesalahan',
                text: message,
                icon: 'error',
                showConfirmButton: true,
                showCloseButton: true
            });
        }

        function closeModal() {
            $('#modal-tambah-mengajar').modal('hide');
            $('#modal-edit-mengajar').modal('hide');
            $('#modal-lihat-mengajar').modal('hide');
        }
        function showLihatModal(data) {
            // Menghapus baris yang ada di DataTable sebelum menambahkan data baru
            $('#tabel-lihat-mengajar').DataTable().clear().destroy();

            // Inisialisasi DataTable di dalam modal
            var tableLihat = $('#tabel-lihat-mengajar').DataTable({
                data: data.data, // Use data.data to access the array of objects
                columns: [
                    {
                        data: null,
                        name: 'DT_RowIndex', // Use DT_RowIndex for the row index
                        render: function (data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    },
                    { data: 'guru.nama', name: 'guru.nama' },
                    { data: 'pelajaran.nama_pel', name: 'pelajaran.nama_pelajaran' },
                    { data: 'sks', name: 'sks' },
                    { data: 'semester', name: 'semester' },
                    { data: 'kelas', name: 'kelas' },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    }
                ],
                order: [[0, 'asc']] // Misalkan diurutkan berdasarkan kolom pertama (DT_RowIndex)
            });

            // Menampilkan modal
            $('#modal-lihat-mengajar').modal('show');
        }


        // show add modal
        function showAddModal() {
            $('#modal-tambah-mengajar').modal('show');
            $('#form-tambah-mengajar').trigger('reset');
            $('#guru').selectpicker('refresh');
            $('#mapel').selectpicker('refresh');
            $('#semester').selectpicker('refresh');
            $('select[name=id_kelas]').empty().selectpicker('destroy'); 
            $('select[name=id_kelas]').selectpicker({
            title: 'Pilih pelajaran terlebih dahulu'
        });
        }

        // show edit modal
        function showEditModal(mengajar) {
            $('#modal-edit-mengajar').modal('show');
            $('#form-edit-mengajar').trigger('reset');
            $('#mengajar-id').val(mengajar.id);
            $('#editguru').selectpicker('val', mengajar.id_guru);
            $('#editmapel').selectpicker('val', mengajar.id_pel);
            $('#editsemester').selectpicker('val', mengajar.semester);
            $('#editsks').val(mengajar.sks);
            var id_pel = mengajar.id_pel;
            
            $.ajax({
                type: "POST",
                url: '/mengajar/' + id_pel + '/getKelas',
                dataType: 'JSON',
                data: { _token: '{{ csrf_token() }}', id_pel: id_pel },
                beforeSend: function (xhr) {
                    $('select[name=id_kelas]').empty()
                        .selectpicker({
                            title: 'Memproses..'
                        })
                        .selectpicker('refresh');
                },
                success: function (res) {
                    $('select[name=id_kelas]').empty()
                        .selectpicker({
                            title: 'Data kelas tidak ditemukan'
                        })
                        .selectpicker('refresh');

                    var groupedKelas = {};
                    var selected = {};

                    // Perlu mencocokkan id_kelas dari res dengan id_kelas dari mengajar.kelas
                    $.each(res, function (index, value) {
                        var id_kelas = value.id;
                        var kelas = value.nama_kelas;
                        var tingkat = value.tingkat;

                        if (!groupedKelas[tingkat]) {
                            groupedKelas[tingkat] = [];
                        }

                        $.each(JSON.parse(mengajar.kelas), function (key, val) {
                            if (val.id_kelas == id_kelas) {
                                selected[id_kelas] = "selected";
                            }
                        });

                        groupedKelas[tingkat].push('<option value="' + id_kelas + '" title="' + kelas + '" data-tingkat="' + tingkat + '"' + selected[id_kelas] + '>' + kelas + '</option>');
                    });

                    var output = '';
                    for (var tingkat in groupedKelas) {
                        output += '<optgroup label="Tingkat ' + tingkat + '">';
                        output += groupedKelas[tingkat].join('');
                        output += '</optgroup>';
                    }

                    $('select[name=id_kelas]').append(output)
                        .selectpicker({
                            title: '- Pilih Kelas -'
                        })
                        .selectpicker('refresh');
                }
            });

            $('input[name=kelas]').val(mengajar.kelas);
            $('.selectpicker').selectpicker('refresh');
        }

      // event select pelajaran untuk value select kelas
        $('select[name=mapel]').change(function (e) {
            var id_pel = e.target.value;
            $.ajax({
                type: "POST",
                url: '/mengajar/' + id_pel + '/getKelas',
                dataType: 'JSON',
                data: {
                    _token: '{{ csrf_token() }}',
                    id_pel: id_pel
                },
                beforeSend: function (xhr) {
                    $('select[name=id_kelas]').empty()
                        .selectpicker({
                            title: 'Memproses..'
                        })
                        .selectpicker('refresh');
                },
                success: function (res) {
                    $('select[name=id_kelas]').empty()
                        .selectpicker({
                            title: 'Data kelas tidak ditemukan'
                        })
                        .selectpicker('refresh');

                    var groupedKelas = {};

                    $.each(res, function (index, value) {
                        var kelas = value.nama_kelas;
                        var tingkat = value.tingkat;

                        if (!groupedKelas[tingkat]) {
                            groupedKelas[tingkat] = [];
                        }

                        groupedKelas[tingkat].push('<option value="' + value.id + '" title="' + kelas + '" data-tingkat="' + tingkat + '">' + kelas + '</option>');
                    });

                    var output = '';
                    for (var tingkat in groupedKelas) {
                        output += '<optgroup label="Tingkat ' + tingkat + '">';
                        output += groupedKelas[tingkat].join('');
                        output += '</optgroup>';
                    }


                    // Append this line inside the success callback
                    $('select[name=id_kelas]').append(output)
                        .selectpicker({
                            title: '- Pilih Kelas -'
                        })
                        .selectpicker('refresh');
                },
                error: function (xhr, status, error) {
                    showErrorAlert('Terjadi kesalahan saat mengambil data kelas.');
                }
            });
        });

        // event select kelas dan input
        $('select[name=id_kelas]').change(function (e) {
            var selected = $(this).find("option:selected");
            var array = [];
            for (var i = 0; i < selected.length; i++) {
                var id = $(selected[i]).val();
                var kelas = $(selected[i]).attr("title");
                var tingkat = $(selected[i]).data("tingkat");
                array.push({
                    id_kelas: id,
                    kelas: kelas,
                    tingkat: tingkat
                });
            }
            $('input[name=kelas]').val(JSON.stringify(array));
        });

        // simpan dan update
        function saveMengajar(event) {
            event.preventDefault();
            var id_guru = $('#guru').val();
            var id_pel = $('#mapel').val();
            var semester = $('#semester').val();
            var sks = $('input[name=sks]').val();
            var kelas = $('input[name=kelas]').val();
            var Id = $('#mengajar-id').val();
            var data = {
            data: [
                { name: 'guru', value: id_guru },
                { name: 'mapel', value: id_pel },
                { name: 'kelas', value: kelas },
                { name: 'sks', value: sks },
                { name: 'semester', value: semester }
            ],
            _token: '{{ csrf_token() }}'
        };
            $.ajax({
                url: "{{ route('mengajar.store') }}",
                type:'POST',
                dataType: 'JSON',
                data: data,
                success: function (response) {
                    showSuccessAlert(response.success);
                    var table = $('#tabel-mengajar').DataTable();
                    table.ajax.reload();
                    closeModal();
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
         // update
         function updateMengajar(event) {
            event.preventDefault();
            var id_guru = $('#editguru').val();
            var id_pel = $('#editmapel').val();
            var semester = $('#editsemester').val();
            var sks = $('#editsks').val();
            var kelas = $('input[name=kelas]').val();
            var Id = $('#mengajar-id').val();
            var data = {
                data: [
                    { name: 'guru', value: id_guru },
                    { name: 'mapel', value: id_pel },
                    { name: 'kelas', value: kelas },
                    { name: 'sks', value: sks },
                    { name: 'semester', value: semester }
                ],
                _token: '{{ csrf_token() }}'
            };
            $.ajax({
                url: '/mengajar/' + Id,
                type: 'PUT',
                dataType: 'JSON',
                data: data,
                success: function (response) {
                    showSuccessAlert(response.success);
                    var table = $('#tabel-mengajar').DataTable();
                    table.ajax.reload();
                    closeModal();
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
        $('#cetakBtn').click(function () {
            Swal.fire({
                title: "Konfirmasi",
                text: "Cetak data tugas guru mengajar?",
                icon: 'question',
                showCancelButton: true,
                showCloseButton: false,
                cancelButtonColor: '#999',
                confirmButtonColor: '#435EBE',
                confirmButtonText: 'Ya',
                cancelButtonText: 'Batal'
            }).then(function (result) {
                if (result.value) {
                    window.location = "{{ route('cetak.mengajar') }}";
                    showSuccessAlert('Data tugas guru mengajar berhasil dicetak!');
                }
            });
        });
    </script>
@endsection