<label class="form-label" for="{{ $name }}">{{ $label ?? str_readable($name) }} @if(isset($required) && $required) <span class="form-required">*</span>@endif</label>
<textarea id="{{ $name }}"
        type="{{ $type }}"
        class="form-control"
        name="{{ $name }}"
        rows="{{ $rows ?? 5 }}"
        placeholder="{{ $placeholder ?? 'Enter ' . str_replace("_", " ", $name) }}"
        @if(isset($required) && $required) required @endif
        @if(isset($maxLength)) maxlength="{{ $maxLength }}" @endif
        @if(isset($dataParsleyErrorMessage)) data-parsley-error-message="{{ $dataParsleyErrorMessage }}" @endif
>{{ old($name, isset($entity) ? $entity->$name : null) }}</textarea>
