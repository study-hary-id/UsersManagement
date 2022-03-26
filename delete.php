<?php

// Initialize the session.
session_start();
 
// Check if the user is logged in, if not then redirect to login page.
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

// Process delete operation after confirmation.
if (isset($_POST["id"]) && !empty($_POST["id"])) {
    // Include config file.
    require_once "config.php";
    
    // Prepare a delete statement.
    $sql = "DELETE FROM users WHERE id = ?";
    
    if ($stmt = mysqli_prepare($link, $sql)) {
        // Bind variables to the prepared statement as parameters.
        mysqli_stmt_bind_param($stmt, "i", $param_id);
        
        // Set parameters.
        $param_id = trim($_POST["id"]);
        
        // Attempt to execute the prepared statement.
        if (mysqli_stmt_execute($stmt)) {
            // Records deleted successfully. Redirect to landing page.
            header("location: welcome.php");
            exit();
        } else {
            echo "Oops! Something went wrong. Please try again later.";
        }
    }

    // Close statement
    mysqli_stmt_close($stmt);

    // Close connection
    mysqli_close($link);
} else {
    // Check existence of id parameter.
    if (empty(trim($_GET["id"]))) {
        // URL doesn't contain id parameter. Redirect to error page.
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
            class="form-signin form-signin-sm"
            method="post"
        >
            <h1 class="h3 my-3 font-weight-normal text-center">
                Delete Record
            </h1>
            <div class="alert alert-danger">
                <input type="hidden" name="id" value="<?php echo trim($_GET["id"]); ?>"/>
                <p>Are you sure you want to delete this user?</p>
                <div class="form-group form-row" style="gap: 1rem">
                    <input type="submit" value="Yes" class="col btn btn-danger">
                    <a href="welcome.php" class="col btn btn-secondary">No</a>
                </div>
            </div>
        </form>
    </div>
</body>
</html>