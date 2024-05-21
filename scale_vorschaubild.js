document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.container2 img').forEach(img => {
        // Fügt jedem Bild einen Event-Listener für das Klick-Ereignis hinzu
        img.addEventListener('click', () => {
            // Überprüft, ob das Bild bereits vergrößert ist (Skalierungsfaktor 1.1)
            if (img.style.transform === 'scale(1.1)') {
                // Wenn das Bild vergrößert ist, setzt es zurück auf die normale Größe (Skalierungsfaktor 1)
                img.style.transform = 'scale(1)';
            } else {
                // Wenn das Bild nicht vergrößert ist, vergrößert es auf 1.1-fache Größe
                img.style.transform = 'scale(1.1)';
            }
            // Fügt eine Übergangsanimation für die Transformation hinzu, die 0.3 Sekunden dauert
            img.style.transition = 'transform 0.3s';
        });
    });
});