<?php
// Conectar a la base de datos
$conn = new mysqli('localhost', 'root', '', 'imagenes'); // Ajusta las credenciales si es necesario

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener imágenes
$result = $conn->query("SELECT name, description, file_path, upload_date FROM uploads"); // Asegúrate de que la columna 'description' esté en la base de datos

// Obtener notas
$notesResult = $conn->query("SELECT note, note_name, upload_date FROM notes"); // Asegúrate de que la tabla 'notes' tenga la columna 'note_name'
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Subir Archivos</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #e9ecef; /* Color de fondo más suave */
        }
        h1, h2 {
            color: #343a40; /* Color de texto más oscuro */
            text-align: center; /* Centrar títulos */
        }
        .form-container {
            display: flex; /* Usar flexbox para alinear los formularios */
            justify-content: space-around; /* Espacio entre los formularios */
            margin: 50px; /* Reducir margen alrededor del contenedor */
        }
        form {
            padding: 20px;
            background: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            max-width: 400px; /* Ancho máximo del formulario */
            flex: 1; /* Permitir que los formularios crezcan */
            margin: 0 3px; /* Reducir espaciado entre formularios */
        }
        input[type="text"], input[type="file"], button, textarea {
            width: 100%; /* Ancho completo */
            padding: 10px;
            margin: 10px 0; /* Espaciado entre elementos */
            border: 1px solid #ced4da;
            border-radius: 5px;
        }
        button {
            background-color: #007bff; /* Color de fondo del botón */
            color: white; /* Color del texto del botón */
            border: none; /* Sin borde */
            cursor: pointer; /* Cambiar cursor al pasar */
        }
        button:hover {
            background-color: #0056b3; /* Color al pasar el ratón */
        }
        .imagenes {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); /* Cuadrícula responsiva */
            gap: 10px; /* Espacio entre las imágenes */
            padding: 10px; /* Espacio interno */
        }
        .imagen {
            background: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            transition: transform 0.2s; /* Efecto de transición */
        }
        .imagen:hover {
            transform: scale(1.05); /* Efecto de aumento al pasar */
        }
        .imagen img {
            width: 100%; /* Ajustar al contenedor */
            height: auto;
            border-radius: 10px 10px 0 0; /* Bordes redondeados en la parte superior */
        }
        .fecha {
            font-size: 0.9em;
            color: #6c757d; /* Color de texto más suave */
        }
        .descripcion {
            font-size: 0.9em;
            color: #495057; /* Color de texto para la descripción */
        }
        .nota {
            background: #f8f9fa; /* Color de fondo para las notas */
            border-radius: 5px;
            padding: 10px;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <div>
            <h2>Subir Archivos</h2>
            <form action="upload.php" method="post" enctype="multipart/form-data">
                <input type="text" name="name" placeholder="Nombre" required>
                <input type="text" name="description" placeholder="Descripción"> <!-- Descripción no obligatoria -->
                <input type="file" name="file" required>
                <button type="submit">Subir</button>
            </form>
        </div>

        <div>
            <h2>Notas</h2>
            <form action="upload_note.php" method="post"> <!-- Formulario para subir notas -->
                <input type="text" name="note_name" placeholder="Nombre de la Nota" required> <!-- Campo para el nombre de la nota -->
                <textarea name="note" placeholder="Escribe tu nota aquí..." required></textarea>
                <button type="submit">Subir Nota</button>
            </form>
        </div>
    </div>

    <h2>Subidas Recientes</h2>
    <div class="imagenes">
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div class='imagen'>";
                echo "<img src='" . htmlspecialchars($row['file_path']) . "' alt='" . htmlspecialchars($row['name']) . "' />";
                echo "<h3>" . htmlspecialchars($row['name']) . "</h3>";
                echo "<p class='descripcion'>" . htmlspecialchars($row['description']) . "</p>"; // Mostrar la descripción
                echo "<p class='fecha'>Subido el: " . date('d/m/Y H:i', strtotime($row['upload_date'])) . "</p>";
                echo "</div>";
            }
        } else {
            echo "No hay imágenes subidas.";
        }
        ?>
    </div>

    <h2>Notas Recientes</h2>
    <div class="notas">
        <?php
        if ($notesResult->num_rows > 0) {
            while ($noteRow = $notesResult->fetch_assoc()) {
                echo "<div class='nota'>";
                echo "<h3>" . htmlspecialchars($noteRow['note_name']) . "</h3>"; // Mostrar el nombre de la nota
                echo "<p>" . htmlspecialchars($noteRow['note']) . "</p>";
                echo "<p class='fecha'>Subido el: " . date('d/m/Y H:i', strtotime($noteRow['upload_date'])) . "</p>";
                echo "</div>";
            }
        } else {
            echo "No hay notas subidas.";
        }
        ?>
    </div>

    <?php $conn->close(); ?>
</body>
</html>