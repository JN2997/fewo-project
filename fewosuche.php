<?php 
session_start(); 
include 'auth_nav.php'; 
// Abrufen der Parameter 'personenanzahl' und 'land' aus der URL, falls vorhanden, ansonsten leere Werte setzen
$personenanzahl = isset($_GET['personenanzahl']) ? $_GET['personenanzahl'] : '';
$land = isset($_GET['land']) ? $_GET['land'] : '';



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
    <script src="js/popup.js"></script>
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
        <h2><span>Filter</span></h2> <br>
        <!-- Formular zur Filterung der Ferienwohnungen -->
        <form action="fewosuche.php" method="GET">
            <!-- Dropdown zur Auswahl der Personenanzahl -->
            <select id="personenanzahl" name="personenanzahl">
                <?php
                $personenOptionen = ["1", "2", "3", "4", "5", "6", "7+"]; // Optionen für die Personenanzahl
                foreach ($personenOptionen as $option) {
                    // Jede Option anzeigen und die ausgewählte Option markieren
                    echo '<option value="' . $option . '"' . ($option === $personenanzahl ? ' selected' : '') . '>' . $option . '</option>';
                }
                ?>
            </select>
            <br>
            <!-- Dropdown zur Auswahl des Landes -->
            <select id="land" name="land">
                <?php
                $laenderOptionen = [
                    "deutschland" => "Deutschland", "oesterreich" => "Österreich",
                    "schweiz" => "Schweiz", "schweden" => "Schweden","finnland" => "Finnland",
                    "england" => "England", "daenemark" => "Dänemark", "norwegen" => "Norwegen"
                ]; // Optionen für die Länder
                foreach ($laenderOptionen as $key => $value) {
                    // Jede Option anzeigen und die ausgewählte Option markieren
                    echo '<option value="' . $key . '"' . ($key === $land ? ' selected' : '') . '>' . $value . '</option>';
                }
                ?>
            </select>
            <br><br>
            <!-- Weitere Filteroptionen ohne initiale Werte -->
            <h4>Preise</h4>
            <label><input type="checkbox" name="filter[price1]" value="price1" <?php echo in_array("price1", $_GET['filter'] ?? []) ? 'checked' : ''; ?>>max. 100€ pro Nacht</label><br>
            <label><input type="checkbox" name="filter[price2]" value="price2" <?php echo in_array("price2", $_GET['filter'] ?? []) ? 'checked' : ''; ?>>max. 200€ pro Nacht</label><br>
            <label><input type="checkbox" name="filter[price3]" value="price3" <?php echo in_array("price3", $_GET['filter'] ?? []) ? 'checked' : ''; ?>>max. 300€ pro Nacht</label><br>
            <h4>Merkmale</h4>
            <label><input type="checkbox" name="filter[Barrierefrei]" value="Barrierefrei" <?php echo in_array("Barrierefrei", $_GET['filter'] ?? []) ? 'checked' : ''; ?>>Barrierefrei</label><br>
            <label><input type="checkbox" name="filter[Allergikerfreundlich]" value="Allergikerfreundlich" <?php echo in_array("Allergikerfreundlich", $_GET['filter'] ?? []) ? 'checked' : ''; ?>>Allergikerfreundlich</label><br>
            <label><input type="checkbox" name="filter[Familienfreundlich]" value="Familienfreundlich" <?php echo in_array("Familienfreundlich", $_GET['filter'] ?? []) ? 'checked' : ''; ?>>Familienfreundlich</label><br>
            <label><input type="checkbox" name="filter[Haustiere erlaubt]" value="Haustiere erlaubt" <?php echo in_array("Haustiere erlaubt", $_GET['filter'] ?? []) ? 'checked' : ''; ?>>Haustiere erlaubt</label><br>
            <label><input type="checkbox" name="filter[Nichtraucher]" value="Nichtraucher" <?php echo in_array("Nichtraucher", $_GET['filter'] ?? []) ? 'checked' : ''; ?>>Nichtraucher</label><br>
            <h4>Ausstattung</h4>
            <label><input type="checkbox" name="filter[Pool]" value="Pool" <?php echo in_array("Pool", $_GET['filter'] ?? []) ? 'checked' : ''; ?>>Pool</label><br>
            <label><input type="checkbox" name="filter[Sauna]" value="Sauna" <?php echo in_array("Sauna", $_GET['filter'] ?? []) ? 'checked' : ''; ?>>Sauna</label><br>
            <label><input type="checkbox" name="filter[WLAN]" value="WLAN" <?php echo in_array("WLAN", $_GET['filter'] ?? []) ? 'checked' : ''; ?>>WLAN</label><br>
            <label><input type="checkbox" name="filter[Parkplatz]" value="Parkplatz" <?php echo in_array("Parkplatz", $_GET['filter'] ?? []) ? 'checked' : ''; ?>>Parkplatz</label><br>
            <label><input type="checkbox" name="filter[Küche]" value="Küche" <?php echo in_array("Küche", $_GET['filter'] ?? []) ? 'checked' : ''; ?>>Küche</label><br>
            <label><input type="checkbox" name="filter[Garten/Terasse]" value="Garten/Terasse" <?php echo in_array("Garten/Terasse", $_GET['filter'] ?? []) ? 'checked' : ''; ?>>Garten/Terasse</label><br>
            <label><input type="checkbox" name="filter[Klimaanlage]" value="Klimaanlage" <?php echo in_array("Klimaanlage", $_GET['filter'] ?? []) ? 'checked' : ''; ?>>Klimaanlage</label><br>
            <h4>Aktivitäten</h4>
            <label><input type="checkbox" name="filter[Angeln]" value="Angeln" <?php echo in_array("Angeln", $_GET['filter'] ?? []) ? 'checked' : ''; ?>>Angeln</label><br>
            <label><input type="checkbox" name="filter[Bootfahren]" value="Bootfahren" <?php echo in_array("Bootfahren", $_GET['filter'] ?? []) ? 'checked' : ''; ?>>Bootfahren</label><br>
            <label><input type="checkbox" name="filter[Reiten]" value="Reiten" <?php echo in_array("Reiten", $_GET['filter'] ?? []) ? 'checked' : ''; ?>>Reiten</label><br>
            <label><input type="checkbox" name="filter[Schwimmen]" value="Schwimmen" <?php echo in_array("Schwimmen", $_GET['filter'] ?? []) ? 'checked' : ''; ?>>Schwimmen</label><br>
            <label><input type="checkbox" name="filter[Shoppingmöglichkeiten]" value="Shoppingmöglichkeiten" <?php echo in_array("Shoppingmöglichkeiten", $_GET['filter'] ?? []) ? 'checked' : ''; ?>>Shoppingmöglichkeiten</label><br>
            <label><input type="checkbox" name="filter[Tennis]" value="Tennis" <?php echo in_array("Tennis", $_GET['filter'] ?? []) ? 'checked' : ''; ?>>Tennis</label><br>
            <label><input type="checkbox" name="filter[Wandern]" value="Wandern" <?php echo in_array("Wandern", $_GET['filter'] ?? []) ? 'checked' : ''; ?>>Wandern</label><br>
            <h4>Lage</h4>
            <label><input type="checkbox" name="filter[Strandnähe]" value="Strandnähe" <?php echo in_array("Strandnähe", $_GET['filter'] ?? []) ? 'checked' : ''; ?>>Strandnähe</label><br>
            <label><input type="checkbox" name="filter[Strand]" value="Strand" <?php echo in_array("Strand", $_GET['filter'] ?? []) ? 'checked' : ''; ?>>Strand</label><br>
            <label><input type="checkbox" name="filter[Bergblick]" value="Bergblick" <?php echo in_array("Bergblick", $_GET['filter'] ?? []) ? 'checked' : ''; ?>>Bergblick</label><br>
            <label><input type="checkbox" name="filter[Meerblick]" value="Meerblick" <?php echo in_array("Meerblick", $_GET['filter'] ?? []) ? 'checked' : ''; ?>>Meerblick</label><br>
            <label><input type="checkbox" name="filter[Seeblick]" value="Seeblick" <?php echo in_array("Seeblick", $_GET['filter'] ?? []) ? 'checked' : ''; ?>>Seeblick</label><br>
            <br>
            <button type="submit">Suche aktualisieren</button> <!-- Button zum Absenden des Formulars -->
        </form>
    </div>
    <!-- Hauptinhalt -->
    <main>
        <?php include 'unterkunft_suchen.php'; ?> <!-- Einbinden der Datei 'unterkunft_suchen.php' für die Anzeige der Suchergebnisse -->
    </main>
    <footer>
        <p>Kontaktieren Sie uns für weitere Informationen:</p>
        <p>Telefon: 123-456-789</p>
        <p>Email: <a href="mailto:info@IhrFerienDomizil.com">info@IhrFerienDomizil.com</a></p>
    </footer>
</body>
</html>
