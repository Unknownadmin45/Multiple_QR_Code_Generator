<?php
include('phpqrcode/qrlib.php');
$qrType = $_POST['qrType'];
$data = '';

switch ($qrType) {
    case 'business_card':
        $data = "Name: " . $_POST['name'] . "\n";
        $data .= "Job Title: " . $_POST['jobTitle'] . "\n";
        $data .= "Company: " . $_POST['company'] . "\n";
        $data .= "Phone: " . $_POST['phone'] . "\n";
        $data .= "Email: " . $_POST['email'] . "\n";
        $data .= "Website: " . $_POST['website'];
        break;
    case 'event_ticket':
        $data = "Event: " . $_POST['eventName'] . "\n";
        $data .= "Date: " . $_POST['date'] . "\n";
        $data .= "Time: " . $_POST['time'] . "\n";
        $data .= "Location: " . $_POST['location'] . "\n";
        $data .= "Ticket Info: " . $_POST['ticketInfo'];
        break;
    case 'menu_card':
        $data = "Restaurant: " . $_POST['restaurantName'] . "\n";
        $data .= "Address: " . $_POST['address'] . "\n";
        $data .= "Contact: " . $_POST['contact'] . "\n";
        $data .= "Menu Items: " . $_POST['menuItems'] . "\n";
        $data .= "Order Link: " . $_POST['orderingLink'];
        break;
    case 'payment':
        $data = "Payment Amount: " . $_POST['paymentAmount'] . "\n";
        $data .= "Payment Method: " . $_POST['paymentMethod'] . "\n";
        $data .= "Payment Details: " . $_POST['paymentDetails'];
        break;

    case 'social_media':
        $data = "Social Media Platform: " . $_POST['socialMediaPlatform'] . "\n";
        $data .= "User  Handle/Link: " . $_POST['userHandle'];
        break;

    default:
        $data = "Invalid QR Code Type.";
        break;    
}

// Generate QR code
$directory = 'qrcodes';

if (!is_dir($directory)) {
    mkdir($directory, 0755, true);
}

$qrFilePath = $directory . '/' . uniqid() . '.png';
QRcode::png($data, $qrFilePath, QR_ECLEVEL_L, 5);

// Save data to database
$conn = new mysqli('localhost', 'root', '', 'qr_code_generator');
$sql = "INSERT INTO qr_codes (qr_type, data, file_path) VALUES ('$qrType', '$data', '$qrFilePath')";
$conn->query($sql);
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generated QR Code</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .result-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            padding: 30px;
            text-align: center;
            max-width: 500px;
            width: 100%;
        }
        h3 {
            color: #2c3e50;
            margin-bottom: 25px;
            font-weight: 600;
            position: relative;
            padding-bottom: 15px;
        }
        h3::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 50px;
            height: 3px;
            background: #3498db;
        }
        .qr-image {
            padding: 20px;
            background: #f8f9fa;
            border-radius: 10px;
            margin: 20px 0;
            box-shadow: 0 0 10px rgba(0,0,0,0.05);
        }
        .qr-image img {
            max-width: 250px;
            height: auto;
        }
        .btn-download {
            background: #2ecc71;
            border: none;
            padding: 12px 30px;
            border-radius: 25px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
            margin-top: 20px;
        }
        .btn-download:hover {
            background: #27ae60;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(46, 204, 113, 0.3);
        }
        .btn-back {
            background: #3498db;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 25px;
            font-weight: 500;
            margin-top: 15px;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
        }
        .btn-back:hover {
            background: #2980b9;
            color: white;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="result-container">
        <h3><i class="fas fa-check-circle text-success mr-2"></i>QR Code Generated Successfully!</h3>
        <div class="qr-image">
            <img src="<?php echo $qrFilePath; ?>" alt="QR Code">
        </div>
        <div class="action-buttons">
            <a href="download.php?file=<?php echo $qrFilePath; ?>" class="btn btn-download">
                <i class="fas fa-download mr-2"></i>Download QR Code
            </a>
            <br>
            <a href="index.php" class="btn-back mt-3">
                <i class="fas fa-arrow-left mr-2"></i>Generate Another QR Code
            </a>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>