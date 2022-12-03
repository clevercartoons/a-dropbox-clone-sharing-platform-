<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('frontend.includes.head')
    @include('frontend.includes.styles')
</head>

<body>
    <div class="page-bg" style="background: url({{ asset($settings['website_home_background']) }}) no-repeat;">
        @include('frontend.includes.header')
        @yield('content')
    </div>
    @if (count($features) > 0)
        <div id="features" class="content-section">
            <div class="container">
                <div class="content-section-cont">
                    <div class="content-section-header">
                        <h2 class="content-section-title text-center mb-3">{{ lang('Features', 'home page') }}</h2>
                        <div class="col-lg-8 mx-auto">
                            <p class="content-section-text text-center text-muted">
                                {{ lang('Features description', 'home page') }}</p>
                        </div>
                    </div>
                    <div class="content-section-body">
                        <div class="features">
                            @foreach ($features as $i => $feature)
                                <div class="feat" data-aos="fade" data-aos-duration="1000">
                                    <div class="row align-items-center g-5">
                                        <div
                                            class="col-12 col-lg-7 @if ($i % 2 == 0) order-lg-2 @endif">
                                            <div class="feat-info">
                                                <p class="feat-info-title">{{ $feature->title }}</p>
                                                <p class="feat-info-text">{!! allowBr($feature->content) !!}</p>
                                                @if ($feature->link)
                                                    <a class="feat-info-link"
                                                        href="{{ $feature->link }}">{{ $feature->link_text }}</a>
                                                @endif
                                            </div>
                                        </div>
                                        <div
                                            class="col-12 col-lg-5 @if ($i % 2 == 0) order-lg-1 @endif">
                                            <div class="feat-img d-flex justify-content-center justify-content-lg-end">
                                                <img src="{{ asset($feature->image) }}" alt="{{ $feature->title }}"
                                                    title="{{ $feature->title }}" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
    @if (count($monthlyPlans) > 0 or count($yearlyPlans) > 0)
        <div id="prices" class="content-section bg">
            <div class="container">
                <div class="content-section-cont">
                    <div class="content-section-header">
                        <h2 class="content-section-title text-center mb-3">{{ lang('Pricing', 'home page') }}
                        </h2>
                        <div class="col-lg-8 mx-auto">
                            <p class="content-section-text text-center text-muted">
                                {{ lang('Pricing description', 'home page') }}</p>
                        </div>
                    </div>
                    <div class="content-section-body mt-4">
                        <div class="d-flex justify-content-center">
                            <div class="plan-switcher">
                                <span>{{ lang('Monthly', 'plans') }}</span>
                                <span>{{ lang('Yearly', 'plans') }}</span>
                            </div>
                        </div>
                        <div class="plans mt-4">
                            @include('frontend.includes.plans')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
    @if ($settings['website_blog_status'] && count($blogArticles) > 0)
        <div id="blog" class="content-section">
            <div class="container">
                <div class="content-section-cont">
                    <div class="content-section-header">
                        <h2 class="content-section-title text-center mb-3">{{ lang('Blog', 'home page') }}</h2>
                        <div class="col-lg-8 mx-auto">
                            <p class="content-section-text text-center text-muted">
                                {{ lang('Blog description', 'home page') }}</p>
                        </div>
                    </div>
                    <div class="content-section-body">
                        <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 g-3 mt-5 mb-5" data-aos="fade"
                            data-aos-duration="1000">
                            @foreach ($blogArticles as $blogArticle)
                                <div class="col">
                                    <div class="post aos-init aos-animate" data-aos="fade-zoom-in"
                                        data-aos-duration="1000">
                                        <div class="post-header">
                                            <div class="post-img"
                                                style="background-image: url({{ asset($blogArticle->image) }});">
                                            </div>
                                            <a class="post-section"
                                                href="{{ route('blog.category', $blogArticle->blogCategory->slug) }}">
                                                {{ $blogArticle->blogCategory->name }}
                                            </a>
                                        </div>
                                        <div class="post-body">
                                            <div class="post-meta">
                                                <p class="post-author mb-0">
                                                    <i class="fa fa-user"></i>
                                                    {{ $blogArticle->admin->firstname . ' ' . $blogArticle->admin->lastname }}
                                                </p>
                                                <time class="post-date">
                                                    <i class="fa fa-calendar-alt"></i>
                                                    {{ vDate($blogArticle->created_at) }}
                                                </time>
                                            </div>
                                            <a href="{{ route('blog.article', $blogArticle->slug) }}"
                                                class="post-title">{{ shortertext($blogArticle->title, 60) }}</a>
                                            <p class="post-text">
                                                {{ shortertext($blogArticle->short_description, 120) }}</p>
                                            <div class="post-action">
                                                <a href="{{ route('blog.article', $blogArticle->slug) }}"
                                                    class="btn btn-secondary">
                                                    {{ lang('Read More', 'blog') }} <i
                                                        class="fas fa-arrow-right fa-sm ms-1"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="d-flex justify-content-end mt-4">
                            <a href="{{ route('blog.index') }}" class="link">
                                {{ lang('View All', 'home page') }}
                                <i class="fas fa-arrow-right fa-sm"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
    @if (count($faqs) > 0)
        <div id="faq" class="content-section">
            <div class="container">
                <div class="content-section-cont">
                    <div class="content-section-header">
                        <h2 class="content-section-title text-center mb-3">{{ lang('FAQ', 'home page') }}</h2>
                        <div class="col-lg-8 mx-auto">
                            <p class="content-section-text text-center text-muted">
                                {{ lang('FAQ description', 'home page') }}</p>
                        </div>
                    </div>
                    <div class="content-section-body">
                        <div class="faq" data-aos="fade" data-aos-duration="1000">
                            <div class="accordion accordion-flush" id="accordionFlush">
                                @foreach ($faqs as $faq)
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="flush-heading{{ hashid($faq->id) }}">
                                            <button class="accordion-button collapsed" type="button"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#flush-collapse{{ hashid($faq->id) }}"
                                                aria-expanded="false" aria-controls="flush-collapseOne">
                                                {{ $faq->title }}
                                            </button>
                                        </h2>
                                        <div id="flush-collapse{{ hashid($faq->id) }}"
                                            class="accordion-collapse collapse"
                                            aria-labelledby="flush-heading{{ hashid($faq->id) }}"
                                            data-bs-parent="#accordionFlush">
                                            <div class="accordion-body">
                                                <div class="mb-0">{!! $faq->content !!}</div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="d-flex justify-content-end mt-4">
                                <a href="{{ route('faq') }}" class="link">
                                    {{ lang('Find out more answers on our FAQ', 'home page') }}
                                    <i class="fas fa-arrow-right fa-sm"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
    @if ($settings['website_contact_form_status'])
        <div id="contact" class="content-section bg">
            <div class="container">
                <div class="content-section-cont">
                    <div class="content-section-header">
                        <h2 class="content-section-title text-center mb-3">{{ lang('Contact Us', 'home page') }}
                        </h2>
                        <div class="col-lg-8 mx-auto">
                            <p class="content-section-text text-center text-muted">
                                {{ lang('Contact Us description', 'home page') }}</p>
                        </div>
                    </div>
                    <div class="content-section-body">
                        <div class="contact-us" data-aos="fade" data-aos-duration="1000">
                            <form id="contactForm" method="POST">
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
                                    <input type="text" name="subject" class="form-control"
                                        value="{{ old('subject') }}" required />
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
            </div>
        </div>
    @endif
    @include('frontend.includes.footer')
    @include('frontend.configurations.config')
    @include('frontend.configurations.widgets')
    @include('frontend.includes.scripts')
    {!! google_captcha() !!}
</body>

</html>
