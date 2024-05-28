// Warten, bis das DOM vollständig geladen ist
document.addEventListener('DOMContentLoaded', function() {
    // Referenzen zu den Eingabefeldern und Ausgabeelementen abrufen
    const anreiseInput = document.getElementById('anreise'); // Anreise-Datumsfeld
    const abreiseInput = document.getElementById('abreise'); // Abreise-Datumsfeld
    const gesamtpreisOutput = document.getElementById('gesamtpreis'); // Ausgabefeld für den Gesamtpreis

    // Ein neues div-Element für die Fehlerausgabe erstellen
    const errorOutput = document.createElement('div'); // Fehlerausgabe-Div erstellen
    errorOutput.id = 'errorOutput'; // ID für das Fehlerausgabe-Div setzen
    // Das Fehlerausgabe-Element vor dem gesamtpreisOutput-Element einfügen
    gesamtpreisOutput.parentNode.insertBefore(errorOutput, gesamtpreisOutput); // Fehlerausgabe-Div in das DOM einfügen

    // Setzen des Standardwerts und des Mindestwerts für das Anreisedatum
    anreiseInput.value = heute; // Standardwert für das Anreisedatum setzen (heutiges Datum)
    anreiseInput.setAttribute('min', heute); // Mindestwert für das Anreisedatum setzen (heutiges Datum)

    // Setzen des Standardwerts und des Mindestwerts für das Abreisedatum
    abreiseInput.value = morgen; // Standardwert für das Abreisedatum setzen (morgen)
    abreiseInput.setAttribute('min', morgen); // Mindestwert für das Abreisedatum setzen (morgen)

    // Funktion zur Berechnung des Gesamtpreises
    function berechneGesamtpreis() {
        // Abrufen der Anreise- und Abreisedaten
        const anreise = new Date(anreiseInput.value); // Anreisedatum
        const abreise = new Date(abreiseInput.value); // Abreisedatum
        let errorMessage = ''; // Variable für Fehlermeldungen

        // Überprüfen, ob beide Daten ausgewählt sind
        if (anreiseInput.value && abreiseInput.value) {
            // Überprüfen, ob die Abreise nach der Anreise liegt
            if (anreise >= abreise) {
                // Fehlermeldung, wenn die Abreise nicht nach der Anreise liegt
                errorMessage = 'Abreise muss nach dem Anreisedatum liegen und mindestens einen Tag Unterschied haben.';
            } else {
                // Berechnung der Anzahl der Tage zwischen Anreise und Abreise
                const zeitDifferenz = abreise.getTime() - anreise.getTime(); // Zeitdifferenz in Millisekunden
                const tage = zeitDifferenz / (1000 * 3600 * 24); // Zeitdifferenz in Tagen

                // Überprüfen, ob die Anzahl der Tage mindestens 1 ist
                if (tage >= 1) {
                    // Berechnung des Gesamtpreises
                    const gesamtpreis = tage * preisProNacht; // Gesamtpreis berechnen
                    gesamtpreisOutput.textContent = `Gesamtpreis: ${gesamtpreis.toFixed(2)} EUR`; // Gesamtpreis anzeigen
                } else {
                    // Fehlermeldung, wenn die Abreise weniger als einen Tag nach der Anreise liegt
                    errorMessage = 'Abreise muss mindestens einen Tag nach Anreise liegen.';
                }
            }
        }

        // Anzeige der Fehlermeldung oder des Gesamtpreises
        if (errorMessage) {
            errorOutput.textContent = errorMessage; // Fehlermeldung anzeigen
            gesamtpreisOutput.textContent = 'Gesamtpreis: 0.00 EUR'; // Gesamtpreis auf 0 setzen
        } else {
            errorOutput.textContent = ''; // Keine Fehlermeldung anzeigen
        }
    }

    // Funktion zur Validierung des Anreisedatums
    function validateAnreise() {
        const heute = new Date().toISOString().split('T')[0]; // Heutiges Datum im Format YYYY-MM-DD
        anreiseInput.setAttribute('min', heute); // Mindestwert für das Anreisedatum setzen (heutiges Datum)
        if (anreiseInput.value && anreiseInput.value < heute) {
            // Fehlermeldung, wenn das Anreisedatum in der Vergangenheit liegt
            anreiseInput.setCustomValidity('Anreise kann nicht in der Vergangenheit liegen.');
            errorOutput.textContent = 'Anreise kann nicht in der Vergangenheit liegen.';
        } else {
            anreiseInput.setCustomValidity(''); // Keine Fehlermeldung setzen
            errorOutput.textContent = ''; // Keine Fehlermeldung anzeigen

            // Setzen des Mindestdatums für Abreise basierend auf dem Anreisedatum
            const anreiseDate = new Date(anreiseInput.value); // Anreisedatum
            const minAbreiseDate = new Date(anreiseDate.getTime() + (1000 * 60 * 60 * 24)); // Mindest-Abreisedatum (Anreisedatum + 1 Tag)
            const minAbreiseDateString = minAbreiseDate.toISOString().split('T')[0]; // Mindest-Abreisedatum im Format YYYY-MM-DD
            abreiseInput.setAttribute('min', minAbreiseDateString); // Mindestwert für das Abreisedatum setzen

            // Überprüfen, ob das aktuelle Abreisedatum vor dem Mindestdatum liegt
            if (abreiseInput.value && abreiseInput.value <= anreiseInput.value) {
                abreiseInput.value = minAbreiseDateString; // Abreisedatum auf Mindestdatum setzen
            }
        }
    }

    // Funktion zur Validierung des Abreisedatums
    function validateAbreise() {
        if (anreiseInput.value && abreiseInput.value && abreiseInput.value <= anreiseInput.value) {
            // Fehlermeldung, wenn das Abreisedatum vor dem Anreisedatum liegt
            abreiseInput.setCustomValidity('Abreise muss nach dem Anreisedatum liegen.');
            errorOutput.textContent = 'Abreise muss nach dem Anreisedatum liegen.';
        } else {
            abreiseInput.setCustomValidity(''); // Keine Fehlermeldung setzen
            if (!anreiseInput.value || !abreiseInput.value) {
                errorOutput.textContent = ''; // Keine Fehlermeldung anzeigen
            }
        }
    }

    // Event-Listener für Änderungen und Eingaben in den Datumsfeldern hinzufügen
    anreiseInput.addEventListener('change', function() {
        validateAnreise(); // Anreisedatum validieren
        berechneGesamtpreis(); // Gesamtpreis berechnen
    });
    abreiseInput.addEventListener('change', function() {
        validateAbreise(); // Abreisedatum validieren
        berechneGesamtpreis(); // Gesamtpreis berechnen
    });
    anreiseInput.addEventListener('input', validateAnreise); // Anreisedatum bei Eingabe validieren
    abreiseInput.addEventListener('input', validateAbreise); // Abreisedatum bei Eingabe validieren

    // Gesamtpreis berechnen und anzeigen, wenn die Seite geladen wird
    berechneGesamtpreis(); // Initiale Berechnung des Gesamtpreises
});
