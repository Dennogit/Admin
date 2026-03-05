<?php
header('Content-Type: application/json');
$conn = new mysqli("localhost", "root", "", "university_portal");
if ($conn->connect_error) {
    echo json_encode([]);
    exit;
}
$category = $_GET['category'] ?? 'students';
$table = ($category === 'staff') ? 'staff_notices' : 'students_notices';
$res = $conn->query("SELECT * FROM `$table` ORDER BY created_at DESC");
$notices = [];
while ($row = $res->fetch_assoc()) {
    $notices[] = $row;
}
echo json_encode($notices);
