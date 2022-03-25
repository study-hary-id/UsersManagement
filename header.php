<?php
$title = htmlspecialchars($_SERVER["PHP_SELF"]);

$title = substr($title, 1, strpos($title, ".") - 1);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo ucfirst($title) ?> | School Payment</title>

    <link
        rel="stylesheet"
        href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"
    >
    <link
        rel="stylesheet"
        href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css"
    >
    <style>
        body { background-color: #f5f5f5 }
        .form-signin, .welcome div {
            width: 100%;
            max-width: 660px;
            padding: 15px;
            margin: auto;
        }
        .form-signin-sm {
            max-width: 330px;
        }
        .welcome div {
            max-width: 550px;
        }
        .welcome .table-responsive-xl {
            max-width: unset;
        }
        .welcome td:last-child {
            min-width: 120px;
        }
        /* @media only screen and (max-width: 768px) {
            .welcome thead {
                display: none;
            }
            .welcome tr td:first-child {
                display: none;
            }
        } */
        @media only screen and (min-width: 768px) {
            .card .btn-block {
                width: 200px;
                margin: 0 auto;
            }
        }
        @media only screen and (max-width: 280px) {
            .form-signin {
                padding: 0;
            }

            /* Welcome page rules */
            .welcome h1 {
                font-size: 1.75rem;
            }
            .welcome h2 {
                font-size: 1.5rem;
            }
            .welcome .row {
                padding: 0;
            }

            /* View record page rules */
            .card-body {
                padding: .75rem;
            }
            .card-body .form-group {
                margin-bottom: 0;
            }
        }
    </style>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
