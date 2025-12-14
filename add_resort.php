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

// 2. Receive Basic Data
$title = $_POST['title'];
$location = $_POST['location'];
$price = $_POST['price'];
$description = $_POST['description'];

// 3. Receive New Data
$room_count = isset($_POST['room_count']) ? intval($_POST['room_count']) : 0;
$amenities_input = isset($_POST['amenities']) ? $_POST['amenities'] : '';

// Validation
if (empty($title) || empty($location) || empty($price) || empty($description) || $room_count < 1) {
    echo json_encode(['success' => false, 'message' => 'All fields are required, and room count must be at least 1']);
    exit();
}

// 4. GENERATE ROOMS JSON ARRAY
// Logic: Loop from 1 to room_count creating the specific object structure requested
$rooms_array = [];
for ($i = 1; $i <= $room_count; $i++) {
    $rooms_array[] = [
        "room" => (string)$i, // Storing as string "1" as requested
        "availability" => true
    ];
}
$rooms_json = json_encode($rooms_array); // Convert array to JSON string

// 5. GENERATE AMENITIES JSON ARRAY
// Logic: Split the input string by commas, trim whitespace, and encode as JSON
$amenities_array = array_map('trim', explode(',', $amenities_input));
$amenities_json = json_encode($amenities_array); // Convert to JSON string (e.g., ["Wifi", "Pool"])

// Escape strings for SQL safety
$title = $conn->real_escape_string($title);
$location = $conn->real_escape_string($location);
$description = $conn->real_escape_string($description);
$imagePath = $conn->real_escape_string($imagePath);
// json_encode handles quotes safely, but good practice to escape before query insertion just in case
$rooms_json = $conn->real_escape_string($rooms_json); 
$amenities_json = $conn->real_escape_string($amenities_json);

// 6. SQL INSERT
// Added 'rooms' and 'amenities' columns
$sql = "INSERT INTO resort (image, title, location, price, status, description, rooms, amenities) 
        VALUES ('$imagePath', '$title', '$location', '$price', 'available', '$description', '$rooms_json', '$amenities_json')";

if ($conn->query($sql) === TRUE) {
    echo json_encode(['success' => true, 'message' => 'Resort added successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $conn->error]);
}

$conn->close();
?>