<?php
require_once "../includes/auth.php";
require_once "../includes/db.php";

$user_id = $_SESSION['user_id'];
$old = $_POST['old'];
$new = $_POST['new'];

$stmt = $conn->prepare("UPDATE files SET filename=? WHERE user_id=? AND filename=?");
$stmt->bind_param("sis", $new, $user_id, $old);

echo $stmt->execute() ? "Renamed" : "Error";
?>
