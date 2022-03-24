<?php

// Initialize the session.
session_start();

require_once "header.php"
?>
<body>
    <div class="welcome container text-center">
        <h1 class="my-5">
            Hi, there. Welcome to our site.
        </h1>

        <?php if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) : ?>
        <div class="row" style="gap: 1rem">
            <a href="login.php" class="col-sm btn btn-primary">
                Login
            </a>
            <a href="register.php" class="col-sm btn btn-link">
                Register
            </a>
        </div>
        <?php endif ?>
    </div>
</body>
</html>