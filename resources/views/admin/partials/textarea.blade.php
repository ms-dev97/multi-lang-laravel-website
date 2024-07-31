<div class="form-group">
    <label for="{{ $id }}">{{ $label }}</label>
    <textarea 
        name="{{ $name }}"
        id="{{ $id }}"
        class="form-control @error($name) is-invalid @enderror"
        {{ $required ? 'required' : '' }}
        placeholder="{{ $placeholder ?? '' }}"
    >{{ $value ?? '' }}</textarea>

    @error($name)
        <div class="input-invalid">
            {{ $message }}
        </div>
    @enderror
</div>