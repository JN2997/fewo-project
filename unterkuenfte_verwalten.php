<?php 
session_start(); 
include 'db_connect.php';
include 'auth_nav.php';
exclude_user_Roles (['guest', 'Mieter', 'Admin'], 'index.php');


		
// Dynamische Inhaltsanzeige
$page = isset($_GET['page']) ? $_GET['page'] : 'welcome';

$sql = "SELECT tag_wert FROM tags"; 
$result = $conn->query($sql);

$tags = array();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $tags[] = $row["tag_wert"];
    }
}

$conn->close();

function loadContent($page) {
    switch ($page) {
        case 'add':
            include 'haus_add.php';
            break;
        case 'edit':
            include 'haus_edit.php';
            break;
        case 'bookings':
            include 'haus_bookings.php';
            break;
        default:
            echo '<div class="welcome-message"><b><h3>Herzlich Willkommen im Verwaltungsbereich für Ihre Ferienhäuser.</h3> <br>
												  Sie können hier neue Ferienhäuser hinzufügen, welche dann vermietet werden, Ferienhäuser bearbeiten oder löschen und natürlich für jedes Ferienhaus die Buchungen einsehen.</b></div>';
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
	
		<!-- CSS Aufrufe -->
	<link rel="stylesheet" href="css/main.css">
		<!-- JavaScript Aufrufe -->
	<script>
        function navigate(page) {
            window.location.href = `?page=${page}`;
        }
    </script>
    
</head>

<body>
    <header>
        <div class="logo">
            <a href="index.php"><img src="img/Zeichnung-Flach.png" alt="Logo"></a>
        </div>
        <nav class="menu">
			<?php display_menu();?>
        </nav>
    </header>
<!-- Linke Sidebar -->
		    <div class="sidebar-left">
				<h2><span>Unterkünfte</span></h2> <br>
				<button onclick="navigate('add')">Hinzufügen</button>
				<button onclick="navigate('edit')">Bearbeiten</button>
				<button onclick="navigate('bookings')">Buchungen einsehen</button>
			</div>
<!-- Hauptinhalt -->
	<main>
		<?php loadContent($page, $tags); ?>
	</main>

	<footer>
	  <p>Kontaktieren Sie uns für weitere Informationen:</p>
	  <p>Telefon: 123-456-789</p>
	  <p>Email: info@IhrFerienDomizil.com</p>
	</footer>
	

</body>
</html>