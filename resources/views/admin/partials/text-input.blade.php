<div class="form-group">
    <label for="{{ $id }}">{{ $label }}</label>
    <input 
        type="{{ $type }}" 
        name="{{ $name }}" 
        id="{{ $id }}" 
        class="form-control @error($name) is-invalid @enderror"
        {{ $required ? 'required' : '' }}
        value="{{ $value ?? '' }}"
        placeholder="{{ $placeholder ?? '' }}"
    >
    @error($name)
        <div class="input-invalid">
            {{ $message }}
        </div>
    @enderror
</div>