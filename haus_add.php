<?php 
include 'db_connect.php';

$sql = "SELECT tag_wert FROM tags"; // Adjust this query to your table structure
$result = $conn->query($sql);

$tags = array();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $tags[] = $row["tag_wert"];

    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ihr Ferien Domizil</title>
	
		<!-- CSS Aufrufe -->
	<link rel="stylesheet" href="css/main.css">
	
		<!-- JavaScript Aufrufe -->
	<script>
		const dbTags = <?php echo json_encode($tags); ?>; // Konvertiert das PHP-Array $tags in ein JSON-Format und speichert es in einer JavaScript-Variablen dbTags.
		console.log(dbTags); // Überprüfen, ob die Tags korrekt übergeben werden
	</script>
	
	<script src="js/tags_add_haus.js" defer></script>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include 'db_connect.php';

    function upload_image($file, $haus_id, $img_typ, $conn) {
        $target_dir = "img/unterkuenfte/";
        $target_file = $target_dir . basename($file["name"]);
        $upload_ok = 1;
        $image_file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        $check = getimagesize($file["tmp_name"]);
        if ($check === false) {
            echo "Datei ist kein Bild.";
            $upload_ok = 0;
        }

        if (file_exists($target_file)) {
            echo "Entschuldigung, die Datei existiert bereits.";
            $upload_ok = 0;
        }

        if ($file["size"] > 5000000) { // 5MB Limit
            echo "Entschuldigung, Ihre Datei ist zu groß.";
            $upload_ok = 0;
        }

        if ($image_file_type != "jpg" && $image_file_type != "png" && $image_file_type != "jpeg" && $image_file_type != "gif") {
            echo "Entschuldigung, nur JPG, JPEG, PNG & GIF Dateien sind erlaubt.";
            $upload_ok = 0;
        }

        if ($upload_ok == 0) {
            echo "Entschuldigung, Ihre Datei wurde nicht hochgeladen.";
        } else {
            if (move_uploaded_file($file["tmp_name"], $target_file)) {
                $stmt = $conn->prepare("INSERT INTO img (HAUS_ID, img_typ, img_url) VALUES (?, ?, ?)");
                $stmt->bind_param("iss", $haus_id, $img_typ, $target_file);
                $stmt->execute();
                $stmt->close();
            } else {
                echo "Entschuldigung, es gab einen Fehler beim Hochladen Ihrer Datei.";
            }
        }
    }

    if (!isset($_SESSION['USER_ID'])) {
        echo "Sie müssen eingeloggt sein, um diese Aktion auszuführen.";
        exit;
    }

    $user_id = $_SESSION['USER_ID'];

    $name = $_POST['name'];
    $adresse = $_POST['adresse'];
    $land = $_POST['land'];
    $beschreibung = $_POST['beschreibung'];
    $personen = $_POST['personen'];
    $preis = $_POST['preis'];

    $stmt = $conn->prepare("INSERT INTO haus (name, adresse, land, beschreibung, personen, preis, USER_ID) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssiii", $name, $adresse, $land, $beschreibung, $personen, $preis, $user_id);
    if ($stmt->execute()) {
        $haus_id = $stmt->insert_id;

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

        $tags = explode(',', $_POST['tags']);
        foreach ($tags as $tag) {
            $tag = trim($tag);
            if (!empty($tag)) {
                $stmt = $conn->prepare("SELECT TAG_ID FROM tags WHERE tag_wert = ?");
                $stmt->bind_param("s", $tag);
                $stmt->execute();
                $stmt->bind_result($tag_id);
                $stmt->fetch();
                $stmt->close();

                if (!empty($tag_id)) {
                    $stmt = $conn->prepare("INSERT INTO tag_haus_relation (HAUS_ID, TAG_ID) VALUES (?, ?)");
                    $stmt->bind_param("ii", $haus_id, $tag_id);
                    $stmt->execute();
                    $stmt->close();
                }
            }
        }

        echo "<script>alert('Das Haus wurde erfolgreich hinzugefügt.'); window.location.href='unterkuenfte_verwalten.php';</script>";
    } else {
        echo "<script>alert('Fehler beim Hinzufügen des Hauses.'); window.location.href='unterkuenfte_verwalten.php';</script>";
    }
    $stmt->close();

    $conn->close();
}
?>

<div class="add_haus">
    <form action="unterkuenfte_verwalten.php?page=add" method="post" enctype="multipart/form-data" class="form-horizontal">
        <div class="form-group">
            <input type="text" name="name" placeholder="Name des Ferienhauses" required>
            <input type="text" name="adresse" placeholder="Adresse" required>
        </div>
        <div class="form-group">
            <input type="text" name="land" placeholder="Land" required>
            <input type="text" name="beschreibung" placeholder="Beschreibung" required>
        </div>
        <div class="form-group">
            <input type="number" name="personen" placeholder="Maximale Personenanzahl" required>
            <input type="number" name="preis" placeholder="Preis pro Nacht" step="1" required>
        </div>
        <div class="form-group">
            <div class="upload_section">
                <label for="vorschaubild">Vorschaubild</label>
                <input type="file" id="vorschaubild" name="vorschaubild" accept="image/*" required>
            </div>
            <div class="upload_section">
                <label for="lageplan">Lageplan</label>
                <input type="file" id="lageplan" name="lageplan" accept="image/*" required>
            </div>
            <div class="upload_section">
                <label for="aussenansicht">Außenansicht</label>
                <input type="file" id="aussenansicht" name="aussenansicht[]" accept="image/*" multiple required>
            </div>
            <div class="upload_section">
                <label for="innenansicht">Innenansicht</label>
                <input type="file" id="innenansicht" name="innenansicht[]" accept="image/*" multiple required>
            </div>
        </div>
        <div class="form-group tags-input-container">
            <div class="tags-input">
                <label for="tags">Tags</label>
                <div id="tags-container" class="tags-container"></div>
            </div>
            <select id="tags-select" multiple size="10">
                <!-- Optionen werden hier von JavaScript hinzugefügt -->
            </select>
            <input type="hidden" name="tags" id="hidden-tags">
        </div>
        <button type="submit">Haus hinzufügen</button>
    </form>
</div>