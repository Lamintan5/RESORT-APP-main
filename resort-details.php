<?php
session_start();

$userId = isset($_SESSION['user']) ? $_SESSION['user']['id'] : null;
$username = isset($_SESSION['user']) ? $_SESSION['user']['username'] : null;

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resort Details</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f7f9; 
            color: #333;
        }

        .resort-details {
            padding: 20px 0 60px;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }

        .resort-container {
            display: flex;
            justify-content: space-between;
            max-width: 1200px;
            width: 90%; 
            gap: 40px;
            margin-top: 30px;
            background-color: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        }

        .resort-image {
            flex: 1;
            max-width: 500px;
            min-width: 300px;
            overflow: hidden;
        }

        .resort-image img {
            width: 100%;
            height: 100%;
            border-radius: 8px;
            object-fit: cover;
            transition: transform 0.3s ease;
        }
        
        .resort-image img:hover {
            transform: scale(1.03);
        }

        .resort-info {
            flex: 2;
            max-width: 600px;
        }

        .resort-info h2 {
            font-size: 2.5rem;
            color: #1a73e8;
            margin-bottom: 10px;
            border-bottom: 2px solid #e0e0e0;
            padding-bottom: 5px;
        }

        .resort-info p {
            font-size: 1.1rem;
            line-height: 1.6;
            margin-bottom: 10px;
        }

        .resort-info strong {
            font-weight: 600;
            color: #555;
            display: inline-block;
            width: 70px;
        }

        .resort-info span {
            font-weight: normal;
        }
        
        #resortPrice {
            font-size: 1.3rem;
            font-weight: bold;
            color: #e8491d;
        }
        #resortStatus {
            font-weight: bold;
            color: #28a745;
        }

        .details-section {
            width: 90%;
            max-width: 1200px;
            margin-top: 40px;
            padding: 30px;
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }

        .details-section h3 {
            font-size: 1.8rem;
            color: #1a73e8;
            border-bottom: 1px solid #e0e0e0;
            padding-bottom: 8px;
            margin-top: 20px;
            margin-bottom: 15px;
        }

        .amenities-list {
            list-style: none;
            margin-left: 0;
            padding: 0;
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            font-size: 1.1rem;
        }
        
        .amenities-list li {
            background-color: #e6f0ff;
            padding: 8px 15px;
            border-radius: 20px;
            color: #1a73e8;
            font-weight: 500;
            list-style-type: none;
        }

        .rooms-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
            gap: 20px;
            padding: 20px 0;
        }

        .room-card {
            background-color: #f7f9fa;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            padding: 20px 15px;
            text-align: center;
            font-size: 1.2rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        }
        
        .room-card.available {
            color: #1a73e8;
        }

        .room-card.available:hover {
            background-color: #e6f0ff;
            border-color: #1a73e8;
            transform: translateY(-3px);
        }

        .room-card.selected {
            background-color: #28a745;
            color: white;
            border-color: #1e7e34;
            box-shadow: 0 4px 10px rgba(40, 167, 69, 0.3);
        }

        .room-card.booked {
            background-color: #dc3545;
            color: white;
            cursor: not-allowed;
            opacity: 0.7;
            border-color: #c82333;
            box-shadow: none;
        }
        
        #roomSelectionMessage {
            color: #dc3545 !important;
            font-weight: 500;
            padding: 10px;
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            border-radius: 5px;
        }

        .booking-container {
            display: none;
            margin-top: 50px;
            width: 90%;
            max-width: 600px;
            background-color: #fff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        }

        .booking-container h3 {
            text-align: center;
            margin-bottom: 25px;
            font-size: 2rem;
            color: #1a73e8;
        }

        .booking-container .form-group {
            margin-bottom: 20px;
        }

        .booking-container label {
            display: block;
            margin-bottom: 8px;
            font-size: 1rem;
            font-weight: 600;
            color: #555;
        }

        .booking-container input, .booking-container select {
            width: 100%;
            padding: 12px;
            font-size: 1rem;
            border: 1px solid #ccc;
            border-radius: 8px;
            box-sizing: border-box;
            transition: border-color 0.3s;
        }
        
        .booking-container input:focus, .booking-container select:focus {
            border-color: #1a73e8;
            outline: none;
            box-shadow: 0 0 5px rgba(26, 115, 232, 0.5);
        }

        .booking-container button {
            width: 100%;
            padding: 15px;
            background-color: #1a73e8;
            color: white;
            border: none;
            font-size: 1.2rem;
            font-weight: 600;
            cursor: pointer;
            border-radius: 8px;
            transition: background-color 0.3s, transform 0.1s;
            margin-top: 10px;
        }

        .booking-container button:hover {
            background-color: #155cb0;
            transform: translateY(-1px);
        }
        
        @media (max-width: 900px) {
            .resort-container {
                flex-direction: column;
                align-items: center;
            }
            .resort-image, .resort-info {
                max-width: 100%;
                min-width: unset;
                width: 100%;
            }
            .resort-image {
                height: 300px;
            }
        }

    </style>
</head>
<body>

<section class="resort-details">
    <div class="resort-container">
        <div class="resort-image">
            <img id="resortImage" src="" alt="Resort Image">
        </div>
        <div class="resort-info">
            <h2 id="resortTitle"></h2>
            <p id="resortLocation"></p>
            <p id="resortDescription"></p>
            <p><strong>Status:</strong> <span id="resortStatus"></span></p>
            <p><strong>Price:</strong> Ksh.<span id="resortPrice"></span> per day</p>
        </div>
    </div>

    <div class="details-section">
        <h3>Amenities</h3>
        <ul id="amenitiesList" class="amenities-list">
            </ul>
        
        <h3>Available Rooms (Select one to book)</h3>
        <div id="roomsGrid" class="rooms-grid">
            </div>
        
        <p id="roomSelectionMessage" style="color: red; margin-top: 15px;">Please select an available room to proceed with booking.</p>
    </div>

    <div class="booking-container">
        <h3>Book Your Stay</h3>
        <form id="bookingForm" onsubmit="submitBooking(event)">
            <input type="hidden" id="selectedRoomNumber" name="selectedRoomNumber"> 
            
            <div class="form-group">
                <label for="checkInDate">Check-in Date:</label>
                <input type="date" id="checkInDate" required>
            </div>
            <div class="form-group">
                <label for="checkOutDate">Check-out Date:</label>
                <input type="date" id="checkOutDate" required>
            </div>
            <div class="form-group">
                <label for="creditCardNumber">Credit Card Number:</label>
                <input type="text" id="creditCardNumber" required placeholder="1234 5678 1234 5678">
            </div>
            <div class="form-group">
                <label for="creditCardExpiry">Expiry Date:</label>
                <input type="month" id="creditCardExpiry" required>
            </div>
            <div class="form-group">
                <label for="creditCardCVV">CVV:</label>
                <input type="text" id="creditCardCVV" required placeholder="123">
            </div>
            <div class="form-group">
                <label for="paymentMethod">Payment Method:</label>
                <select id="paymentMethod" required>
                    <option value="cash">Cash</option>
                    <option value="electronic">Electronic</option>
                </select>
            </div>

            <button type="submit">Book Now</button>
        </form>
    </div>
</section>

<script>
let selectedRoom = null;

async function fetchResortDetails() {
    try {
        const urlParams = new URLSearchParams(window.location.search);
        const resortId = urlParams.get('id');
        
        const response = await fetch(`fetch_single_resort.php?id=${resortId}`);
        const data = await response.json();

        if (data.success) {
            const resort = data.data;
            
            document.getElementById('resortImage').src = resort.image;
            document.getElementById('resortTitle').innerText = resort.title;
            document.getElementById('resortLocation').innerText = resort.location;
            document.getElementById('resortDescription').innerText = resort.description;
            document.getElementById('resortStatus').innerText = resort.status;
            document.getElementById('resortPrice').innerText = resort.price;

            renderAmenities(resort.amenities);

            renderRooms(resort.rooms);

            document.getElementById('roomsGrid').addEventListener('click', handleRoomSelection);
            
        } else {
            alert('Resort not found');
        }
    } catch (error) {
        console.error('Error fetching resort details:', error);
    }
}

function renderAmenities(amenitiesJson) {
    const amenitiesList = document.getElementById('amenitiesList');
    amenitiesList.innerHTML = '';
    
    let amenities = [];
    try {
        amenities = JSON.parse(amenitiesJson);
    } catch (e) {
        amenities = []; 
    }
    
    if (Array.isArray(amenities) && amenities.length > 0) {
        amenities.forEach(item => {
            if (item && item.trim() !== "") {
                const li = document.createElement('li');
                li.innerText = item.trim();
                amenitiesList.appendChild(li);
            }
        });
    } else {
        amenitiesList.innerHTML = '<li>No amenities listed.</li>';
    }
}

function renderRooms(roomsJson) {
    const roomsGrid = document.getElementById('roomsGrid');
    roomsGrid.innerHTML = ''; 
    
    let rooms = [];
    try {
        rooms = JSON.parse(roomsJson);
    } catch (e) {
        rooms = [];
    }

    if (Array.isArray(rooms) && rooms.length > 0) {
        rooms.forEach(room => {
            const card = document.createElement('div');
            card.classList.add('room-card');
            card.dataset.roomNumber = room.room; 
            card.innerText = `Room ${room.room}`;

            const isAvailable = (room.availability === true || room.availability === 'true');
            
            if (isAvailable) {
                card.classList.add('available');
                card.dataset.status = 'available';
            } else {
                card.classList.add('booked');
                card.dataset.status = 'booked';
            }
            roomsGrid.appendChild(card);
        });
    } else {
        roomsGrid.innerHTML = '<p>No room information available for this resort.</p>';
    }
}

function handleRoomSelection(event) {
    const card = event.target.closest('.room-card');
    if (!card || card.dataset.status === 'booked') return; 

    document.querySelectorAll('.room-card.selected').forEach(el => {
        el.classList.remove('selected');
    });

    card.classList.add('selected');
    selectedRoom = card.dataset.roomNumber; 

    document.getElementById('selectedRoomNumber').value = selectedRoom;
    document.getElementById('roomSelectionMessage').style.display = 'none';
    document.querySelector('.booking-container').style.display = 'block';
}


async function submitBooking(event) {
    event.preventDefault(); 
    
    if (!selectedRoom) {
        alert("Please select an available room before booking.");
        return;
    }

    const checkInDate = document.getElementById('checkInDate').value;
    const checkOutDate = document.getElementById('checkOutDate').value;
    const paymentMethod = document.getElementById('paymentMethod').value;
    const resortId = new URLSearchParams(window.location.search).get('id');
    
    const formData = new FormData();
    formData.append('checkInDate', checkInDate);
    formData.append('checkOutDate', checkOutDate);
    formData.append('paymentMethod', paymentMethod);
    formData.append('resortId', resortId);
    formData.append('roomNumber', selectedRoom);

    try {
        const response = await fetch('process_booking.php', {
            method: 'POST',
            body: formData
        });

        const result = await response.json();

        if (result.success) {
            alert(result.message);
            window.location.reload(); 
        } else {
            alert(result.message); 
        }
    } catch (error) {
        console.error('Error submitting booking:', error);
        alert('Booking failed. Please try again.');
    }
}

window.onload = fetchResortDetails;

</script>

</body>
</html>