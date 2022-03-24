<?php

// Initialize the session.
session_start();
 
// Check if the user is logged in, if not then redirect to login page.
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

require_once "header.php";
?>
<body>
    <div class="welcome container text-center">
        <h1 class="my-5">
            Hi, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>. Welcome to our site.
        </h1>
        <div class="row" style="gap: 1rem">
            <a href="reset-password.php" class="col-sm btn btn-warning">
                Reset Your Password
            </a>
            <a href="logout.php" class="col-sm btn btn-danger">
                Sign Out of Your Account
            </a>
        </div>
    </div>
</body>
</html>