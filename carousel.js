let currentIndex = 0; // Startindex
const images = document.querySelectorAll('.carousel-images img');

function cycleImages() {
    const totalImages = images.length;
    images.forEach((img, index) => img.classList.remove('previous', 'active', 'next'));

    const nextIndex = (currentIndex + 1) % totalImages;
    const previousIndex = (currentIndex - 1 + totalImages) % totalImages;

    images[currentIndex].classList.add('active');
    images[nextIndex].classList.add('next');
    images[previousIndex].classList.add('previous');

    currentIndex = nextIndex;
}

setInterval(cycleImages, 3000); // Wechsel alle 3 Sekunden
cycleImages();
