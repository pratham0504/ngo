<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Form</title>
    <script src="https://www.paypal.com/sdk/js?client-id=AZgUvqDidJv_eyD6j5IMJOfuK1MnoYuy6t0Z3oPb9-NTPk5HX7GExNgmcD-A4Rh_pcdz3DbjS_Ra1oJX&currency=USD"></script>
    <link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">
    <style>
        * { box-sizing: border-box; }
        body { background-color: #e9e466; font-family: Raleway; }
        #adoptForm {
            background-color: #ffffff;
            margin: 100px auto;
            padding: 40px;
            width: 70%;
            min-width: 300px;
            opacity: 0.9;
            border-radius: 10px;
        }
        h1 { text-align: center; }
        input, select {
            padding: 10px;
            width: 100%;
            font-size: 17px;
            font-family: Raleway;
            border: 1px solid #aaaaaa;
            border-radius: 5px;
        }
        button {
            background-color: #04AA6D;
            color: #ffffff;
            border: none;
            padding: 10px 20px;
            font-size: 17px;
            font-family: Raleway;
            cursor: pointer;
            border-radius: 5px;
        }
        button:hover { opacity: 0.8; }
        #prevBtn { background-color: #bbbbbb; }
        #paypal-button-container {
            display: none;
            margin-top: 20px;
            background-color: #ffffff;
            padding: 10px;
            width: 250px;
            border: 1px solid #aaaaaa;
            border-radius: 8px;
            margin: 10px auto;
        }
        .loader {
            border: 8px solid #f3f3f3;
            border-top: 8px solid #04AA6D;
            border-radius: 50%;
            width: 60px;
            height: 60px;
            animation: spin 1s linear infinite;
            display: none;
            position: fixed;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            z-index: 10;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
        }
        .modal-content {
            position: relative;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: transparent;
            text-align: center;
        }
        .success-message {
            font-size: 24px;
            font-family: 'Raleway', sans-serif;
            color: #04AA6D;
            margin-top: 10px;
            display: none;
        }
        .checkmark {
            width: 50px;
            height: 50px;
            fill: none;
            stroke: #04AA6D;
            stroke-width: 4;
            stroke-linecap: round;
            stroke-linejoin: round;
            display: inline-block;
            margin-bottom: 10px;
        }
        .checkmark-container {
            width: 70px;
            height: 70px;
            background-color: white;
            display: inline-block;
            border-radius: 5px;
            padding: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>
<body>
    <div class="container">
        <form id="adoptForm" method="post">
            <h1>Membership Form</h1>
            <p>
                <img src="images/logo.png" height="200px" width="200px">
                <br>
                Fill up the required details.
                <br>
                We will get back to you shortly via Email ID and Phone Number.
                <br>
                [**Please make sure you have entered the correct Email ID and Phone Number.]
                <br>
                For any queries, you can write to us at <strong>littlevoices@gmail.com</strong>
            </p>
            <p><input placeholder="Full Name" name="fname" id="fname" required></p>
            <p><input placeholder="E-mail" name="email" type="email" id="email" required></p>
            <p><input placeholder="Phone Number" name="phone" id="phone" required></p>
            <p>Birthday:</p>
            <p><input type="date" name="dob" id="dob" required></p>
            <p><input placeholder="Address" name="address" id="address" required></p>

            <p>Membership Duration:</p>
            <select name="membership-duration" id="membership-duration" required>
                <option value="" disabled selected>Select Membership Duration</option>
                <option value="1-month">1 Month - $100</option>
                <option value="6-months">6 Months - $500</option>
                <option value="1-year">1 Year - $1000</option>
            </select>

            <p><input placeholder="Amount" name="amount" id="amount" type="number" value="100" required readonly></p>

            <p>Mode of payment:</p>
            <select name="options" id="payment-option" required>
                <option value="" disabled selected>Select Payment Method</option>
                <option value="PayPal">PayPal</option>
            </select>

            <div id="paypal-button-container"></div>
            <div id="success-popup" class="modal">
                <div class="modal-content">
                    <div class="checkmark-container">
                        <svg class="checkmark" viewBox="0 0 52 52">
                            <circle cx="26" cy="26" r="25" stroke="#04AA6D" fill="none"/>
                            <path d="M14.1 27.2l7.1 7.2 16.7-16.8" />
                        </svg>
                    </div>
                    <div class="success-message">Payment Successful!</div>
                </div>
            </div>

            <div class="loader" id="loader"></div> <!-- Loader element -->

            <input type="hidden" name="payment_id" id="payment_id">
            <input type="hidden" name="payment_status" id="payment_status">

            <p><button type="button" id="submit-button">Join Us</button></p>
        </form>
    </div>

    <script>
        // Show PayPal button when payment option is selected
        document.getElementById('payment-option').addEventListener('change', function() {
            if (this.value === 'PayPal') {
                document.getElementById('paypal-button-container').style.display = 'block';
            } else {
                document.getElementById('paypal-button-container').style.display = 'none';
            }
        });

        // Automatically update amount based on membership duration
        document.getElementById('membership-duration').addEventListener('change', function() {
            var amountField = document.getElementById('amount');
            switch (this.value) {
                case '1-month':
                    amountField.value = 100;
                    break;
                case '6-months':
                    amountField.value = 500;
                    break;
                case '1-year':
                    amountField.value = 1000;
                    break;
                default:
                    amountField.value = 100;
            }
        });

        // On clicking the submit button
        document.getElementById('submit-button').addEventListener('click', function() {
            var form = document.getElementById('adoptForm');
            var fname = document.getElementById('fname').value;
            var email = document.getElementById('email').value;
            var phone = document.getElementById('phone').value;
            var amount = document.getElementById('amount').value;

            // Validate form fields
            if (!fname || !email || !phone || !amount) {
                alert('Please fill all the required fields.');
                return;
            }

            // Check if PayPal is selected for payment
            if (document.getElementById('payment-option').value === 'PayPal') {
                document.getElementById('loader').style.display = 'block'; // Show loader
                paypal.Buttons({
                    createOrder: function(data, actions) {
                        return actions.order.create({
                            purchase_units: [{
                                amount: { value: amount }
                            }]
                        });
                    },
                    onApprove: function(data, actions) {
                        return actions.order.capture().then(function(details) {
                            document.getElementById('payment_id').value = details.id;
                            document.getElementById('payment_status').value = 'Paid';
                            form.submit();
                        });
                    },
                    onError: function(err) {
                        document.getElementById('loader').style.display = 'none';
                        alert('Payment could not be completed. Please try again.');
                    }
                }).render('#paypal-button-container');
            } else {
                form.submit(); // Submit the form if no online payment is selected
            }
        });
    </script>
</body>
</html>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Capture the form data
    $fname = $_POST['fname'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $dob = $_POST['dob'];
    $address = $_POST['address'];
    $membershipDuration = $_POST['membership-duration'];
    $amount = $_POST['amount'];
    $paymentId = $_POST['payment_id'];
    $paymentStatus = $_POST['payment_status'];

    // Insert the form data into the MySQL database
    $host = 'localhost';
    $db = 'ngo';
    $user = 'root';
    $pass = 'P_ved@2004';
    $conn = new mysqli($host, $user, $pass, $db);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("INSERT INTO member_info (name, email, phone, dob, address, membership_duration, amount, payment_id, payment_status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssss", $fname, $email, $phone, $dob, $address, $membershipDuration, $amount, $paymentId, $paymentStatus);

    if ($stmt->execute()) {
        echo "<script>
                document.getElementById('success-popup').style.display = 'block';
                document.getElementById('loader').style.display = 'none';
                setTimeout(function() { window.location.reload(); }, 3000);
              </script>";
    } else {
        echo "<script>alert('There was an error submitting the form. Please try again.');</script>";
    }

    $stmt->close();
    $conn->close();
}

?>