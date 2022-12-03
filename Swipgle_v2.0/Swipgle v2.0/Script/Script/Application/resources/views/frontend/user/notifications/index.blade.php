@extends('frontend.user.layouts.dash')
@section('title', lang('Notifications', 'user') . ' (' . $unreadNotificationsCount . ')')
@section('content')
    <div class="vr__notifications">
        @forelse ($notifications as $notification)
            @if ($notification->link)
                <a href="{{ route('user.notifications.view', hashid($notification->id)) }}"
                    class="vr__notification__item d-flex justify-content-between align-items-center">
                @else
                    <div class="vr__notification__item d-flex justify-content-between align-items-center">
            @endif
            <div class="flex-shrink-0">
                <img class="rounded-2" src="{{ $notification->image }}" width="60" height="60">
            </div>
            <div class="flex-grow-1 ms-3">
                <h5 class="mb-1">{{ $notification->title }}</h5>
                <p class="mb-0 text-muted">{{ $notification->created_at->diffforhumans() }}</p>
            </div>
            @if (!$notification->status)
                <div class="flex-grow-2 ms-3">
                    <span class="icon text-danger flashing"><i class="fas fa-circle"></i></span>
                </div>
            @endif
            @if ($notification->link)
                </a>
            @else
    </div>
    @endif
@empty
    @include('frontend.user.includes.empty')
    @endforelse
    </div>
    {{ $notifications->links() }}
@endsection
