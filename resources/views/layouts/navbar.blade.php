<nav class="navbar navbar-dark" style="background: #1a2744;">
    <div class="container-fluid">
        <div class="d-flex align-items-center">
            <button class="navbar-toggler d-md-none me-2 border-0" type="button"
                data-bs-toggle="offcanvas" data-bs-target="#sidebarOffcanvas">
                <span class="navbar-toggler-icon"></span>
            </button>
            <span class="navbar-brand mb-0 fw-bold d-md-none"></i>SIPRAGA</span>
        </div>
        <div class="d-flex align-items-center gap-2">
            <div class="user-info text-white text-end d-none d-md-block">
                <div class="name">{{ Auth::user()->name }}</div>
                <div class="role">{{ Auth::user()->getRoleNames()->implode(', ') }}</div>
            </div>
            <div class="dropdown">
                <button class="btn btn-sm btn-outline-light rounded-circle" data-bs-toggle="dropdown"
                    style="width: 36px; height: 36px; display: flex; align-items: center; justify-content: center;">
                    <i class="bi bi-person-fill"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                    <li class="dropdown-item-text d-md-none">
                        <strong>{{ Auth::user()->name }}</strong><br>
                        <small class="text-muted">{{ Auth::user()->getRoleNames()->implode(', ') }}</small>
                    </li>
                    <li><hr class="dropdown-divider d-md-none"></li>
                    @can('profile-pelanggan.view')
                        <li>
                            <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                <i class="bi bi-gear me-2"></i> Profile
                            </a>
                        </li>
                    @endcan
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="dropdown-item" type="submit">
                                <i class="bi bi-box-arrow-right me-2"></i> Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>
