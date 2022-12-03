@extends('frontend.layouts.front')
@section('title', $SeoConfiguration->title ?? '')
@section('content')
    @include('frontend.includes.hero')
    @push('styles_libs')
        <link rel="stylesheet" href="{{ asset('assets/vendor/libs/sweetalert/sweetalert2.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/vendor/libs/aos/aos.min.css') }}">
    @endpush
    @push('scripts_libs')
        <script src="{{ asset('assets/vendor/libs/clipboard/clipboard.min.js') }}"></script>
        <script src="{{ asset('assets/vendor/libs/sweetalert/sweetalert2.min.js') }}"></script>
        <script src="{{ asset('assets/vendor/libs/dropzone/dropzone.min.js') }}"></script>
        <script src="{{ asset('assets/vendor/libs/autosize/autosize.min.js') }}"></script>
        <script src="{{ asset('assets/vendor/libs/aos/aos.min.js') }}"></script>
        <script src="{{ asset('assets/vendor/libs/tags-input/tags-input.min.js') }}"></script>
    @endpush
    @push('scripts')
        <script src="{{ asset('assets/js/handler.js') }}"></script>
        @if (is_null($rating))
            <script src="{{ asset('assets/js/ratings.min.js') }}"></script>
        @endif
        <script>
            "use strict";
            AOS.init({
                once: true,
                disable: 'mobile',
            });
            autosize(document.querySelectorAll("[autosize]"));
        </script>
    @endpush
@endsection
