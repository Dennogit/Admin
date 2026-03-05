<?php
header('Content-Type: application/json');

// Update these variables with your actual DB credentials
$host = 'localhost';
$db   = 'university_portal';
$user = 'root';
$pass = '';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    echo json_encode(['count' => 0]);
    exit;
}

// Adjust the WHERE clause if your schema is different
$sql = "SELECT COUNT(*) as count FROM notices ";
$result = $conn->query($sql);

if ($result && $row = $result->fetch_assoc()) {
    echo json_encode(['count' => (int)$row['count']]);
} else {
    echo json_encode(['count' => 0]);
}

$conn->close();
?>
