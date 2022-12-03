@extends('backend.layouts.grid')
@section('title', __('Transaction | #') . $transaction->transaction_id)
@section('container', 'container-max-lg')
@section('back', route('admin.transactions.index'))
@section('content')
    @if ($transaction->status == 2)
        <div class="alert alert-danger">
            <p class="mb-0"><strong>{{ __('Transaction has been canceled') }}</strong></p>
            @if ($transaction->cancellation_reason)
                <p class="mb-0 mt-1"><i
                        class="fas fa-quote-left me-2"></i><i>{{ $transaction->cancellation_reason }}</i></p>
            @endif
        </div>
    @endif
    <div class="card custom-card">
        <div class="card-header bg-primary text-white">
            {{ __('Transaction details') }}
        </div>
        <ul class="custom-list-group list-group list-group-flush">
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <span><strong>{{ __('Transaction Number') }}</strong></span>
                <span>#{{ $transaction->transaction_id }}</span>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <span><strong>{{ __('User') }}</strong></span>
                <span><a href="{{ route('admin.users.edit', $transaction->user->id) }}" class="text-dark"><i
                            class="fa fa-user me-2"></i>
                        {{ $transaction->user->firstname . ' ' . $transaction->user->lastname }}
                    </a></span>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <span><strong>{{ __('Plan Name') }}</strong></span>
                <span><a href="{{ route('admin.plans.edit', $transaction->plan->id) }}"
                        style="color: {{ $transaction->plan->color }}"><i class="far fa-gem me-2"></i>
                        {{ $transaction->plan->name }}
                    </a></span>
            </li>
            @if ($transaction->gateway)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <span><strong>{{ __('Payment Gateway') }}</strong></span>
                    <span><a href="{{ route('admin.settings.gateways.edit', $transaction->gateway->id) }}"
                            class="text-dark"><i
                                class="fas fa-external-link-alt me-2"></i>{{ $transaction->gateway->name }}</a></span>
                </li>
            @endif
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <span><strong>{{ __('Plan Price') }}</strong></span>
                <span><strong>{{ priceSymbol($transaction->plan_price) }}</strong></span>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <span><strong>{{ __('Taxes') }}</strong></span>
                <span><strong>{{ priceSymbol($transaction->tax_price) }}</strong></span>
            </li>
            @if ($transaction->gateway)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <span><strong>{{ __('Gateway Fees') }}</strong></span>
                    <span><strong>{{ priceSymbol($transaction->fees_price) }}</strong></span>
                </li>
            @endif
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <span><strong>{{ __('Total Price') }}</strong></span>
                <span><strong>{{ priceSymbol($transaction->total_price) }}</strong></span>
            </li>
            @if ($transaction->payment_id)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <span><strong>{{ __('Payment ID') }}</strong></span>
                    <span>{{ $transaction->payment_id }}</span>
                </li>
            @endif
            @if ($transaction->payer_id)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <span><strong>{{ __('Payer ID') }}</strong></span>
                    <span>{{ $transaction->payer_id }}</span>
                </li>
            @endif
            @if ($transaction->payer_email)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <span><strong>{{ __('Payer Email') }}</strong></span>
                    <span>{{ $transaction->payer_email }}</span>
                </li>
            @endif
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <span><strong>{{ __('Transaction Type') }}</strong></span>
                <span>
                    @if ($transaction->type == 0)
                        <span class="badge bg-lg-1">{{ __('Subscribe') }}</span>
                    @elseif($transaction->type == 1)
                        <span class="badge bg-lg-2">{{ __('Renew') }}</span>
                    @elseif($transaction->type == 2)
                        <span class="badge bg-lg-9">{{ __('Upgrade') }}</span>
                    @endif
                </span>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <span><strong>{{ __('Transaction Status') }}</strong></span>
                <span>
                    @if ($transaction->plan_price != 0)
                        @if ($transaction->status == 1)
                            <span class="badge bg-success">{{ __('Paid') }}</span>
                        @elseif($transaction->status == 2)
                            <span class="badge bg-danger">{{ __('Canceled') }}</span>
                        @endif
                    @else
                        @if ($transaction->status == 1)
                            <span class="badge bg-success">{{ __('Done') }}</span>
                        @elseif($transaction->status == 2)
                            <span class="badge bg-danger">{{ __('Canceled') }}</span>
                        @endif
                    @endif
                </span>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <span><strong>{{ __('Transaction date') }}</strong></span>
                <span><strong>{{ vDate($transaction->created_at) }}</strong></span>
            </li>
        </ul>
    </div>
    @if ($transaction->status == 1)
        <div class="modal fade" id="cancelTransaction" tabindex="-1" aria-labelledby="cancelTransactionLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="cancelTransactionLabel">{{ __('Cancel Transaction') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('admin.transactions.update', $transaction->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label class="form-label">{{ __('Cancellation Reason') }} : <span
                                        class="red">*</span></label>
                                <textarea name="cancellation_reason" rows="6" class="form-control" placeholder="Max 150 character"
                                    required></textarea>
                            </div>
                            <button class="vironeer-form-confirm btn btn-danger">{{ __('Cancel') }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection
