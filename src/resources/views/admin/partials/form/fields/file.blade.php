<label class="form-label" for="{{ $name }}">{{ $label ?? str_readable($name) }} @if(isset($required) && $required) <span class="form-required">*</span>@endif</label>
<div class="custom-file">
    <input type="file"
           name="{{ $name }}"
           id="{{ $name }}"
           class="custom-file-input"
           data-parsley-trigger="change"
           value="{{ old($name, isset($entity) ? $entity->$name : null) }}"
           @if(isset($required) && $required) required @endif
           @if(isset($maxSize)) data-parsley-filemaxsize="{{ $maxSize }}" @endif
           @if(isset($mimeTypes))
           data-parsley-filemimetypes="{{ $mimeTypes }}"
           accept="{{ $mimeTypes }}"
           @endif
           @if(isset($dataParsleyErrorMessage)) data-parsley-error-message="{{ $dataParsleyErrorMessage }}" @endif
    >
    <label class="custom-file-label" for="{{ $name }}">{{ isset($entity) && $entity->$name ? $entity->name : ($placeholder ?? 'Select ' . str_readable($name)) }}</label>
</div>
<div class="border rounded-lg custom-file-preview text-center p-3" style="display:{{ isset($entity) && $entity->$name ? 'block' : 'none' }};">
    <img src="{{ isset($entity) && $entity->$name ? Storage::url($entity->$name) : null }}" data-src="{{ isset($entity) && $entity->$name ? Storage::url($entity->$name) : null }}" class="img-fluid">
</div>
