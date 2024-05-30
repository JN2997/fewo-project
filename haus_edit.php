<?php
include 'db_connect.php';

// Überprüfen, ob der Benutzer eingeloggt ist
if (!isset($_SESSION['USER_ID'])) {
    echo "Sie müssen eingeloggt sein, um diese Aktion auszuführen.";
    exit;
}

$user_id = $_SESSION['USER_ID'];

// Ferienhausdaten aktualisieren
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update'])) {
    $haus_id = $_POST['haus_id'];
    $name = $_POST['name'];
    $beschreibung = $_POST['beschreibung'];
    $personen = $_POST['personen'];
    $preis = $_POST['preis'];

    $stmt = $conn->prepare("UPDATE haus SET name=?, beschreibung=?, personen=?, preis=? WHERE HAUS_ID=? AND USER_ID=?");
    $stmt->bind_param("ssiiii", $name, $beschreibung, $personen, $preis, $haus_id, $user_id);
    if ($stmt->execute()) {
        echo "<script>alert('Das Haus wurde erfolgreich aktualisiert.'); window.location.href='unterkuenfte_verwalten.php?page=edit';</script>";
    } else {
        echo "<script>alert('Fehler beim Aktualisieren des Hauses.'); window.location.href='unterkuenfte_verwalten.php?page=edit';</script>";
    }
    $stmt->close();
}

// Ferienhaus löschen
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete'])) {
    $haus_id = $_POST['haus_id'];
    
    try {
        // Bilder löschen
        $stmt = $conn->prepare("SELECT img_url FROM img WHERE HAUS_ID=?");
        $stmt->bind_param("i", $haus_id);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            if (file_exists($row['img_url'])) {
                unlink($row['img_url']); // Löscht die Datei vom Server
            }
        }
        $stmt->close();

        // Bilddatensätze löschen
        $stmt = $conn->prepare("DELETE FROM img WHERE HAUS_ID=?");
        $stmt->bind_param("i", $haus_id);
        $stmt->execute();
        $stmt->close();

        // Tag-Zuordnungen löschen
        $stmt = $conn->prepare("DELETE FROM tag_haus_relation WHERE HAUS_ID=?");
        $stmt->bind_param("i", $haus_id);
        $stmt->execute();
        $stmt->close();

        // Haus löschen
        $stmt = $conn->prepare("DELETE FROM haus WHERE HAUS_ID=? AND USER_ID=?");
        $stmt->bind_param("ii", $haus_id, $user_id);
        if ($stmt->execute()) {
            echo "<script>alert('Das Haus wurde erfolgreich gelöscht.'); window.location.href='unterkuenfte_verwalten.php?page=edit';</script>";
        } else {
            echo "<script>alert('Fehler beim Löschen des Hauses.'); window.location.href='unterkuenfte_verwalten.php?page=edit';</script>";
        }
        $stmt->close();
    } catch (mysqli_sql_exception $e) {
        echo "<script>alert('Fehler: Es liegen aktuell noch Buchungen für Ihr Ferienhaus vor, bitte nehmen Sie Kontakt mit uns auf.'); window.location.href='unterkuenfte_verwalten.php?page=edit';</script>";
    }
}
// Haus-Daten abrufen
if (isset($_GET['haus_id'])) {
    $haus_id = $_GET['haus_id'];
    $stmt = $conn->prepare("SELECT * FROM haus WHERE HAUS_ID=? AND USER_ID=?");
    $stmt->bind_param("ii", $haus_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $haus = $result->fetch_assoc();
    $stmt->close();

    // Bilder abrufen
    $stmt = $conn->prepare("SELECT img_url, img_typ FROM img WHERE HAUS_ID=?");
    $stmt->bind_param("i", $haus_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $images = [];
    while ($row = $result->fetch_assoc()) {
        $images[$row['img_typ']][] = $row['img_url'];
    }
    $stmt->close();

    // Tags abrufen
    $stmt = $conn->prepare("SELECT t.tag_wert FROM tags t JOIN tag_haus_relation thr ON t.TAG_ID = thr.TAG_ID WHERE thr.HAUS_ID=?");
    $stmt->bind_param("i", $haus_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $tags = [];
    while ($row = $result->fetch_assoc()) {
        $tags[] = $row['tag_wert'];
    }
    $stmt->close();
} else {
    // Liste der Häuser anzeigen
    $stmt = $conn->prepare("SELECT * FROM haus WHERE USER_ID=?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
	echo '<div class="haus_list"><h3>Wählen Sie ein Haus zum Bearbeiten:</h3>';

	if ($result->num_rows > 0) {
		echo '<ul>';
		while ($row = $result->fetch_assoc()) {
			echo '<li><a href="?page=edit&haus_id=' . $row['HAUS_ID'] . '">' . htmlspecialchars($row['name']) . '</a></li>';
		}
		
	} else {
		echo '<li>Keine Häuser vorhanden.</li>';
	}
	echo '</ul>';
	echo '</div>';
    $stmt->close();
    $conn->close();
    exit;
}
?>

<div class="edit_haus">
    <form action="unterkuenfte_verwalten.php?page=edit" method="post" class="form-horizontal">
        <input type="hidden" name="haus_id" value="<?php echo $haus['HAUS_ID']; ?>">
        <div class="form-group">
            <input type="text" name="name" value="<?php echo htmlspecialchars($haus['name']); ?>" placeholder="Name des Ferienhauses" required>
        </div>
        <div class="form-group">
            <textarea name="beschreibung" placeholder="Beschreibung" required><?php echo htmlspecialchars($haus['beschreibung']); ?></textarea>
        </div>
        <div class="form-group">
            <input type="number" name="personen" value="<?php echo htmlspecialchars($haus['personen']); ?>" placeholder="Maximale Personenanzahl" required>
        </div>
        <div class="form-group">
            <input type="number" name="preis" value="<?php echo htmlspecialchars($haus['preis']); ?>" placeholder="Preis pro Nacht" step="1" required>
        </div>
        
        <button type="submit" name="update">Haus aktualisieren</button>
        <button type="submit" name="delete">Haus löschen</button>
    </form>
</div>

<div class="haus_details">
	<p><b>Bilder, Tags, Land und Adresse eines Ferienhauses können nicht geändert werden. Hierzu müssen Sie ein neues Ferienhaus anlegen und das alte Löschen.</b></p>
    <h3>Tags:</h3> <?php echo implode(", ", array_map('htmlspecialchars', $tags)); ?>
    <h3>Adresse:</h3><?php echo htmlspecialchars($haus['adresse']); ?>
    <h3>Land:</h3> <?php echo htmlspecialchars($haus['land']); ?>
    <div style="display: flex; flex-wrap: wrap; gap: 10px;">
        <div>
            <h4>Vorschaubild:</h4>
            <?php if (isset($images['Vorschaubild'])): ?>
                <img src="<?php echo $images['Vorschaubild'][0]; ?>" alt="Vorschaubild" style="max-width: 150px; max-height: 150px;">
            <?php endif; ?>
        </div>
        <div>
            <h4>Lageplan:</h4>
            <?php if (isset($images['Lageplan'])): ?>
                <img src="<?php echo $images['Lageplan'][0]; ?>" alt="Lageplan" style="max-width: 150px; max-height: 150px;">
            <?php endif; ?>
        </div>
        <div>
            <h4>Außenansicht:</h4>
            <?php if (isset($images['Außenansicht'])): ?>
                <?php foreach ($images['Außenansicht'] as $url): ?>
                    <img src="<?php echo $url; ?>" alt="Außenansicht" style="max-width: 150px; max-height: 150px;">
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <div>
            <h4>Innenansicht:</h4>
            <?php if (isset($images['Innenansicht'])): ?>
                <?php foreach ($images['Innenansicht'] as $url): ?>
                    <img src="<?php echo $url; ?>" alt="Innenansicht" style="max-width: 150px; max-height: 150px;">
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>
