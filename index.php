<?php
session_start();
?>
<!DOCTYPE HTML>
<html>
	<head>
		<title>Hotel Website | Home</title>
		<link href='http://fonts.googleapis.com/css?family=PT+Sans+Narrow' rel='stylesheet' type='text/css'>
		<link rel="stylesheet" href="css/responsiveslides.css">
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
		<script src="js/responsiveslides.min.js"></script>
		<link rel="stylesheet" href="css/style.css">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.0/css/all.min.css"/>
		  <script>
			    $(function () {
			
			      $("#slider1").responsiveSlides({
			        maxwidth: 1600,
			        speed: 600
			      });
			});
		  </script>
		  <style>
			.contact-info a {
				text-decoration: none;
				color: white;
				padding: 10px 20px;
				border-radius: 5px;
				display: inline-flex;
				align-items: center;
				margin: 5px;
				transition: background-color 0.3s ease;
				font-family: 'PT Sans Narrow', sans-serif;
			}

			.btn-talk {
				background-color: #007BFF;
				border: none;
			}

			.btn-dashboard {
				background-color: #28a745;
				border: none;
			}

			.btn-talk:hover, .btn-dashboard:hover {
				background-color: #0056b3;
				color: white;
			}

			.contact-info i {
				margin-right: 8px;
				font-size: 16px;
			}

			.logo{
				height: 150px;
				width: 150px;
			}

            /* --- Added Styles for Booking Modal --- */
            .modal {
                display: none; 
                position: fixed; 
                z-index: 9999; 
                left: 0; 
                top: 0; 
                width: 100%; 
                height: 100%; 
                overflow: auto; 
                background-color: rgba(0,0,0,0.6); 
            }
            .modal-content {
                background-color: #fefefe;
                margin: 5% auto; 
                padding: 25px;
                border: 1px solid #888;
                width: 80%;
                max-width: 900px;
                border-radius: 8px;
                box-shadow: 0 4px 8px rgba(0,0,0,0.2);
                font-family: 'PT Sans Narrow', sans-serif;
            }
            .close-modal {
                color: #aaa;
                float: right;
                font-size: 28px;
                font-weight: bold;
                cursor: pointer;
            }
            .close-modal:hover, .close-modal:focus {
                color: #000;
                text-decoration: none;
                cursor: pointer;
            }
            .booking-table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 20px;
            }
            .booking-table th, .booking-table td {
                border: 1px solid #ddd;
                padding: 12px;
                text-align: left;
                font-size: 16px;
            }
            .booking-table th {
                background-color: #454545;
                color: white;
                text-transform: uppercase;
            }
            .booking-table tr:nth-child(even) {background-color: #f2f2f2;}
            
            .btn-cancel {
                background-color: #dc3545;
                color: white;
                border: none;
                padding: 6px 12px;
                border-radius: 4px;
                cursor: pointer;
                font-family: 'PT Sans Narrow', sans-serif;
                text-transform: uppercase;
                transition: background 0.3s;
            }
            .btn-cancel:hover { background-color: #c82333; }
            
            .status-canceled { color: red; font-weight: bold; }
            .status-active { color: green; font-weight: bold; }

		  </style>
	</head>
	<body>
		<div class="header">
				<div class="wrap">
					<div class="header-top">
						<div class="logo">
							<a href="index.php"><img src="images/logo.png " title="logo" /></a>
						</div>
						<div class="contact-info">
							<p class="phone">Call us : <a href="#">980XXXXXXX</a></p>
							<p class="gpa">Gpa : <a href="#">View map</a></p>
							<p class="code">BROUGHT TO YOU BY: <a href="https:www.code-projects.org">CODE-PROJECTS</a></p>
							<?php if (isset($_SESSION['user'])): ?>
								<a href="logout.php" class="btn-talk">
									<i class="fas fa-sign-out-alt"></i> Log out
								</a>
							<?php else: ?>
								<a href="auth.php" class="btn-talk">
									<i class="fas fa-sign-in-alt"></i> Log in
								</a>
							<?php endif; ?>
							<?php if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin'): ?>
								<a href="dashboard.php" class="btn-dashboard">
									<i class="fas fa-tachometer-alt"></i> Dashboard
								</a>
							<?php endif; ?>
						</div>

						<div class="clear"> </div>
					</div>
				</div>
				<div class="header-top-nav">
					<div class="wrap">
						<ul>
							<li class="active"><a href="index.php">Home</a></li>
							<li><a href="about.html">About</a></li>
							<li><a href="services.html">Services</a></li>
							<li><a href="gallery.html">Gallery</a></li>
							<li><a href="contact.html">Contact</a></li>
                            <?php if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'customer'): ?>
                                <li><a href="#" onclick="openBookingModal(); return false;">My Bookings</a></li>
                            <?php endif; ?>
							<div class="clear"> </div>
						</ul>
					</div>
				</div>
			</div>
			<div class="clear"> </div>
			<div class="image-slider">
						<ul class="rslides" id="slider1">
						      <li><img src="images/slider3.jpg" alt=""></li>
						      <li><img src="images/slider1.jpg" alt=""></li>
						      <li><img src="images/slider3.jpg" alt=""></li>
						    </ul>
						 </div>
					<div class="content">
				<div class="quit">
					<p><span class="start">Your </span> hotel's <span class="end">Motto .</span></p>
				</div>
					<div class="content-grids">
						<div class="wrap">
						<div class="content-grid">
							<div class="content-grid-pic">
								<a href="#"><img src="images/icon1.png" title="image-name" /></a>
							</div>
							<div class="content-grid-info">
								<h3>Best food Ever</h3>
								<p>"DESCRIPTION"</p>
								<a href="#">Read More</a>
							</div>
							<div class="clear"> </div>
						</div>
						<div class="content-grid">
							<div class="content-grid-pic">
								<a href="#"><img src="images/icon2.png" title="image-name" /></a>
							</div>
							<div class="content-grid-info">
								<h3>24x7 phone support</h3>
								<p>"DESCRIPTION"</p>
								<a href="#">Read More</a>
							</div>
							<div class="clear"> </div>
						</div>
						<div class="content-grid">
							<div class="content-grid-pic">
								<a href="#"><img src="images/iocn3.png" title="image-name" /></a>
							</div>
							<div class="content-grid-info">
								<h3>Best Room Services</h3>
								<p>"DESCRIPTION"</p>
								<a href="#">Read More</a>
							</div>
							<div class="clear"> </div>
						</div>
						
						<div class="clear"> </div>
					</div>
				</div>
				<div class="clear"> </div>
				<div class="content-box">
					<div class="wrap">
					<div class="content-box-left">
						<div class="content-box-left-topgrid">
							<h3>welcome to our Hotel</h3>
							<p> ' Feel Like Home :)</p>
							<p> Hotel's Description</p>
							<span>For more information about our Hotel, Call 980XXXXXXX</span>
						</div>
						<div class="content-box-left-bootomgrids">
							<div class="content-box-left-bootomgrid">
								<h3>Deluxe Rooms</h3>
								<p> Your description about deluxe rooms</p>
								<a href="#"><img src="images/slider1.jpg" title="image-name" /></a>
							</div>
							<div class="content-box-left-bootomgrid">
								<h3>Luxury Rooms</h3>
								<p>Your description about Luxury rooms</p>
								<a href="#"><img src="images/slider2.jpg" title="image-name" /></a>
							</div>
							<div class="content-box-left-bootomgrid lastgrid">
								<h3>Executive Rooms</h3>
								<p>Your description about Executive rooms</p>
								<a href="#"><img src="images/slider3.jpg" title="image-name" /></a>
							</div>
							<div class="clear"> </div>
						</div>
						<div class="clear"> </div>
					</div>
					<div class="content-box-right">
						<div class="content-box-right-topgrid">
							<h3>To days Specials</h3>
							<a href="#"><img src="images/slider1.jpg" title="imnage-name" /></a>
							<h4>Super Discount Offer</h4>
							<p>"DESCRIPTION"</p>
							<a href="#">Read More</a>
						</div>
						<div class="content-box-right-bottomgrid">
							
						</div>
					</div>
					<div class="clear"> </div>
				</div>
				<div class="clear"> </div>
				<div class="boxs">
				<div class="wrap">
				<div class="box">
			
				</div>
				<div class="box center-box">
					<ul>
						<li><a href="https:www.code-projects.org">Leave a Feedback</a></li>
						<li><a href="https:www.code-projects.org">Reviews and Ratings</a></li>
						<li><a href="https:www.code-projects.org">FAQs</a></li>
						<li><a href="https:www.code-projects.org">Packages</a></li>
						<li><a href="https:www.code-projects.org">Know about Moutaineering and trekking here</a></li>
						<li><a href="https:www.code-projects.org">www.code-projects.org</a></li>
					</ul>
				</div>
		
				<div class="clear"> </div>
			</div>
			</div>
		<div class="copy-tight">
			<p>&copy HOTEL,Nepal 2017 "THIS PROJECT IS BROUGHT TO YOU BY <a href="http://www.code-projects.org/">CODE-PROJECTS"</a></p>
		</div>
		</div>
			</div>
		<div id="bookingModal" class="modal">
            <div class="modal-content">
                <span class="close-modal" onclick="closeBookingModal()">&times;</span>
                <h2>My Bookings</h2>
                <div id="bookingList">
                    <p>Loading bookings...</p>
                </div>
            </div>
        </div>

        <script>
            function openBookingModal() {
                document.getElementById('bookingModal').style.display = 'block';
                fetchBookings();
            }

            function closeBookingModal() {
                document.getElementById('bookingModal').style.display = 'none';
            }

            window.onclick = function(event) {
                var modal = document.getElementById('bookingModal');
                if (event.target == modal) {
                    modal.style.display = "none";
                }
            }

            function fetchBookings() {
                fetch('fetch_user_bookings.php')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        let html = '<table class="booking-table">';
                        html += '<thead><tr><th>Room Type</th><th>Room No</th><th>Check In</th><th>Check Out</th><th>Price</th><th>Status</th><th>Action</th></tr></thead><tbody>';
                        
                        if (data.data.length > 0) {
                            data.data.forEach(booking => {
                                let statusClass = booking.status === 'canceled' ? 'status-canceled' : 'status-active';
                                let actionBtn = '';
                                if(booking.status !== 'canceled') {
                                    actionBtn = `<button class="btn-cancel" onclick="cancelBooking(${booking.id})">Cancel</button>`;
                                } else {
                                    actionBtn = '<span>-</span>';
                                }

                                html += `<tr>
                                    <td>${booking.title}</td>
                                    <td>${booking.room_number ? booking.room_number : 'N/A'}</td>
                                    <td>${booking.checkin}</td>
                                    <td>${booking.checkout}</td>
                                    <td>$${booking.price}</td>
                                    <td class="${statusClass}">${booking.status.toUpperCase()}</td>
                                    <td>${actionBtn}</td>
                                </tr>`;
                            });
                        } else {
                            html += '<tr><td colspan="7">No bookings found.</td></tr>';
                        }
                        html += '</tbody></table>';
                        document.getElementById('bookingList').innerHTML = html;
                    } else {
                        document.getElementById('bookingList').innerHTML = '<p>' + data.message + '</p>';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    document.getElementById('bookingList').innerHTML = '<p>Error loading bookings.</p>';
                });
            }

            function cancelBooking(id) {
                if(!confirm("Are you sure you want to cancel this reservation?")) return;

                fetch('cancel_booking.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ id: id })
                })
                .then(response => response.json())
                .then(data => {
                    alert(data.message);
                    if(data.success) {
                        fetchBookings();
                    }
                })
                .catch((error) => {
                    console.error('Error:', error);
                    alert("An error occurred while canceling.");
                });
            }
        </script>

	</body>
</html>