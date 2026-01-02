<div class="header-top">
                    <div class="container">
                        <div class="logo">
                        <a href="{{ url('/') }}"><img src="{{ asset('img/logo_app.png') }}" alt="Logo" srcset="" style="max-width: 200px; height: auto;"></a>
                        </div>
                        <div class="header-top-right">

                            <div class="dropdown">
                                <a href="#" id="topbarUserDropdown" class="user-dropdown d-flex align-items-center dropend dropdown-toggle " data-bs-toggle="dropdown" aria-expanded="false">
                                    <div class="avatar avatar-md2" >
                                    @if (Auth::check())
                                        @php $data = Auth::user(); 
                                        @endphp
                                        <img src="{{ asset($data->profile_image) }}" alt="Avatar">
                                    </div>
                                    <div class="text">
                                        <h6 class="user-dropdown-name">{{ $data->nama }}</h6>
                                    </div>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end shadow-lg" aria-labelledby="topbarUserDropdown">
                                <li><h6 class="dropdown-header">Hello, {{ $data->nama }}!</h6></li>
                                @if ($data->role === 'guru')
                                    <li><a class="dropdown-item" href="{{ route('user.site.halaman_guru') }}"><i class="icon-mid bi bi-calendar2-week me-2"></i>Jadwal</a></li>
                                @endif
                                @if ($data->role === 'murid')
                                    <li><a class="dropdown-item" href="{{ route('user.site.halaman_murid') }}"><i class="icon-mid bi bi-calendar2-week me-2"></i>Jadwal</a></li>
                                @endif

                                <li><hr class="dropdown-divider"></li>
                                  <li><a class="dropdown-item" href="{{ route('profile.index') }}"><i class="icon-mid bi bi-person me-2"></i>My Profile</a></li>
                                  <li><hr class="dropdown-divider"></li>
                                  <li><a class="dropdown-item" href="{{ route('logout') }}"><i class="icon-mid bi bi-box-arrow-left me-2"></i>Logout</a></li>
                                </ul>
                                @endif
                            </div>
                        </div>
                    </div>
</div>
