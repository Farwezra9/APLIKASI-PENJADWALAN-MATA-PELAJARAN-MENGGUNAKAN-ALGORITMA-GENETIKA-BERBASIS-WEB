@extends('admin.layouts.adminmaster')
@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Master Data</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Users</li>
                    </ol>
                </nav>
            </div>
        </div>

        <!-- Datatables -->
        <section class="section">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Data User</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped" style="width: 100%;" id="tabel-user">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Role</th>
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

<!-- Modal edit -->
<div class="modal fade text-left" id="modal-user" tabindex="-1" role="dialog" aria-labelledby="myModalLabel160" aria-hidden="true" style="display:none;">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <h5 class="modal-title white" id="myModalLabel160">FORM EDIT USER</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i data-feather="x"></i>
                </button>
            </div>
            <div class="modal-body">
                <form id="form-user" class="form form-horizontal" data-parsley-validate>
                    <input type="hidden" id="user-id" name="user-id">
                    <div class="form-body">
                        <div class="row">
                            <div class="col-md-4">
                                <label for="nama">Nama</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <input type="text" class="form-control" id="nama" name="nama" class="form-control" placeholder="Nama" data-parsley-required="true" data-parsley-error-message="Nama tidak boleh kosong.">
                            </div>
                            <div class="col-md-4">
                                <label for="email">Email</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <input type="email" class="form-control" id="email" name="email" class="form-control" placeholder="Email" data-parsley-required="true" data-parsley-error-message="Email tidak boleh kosong.">
                            </div>
                            <div class="col-md-4">
                                <label for="role">Role</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <select class="selectpicker" data-style="btn-outline-light" id="role" name="role" data-parsley-required="true" data-parsley-error-message="Role harus dipilih.">
                                    <option value="" disabled selected>- Pilih Role -</option>
                                    <option value="murid">MURID</option>
                                    <option value="guru">GURU</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="password">Password</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <input type="password" class="form-control" id="password" name="password" class="form-control" placeholder="Password" data-eye>
                            </div>
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
                </form>
            </div>
        </div>
</div>
<!-- Modal edit End -->
    @section('scripts')
    <script>
        $(document).ready(function () {
            var table = $('#tabel-user').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('user.index') }}",
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
                    { data: 'nama', name: 'nama' },
                    { data: 'email', name: 'email' },
                    { data: 'role', name: 'role' },
                    { data: 'action', name: 'action', orderable: false, searchable: false,className: 'text-center' }
                ],
                order: [[3, 'asc']]
            });
            $(document).on('click', '.edit', function () {
              var id = $(this).attr('id');
                $.ajax({
                    url: '/user/' + id + '/edit',
                    type: 'GET',
                    success: function (response) {
                        showEditModal(response);
                    },
                    error: function (xhr, status, error) {
                        showErrorAlert('Terjadi kesalahan saat mengambil data user.');
                    }
                });
            });

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
                        url: '/user/' + id,
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function (response) {
                            showSuccessAlert(response.success);
                            var table = $('#tabel-user').DataTable();
                            table.ajax.reload();
                        },
                        error: function (xhr, status, error) {
                            showErrorAlert('Terjadi kesalahan saat menghapus user.');
                        }
                    });
                }
            });
        });

            $('#form-user').submit(function (event) {
                updateUser(event);
            });

            $('#modal-close-btn').click(function () {
                closeModal();
            });
            $("input[type='password'][data-eye]").each(function(i) {
            var $this = $(this),
                id = 'eye-password-' + i,
                el = $('#' + id);

            $this.wrap($("<div/>", {
                style: 'position:relative',
                id: id
            }));

            $this.css({
                paddingRight: 60
            });
            $this.after($("<div/>", {
                class: 'show-password-icon',
                id: 'passeye-toggle-'+i,
            }).css({
                position: 'absolute',
                right: 10,
                top: ($this.outerHeight() / 2) - 13,
                padding: '25px 2px',
                fontSize: 12,
                cursor: 'pointer',
            }).append('<i class="bi bi-eye-slash"></i>'));

            $this.after($("<input/>", {
                type: 'hidden',
                id: 'passeye-' + i
            }));

            $this.on("keyup paste", function() {
                $("#passeye-"+i).val($(this).val());
            });
            $("#passeye-toggle-"+i).on("click", function() {
                if($this.hasClass("show")) {
                    $this.attr('type', 'password');
                    $this.removeClass("show");
                    $(this).html('<i class="bi bi-eye-slash"></i>');
                } else {
                    $this.attr('type', 'text');
                    $this.val($("#passeye-"+i).val());				
                    $this.addClass("show");
                    $(this).html('<i class="bi bi-eye"></i>');
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

        function showAddModal() {
            $('#modal-user').modal('show');
            $('#form-user').trigger('reset');
        }

        function showEditModal(user) {
            $('#modal-user').modal('show');
            $('#form-user').trigger('reset');
            $('#user-id').val(user.id);
            $('#nama').val(user.nama);
            $('#email').val(user.email);
            $('#username').val(user.username);
            $('#role').selectpicker('val', user.role);
            $('#password').attr('placeholder', "Kosongkan jika tidak diubah");
            $('#password').removeAttr('required');
        }

        function closeModal() {
            $('#modal-user').modal('hide');
        }

        // Function for saving user via AJAX.
        function updateUser(event) {
            event.preventDefault();

            var id = $('#user-id').val();
            var nama = $('#nama').val();
            var email = $('#email').val();
            var role = $('#role').val();
            var password = $('#password').val();

        $.ajax({
            url: '/user/' + id,
            type: 'PUT',
            data: {
                    _token: '{{ csrf_token() }}',
                    nama: nama,
                    email: email,
                    role: role,
                    password: password
                },
                success: function (response) {
                    showSuccessAlert(response.success);

                    var table = $('#tabel-user').DataTable();
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
    </script>
@endsection