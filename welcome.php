<?php

// Initialize the session.
session_start();
 
// Check if the user is logged in, if not then redirect to login page.
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

require_once "config.php";
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

        <div class="my-3 d-flex flex-wrap justify-content-between">
            <h2 class="mb-3">Users Details</h2>
            <a href="create.php" class="btn btn-success" style="height: 38px">
                <i class="fa fa-plus"></i> Add New User
            </a>
        </div>

        <div class="table-responsive-xl">
        <?php

        // Attempt select query execution.
        $sql = "SELECT * FROM users";
        if ($result = mysqli_query($link, $sql)) {
            if (mysqli_num_rows($result) > 0) {

                // Users table view
                echo '<table class="table table-bordered table-striped">';
                    echo '<thead>';
                        echo '<tr>';
                            echo '<th>#</th>';
                            echo '<th>Username</th>';
                            echo '<th>Fullname</th>';
                            echo '<th>Email</th>';
                            echo '<th>Salary</th>';
                            echo '<th>Action</th>';
                        echo '</tr>';
                    echo '</thead>';
                    echo '<tbody>';

                    while ($row = mysqli_fetch_array($result)) {
                        echo '<tr>';
                            echo '<td>' . $row['id'] . '</td>';
                            echo '<td>' . $row['username'] . '</td>';
                            echo '<td>' . $row['full_name'] . '</td>';
                            echo '<td>' . $row['email'] . '</td>';
                            echo '<td>' . $row['salary'] . '</td>';
                            echo '<td>';
                                echo '<a href="read.php?id='. $row['id'] .'" class="mr-3" title="View Record" data-toggle="tooltip"><span class="fa fa-eye"></span></a>';
                                echo '<a href="update.php?id='. $row['id'] .'" class="mr-3" title="Update Record" data-toggle="tooltip"><span class="fa fa-pencil"></span></a>';
                                echo '<a href="delete.php?id='. $row['id'] .'" title="Delete Record" data-toggle="tooltip"><span class="fa fa-trash"></span></a>';
                            echo "</td>";
                        echo "</tr>";
                    }
                    echo "</tbody>";                            
                echo "</table>";

                // Free result set
                mysqli_free_result($result);
            } else{
                echo '<div class="alert alert-danger"><em>No records were found.</em></div>';
            }
        } else{
            echo "Oops! Something went wrong. Please try again later.";
        }

        // Close connection
        mysqli_close($link);
        ?>
        </div>
    </div>
    <script>
        $(document).ready(function () {
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
</body>
</html>