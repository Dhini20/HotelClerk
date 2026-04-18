<?php
include '../db/connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = intval($_POST['id']);
    $stmt = $conn->prepare("DELETE FROM Customer WHERE CustomerID = ?");
    $stmt->execute([$id]);
    echo 'success';
    exit;
}
echo 'error';
