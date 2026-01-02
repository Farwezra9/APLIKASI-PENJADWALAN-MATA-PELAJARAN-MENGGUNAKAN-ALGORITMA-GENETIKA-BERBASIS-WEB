@extends('admin.layouts.adminmaster')
@section('content')
<div class="page-heading">
        <div class="page-title">
            <div class="row">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Penjadwalan</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Tersimpan</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!-- Modal import -->
        <div class="modal fade text-left" id="modal-import" tabindex="-1" role="dialog" aria-labelledby="myModalLabel160" aria-hidden="true" style="display:none;">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header bg-primary">
                            <h5 class="modal-title white" id="myModalLabel160">FORM IMPORT JADWAL</h5>
                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                <i data-feather="x"></i>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form id="import-jadwal" class="form form-horizontal" enctype="multipart/form-data" data-parsley-validate>
                                <div class="form-body">
                                    <div class="row">
                                            <div class="mb-3">
                                                <label for="formFile" class="form-label">Pilih File Data Jadwal</label>
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
                <h5 class="card-title">Jadwal Mata Pelajaran SMKN 2 Kuningan<br>Semester {{ $semester }} Tahun Akademik {{ $tahunAkademik }}</h5>
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
                        <table class="table table-striped" style="width: 100%;" id="tabel-jadwal">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Hari</th>
                                    <th>Jam</th>
                                    <th>Guru</th>
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
    </div>
@endsection

@section('scripts')
<script>
    $(document).ready(function () {
        var table = $('#tabel-jadwal').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('jadwal.index') }}",
                type: 'GET'
            },
            columns: [
                {
                    data: null,
                    name: 'id',
                    render: function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },
                { data: 'hari', name: 'hari', className: 'text-center' },
                { data: 'jam', name: 'jam', className: 'text-center' },
                { data: 'guru', name: 'guru' },
                { data: 'mata_pelajaran', name: 'mata_pelajaran' },
                { data: 'kelas', name: 'kelas' },
                { data: 'sks', name: 'sks', className: 'text-center' },
            ],
            order: [[5, 'ASC'],[1, 'DESC'],[2, 'ASC']]
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
    function showImportModal() {
            $('#modal-import').modal('show');
            $('#import-jadwal').trigger('reset');
        }
    $('#cetakBtn').click(function () {
    Swal.fire({
        title: "Konfirmasi",
        text: "Cetak jadwal mata pelajaran?",
        icon: 'question',
        showCancelButton: true,
        showCloseButton: false,
        cancelButtonColor: '#999',
        confirmButtonColor: '#435EBE',
        confirmButtonText: 'Ya',
        cancelButtonText: 'Batal'
    }).then(function (result) {
        if (result.value) {
            window.location = "{{ route('cetak.jadwal') }}";
            Swal.fire({
            icon: 'success',
            text: 'Jadwal mata pelajaran berhasil dicetak!',
            title: "Sukses",
            showConfirmButton: true
          });
        }
    });
});
    $('#import-jadwal').submit(function (event) {
            event.preventDefault();

            var formData = new FormData(this);
            formData.append('_token', '{{ csrf_token() }}');  // Tambahkan token CSRF ke FormData

            $.ajax({
                url: "{{ route('jadwal.import') }}",
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function (response) {
                    showSuccessAlert(response.success);
                    $('#modal-import').modal('hide');
                    $('#tabel-jadwal').DataTable().ajax.reload();
                },
                error: function (xhr, status, error) {
                    showErrorAlert('Terjadi kesalahan saat mengimpor data jadwal.');
                }
            });
        });
</script>
@endsection
