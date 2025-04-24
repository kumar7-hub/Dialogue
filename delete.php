<?php
    require_once 'config.php';

    $error = "";

    // Redirect to home page if user is not logged in
    if (!isset($_SESSION['loggedIn'])) {
        header("Location: index.php");
        exit;
    }

    if (isset($_POST['deleteAccount'])) {

        $username = htmlspecialchars(trim($_POST['username']));
        $password = htmlspecialchars(trim($_POST['password']));

        // Check if fields are empty 
        if (empty($username) || empty($password)) $error = "Please fill in all fields";

        if (empty($error)) {
            try {
                // Create connection to database
                $db = getConnection();

                // Check if username exists 
                $query = $db->prepare("SELECT username, password FROM User WHERE username = ?");
                $query->bind_param("s", $username);
                $query->execute();
                
                $result = $query->get_result();

                if ($result->num_rows === 1) {
                    $row = $result->fetch_assoc();

                    // Verify user's password with hash
                    if (!password_verify($password, $row['password'])) $error = "Invalid username or password";
                    else {
                        // Delete user's account
                        $query = $db->prepare("DELETE FROM User WHERE username = ?");
                        $query->bind_param("s", $username);
                        $query->execute();

                        // Close connection to database 
                        closeConnection($db);

                        // Log out user 
                        header("Location: logout.php");
                        exit;
                    }
                }
                // User does not exist
                else $error = "Invalid username or password";

                // Close connection to database 
                closeConnection($db);
            }
            catch (Exception $e) {
                error_log("Delete account error: {$e->getMessage()}\n");

                // Alert user
                $message = "An error occurred. Please try again later.";
                echo "<script>alert('$message');</script>";
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Account</title>
    <script src="https://kit.fontawesome.com/329329b608.js" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="./styles/nav.css">
    <link rel="stylesheet" href="./styles/auth.css">
</head>
<body>
    <?php include 'nav.php'; ?>

    <div class="container">
        <h1 class="title" style="margin-top: 80px !important; margin-bottom: 30px;">Leaving?</h1>

        <div class="content-container">
            <h2 class="form-header">Delete Account</h2>

            <form id="delete-form" class="form" action="delete.php" method="POST" autocomplete="off">
                <!-- Delete Input Fields -->
                <div>
                    <input id="delete-username" type="text" name="username" placeholder="Username" autocomplete="off" required><br>
                </div>
                <div class="field">
                    <input id="delete-password" type="password" name="password" placeholder="Password" autocomplete="off" required><br>
                </div>    

                <?php
                    if ($error) echo "<p class='error'>*{$error}*</p>";
                ?>

                <!-- Delete Account Button -->
                <input id="delete-submit" class="submit" type="submit" name="deleteAccount" value="Delete Account" style="margin-bottom: 10px;">
            </form>
        </div>
    </div>
</body>
</html>