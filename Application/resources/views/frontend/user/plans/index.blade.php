@extends('frontend.user.layouts.auth')
@section('title', lang('Pricing plans'))
@section('bg', 'bg-light')
@section('content')
    <div class="plans plans-page">
        <div class="plans-header text-center mt-5 mb-4">
            @if (request()->input('st') == 'subscribe')
                <i class="fas fa-check-circle"></i>
                <h2>{{ lang('Choose your plan to complete the subscription', 'user') }}</h2>
            @else
                <i class="far fa-gem"></i>
                <h2>{{ lang('Pricing plans') }}</h2>
            @endif
        </div>
        <div class="d-flex justify-content-center mb-4">
            <div class="plan-switcher">
                <span>{{ lang('Monthly', 'plans') }}</span>
                <span>{{ lang('Yearly', 'plans') }}</span>
            </div>
        </div>
        @include('frontend.includes.plans')
    </div>
@endsection
