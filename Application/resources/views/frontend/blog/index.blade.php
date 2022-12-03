@extends('frontend.layouts.pages')
@section('title', $blogCategory ?? lang('Blog', 'blog'))
@section('content')
    <div class="container">
        <div class="blog">
            <div class="row g-3">
                <div class="col-12 col-lg-8">
                    @forelse ($blogArticles as $blogArticle)
                        <div class="blog-post">
                            <div class="card p-0 shadow-sm border-0">
                                <div class="blog-post-img">
                                    <img src="{{ asset($blogArticle->image) }}" alt="{{ $blogArticle->title }}"
                                        title="{{ $blogArticle->title }}" />
                                    <a href="{{ route('blog.category', $blogArticle->blogCategory->slug) }}"
                                        class="blog-post-cate">{{ $blogArticle->blogCategory->name }}</a>
                                </div>
                                <div class="card-body p-4">
                                    <a class="card-title h5 mb-2 d-block text-secondary"
                                        href="{{ route('blog.article', $blogArticle->slug) }}">{{ $blogArticle->title }}</a>
                                    <div class="small mb-3">
                                        <i class="far fa-calendar-alt text-secondary me-1 fs-6"></i>
                                        <span class="me-2">{{ vDate($blogArticle->created_at) }}</span>
                                        <i class="far fa-user text-secondary me-1 fs-6"></i>
                                        <span
                                            class="me-2">{{ $blogArticle->admin->firstname . ' ' . $blogArticle->admin->lastname }}</span>
                                        <i class="far fa-comments text-secondary me-1 fs-6"></i>
                                        <span>{{ $blogArticle->comments_count }}</span>
                                    </div>
                                    <p class="card-text text-muted">
                                        {{ shortertext($blogArticle->short_description, 150) }}
                                    </p>
                                    <div class="d-flex">
                                        <a href="{{ route('blog.article', $blogArticle->slug) }}"
                                            class="btn btn-secondary hvr-radial-out">{{ lang('Read More', 'blog') }}</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="card p-0 shadow-sm border-0 text-center p-5 text-muted">
                            <h3>{{ lang('No data found', 'blog') }}</h3>
                            <p class="mb-0">
                                {{ lang('It looks like there is no articles or your search did not return any results', 'blog') }}
                            </p>
                        </div>
                    @endforelse
                    @if (!request()->input('q') && !is_null(request()->input('q')))
                        {{ $blogArticles->links() }}
                    @endif
                </div>
                @include('frontend.includes.blogSidebar')
            </div>
        </div>
    </div>
@endsection
