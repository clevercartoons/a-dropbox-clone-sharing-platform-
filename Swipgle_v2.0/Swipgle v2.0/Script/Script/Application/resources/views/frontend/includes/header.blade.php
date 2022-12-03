<header class="main-header">
    <nav class="nav-bar">
        <div class="container d-flex align-items-center">
            <a class="nav-bar-logo" href="{{ url('/') }}">
                <img src="{{ asset($settings['website_light_logo']) }}" alt="{{ $settings['website_name'] }}" />
            </a>
            <div class="nav-bar-links ms-auto">
                <div class="nav-bar-links-close d-xl-none">
                    <i class="fa fa-times"></i>
                </div>
                @foreach ($navbarMenuLinks as $navbarMenuLink)
                    <a class="nav-bar-link"
                        @if (!$navbarMenuLink->type) href="{{ $navbarMenuLink->link }}" @else data-link="{{ $navbarMenuLink->link }}" @endif>
                        {{ $navbarMenuLink->name }}
                    </a>
                @endforeach
                <div class="nav-bar-link language dropdown" data-dropdown>
                    <p class="mb-0">
                        <i class="fas fa-language fa-lg me-2"></i>
                        {{ getLangName() }}
                        <i class="fas fa-chevron-down fa-xs ms-1"></i>
                    </p>
                    <div class="nav-bar-link-dropdown">
                        @foreach ($languages as $language)
                            <a class="@if (app()->getLocale() == $language->code) active @endif"
                                href="{{ langURL($language->code) }}">{{ $language->name }}</a>
                        @endforeach
                    </div>
                </div>
                @guest
                    @if ($settings['website_registration_status'])
                        <a class="nav-bar-link btn btn-outline-light btn-sm" href="{{ route('register') }}">
                            {{ lang('Sign Up', 'user') }}
                        </a>
                    @endif
                    <a class="nav-bar-link btn btn-light btn-sm" href="{{ route('login') }}">
                        {{ lang('Sign In', 'user') }}
                    </a>
                @endguest
            </div>
            @auth
                <div class="user-menu ms-auto ms-xl-2" data-dropdown>
                    <div class="user-avatar">
                        <img src="{{ asset(userAuthInfo()->avatar) }}" alt="{{ userAuthInfo()->name }}" />
                    </div>
                    <p class="user-name mb-0 ms-2 d-none d-sm-block">
                        {{ userAuthInfo()->name }}
                    </p>
                    <div class="nav-bar-user-dropdown-icon ms-2 d-none d-sm-block">
                        <i class="fas fa-chevron-down fa-xs"></i>
                    </div>
                    <div class="user-menu-dropdown">
                        <a class="user-menu-link" href="{{ route('user.dashboard') }}">
                            <i class="fas fa-th-large"></i>
                            {{ lang('Dashboard', 'user') }}
                        </a>
                        <a class="user-menu-link" href="{{ route('user.settings') }}">
                            <i class="fa fa-cog"></i>
                            {{ lang('Settings', 'user') }}
                        </a>
                        <form class="d-inline" action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button class="user-menu-link text-danger">
                                <i class="fa fa-power-off me-2"></i>{{ lang('Logout', 'user') }}
                            </button>
                        </form>
                    </div>
                </div>
            @endauth
            <div class="nav-bar-menu-icon {{ auth()->check() ? 'ms-3' : 'ms-auto' }} d-xl-none">
                <i class="fa fa-bars"></i>
            </div>
        </div>
    </nav>
</header>
