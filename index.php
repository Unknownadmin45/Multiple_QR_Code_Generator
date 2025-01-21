<?php
// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "qr_code_generator";

// Create a connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database if it does not exist
$db_create_sql = "CREATE DATABASE IF NOT EXISTS $dbname";
if ($conn->query($db_create_sql) === TRUE) {
    
} else {
    
}

// Select the database
$conn->select_db($dbname);

// Create table for QR Codes
$table_create_sql = "
CREATE TABLE IF NOT EXISTS qr_codes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    qr_type VARCHAR(50) NOT NULL,
    data TEXT NOT NULL,
    file_path VARCHAR(255)
)";

if ($conn->query($table_create_sql) === TRUE) {
   
} else {
    echo "Error creating table: " . $conn->error . "<br>";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code Generator</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
        }
        .container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            padding: 30px;
            margin-top: 30px;
            margin-bottom: 30px;
        }
        h1 {
            color: #2c3e50;
            font-weight: 700;
            margin-bottom: 30px;
            text-transform: uppercase;
            letter-spacing: 2px;
            border-bottom: 3px solid #3498db;
            padding-bottom: 10px;
        }
        .form-group label {
            font-weight: 600;
            color: #34495e;
        }
        .form-control {
            border-radius: 8px;
            border: 2px solid #eee;
            transition: all 0.3s ease;
        }
        .form-control:focus {
            border-color: #3498db;
            box-shadow: 0 0 10px rgba(52, 152, 219, 0.2);
        }
        .qrForm {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            margin-top: 20px;
        }
        .qrForm h4 {
            color: #2c3e50;
            margin-bottom: 20px;
            font-weight: 600;
        }
        .btn-primary {
            background: #3498db;
            border: none;
            padding: 12px 30px;
            border-radius: 25px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            background: #2980b9;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(52, 152, 219, 0.3);
        }
        .input-group {
            margin-bottom: 15px;
        }
        .input-group-prepend .input-group-text {
            background: #3498db;
            color: white;
            border: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="text-center"><i class="fas fa-qrcode mr-3"></i>QR Code Generator</h1>
        <form action="generate_qr.php" method="POST" class="needs-validation" novalidate>
            <div class="form-group">
                <label for="qrType"><i class="fas fa-list-alt mr-2"></i>Select QR Code Type:</label>
                <select class="form-control" name="qrType" id="qrType" required>
                    <option value="">Choose type...</option>
                    <option value="business_card">Business Card</option>
                    <option value="event_ticket">Event Ticket</option>
                    <option value="menu_card">Menu Card</option>
                    <option value="payment">Payment Information</option>
                    <option value="social_media">Social Media Information</option>
                    
                </select>
            </div>

         <!-- Payment QR Code Form -->
<div id="paymentForm" class="qrForm" style="display:none;">
    <h4><i class="fas fa-money-bill-wave mr-2"></i>Payment Information</h4>
    <div class="form-group">
        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
            </div>
            <input type="number" name="paymentAmount" class="form-control" placeholder="Payment Amount" required>
        </div>
        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-credit-card"></i></span>
            </div>
            <select name="paymentMethod" class="form-control" required>
                <option value="">Select Payment Method...</option>
                <option value="upi">UPI</option>
                <option value="credit_card">Credit Card</option>
                <option value="debit_card">Debit Card</option>
                <option value="paypal">PayPal</option>
            </select>
        </div>
        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-info-circle"></i></span>
            </div>
            <input type="text" name="paymentDetails" class="form-control" placeholder="Payment Details (e.g., UPI ID, Card Number)" required>
        </div>
    </div>
</div>

        <!-- Social Media QR Code Form -->
<div id="socialMediaForm" class="qrForm" style="display:none;">
    <h4><i class="fas fa-share-alt mr-2"></i>Social Media Information</h4>
    <div class="form-group">
        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-users"></i></span>
            </div>
            <select name="socialMediaPlatform" class="form-control" required>
                <option value="">Select Social Media Platform...</option>
                <option value="instagram">Instagram</option>
                <option value="facebook">Facebook</option>
                <option value="twitter">Twitter</option>
            </select>
        </div>
        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-link"></i></span>
            </div>
            <input type="text" name="userHandle" class="form-control" placeholder="User  Handle or Profile Link" required>
        </div>
    </div>
</div>


           <!-- Business Card Form -->
<div id="businessCardForm" class="qrForm">
    <h4><i class="fas fa-id-card mr-2"></i>Business Card Information</h4>
    <div class="form-group">
        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-user"></i></span>
            </div>
            <input type="text" name="name" class="form-control" placeholder="Name">
        </div>
        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-briefcase"></i></span>
            </div>
            <input type="text" name="jobTitle" class="form-control" placeholder="Job Title">
        </div>
        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-building"></i></span>
            </div>
            <input type="text" name="company" class="form-control" placeholder="Company">
        </div>
        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-phone"></i></span>
            </div>
            <input type="tel" name="phone" class="form-control" placeholder="Phone Number">
        </div>
        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
            </div>
            <input type="email" name="email" class="form-control" placeholder="Email Address">
        </div>
        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-globe"></i></span>
            </div>
            <input type="url" name="website" class="form-control" placeholder="Website URL">
        </div>
    </div>
</div>

<!-- Event Ticket Form -->
<div id="eventTicketForm" class="qrForm" style="display:none;">
    <h4><i class="fas fa-ticket-alt mr-2"></i>Event Ticket Information</h4>
    <div class="form-group">
        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
            </div>
            <input type="text" name="eventName" class="form-control" placeholder="Event Name">
        </div>
        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-calendar-day"></i></span>
            </div>
            <input type="date" name="date" class="form-control" placeholder="Date">
        </div>
        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-clock"></i></span>
            </div>
            <input type="time" name="time" class="form-control" placeholder="Time">
        </div>
        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
            </div>
            <input type="text" name="location" class="form-control" placeholder="Location">
        </div>
        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-info-circle"></i></span>
            </div>
            <input type="text" name="ticketInfo" class="form-control" placeholder="Ticket Information">
        </div>
    </div>
</div>

<!-- Menu Card Form -->
<div id="menuCardForm" class="qrForm" style="display:none;">
    <h4><i class="fas fa-utensils mr-2"></i>Menu Card Information</h4>
    <div class="form-group">
        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-store"></i></span>
            </div>
            <input type="text" name="restaurantName" class="form-control" placeholder="Restaurant Name">
        </div>
        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-map-marked-alt"></i></span>
            </div>
            <input type="text" name="address" class="form-control" placeholder="Address">
        </div>
        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-phone"></i></span>
            </div>
            <input type="tel" name="contact" class="form-control" placeholder="Contact Information">
        </div>
        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-list-alt"></i></span>
            </div>
            <textarea name="menuItems" class="form-control" placeholder="Menu Items (Description and Prices)" rows="4"></textarea>
        </div>
        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-link"></i></span>
            </div>
            <input type="url" name="orderingLink" class="form-control" placeholder="Online Ordering Link">
        </div>
    </div>
</div>

            <button type="submit" class="btn btn-primary mt-4 btn-block">
                <i class="fas fa-qrcode mr-2"></i>Generate QR Code
            </button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
    document.getElementById('qrType').addEventListener('change', function() {
        document.querySelectorAll('.qrForm').forEach(form => form.style.display = 'none');
        const selectedForm = this.value === 'business_card' ? 'businessCardForm' :
                             this.value === 'event_ticket' ? 'eventTicketForm' :
                             this.value === 'menu_card' ? 'menuCardForm' :
                             this.value === 'payment' ? 'paymentForm' :
                             this.value === 'social_media' ? 'socialMediaForm' : '';
                             
        if (selectedForm) {
            document.getElementById(selectedForm).style.display = 'block';
        }
    });
</script>

</body>
</html>
