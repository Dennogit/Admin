<?php
header('Content-Type: application/json');
$conn = new mysqli("localhost", "root", "", "university_portal");
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'error' => 'DB connection failed']);
    exit;
}

$title = $_POST['addTitle'] ?? '';
$meta = $_POST['addMeta'] ?? '';
$details = $_POST['addDetails'] ?? '';
$audience = $_POST['addAudience'] ?? '';
$img = '';

if (isset($_FILES['addImg']) && $_FILES['addImg']['error'] == UPLOAD_ERR_OK) {
    $ext = pathinfo($_FILES['addImg']['name'], PATHINFO_EXTENSION);
    $imgName = uniqid('notice_', true) . '.' . $ext;
    $target = '../uploads/' . $imgName;
    if (!is_dir('../uploads')) mkdir('../uploads', 0777, true);
    if (move_uploaded_file($_FILES['addImg']['tmp_name'], $target)) {
        $img = 'uploads/' . $imgName;
    }
}

$tables = [];
if ($audience == 'students') $tables[] = 'students_notices';
elseif ($audience == 'staff') $tables[] = 'staff_notices';
elseif ($audience == 'both') $tables = ['students_notices', 'staff_notices'];

$success = false;
foreach ($tables as $table) {
    $stmt = $conn->prepare("INSERT INTO `$table` (title, meta, details, img, created_at) VALUES (?, ?, ?, ?, NOW())");
    $stmt->bind_param("ssss", $title, $meta, $details, $img);
    $success = $stmt->execute();
    $stmt->close();
}
echo json_encode(['success' => $success]);
