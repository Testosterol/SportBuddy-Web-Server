<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>NETTUTS > Sign up</title>
</head>
<body>
<!-- start header div -->
<div id="header">
    <h3>SportBuddy Account Activation</h3>
</div>

<div id="wrap">
<?php
    $con = mysqli_connect("localhost", "id170806_sportbuddy", "asdasdasd", "id170806_startingandroind_db") or die(mysqli_connect_errno()); // Connect to database server(localhost) with username and password.
    if (isset($_GET['email']) && !empty($_GET['email']) AND isset($_GET['verification']) && !empty($_GET['verification'])) {
        $email = mysqli_real_escape_string($con,$_GET['email']); // Set email variable
        $verification = mysqli_real_escape_string($con,$_GET['verification']); // Set hash variable
        $search = mysqli_query($con ,"SELECT user_email, verification FROM users WHERE user_email='" . $email . "' AND verification='" . $verification . "'") or die(mysqli_connect_errno());
        $match = mysqli_num_rows($search);
        $isActive = "Yes";
        if ($match > 0) {
            $result = mysqli_query($con,"UPDATE users SET is_active='$isActive' WHERE user_email='$email'") or die(mysqli_connect_errno());
            echo("Account was successfully activated");
        } else {
            echo("Couldnt activate the account");
        }
    }
    ?>
</div>
</body>
</html>