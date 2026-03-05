<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../Logins/login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize input
    $title = trim($_POST['title'] ?? '');
    $meta = trim($_POST['meta'] ?? '');
    $details = trim($_POST['details'] ?? '');
    $audience = trim($_POST['audience'] ?? '');
    $image_path = '';

    // Validate
    if (empty($title) || empty($meta) || empty($details) || empty($audience)) {
        echo "All fields are required.";
        exit;
    }

    // Handle image
    if (!empty($_FILES['image']['name'])) {
        $uploadDir = './uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $filename = basename($_FILES['image']['name']);
        $targetPath = $uploadDir . time() . '_' . preg_replace('/[^a-zA-Z0-9_.]/', '_', $filename);

        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
            $image_path = $targetPath;
        } else {
            echo "Image upload failed.";
            exit;
        }
    }

    // Database insert
    require_once 'db.php'; // Make sure this connects successfully
    $db = new mysqli('localhost', 'root', '', 'university_portal');

    if ($db->connect_errno) {
        echo "Database connection failed: " . $db->connect_error;
        exit;
    }

    $stmt = $db->prepare("INSERT INTO notices (title, meta, details, audience, image_path) VALUES (?, ?, ?, ?, ?)");
    if (!$stmt) {
        echo "Prepare failed: " . $db->error;
        exit;
    }

    $stmt->bind_param("sssss", $title, $meta, $details, $audience, $image_path);

    if ($stmt->execute()) {
        header("Location: notic.php"); // or your dashboard file
        exit;
    } else {
        echo "Insert failed: " . $stmt->error;
    }

    $stmt->close();
    $db->close();
} else {
    echo "Invalid request method.";
}
?>
