<?php
session_start();
header("Content-Type: application/json");

if (!isset($_SESSION['user'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    
    exit();
}

require 'db.php';  

$checkInDate = isset($_POST['checkInDate']) ? $_POST['checkInDate'] : null;
$checkOutDate = isset($_POST['checkOutDate']) ? $_POST['checkOutDate'] : null;
$paymentMethod = isset($_POST['paymentMethod']) ? $_POST['paymentMethod'] : null;
$resortId = isset($_POST['resortId']) ? $_POST['resortId'] : null;
$roomNumber = isset($_POST['roomNumber']) ? $_POST['roomNumber'] : null;
$userId = $_SESSION['user']['id'];
$username = $_SESSION['user']['username'];

if (!$checkInDate || !$checkOutDate || !$paymentMethod || !$resortId || !$roomNumber) {
    echo json_encode(['success' => false, 'message' => 'Missing required fields, including room selection.']);
    exit();
}

$checkInTimestamp = strtotime($checkInDate);
$checkOutTimestamp = strtotime($checkOutDate);
$daysCount = ($checkOutTimestamp - $checkInTimestamp) / (60 * 60 * 24);

$sql = "SELECT title, price, rooms FROM resort WHERE id = ?"; 
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $resortId);
$stmt->execute();
$resortResult = $stmt->get_result();
$resort = $resortResult->fetch_assoc();

if (!$resort) {
    echo json_encode(['success' => false, 'message' => 'Resort not found']);
    exit();
}

$title = $resort['title'];
$pricePerDay = $resort['price'];
$roomsJson = $resort['rooms'];

$totalPrice = $pricePerDay * $daysCount;

$rooms = json_decode($roomsJson, true);
$isRoomAvailable = false;
$roomIndexToUpdate = -1;

foreach ($rooms as $index => $room) {
    if ($room['room'] == $roomNumber && ($room['availability'] === true || $room['availability'] === 'true')) {
        $roomIndexToUpdate = $index;
        $isRoomAvailable = true;
        break;
    }
}

if (!$isRoomAvailable) {
     echo json_encode(['success' => false, 'message' => 'The selected room is no longer available. Please select another.']);
     exit();
}

$rooms[$roomIndexToUpdate]['availability'] = false; 
$newRoomsJson = json_encode($rooms);

$updateSql = "UPDATE resort SET rooms = ? WHERE id = ?";
$updateStmt = $conn->prepare($updateSql);
$updateStmt->bind_param("si", $newRoomsJson, $resortId);
if (!$updateStmt->execute()) {
    echo json_encode(['success' => false, 'message' => 'Failed to update room availability']);
    exit();
}

$bookingSql = "INSERT INTO booking (rid, uid, title, username, checkin, checkout, price, status, room_number) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($bookingSql);
$status = 'confirmed'; 
$stmt->bind_param("iisssssss", $resortId, $userId, $title, $username, $checkInDate, $checkOutDate, $totalPrice, $status, $roomNumber); 
$bookingResult = $stmt->execute();

if ($bookingResult) {
    $paymentSql = "INSERT INTO payments (rid, uid, title, username, amount, method) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($paymentSql);
    $stmt->bind_param("iissds", $resortId, $userId, $title, $username, $totalPrice, $paymentMethod);
    $paymentResult = $stmt->execute();

    if ($paymentResult) {
        echo json_encode(['success' => true, 'message' => 'Booking for Room ' . $roomNumber . ' confirmed!']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Payment failed']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Booking failed']);
}

$conn->close();
?>