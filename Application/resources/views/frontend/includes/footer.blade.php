<footer>
    <div class="container">
        <div class="row justify-content-between align-items-center g-3">
            @if (count($footerMenuLinks) > 0)
                <div class="col-auto">
                    <div class="footer-links">
                        @foreach ($footerMenuLinks as $footerMenuLink)
                            <div class="footer-link">
                                <a href="{{ $footerMenuLink->link }}"
                                    class="link">{{ $footerMenuLink->name }}</a>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
            <div class="col-auto">
                <div class="copyright">
                    <p class="mb-0">&copy; <span data-year></span> {{ $settings['website_name'] }} -
                        {{ lang('All rights reserved') }}.</p>
                </div>
            </div>
        </div>
    </div>
</footer>
