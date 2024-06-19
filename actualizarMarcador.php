<?php
// CREATE TABLE markers (
//     id INT AUTO_INCREMENT PRIMARY KEY,
//     lat DECIMAL(10, 8),
//     lng DECIMAL(11, 8),
//     description VARCHAR(255)
// );

// Conexión a la base de datos
$host = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "mapas";

// Crear conexión
$conn = new mysqli($host, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Recibir datos del marcador
$id = $_POST['id']; // Asegúrate de enviar este dato desde el cliente
$lat = $_POST['lat'];
$lng = $_POST['lng'];

// Actualizar datos
$sql = "UPDATE markers SET lat = ?, lng = ? WHERE id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ddi", $lat, $lng, $id);

if ($stmt->execute()) {
    echo "Record updated successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$stmt->close();
$conn->close();
?>