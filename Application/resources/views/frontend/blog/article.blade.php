@extends('frontend.layouts.pages')
@section('title', $blogArticle->title)
@section('description', $blogArticle->short_description)
@section('content')
    <div class="container">
        <div class="blog">
            <div class="row g-4">
                <div class="col-12 col-lg-8">
                    <div class="blog-post">
                        <div class="card p-0 shadow-sm border-0">
                            <div class="blog-post-img blog-single-post-img">
                                <img src="{{ asset($blogArticle->image) }}" alt="{{ $blogArticle->title }}"
                                    title="{{ $blogArticle->title }}" />
                            </div>
                            <div class="card-body p-4 pb-0">
                                <a class="card-title h5 mb-2 d-block"
                                    href="{{ route('blog.category', $blogArticle->blogCategory->slug) }}">
                                    {{ $blogArticle->title }}
                                </a>
                                <div class="small mb-4">
                                    <i class="far fa-calendar-alt text-secondary me-1 fs-6"></i>
                                    <span class="me-2">{{ vDate($blogArticle->created_at) }}</span>
                                    <i class="far fa-user text-secondary me-1 fs-6"></i>
                                    <span
                                        class="me-2">{{ $blogArticle->admin->firstname . ' ' . $blogArticle->admin->lastname }}</span>
                                </div>
                                {!! $blogArticle->content !!}
                            </div>
                            <div class="blog-post-comments p-4 mt-3">
                                <h5 class="mb-4">
                                    <i class="far fa-comments me-1"></i>
                                    {{ lang('Comments', 'blog') }} ({{ count($blogArticleComments) }})
                                </h5>
                                @forelse ($blogArticleComments as $blogArticleComment)
                                    <div class="blog-post-comment">
                                        <div class="blog-post-comment-avatar">
                                            <img src="{{ asset($blogArticleComment->user->avatar) }}"
                                                alt="{{ $blogArticleComment->user->firstname . ' ' . $blogArticleComment->user->lastname }}" />
                                        </div>
                                        <div class="blog-post-comment-info">
                                            <p class="blog-post-comment-title h6">
                                                {{ $blogArticleComment->user->firstname . ' ' . $blogArticleComment->user->lastname }}
                                            </p>
                                            <p class="blog-post-comment-text text-muted small mt-1 mb-2">
                                                <i class="far fa-calendar-alt me-1 text-secondary fs-6"></i>
                                                {{ vDate($blogArticleComment->created_at) }}
                                            </p>
                                            <p class="blog-post-comment-text mb-0">
                                                {!! allowBr($blogArticleComment->comment) !!}
                                            </p>
                                        </div>
                                    </div>
                                @empty
                                    <div class="alert mb-0 p-1">
                                        {{ lang('No comments available', 'blog') }}
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                    <div class="blog-card shadow-sm">
                        @auth
                            <div class="blog-card-header">
                                <p class="blog-card-title h5">{{ lang('Leave a comment', 'blog') }}</p>
                            </div>
                            <div class="blog-card-body mt-3">
                                <form action="{{ route('blog.article.comment', $blogArticle->slug) }}" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="form-label">{{ lang('Your comment', 'blog') }} : <span
                                                class="red">*</span></label>
                                        <textarea name="comment" class="form-control" rows="6" required></textarea>
                                    </div>
                                    {!! display_captcha() !!}
                                    <button class="btn btn-secondary px-5">{{ lang('Publish', 'blog') }}</button>
                                </form>
                            </div>
                        @else
                            <div class="alert mb-0 text-center p-1">
                                {{ lang('Login or create account to leave comments', 'blog') }}
                            </div>
                        @endauth
                    </div>
                </div>
                @include('frontend.includes.blogSidebar')
            </div>
        </div>
    </div>
    @push('scripts')
        {!! google_captcha() !!}
    @endpush
@endsection
