@extends('frontend.layouts.pages')
@section('title', lang('Contact Us'))
@section('content')
    <div class="container">
        <div class="page-card margin border-secondary">
            <div class="page-card-body bg-light border">
                <form id="contactForm" method="POST">
                    @csrf
                    <div class="row row-cols-1 g-3 row-cols-md-2 gx-3 mb-3">
                        <div class="col">
                            <label class="form-label">{{ lang('Name', 'forms') }} : <span
                                    class="red">*</span></label>
                            <input type="text" name="name" class="form-control"
                                value="{{ userAuthInfo()->name ?? old('name') }}" required />
                        </div>
                        <div class="col">
                            <label class="form-label">{{ lang('Email address', 'forms') }} : <span
                                    class="red">*</span></label>
                            <input type="email" name="email" class="form-control"
                                value="{{ userAuthInfo()->email ?? old('email') }}" required />
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ lang('Subject', 'forms') }} : <span
                                class="red">*</span></label>
                        <input type="text" name="subject" class="form-control" value="{{ old('subject') }}" required />
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ lang('Message', 'forms') }} : <span
                                class="red">*</span></label>
                        <textarea type="text" name="message" name="message" class="form-control" rows="8"
                            required>{{ old('message') }}</textarea>
                    </div>
                    {!! display_captcha() !!}
                    <div class="d-flex">
                        <button id="sendMessage"
                            class="btn btn-secondary btn-xl px-5">{{ lang('Send', 'home page') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @push('scripts')
        {!! google_captcha() !!}
    @endpush
@endsection
