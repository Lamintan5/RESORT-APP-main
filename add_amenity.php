<?php
header("Content-Type: application/json");

error_reporting(E_ALL);
ini_set('display_errors', 1);

$conn = new mysqli('localhost', 'root', '', 'hotel');
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed: ' . $conn->connect_error]);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $resortId = intval($_POST['resort_id']);
    $name = $_POST['amenity_name'];
    $description = $_POST['amenity_description'];

    $imagePath = '';
    if (isset($_FILES['amenity_image']) && $_FILES['amenity_image']['error'] === UPLOAD_ERR_OK) {
        $image = $_FILES['amenity_image'];
        $uploadDir = 'uploads/';
        $fileName = uniqid() . '_' . basename($image['name']);
        $imagePath = $uploadDir . $fileName;

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        if (!move_uploaded_file($image['tmp_name'], $imagePath)) {
            echo json_encode(['success' => false, 'message' => 'Failed to upload amenity image']);
            exit();
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Amenity image is required']);
        exit();
    }

    $sqlFetch = "SELECT amenities FROM resort WHERE id = $resortId";
    $result = $conn->query($sqlFetch);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $currentAmenities = $row['amenities'];

        $amenitiesArray = json_decode($currentAmenities, true);
        if (!is_array($amenitiesArray)) {
            $amenitiesArray = [];
        }

        $newAmenity = [
            "id" => uniqid('amenity_'),
            "name" => $name,
            "image" => $imagePath,
            "description" => $description
        ];

        $amenitiesArray[] = $newAmenity;

        $newJson = $conn->real_escape_string(json_encode($amenitiesArray));
        $sqlUpdate = "UPDATE resort SET amenities = '$newJson' WHERE id = $resortId";

        if ($conn->query($sqlUpdate) === TRUE) {
            echo json_encode(['success' => true, 'message' => 'Amenity added successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Database update failed: ' . $conn->error]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Resort not found']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}

$conn->close();
?>