// Warten, bis das DOM vollständig geladen ist
document.addEventListener('DOMContentLoaded', function() {
    // Referenzen zu den Eingabefeldern und Ausgabeelementen abrufen
    const anreiseInput = document.getElementById('anreise');
    const abreiseInput = document.getElementById('abreise');
    const gesamtpreisOutput = document.getElementById('gesamtpreis');

    // Ein neues div-Element für die Fehlerausgabe erstellen
    const errorOutput = document.createElement('div');
    errorOutput.id = 'errorOutput';
    // Das Fehlerausgabe-Element vor dem gesamtpreisOutput-Element einfügen
    gesamtpreisOutput.parentNode.insertBefore(errorOutput, gesamtpreisOutput);

    // Setzen des Standardwerts und des Mindestwerts für das Anreisedatum
    anreiseInput.value = heute;
    anreiseInput.setAttribute('min', heute);

    // Setzen des Standardwerts und des Mindestwerts für das Abreisedatum
    abreiseInput.value = morgen;
    abreiseInput.setAttribute('min', morgen);

    // Funktion zur Berechnung des Gesamtpreises
    function berechneGesamtpreis() {
        // Abrufen der Anreise- und Abreisedaten
        const anreise = new Date(anreiseInput.value);
        const abreise = new Date(abreiseInput.value);
        let errorMessage = '';

        // Überprüfen, ob beide Daten ausgewählt sind
        if (anreiseInput.value && abreiseInput.value) {
            // Überprüfen, ob die Abreise nach der Anreise liegt
            if (anreise >= abreise) {
                errorMessage = 'Abreise muss nach dem Anreisedatum liegen und mindestens einen Tag Unterschied haben.';
            } else {
                // Berechnung der Anzahl der Tage zwischen Anreise und Abreise
                const zeitDifferenz = abreise.getTime() - anreise.getTime();
                const tage = zeitDifferenz / (1000 * 3600 * 24);

                // Überprüfen, ob die Anzahl der Tage mindestens 1 ist
                if (tage >= 1) {
                    // Berechnung des Gesamtpreises
                    const gesamtpreis = tage * preisProNacht;
                    gesamtpreisOutput.textContent = `Gesamtpreis: ${gesamtpreis.toFixed(2)} EUR`;
                } else {
                    errorMessage = 'Abreise muss mindestens einen Tag nach Anreise liegen.';
                }
            }
        }

        // Anzeige der Fehlermeldung oder des Gesamtpreises
        if (errorMessage) {
            errorOutput.textContent = errorMessage;
            gesamtpreisOutput.textContent = 'Gesamtpreis: 0.00 EUR';
        } else {
            errorOutput.textContent = '';
        }
    }

    // Funktion zur Validierung des Anreisedatums
    function validateAnreise() {
        const heute = new Date().toISOString().split('T')[0];
        anreiseInput.setAttribute('min', heute);
        if (anreiseInput.value && anreiseInput.value < heute) {
            anreiseInput.setCustomValidity('Anreise kann nicht in der Vergangenheit liegen.');
            errorOutput.textContent = 'Anreise kann nicht in der Vergangenheit liegen.';
        } else {
            anreiseInput.setCustomValidity('');
            errorOutput.textContent = '';

            // Setzen des Mindestdatums für Abreise basierend auf dem Anreisedatum
            const anreiseDate = new Date(anreiseInput.value);
            const minAbreiseDate = new Date(anreiseDate.getTime() + (1000 * 60 * 60 * 24)); // Anreise + 1 Tag
            const minAbreiseDateString = minAbreiseDate.toISOString().split('T')[0];
            abreiseInput.setAttribute('min', minAbreiseDateString);

            // Überprüfen, ob das aktuelle Abreisedatum vor dem Mindestdatum liegt
            if (abreiseInput.value && abreiseInput.value <= anreiseInput.value) {
                abreiseInput.value = minAbreiseDateString;
            }
        }
    }

    // Funktion zur Validierung des Abreisedatums
    function validateAbreise() {
        if (anreiseInput.value && abreiseInput.value && abreiseInput.value <= anreiseInput.value) {
            abreiseInput.setCustomValidity('Abreise muss nach dem Anreisedatum liegen.');
            errorOutput.textContent = 'Abreise muss nach dem Anreisedatum liegen.';
        } else {
            abreiseInput.setCustomValidity('');
            if (!anreiseInput.value || !abreiseInput.value) {
                errorOutput.textContent = '';
            }
        }
    }

    // Event-Listener für Änderungen und Eingaben in den Datumsfeldern hinzufügen
    anreiseInput.addEventListener('change', function() {
        validateAnreise();
        berechneGesamtpreis();
    });
    abreiseInput.addEventListener('change', function() {
        validateAbreise();
        berechneGesamtpreis();
    });
    anreiseInput.addEventListener('input', validateAnreise);
    abreiseInput.addEventListener('input', validateAbreise);

    // Gesamtpreis berechnen und anzeigen, wenn die Seite geladen wird
    berechneGesamtpreis();
});
