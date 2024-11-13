<?php
// Conectar a la base de datos
$conn = new mysqli('localhost', 'root', '', 'imagenes'); // Ajusta las credenciales si es necesario

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Verificar si se ha subido un archivo
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['file'])) {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $file = $_FILES['file'];

    // Validar el archivo
    if ($file['error'] === UPLOAD_ERR_OK) {
        $fileType = $file['type'];
        $fileName = basename($file['name']);
        $uploadDir = 'uploads/'; // Asegúrate de que esta carpeta exista y tenga permisos de escritura
        $filePath = $uploadDir . uniqid() . '-' . $fileName;

        // Mover el archivo a la carpeta de destino
        if (move_uploaded_file($file['tmp_name'], $filePath)) {
            // Insertar información en la base de datos
            $stmt = $conn->prepare("INSERT INTO uploads (name, description, file_path, file_type) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $name, $description, $filePath, $fileType);
            $stmt->execute();
            $stmt->close();
            header("Location: index.php"); // Redirigir de vuelta al formulario
            exit();
        } else {
            echo "Error al mover el archivo.";
        }
    } else {
        echo "Error en la subida del archivo.";
    }
}

$conn->close();
?>