<div id="sidebar">
    <div class="sidebar-wrapper active">
    <div class="sidebar-header position-relative">
        <div class="d-flex justify-content-between align-items-center">
        <div class="logo">
                <a href="#"><img src="{{ asset('img/logo_app.png') }}" alt="Logo" srcset="" style="max-width: 200px; height: auto;"></a>
            </div>
            <div class="sidebar-toggler  x">
                <a href="#" class="sidebar-hide d-xl-none d-block"><i class="bi bi-x bi-middle"></i></a>
            </div>
        </div>
    </div>
    <div class="sidebar-menu">
        <ul class="menu mt-0">
            <li
                class="{{ request()->routeIs('admin.site.halaman_admin') ? 'sidebar-item active' : 'sidebar-item' }}">
                <a href="{{ route('admin.site.halaman_admin') }}" class='sidebar-link'>
                    <i class="bi bi-grid-fill"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="sidebar-title">Master Data</li>
            <li
                class="{{ request()->routeIs('jurusan.index') ? 'sidebar-item active' : 'sidebar-item' }}  ">
                <a href="{{ route('jurusan.index') }}" class='sidebar-link'>
                    <i class="bi bi-mortarboard-fill"></i>
                    <span>Jurusan</span>
                </a>
            </li>
            <li
                class="{{ request()->routeIs('kelas.index') ? 'sidebar-item active' : 'sidebar-item' }}  ">
                    <a href="{{ route('kelas.index') }}" class='sidebar-link'>
                    <i class="bi bi-signpost-split-fill"></i>
                    <span>Kelas</span>
                </a>
            </li>
            <li
                class="{{ request()->routeIs('pelajaran.index') ? 'sidebar-item active' : 'sidebar-item' }}  ">
                    <a href="{{ route('pelajaran.index') }}" class='sidebar-link'>
                    <i class="bi bi-book-fill"></i>
                    <span>Mata Pelajaran</span>
                </a>
            </li>
            <li
                class="{{ request()->routeIs('murid.index') ? 'sidebar-item active' : 'sidebar-item' }}  ">
                    <a href="{{ route('murid.index') }}" class='sidebar-link'>
                    <i class="bi bi-backpack2-fill"></i>
                    <span>Murid</span>
                </a>
            </li>
            <li
                class="{{ request()->routeIs('guru.index') ? 'sidebar-item active' : 'sidebar-item' }}  ">
                    <a href="{{ route('guru.index') }}" class='sidebar-link'>
                    <i class="bi bi-person-vcard-fill"></i>
                    <span>Guru</span>
                </a>
            </li>
            <li class="{{ request()->routeIs('mengajar.index') ? 'sidebar-item active' : 'sidebar-item' }}  ">
                <a href="{{ route('mengajar.index') }}" class='sidebar-link'>
                    <i class="bi bi-pass-fill"></i>
                    <span>Mengajar</span>
                </a>
            </li>
            <li class="{{ request()->routeIs('jam.*') || request()->routeIs('hari.*') ? 'sidebar-item active has-sub' : 'sidebar-item has-sub' }}">
                <a href="#" class='sidebar-link'>
                    <i class="bi bi-clock-fill"></i>
                    <span>Waktu</span>
                </a>
                <ul class="{{ request()->routeIs('jam.*') || request()->routeIs('hari.*') ? 'submenu active submenu-open' : 'submenu submenu-closed' }}">
                    <li class="{{ request()->routeIs('hari.*') ? 'submenu-item active' : 'submenu-item' }}">
                        <a href="{{ route('hari.index') }}" class="submenu-link">Hari</a>
                    </li>
                    <li class="{{ request()->routeIs('jam.*') ? 'submenu-item active' : 'submenu-item' }}">
                        <a href="{{ route('jam.index') }}" class="submenu-link">Jam</a>
                    </li>
                </ul>
            </li>
            <li class="{{ request()->routeIs('user.index') ? 'sidebar-item active' : 'sidebar-item' }}  ">
                <a href="{{ route('user.index') }}" class='sidebar-link'>
                    <i class="bi bi-person-fill-gear"></i>
                    <span>User</span>
                </a>
            </li>
            <li class="sidebar-title">Penjadwalan</li>
            <li
                class="{{ request()->routeIs('generate.index') ? 'sidebar-item active' : 'sidebar-item' }}  ">
                    <a href="{{ route('generate.index') }}" class='sidebar-link'>
                    <i class="bi bi-calendar-week-fill"></i>
                    <span>Generate</span>
                </a>
            </li>
            <li
                class="{{ request()->routeIs('jadwal.index') ? 'sidebar-item active' : 'sidebar-item' }}  ">
                    <a href="{{ route('jadwal.index') }}" class='sidebar-link'>
                    <i class="bi bi-calendar2-check-fill"></i>
                    <span>Tersimpan</span>
                </a>
            </li>
        </ul>
    </div>
</div>
</div>