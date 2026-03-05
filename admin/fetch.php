<?php
header('Content-Type: application/json');

// In production read from DB then echo json_encode($rows)
echo file_get_contents(__DIR__.'/mock.json');
?>