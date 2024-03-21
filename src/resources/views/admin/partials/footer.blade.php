{{--
<div class="footer">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <div class="row">
                    <div class="col-6 col-md-3">
                        <ul class="list-unstyled mb-0">
                            <li><a href="#">First link</a></li>
                            <li><a href="#">Second link</a></li>
                        </ul>
                    </div>
                    <div class="col-6 col-md-3">
                        <ul class="list-unstyled mb-0">
                            <li><a href="#">Third link</a></li>
                            <li><a href="#">Fourth link</a></li>
                        </ul>
                    </div>
                    <div class="col-6 col-md-3">
                        <ul class="list-unstyled mb-0">
                            <li><a href="#">Fifth link</a></li>
                            <li><a href="#">Sixth link</a></li>
                        </ul>
                    </div>
                    <div class="col-6 col-md-3">
                        <ul class="list-unstyled mb-0">
                            <li><a href="#">Other link</a></li>
                            <li><a href="#">Last link</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 mt-4 mt-lg-0">
                Footer message
            </div>
        </div>
    </div>
</div>
--}}
<footer class="footer">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-12 col-lg-auto mt-3 mt-lg-0 text-center">
                {!! __('admin.footer.copy-right', ['year' => date('Y')]) !!}
            </div>
            <div class="col-auto ml-lg-auto">
                <ul class="list-inline list-inline-dots mb-0">
                    <li class="list-inline-item"><a target="_blank" href="mailto:{{ config('settings.support-email') }}?Subject=Need Help">{{ __('admin.footer.need-help') }}</a></li>
                </ul>
            </div>
        </div>
    </div>
</footer>
