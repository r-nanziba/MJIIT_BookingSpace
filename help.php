<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Help Centre - BookingSpace</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body {
            font-family: 'Roboto', Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-image: url('bg website.png');
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-position: center;
        }

        /* Navbar Styling */
        .navbar {
            display: flex;
            align-items: center;
            background-color: rgba(255, 255, 255, 0.9);
            color: rgb(114, 4, 4);
            padding: 8px 20px;
            justify-content: space-between;
            width: 100%;
            border-bottom: 2px solid #8B0000;
            z-index: 10;
        }

        .navbar-title {
            display: flex;
            align-items: center;
        }

        .navbar-title img {
            max-height: 30px;
            margin-right: 10px;
        }

        .navbar-title p {
            font-weight: bold;
            font-size: 20px;
            margin: 0;
        }

        .navbar-links {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .navbar-links a {
            color: rgb(119, 4, 4);
            text-decoration: none;
            margin-right: 0px;
            font-size: 14px;
        }

        .navbar-links a:hover {
            color: #ddd;
        }

        .navbar-profile i {
            font-size: 24px;
        }

        .help-container {
            max-width: 900px;
            margin: 50px auto;
            padding: 20px;
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .help-header {
            text-align: center;
            margin-bottom: 20px;
        }

        .help-header h1 {
            font-size: 24px;
            color: #8B0000;
        }

        .help-header p {
            color: #555;
            margin-top: 5px;
        }

        .accordion-button {
            font-weight: bold;
            color: #8B0000;
        }

        .accordion-button:focus {
            box-shadow: none;
        }

        .accordion-item {
            border: none;
        }

        .contact-section {
            text-align: center;
            margin-top: 30px;
        }

        .contact-section a {
            color: #8B0000;
            text-decoration: none;
            font-weight: bold;
        }

        .contact-section a:hover {
            text-decoration: underline;
        }
        /* Profile Dropdown Styles */
.dropdown {
    position: relative;
    display: inline-block;
    margin-left: 0;
}

.fa-user {
    font-size: 22px;
    cursor: pointer;
    color: rgb(119, 4, 4);
}

.dropdown-content {
    display: none;
    position: absolute;
    right: 0;
    background-color: white;
    min-width: 160px;
    box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);
    z-index: 1;
    transition: all 0.2s ease-in-out;
    opacity: 0;
    visibility: hidden;
}

.dropdown-content.show {
    display: block;
    opacity: 1;
    visibility: visible;
}

.dropdown-content a {
    color: rgb(119, 4, 4);
    padding: 12px 16px;
    text-decoration: none;
    display: block;
    transition: background-color 0.2s;
}

.dropdown-content a:hover {
    background-color: #ddd;
    color: rgb(119, 4, 4);
}

    </style>
</head>
<body>
    <!-- Navbar -->
    <div class="navbar">
    <div class="navbar-title">
        <img src="UTM-LOGO-FULL.png" alt="UTM Logo">
        <img src="Mjiit RoomMaster logo.png" alt="MJIIT Logo">
        <p>BookingSpace</p>
    </div>
    <div class="navbar-links">
        <a href="home.php">Home</a>
        <a href="my_bookings.php">My Bookings</a>
        <a href="rooms.php">Rooms</a>
        <a href="analytics.php">Analytics</a>
        <a href="help.php"><b>Help</b></a>
        <div class="dropdown">
            <i class="fa-solid fa-user" id="profileIcon"></i>
            <div class="dropdown-content" id="dropdownMenu">
                <a href="profile.php">Profile</a>
                <a href="login.php">Logout</a>
            </div>
        </div>
    </div>
</div>

    <!-- Help Centre -->
    <div class="help-container">
    <div class="help-header">
    <h1>Welcome to the Help Centre</h1>
    <p>We're available 8:00 AM - 8:00 PM</p>
    <button class="btn btn-danger" onclick="location.href='mailto:support@roommaster.com?subject=Help%20with%20Booking&body=Hi,%0D%0A%0D%0AI need assistance with booking...';">
        Get help with booking
    </button>
</div>


        <div class="accordion" id="helpAccordion">
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingCancellation">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseCancellation" aria-expanded="true" aria-controls="collapseCancellation">
                        Cancellation
                    </button>
                </h2>
                <div id="collapseCancellation" class="accordion-collapse collapse show" aria-labelledby="headingCancellation" data-bs-parent="#helpAccordion">
                    <div class="accordion-body">
                        <p><strong>Can I cancel my booking?</strong></p>
                        <p>Yes, you can cancel your booking from the "My Bookings" page. Click "Cancel" next to the booking you wish to cancel.</p>
                        <p><strong>If I need to cancel my booking, will I pay a fee?</strong></p>
                        <p>No, cancellations are free, but you must cancel at least 24 hours before the booking time.</p>
                    </div>
                </div>
            </div>

            <div class="accordion-item">
                <h2 class="accordion-header" id="headingBookingDetails">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseBookingDetails" aria-expanded="false" aria-controls="collapseBookingDetails">
                        Booking Details
                    </button>
                </h2>
                <div id="collapseBookingDetails" class="accordion-collapse collapse" aria-labelledby="headingBookingDetails" data-bs-parent="#helpAccordion">
                    <div class="accordion-body">
                        <p><strong>How can I check the status of my bookings?</strong></p>
                        <p>Go to the "My Bookings" page to view the status of your bookings (e.g., Pending, Approved, or Rejected).</p>
                    </div>
                </div>
            </div>

            <div class="accordion-item">
                <h2 class="accordion-header" id="headingRoomTypes">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseRoomTypes" aria-expanded="false" aria-controls="collapseRoomTypes">
                        Room Types
                    </button>
                </h2>
                <div id="collapseRoomTypes" class="accordion-collapse collapse" aria-labelledby="headingRoomTypes" data-bs-parent="#helpAccordion">
                    <div class="accordion-body">
                        <p><strong>What types of rooms are available?</strong></p>
                        <p>We have lecture rooms, seminar rooms, syndicate rooms, and meeting rooms available for booking. Check the "Rooms" page for details.</p>
                    </div>
                </div>
            </div>

            <div class="accordion-item">
                <h2 class="accordion-header" id="headingPricing">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapsePricing" aria-expanded="false" aria-controls="collapsePricing">
                        Pricing
                    </button>
                </h2>
                <div id="collapsePricing" class="accordion-collapse collapse" aria-labelledby="headingPricing" data-bs-parent="#helpAccordion">
                    <div class="accordion-body">
                        <p><strong>How is pricing determined?</strong></p>
                        <p>Pricing is based on the room type and booking duration. Refer to the pricing chart available on the "Rooms" page for detailed information.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="contact-section">
            <h3>Still need help?</h3>
            <p>Contact us at:</p>
            <p>Email: <a href="mailto:support@mjiit-bookingspace.com">support@mjiit-bookingspace.com</a></p>
            <p>Phone: +6012-3456789</p>
        </div>
    </div>
    <script>
    // Toggle dropdown on click
    document.querySelector('.fa-user').addEventListener('click', function(e) {
        const dropdown = document.querySelector('.dropdown-content');
        dropdown.classList.toggle('show');
        e.stopPropagation();
    });

    // Close dropdown when clicking outside
    window.addEventListener('click', function(e) {
        if (!e.target.matches('.fa-user')) {
            const dropdown = document.querySelector('.dropdown-content');
            if (dropdown.classList.contains('show')) {
                dropdown.classList.remove('show');
            }
        }
    });
</script>
</body>
</html>
