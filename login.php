<?php
    require_once 'config.php';

    $error = "";

    if (isset($_POST['login'])) {

        $result = false;

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

                // Close connection to database 
                closeConnection($db);

                if ($result->num_rows === 1) {
                    $row = $result->fetch_assoc();

                    // Verify user's password with hash 
                    if (!password_verify($password, $row['password'])) {
                        $error = "Invalid username or password";
                    }
                    else {
                        // Set session variables for global access
                        $_SESSION['loggedIn'] = true;
                        $_SESSION['username'] = $username;

                        // Redirect to home page
                        header("Location: index.php");
                        exit;
                    }
                }
                else $error = "Invalid username or password";
            }
            catch (Exception $e) {
                error_log("Log in error: {$e->getMessage()}\n");

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
    <title>Sign In</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="./styles/nav.css">
    <link type="text/css" rel="stylesheet" href="./styles/auth.css">
</head>
<body>
    <?php include 'nav.php'; ?>

    <div class="container">
        <h1 class="title" style="margin-top: 80px !important; margin-bottom: 30px;">Welcome Back!</h1>

        <div class="content-container">
            <h2 class="form-header">Sign In</h2>

            <form id="login-form" class="form" action="login.php" method="POST" autocomplete="off">
                <!-- Login Input Fields -->
                <div>
                    <input id="login-username" type="text" name="username" placeholder="Username" autocomplete="off" required><br>
                </div>
                <div class="field">
                    <input id="login-password" type="password" name="password" placeholder="Password" autocomplete="off" required><br>
                </div>
                
                <?php
                    if ($error) echo "<p class='error'>*{$error}*</p>";
                ?>

                <!-- Sign In Button -->
                <input id="login-submit" class="submit" type="submit" name="login" value="Sign In">

                <p class="redirect-message">Don't have an account? <a href="./register.php">Sign Up</a></p>
            </form>
        </div>
    </div>
</body>
</html>