<?php
require_once "../includes/auth.php";
require_once "../includes/db.php";

$data = json_decode(file_get_contents("php://input"), true);

$filename = $data['filename'];
$html = $data['html'];
$css = $data['css'];
$js = $data['js'];
$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("INSERT INTO files (user_id, filename, html, css, js) 
    VALUES (?, ?, ?, ?, ?)
    ON DUPLICATE KEY UPDATE html=VALUES(html), css=VALUES(css), js=VALUES(js)");
$stmt->bind_param("issss", $user_id, $filename, $html, $css, $js);

if ($stmt->execute()) {
    echo json_encode(["status" => "success"]);
} else {
    echo json_encode(["status" => "error"]);
}
?>