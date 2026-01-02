@extends('admin.layouts.adminmaster')
@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Penjadwalan</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Generate</li>
                    </ol>
                </nav>
            </div>
        </div>
            <section class="section">
              <div class="col-12">
                  <div class="card">
                      <div class="card-header">
                          <h4 class="card-title">Pilihan Generate Jadwal Mata Pelajaran</h4>
                      </div>
                      <div class="card-body">
                          <form id="formBuatJadwal" class="form form-horizontal" data-parsley-validate>
                          <div class="row">
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label for="populasi">Jumlah Populasi</label>
                                    <input type="text" class="form-control" id="populasi" name="populasi" value="<?php echo isset($populasi) ? $populasi : '10'; ?>" placeholder="Jumlah Populasi" data-parsley-required="true" data-parsley-error-message="Jumlah populasi tidak boleh kosong.">
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label for="generasi">Jumlah Generasi</label>
                                    <input type="text" class="form-control" id="generasi" name="generasi" value="<?php echo isset($generasi) ? $generasi : '10000'; ?>" placeholder="Jumlah Generasi" data-parsley-required="true" data-parsley-error-message="Jumlah generasi tidak boleh kosong.">
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label for="crossover">Probabilitas CrossOver</label>
                                    <input type="text" class="form-control" id="crossover" name="crossover" value="<?php echo isset($crossover) ? $crossover : '0.7'; ?>" placeholder="Probabilitas CrossOver" data-parsley-required="true" data-parsley-error-message="Probabilitas CrossOver tidak boleh kosong.">
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label for="mutasi">Probabilitas Mutasi</label>
                                    <input type="text" class="form-control" id="mutasi" name="mutasi" value="<?php echo isset($mutasi) ? $mutasi : '0.4'; ?>" placeholder="Probabilitas Mutasi" data-parsley-required="true" data-parsley-error-message="Probabilitas Mutasi tidak boleh kosong.">
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                <label for="semester">Jenis Semester</label>
                                    <select class="selectpicker" data-style="btn-outline-light" id="jenis_semester" name="jenis_semester" data-parsley-required="true" data-parsley-error-message="Jenis semester harus dipilih.">
                                        <option value="" disabled selected>- Pilih Jenis Semester -</option>
                                        <@foreach($dataMengajar as $semester)
                                            <option value="{{ $semester }}">{{ $semester }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            </div>
                            <div class="col-12 d-flex justify-content-end">
                                        <button class="btn btn-primary" id="submitBtn">
                                            <i class="bi bi-rocket-takeoff-fill"></i> Generate
                                        </button>
                            </div>
                            </div>
                          </form>
                  </div>
              </div>
      </section>

        <!-- Datatables -->
        <section class="section">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h3 class="card-title">Hasil Generate Jadwal Mata Pelajaran</h3>
                            <button class="btn btn-primary" onclick="showSaveModal();">
                                <i class="bi bi-save"></i> Simpan
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped" style="width: 100%;" id="tabel-jadwal">
                                    <thead>
                                        <tr>
                                          <th>#</th>
                                          <th>Hari</th>
                                          <th>Jam</th>
                                          <th>Guru</th>
                                          <th>Semester</th>
                                          <th>Mata Pelajaran</th>
                                          <th>Kelas</th>
                                          <th>SKS</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </section>
                <!-- Datatables end -->

                        <!-- Datatables -->
                <section class="section">
                    <div class="card" id="bentrok-card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h3 class="card-title">Data Bentrok Jadwal Mata Pelajaran</h3>
                        </div>
                        <div class="card-body">
                <div class="table-responsive">
                    <table id="tabel-bentrok" class="table table-striped" style="width: 100%;" cellspacing="0">
                        <thead>
                            <tr>
                                <th class="font-10">#</th>
                                <th class="font-10">Hari</th>
                                <th class="font-10">Jam</th>
                                <th class="font-10">Guru</th>
                                <th class="font-10">Pelajaran</th>
                                <th class="font-10">Kelas</th>
                                <th class="font-10">SKS</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                    </table>
                  </div>
                    </div>
                    </div>
                </section>

            <!-- Modal simpan -->
            <div class="modal fade text-left" id="modal-simpan" tabindex="-1" role="dialog" aria-labelledby="myModalLabel160" aria-hidden="true" style="display:none;">
                <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                <div class="modal-header bg-primary">
                <h5 class="modal-title white" id="myModalLabel160">FORM SIMPAN JADWAL MATA PELAJARAN</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <i data-feather="x"></i>
                </button>
            </div>
            <div class="modal-body">
                <form id="simpan-jadwal" class="form form-horizontal" data-parsley-validate>
                    <div class="form-body">
                        <div class="row">
                        <div class="col-md-4">
                                <label for="tahun_akademik">Tahun Akademik</label>
                            </div>
                            <div class="col-md-8 form-group">
                            <input type="text" class="form-control" id="tahun_akademik" name="tahun_akademik" placeholder="Masukan Tahun Akademik (2021 - 2022)" data-parsley-required="true" data-parsley-error-message="Tahun akademik tidak boleh kosong.">
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
<!-- Modal Simpan End -->

    <!-- Modal edit -->
    <div class="modal fade text-left" id="modal-edit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel160"
        aria-hidden="true" style="display:none;">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-success">
                    <h5 class="modal-title white" id="myModalLabel160">FORM EDIT JADWAL</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i data-feather="x"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="form-edit" class="form form-horizontal" data-parsley-validate>
                        <input type="hidden" id="jadwal-id" name="jadwal-id">
                        <div class="form-body">
                        <div class="row">
                        <div class="col-md-3">
                            <label for="hari">Hari</label>
                        </div>
                        <div class="col-md-9 form-group">
                        <select class="selectpicker" data-style="btn-outline-light" id="hari" name="hari" selected data-parsley-required="true" data-parsley-error-message="Hari harus dipilih.">
                                <option value="" disabled selected>- Pilih Hari -</option>
                                @foreach ($dataHari as $hari)
                                <option value="{{ $hari->id }}">{{ $hari->nama_hari }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="jam">Jam</label>
                        </div>
                        <div class="col-md-9 form-group">
                            <select class="selectpicker" data-style="btn-outline-light" id="jam" name="jam" selected data-parsley-required="true" data-parsley-error-message="Jam harus dipilih.">
                                <option value="" disabled selected>- Pilih Jam Mulai -</option>
                                @foreach ($dataJam as $jam)
                                <option value="{{ $jam->id }}">{{ $jam->range_jam }}</option>
                                @endforeach
                            </select>
                        </div>
                        </div>
                <div class="modal-footer">
                    <button type="submit" id="BtnEdit" class="btn icon icon-left btn-success">
                        Update
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
    <!-- Modal edit End -->

    <!-- Modal tukar -->
<div class="modal fade text-left" id="modal-tukar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel160"
        aria-hidden="true" style="display:none;">
        <div class="modal-dialog modal-dialog-scrollable modal-full" role="document">
            <div class="modal-content">
                <div class="modal-header bg-success">
                    <h5 class="modal-title white" id="myModalLabel160">PILIH JADWAL YANG AKAN DITUKAR</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i data-feather="x"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="form-tukar" class="form form-horizontal" data-parsley-validate>
                    <input type="hidden" id="jadwal-id" name="jadwal-id">
                    <div class="table-responsive">
                                <table class="table table-striped" style="width: 100%;" id="tabel-tukar">
                                    <thead>
                                        <tr>
                                          <th>#</th>
                                          <th>Hari</th>
                                          <th>Jam</th>
                                          <th>Guru</th>
                                          <th>Mata Pelajaran</th>
                                          <th>Kelas</th>
                                          <th>SKS</th>
                                          <th>Aksi</th>
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
                </div>
                </form>
            </div>

    <!-- Modal tukar End -->
    </div>
@endsection
    @section('scripts')
    <script>
        $(document).ready(function () {
            var tableJadwal = $('#tabel-jadwal').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('generate.index') }}",
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
                    { data: 'hari', name: 'hari',  className: 'text-center' },
                    { data: 'jam_sekolah', name: 'jam_sekolah',  className: 'text-center'},
                    { data: 'guru', name: 'guru' },
                    { data: 'semester', name: 'semester'},
                    { data: 'nama_pel', name: 'nama_pel' },
                    { data: 'kelas', name: 'kelas' },
                    { data: 'sks', name: 'sks',  className: 'text-center'},
                ],
                order: [[6, 'ASC'],[1, 'DESC'],[2, 'ASC']]
            });

            $('#simpan-jadwal').submit(function (event) {
                saveJadwal(event);
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
        function createNotification(status, teks) {
          $('.progress').removeClass('d-block').addClass('d-none');

          Swal.fire({
            icon: status,
            title: teks,
            showConfirmButton: true
          });
        }
        function createNotification(status, teks) {
            $('.progress').removeClass('d-block').addClass('d-none');
            Swal.fire({
                icon: status,
                title: teks,
                showConfirmButton: true
            });
        }


        function convertTime(start) {
            const sec_num = parseInt((performance.now() - start) / 1000, 10); // Changed new Date().getTime() to performance.now()
            const hours = Math.floor(sec_num / 3600);
            const minutes = Math.floor((sec_num % 3600) / 60); // Corrected the calculation
            const seconds = sec_num % 60; // Corrected the calculation

            return (hours === 0) ? (minutes === 0) ? seconds + ' Detik' : minutes + ' Menit, ' + seconds + ' Detik' : hours + ' Jam, ' + minutes + ' Menit, ' + seconds + ' Detik';
        }

        $("#submitBtn").click(function() {
            if ($('#formBuatJadwal').parsley().validate()) {
                $('#submitBtn')
                    .attr('disabled', true)
                    .html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Memproses...');

                var start = performance.now();
                var jenis_semester = $('#jenis_semester').val();
                var populasi = $('#populasi').val();
                var crossover = $('#crossover').val();
                var mutasi = $('#mutasi').val();
                var generasi = $('#generasi').val();
                $.ajax({
                    type: "POST",
                    url: "{{ route('generate.jadwal') }}",
                    dataType: "JSON",
                    data: {
                        jenis_semester: jenis_semester,
                        populasi: populasi,
                        generasi: generasi,
                        crossover: crossover,
                        mutasi:mutasi,
                        _token: '{{ csrf_token() }}',
                    },
                    success: function(res) {
                        const waktu = convertTime(start);
                        var tableJadwal = $('#tabel-jadwal').DataTable();
                        tableJadwal.clear().draw();

                        if (res.status == true) {
                            createNotification('success', 'Generate jadwal mata pelajaran berhasil, Fitness Terbaik = ' + res.bestFitness + ', Waktu Proses = ' + waktu);
                        } else if (res.status == false) {
                            createNotification('error', 'Generate jadwal mata pelajaran gagal karena tidak ditemukan solusi optimal, Waktu Proses = ' + waktu);
                        }
                        tableJadwal.ajax.reload();
                    },
                    error: function(res) {
                        const waktu = convertTime(start);
                        createNotification('error', 'Terjadi masalah di server, Waktu Proses = ' + waktu);
                    },
                    complete: function() {
                        $('#submitBtn')
                            .attr('disabled', false)
                            .html('<i class="bi bi-rocket-takeoff-fill"></i> Generate');
                    }
                });
            }
        });
        function closeModal() {
            $('#modal-simpan').modal('hide');
        }
        function showSaveModal() {
            $('#modal-simpan').modal('show');
            $('#simpan-jadwal').trigger('reset');
        }

        function saveJadwal(event) {
            event.preventDefault();
            if ($('#simpan-jadwal').parsley().validate()) {
            var tahunakademik = $('#tahun_akademik').val();

            $.ajax({
                url: "{{ route('generate.simpan') }}",
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    tahun_akademik: tahunakademik,
                },
                success: function (response) {
                    showSuccessAlert(response.success);
                    closeModal();
                    setTimeout(function() {
        window.location.href = "{{ route('jadwal.index') }}";
    }, 3000);
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
