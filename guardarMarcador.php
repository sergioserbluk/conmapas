<?php
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
$lat = $_POST['lat'];
$lng = $_POST['lng'];
$description = $_POST['description']; // Asegúrate de sanear estos valores para prevenir inyección SQL

// Insertar datos
$sql = "INSERT INTO markers (lat, lng, description) VALUES (?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("dds", $lat, $lng, $description);

if ($stmt->execute()) {
    echo "New record created successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$stmt->close();
$conn->close();
?>