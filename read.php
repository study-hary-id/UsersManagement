<?php

// Initialize the session.
session_start();
 
// Check if the user is logged in, if not then redirect to login page.
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

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

require_once "header.php";
?>
<body>
    <div class="container">
        <h1 class="my-5 text-center">View Record</h1>
        <div class="card-deck mb-3">
            <div class="card mb-4 shadow-sm">
                <div class="card-header text-center">
                    <h4 class="my-0 font-weight-normal">
                        User Details - <?php echo $row["id"] ?>
                    </h4>
                </div>

                <div class="card-body">
                    <div class="form-row">
                        <div class="col-sm form-group">
                            <label>Username</label>
                            <p><b><?php echo $username; ?></b></p>
                        </div>
                        <div class="col-sm form-group">
                            <label>Fullname</label>
                            <p><b><?php echo $full_name; ?></b></p>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="col-sm form-group">
                            <label>Email</label>
                            <p><b><?php echo $email; ?></b></p>
                        </div>
                        <div class="col-sm form-group">
                            <label>Address</label>
                            <p><b><?php echo $address; ?></b></p>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="col-sm form-group">
                            <label>Salary</label>
                            <p><b><?php echo $salary; ?></b></p>
                        </div>
                        <div class="col-sm form-group">
                            <label>Phone Number</label>
                            <p><b><?php echo $phone_number; ?></b></p>
                        </div>
                    </div>

                    <p class="text-center">
                        <a
                            href="welcome.php"
                            class="btn btn-block btn-outline-primary"
                        >Back</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>