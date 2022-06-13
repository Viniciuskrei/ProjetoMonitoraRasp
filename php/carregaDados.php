<?php

$conn = connection();

$sql = "SELECT * FROM dados ORDER BY id DESC LIMIT 5";
$stmt = $conn->prepare($sql);
$stmt->execute();

$result = $stmt->fetchAll();

$conn = closeConnection();

?>