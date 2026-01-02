@extends('user.layouts.usermaster')
@section('content')

<div class="row px-3"> 
    <h4><i class="icon-mid bi bi-person me-2"></i>Profile</h4>
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
                    @if($profile)
                        <div class="tab-pane fade show active" id="pills-details" role="tabpanel" aria-labelledby="pills-home-tab">
                            <h5 class="card-title mb-4 mt-4">Profile Details</h5>
                            <div class="row mb-4 profile-details">
                                <div class="col-lg-3 col-md-4 label">NISN</div>
                                <div class="col-lg-9 col-md-8">{{ $profile->nisn }}</div>
                            </div>
                            <div class="row mb-4 profile-details">
                                <div class="col-lg-3 col-md-4 label">Nama</div>
                                <div class="col-lg-9 col-md-8">{{ $profile->nama }}</div>
                            </div>
                            <div class="row mb-4 profile-details">
                                <div class="col-lg-3 col-md-4 label">Kelas</div>
                                <div class="col-lg-9 col-md-8">{{ $profile->kelas->nama_kelas }}</div>
                            </div>
                            <div class="row mb-4 profile-details">
                                <div class="col-lg-3 col-md-4 label">Jenis Kelamin</div>
                                <div class="col-lg-9 col-md-8">{{ $profile->jk }}</div>
                            </div>
                            <div class="row mb-4 profile-details">
                                <div class="col-lg-3 col-md-4 label">Email</div>
                                <div class="col-lg-9 col-md-8">{{ $profile->email }}</div>
                            </div>
                            <div class="row mb-4 profile-details">
                                <div class="col-lg-3 col-md-4 label">Alamat</div>
                                <div class="col-lg-9 col-md-8">{{ $profile->alamat ?? '-' }}</div>
                            </div>
                            <div class="row mb-4 profile-details">
                                <div class="col-lg-3 col-md-4 label">No Telp</div>
                                <div class="col-lg-9 col-md-8">{{ $profile->notelp ?? '-' }}</div>
                            </div>
                        </div>
                    @else
                        <p>No user data available.</p>
                    @endif

                    {{-- Edit Profile Tab --}}
                    <div class="tab-pane fade" id="pills-edit" role="tabpanel" aria-labelledby="pills-profile-tab">
                    @if($profile)
                        <form id="form-profile">
                            <input type="hidden" id="user-id" name="user-id" value="{{ $profile->id }}">
                            <div class="row mb-4 mt-5 profile-details">
                                <div class="col-lg-3 col-md-4 label">NISN</div>
                                <div class="col-lg-9 col-md-8">
                                    <input type="number" class="form-control" id="nisn" name="nisn" placeholder="NISN" value="{{ $profile->nisn }}">
                                </div>
                            </div>
                            <div class="row mb-4 profile-details">
                                <div class="col-lg-3 col-md-4 label">Nama</div>
                                <div class="col-lg-9 col-md-8">
                                    <input type="text" class="form-control" id="nama" name="nama" placeholder="Nama" value="{{ $profile->nama }}">
                                </div>
                            </div>
                            <div class="row mb-4 profile-details">
                                <div class="col-lg-3 col-md-4 label">kelas</div>
                                <div class="col-lg-9 col-md-8">
                                <select class="selectpicker" data-style="btn-outline-light" id="kelas" name="kelas" data-parsley-required="true" data-parsley-error-message="Kelas murid harus dipilih.">
                                <option value="" disabled {{ $profile->id_kelas ? '' : 'selected' }}>- Pilih Kelas Murid -</option>
                                @php
                                    $kelasByJurusan = $dataKelas->groupBy('id_jurusan');
                                @endphp

                                @foreach ($kelasByJurusan as $idJurusan => $kelasJurusan)
                                    @php
                                        $jurusan = $kelasJurusan->first()->jurusan->nama_jurusan;
                                    @endphp

                                    <optgroup label="{{ $jurusan }}">
                                        @foreach ($kelasJurusan as $kelas)
                                            <option value="{{ $kelas->id }}" data-subtext="Tingkat {{ $kelas->tingkat }}"
                                                {{ $kelas->id == $profile->id_kelas ? 'selected' : '' }}>
                                                {{ $kelas->nama_kelas }}
                                            </option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                                </div>
                            </div>
                            <div class="row mb-4 profile-details">
                                <div class="col-lg-3 col-md-4 label">Jenis Kelamin</div>
                                <div class="col-lg-9 col-md-8">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="jk" id="jkL" value="Laki-laki" {{ $profile->jk == 'Laki-laki' ? 'checked' : '' }} required>
                                        <label class="form-check-label" for="jkL">Laki-laki</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="jk" id="jkP" value="Perempuan" {{ $profile->jk == 'Perempuan' ? 'checked' : '' }} required>
                                        <label class="form-check-label" for="jkP">Perempuan</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-4 profile-details">
                                <div class="col-lg-3 col-md-4 label">Email</div>
                                <div class="col-lg-9 col-md-8">
                                    <input type="email" class="form-control" id="email" name="email" placeholder="Email" value="{{ $profile->email }}">
                                </div>
                            </div>
                            <div class="row mb-4 profile-details">
                                <div class="col-lg-3 col-md-4 label">Alamat</div>
                                <div class="col-lg-9 col-md-8">
                                    <textarea class="form-control" id="alamat" name="alamat" rows="3" placeholder="Alamat">{{ $profile->alamat }}</textarea>
                                </div>
                            </div>
                            <div class="row mb-4 profile-details">
                                <div class="col-lg-3 col-md-4 label">No Telp</div>
                                <div class="col-lg-9 col-md-8">
                                    <input type="number" class="form-control" id="notelp" name="notelp" placeholder="Nomor Telepon" value="{{ $profile->notelp }}">
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
                                <input type="hidden" id="user-id-pass" name="user-id-pass" value="{{ $user->id }}">
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
                title: 'Gagal',
                text: message,
                icon: 'error',
                showConfirmButton: false,
                showCloseButton: true
            });
        }

        function UpdateProfile(event) {
    event.preventDefault();

    var id = $('#user-id').val(); // Gunakan id dari input hidden
    var nisn = $('#nisn').val();
    var nama = $('#nama').val();
    var email = $('#email').val();
    var alamat = $('#alamat').val();
    var notelp = $('#notelp').val();
    var kelas = $('#kelas').val();
    var jk = $('input[name="jk"]:checked').val(); // Ambil nilai jenis kelamin yang dipilih
    var url = '/profilemurid/' + id;
    var method = 'PUT';

    $.ajax({
        url: url,
        type: method,
        data: {
            _token: '{{ csrf_token() }}',
            nisn: nisn,
            nama: nama,
            email: email,
            alamat: alamat,
            notelp: notelp,
            kelas: kelas,
            jk: jk
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
    var profile_image = $('#profile_image')[0].files[0]; 
    var url = '/profileImage/' + id;

    var formData = new FormData();
    formData.append('profile_image', profile_image);
    formData.append('_token', '{{ csrf_token() }}');

    $.ajax({
        url: url,
        type: 'POST',
        data: formData,
        processData: false, 
        contentType: false, 
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
