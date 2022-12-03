@extends('backend.layouts.form')
@section('title', __('Create feature'))
@section('back', route('admin.features.index'))
@section('content')
    <form id="vironeer-submited-form" action="{{ route('admin.features.store') }}" method="POST"
        enctype="multipart/form-data">
        @csrf
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card p-2">
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">{{ __('Feature title') }} : <span
                                    class="red">*</span></label>
                            <input type="text" name="title" class="form-control" value="{{ old('title') }}" required
                                autofocus />
                        </div>
                        <div class="mb-3">
                            <label class="form-label">{{ __('Feature content') }} :
                                <small
                                    class="text-muted">({{ __('Max 600 characters, spaces allowed') }})</small><span
                                    class="red">*</span></label>
                            <textarea name="content" rows="10" class="form-control"
                                required>{{ old('content') }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">{{ __('Link Text') }} : <small
                                    class="text-muted">({{ __('Optional') }})</small></label>
                            <input type="link" name="link_text" class="form-control" value="{{ old('link_text') }}"
                                placeholder="Read more" />
                        </div>
                        <div class="mb-0">
                            <label class="form-label">{{ __('Link') }} : <small
                                    class="text-muted">({{ __('Optional') }})</small></label>
                            <input type="link" name="link" class="form-control" value="{{ old('link') }}"
                                placeholder="/" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <div class="vironeer-file-preview-box mb-3 bg-light p-5 text-center">
                            <div class="file-preview-box mb-3 d-none">
                                <img id="filePreview" src="#" class="rounded-3 w-100" height="160">
                            </div>
                            <button id="selectFileBtn" type="button"
                                class="btn btn-secondary mb-2">{{ __('Choose Image') }}</button>
                            <input id="selectedFileInput" type="file" name="image" accept="image/png, image/jpg, image/jpeg"
                                hidden required>
                            <small class="text-muted d-block">{{ __('Allowed (PNG, JPG, JPEG)') }}</small>
                            <small class="text-muted d-block">{{ __('Image will be resized into (500x280)') }}</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">{{ __('Language') }} :<span
                                    class="red">*</span></label>
                            <select name="lang" class="form-select select2" required>
                                <option></option>
                                @foreach ($adminLanguages as $adminLanguage)
                                    <option value="{{ $adminLanguage->code }}"
                                        @if (old('lang') == $adminLanguage->code) selected @endif>
                                        {{ $adminLanguage->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
