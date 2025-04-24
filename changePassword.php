<?php
    require_once 'config.php';

    $error = "";
    $success = "";
    $email = "";

    // Redirect to home page if user is not logged in
    if (!isset($_SESSION['loggedIn'])) {
        header("Location: index.php");
        exit;
    }

    try {

        if (isset($_POST['updatePass'])) {

            $newPass = htmlspecialchars(trim($_POST['newPassword']));
            $confirmNewPass = htmlspecialchars(trim($_POST['confirmPassword']));

            // Check if fields are empty 
            if (empty($newPass) || empty($confirmNewPass)) $error = "Please fill in all fields";
            // Check if passwords match
            else if ($newPass !== $confirmNewPass) $error = "New passwords do not match";
            
            if (empty($error)) {
                // Hash user's new password 
                $passwordHash = password_hash($newPass, PASSWORD_DEFAULT);

                // Create connection to database
                $db = getConnection();

                // Update user's password 
                $query = $db->prepare("UPDATE User SET password = ? WHERE username = ?");
                $query->bind_param("ss", $passwordHash, $_SESSION['username']);
                $query->execute();

                if ($query->affected_rows > 0) $success = "Password updated successfully";

                // Close connection to database 
                closeConnection($db);
            }
        }
    }
    catch (Exception $e) {
        error_log("Change password error: {$e->getMessage()}\n");

        // Alert user
        $message = "An error occurred. Please try again later.";
        echo "<script>alert('$message');</script>";
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <script src="https://kit.fontawesome.com/329329b608.js" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="./styles/nav.css">
    <link type="text/css" rel="stylesheet" href="./styles/auth.css">
</head>
<body>
    <?php include 'nav.php'; ?>

    <div class="container">
        <h1 class="title" style="margin-top: 80px !important; margin-bottom: 30px;"><?=$_SESSION['username']?></h1>

        <div class="content-container">
            <h2 class="form-header">Change Password</h2>

            <form id="profile-form" class="form" action="changePassword.php" method="POST" autocomplete="off">
                <!-- Profile Input Fields -->
                <div class="field">
                    <input id="profile-password" type="password" name="newPassword" placeholder="New Password" autocomplete="off" required><br>
                </div>    
                <div class="field">
                    <input id="profile-confirm-password" type="password" name="confirmPassword" placeholder="Confirm New Password" autocomplete="off" required><br>
                </div>

                <?php
                    if ($error) echo "<p class='error'>*{$error}*</p>";
                    else if ($success) echo "<p class='success'>{$success}</p>";
                ?>

                <!-- Update Button -->
                <input id="profile-submit" class="submit" type="submit" name="updatePass" value="Update">
            </form>
        </div>
    </div>
</body>
</html>