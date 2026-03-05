<?php
session_start();
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
    exit;
}

require_once 'db.php'; // make sure this defines $conn

$title = trim($_POST['title'] ?? '');
$meta = trim($_POST['meta'] ?? '');
$audience = trim($_POST['audience'] ?? 'Both');
$image_path = ''; // default empty

// Validate required fields
if (empty($title) || empty($meta) || empty($audience)) {
    echo json_encode(['status' => 'error', 'message' => 'All fields are required']);
    exit;
}

// ================== HANDLE IMAGE UPLOAD ==================
if (!empty($_FILES['image']['name'])) {

    $uploadDir = 'uploads/'; // MUST have trailing slash

    // Create folder if it doesn't exist
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    // Validate file type
    $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];
    $fileType = $_FILES['image']['type'];

    if (!in_array($fileType, $allowedTypes)) {
        echo json_encode(['status' => 'error', 'message' => 'Only JPG, PNG, WEBP allowed']);
        exit;
    }

    // Generate unique file name
    $imageName = time() . '_' . preg_replace("/[^a-zA-Z0-9.]/", "", $_FILES['image']['name']);
    $targetFile = $uploadDir . $imageName;

    if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
        $image_path = $targetFile; // SAVE CORRECT PATH
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Image upload failed']);
        exit;
    }
}

// ================== INSERT INTO DATABASE ==================
$stmt = $conn->prepare("INSERT INTO notices (title, meta, audience, image_path) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $title, $meta, $audience, $image_path);

if ($stmt->execute()) {
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Database insert failed']);
}

$stmt->close();
$conn->close();
?>