<?php
session_start();
header("Content-Type: application/json");

require 'db.php'; 

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'customer') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access.']);
    exit();
}

$userId = $_SESSION['user']['id'];

$sql = "SELECT id, title, room_number, checkin, checkout, price, status FROM booking WHERE uid = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

$bookings = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $bookings[] = $row;
    }
}

echo json_encode(['success' => true, 'data' => $bookings]);

$conn->close();
?>