<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="X-UA-Compatible" content="ie=edge" />
<meta name="csrf-token" content="{{ csrf_token() }}" />
<meta name="theme-color" content="{{ $settings['website_primary_color'] }}">
@if ($SeoConfiguration)
    @php
        $description = $__env->yieldContent('description') ? $__env->yieldContent('description') : $SeoConfiguration->description;
        $robots = $SeoConfiguration->robots_index . ', ' . $SeoConfiguration->robots_follow_links;
        $localeAlternate = $SeoConfiguration->language->code . '_' . strtoupper($SeoConfiguration->language->code);
    @endphp
    <meta name="title" content="{{ $settings['website_name'] }} - @yield('title')">
    <meta name="description" content="{{ $description }}">
    <meta name="keywords" content="{{ $SeoConfiguration->keywords }}">
    <link rel="alternate" hreflang="x-default" href="{{ url('/') }}" />
    @foreach ($languages as $language)
        <link rel="alternate" hreflang="{{ $language->code }}" href="{{ url($language->code) }}" />
    @endforeach
    <meta name="robots" content="{{ $robots }}">
    <meta name="language" content="{{ $SeoConfiguration->language->name }}">
    <meta name="author" content="{{ $settings['website_name'] }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ asset($settings['website_social_image']) }}">
    <meta property="og:site_name" content="{{ $settings['website_name'] }}">
    <meta property="og:locale" content="{{ $SeoConfiguration->language->code }}">
    <meta property="og:locale:alternate" content="{{ $localeAlternate }}">
    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ $settings['website_name'] }} - @yield('title')">
    <meta property="og:description" content="{{ $description }}">
    <meta property="og:image:width" content="600">
    <meta property="og:image:height" content="315">
    <meta name="twitter:card" content="summary">
    <meta name="twitter:title" content="{{ $settings['website_name'] }} - @yield('title')">
    <meta name="twitter:image:src" content="{{ asset($settings['website_social_image']) }}">
    <meta name="twitter:description" content="{{ $description }}">
@endif
