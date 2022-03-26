<?php

// Initialize the session.
session_start();
 
// Check if the user is logged in, if not then redirect to login page.
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

// Include config file.
require_once "config.php";
 
// Define variables and initialize with empty values.
$email = $username = $password = "";
$full_name = $address = $phone_number = $salary = "";

$email_err = $username_err = $password_err = "";
$full_name_err = $salary_err = "";

// Processing form data when form is submitted.
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (empty(trim($_POST["email"]))) {
        $email_err = "Please enter an email.";
    } elseif (!preg_match('/^[a-z0-9.@]+$/', trim($_POST["email"]))) {
        $email_err = "Email can only contain letters, numbers, dot, and @.";
    } else {
        // Prepare a select statement.
        $sql = "SELECT id FROM users WHERE email = ?";
        
        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters.
            mysqli_stmt_bind_param($stmt, "s", $param_email);
            
            // Set parameters.
            $param_email = trim($_POST["email"]);
            
            // Attempt to execute the prepared statement.
            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt); /* store result */
                
                if (mysqli_stmt_num_rows($stmt) == 1) {
                    $email_err = "This email is already taken.";
                } else {
                    $email = trim($_POST["email"]);
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement.
            mysqli_stmt_close($stmt);
        }
    }

    if (empty(trim($_POST["username"]))) {
        $username_err = "Please enter a username.";
    } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', trim($_POST["username"]))) {
        $username_err = "Username can only contain letters, numbers, and underscores.";
    } else {
        // Prepare a select statement.
        $sql = "SELECT id FROM users WHERE username = ?";
        
        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters.
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            // Set the parameters.
            $param_username = trim($_POST["username"]);
            
            // Attempt to execute the prepared statement.
            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt); /* store result */
                
                if (mysqli_stmt_num_rows($stmt) == 1) {
                    $username_err = "This username is already taken.";
                } else {
                    $username = trim($_POST["username"]);
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement.
            mysqli_stmt_close($stmt);
        }
    }

    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter a password.";
    } elseif (strlen(trim($_POST["password"])) < 8) {
        $password_err = "Password must have at least 8 characters.";
    } else {
        $password = trim($_POST["password"]);
    }

    if (empty(trim($_POST["full-name"]))) {
        $full_name_err = "Please enter a fullname.";
    } else {
        $full_name = strtoupper(trim($_POST["full-name"]));
    }

    if (!empty(trim($_POST["address"]))) {
        $address = trim($_POST["address"]);
    }

    if (!empty(trim($_POST["phone-number"]))) {
        $phone_number = trim($_POST["phone-number"]);
    }

    if (empty(trim($_POST["salary"]))) {
        $salary_err = "Please enter the salary amount.";
    } elseif (!ctype_digit($_POST["salary"])) {
        $salary_err = "Please enter a positive integer value.";
    } else {
        $salary = (int)trim($_POST["salary"]);
    }

    // Check input errors before inserting in database.
    if (empty($email_err) && empty($username_err) && empty($password_err)
        && empty($full_name_err) && empty($salary_err)) {

        // Prepare an insert statement.
        $sql = "INSERT INTO users (
                    email, username, password, full_name, address, phone_number, salary
                )
                VALUES (?, ?, ?, ?, ?, ?, ?)";

        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters.
            mysqli_stmt_bind_param(
                $stmt,
                "ssssssi",
                $param_email,
                $param_username,
                $param_password,
                $param_full_name,
                $param_address,
                $param_phone_number,
                $param_salary
            );
            
            // Set parameters.
            $param_email = $email;
            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash.
            $param_full_name = $full_name;
            $param_address = $address;
            $param_phone_number = $phone_number;
            $param_salary = $salary;

            // Attempt to execute the prepared statement.
            if (mysqli_stmt_execute($stmt)) {
                header("location: welcome.php");
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement.
            mysqli_stmt_close($stmt);
        }
    }

    // Close connection.
    mysqli_close($link);
}

require_once "header.php";
?>
<body>
    <div class="container">
        <form
            action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>"
            class="form-signin"
            method="post"
        >
            <h1 class="h3 my-3 font-weight-normal text-center">
                Create Record
            </h1>
            <p class="text-center">
                Please fill this form and submit to add employee record to the database.
            </p>

            <div class="form-row">
                <div class="col-sm form-group">
                    <label>Email</label>
                    <input
                        type="email"
                        name="email"
                        class="form-control
                        <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>"
                        value="<?php echo $email; ?>"
                    >
                    <span class="invalid-feedback"><?php echo $email_err; ?></span>
                </div>

                <div class="col-sm form-group">
                    <label>Username</label>
                    <input
                        type="text"
                        name="username"
                        class="form-control
                        <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>"
                        value="<?php echo $username; ?>"
                    >
                    <span class="invalid-feedback"><?php echo $username_err; ?></span>
                </div>
            </div>
  
            <div class="form-row">
                <div class="col-sm form-group">
                    <label>Password</label>
                    <input
                        type="password"
                        name="password"
                        class="form-control
                        <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>"
                        value="<?php echo $password; ?>"
                    >
                    <span class="invalid-feedback"><?php echo $password_err; ?></span>
                </div>

                <div class="col-sm form-group">
                    <label>Full Name</label>
                    <input
                        type="text"
                        name="full-name"
                        class="form-control
                        <?php echo (!empty($full_name_err)) ? 'is-invalid' : ''; ?>"
                        value="<?php echo $full_name; ?>"
                    >
                    <span class="invalid-feedback"><?php echo $full_name_err; ?></span>
                </div>
            </div>

            <div class="form-row">
                <div class="col-sm form-group">
                    <label>Salary</label>
                    <input
                        type="number"
                        name="salary"
                        class="form-control
                        <?php echo (!empty($salary_err)) ? 'is-invalid' : ''; ?>"
                        value="<?php echo $salary; ?>"
                        min="0"
                        max="1000000000"
                    >
                    <span class="invalid-feedback"><?php echo $salary_err; ?></span>
                </div>

                <div class="col-sm form-group">
                    <label>Phone Number</label>
                    <input
                        type="text"
                        name="phone-number"
                        class="form-control"
                        value="<?php echo $phone_number; ?>"
                    >
                </div>
            </div>

            <div class="form-group">
                <label>Address</label>
                <textarea
                    name="address"
                    class="form-control"
                ><?php echo $address; ?></textarea>
            </div>

            <div class="form-group">
                <input type="submit" class="btn btn-primary px-4" value="Submit">
                <!-- <input type="reset" class="btn btn-secondary ml-2" value="Reset"> -->
                <a href="welcome.php" class="btn btn-secondary px-4 ml-2">Cancel</a>
            </div>
        </form>
    </div>    
</body>
</html>