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
$paymentMethod = 'electronic';
$resortId = isset($_POST['resortId']) ? $_POST['resortId'] : null;
$roomNumber = isset($_POST['roomNumber']) ? $_POST['roomNumber'] : null;
$userId = $_SESSION['user']['id'];
$username = $_SESSION['user']['username'];

if (!$checkInDate || !$checkOutDate || !$resortId || !$roomNumber) {
    echo json_encode(['success' => false, 'message' => 'Missing required fields.']);
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

foreach ($rooms as $room) {
    if ($room['room'] == $roomNumber && ($room['availability'] === true || $room['availability'] === 'true')) {
        $isRoomAvailable = true;
        break;
    }
}

if (!$isRoomAvailable) {
    echo json_encode(['success' => false, 'message' => 'The selected room is unavailable.']);
    exit();
}

$bookingSql = "INSERT INTO booking (rid, uid, title, username, checkin, checkout, price, status, room_number) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($bookingSql);
$status = 'pending';
$stmt->bind_param("iisssssss", $resortId, $userId, $title, $username, $checkInDate, $checkOutDate, $totalPrice, $status, $roomNumber);
$bookingResult = $stmt->execute();

if ($bookingResult) {
    $paymentSql = "INSERT INTO payments (rid, uid, title, username, amount, method) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($paymentSql);
    $stmt->bind_param("iissds", $resortId, $userId, $title, $username, $totalPrice, $paymentMethod);
    $paymentResult = $stmt->execute();

    if ($paymentResult) {
        echo json_encode(['success' => true, 'message' => 'Booking request submitted. Status: Pending approval.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Payment record failed to create']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Booking failed']);
}

$conn->close();
?>