@include('frontend.configurations.metaTags')
<title>{{ $settings['website_name'] }} @hasSection('title')— @yield('title') @endif
</title>
<link rel="shortcut icon" href="{{ asset($settings['website_favicon']) }}">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Almarai:wght@300;400;700&display=swap">
