<div class="form-group img-upload">
    <label for="{{ $id }}">{{ $label }}</label>
    <input 
        type="file" 
        name="{{ $name }}" 
        id="{{ $id }}"
        class="form-control img-input @error('{{ $name }}') is-invalid @enderror"
        {{ $required ? 'required' : '' }}
        value="{{ $value ?? '' }}"
        accept="image/*"
    >
    @error($name)
        <div class="input-invalid">
            {{ $message }}
        </div>
    @enderror
    <img class="img-preview" src="{{ $src ?? '' }}" alt="">
</div>