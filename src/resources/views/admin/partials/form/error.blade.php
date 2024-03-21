@if ($errors->has($field) || $errors->has($field . '.*'))
    <div class="invalid-feedback filled">
        @foreach($errors->get($field) as $error)
        <div class="parsley-type">{{ $error }}</div>
        @endforeach
    </div>
@endif
