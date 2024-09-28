// Image upload preview
const imgInput = document.querySelectorAll('.img-input');

imgInput.forEach(item => {
    item.addEventListener('change', function() {
        const img = this.files[0];
        const imgPreview = this.parentElement.querySelector('.img-preview');
        const fr = new FileReader();
        
        fr.readAsDataURL(img);
        fr.onloadend = () => imgPreview.setAttribute('src', fr.result);
    })
});

// Delete confirmation dialog
const confirmDialogOpen = document.querySelectorAll('.delete-confirm-btn');

confirmDialogOpen.forEach(item => {
    item.addEventListener('pointerdown', function() {
        const targetDialog = this.dataset.target;
        document.querySelector(targetDialog).showModal();
    });
});

// Dialog dismiss
document.querySelectorAll('dialog .dialog-dismiss').forEach(item => {
    item.addEventListener('pointerdown', function() {
        this.closest('dialog').close();
    });
});

document.addEventListener('DOMContentLoaded', function(e) {
    // fadeout notifications
    const notifications = this.querySelectorAll('.notification');
    notifications.forEach(item => {
        item.querySelector('.progress-bar').animate([
            {transform: 'scaleX(0)'},
            {transform: 'scaleX(1)'}
        ], {
            duration: 5000,
            easing: 'linear'
        }).addEventListener('finish', () => item.remove());

        // close notification
        item.querySelector('.close').addEventListener('pointerdown', () => {
            item.animate([{opacity: 1}, {opacity: 0}], {duration: 300}).onfinish = () => item.remove();
        });
    });

    // Keep dropdown open it has active item
    const activeSidebarDropdown = this.querySelector('.dropdown:has(.sidebar-item.active)');
    if (activeSidebarDropdown) activeSidebarDropdown.setAttribute('open', true);
});

// unisharp file manager
var lfm = function(id, type, options) {
    let button = document.getElementById(id);

    button.addEventListener('click', function () {
        var route_prefix = (options && options.prefix) ? options.prefix : '/laravel-filemanager';
        var target_input = document.getElementById(button.getAttribute('data-input'));

        window.open(route_prefix + '?type=' + type || 'file', 'FileManager', 'width=900,height=600');
        window.SetUrl = function (items) {
        var file_path = items.map(function (item) {
            return item.url;
        }).join(',');

        // set the value of the desired input to image url
        target_input.value = file_path;
        target_input.dispatchEvent(new Event('change'));

        };
    });
};

function populateGalleryItems(target, inputId, previewId) {
    document.getElementById(target).addEventListener('change', function() {
        const galleryItemsInput = document.getElementById(inputId);
        const galleryPreview = document.getElementById(previewId);

        const newVal = this.value != '' ? this.value.split(',') : [];
        const oldVal = galleryItemsInput.value != '' ? galleryItemsInput.value.split(',') : [];
        const mergedVals = [...oldVal, ...newVal];
        const uniqueVals = Array.from(new Set(mergedVals));

        galleryItemsInput.value = uniqueVals.join(',');
        this.value = '';

        galleryPreview.innerHTML = '';
        uniqueVals.forEach(function (item) {
            const html = `
                <div class="gallery-item" data-url="${item}">
                    <img src="${item}" class="gallery-img">
                    <div class="gallery-item-remove">
                        <button type="button" class="remove-btn">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M7 6V3C7 2.44772 7.44772 2 8 2H16C16.5523 2 17 2.44772 17 3V6H22V8H20V21C20 21.5523 19.5523 22 19 22H5C4.44772 22 4 21.5523 4 21V8H2V6H7ZM13.4142 13.9997L15.182 12.232L13.7678 10.8178L12 12.5855L10.2322 10.8178L8.81802 12.232L10.5858 13.9997L8.81802 15.7675L10.2322 17.1817L12 15.4139L13.7678 17.1817L15.182 15.7675L13.4142 13.9997ZM9 4V6H15V4H9Z"></path></svg>
                        </button>
                    </div>
                </div>
            `;
            galleryPreview.insertAdjacentHTML('beforeend', html);
        });
    });
}

function removeGalleryItem(galleryContainerId, galleryInputId) {
    const galleryContainer = document.getElementById(galleryContainerId);

    galleryContainer.addEventListener('pointerdown', e => {
        const btnIsclicked = e.composedPath().some(path => path.classList?.contains('remove-btn'));

        if(btnIsclicked) {
            const parent = e.target.closest('.gallery-item');
            const url = parent.dataset.url;
            const galleryInput = document.getElementById(galleryInputId);

            galleryInput.value = galleryInput.value.split(',').filter(item => item != url).join(',');
            parent.remove();
        }
    });
}