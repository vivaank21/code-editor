<?php
require_once "../includes/auth.php";
require_once "../includes/db.php";

$user_id = $_SESSION['user_id'];
$filename = $_GET['filename'];

$stmt = $conn->prepare("DELETE FROM files WHERE user_id=? AND filename=?");
$stmt->bind_param("is", $user_id, $filename);

echo $stmt->execute() ? "Deleted" : "Error";
?>
