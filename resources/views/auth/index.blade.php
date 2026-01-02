<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <link href="{{ asset('img/logo.png') }}" rel="icon">
    <title>Login | Aplikasi Penjadwalan Mata Pelajaran SMKN 2 Kuningan</title>
    <meta content='width=device-width, initial-scale=1.0, shrink-to-fit=no' name='viewport' />
    <link href="{{ asset('compiled/css/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="{{ asset('extensions/sweetalert2/sweetalert2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('extensions/@fortawesome/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('compiled/css/my-login.css') }}">
</head>
<body class="my-login-page">
    <section class="h-100">
        <div class="container h-100">
            <div class="row justify-content-md-center h-100">
                <div class="card-wrapper">
                    <div class="card fat">
                        <div class="card-body">
                        <div class="brand">
                        <img src="{{ asset('img/logo_app.png') }}" alt="logo">
                            </div>
                            <form class="my-login-validation" id="form-login" novalidate="">
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="text" id="email" class="form-control" name="email" placeholder="Email" required autofocus>
                                    <div class="help-block with-errors"></div>
                                </div>

                                <div class="form-group">
                                <label for="password">Password</label>
                                    <input id="password" type="password" class="form-control" name="password" placeholder="Password" required data-eye>
                                    <div class="help-block with-errors"></div>
                                </div>

                                <div class="form-group m-0">
                                    <button type="submit" class="btn btn-primary btn-block">
                                        Login
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="footer">
                    <p>{{ date('Y') }} SMKN 2 Kuningan</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script src="{{ asset('compiled/js/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('compiled/js/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('compiled/js/bootstrap/js/popper.min.js') }}"></script>
    <script src="{{ asset('extensions/sweetalert2/sweetalert2.min.js') }}"></script>
    <script>
        $(document).ready(function () {
            $("input[type='password'][data-eye]").each(function (i) {
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
                    top: ($this.outerHeight() / 2) - 12,
                    padding: '2px 7px',
                    fontSize: 12,
                    cursor: 'pointer',
                }).append('<i class="fas fa-eye-slash"></i>'));

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
                        $(this).html('<i class="fas fa-eye-slash"></i>');
                    } else {
                        $this.attr('type', 'text');
                        $this.val($("#passeye-"+i).val());				
                        $this.addClass("show");
                        $(this).html('<i class="fas fa-eye"></i>');
                    }
                });
            });

            $('#form-login').submit(function (e) {
                e.preventDefault();
                var email = $('#email').val();
                var password = $('#password').val();
                $.ajax({
                    type: "POST",
                    url: "{{ route('login.action') }}",
                    dataType: 'JSON',
                    data: {
                        _token: '{{ csrf_token() }}',
                        email: email,
                        password: password,
                    },
                    success: function (response) {
                        if (response.success) {
                            var role = response.role;
                            Swal.fire({
                                icon: 'success',
                                text: 'Anda berhasil Login',
                                showConfirmButton: false,
                                timer: 1500
                            });
                            if (role === 'admin') {
                                setTimeout(function () {
                                    window.location = "{{ route('admin.site.halaman_admin') }}";
                                }, 1200);
                            } else if(role === 'guru') {
                                setTimeout(function () {
                                    window.location = "{{ route('user.site.halaman_guru') }}";
                                }, 1200);
                            } else if(role === 'murid') {
                                setTimeout(function () {
                                    window.location = "{{ route('user.site.halaman_murid') }}";
                                }, 1200);
                            }
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Terjadi Kesalahan.',
                                text: 'Masukan username dan password dengan benar',
                                showConfirmButton: false,
                                timer: 1500
                            });
                        }
                    }
                });
            });
        });
    </script>
@if(session('logout'))
    <script>
        Swal.fire({
            icon: 'success',
            title: '{{ session('logout') }}',
        });
    </script>
@endif
@if(session('not_login'))
    <script>
        Swal.fire({
            icon: 'error',
            title: '{{ session('not_login') }}',
        });
    </script>
@endif
</body>
</html>
