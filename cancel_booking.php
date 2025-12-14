<?php
header("Content-Type: application/json");

error_reporting(E_ALL);
ini_set('display_errors', 1);

$conn = new mysqli('localhost', 'root', '', 'hotel');
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed: ' . $conn->connect_error]);
    exit();
}

$data = json_decode(file_get_contents('php://input'), true);
$bookingId = isset($data['id']) ? $data['id'] : null;

if (!$bookingId) {
    echo json_encode(['success' => false, 'message' => 'Booking ID is required']);
    exit();
}

$sql = "SELECT rid, room_number, status FROM booking WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $bookingId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $booking = $result->fetch_assoc();

    if ($booking['status'] === 'canceled') {
        echo json_encode(['success' => false, 'message' => 'Booking is already canceled']);
        exit();
    }

    $resortId = $booking['rid'];
    $roomNumber = $booking['room_number'];

    $resortSql = "SELECT rooms FROM resort WHERE id = ?";
    $rStmt = $conn->prepare($resortSql);
    $rStmt->bind_param("i", $resortId);
    $rStmt->execute();
    $rResult = $rStmt->get_result();

    if ($rResult->num_rows > 0) {
        $resort = $rResult->fetch_assoc();
        
        $rooms = json_decode($resort['rooms'], true);
        $roomsUpdated = false;
        if (is_array($rooms)) {
            foreach ($rooms as &$room) {
                if ($room['room'] == $roomNumber) {
                    $room['availability'] = true;
                    $roomsUpdated = true;
                    break; 
                }
            }
        }
        if ($roomsUpdated) {
            $newRoomsJson = json_encode($rooms);
            $updateResortSql = "UPDATE resort SET rooms = ? WHERE id = ?";
            $urStmt = $conn->prepare($updateResortSql);
            $urStmt->bind_param("si", $newRoomsJson, $resortId);
            $urStmt->execute();
        }
    }

    $cancelSql = "UPDATE booking SET status = 'canceled' WHERE id = ?";
    $cStmt = $conn->prepare($cancelSql);
    $cStmt->bind_param("i", $bookingId);

    if ($cStmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Booking canceled and room released successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update booking status']);
    }

} else {
    echo json_encode(['success' => false, 'message' => 'Booking not found']);
}

$conn->close();
?>