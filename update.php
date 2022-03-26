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
$email = $username = $full_name = "";
$address = $phone_number = $salary = "";

$email_err = $username_err = $full_name_err = $salary_err = "";
$id = trim($_GET["id"]);

// Processing form data when form is submitted.
if (isset($_POST["id"]) && !empty($_POST["id"])) {

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
                $result = mysqli_stmt_get_result($stmt);
                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

                if (mysqli_num_rows($result) == 1 && $row["id"] != $_POST["id"]) {
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
                $result = mysqli_stmt_get_result($stmt);
                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                
                if (mysqli_num_rows($result) == 1 && $row["id"] != $_POST["id"]) {
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
    if (empty($email_err) && empty($username_err)
        && empty($full_name_err) && empty($salary_err)) {

        // Prepare an insert statement.
        $sql = "UPDATE users
                SET email = ?,
                    username = ?,
                    full_name = ?,
                    address = ?,
                    phone_number = ?,
                    salary = ?
                WHERE id = ?";

        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters.
            mysqli_stmt_bind_param(
                $stmt,
                "sssssii",
                $param_email,
                $param_username,
                $param_full_name,
                $param_address,
                $param_phone_number,
                $param_salary,
                $param_id
            );
            
            // Set parameters.
            $param_email = $email;
            $param_username = $username;
            $param_full_name = $full_name;
            $param_address = $address;
            $param_phone_number = $phone_number;
            $param_salary = $salary;
            $param_id = trim($_POST["id"]);

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

} else {
    // Check existence of id parameter before processing further
    if (isset($_GET["id"]) && !empty(trim($_GET["id"]))) {
        // Include config file
        require_once "config.php";
        
        // Prepare a select statement
        $sql = "SELECT * FROM users WHERE id = ?";
        
        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "i", $param_id);
            
            // Set parameters
            $param_id = trim($_GET["id"]);
            
            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                $result = mysqli_stmt_get_result($stmt);
        
                if (mysqli_num_rows($result) == 1) {
                    /* Fetch result row as an associative array. Since the result set
                    contains only one row, we don't need to use while loop */
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

                    // Retrieve individual field value
                    $email = $row["email"];
                    $username = $row["username"];
                    $full_name = $row["full_name"];
                    $address = $row["address"];
                    $salary = $row["salary"];
                    $phone_number = $row["phone_number"];
                } else {
                    // URL doesn't match with the database. Redirect to error page
                    header("location: error.php");
                    exit();
                }
                
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
        
        // Close connection
        mysqli_close($link);

    } else{
        // URL doesn't contain id parameter. Redirect to error page
        header("location: error.php");
        exit();
    }
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
                Update Record
            </h1>
            <p class="text-center">
                Please edit the input values and submit to update the employee record.
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
            </div>

            <div class="form-row">
                <div class="col-sm form-group">
                    <label>Phone Number</label>
                    <input
                        type="text"
                        name="phone-number"
                        class="form-control"
                        value="<?php echo $phone_number; ?>"
                    >
                </div>

                <div class="col-sm form-group">
                    <label>Address</label>
                    <textarea
                        name="address"
                        class="form-control"
                    ><?php echo $address; ?></textarea>
                </div>
            </div>

            <div class="form-group">
                <input type="hidden" name="id" value="<?php echo $id ?>">
                <input type="submit" class="btn btn-primary px-4" value="Submit">
                <!-- <input type="reset" class="btn btn-secondary ml-2" value="Reset"> -->
                <a href="welcome.php" class="btn btn-secondary px-4 ml-2">Cancel</a>
            </div>
        </form>
    </div>    
</body>
</html>