<?php
// --- DB CONNECTION ---
$host = "localhost";
$user = "root";
$pass = ""; 
$dbname = "university_portal"; 

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// --- VALIDATE & SANITIZE ---
$title = isset($_POST['title']) ? trim($_POST['title']) : '';
$meta = isset($_POST['meta']) ? htmlspecialchars(trim($_POST['meta']), ENT_QUOTES, 'UTF-8') : '';
$details = isset($_POST['details']) ? trim($_POST['details']) : '';
$audience = isset($_POST['audience']) ? $_POST['audience'] : '';
$imagePath = null;

if (!$title || !$meta || !$details || !$audience) {
    die("Missing required fields");
}

// --- HANDLE IMAGE UPLOAD ---
if (!empty($_FILES['image']['name'])) {
    $targetDir = "./uploads/";
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    $imgName = basename($_FILES["image"]["name"]);
    $imgExt = strtolower(pathinfo($imgName, PATHINFO_EXTENSION));
    $allowed = ["jpg", "jpeg", "png", "gif"];

    if (in_array($imgExt, $allowed)) {
        $newName = uniqid("notice_", true) . "." . $imgExt;
        $targetFile = $targetDir . $newName;
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
            $imagePath = $targetFile;
        } else {
            echo "Image upload failed.";
        }
    } else {
        echo "Unsupported file type.";
    }
}

// --- INSERT INTO DATABASE ---
$stmt = $conn->prepare("INSERT INTO notices (title, meta, details, audience, image_path) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("sssss", $title, $meta, $details, $audience, $imagePath);

if ($stmt->execute()) {
    // Notice added successfully, redirect to notice.php
    header("Location: notic.php");
    exit();
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
