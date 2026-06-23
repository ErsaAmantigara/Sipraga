<nav>

    <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
        <i class="bi bi-speedometer2 me-2"></i> Dashboard
    </a>

    @can('pengaduan.view')
        <a href="{{ route('pengaduan.index') }}" class="{{ request()->routeIs('pengaduan.*') ? 'active' : '' }}">
            <i class="bi bi-chat-square-text me-2"></i> Pengaduan
        </a>
    @endcan

    @can('pengerjaan.view')
        <a href="{{ route('pengerjaan.index') }}" class="{{ request()->routeIs('pengerjaan.*') ? 'active' : '' }}">
            <i class="bi bi-tools me-2"></i> Pengerjaan
        </a>
    @endcan

    @can('cabang.view')
        <a href="{{ route('cabang.index') }}" class="{{ request()->routeIs('cabang.*') ? 'active' : '' }}">
            <i class="bi bi-building me-2"></i> Cabang
        </a>
    @endcan

    @can('kriteria-saw.view')
        <a href="{{ route('kriteria-saw.index') }}" class="{{ request()->routeIs('kriteria-saw.*') ? 'active' : '' }}">
            <div class="d-flex align-items-center">
                <i class="bi bi-list-check me-2"></i>
                <p class="m-0">Kriteria Prioritas <br> Pengaduan</p>
            </div>
        </a>
    @endcan

    @can('penilaian-saw.view')
        <a href="{{ route('penilaian-saw.index') }}" class="{{ request()->routeIs('penilaian-saw.*') ? 'active' : '' }}">
            <div class="d-flex align-items-center">
                <i class="bi bi-graph-up me-2"></i>
                <p class="m-0">Penilaian Prioritas <br> Pengaduan</p>
            </div>
        </a>
    @endcan

    @can('users.view')
        <a href="{{ route('users.index') }}" class="{{ request()->routeIs('users.*') ? 'active' : '' }}">
            <i class="bi bi-people me-2"></i> Users
        </a>
    @endcan

    @can('laporan.view')
        <a href="{{ route('laporan.index') }}" class="{{ request()->routeIs('laporan.*') ? 'active' : '' }}">
            <i class="bi bi-download me-2"></i> Laporan
        </a>
    @endcan

    @can('roles.view')
        <a href="{{ route('roles.index') }}" class="{{ request()->routeIs('roles.*') ? 'active' : '' }}">
            <i class="bi bi-shield-lock me-2"></i> Roles
        </a>
    @endcan

    @can('roles.view')
        <a href="{{ route('permissions.index') }}" class="{{ request()->routeIs('permissions.index') ? 'active' : '' }}">
            <i class="bi bi-key me-2"></i> Permissions
        </a>
    @endcan

    @can('profile-pelanggan.view')
        <a href="{{ route('profile.edit') }}" class="{{ request()->routeIs('profile.*') ? 'active' : '' }}">
            <i class="bi bi-gear me-2"></i> Profile
        </a>
    @endcan

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <br>
        <button type="submit" class="btn btn-link text-white text-start w-100">
            <i class="bi bi-box-arrow-right me-2"></i> Logout
        </button>
    </form>
</nav>
