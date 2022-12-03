<div class="page-content">
    <div id="drag-box">
        <div class="drag-box-inner">
            <i class="fas fa-file-export fa-4x"></i>
            <h2 class="my-4">{{ lang('Drop File Here', 'upload zone') }}</h2>
            <h3>{{ lang('Add your files or folders by drag-and-dropping them on this window 😉', 'upload zone') }}
            </h3>
        </div>
    </div>
    <div class="upload-box">
        <div id="upload-btn">
            <h1 class="upload-box-title">{{ lang('Send large files fast, easy and secure', 'upload zone') }}</h1>
            <p class="upload-box-text">
                {{ lang('Transfer your images, videos and heavy documents of up to 20 GB* per transfer', 'upload zone') }}
            </p>
            <div class="upload-box-button" data-upload-btn>
                <div class="upload-box-button-spinner"></div>
                <div class="upload-box-button-spinner2"></div>
                <div class="upload-box-button-inner">
                    <div class="upload-box-button-inner-text">
                        <span>{{ lang('Start', 'upload zone') }}</span>
                    </div>
                    <div class="upload-box-button-inner-icon">
                        <i class="fas fa-cloud-upload-alt fa-4x"></i>
                    </div>
                </div>
            </div>
            <div class="upload-box-button-tooltip mt-5 d-none d-lg-block">
                <p class="upload-box-button-tooltip-text mb-0">
                    {{ lang('Click or drag and drop your files here', 'upload zone') }}</p>
                <div class="upload-box-button-tooltip-btn"><span
                        data-upload-btn>{{ lang('Or Click here', 'upload zone') }}</span></div>
            </div>
            <div class="counter">
                <div class="row align-items-center g-3">
                    <div class="col-sm-4 col-lg-3">
                        <div class="counter-item">
                            <div class="counter-item-text">{{ number_format($statistics_download_files) }}</div>
                            <div class="counter-item-title">
                                {{ lang('Downloaded files', 'upload zone') }}
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4 col-lg-3">
                        <div class="counter-item">
                            <div class="counter-item-text">{{ number_format($statistics_send_files) }}</div>
                            <div class="counter-item-title">
                                {{ lang('Send files', 'upload zone') }}
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4 col-lg-3">
                        <div class="counter-item">
                            <div class="counter-item-text">{{ number_format($statistics_total_transfers) }}</div>
                            <div class="counter-item-title">
                                {{ lang('Total transfers', 'upload zone') }}
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12 col-lg-3">
                        <div class="counter-item">
                            <div class="counter-item-text d-flex justify-content-center align-items-center">
                                <span class="me-2">{{ number_format($ratings->avg('stars'), 2) }}</span>
                                <div class="stars">
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
                                </div>
                            </div>
                            <div class="counter-item-title">
                                {{ lang('Average user rating', 'upload zone') }}
                                <small class="text-muted d-block">({{ number_format(count($ratings)) }}
                                    {{ count($ratings) != 1 ? lang('votes', 'upload zone') : lang('vote', 'upload zone') }})</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="upload-box" class="upload-form">
            @if (!subscription()->is_subscribed)
                <div class="alert bg-danger text-white border-0">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>{{ lang('Login or create account to start transferring files', 'alerts') }}</strong>
                </div>
            @endif
            @if (subscription()->is_canceled)
                <div class="alert bg-danger text-white border-0">
                    <i class="far fa-times-circle me-2"></i>
                    <strong>{{ lang('Your subscription has been canceled, please contact us for more information', 'alerts') }}</strong>
                </div>
            @endif
            @if (subscription()->is_expired && !subscription()->is_canceled)
                <div class="alert bg-danger text-white border-0">
                    <i class="fas fa-stopwatch me-2"></i>
                    <strong>{{ lang('Your subscription has been expired, Please renew it to continue using the service.', 'user') }}</strong>
                </div>
            @endif
            @if (subscription()->remining_days < 6 && !subscription()->is_expired && !subscription()->is_canceled)
                <div class="alert bg-warning text-dark border-0">
                    <i class="fas fa-stopwatch me-2"></i>
                    <strong>{{ lang('Your subscription is about expired, Renew it to avoid deleting your files.', 'user') }}</strong>
                </div>
            @endif
            <div class="upload-form-content">
                <div class="upload-user">
                    <div class="upload-user-cont">
                        <div class="upload-user-header">
                            <div class="upload-user-header-button active" data-bs-toggle="tooltip"
                                data-bs-placement="top"
                                data-bs-original-title="{{ lang('Transfer files to one or more recipients', 'upload zone') }}">
                                {{ lang('Send files', 'upload zone') }}
                            </div>
                            <div class="upload-user-header-button" data-bs-toggle="tooltip" data-bs-placement="top"
                                data-bs-original-title="{{ lang('Upload files and get download link', 'upload zone') }}"
                                {{ subscription()->plan->transfer_link ? '' : 'disabled' }}>
                                {{ lang('Create a link', 'upload zone') }}
                                @if (!subscription()->plan->transfer_link)
                                    <span class="pro"
                                        style="background: {{ featureFirstPlanDetails('transfer_link')->color }};">{{ featureFirstPlanDetails('transfer_link')->name }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="upload-user-body">
                            <div class="upload-user-body-item active">
                                <form id="send-files" class="transfer-files-form">
                                    <div class="form-floating form-group mb-3">
                                        <input type="email" name="sender_email" class="form-control"
                                            placeholder="{{ lang('Your Email Address', 'upload zone') }}"
                                            value="{{ userAuthInfo()->email ?? '' }}">
                                        <label>{{ lang('Your Email Address', 'upload zone') }}</label>
                                        <div class="form-icon" data-bs-toggle="tooltip" data-bs-placement="right"
                                            data-bs-original-title="{{ lang('Enter your email address to track your transfer.', 'upload zone') }}">
                                            <i class="fas fa-question fa-lg"></i>
                                        </div>
                                    </div>
                                    <div class="form-floating form-group mb-3">
                                        <input type="text" name="sender_name" class="form-control"
                                            placeholder="{{ lang('Sender name (optional)', 'upload zone') }}"
                                            value="{{ userAuthInfo()->name ?? '' }}">
                                        <label>{{ lang('Sender name (optional)', 'upload zone') }}</label>
                                        <div class="form-icon" data-bs-toggle="tooltip" data-bs-placement="right"
                                            data-bs-original-title="{{ lang('You can provide a sender name to your contacts so they can identify you.', 'upload zone') }}">
                                            <i class="fas fa-question fa-lg"></i>
                                        </div>
                                    </div>
                                    <div class="form-floating form-group mb-3">
                                        <input type="text" name="send_to" class="form-control" id="input-tags"
                                            placeholder="{{ lang('Send to', 'upload zone') }}">
                                        <div class="form-icon" data-bs-toggle="tooltip" data-bs-placement="right"
                                            data-bs-original-title="{{ lang('Type email and press enter', 'upload zone') }}">
                                            <i class="fas fa-question fa-lg"></i>
                                        </div>
                                    </div>
                                    <div class="form-floating form-group mb-3">
                                        <input type="text" name="subject" class="form-control"
                                            placeholder="{{ lang('Subject (optional)', 'upload zone') }}">
                                        <label>{{ lang('Subject (optional)', 'upload zone') }}</label>
                                        <div class="form-icon" data-bs-toggle="tooltip" data-bs-placement="right"
                                            data-bs-original-title="{{ lang('You can enter a subject to name your transfer and make it more easily identifiable.', 'upload zone') }}">
                                            <i class="fas fa-question fa-lg"></i>
                                        </div>
                                    </div>
                                    <div class="form-floating">
                                        <textarea type="text" name="message" class="form-control textarea"
                                            placeholder="{{ lang('Your message (optional)', 'upload zone') }}"
                                            autosize></textarea>
                                        <label>{{ lang('Your message (optional)', 'upload zone') }}</label>
                                    </div>
                                </form>
                            </div>
                            <div class="upload-user-body-item">
                                <form id="create-link" class="transfer-files-form">
                                    @if (subscription()->plan->transfer_link)
                                        <div class="form-floating form-group mb-3">
                                            <input type="email" name="sender_email" class="form-control"
                                                placeholder="{{ lang('Your Email Address', 'upload zone') }}"
                                                value="{{ userAuthInfo()->email ?? '' }}">
                                            <label>{{ lang('Your Email Address', 'upload zone') }}</label>
                                            <div class="form-icon" data-bs-toggle="tooltip"
                                                data-bs-placement="right"
                                                data-bs-original-title="{{ lang('Enter your email address to track your transfer.', 'upload zone') }}">
                                                <i class="fas fa-question fa-lg"></i>
                                            </div>
                                        </div>
                                        <div class="form-floating form-group mb-3">
                                            <input type="text" name="subject" class="form-control"
                                                placeholder="{{ lang('Subject (optional)', 'upload zone') }}">
                                            <label>{{ lang('Subject (optional)', 'upload zone') }}</label>
                                            <div class="form-icon" data-bs-toggle="tooltip"
                                                data-bs-placement="right"
                                                data-bs-original-title="{{ lang('You can enter a subject to name your transfer and make it more easily identifiable.', 'upload zone') }}">
                                                <i class="fas fa-question fa-lg"></i>
                                            </div>
                                        </div>
                                        <div class="form-floating form-group">
                                            <input type="text" name="custom_link" class="form-control"
                                                placeholder="Custom link">
                                            <label>{{ lang('Custom link (optional)', 'upload zone') }}</label>
                                            <div class="form-icon" data-bs-toggle="tooltip"
                                                data-bs-placement="right"
                                                data-bs-original-title="{{ lang('Customize your transfer links so your users can identify it more easily.', 'upload zone') }}">
                                                <i class="fas fa-question fa-lg"></i>
                                            </div>
                                        </div>
                                    @endif
                                </form>
                            </div>
                            <div class="upload-user-options-forms">
                                <div class="upload-user-options-form">
                                    <div class="upload-user-options-form-header">
                                        <p class="header-text">{{ lang('Password', 'upload zone') }}
                                            @if (!subscription()->plan->transfer_password)
                                                <span class="pro"
                                                    style="background: {{ featureFirstPlanDetails('transfer_password')->color }};">{{ featureFirstPlanDetails('transfer_password')->name }}</span>
                                            @endif
                                        </p>
                                    </div>
                                    <div class="mb-4">
                                        <div class="form-floating form-button">
                                            <input type="password" name="password"
                                                class="transfer-password form-control file-password show-password"
                                                placeholder="{{ lang('Password', 'upload zone') }}"
                                                autocomplete="off"
                                                {{ subscription()->plan->transfer_password ? '' : 'disabled' }} />
                                            <label>{{ lang('Password', 'upload zone') }}</label>
                                            <button class="btn btn-secondary" data-password="show-password">
                                                <i class="fas fa-eye-slash"></i>
                                                <i class="fas fa-eye d-none"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="upload-user-options-form">
                                    <div class="upload-user-options-form-header">
                                        <p class="header-text">{{ lang('Notifications', 'upload zone') }}
                                            @if (!subscription()->plan->transfer_notify)
                                                <span class="pro"
                                                    style="background: {{ featureFirstPlanDetails('transfer_notify')->color }};">{{ featureFirstPlanDetails('transfer_notify')->name }}</span>
                                            @endif
                                        </p>
                                    </div>
                                    <div class="mb-4">
                                        <div class="form-toggle mb-2">
                                            <input type="checkbox" name="download_notify"
                                                class="download-notify file-noti-download"
                                                {{ subscription()->plan->transfer_notify ? '' : 'disabled' }}>
                                            <div class="toggle-style order-2"></div>
                                            <label
                                                class="order-1">{{ lang('Notify me when downloaded', 'upload zone') }}</label>
                                        </div>
                                        <div class="form-toggle">
                                            <input type="checkbox" name="expiry_notify"
                                                class="expiry-notify file-noti-expiry"
                                                {{ subscription()->plan->transfer_notify ? '' : 'disabled' }}>
                                            <div class="toggle-style order-2"></div>
                                            <label
                                                class="order-1">{{ lang('Notify me when expired', 'upload zone') }}</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="upload-user-options-form full-height">
                                    <div class="upload-user-options-form-header">
                                        <div class="d-flex">
                                            <p class="header-text">{{ lang('Availability', 'upload zone') }}
                                                @if (!subscription()->plan->transfer_expiry)
                                                    <span class="pro"
                                                        style="background: {{ featureFirstPlanDetails('transfer_expiry')->color }};">{{ featureFirstPlanDetails('transfer_expiry')->name }}</span>
                                                @endif
                                            </p>
                                        </div>
                                        @if (subscription()->is_subscribed)
                                            <p class="mb-0 ms-auto">
                                                {{ subscription()->plan->transfer_interval_days }}
                                            </p>
                                        @endif
                                    </div>
                                    <div class="mb-4">
                                        <div class="mb-3">
                                            <label
                                                class="mb-2">{{ lang('Set expiry date', 'upload zone') }}:</label>
                                            <input type="datetime-local" name="expiry_at"
                                                class="transfer-expiry-date form-control file-expiry"
                                                {{ subscription()->plan->transfer_expiry ? '' : 'disabled' }} />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="upload-user-footer">
                            <div class="upload-user-options">
                                <div class="upload-user-option password-option">
                                    <i class="fas fa-lock-open"></i>
                                </div>
                                <div class="upload-user-option">
                                    <i class="far fa-bell"></i>
                                </div>
                                <div class="upload-user-option">
                                    <i class="far fa-calendar-alt"></i>
                                </div>
                            </div>
                            <div class="upload-user-footer-buttons">
                                <div class="upload-user-footer-button active">
                                    <button class="btn btn-secondary btn-xl w-100"
                                        id="transfer-btn">{{ lang('Transfer', 'upload zone') }}</button>
                                </div>
                                <div class="upload-user-footer-button">
                                    <div class="d-flex justify-content-between">
                                        <button class="btn btn-outline-secondary btn-xl"
                                            data-close-options>{{ lang('Ignore', 'upload zone') }}</button>
                                        <button class="btn btn-secondary btn-xl"
                                            data-confirm-options>{{ lang('Validate', 'upload zone') }}</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="upload-form-side">
                    @if (subscription()->is_subscribed)
                        <div class="upload-form-side-cont mb-3">
                            <div class="client-storage-space">
                                <h4>{{ lang('Your storage space', 'user') }}</h4>
                                @if (!is_null(subscription()->plan->storage_space_number))
                                    @php
                                        if (subscription()->storage->used_percentage > 80) {
                                            $bg = 'bg-danger';
                                        } elseif (subscription()->storage->used_percentage > 50 && subscription()->storage->used_percentage < 80) {
                                            $bg = 'bg-warning';
                                        } else {
                                            $bg = 'bg-success';
                                        }
                                    @endphp
                                    <div class="progress">
                                        <div id="client-used-space-progress" class="progress-bar {{ $bg }}"
                                            role="progressbar"
                                            style="width: {{ subscription()->storage->used_percentage }}%"
                                            aria-valuenow="{{ subscription()->storage->used_percentage }}"
                                            aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                @endif
                                <small>
                                    <strong id="client-used-space">
                                        {{ subscription()->storage->used_space }}</strong>
                                    <span class="text-primary">/</span>
                                    <strong>{{ subscription()->plan->storage_space }}</strong>
                                </small>
                            </div>
                        </div>
                    @endif
                    <div class="upload-form-side-cont">
                        <div class="upload-form-side-upper">
                            <div class="upload-form-addfile" data-upload-btn>
                                <i class="fas fa-plus fa-2x"></i> {{ lang('Add more files', 'upload zone') }}
                            </div>
                        </div>
                        <div class="upload-box-files-info">
                            <div class="upload-box-files-text">
                                <strong data-upload-count></strong>
                                <strong id="uploadZone-files">{{ lang('files', 'upload zone') }}</strong>
                                <span data-dz-totalsize></span>
                            </div>
                            <button class="upload-box-files-remove" data-remove-files>
                                <i class="far fa-trash-alt"></i>
                            </button>
                        </div>
                        <div class="upload-box-files">
                            <div class="upload-box-files-form">
                                <div id="upload-previews"></div>
                                <div class="files-preview">
                                    <div id="preview-template">
                                        <div class="dz-preview dz-file-preview well" id="dz-preview-template">
                                            <div class="dz-details">
                                                <div class="dz-error-mark">
                                                    <span>
                                                        <i class="far fa-times-circle"></i>
                                                    </span>
                                                </div>
                                                <div class="dz-success-mark">
                                                    <span>
                                                        <i class="far fa-check-circle"></i>
                                                    </span>
                                                </div>
                                                <div class="dz-filename">
                                                    <span data-dz-name></span>
                                                </div>
                                                <div class="dz-percentage" data-dz-percentage></div>
                                            </div>
                                            <div class="dz-progress">
                                                <span class="dz-upload" data-dz-uploadprogress></span>
                                            </div>
                                            <a class="dz-remove" data-dz-remove></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="uploaded-box" class="uploaded-box">
            <div class="files-uploaded">
                <div class="files-uploaded-img">
                    <img src="{{ asset('images/upload-done.png') }}"
                        alt="{{ lang("It's done!", 'upload zone') }}">
                </div>
                <p class="files-uploaded-title">{{ lang("It's done!", 'upload zone') }}</p>
                <p id="files-uploaded-expiry-text" class="files-uploaded-text">
                    {!! str_replace('{expiry_interval}', '<strong id="transfer-expiry-on"><strong>', lang('Your transfer download link will be available until {expiry_interval}.', 'upload zone')) !!}
                </p>
                <div class="files-uploaded-social">
                    <p class="files-uploaded-text mb-0">
                        {{ lang('You can copy the download link, view the content or share it', 'upload zone') }}
                    </p>
                    <div class="files-uploaded-social-links mt-3">
                        <a href="#" target="_blank" class="facebook"><i class="fab fa-facebook"></i></a>
                        <a href="#" target="_blank" class="twitter"><i class="fab fa-twitter"></i></a>
                        <a href="#" target="_blank" class="linkedin"><i class="fab fa-linkedin"></i></a>
                        <a href="#" target="_blank" class="whatsapp"><i class="fab fa-whatsapp"></i></a>
                    </div>
                </div>
                <div class="files-uploaded-link mt-3">
                    <div class="share-link-form row g-2">
                        <div class="col">
                            <div class="input-group">
                                <input id="input-link" type="text" class="form-control" value="#" readonly>
                                <button id="copy-btn" class="btn btn-outline-secondary"
                                    data-clipboard-target="#input-link">{{ lang('Copy', 'upload zone') }}</button>
                            </div>
                        </div>
                        <div class="col-auto">
                            <a id="open-download-link" href="#" target="_blank"
                                class="btn btn-outline-secondary btn-external-link"><i
                                    class="fas fa-external-link-alt"></i></a>
                        </div>
                    </div>
                </div>
                <div class="files-uploaded-buttons mt-3">
                    <a id="view-taransfer" href="#"
                        class="btn btn-outline-secondary w-100 btn-xl fw-light">{{ lang('View my transfer', 'upload zone') }}</a>
                    <button id="new-transfer"
                        class="btn btn-secondary w-100 btn-xl mt-2">{{ lang('New transfer?', 'upload zone') }}</button>
                </div>
            </div>
            @if (is_null($rating))
                <div id="rating-box" class="files-uploaded">
                    <p class="files-uploaded-title v2 mb-2">
                        {{ str_replace('{website_name}', $settings['website_name'], lang('Rate {website_name} :', 'upload zone')) }}
                    </p>
                    <div class="ratings">
                        <div class="star">
                            <i class="far fa-star fa-lg d-none"></i>
                            <i class="fa fa-star fa-lg"></i>
                            <input type="radio" name="ratings" value="1" hidden />
                        </div>
                        <div class="star">
                            <i class="far fa-star fa-lg d-none"></i>
                            <i class="fa fa-star fa-lg"></i>
                            <input type="radio" name="ratings" value="2" hidden />
                        </div>
                        <div class="star">
                            <i class="far fa-star fa-lg d-none"></i>
                            <i class="fa fa-star fa-lg"></i>
                            <input type="radio" name="ratings" value="3" hidden />
                        </div>
                        <div class="star">
                            <i class="far fa-star fa-lg d-none"></i>
                            <i class="fa fa-star fa-lg"></i>
                            <input type="radio" name="ratings" value="4" hidden />
                        </div>
                        <div class="star">
                            <i class="far fa-star fa-lg d-none"></i>
                            <i class="fa fa-star fa-lg"></i>
                            <input type="radio" name="ratings" value="5" hidden checked />
                        </div>
                    </div>
                    <div class="rating-faces mt-3">
                        <div class="face">
                            <i class="far fa-frown fa-2x"></i>
                        </div>
                        <div class="face">
                            <i class="far fa-meh fa-2x"></i>
                        </div>
                        <div class="face">
                            <i class="far fa-meh-rolling-eyes fa-2x"></i>
                        </div>
                        <div class="face">
                            <i class="far fa-smile fa-2x"></i>
                        </div>
                        <div class="face active">
                            <i class="far fa-smile-beam fa-2x"></i>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@push('config')
    @php
    $directFileToBig = str_replace('{maxFilesize}', '{{maxFilesize}}', lang('file is too big max file size: {maxFilesize}MiB.', 'upload zone'));
    $dictResponseError = str_replace('{statusCode}', '{{statusCode}}', lang('Server responded with {statusCode} code.', 'upload zone'));
    $maxTransferSizeError = str_replace('{maxTransferSize}', subscription()->plan->transfer_size ?? 0, lang('Max size per transfer : {maxTransferSize}.', 'upload zone'));
    $userRemainingStorageSpace = subscription()->is_subscribed ? subscription()->storage->remaining_space_number : 0;
    $maxTransferSize = subscription()->plan->transfer_size_number;
    $subscribed = subscription()->is_subscribed ? 1 : 0;
    $subscriptionExpired = subscription()->is_expired ? 1 : 0;
    $subscriptionCanceled = subscription()->is_canceled ? 1 : 0;
    $unsubscribedError = !is_null(subscription()->plan->id) ? lang('You have no subscription or your subscription has been expired', 'alerts') : lang('Login or create account to start transferring files', 'alerts');
    $subscriptionCanceledError = lang('Your subscription has been canceled, please contact us for more information', 'alerts');
    $transferPassword = subscription()->plan->transfer_password ? 1 : 0;
    $transferNotify = subscription()->plan->transfer_notify ? 1 : 0;
    $transferExpiry = subscription()->plan->transfer_expiry ? 1 : 0;
    @endphp
    <script>
        "use strict";
        const uploadConfig = {
            subscribed: "{{ $subscribed }}",
            subscriptionExpired: "{{ $subscriptionExpired }}",
            subscriptionCanceled: "{{ $subscriptionCanceled }}",
            subscriptionCanceledError: "{{ $subscriptionCanceledError }}",
            unsubscribedError: "{{ $unsubscribedError }}",
            userRemainingStorageSpace: "{{ $userRemainingStorageSpace }}",
            insufficientStorageSpaceError: "{{ lang('Insufficient storage space, please check your space or upgrade your plan', 'alerts') }}",
            maxTransferSize: "{{ $maxTransferSize }}",
            maxTransferSizeError: "{{ $maxTransferSizeError }}",
            singleFile: "{{ lang('file', 'upload zone') }}",
            multipleFiles: "{{ lang('files', 'upload zone') }}",
            transferPassword: "{{ $transferPassword }}",
            transferNotify: "{{ $transferNotify }}",
            transferExpiry: "{{ $transferExpiry }}",
            transferPasswordError: "{{ lang('Setting password feature not available for your subscription', 'upload zone') }}",
            transferNotifyError: "{{ lang('The notify on download and expiry feature not available for your subscription', 'upload zone') }}",
            transferExpiryError: "{{ lang('Setting expiry date feature not available for your subscription', 'upload zone') }}",
        };
        let stringifyUploadConfig = JSON.stringify(uploadConfig),
            getUploadConfig = JSON.parse(stringifyUploadConfig);
    </script>
    <script>
        "use strict";
        const dropzoneOptions = {
            dictDefaultMessage: "{{ lang('Drop files here to upload', 'upload zone') }}",
            dictFallbackMessage: "{{ lang('Your browser does not support drag and drop file uploads.', 'upload zone') }}",
            dictFallbackText: "{{ lang('Please use the fallback form below to upload your files like in the olden days.', 'upload zone') }}",
            dictFileTooBig: "{{ $directFileToBig }}",
            dictInvalidFileType: "{{ lang('You cannot upload files of this type.', 'upload zone') }}",
            dictResponseError: "{{ $dictResponseError }}",
            dictCancelUpload: "{{ lang('Cancel upload', 'upload zone') }}",
            dictCancelUploadConfirmation: "{{ lang('Are you sure you want to cancel this upload?', 'upload zone') }}",
            dictRemoveFile: "{{ lang('Remove file', 'upload zone') }}",
            dictMaxFilesExceeded: "{{ lang('You can not upload any more files.', 'upload zone') }}",
        };
    </script>
@endpush
