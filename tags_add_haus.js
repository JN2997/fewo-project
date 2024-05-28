// Warten, bis das DOM vollständig geladen ist
document.addEventListener('DOMContentLoaded', 
function() {    // Referenzen zu den DOM-Elementen abrufen
    const tagsSelect = document.getElementById('tags-select'); // Dropdown-Auswahl für Tags
    const tagsContainer = document.getElementById('tags-container'); // Container für die ausgewählten Tags
    const hiddenTagsInput = document.getElementById('hidden-tags'); // Verstecktes Eingabefeld für die ausgewählten Tags
    let selectedTags = []; // Array zur Speicherung der ausgewählten Tags

    // Tags aus der Datenbank in das Select-Element einfügen
    if (dbTags && tagsSelect) {
        dbTags.forEach(tag => {
            const option = document.createElement('option'); // Neues Option-Element erstellen
            option.value = tag; // Wert des Option-Elements setzen
            option.text = tag; // Text des Option-Elements setzen
            tagsSelect.appendChild(option); // Option-Element zum Select-Element hinzufügen
        });
    }

    // Funktion zum Aktualisieren der Tags im Container
    function updateTagsContainer() {
        tagsContainer.innerHTML = ''; // Container leeren
        selectedTags.forEach(tag => {
            const tagSpan = document.createElement('span'); // Neues Span-Element für das Tag erstellen
            tagSpan.className = 'tag'; // Klasse für das Span-Element setzen
            tagSpan.innerText = tag; // Text des Span-Elements setzen

            // Entfernen-Button für das Tag erstellen
            const removeButton = document.createElement('span'); // Neues Span-Element für den Entfernen-Button
            removeButton.innerHTML = '&times;'; // Text des Entfernen-Buttons setzen (×)
            removeButton.className = 'remove-tag'; // Klasse für den Entfernen-Button setzen
            removeButton.addEventListener('click', 
			function() {
                // Tag aus dem Array der ausgewählten Tags entfernen
                selectedTags = selectedTags.filter(t => t !== tag);
                updateTagsContainer(); // Container aktualisieren
                updateSelectElement(); // Select-Element aktualisieren
            });

            tagSpan.appendChild(removeButton); // Entfernen-Button zum Tag-Span hinzufügen
            tagsContainer.appendChild(tagSpan); // Tag-Span zum Container hinzufügen
        });
        hiddenTagsInput.value = selectedTags.join(','); // Aktualisiere das versteckte Eingabefeld mit den ausgewählten Tags
    }

    // Funktion zum Aktualisieren des Select-Elements
    function updateSelectElement() {
        Array.from(tagsSelect.options).forEach(option => {
            // Option deaktivieren, wenn der Tag bereits ausgewählt ist
            option.disabled = selectedTags.includes(option.value);
        });
    }

    // Tags hinzufügen, wenn sie ausgewählt werden
    tagsSelect.addEventListener('change', function() {
        const selectedTag = tagsSelect.value; // Ausgewählter Tag
        if (selectedTag && !selectedTags.includes(selectedTag)) {
            selectedTags.push(selectedTag); // Tag zum Array der ausgewählten Tags hinzufügen
            updateTagsContainer(); // Container aktualisieren
            updateSelectElement(); // Select-Element aktualisieren
        }
        tagsSelect.value = ''; // Select-Element zurücksetzen
    });

    // Initiale Tags-Aktualisierung
    updateTagsContainer();
});
