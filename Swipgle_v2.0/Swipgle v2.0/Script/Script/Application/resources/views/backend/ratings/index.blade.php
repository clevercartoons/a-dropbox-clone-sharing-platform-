@extends('backend.layouts.grid')
@section('title', __('Ratings'))
@section('container', 'container-max-lg')
@section('content')
    <div class="row mb-4">
        <div class="col-lg-12">
            <div class="vironeer-counter-box bg-amazon">
                <h3 class="vironeer-counter-box-title">{{ __('Average user rating') }}</h3>
                <p class="vironeer-counter-box-number ratings">
                    @php $avgRating = $ratings->avg('stars'); @endphp
                    @foreach (range(1, 5) as $i)
                        <span class="fa-stack">
                            <i class="far fa-star fa-stack-1x"></i>
                            @if ($avgRating > 0)
                                @if ($avgRating > 0.5)
                                    <i class="fas fa-star fa-stack-1x"></i>
                                @else
                                    <i class="fas fa-star-half fa-stack-1x"></i>
                                @endif
                            @endif
                            @php $avgRating--; @endphp
                        </span>
                    @endforeach
                    ({{ number_format($ratings->avg('stars'), 2) }})
                </p>
                <span class="vironeer-counter-box-icon">
                    <i class="far fa-star"></i>
                </span>
            </div>
        </div>
    </div>
    <div class="card ratings custom-card">
        <table id="datatable" class="table w-100">
            <thead>
                <tr>
                    <th class="tb-w-2x">{{ __('#') }}</th>
                    <th class="tb-w-3x">{{ __('Ip') }}</th>
                    <th class="tb-w-7x">{{ __('Rating') }}</th>
                    <th class="tb-w-3x">{{ __('Rated at') }}</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($ratings as $rating)
                    <tr>
                        <td>{{ $rating->id }}</td>
                        <td><a href="{{ route('admin.users.logsbyip', $rating->ip) }}"><i
                                    class="fas fa-map-marker-alt me-2"></i>{{ $rating->ip }}</a></td>
                        <td>
                            @php
                                $rate = $rating->stars;
                            @endphp
                            @foreach (range(1, 5) as $i)
                                <span class="fa-stack text-warning">
                                    <i class="far fa-star fa-stack-1x"></i>
                                    @if ($rate > 0)
                                        @if ($rate > 0.5)
                                            <i class="fas fa-star fa-stack-1x"></i>
                                        @else
                                            <i class="fas fa-star-half fa-stack-1x"></i>
                                        @endif
                                    @endif
                                    @php
                                        $rate--;
                                    @endphp
                                </span>
                            @endforeach
                            ({{ $rating->stars }})
                        </td>
                        <td>{{ vDate($rating->created_at) }}</td>
                        <td>
                            <div class="text-end">
                                <button type="button" class="btn btn-sm rounded-3" data-bs-toggle="dropdown"
                                    aria-expanded="true">
                                    <i class="fa fa-ellipsis-v fa-sm text-muted"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-sm-end" data-popper-placement="bottom-end">
                                    <li>
                                        <form action="{{ route('admin.ratings.destroy', $rating->id) }}" method="POST">
                                            @csrf @method('DELETE')
                                            <button class="vironeer-able-to-delete dropdown-item text-danger"><i
                                                    class="far fa-trash-alt me-2"></i>{{ __('Delete') }}</button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
