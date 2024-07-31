<div class="toggle-input-container">
    <div class="label">{{ $label }}</div>

    <label for="{{ $id }}" class="toggle-input">
        <input type="checkbox" name="{{ $name }}" id="{{ $id }}" class="toggle-switch" @checked($checked)>
        <div class="circle"></div>
    </label>
</div>