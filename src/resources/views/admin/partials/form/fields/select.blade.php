@if($label !== '')
<label class="form-label" for="{{ $name }}">{{ $label ?? str_readable($name) }} @if(isset($required) && $required) <span class="form-required">*</span>@endif</label>
@endif
<select id="{{ $name }}"
        class="form-control{{ isset($isNativeSelect) && $isNativeSelect ? '' : ' selectize' }}{{ isset($inputClass) ? ' ' . $inputClass : '' }}"
        name="{{ $name . (isset($multiple) && $multiple ? '[]' : '') }}"
        @if(isset($required) && $required) required @endif
        @if(isset($disabled) && $disabled) disabled @endif
        @if(isset($multiple) && $multiple) multiple @endif
        @if(isset($maxItems) && $maxItems) data-max-items="{{ $maxItems }}" @endif
        @if(isset($allowCreate) && $allowCreate) data-allow-create="true" @endif
        @if(isset($remote) && $remote)
            data-remote="true"
            data-remote-url="{{ $remoteUrl ?? '' }}"
        @endif
        @if(isset($inputAttributes))
            @foreach($inputAttributes as $key => $attr)
                {{$key}}="{{ $attr }}"
            @endforeach
        @endif
        @if(isset($dataParsleyErrorMessage)) data-parsley-error-message="{{ $dataParsleyErrorMessage }}" @endif
>
    <option value="">{{ $placeholder ?? 'Select ' . str_replace("_", " ", $name) }}</option>

    @if(isset($remote) && $remote)
        @if(isset($relation) && isset($entity))
            @if(isset($multiple) && $multiple)
                @foreach($entity->$relation()->get() as $item)
                    <option value="{{ $item->id }}" selected>{{ $relationField ? $item->$relationField : $item->id }}</option>
                @endforeach
            @else
                <option value="{{ old($name, $entity->$name) }}" selected>{{ optional($entity->$relation()->where('id', $entity->$name)->first())->$relationField }}</option>
            @endif
        @endif
    @else
        @if(isset($items))
            @foreach($items as $key => $item)
                <option value="{{ $key }}"
                @if(isset($multiple) && $multiple)
                    {{ in_array($key, old($name, isset($entity) ? $entity->$name->pluck($relationField ?? 'id')->toArray() : [])) ? 'selected' : '' }}
                @else
                    {{ $key == old($name, isset($value) ? $value : (isset($entity) ? $entity->$name : null)) ? 'selected' : '' }}
                @endif
                >{{ $item }}</option>
            @endforeach
        @endif
    @endif
</select>
