<?php
session_start();
header("Content-Type: application/json");

if (!isset($_SESSION['user'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access.']);
    exit();
}

require 'db.php';

$bookingId = isset($_POST['bookingId']) ? (int)$_POST['bookingId'] : 0;

if ($bookingId <= 0) {
    echo json_encode(['success' => false, 'message' => 'Missing booking ID.']);
    exit();
}

$conn->begin_transaction();

try {
    $sql = "SELECT rid, room_number FROM booking WHERE id = ? AND status = 'pending'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $bookingId);
    $stmt->execute();
    $bookingResult = $stmt->get_result();
    $booking = $bookingResult->fetch_assoc();

    if (!$booking) {
        throw new Exception("Booking not found or already processed.");
    }

    $resortId = $booking['rid'];
    $roomNumber = $booking['room_number'];

    $sql = "SELECT rooms FROM resort WHERE id = ? FOR UPDATE";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $resortId);
    $stmt->execute();
    $resortResult = $stmt->get_result();
    $resort = $resortResult->fetch_assoc();

    if (!$resort) {
        throw new Exception("Resort not found.");
    }

    $rooms = json_decode($resort['rooms'], true);
    $roomIndexToUpdate = -1;
    $isAvailable = false;

    foreach ($rooms as $index => $room) {
        if ($room['room'] == $roomNumber) {
            $roomIndexToUpdate = $index;
            if ($room['availability'] === true || $room['availability'] === 'true') {
                $isAvailable = true;
            }
            break;
        }
    }

    if ($roomIndexToUpdate === -1) {
        throw new Exception("Room configuration error: Room not found.");
    }

    if (!$isAvailable) {
        throw new Exception("Room has already been taken by another confirmed booking.");
    }

    $rooms[$roomIndexToUpdate]['availability'] = false;
    $newRoomsJson = json_encode($rooms);

    $updateResortSql = "UPDATE resort SET rooms = ? WHERE id = ?";
    $updateStmt = $conn->prepare($updateResortSql);
    $updateStmt->bind_param("si", $newRoomsJson, $resortId);
    if (!$updateStmt->execute()) {
        throw new Exception("Failed to update room availability.");
    }

    $updateBookingSql = "UPDATE booking SET status = 'confirmed' WHERE id = ?";
    $updateStmt = $conn->prepare($updateBookingSql);
    $updateStmt->bind_param("i", $bookingId);
    if (!$updateStmt->execute()) {
        throw new Exception("Failed to confirm booking status.");
    }

    $conn->commit();
    echo json_encode(['success' => true, 'message' => 'Booking confirmed successfully. Room is now locked.']);

} catch (Exception $e) {
    $conn->rollback();
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

$conn->close();
?>