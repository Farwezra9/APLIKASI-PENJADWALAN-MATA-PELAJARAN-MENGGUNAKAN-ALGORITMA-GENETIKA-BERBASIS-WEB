@extends('user.layouts.usermaster')
@section('content')
    <div class="page-heading">
        <!-- Datatables -->
        <div class="page-content">
        <section class="section">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title">Jadwal Mata Pelajaran SMKN 2 Kuningan<br>Semester {{ $semester }} Tahun Akademik {{ $tahunAkademik }}</h5>
                    <button class="btn btn-primary" id="cetakBtn">
                        <i class="bi bi-printer-fill"></i> Cetak
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
                                    <th>Mata Pelajaran</th>
                                    <th>SKS</th>
                                    <th>Kelas</th>
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
            url: "{{ route('user.site.halaman_guru') }}",
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
            { data: 'hari', name: 'hari' },
            { data: 'jam', name: 'jam'},
            { data: 'mata_pelajaran', name: 'mata_pelajaran' },
            { data: 'sks', name: 'sks' },
            { data: 'kelas', name: 'kelas' }
        ],
        order: [[1, 'DESC'],[2, 'ASC']]
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
            showConfirmButton: false,
            showCloseButton: true
        });
    }

    $('#cetakBtn').click(function () {
        Swal.fire({
            title: "Konfirmasi",
            text: "Cetak jadwal mata pelajaran?",
            icon: 'info',
            cancelButtonColor: '#999',
            confirmButtonColor: '#435EBE',
            showCancelButton: true,
            showCloseButton: false,

            confirmButtonText: 'Ya',
            cancelButtonText: 'Batal'
        }).then(function (result) {
            if (result.value) {
                window.location = "{{ route('jadwal.guru') }}";
                Swal.fire({
                    icon: 'success',
                    text: 'Jadwal mata pelajaran berhasil dicetak!',
                    title: "Berhasil",
                    showConfirmButton: true
                });
            }
        });
    });
</script>
@endsection
