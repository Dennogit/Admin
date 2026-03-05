<?php
// VERY bare‑bones – sanitise & validate properly!
if ($_SERVER['REQUEST_METHOD']==='POST') {
    $title = $_POST['title'] ?? '';
    $body  = $_POST['body']  ?? '';
    $cat   = $_POST['category'] ?? 'students';
    $img   = $_POST['image'] ?? '';

    // Example: insert into DB here
    // $pdo->prepare("INSERT INTO notices ...")->execute([...]);

    echo "OK";
}
?>