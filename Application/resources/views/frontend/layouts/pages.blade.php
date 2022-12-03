<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('frontend.includes.head')
    @include('frontend.includes.styles')
</head>

<body class="bg-light">
    <div class="page">
        <div class="page-header">
            @include('frontend.includes.header')
            <div class="page-content v2 text-center">
                <h2 class="page-title mb-0">@yield('title')</h2>
            </div>
        </div>
        @yield('content')
        @include('frontend.includes.footer')
    </div>
    @include('frontend.configurations.config')
    @include('frontend.configurations.widgets')
    @include('frontend.includes.scripts')
</body>

</html>
