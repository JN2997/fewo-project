<?php 
session_start(); 
include 'db_connect.php'; 
include 'auth_nav.php'; 
exclude_user_Roles(['guest', 'Mieter', 'Admin'], 'index.php'); 

// Dynamische Inhaltsanzeige
$page = isset($_GET['page']) ? $_GET['page'] : 'welcome'; // Überprüfen, ob eine Seite in der URL angegeben ist, ansonsten 'welcome' setzen

// Abrufen aller Tags aus der Datenbank
$sql = "SELECT tag_wert FROM tags"; 
$result = $conn->query($sql);

$tags = array();
if ($result->num_rows > 0) { // Überprüfen, ob Ergebnisse vorhanden sind
    while($row = $result->fetch_assoc()) { // Alle Ergebnisse durchlaufen
        $tags[] = $row["tag_wert"]; // Jeden Tag-Wert in das Array $tags hinzufügen
    }
}

$conn->close(); // Datenbankverbindung schließen

// Funktion zur Inhaltsanzeige basierend auf der gewählten Seite
function loadContent($page) {
    switch ($page) {
        case 'add':
            include 'haus_add.php'; // Seite zum Hinzufügen eines Hauses einbinden
            break;
        case 'edit':
            include 'haus_edit.php'; // Seite zum Bearbeiten eines Hauses einbinden
            break;
        case 'bookings':
            include 'haus_bookings.php'; // Seite zur Anzeige von Buchungen einbinden
            break;
        default:
            echo '<div class="welcome-message"><b><h3>Herzlich Willkommen im Verwaltungsbereich für Ihre Ferienhäuser.</h3> <br>
                  Sie können hier neue Ferienhäuser hinzufügen, welche dann vermietet werden, Ferienhäuser bearbeiten oder löschen und natürlich für jedes Ferienhaus die Buchungen einsehen.</b></div>'; // Willkommensnachricht anzeigen
            break;
    }
}

?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ihr Ferien Domizil</title>
    <!-- CSS-Aufrufe -->
    <link rel="stylesheet" href="css/main.css">
    <!-- JavaScript-Aufrufe -->
    <script>
        function navigate(page) {
            window.location.href = `?page=${page}`; // JavaScript-Funktion zum Navigieren zwischen Seiten
        }
    </script>
</head>
<body>
    <header>
        <div class="logo">
            <a href="index.php"><img src="img/Zeichnung-Flach.png" alt="Logo"></a> <!-- Logo und Link zur Startseite -->
        </div>
        <nav class="menu">
            <?php display_menu(); ?> <!-- Menü basierend auf der Benutzerrolle anzeigen -->
        </nav>
    </header>
    <!-- Linke Sidebar -->
    <div class="sidebar-left">
        <h2><span>Unterkünfte</span></h2> <br>
        <button onclick="navigate('add')">Hinzufügen</button> <!-- Button zum Hinzufügen von Häusern -->
        <button onclick="navigate('edit')">Bearbeiten</button> <!-- Button zum Bearbeiten von Häusern -->
        <button onclick="navigate('bookings')">Buchungen einsehen</button> <!-- Button zur Anzeige von Buchungen -->
    </div>
    <!-- Hauptinhalt -->
    <main>
        <?php loadContent($page); ?> <!-- Inhalt basierend auf der gewählten Seite laden -->
    </main>
    <footer>
        <p>Kontaktieren Sie uns für weitere Informationen:</p>
        <p>Telefon: 123-456-789</p>
        <p>Email: <a href="mailto:info@IhrFerienDomizil.com">info@IhrFerienDomizil.com</a></p>
    </footer>
</body>
</html>
