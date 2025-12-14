<?php
header("Content-Type: application/json");

$conn = new mysqli('localhost', 'root', '', 'hotel');
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed: ' . $conn->connect_error]);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $title = $conn->real_escape_string($_POST['title']);
    $location = $conn->real_escape_string($_POST['location']);
    $price = $conn->real_escape_string($_POST['price']);
    $description = $conn->real_escape_string($_POST['description']);
    
    $room_count = isset($_POST['room_count']) ? intval($_POST['room_count']) : 0;
    $rooms_array = [];
    if ($room_count > 0) {
        for ($i = 1; $i <= $room_count; $i++) {
            $rooms_array[] = [
                "room" => (string)$i,
                "availability" => true
            ];
        }
    }
    $rooms_json = $conn->real_escape_string(json_encode($rooms_array));

    $amenities_input = isset($_POST['amenities']) ? $_POST['amenities'] : '';
    $amenities_array = array_map('trim', explode(',', $amenities_input));
    $amenities_json = $conn->real_escape_string(json_encode($amenities_array));

    $imagePath = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $image = $_FILES['image'];
        $imagePath = 'uploads/' . basename($image['name']);
        if (!move_uploaded_file($image['tmp_name'], $imagePath)) {
            echo json_encode(['success' => false, 'message' => 'Failed to upload image']);
            exit();
        }
    }

    $sql = "UPDATE resort SET 
            title = '$title', 
            location = '$location', 
            price = '$price', 
            description = '$description',
            rooms = '$rooms_json',
            amenities = '$amenities_json'";
    
    if ($imagePath) {
        $sql .= ", image = '$imagePath'";
    }

    $sql .= " WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(['success' => true, 'message' => 'Resort updated successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error updating resort: ' . $conn->error]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}

$conn->close();
?>