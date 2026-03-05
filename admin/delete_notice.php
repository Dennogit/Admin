<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
  echo json_encode(['status' => 'error', 'message' => 'Not authorized']);
  exit;
}

require_once 'db.php';

$data = json_decode(file_get_contents("php://input"), true);
$id = intval($data['id'] ?? 0);

if ($id > 0) {
  $stmt = $conn->prepare("DELETE FROM notices WHERE id = ?");
  $stmt->bind_param("i", $id);

  if ($stmt->execute()) {
    echo json_encode(['status' => 'success']);
  } else {
    echo json_encode(['status' => 'error', 'message' => 'Delete failed.']);
  }

  $stmt->close();
} else {
  echo json_encode(['status' => 'error', 'message' => 'Invalid ID.']);
}
$conn->close();
