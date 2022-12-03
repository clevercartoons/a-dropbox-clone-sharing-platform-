@extends('frontend.layouts.pages')
@section('title', lang('Faq'))
@section('content')
    <div class="container">
        <div class="faq bg-light p-0">
            <div class="accordion accordion-flush" id="accordionFlush">
                @foreach ($faqs as $faq)
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="flush-heading{{ hashid($faq->id) }}">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#flush-collapse{{ hashid($faq->id) }}" aria-expanded="false"
                                aria-controls="flush-collapseOne">
                                {{ $faq->title }}
                            </button>
                        </h2>
                        <div id="flush-collapse{{ hashid($faq->id) }}" class="accordion-collapse collapse"
                            aria-labelledby="flush-heading{{ hashid($faq->id) }}" data-bs-parent="#accordionFlush">
                            <div class="accordion-body">
                                <div class="mb-0">{!! $faq->content !!}</div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            {{ $faqs->links() }}
        </div>
    </div>
@endsection
