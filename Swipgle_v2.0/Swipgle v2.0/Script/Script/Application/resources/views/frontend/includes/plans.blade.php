<div class="plans-monthly">
    @if (count($monthlyPlans) > 0)
        <div class="row row-cols-1 row-cols-sm-2  row-cols-xxl-4 g-3 justify-content-center">
            @foreach ($monthlyPlans as $monthlyPlan)
                <div class="col">
                    <div class="plan {{ planClass($monthlyPlan->id) }}" data-aos="zoom-out-right"
                        data-aos-duration="1000">
                        {!! planBadge($monthlyPlan->id) !!}
                        <div class="plan-header">
                            <p class="plan-title">{{ $monthlyPlan->name }}</p>
                            <div class="plan-price">
                                @if ($monthlyPlan->price != 0)
                                    <span class="currency">{{ currencySymbol() }}</span>
                                    <span class="number">{{ price($monthlyPlan->price) }}</span>
                                    <span class="duration">/
                                        {{ !$monthlyPlan->interval ? lang('month', 'plans') : lang('year', 'plans') }}
                                    </span>
                                @else
                                    <span class="text">{{ lang('free') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="plan-feats">
                            <div class="plan-feat">
                                <i class="fa fa-check"></i>
                                @if ($monthlyPlan->storage_space)
                                    <span
                                        class="f-bold">{{ formatBytes($monthlyPlan->storage_space) }}</span>&nbsp;{{ lang('Storage Space', 'plans') }}
                                @else
                                    <span
                                        class="f-bold">{{ lang('Unlimited', 'plans') }}</span>&nbsp;{{ lang('Storage Space', 'plans') }}
                                @endif
                            </div>
                            <div class="plan-feat">
                                <i class="fa fa-check"></i>
                                @if ($monthlyPlan->transfer_size)
                                    <span
                                        class="f-bold">{{ formatBytes($monthlyPlan->transfer_size) }}</span>&nbsp;{{ lang('Size per transfer', 'plans') }}
                                @else
                                    <span
                                        class="f-bold">{{ lang('Unlimited', 'plans') }}</span>&nbsp;{{ lang('Size per transfer', 'plans') }}
                                @endif
                            </div>
                            <div class="plan-feat">
                                <i class="fa fa-check"></i>
                                @if ($monthlyPlan->transfer_interval)
                                    {{ lang('Files available for', 'plans') }}&nbsp;<span
                                        class="f-bold">{{ $monthlyPlan->transfer_interval }}&nbsp;{{ $monthlyPlan->transfer_interval > 1 ? lang('days') : lang('day') }}</span>
                                @else
                                    {{ lang('Files available for', 'plans') }}&nbsp;<span
                                        class="f-bold">{{ lang('Unlimited time', 'plans') }}</span>
                                @endif
                            </div>
                            @if ($monthlyPlan->transfer_password)
                                <div class="plan-feat">
                                    <i class="fa fa-check"></i>
                                    {{ lang('Password protection', 'plans') }}
                                </div>
                            @endif
                            @if ($monthlyPlan->transfer_notify)
                                <div class="plan-feat">
                                    <i class="fa fa-check"></i>
                                    {{ lang('Email notification', 'plans') }}
                                </div>
                            @endif
                            @if ($monthlyPlan->transfer_expiry)
                                <div class="plan-feat">
                                    <i class="fa fa-check"></i>
                                    {{ lang('Expiry time control', 'plans') }}
                                </div>
                            @endif
                            @if ($monthlyPlan->transfer_link)
                                <div class="plan-feat">
                                    <i class="fa fa-check"></i>
                                    {{ lang('Generate transfer links', 'plans') }}
                                </div>
                            @endif
                            @if ($monthlyPlan->custom_features)
                                @foreach ($monthlyPlan->custom_features as $custom_feature)
                                    <div class="plan-feat">
                                        <i class="fa fa-check"></i>
                                        {{ $custom_feature->name }}
                                    </div>
                                @endforeach
                            @endif
                        </div>
                        {!! planButton($monthlyPlan) !!}
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="row">
            <div class="col-lg-6 m-auto">
                <div class="alert alert-primary">{{ lang('No monthly plans available', 'plans') }}</div>
            </div>
        </div>
    @endif
</div>
<div class="plans-yearly">
    @if (count($yearlyPlans) > 0)
        <div class="row row-cols-1 row-cols-sm-2 row-cols-xl-3 row-cols-xxl-4 g-3 justify-content-center">
            @foreach ($yearlyPlans as $yearlyPlan)
                <div class="col">
                    <div class="plan {{ planClass($yearlyPlan->id) }}" data-aos="zoom-out-right"
                        data-aos-duration="1000">
                        {!! planBadge($yearlyPlan->id) !!}
                        <div class="plan-header">
                            <p class="plan-title">{{ $yearlyPlan->name }}</p>
                            <div class="plan-price">
                                @if ($yearlyPlan->price != 0)
                                    <span class="currency">{{ currencySymbol() }}</span>
                                    <span class="number">{{ price($yearlyPlan->price) }}</span>
                                    <span class="duration">/
                                        {{ !$yearlyPlan->interval ? lang('month', 'plans') : lang('year', 'plans') }}
                                    </span>
                                @else
                                    <span class="text">{{ lang('free') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="plan-feats">
                            <div class="plan-feat">
                                <i class="fa fa-check"></i>
                                @if ($yearlyPlan->storage_space)
                                    <span
                                        class="f-bold">{{ formatBytes($yearlyPlan->storage_space) }}</span>&nbsp;{{ lang('Storage Space', 'plans') }}
                                @else
                                    <span
                                        class="f-bold">{{ lang('Unlimited', 'plans') }}</span>&nbsp;{{ lang('Storage Space', 'plans') }}
                                @endif
                            </div>
                            <div class="plan-feat">
                                <i class="fa fa-check"></i>
                                @if ($yearlyPlan->transfer_size)
                                    <span
                                        class="f-bold">{{ formatBytes($yearlyPlan->transfer_size) }}</span>&nbsp;{{ lang('Size per transfer', 'plans') }}
                                @else
                                    <span
                                        class="f-bold">{{ lang('Unlimited', 'plans') }}</span>&nbsp;{{ lang('Size per transfer', 'plans') }}
                                @endif
                            </div>
                            <div class="plan-feat">
                                <i class="fa fa-check"></i>
                                @if ($yearlyPlan->transfer_interval)
                                    {{ lang('Files available for', 'plans') }}&nbsp;<span
                                        class="f-bold">{{ $yearlyPlan->transfer_interval }}&nbsp;{{ $yearlyPlan->transfer_interval > 1 ? lang('days') : lang('day') }}</span>
                                @else
                                    {{ lang('Files available for', 'plans') }}&nbsp;<span
                                        class="f-bold">{{ lang('Unlimited time', 'plans') }}</span>
                                @endif
                            </div>
                            @if ($yearlyPlan->transfer_password)
                                <div class="plan-feat">
                                    <i class="fa fa-check"></i>
                                    {{ lang('Password protection', 'plans') }}
                                </div>
                            @endif
                            @if ($yearlyPlan->transfer_notify)
                                <div class="plan-feat">
                                    <i class="fa fa-check"></i>
                                    {{ lang('Email notification', 'plans') }}
                                </div>
                            @endif
                            @if ($yearlyPlan->transfer_expiry)
                                <div class="plan-feat">
                                    <i class="fa fa-check"></i>
                                    {{ lang('Expiry time control', 'plans') }}
                                </div>
                            @endif
                            @if ($yearlyPlan->transfer_link)
                                <div class="plan-feat">
                                    <i class="fa fa-check"></i>
                                    {{ lang('Generate transfer links', 'plans') }}
                                </div>
                            @endif
                            @if ($yearlyPlan->custom_features)
                                @foreach ($yearlyPlan->custom_features as $custom_feature)
                                    <div class="plan-feat">
                                        <i class="fa fa-check"></i>
                                        {{ $custom_feature->name }}
                                    </div>
                                @endforeach
                            @endif
                        </div>
                        {!! planButton($yearlyPlan) !!}
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="row">
            <div class="col-lg-6 m-auto">
                <div class="alert alert-primary">{{ lang('No yearly plans available', 'plans') }}</div>
            </div>
        </div>
    @endif
</div>
