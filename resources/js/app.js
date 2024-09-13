// import './bootstrap';
import.meta.glob([
    '../images/**',
]);
import * as bootstrap from 'bootstrap';

document.addEventListener('DOMContentLoaded', function() {
    // Gallery viewer
    const gallerySection = document.querySelector('.gallery-section');
    if (gallerySection) {
        openGallery(gallerySection);
    }

    // Counter up effect
    const counterUpNumbers = document.querySelectorAll('.counter-up');

    if (counterUpNumbers.length > 0) {
        const countObserver = new IntersectionObserver(entries => {
            entries.forEach(entry => {
                if (!entry.isIntersecting) return;
                const target = entry.target;
                const count = Number(target.dataset.count);
                let currentCount = 0;
                target.textContent = 0;

                const interval = setInterval(() => {
                    if (currentCount > count) {
                        clearInterval(interval);
                        countObserver.unobserve(target);
                        target.textContent = count;
                        return;
                    }
                    target.textContent = currentCount;
                    currentCount += Math.ceil(count / 40);
                }, 32);
            });
        }, {threshold: 1});

        counterUpNumbers.forEach(item => {
            countObserver.observe(item);
        });
    }

});

function openGallery(gallerySection) {
    const galleryImgs = gallerySection.querySelectorAll('.gallery-img');
    const galleryViewer = gallerySection.querySelector('.gallery-viewer');
    const galleryViewerImg = galleryViewer.querySelector('.viewer-img');
    const imgURLs = [];
    let current = 0;

    galleryImgs.forEach((img, i) => {
        // push images
        imgURLs.push(img.getAttribute('src'));

        img.addEventListener('pointerdown', function() {
            galleryViewer.showPopover();
            galleryViewerImg.setAttribute('src', this.getAttribute('src'));

            // current image
            current = i;
        });
    });

    function flipNext() {
        current++;
        if (current > imgURLs.length - 1) current = 0;
        galleryViewerImg.setAttribute('src', imgURLs[current]);
    }

    function flipPrev() {
        current--;
        if (current < 0) current = imgURLs.length - 1;
        galleryViewerImg.setAttribute('src', imgURLs[current]);
    }

    // next image
    galleryViewer.querySelector('.next').addEventListener('pointerdown', flipNext);

    // previous image
    galleryViewer.querySelector('.pre').addEventListener('pointerdown', flipPrev);

    // close modal
    galleryViewer.querySelector('.close').addEventListener('pointerdown', () => {
        galleryViewer.hidePopover();
    })
}