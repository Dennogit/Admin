<?php
header('Content-Type: application/json');

$host = "localhost";
$user = "root";
$pass = "";
$dbname = "university_portal";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    echo json_encode([
        "status" => "error",
        "message" => "Database connection failed"
    ]);
    exit;
}

// Fetch notices meant for students or both
$sql = "SELECT id, title, image_path, created_at 
        FROM notices 
        WHERE audience = 'Students' OR audience = 'both'
        ORDER BY created_at DESC";

$result = $conn->query($sql);

$notices = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $notices[] = $row;
    }
}

echo json_encode([
    "status" => "success",
    "data" => $notices
]);

$conn->close();
?>