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
            max-width: 150px;
            margin-right: 20px;
            border-radius: 15px; /* Abgerundete Ecken für das Bild */
        }
        .container .text {
            flex: 1;
        }
    </style>
</head>
<body>
    <div class="container">
        <img src="https://via.placeholder.com/150" alt="Beispielbild">
        <div class="text">
            <h2>Überschrift 1</h2>
            <p>Platz für Tags.</p>
        </div>
    </div>
    <div class="container">
        <img src="https://via.placeholder.com/150" alt="Beispielbild">
        <div class="text">
            <h2>Überschrift 2</h2>
            <p>Platz für Tags.</p>
        </div>
    </div>
    <div class="container">
        <img src="https://via.placeholder.com/150" alt="Beispielbild">
        <div class="text">
            <h2>Überschrift 3</h2>
            <p>Platz für Tags.</p>
        </div>
    </div>
</body>
</html>