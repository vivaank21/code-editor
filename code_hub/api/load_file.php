<?php
require_once "../includes/auth.php";
require_once "../includes/db.php";

$user_id = $_SESSION['user_id'];
$filename = $_GET['filename'];

$stmt = $conn->prepare("SELECT html, css, js FROM files WHERE user_id=? AND filename=?");
$stmt->bind_param("is", $user_id, $filename);
$stmt->execute();
$result = $stmt->get_result()->fetch_assoc();

echo json_encode($result);
?>
