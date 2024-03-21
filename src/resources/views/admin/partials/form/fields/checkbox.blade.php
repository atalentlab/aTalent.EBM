<label class="custom-switch" for="{{ $name }}">
    <input type="checkbox" id="{{ $name }}" name="{{ $name }}" class="custom-switch-input" {{ old($name, isset($entity) ? $entity->$name : null) ? 'checked' : '' }} value="1">
    <span class="custom-switch-indicator"></span>
    <span class="custom-switch-description">{{ $label ?? str_readable($name) }}</span>
</label>

