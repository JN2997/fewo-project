function openPopupanmelden() {
    // Zeigt das Anmelde-Popup an
    document.getElementById("popupanmelden").style.display = "block";
}

function openPopupregistrieren() {
    // Zeigt das Registrierungs-Popup an
    document.getElementById("popupregistrieren").style.display = "block";
}

function closePopup() {
    // Blendet sowohl das Anmelde- als auch das Registrierungs-Popup
    document.getElementById("popupanmelden").style.display = "none";
    document.getElementById("popupregistrieren").style.display = "none";
}
