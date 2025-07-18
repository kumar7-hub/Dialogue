<?php
    require_once 'config.php';

    $error = "";

    if (isset($_POST['register'])) {

        $username = htmlspecialchars(trim($_POST['username']));
        $email = htmlspecialchars(trim($_POST['email']));
        $password = htmlspecialchars(trim($_POST['password']));
        $confirmPassword = htmlspecialchars(trim($_POST['confirmPassword']));

        // Check if fields are empty 
        if (empty($username) || empty($password) || empty($confirmPassword) || empty($email)) {
            $error = "Please fill in all fields";
        } 
        // Check if passwords match
        else if ($password !== $confirmPassword) {
            $error = "Passwords do not match";
        }
        // Check if email format is valid 
        else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = "Invalid email format";
        }

        if (empty($error)) {
            // Hash user's password 
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);

            try {
                // Create connection to database
                $db = getConnection();

                // Check if username or email exists 
                $query = $db->prepare("CALL CreateUserAccount(?, ?, ?)");
                $query->bind_param("sss", $username, $passwordHash, $email);
                $query->execute();
                
                $result = $query->get_result();
                $row = $result->fetch_assoc();

                // Close connection to database 
                closeConnection($db);

                if (!empty($row['Message'])) $error = "Username or email already exists"; 
                else {
                    // Set session variables for global access
                    $_SESSION['loggedIn'] = true;
                    $_SESSION['uid'] = $row['uid'];
                    $_SESSION['username'] = $username;

                    // Redirect to home page
                    header("Location: index.php");
                    exit;
                }
            }
            catch (Exception $e) {
                error_log("Sign in error: {$e->getMessage()}\n");

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
    <title>Sign Up</title>
    <script src="https://kit.fontawesome.com/329329b608.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="./styles/auth.css">
</head>
<body>
    <div class="container">
        <h1 class="title">Dialogue</h1>

        <div class="content-container">
            <h2 class="form-header">Sign Up</h2>

            <form id="signup-form" class="form" action="register.php" method="POST" autocomplete="off">
                <!-- Sign Up Input Fields -->
                <div>
                    <input id="signup-username" type="text" name="username" placeholder="Username" autocomplete="off" required><br>
                </div>
                <div class="field">
                    <input id="signup-email" type="email" name="email" placeholder="Email" autocomplete="off" required><br>
                </div>
                <div class="field">
                    <input id="signup-password" type="password" name="password" placeholder="Password" autocomplete="off" required><br>
                </div>    
                <div class="field">
                    <input id="signup-confirm-password" type="password" name="confirmPassword" placeholder="Confirm Password" autocomplete="off" required><br>
                </div>

                <?php
                    if ($error) echo "<p class='error'>*{$error}*</p>";
                ?>

                <!-- Sign Up Button -->
                <input id="signup-submit" class="submit" type="submit" name="register" value="Sign Up">

                <p class="redirect-message">Already on Dialogue? <a href="./login.php">Sign In</a></p>
            </form>
        </div>
    </div>
</body>
</html>