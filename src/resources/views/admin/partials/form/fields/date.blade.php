<label class="form-label" for="{{ $name }}">{{ $label ?? str_readable($name) }} @if(isset($required) && $required) <span class="form-required">*</span>@endif</label>

<div class="input-group js-date-input">
    <input type="text"
           id="{{ $name }}"
           name="{{ $name }}"
           class="form-control"
           data-mask="0000-00-00"
           data-date-format="YYYY-MM-DD"
           data-mask-clearifnotmatch="true"
           placeholder="0000-00-00"
           autocomplete="off"
           value="{{ old($name, isset($value) ? $value : (isset($entity) ? $entity->$name : null)) }}"
           maxlength="10"
           @if(isset($required) && $required) required @endif
           @if(isset($disabled) && $disabled) disabled @endif
           @if(isset($readonly) && $readonly) readonly @endif
           @if(isset($minDate) && $minDate) data-parsley-mindate={{ $minDate }} @endif
           @if(isset($dataParsleyErrorMessage)) data-parsley-error-message="{{ $dataParsleyErrorMessage }}" @endif
    >
    @if(isset($addTimeButtons) && $addTimeButtons)
    <div class="input-group-append">
        <button type="button" class="btn btn-sm btn-light js-date-input-add" data-seconds="{{ 3600 * 24 * 15 }}">15d</button>
        <button type="button" class="btn btn-sm btn-light js-date-input-add" data-seconds="{{ 3600 * 24 * 30 }}">30d</button>
        <button type="button" class="btn btn-sm btn-light js-date-input-add" data-seconds="{{ 3600 * 24 * 365 }}">1y</button>
    </div>
    @endif
</div>

