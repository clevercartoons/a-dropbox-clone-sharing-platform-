@include('frontend.configurations.metaTags')
<title>{{ $settings['website_name'] }} — {{ lang('User', 'user') }} - @yield('title')</title>
<link rel="shortcut icon" href="{{ asset($settings['website_favicon']) }}">
<link rel="stylesheet"
    href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;900&display=swap" />
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Almarai:wght@300;400;700&display=swap">
