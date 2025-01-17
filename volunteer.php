<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Form</title>
</head>
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
    }

    h1 {
    text-align: center;  
    }

    input {
    padding: 10px;
    width: 100%;
    font-size: 17px;
    font-family: Raleway;
    border: 1px solid #aaaaaa;
    }

    /* Mark input boxes that gets an error on validation: */
    input.invalid {
    background-color: #ffdddd;
    }

    /* Hide all steps by default: */
    .tab {
    display: none;
    }

    button {
    background-color: #04AA6D;
    color: #ffffff;
    border: none;
    padding: 10px 20px;
    font-size: 17px;
    font-family: Raleway;
    cursor: pointer;
    }

    button:hover {
    opacity: 0.8;
    }

    #prevBtn {
    background-color: #bbbbbb;
    }

    /* Make circles that indicate the steps of the form: */
    .step {
    height: 15px;
    width: 15px;
    margin: 0 2px;
    background-color: #bbbbbb;
    border: none;  
    border-radius: 50%;
    display: inline-block;
    opacity: 0.5;
    }

    .step.active {
    opacity: 1;
    }

    /* Mark the steps that are finished and valid: */
    .step.finish {
    background-color: #04AA6D;
    }
    </style>
<body>
    <div class="container">
        <?php 
        if (isset($_POST["submit"])) 
        {


            $fname = $_POST['fname'];
            $email = $_POST['email'];
            $phone = $_POST['phone'];
            $age = $_POST['age'];
            $address = $_POST['address'];
            $skills = $_POST['skills'];
            $comment = $_POST['comment'];
            $options = $_POST['options'];

            $errors = array();  
           
            if (empty($fname) OR empty($email)) 
            { 
                array_push($errors, "All fields are required"); 
            }      

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) 
            { 
                array_push($errors, "Email is not valid");         
            }


            require_once "database.php";
            $sql = "SELECT * FROM volunteer_info WHERE email = '$email'";
            $result = mysqli_query($conn, $sql);
            $rowCount = mysqli_num_rows($result);
            if($rowCount>0)
            {
                array_push($errors, "Email already Exists!");
            }

            if (count($errors)>0) 
            {
                foreach ($errors as $error) 
                {
                    echo "<div class='alert alert-danger'>$error</div>";
                }
            }
            else
            {
                require_once "database.php";
                $sql = "INSERT INTO volunteer_info (fname, email, phone, age, address, skills, comment, options) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt = mysqli_stmt_init($conn);
                $prepareStmt = mysqli_stmt_prepare($stmt,$sql);
                if($prepareStmt) 
                {
                    mysqli_stmt_bind_param($stmt,"ssiissss",$fname, $email, $phone, $age, $address, $skills, $comment, $options);
                    mysqli_stmt_execute($stmt); 
                    echo "<div class='alert alert-success'>You are registered successfully.</div>"; 
                }
                else
                { 
                    die ("Something went wrong");
            
                }
            }
        }

        ?>
               
                    <form id="adoptForm" action="volunteer.php" method="post">
                    <h1>Volunteer Form</h1>
                <!-- One "tab" for each step in the form: -->
                    <p>
                        <img src="images/logo.png" height="200px" width="200px">
                        <br>
                        Fill up the required details.
                        <br>
                        We will get back to you shortly via Email ID and Phone Number
                        <br>
                        [**Please make sure you have entered correct Email ID and Phone Number.]
                        <br> 
                        Do keep logging in using your username and password for frequent updates.
                        <br>
                        For any queries you can write to us on <strong> littlevoices@gmail.com</strong>

                    </p>
                    <br>
                    <p><input placeholder="Full Name"  name="fname"></p>
                    Contact Info:
                    <p><input placeholder="E-mail"  name="email"></p>
                    <p><input placeholder="Phone Number"  name="phone"></p>
                    Age (in years):
                    <p><input placeholder="Age"  name="age"></p>
                    Address:
                    <p><input placeholder="Address"  name="address"></p>
                    Your skills:
                    <p><textarea name="skills"></textarea></p>
                    <br>
                    Additional Comments:
                    <p><textarea name="comment"></textarea></p>
                    <br>
                    Available on:
                    <select name="options">
                    <option value="Weekdays">Weekdays</option>
                    <option value="Weekends">Weekends</option>
                    <option value="Anyday">Anyday</option>
                    </select> 
                    <p><input type="submit" class="btn btn-primary" value="Volunteer" name="submit"><p>
                </form>
    </div>
</body> 
</html>
