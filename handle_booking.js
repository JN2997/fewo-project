function handleBooking() {
    // Überprüfen, ob der Benutzer eingeloggt ist
    var loggedIn = <?php echo isset($_SESSION['loggedin']) && $_SESSION['loggedin'] ? 'true' : 'false'; ?>;
    
    if (loggedIn) {
        // Benutzer ist eingeloggt, weiterleiten zur Buchungsseite
        window.location.href = 'unterkunft_buchen.php?HAUS_ID=<?php echo $haus_id; ?>';
    } else {
        // Benutzer ist nicht eingeloggt, Anmelde-Popup öffnen
        function openPopupanmelden() {
    document.getElementById("popupanmelden").style.display = "block";
}
    }
}