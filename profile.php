<?php
    require_once 'config.php';

    $error = "";
    $success = "";
    $email = "";
    $categories = ['Technology', 'Travel', 'Food', 'Lifestyle', 'Cars', 'Sports'];

    // Redirect to category page if topic is accessed from profile page
    if (isset($_GET['topic']) && in_array($_GET['topic'], $categories)) {
        header("Location: index.php?topic={$_GET['topic']}");
        exit;
    }

    try {
        // Create connection to database
        $db = getConnection();

        // Fetch user's email
        $query = $db->prepare("SELECT email FROM User WHERE username = ?");
        $query->bind_param("s", $_SESSION['username']);
        $query->execute();

        $result = $query->get_result();
        $row = $result->fetch_assoc();

        $email = $row['email'];

        // Close connection to database 
        closeConnection($db);

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

                // Fetch user's email
                $query = $db->prepare("SELECT email FROM User WHERE username = ?");
                $query->bind_param("s", $_SESSION['username']);
                $query->execute();

                $result = $query->get_result();
                $row = $result->fetch_assoc();

                $email = $row['email'];

                // Close connection to database 
                closeConnection($db);
            }
        }
    }
    catch (Exception $e) {
        error_log("Profile error: {$e->getMessage()}\n");

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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="./styles/nav.css">
    <link type="text/css" rel="stylesheet" href="./styles/auth.css">
</head>
<body>
    <?php include 'nav.php'; ?>

    <div class="container">
        <h1 class="title" style="margin-top: 80px !important; margin-bottom: 30px;">Hi, <?=$_SESSION['username']?></h1>

        <div class="content-container">
            <h2 class="form-header">Profile</h2>

            <form id="profile-form" class="form" action="profile.php" method="POST" autocomplete="off">
                <!-- Profile Input Fields -->
                <div class="field">
                    <input id="profile-email" type="email" name="email" value=<?=$email?> disabled><br>
                </div>
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