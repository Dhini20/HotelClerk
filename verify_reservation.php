<?php
// Connect to DB
include '../db/connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Accept form-encoded or JSON
    $id = null;
    if (isset($_POST['id'])) $id = intval($_POST['id']);
    else {
        $body = json_decode(file_get_contents('php://input'), true);
        if (isset($body['id'])) $id = intval($body['id']);
    }

    if (!$id) {
        http_response_code(400);
        echo 'error';
        exit;
    }

    try {
        $stmt = $conn->prepare("UPDATE Reservation SET is_verified = 1 WHERE ReservationID = ?");
        $ok = $stmt->execute([$id]);
        echo $ok ? 'success' : 'error';
        exit;
    } catch (PDOException $e) {
        http_response_code(500);
        echo 'error';
        exit;
    }
}

http_response_code(405);
echo 'error';
