<?php
include '../db/connection.php';

if (!isset($_POST['id'])) {
    echo 'error';
    exit;
}

$id = intval($_POST['id']);

try {
    $stmt = $conn->prepare("UPDATE Reservation SET show1 = 1 WHERE ReservationID = ?");
    $stmt->execute([$id]);
    echo 'success';
} catch (Exception $e) {
    echo 'error';
}
?>
