<?php
// Database connection details
$host = "localhost";
$db_user = "root"; // Your MySQL username
$db_pass = "P_ved@2004"; // Your MySQL password
$db_name = "ngo"; // Your database name

// Create connection
$conn = new mysqli($host, $db_user, $db_pass, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Process form data if submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the data from the request
    $name = $_POST['fname'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $amount = $_POST['amount'];
    $payment_id = $_POST['payment_id'];
    $payment_status = $_POST['payment_status'];

    // Insert data into the donation table
    $sql = "INSERT INTO donation (name, email, phone, amount, payment_id, payment_status) 
            VALUES ('$name', '$email', '$phone', '$amount', '$payment_id', '$payment_status')";

    if ($conn->query($sql) === TRUE) {
        // Redirect to success page if donation recorded successfully
        header("Location: success.php");
        exit(); // Ensure no further code is executed
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    // Close connection
    $conn->close();
    exit; // Stop further processing
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donation Information Form</title>
    <link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            background-color: #e9e466;
        }

        #adoptForm {
            background-color: #ffffff;
            margin: 100px auto;
            font-family: Raleway;
            padding: 40px;
            width: 70%;
            min-width: 300px;
            opacity: 0.9;
            border-radius: 10px;
        }

        h1 {
            text-align: center;  
        }

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

        button:hover {
            opacity: 0.8;
        }

        /* Loader Styles */
        .loader {
            border: 8px solid #f3f3f3; /* Light grey */
            border-top: 8px solid #04AA6D; /* Green */
            border-radius: 50%;
            width: 60px;
            height: 60px;
            animation: spin 1s linear infinite;
            display: none; /* Hidden by default */
            position: fixed;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            z-index: 10; /* Ensure it appears above other elements */
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Pop-up Modal Styling */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5); /* Semi-transparent background */
        }

        .modal-content {
            position: relative;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: transparent; /* Make background transparent */
            text-align: center;
        }

        .success-message {
            font-size: 24px;
            font-family: 'Raleway', sans-serif;
            color: #04AA6D;
            margin-top: 10px;
            display: none; /* Hide success message initially */
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
            width: 70px; /* Width of the square box */
            height: 70px; /* Height of the square box */
            background-color: white; /* White background for the square */
            display: inline-block;
            border-radius: 5px; /* Optional: Rounded corners */
            padding: 10px; /* Padding around the checkmark */
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2); /* Optional: Shadow for depth */
        }
    </style>

    <script src="https://www.paypal.com/sdk/js?client-id=AZgUvqDidJv_eyD6j5IMJOfuK1MnoYuy6t0Z3oPb9-NTPk5HX7GExNgmcD-A4Rh_pcdz3DbjS_Ra1oJX&currency=USD"></script> <!-- PayPal SDK -->
</head>
<body>
    <div class="container">
        <form id="adoptForm" action="" method="post">
            <h1>Donation Information Form</h1>
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
            <p><input placeholder="Amount" name="amount" id="amount" type="number" required></p>
            <p>Mode of payment:</p>
            <select name="options" id="payment-option" required>
                <option value="" disabled selected>Select Payment Method</option>
                <option value="PayPal">PayPal</option>
            </select>

            <div id="paypal-button-container" style="display: none;"></div> <!-- PayPal button -->
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
            
            <p><button type="button" class="btn btn-primary" id="submit-button">Donate</button></p>
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

        // On clicking the submit button
        document.getElementById('submit-button').addEventListener('click', function() {
            var form = document.getElementById('adoptForm');
            var fname = document.getElementById('fname').value;
            var email = document.getElementById('email').value;
            var phone = document.getElementById('phone').value;
            var amount = document.getElementById('amount').value;

            // Validate form fields
            if (!fname || !email || !phone || !amount) {
                alert('Please fill in all fields.');
                return;
            }

            // Show loader
            document.getElementById('loader').style.display = 'block';

            // Render PayPal button
            paypal.Buttons({
                createOrder: function(data, actions) {
                    return actions.order.create({
                        purchase_units: [{
                            amount: {
                                value: amount
                            }
                        }]
                    });
                },
                onApprove: function(data, actions) {
                    return actions.order.capture().then(function(details) {
                        // Hide loader
                        document.getElementById('loader').style.display = 'none';

                        // Show success pop-up modal
                        showSuccessModal();

                        // Add hidden input fields and submit form
                        var hiddenPaymentId = document.createElement('input');
                        hiddenPaymentId.setAttribute('type', 'hidden');
                        hiddenPaymentId.setAttribute('name', 'payment_id');
                        hiddenPaymentId.setAttribute('value', data.orderID);
                        form.appendChild(hiddenPaymentId);

                        var hiddenPaymentStatus = document.createElement('input');
                        hiddenPaymentStatus.setAttribute('type', 'hidden');
                        hiddenPaymentStatus.setAttribute('name', 'payment_status');
                        hiddenPaymentStatus.setAttribute('value', "Completed");
                        form.appendChild(hiddenPaymentStatus);

                        setTimeout(function() {
                            form.submit(); // Submit the form after a delay
                        }, 3000);
                    });
                },
                onCancel: function(data) {
                    // Hide loader if cancelled
                    document.getElementById('loader').style.display = 'none';
                    alert('Transaction was cancelled');
                },
                onError: function(err) {
                    // Hide loader on error
                    document.getElementById('loader').style.display = 'none';
                    alert('An error occurred');
                }
            }).render('#paypal-button-container');
        });

        // Function to show success modal pop-up
        function showSuccessModal() {
            var modal = document.getElementById('success-popup');
            var successMessage = document.querySelector('.success-message');

            modal.style.display = 'block'; // Show modal
            successMessage.style.display = 'block'; // Show success message
        }
    </script>
</body>
</html>