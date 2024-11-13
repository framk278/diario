<?php
// Conectar a la base de datos
$conn = new mysqli('localhost', 'root', '', 'imagenes'); // Ajusta las credenciales si es necesario

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Verificar si se ha enviado una nota
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['note'])) {
    $note = $_POST['note'];
    $note_name = $_POST['note_name']; // Obtener el nombre de la nota

    // Insertar la nota en la base de datos
    $stmt = $conn->prepare("INSERT INTO notes (note, note_name) VALUES (?, ?)"); // Asegúrate de que la tabla tenga la columna 'note_name'
    $stmt->bind_param("ss", $note, $note_name);
    $stmt->execute();
    $stmt->close();

    header("Location: index.php"); // Redirigir de vuelta al formulario
    exit();
}

$conn->close();
?>
