<?php

// Nutzung des Skripts: 
// Einbinden des Codes mit "include 'auth_nav.php';" 
// im Header einfach mit "<?php display_menu(); ? >"
// Nutzer können von Seiten ausgeschlossen werden mit "exclude_user_roles(['Vermieter', 'guest', 'Mieter', 'Admin'], 'index.php');"
// Es können auch andere Seiten angegeben werden, aber meistens wird es die index.php sein

// Funktion zur Überprüfung der Benutzerrolle
function get_user_role() {
    // Überprüfen, ob der Benutzer eingeloggt ist und eine Rolle zugewiesen wurde
    if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
        return $_SESSION['role'] ?? 'guest'; // Rückgabe der Benutzerrolle oder 'guest' falls keine Rolle vorhanden
    } else {
        return 'guest'; // Rückgabe der Rolle 'guest' wenn nicht eingeloggt
    }
}

// Menü-Anzeige basierend auf der Benutzerrolle
function display_menu() {
    $role = get_user_role(); // Benutzerrolle abrufen
    
    if ($role === 'guest') {
        echo '
        <!-- Menü für nicht eingeloggte Benutzer -->
        <button onclick="window.open(\'unterkunft_vermieten_unlogged.php\', \'_blank\');">Unterkunft vermieten</button>
        <button onclick="openPopupanmelden()">Anmelden</button>
        <button onclick="openPopupregistrieren()">Registrieren</button>
        ';
        include 'popupanmelden.php'; // Einbinden des Anmelde-Popups
        include 'popupregistrieren.php'; // Einbinden des Registrierungs-Popups
    } else {
        switch ($role) {
            case 'Mieter':
                echo '
                <!-- Menü für Mieter -->
                <button onclick="window.open(\'meine_buchungen.php\', \'_blank\');">Meine Buchungen</button>
                <button onclick="window.open(\'profil.php\', \'_blank\');">Profil</button>
                <button onclick="window.open(\'logout.php\', \'_self\');">Logout</button>
                ';
                break;
            case 'Vermieter':
                echo '
                <!-- Menü für Vermieter -->
                <button onclick="window.open(\'meine_buchungen.php\', \'_blank\');">Meine Buchungen</button>
                <button onclick="window.open(\'unterkuenfte_verwalten.php\', \'_blank\');">Unterkünfte verwalten</button>
                <button onclick="window.open(\'profil.php\', \'_blank\');">Profil</button>
                <button onclick="window.open(\'logout.php\', \'_self\');">Logout</button>
                ';
                break;
            case 'Admin':
                echo '
                <!-- Menü für Admin -->
                <button onclick="window.open(\'verwaltung_user.php\', \'_blank\');">User-Verwaltung</button>
                <button onclick="window.open(\'verwaltung_haeuser.php\', \'_blank\');">Haus-Verwaltung</button>
                <button onclick="window.open(\'verwaltung_buchungen.php\', \'_blank\');">Buchungs-Verwaltung</button>
                <button onclick="window.open(\'verwaltung_tags.php\', \'_blank\');">Tag-Verwaltung</button>
                <button onclick="window.open(\'logout.php\', \'_self\');">Logout</button>
                ';
                break;
        }
    }
}

// Funktion zum Ausschließen von bestimmten Benutzern auf bestimmten Seiten
function exclude_user_roles($excluded_roles, $redirect_page = 'index.php') {
    $role = get_user_role(); // Benutzerrolle abrufen
    // Überprüfen, ob die Benutzerrolle in der Liste der ausgeschlossenen Rollen ist
    if (in_array($role, $excluded_roles)) {
        header("Location: $redirect_page"); // Umleiten auf die angegebene Seite
        exit(); 
    }
}
?>
