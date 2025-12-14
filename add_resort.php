<?php
header("Content-Type: application/json");

error_reporting(E_ALL);
ini_set('display_errors', 1);

$conn = new mysqli('localhost', 'root', '', 'hotel');
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed: ' . $conn->connect_error]);
    exit();
}

if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $image = $_FILES['image'];
    $uploadDir = 'uploads/';
    $imagePath = $uploadDir . basename($image['name']);

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    if (!move_uploaded_file($image['tmp_name'], $imagePath)) {
        echo json_encode(['success' => false, 'message' => 'Failed to upload image']);
        exit();
    }
} else {
    echo json_encode(['success' => false, 'message' => 'No image uploaded or upload error']);
    exit();
}

$title = $_POST['title'];
$location = $_POST['location'];
$price = $_POST['price'];
$description = $_POST['description'];

$room_count = isset($_POST['room_count']) ? intval($_POST['room_count']) : 0;
$amenities_input = isset($_POST['amenities']) ? $_POST['amenities'] : '';

if (empty($title) || empty($location) || empty($price) || empty($description) || $room_count < 1) {
    echo json_encode(['success' => false, 'message' => 'All fields are required, and room count must be at least 1']);
    exit();
}

$rooms_array = [];
for ($i = 1; $i <= $room_count; $i++) {
    $rooms_array[] = [
        "room" => (string)$i, 
        "availability" => true
    ];
}
$rooms_json = json_encode($rooms_array);

$amenities_array = array_map('trim', explode(',', $amenities_input));
$amenities_json = json_encode($amenities_array);

$title = $conn->real_escape_string($title);
$location = $conn->real_escape_string($location);
$description = $conn->real_escape_string($description);
$imagePath = $conn->real_escape_string($imagePath);
$rooms_json = $conn->real_escape_string($rooms_json); 
$amenities_json = $conn->real_escape_string($amenities_json);

$sql = "INSERT INTO resort (image, title, location, price, status, description, rooms, amenities) 
        VALUES ('$imagePath', '$title', '$location', '$price', 'available', '$description', '$rooms_json', '$amenities_json')";

if ($conn->query($sql) === TRUE) {
    echo json_encode(['success' => true, 'message' => 'Resort added successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $conn->error]);
}

$conn->close();
?>