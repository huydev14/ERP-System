<nav class="navbar navbar-expand topbar">
    <div class="brand-area">
        <button type="button" class="nav-link icon-btn" data-widget="pushmenu" aria-label="Toggle menu">
            <i class="fas fa-bars"></i>
        </button>
        <a href="/" class="brand-link">
            <img src="{{ asset('adminlte/dist/img/AdminLTELogo.png') }}" alt="App logo" class="brand-logo">
            <span class="brand-name">{{ config('app.name') }}</span>
            <span class="brand-module">Field Service</span>
        </a>
    </div>

    <div class="search-wrap">
        <i class="fas fa-search search-icon"></i>
        <input type="text" class="form-control search-input" placeholder="Search" aria-label="Search">
    </div>

    <ul class="navbar-nav topbar-actions">
        <li class="nav-item dropdown profile-menu">
            <a data-bs-toggle="dropdown" href="#" class="nav-link profile-link icon-btn">
                <img src="{{ asset('adminlte/dist/img/user2-160x160.jpg') }}" class="img-circle" alt="User">
                <span>{{ auth()->user()->name }}</span>
            </a>

            <div class="dropdown-menu dropdown-menu-end">
                <a href="{{ route('profile.edit') }}" class="dropdown-item">
                    <i class="fas fa-user mr-2"></i> Profile
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="dropdown-item">
                        <i class="fas fa-sign-out-alt mr-2"></i> Đăng xuất
                    </button>
                </form>
            </div>
        </li>

        <li class="nav-item dropdown">
            <a href="#" data-bs-toggle="dropdown" class="nav-link icon-btn !tw-px-1">
                <div class="tw-flex tw-flex-row ">
                    @if (App::getLocale() == 'vi')
                        <span class="fi fi-vn"></span>
                    @else
                        <span class="fi fi-gb"></span>
                    @endif
                    <i class="fas fa-caret-down ml-1"></i>
                </div>

            </a>
            <div class="dropdown-menu tw-right-0 tw-left-auto">
                <a href="{{ route('lang.switch', 'vi') }}"
                    class="dropdown-item {{ App::getLocale() == 'vi' ? 'active !tw' : '' }}">
                    <span class="fi fi-vn"></span> Tiếng Việt
                </a>
                <a href="{{ route('lang.switch', 'en') }}"
                    class="dropdown-item {{ App::getLocale() == 'en' ? 'active' : '' }}">
                    <span class="fi fi-gb"></span> English
                </a>
            </div>
        </li>

        <li class="nav-item">
            <button type="button" class="nav-link icon-btn" data-widget="fullscreen" aria-label="Fullscreen">
                <i class="fas fa-expand-arrows-alt"></i>
            </button>
        </li>
    </ul>
</nav>
