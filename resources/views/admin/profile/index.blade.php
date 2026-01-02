@extends('admin.layouts.adminmaster')

@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Profile</a></li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- Avatar and Upload Form --}}
        <div class="col-md-4">
            <div class="card">
                <div class="card-body pt-4 d-flex flex-column align-items-center">
                    @if($user)
                        <div class="avatar img">
                            <img src="{{ asset($user->profile_image) }}" alt="Gambar Profil" class="avatar-img rounded">
                        </div>
                        <form id="form-upload" enctype="multipart/form-data">
                            <input type="hidden" id="user-id-image" name="user-id-image" value="{{ $user->id }}">
                            <div class="form-group">
                                <div class="mb-3">
                                    <label for="profile_image" class="form-label"></label>
                                    <input type="file" class="form-control" id="profile_image">
                                    <i class="fas fa-upload"></i>
                                    </label>
                                </div>
                            </div>
                        </form>
                    @else
                        <p>No user data available.</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- Profile Details and Edit/Change Password Tabs --}}
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <ul class="nav nav-pills nav-default" id="pills-tab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="pills-details-tab" data-toggle="pill" href="#pills-details" role="tab" aria-controls="pills-home" aria-selected="true">Profile Details</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="pills-edit-tab" data-toggle="pill" href="#pills-edit" role="tab" aria-controls="pills-profile" aria-selected="false">Edit Profile</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="pills-password-tab" data-toggle="pill" href="#pills-password" role="tab" aria-controls="pills-contact" aria-selected="false">Change Password</a>
                        </li>
                    </ul>

                    <div class="tab-content mt-2 mb-3" id="pills-tabContent">
                        {{-- Profile Details Tab --}}
                        @if($user)
                            <div class="tab-pane fade show active" id="pills-details" role="tabpanel" aria-labelledby="pills-home-tab">
                                <h5 class="card-title mb-4 mt-4">Profile Details</h5>
                                <div class="row mb-4 profile-details">
                                    <div class="col-lg-3 col-md-4 label">Nama</div>
                                    <div class="col-lg-9 col-md-8">{{ $user->nama }}</div>
                                </div>
                                <div class="row mb-4 profile-details">
                                    <div class="col-lg-3 col-md-4 label">Email</div>
                                    <div class="col-lg-9 col-md-8">{{ $user->email }}</div>
                                </div>
                                <div class="row mb-4 profile-details">
                                    <div class="col-lg-3 col-md-4 label">Role</div>
                                    <div class="col-lg-9 col-md-8">{{ $user->role }}</div>
                                </div>
                            </div>
                        @else
                            <p>No user data available.</p>
                        @endif

                        {{-- Edit Profile Tab --}}
                        <div class="tab-pane fade" id="pills-edit" role="tabpanel" aria-labelledby="pills-profile-tab">
                            @if($user)
                                <form id="form-profile">
                                    <input type="hidden" id="user-id-pass" name="user-id" value="{{ $user->id }}">
                                    <div class="row mb-4 mt-5 profile-details">
                                        <div class="col-lg-3 col-md-4 label">Nama</div>
                                        <div class="col-lg-9 col-md-8">
                                            <input type="text" class="form-control" id="nama" name="nama" placeholder="Nama" data-bv-notempty="true" data-bv-notempty-message="Nama tidak boleh kosong" value="{{ $user->nama }}">
                                        </div>
                                    </div>
                                    <div class="row mb-4 profile-details">
                                        <div class="col-lg-3 col-md-4 label">Email</div>
                                        <div class="col-lg-9 col-md-8">
                                            <input type="email" class="form-control" id="email" name="email" placeholder="Email" value="{{ $user->email }}">
                                        </div>
                                    </div>
                                    <div class="row mb-4 profile-details">
                                        <div class="col-lg-9 col-md-8 ml-auto text-right">
                                            <button type="submit" id="submit" class="btn btn-success">
                                                <span class="btn-label">
                                                    <i class="fas fa-edit"></i>
                                                </span>
                                                Update
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            @else
                                <p>No user data available.</p>
                            @endif
                        </div>

                        {{-- Change Password Tab --}}
                        <div class="tab-pane fade" id="pills-password" role="tabpanel" aria-labelledby="pills-contact-tab">
                            @if($user)
                                <form id="form-password">
                                    <input type="hidden" id="user-id" name="user-id-pass" value="{{ $user->id }}">
                                    <div class="row mb-4 mt-5 profile-details">
                                        <div class="col-lg-3 col-md-4 label">Password Saat Ini</div>
                                        <div class="col-lg-9 col-md-8">
                                            <input type="password" class="form-control" id="password" name="password" placeholder="Password" required data-eye>
                                        </div>
                                    </div>
                                    <div class="row mb-4 profile-details">
                                        <div class="col-lg-3 col-md-4 label">Password Baru</div>
                                        <div class="col-lg-9 col-md-8">
                                            <input id="password_baru" type="password" class="form-control" name="password_baru" placeholder="Password Baru" required data-eye>
                                        </div>
                                    </div>
                                    <div class="row mb-4 profile-details">
                                        <div class="col-lg-3 col-md-4 label">Re-Password Baru</div>
                                        <div class="col-lg-9 col-md-8">
                                            <input id="re_password_baru" type="password" class="form-control" name="re_password_baru" placeholder="Re-Password Baru" required data-eye>
                                        </div>
                                    </div>
                                    <div class="row mb-4 profile-details">
                                        <div class="col-lg-9 col-md-8 ml-auto text-right">
                                            <button type="submit" id="submitBtn" class="btn btn-success">
                                                <span class="btn-label">
                                                    <i class="fas fa-edit"></i>
                                                </span>
                                                Update
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            @else
                                <p>No user data available.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('scripts')
    <script>
         $(document).ready(function () {
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

        $('#form-profile').submit(function (event) {
            UpdateProfile(event);
        });

        $('#form-password').submit(function (event) {
            UpdatePassword(event);
        });

        $('#profile_image').change(function () {
            UpdateProfileImage(event);
        });

        $('.delete').click(function(e) {
        e.preventDefault();
        var userId = $(this).data('user-id');
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
                        url: '/deleteProfileImage/' + userId,
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                        },
                        success: function (response) {
                            showSuccessAlert(response.success);
                            window.location.reload();
                        },
                        error: function (xhr, status, error) {
                            showErrorAlert('Terjadi kesalahan saat menghapus Profile Image.');
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
                title: 'Error',
                text: message,
                icon: 'error',
                showConfirmButton: false,
                showCloseButton: true
            });
        }

        // Function update profile via AJAX.
        function UpdateProfile(event) {
            event.preventDefault();

            var id = $('#user-id').val();
            var nama = $('#nama').val();
            var email = $('#email').val();
            var username = $('#username').val();
            var role = $('#role').val();
            var url = '/profile/' + id;
            var method = 'PUT';

        $.ajax({
            url: url,
            type: method,
            data: {
                    _token: '{{ csrf_token() }}',
                    nama: nama,
                    email: email,
                    username: username,
                    role: role
                },
                success: function (response) {
                    showSuccessAlert(response.success);
                    window.location.reload();
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

        // Function update password via AJAX.
        function UpdatePassword(event) {
            event.preventDefault();

            var id = $('#user-id-pass').val();
            var password = $('#password').val();
            var password_baru = $('#password_baru').val();
            var re_password_baru = $('#re_password_baru').val();
            var url = '/profilePassword/' + id;
            var method = 'PUT';

        $.ajax({
            url: url,
            type: method,
            data: {
                    _token: '{{ csrf_token() }}',
                    password: password,
                    password_baru: password_baru,
                    re_password_baru: re_password_baru
                },
                success: function (response) {
                    showSuccessAlert(response.success);
                    window.location.reload();
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
        function UpdateProfileImage(event) {
    event.preventDefault();

    var id = $('#user-id-image').val();
    var profile_image = $('#profile_image')[0].files[0]; // Get the selected image file
    var url = '/profileImage/' + id;

    var formData = new FormData();
    formData.append('profile_image', profile_image);
    formData.append('_token', '{{ csrf_token() }}');

    $.ajax({
        url: url,
        type: 'POST',
        data: formData,
        processData: false, // Important: prevent jQuery from processing data
        contentType: false, // Important: prevent jQuery from setting contentType
        success: function (response) {
            showSuccessAlert(response.success);
            window.location.reload();
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
