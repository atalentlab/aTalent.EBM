@if(session()->has('success'))
    <div class="alert alert-icon alert-success" role="alert">
        <i class="fe fe-check mr-2" aria-hidden="true"></i> {!! session('success') !!}
    </div>
@endif

@if(session()->has('warning'))
    <div class="alert alert-icon alert-warning" role="alert">
        <i class="fe fe-alert-triangle mr-2" aria-hidden="true"></i> {!! session('warning') !!}
    </div>
@endif

@if(session()->has('error'))
    <div class="alert alert-icon alert-danger" role="alert">
        <i class="fe fe-alert-octagon mr-2" aria-hidden="true"></i> {!! session('error') !!}
    </div>
@endif

