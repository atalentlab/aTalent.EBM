<label class="form-label" for="{{ $name }}">{{ $label ?? str_readable($name) }} @if(isset($required) && $required) <span class="form-required">*</span>@endif</label>
<input type="text"
       id="{{ $name }}"
       name="{{ $name }}"
       class="form-control"
       data-mask="0000-00-00 00:00:00"
       data-date-format="YYYY-MM-DD"
       data-mask-clearifnotmatch="true"
       placeholder="0000-00-00 00:00:00"
       autocomplete="off"
       value="{{ old($name, isset($value) ? $value : (isset($entity) ? $entity->$name : null)) }}"
       maxlength="19"
       @if(isset($required) && $required) required @endif
       @if(isset($disabled) && $disabled) disabled @endif
       @if(isset($readonly) && $readonly) readonly @endif
       @if(isset($dataParsleyErrorMessage)) data-parsley-error-message="{{ $dataParsleyErrorMessage }}" @endif
>
