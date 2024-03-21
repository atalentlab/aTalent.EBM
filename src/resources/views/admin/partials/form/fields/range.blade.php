<label class="form-label" for="{{ $name }}">{{ $label ?? str_readable($name) }} @if(isset($required) && $required) <span class="form-required">*</span>@endif</label>
<div class="row align-items-center">
    <div class="col">
        <input type="range"
               class="form-control js-custom-range custom-range"
               data-range-for="{{ $name }}"
               value="{{ old($name, isset($value) ? $value : (isset($entity) ? $entity->$name : null)) }}"
               @if(isset($step)) step="{{ $step }}" @endif
               @if(isset($max)) max="{{ $max }}" @endif
               @if(isset($min)) min="{{ $min }}" @endif
               @if(isset($max)) max="{{ $max }}" @endif
               @if(isset($dataParsleyErrorMessage)) data-parsley-error-message="{{ $dataParsleyErrorMessage }}" @endif
        >
    </div>
    <div class="col-auto">
        <input id="{{ $name }}"
               type="number"
               class="form-control w-8"
               name="{{ $name }}"
               value="{{ old($name, isset($value) ? $value : (isset($entity) ? $entity->$name : null)) }}"
               @if(isset($step)) step="{{ $step }}" @endif
               @if(isset($min)) min="{{ $min }}" @endif
               @if(isset($max)) max="{{ $max }}" @endif
               @if(isset($required) && $required) required @endif
               @if(isset($dataParsleyErrorMessage)) data-parsley-error-message="{{ $dataParsleyErrorMessage }}" @endif
               readonly
               placeholder="{{ $placeholder ?? 'Enter ' . str_replace("_", " ", $name) }}"
        >
    </div>
</div>

