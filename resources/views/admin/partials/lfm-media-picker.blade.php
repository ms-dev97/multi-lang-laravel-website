<button type="button" id="lfm" data-input="thumbnail" data-preview="holder" class="media-picker-btn btn btn-primary btn-outline flex align-items-center justify-content-between">
    <span>معرض الصور</span>
    <svg width="24" height="24" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M21 15V18H24V20H21V23H19V20H16V18H19V15H21ZM21.0082 3C21.556 3 22 3.44495 22 3.9934V13H20V5H4V18.999L14 9L17 12V14.829L14 11.8284L6.827 19H14V21H2.9918C2.44405 21 2 20.5551 2 20.0066V3.9934C2 3.44476 2.45531 3 2.9918 3H21.0082ZM8 7C9.10457 7 10 7.89543 10 9C10 10.1046 9.10457 11 8 11C6.89543 11 6 10.1046 6 9C6 7.89543 6.89543 7 8 7Z"></path></svg>
</button>

{{-- Holding currently selected image --}}
<input id="thumbnail" class="form-control" type="hidden">

{{-- Holding all selected images --}}
<input type="hidden" name="gallery_input" id="gallery-input" value="{{ $gallery_input ?? '' }}">

{{-- Gallery images --}}
<div class="lfm-preview flex g-0.5rem" id="lfm-preview">
    @if (isset($gallery_items) && is_array($gallery_items) && count($gallery_items) > 0)
        @foreach ($gallery_items as $item)
            <div class="gallery-item" data-url="{{ $item }}">
                <img src="{{ $item }}" class="gallery-img">
                <div class="gallery-item-remove">
                    <button type="button" class="remove-btn">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M7 6V3C7 2.44772 7.44772 2 8 2H16C16.5523 2 17 2.44772 17 3V6H22V8H20V21C20 21.5523 19.5523 22 19 22H5C4.44772 22 4 21.5523 4 21V8H2V6H7ZM13.4142 13.9997L15.182 12.232L13.7678 10.8178L12 12.5855L10.2322 10.8178L8.81802 12.232L10.5858 13.9997L8.81802 15.7675L10.2322 17.1817L12 15.4139L13.7678 17.1817L15.182 15.7675L13.4142 13.9997ZM9 4V6H15V4H9Z"></path></svg>
                    </button>
                </div>
            </div>
        @endforeach
    @endif
</div>

@push('scripts')
    <script>
        lfm('lfm', 'image', {prefix: '/admin/laravel-filemanager'});
        populateGalleryItems('thumbnail', 'gallery-input', 'lfm-preview');
        removeGalleryItem('lfm-preview', 'gallery-input');
    </script>
@endpush

@push('styles')
    <style>
        .lfm-preview {
            margin-block: 15px 20px;
        }
        .gallery-item {
            position: relative;
        }
        .gallery-img {
            height: 5rem;
        }
        .gallery-item .gallery-item-remove {
            position: absolute;
            inset: 0;
            background-color: rgba(0, 0, 0, 0.7);
            backdrop-filter: blur(3px);
            display: grid;
            place-items: center;
            display: none;
        }
        .gallery-item:hover .gallery-item-remove {
            display: grid;
        }
        .gallery-item .remove-btn {
            display: block;
            width: 24px;
            padding: 0;
            color: #fff;
        }
        .gallery-item .remove-btn > svg {
            display: block;
            width: 100%;
        }
    </style>
@endpush