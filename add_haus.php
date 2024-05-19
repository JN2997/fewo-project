<?php
session_start();
include 'db_connect.php';

	// Funktion zum Hochladen von Bildern
	function upload_image($file, $haus_id, $img_typ, $conn) {
    $target_dir = "img/unterkuenfte/";
    $target_file = $target_dir . basename($file["name"]);
    $upload_ok = 1;
    $image_file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Überprüfen, ob es sich um eine Bilddatei handelt
    $check = getimagesize($file["tmp_name"]);
    if ($check === false) {
        echo "Datei ist kein Bild.";
        $upload_ok = 0;
    }

    // Überprüfen, ob Datei bereits existiert
    if (file_exists($target_file)) {
        echo "Entschuldigung, die Datei existiert bereits.";
        $upload_ok = 0;
    }

    // Überprüfen der Dateigröße
    if ($file["size"] > 5000000) { // 5MB Limit
        echo "Entschuldigung, Ihre Datei ist zu groß.";
        $upload_ok = 0;
    }

    // Erlaubte Dateiformate
    if ($image_file_type != "jpg" && $image_file_type != "png" && $image_file_type != "jpeg" && $image_file_type != "gif") {
        echo "Entschuldigung, nur JPG, JPEG, PNG & GIF Dateien sind erlaubt.";
        $upload_ok = 0;
    }

    // Prüfen, ob $upload_ok auf 0 gesetzt wurde
    if ($upload_ok == 0) {
        echo "Entschuldigung, Ihre Datei wurde nicht hochgeladen.";
    } else {
        if (move_uploaded_file($file["tmp_name"], $target_file)) {
            // Datei in die Datenbank einfügen
            $stmt = $conn->prepare("INSERT INTO img (HAUS_ID, img_typ, img_url) VALUES (?, ?, ?)");
            $stmt->bind_param("iss", $haus_id, $img_typ, $target_file);
            $stmt->execute();
            $stmt->close();
        } else {
            echo "Entschuldigung, es gab einen Fehler beim Hochladen Ihrer Datei.";
        }
    }
}

	// Überprüfen, ob der Benutzer eingeloggt ist
	if (!isset($_SESSION['USER_ID'])) {
		echo "Sie müssen eingeloggt sein, um diese Aktion auszuführen.";
		exit;
	}

	$user_id = $_SESSION['USER_ID'];

	// Haus-Daten verarbeiten
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		$name = $_POST['name'];
		$adresse = $_POST['adresse'];
		$land = $_POST['land'];
		$beschreibung = $_POST['beschreibung'];
		$personen = $_POST['personen'];
		$preis = $_POST['preis'];

		// Haus-Daten in die Datenbank einfügen
		$stmt = $conn->prepare("INSERT INTO haus (name, adresse, land, beschreibung, personen, preis, USER_ID) VALUES (?, ?, ?, ?, ?, ?, ?)");
		$stmt->bind_param("ssssiii", $name, $adresse, $land, $beschreibung, $personen, $preis, $user_id);
		if ($stmt->execute()) {
			$haus_id = $stmt->insert_id; // Die ID des gerade eingefügten Hauses erhalten

			// Bilder hochladen und in die Datenbank einfügen
			upload_image($_FILES['vorschaubild'], $haus_id, 'Vorschaubild', $conn);
			upload_image($_FILES['lageplan'], $haus_id, 'Lageplan', $conn);
			foreach ($_FILES['aussenansicht']['tmp_name'] as $key => $tmp_name) {
				$file_array = array(
					'name' => $_FILES['aussenansicht']['name'][$key],
					'tmp_name' => $_FILES['aussenansicht']['tmp_name'][$key],
					'size' => $_FILES['aussenansicht']['size'][$key],
					'error' => $_FILES['aussenansicht']['error'][$key],
					'type' => $_FILES['aussenansicht']['type'][$key],
				);
				upload_image($file_array, $haus_id, 'Außenansicht', $conn);
			}
			foreach ($_FILES['innenansicht']['tmp_name'] as $key => $tmp_name) {
				$file_array = array(
					'name' => $_FILES['innenansicht']['name'][$key],
					'tmp_name' => $_FILES['innenansicht']['tmp_name'][$key],
					'size' => $_FILES['innenansicht']['size'][$key],
					'error' => $_FILES['innenansicht']['error'][$key],
					'type' => $_FILES['innenansicht']['type'][$key],
				);
				upload_image($file_array, $haus_id, 'Innenansicht', $conn);
			}

			// Tags verarbeiten
			$tags = explode(',', $_POST['tags']);
			foreach ($tags as $tag) {
				$tag = trim($tag);
				if (!empty($tag)) {
					// TAG_ID aus der Tabelle tags holen
					$stmt = $conn->prepare("SELECT TAG_ID FROM tags WHERE tag_wert = ?");
					$stmt->bind_param("s", $tag);
					$stmt->execute();
					$stmt->bind_result($tag_id);
					$stmt->fetch();
					$stmt->close();

					if (!empty($tag_id)) {
						// In die Tabelle tag_haus_relation einfügen
						$stmt = $conn->prepare("INSERT INTO tag_haus_relation (HAUS_ID, TAG_ID) VALUES (?, ?)");
						$stmt->bind_param("ii", $haus_id, $tag_id);
						$stmt->execute();
						$stmt->close();
					}
				}
			}

			// Erfolgspopup anzeigen und weiterleiten
			echo "<script>alert('Das Haus wurde erfolgreich hinzugefügt.'); window.location.href='unterkuenfte.php';</script>";
		} else {
			// Fehlerpopup anzeigen und weiterleiten
			echo "<script>alert('Fehler beim Hinzufügen des Hauses.'); window.location.href='unterkuenfte.php';</script>";
		}
		$stmt->close();
	}

$conn->close();
?>