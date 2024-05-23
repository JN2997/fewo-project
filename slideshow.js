// JavaScript für die Slideshow

// Initialer Index der Slideshow, setzt die Startposition auf das erste Bild
let slideIndex = 1;

// Funktion wird aufgerufen, um das erste Bild anzuzeigen
showSlides(slideIndex);

/**
 * Ändert den aktuellen Slide-Index und zeigt den entsprechenden Slide an.
 * @param {number} n - Die Anzahl der Slides, um die der Index geändert werden soll.
 */
function plusSlides(n) {
    // Ändert den aktuellen Slide-Index um n und zeigt den entsprechenden Slide an
    showSlides(slideIndex += n);
}

/**
 * Zeigt den Slide an, der dem aktuellen Index entspricht.
 * @param {number} n - Der aktuelle Index des anzuzeigenden Slides.
 */
function showSlides(n) {
    // Variable zur Iteration über die Slides
    let i;

    // Holt alle Elemente mit der Klasse "slides"
    let slides = document.getElementsByClassName("slides");

    // Wenn der Index größer ist als die Anzahl der Slides, setze den Index auf 1 (erste Slide)
    if (n > slides.length) {
        slideIndex = 1;
    }
    
    // Wenn der Index kleiner ist als 1, setze den Index auf die Anzahl der Slides (letzte Slide)
    if (n < 1) {
        slideIndex = slides.length;
    }
    
    // Schleife durch alle Slides und verstecke sie
    for (i = 0; i < slides.length; i++) {
        slides[i].style.display = "none";
    }
    
    // Zeige die aktuelle Slide basierend auf dem Index
    slides[slideIndex - 1].style.display = "block";
}