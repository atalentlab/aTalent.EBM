<div class="form-group{{ $errors->has($name) || $errors->has($name . '.*') ? ' is-invalid' : '' }}{{ isset($groupClass) ? ' ' . $groupClass : '' }}">
    @if(in_array($type ?? 'text', ['text', 'url', 'email', 'number']))
        @include('admin.partials.form.fields.text', ['type' => ($type ?? 'text')])
    @else
        @include('admin.partials.form.fields.' . ($type ?? 'text'))
    @endif
    @if(isset($help))
        <small class="form-text text-muted"><i class="fe fe-help-circle align-middle mr-1"></i>{!! $help !!}</small>
    @endif
    @include('admin.partials.form.error', ['field' => $name])
</div>
