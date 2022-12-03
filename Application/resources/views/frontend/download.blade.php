@extends('frontend.layouts.front')
@section('title', lang('Download', 'download'))
@section('content')
    <div class="page-content full-height padding">
        <div class="w-100 text-center">
            @isset($password)
                <div class="files-uploaded v2">
                    <p class="files-uploaded-title">
                        <small>{{ lang('This transfer protected by password', 'download') }}</small>
                    </p>
                    <form action="{{ route('transfer.download.password.unlock', $transfer->link) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <div class="form-floating form-button">
                                <input type="password" name="password" class="form-control show-password"
                                    placeholder="{{ lang('Password', 'download') }}" required>
                                <label>{{ lang('Password', 'download') }}</label>
                                <button type="button" class="btn btn-secondary" data-password="show-password">
                                    <i class="fas fa-eye-slash"></i>
                                    <i class="fas fa-eye d-none"></i>
                                </button>
                            </div>
                        </div>
                        <div class="d-flex mt-3">
                            <button type="submit"
                                class="btn btn-secondary btn-xl w-100">{{ lang('Unlock transfer', 'download') }}</button>
                        </div>
                    </form>
                </div>
            @else
                <div class="files-uploaded v2">
                    <p class="files-uploaded-title mb-2">
                        {{ $transfer->subject ? $transfer->subject : lang('Files are ready!', 'download') }}</p>
                    <div class="files-info">
                        <div class="files-info-item">
                            <i class="fas fa-boxes"></i>
                            {{ count($transferFiles) }}
                            {{ count($transferFiles) > 1 ? lang('files', 'upload zone') : lang('file', 'upload zone') }},
                            {{ $transferFilesTotalSize }}
                        </div>
                        @if (!is_null($transfer->expiry_at))
                            <div class="files-info-item">
                                <i class="far fa-calendar-alt"></i>
                                {{ lang('Expires on', 'download') }} {{ vDate($transfer->expiry_at) }}
                            </div>
                        @endif
                    </div>
                    <div class="files-box">
                        @foreach ($transferFiles as $transferFile)
                            <div class="file">
                                <p class="file-title">{{ $transferFile->name }}</p>
                                <p class="file-size">{{ formatBytes($transferFile->size) }}</p>
                                <div class="file-options">
                                    <button class="download-btn btn btn-link" data-id="{{ hashid($transferFile->id) }}">
                                        <i class="fas fa-download"></i>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    @if ($transfer->storageProvider->symbol == 'local')
                        <div class="d-flex mt-4">
                            <button
                                class="download-all-btn btn btn-secondary btn-xl w-100">{{ count($transferFiles) > 1 ? lang('Download all', 'download') : lang('Download file', 'download') }}</button>
                        </div>
                    @endif
                </div>
            @endisset
        </div>
    </div>
    @push('config')
        <script>
            "use strict";
            const downloadConfig = {
                transferIdentifier: "{{ $transfer->link }}",
            };
            let downloadObjects = JSON.stringify(downloadConfig),
                getDownloadConfig = JSON.parse(downloadObjects);
        </script>
    @endpush
    @push('styles_libs')
        <link rel="stylesheet" href="{{ asset('assets/vendor/libs/aos/aos.min.css') }}">
    @endpush
    @push('scripts_libs')
        <script src="{{ asset('assets/vendor/libs/aos/aos.min.js') }}"></script>
        <script>
            "use strict";
            AOS.init({
                once: true,
                disable: 'mobile',
            });
        </script>
    @endpush
@endsection
