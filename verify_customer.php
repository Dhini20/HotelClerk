<?php
// verify_customer.php
header('Content-Type: application/json');
include '../db/connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("SELECT CustomerID, NIC, Username, FirstName, LastName, PhoneNo, Email, Address, is_verified FROM Customer WHERE CustomerID = ?");
    $stmt->execute([$id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row) {
        echo json_encode($row);
        exit;
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Not found']);
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Accept either form-encoded id or JSON
    $id = null;
    if (isset($_POST['id'])) {
        $id = intval($_POST['id']);
    } else {
        $body = json_decode(file_get_contents('php://input'), true);
        if (isset($body['id'])) $id = intval($body['id']);
    }

    if (!$id) {
        http_response_code(400);
        echo json_encode(['error' => 'Missing id']);
        exit;
    }

    try {
        $stmt = $conn->prepare("UPDATE Customer SET is_verified = 1 WHERE CustomerID = ?");
        $ok = $stmt->execute([$id]);
        if ($ok) {
            // Return a short text 'success' for compatibility with JS code above
            echo 'success';
            exit;
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Update failed']);
            exit;
        }
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
        exit;
    }
}

// default
http_response_code(405);
echo json_encode(['error' => 'Method not allowed']);
