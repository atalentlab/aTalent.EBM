<label class="form-label" for="{{ $name }}">{{ $label ?? str_readable($name) }} @if(isset($required) && $required) <span class="form-required">*</span>@endif</label>
<input id="{{ $name }}"
       type="{{ $type }}" class="form-control"
       name="{{ $name }}"
       value="{{ old($name, isset($value) ? $value : (isset($entity) ? $entity->$name : null)) }}"
       placeholder="{{ $placeholder ?? 'Enter ' . str_replace("_", " ", $name) }}"
       @if(isset($required) && $required) required @endif
       @if(isset($maxLength)) maxlength="{{ $maxLength }}" @endif
       @if(isset($minLength)) minlength="{{ $minLength }}" @endif
       @if(isset($min)) min="{{ $min }}" @endif
       @if(isset($max)) max="{{ $max }}" @endif
       @if(isset($disabled) && $disabled) disabled @endif
       @if(isset($readonly) && $readonly) readonly @endif
       @if($type === 'url' && isset($strict) && $strict) data-parsley-urlstrict @endif
       @if(isset($dataParsleyErrorMessage)) data-parsley-error-message="{{ $dataParsleyErrorMessage }}" @endif
>
