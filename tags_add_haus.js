document.addEventListener('DOMContentLoaded', function() {
    const tagsSelect = document.getElementById('tags-select');
    const tagsContainer = document.getElementById('tags-container');
    const hiddenTagsInput = document.getElementById('hidden-tags');
    let selectedTags = [];

    // Tags in das Select-Element einfügen
    if (dbTags && tagsSelect) {
        dbTags.forEach(tag => {
            const option = document.createElement('option');
            option.value = tag;
            option.text = tag;
            tagsSelect.appendChild(option);
        });
    }

    // Funktion zum Aktualisieren der Tags im Container
    function updateTagsContainer() {
        tagsContainer.innerHTML = ''; // Container leeren
        selectedTags.forEach(tag => {
            const tagSpan = document.createElement('span');
            tagSpan.className = 'tag';
            tagSpan.innerText = tag;
            const removeButton = document.createElement('span');
            removeButton.innerHTML = '&times;';
            removeButton.className = 'remove-tag';
            removeButton.addEventListener('click', function() {
                selectedTags = selectedTags.filter(t => t !== tag);
                updateTagsContainer();
                updateSelectElement();
            });
            tagSpan.appendChild(removeButton);
            tagsContainer.appendChild(tagSpan);
        });
        hiddenTagsInput.value = selectedTags.join(','); // Aktualisiere das versteckte Eingabefeld mit den ausgewählten Tags
    }

    // Funktion zum Aktualisieren des Select-Elements
    function updateSelectElement() {
        Array.from(tagsSelect.options).forEach(option => {
            option.disabled = selectedTags.includes(option.value);
        });
    }

    // Tags hinzufügen, wenn sie ausgewählt werden
    tagsSelect.addEventListener('change', function() {
        const selectedTag = tagsSelect.value;
        if (selectedTag && !selectedTags.includes(selectedTag)) {
            selectedTags.push(selectedTag);
            updateTagsContainer();
            updateSelectElement();
        }
        tagsSelect.value = ''; // Reset select
    });

    // Initiale Tags-Aktualisierung
    updateTagsContainer();
});
