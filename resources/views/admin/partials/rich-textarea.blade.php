<div class="form-group">
    <label for="{{ $id }}">{{ $label }}</label>
    <textarea 
        name="{{ $name }}"
        id="{{ $id }}"
        {{ $required ? 'required' : '' }}
        placeholder="{{ $placeholder ?? '' }}"
        class="rich-textarea"
    >{{ $value ?? '' }}</textarea>

    @error($name)
        <div class="input-invalid">
            {{ $message }}
        </div>
    @enderror
</div>