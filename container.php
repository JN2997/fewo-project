<?php session_start(); 
include 'db_connect.php'; ?>

<!DOCTYPE html>
<html lang="de">
<head>
    <style>
        .container {
            display: flex;
            align-items: center;
            margin: 20px;
            padding: 10px;
            border: 1px solid #ccc;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            width: 80%;
            max-width: 800px;
            border-radius: 15px; /* Abgerundete Ecken für den Container */
        }
        .container img {
            width: 150px;
            height: 150px;
            object-fit: cover; /* Bild skalieren und zuschneiden, um die Größe zu füllen */
            margin-right: 20px;
            transition: transform 0.3s; /* Transition-Effekt */
            border-radius: 15px; /* Abgerundete Ecken für das Bild */
        }
        .container .text {
            flex: 1;
        }
    </style>
</head>
<body>

    <h1>Einträge</h1>

<?php
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo '<div class="container">';
        echo '<img src="' . $row["image_url"] . '" alt="Bild">';
        echo '<div class="text">' . $row["text"] . '</div>';
        echo '</div>';
    }
} else {
    echo "Keine Einträge gefunden.";
}
$conn->close();
?>

<BR>
<p>Beispiel Container...</p>
<BR>
<div class="container">
        <img src="img/Ferienhaus1.jpeg" alt="Beispielbild">
        <div class="text">
            <h2>Überschrift 1</h2>
            <p>Platz für Tags.</p>
        </div>
    </div>
    <div class="container">
        <img src="img/Ferienhaus2.jpeg" alt="Beispielbild">
        <div class="text">
            <h2>Überschrift 2</h2>
            <p>Platz für Tags.</p>
        </div>
    </div>
    <div class="container">
        <img src="img/Ferienhaus3.jpeg" alt="Beispielbild">
        <div class="text">
            <h2>Überschrift 3</h2>
            <p>Platz für Tags.</p>
        </div>
    </div>

    <script>
document.querySelectorAll('.container img').forEach(img => {
    img.addEventListener('click', () => {
        if (img.style.transform === 'scale(1.5)') {
            img.style.transform = 'scale(1)';
        } else {
            img.style.transform = 'scale(1.5)';
        }
        img.style.transition = 'transform 0.3s';
    });
});
</script>

</body>
</html>