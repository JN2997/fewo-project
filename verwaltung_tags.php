<?php
session_start();
include 'db_connect.php';
include 'auth_nav.php';
exclude_user_Roles(['Vermieter', 'guest', 'Mieter'], 'index.php');

// Funktion zum Abrufen aller Tags
function get_all_tags($conn) {
    $sql = "SELECT TAG_ID, tag_wert FROM tags ORDER BY TAG_ID";
    $result = $conn->query($sql);
    return $result;
}

// Funktion zum Hinzufügen eines neuen Tags
function add_tag($conn, $tag_wert) {
    $sql = "INSERT INTO tags (tag_wert) VALUES (?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $tag_wert);
    return $stmt->execute();
}

// Funktion zum Aktualisieren eines Tags
function update_tag($conn, $tag_id, $tag_wert) {
    $sql = "UPDATE tags SET tag_wert = ? WHERE TAG_ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $tag_wert, $tag_id);
    return $stmt->execute();
}

// Funktion zum Löschen eines Tags
function delete_tag($conn, $tag_id) {
    $sql = "DELETE FROM tags WHERE TAG_ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $tag_id);
    return $stmt->execute();
}

// Handling der Formulareingaben
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'add':
                add_tag($conn, $_POST['tag_wert']);
                break;
            case 'update':
                update_tag($conn, $_POST['tag_id'], $_POST['tag_wert']);
                break;
            case 'delete':
                delete_tag($conn, $_POST['tag_id']);
                break;
        }
    }
}
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
</head>
<body>
    <header>
        <div class="logo">
            <a href="index.php"><img src="img/Zeichnung-Flach.png" alt="Logo"></a>
        </div>
        <nav class="menu">
            <?php display_menu(); ?>
        </nav>
    </header>
    <!-- Hauptinhalt -->
    <main>
        <!-- Tabelle mit bestehenden Tags und Formular zum Hinzufügen eines neuen Tags -->
        <table>
			<caption><h1>Tag-Verwaltung</h1></caption>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tag</th>
                    <th style="width: 200px;">Aktionen</th>
                </tr>
            </thead>
           <tbody>
				<!-- Formular zum Hinzufügen eines neuen Tags -->
				<tr>
					<form method="post" action="">
						<input type="hidden" name="action" value="add">
						<td></td>
						<td>
							<input type="text" name="tag_wert" required>
						</td>
						<td>
							<button type="submit">Hinzufügen</button>
						</td>
					</form>
				</tr>

				<?php
				$tags = get_all_tags($conn);
				if ($tags->num_rows > 0) {
					while($row = $tags->fetch_assoc()) {
						echo "<tr>";
						echo "<td>" . $row['TAG_ID'] . "</td>";
						// Formular für die Aktualisierung des Tags
						echo "<form method='post' action=''>";
						echo "<input type='hidden' name='action' value='update'>";
						echo "<input type='hidden' name='tag_id' value='" . $row['TAG_ID'] . "'>";
						echo "<td><input type='text' name='tag_wert' value='" . $row['tag_wert'] . "' required></td>";
						echo "<td>";
						// Aktualisieren Button
						echo "<button type='submit' onclick='return confirm(\"Sind Sie sicher, dass Sie diesen Tag aktualisieren möchten?\");'>Aktualisieren</button>";
						echo "</form>";
						// Formular für das Löschen des Tags
						echo "<form method='post' action='' style='display:inline;'>";
						echo "<input type='hidden' name='action' value='delete'>";
						echo "<input type='hidden' name='tag_id' value='" . $row['TAG_ID'] . "'>";
						// Löschen Button
						echo "<button type='submit' onclick='return confirm(\"Sind Sie sicher, dass Sie diesen Tag löschen möchten?\");'>Löschen</button>";
						echo "</form>";
						echo "</td>";
						echo "</tr>";
					}
				} else {
					echo "<tr><td colspan='3'>Keine Tags gefunden</td></tr>";
				}
				?>
			</tbody>
        </table>
    </main>
    <footer>
        <p>Kontaktieren Sie uns für weitere Informationen:</p>
        <p>Telefon: 123-456-789</p>
        <p>Email: <a href="mailto:info@IhrFerienDomizil.com">info@IhrFerienDomizil.com</a></p>
    </footer>
</body>
</html>
