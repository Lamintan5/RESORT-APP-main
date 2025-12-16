<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="icon" type="image/png" href="images/icons/favicon.ico"/>
    <link rel="stylesheet" href="css/dash.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.0/css/all.min.css"/>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.23/jspdf.plugin.autotable.min.js"></script>

    <style>
        button{
            border:none;
            background-color: transparent;
            font: 1em sans-serif;
            cursor: pointer;
        }
        .export-btn {
            background-color: #e74c3c;
            color: white;
            padding: 8px 12px;
            border-radius: 4px;
            font-size: 0.9em;
            margin-left: 10px;
            cursor: pointer;
        }
        .confirm-btn {
            color: #27ae60;
            margin-right: 10px;
        }
        .cancel-btn {
            color: #c0392b;
        }
        .row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
<div class="sidebar">
    <div>
        <a href="#" class="nav-link active" data-section="resorts"><i class="fa-solid fa-car"></i> Resorts</a>
        <a href="#" class="nav-link" data-section="bookings"><i class="fa-solid fa-ticket"></i> Bookings</a>
        <a href="#" class="nav-link" data-section="payments"><i class="fa-solid fa-money-bill"></i> Payments</a>
    </div>

    <div class="user-profile">
        <i class="fa-regular fa-user profile-icon"></i>
        <div class="user-column">
            <?php
            $isLoggedIn = isset($_SESSION['user']);
            if ($isLoggedIn) {
                $user = $_SESSION['user'];
                echo "<h4>" . htmlspecialchars($user['username']) . "</h4>";
            } else {
                echo "<p>You are not logged in.</p>";
            }
            ?>
        </div>
    </div>
</div>

<div class="main-content">
    <section id="resorts" class="content-section">
        <div class="row">
            <h2>Resorts</h2>
            <div>
                <div class="add-button" onclick="openModal()" style="display:inline-block;">Add</div>
                <button class="export-btn" onclick="exportTableToPDF('resorts-table', 'Resorts_List')"><i class="fa-solid fa-file-pdf"></i> Export PDF</button>
            </div>
        </div>
        <table id="resorts-table">
            <thead>
            <tr>
                <th>Resort ID</th>
                <th>Name</th>
                <th>Location</th>
                <th>Description</th>
                <th>Status</th>
                <th>Price/Day</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody></tbody>
        </table>
    </section>

    <section id="bookings" class="content-section" style="display: none;">
        <div class="row">
            <h2>Bookings</h2>
            <button class="export-btn" onclick="exportTableToPDF('bookings-table', 'Bookings_List')"><i class="fa-solid fa-file-pdf"></i> Export PDF</button>
        </div>
        <table id="bookings-table">
            <thead>
            <tr>
                <th>Book ID</th>
                <th>Resort</th>
                <th>Room</th>
                <th>Customer</th>
                <th>Check In</th>
                <th>Check Out</th>
                <th>Price</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody></tbody>
        </table>
    </section>

    <section id="payments" class="content-section" style="display: none;">
        <div class="row">
            <h2>Payments</h2>
            <button class="export-btn" onclick="exportTableToPDF('payments-table', 'Payments_List')"><i class="fa-solid fa-file-pdf"></i> Export PDF</button>
        </div>
        <table id="payments-table">
            <thead>
            <tr>
                <th>ID</th>
                <th>Resort</th>
                <th>Customer</th>
                <th>Amount</th>
                <th>Method</th>
                <th>Date</th>
            </tr>
            </thead>
            <tbody></tbody>
        </table>
    </section>
</div>

<div id="addResortModel" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <h3>Add resort</h3>
        <form id="addResortForm">
            <label for="image">Image:</label>
            <input type="file" id="image" name="image" accept="image/*" required>
            <label for="title">Title:</label>
            <input type="text" id="make" name="title" placeholder="Enter resort title" required>
            <label for="location">Location:</label>
            <input type="text" id="location" name="location" placeholder="Enter resort location" required>
            <label for="room_count">Number of Rooms:</label>
            <input type="number" id="room_count" name="room_count" placeholder="e.g. 10" min="1" required>
            <label for="price">Price Per Day:</label>
            <input type="number" id="price" name="price" placeholder="Enter price per day" required>
            <label for="description">Description:</label>
            <textarea id="description" name="description" placeholder="Description" rows="5" style="resize: none;" required></textarea>
            <button type="submit">Submit</button>
        </form>
    </div>
</div>

<div id="editResortModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeEditModal()">&times;</span>
        <h3>Edit Resort</h3>
        <form id="editResortForm">
            <input type="hidden" id="edit-id" name="id">
            <label for="edit-image">Image (Optional):</label>
            <input type="file" id="edit-image" name="image" accept="image/*">
            <label for="edit-title">Title:</label>
            <input type="text" id="edit-title" name="title" required>
            <label for="edit-location">Location:</label>
            <input type="text" id="edit-location" name="location" required>
            <label for="edit-price">Price Per Day:</label>
            <input type="number" id="edit-price" name="price" required>
            <label for="edit-description">Description:</label>
            <textarea id="edit-description" name="description" rows="5" style="resize: none;" required></textarea>
            <button type="submit">Save Changes</button>
        </form>
    </div>
</div>

<div id="addAmenityModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeAmenityModal()">&times;</span>
        <h3>Add Amenity</h3>
        <form id="addAmenityForm">
            <input type="hidden" id="amenity_resort_id" name="resort_id">
            <label for="amenity_image">Amenity Image:</label>
            <input type="file" id="amenity_image" name="amenity_image" accept="image/*" required>
            <label for="amenity_name">Amenity Name:</label>
            <input type="text" id="amenity_name" name="amenity_name" required>
            <label for="amenity_description">Description:</label>
            <textarea id="amenity_description" name="amenity_description" rows="3" required></textarea>
            <button type="submit">Add Amenity</button>
        </form>
    </div>
</div>

<script src="js/api.js"></script>

<script>
    const navLinks = document.querySelectorAll('.nav-link');
    const sections = document.querySelectorAll('.content-section');

    navLinks.forEach(link => {
        link.addEventListener('click', event => {
            event.preventDefault();
            navLinks.forEach(link => link.classList.remove('active'));
            sections.forEach(section => section.style.display = 'none');
            link.classList.add('active');
            const sectionId = link.getAttribute('data-section');
            document.getElementById(sectionId).style.display = 'block';
        });
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        fetchBookings();
        fetchPayments();
    });

    function fetchBookings() {
        fetch('fetch_booking.php')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const bookingsTableBody = document.querySelector('#bookings-table tbody');
                    bookingsTableBody.innerHTML = '';
                    data.data.forEach(booking => {
                        const row = document.createElement('tr');

                        let actionButtons = '';
                        if(booking.status === 'pending') {
                            actionButtons = `
                                <button class="confirm-btn" onclick="confirmBooking(${booking.id})" title="Confirm"><i class="fa-solid fa-check"></i></button>
                                <button class="cancel-btn" onclick="cancelBooking(${booking.id})" title="Cancel"><i class="fa-solid fa-ban"></i></button>
                            `;
                        } else {
                            actionButtons = `<span>${booking.status}</span>`;
                        }

                        row.innerHTML = `
                            <td>${booking.id}</td>
                            <td>${booking.title}</td>
                            <td>${booking.room_number}</td>
                            <td>${booking.username}</td>
                            <td>${booking.checkin}</td>
                            <td>${booking.checkout}</td>
                            <td>${booking.price}</td>
                            <td style="text-transform:capitalize; font-weight:bold;">${booking.status}</td>
                            <td>${actionButtons}</td>
                        `;

                        bookingsTableBody.appendChild(row);
                    });
                } else {
                    console.log('Error fetching bookings:', data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
    }

    async function confirmBooking(bookingId) {
        if (!confirm("Confirm this booking? This will lock the room availability.")) {
            return;
        }

        const formData = new FormData();
        formData.append('bookingId', bookingId);

        try {
            const response = await fetch('confirm_booking.php', {
                method: 'POST',
                body: formData
            });

            const result = await response.json();

            if (result.success) {
                alert(result.message);
                fetchBookings();
            } else {
                alert(result.message);
            }
        } catch (error) {
            console.error('Error confirming booking:', error);
            alert('Confirmation failed due to a network error.');
        }
    }

    function cancelBooking(bookingId) {
        const confirmCancel = confirm("Are you sure you want to cancel this booking?");
        if (confirmCancel) {
            fetch('cancel_booking.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ id: bookingId })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Booking canceled successfully');
                        fetchBookings();
                    } else {
                        alert('Error canceling booking');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }
    }

    function fetchPayments() {
        fetch('fetch_payments.php')
            .then(response => response.json())
            .then(data => {
                if (data.success && data.data) {
                    const paymentsTableBody = document.querySelector('#payments-table tbody');
                    paymentsTableBody.innerHTML = '';
                    data.data.forEach(payment => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${payment.id}</td>
                            <td>${payment.title}</td>
                            <td>${payment.username}</td>
                            <td>${payment.amount}</td>
                            <td>${payment.method}</td>
                            <td>${payment.time || 'N/A'}</td>
                        `;
                        paymentsTableBody.appendChild(row);
                    });
                }
            })
            .catch(error => {
                console.error('Error fetching payments:', error);
            });
    }

    function exportTableToPDF(tableId, filename) {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF();

        doc.text(filename.replace('_', ' '), 14, 15);

        doc.autoTable({
            html: '#' + tableId,
            startY: 20,
            theme: 'grid',
            headStyles: { fillColor: [41, 128, 185] },
        });

        doc.save(`${filename}.pdf`);
    }
</script>
</body>
</html>