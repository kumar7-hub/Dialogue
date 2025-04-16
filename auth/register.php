<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="../styles/auth.css">
</head>
<body>
    <div class="container">
        <h1 class="title">Dialogue</h1>

        <div class="content-container">
            <h2 class="form-header">Sign Up</h2>

            <form id="signup-form" class="form" action="register.php" method="POST" autocomplete="off">
                <!-- Sign Up Input Fields -->
                <div>
                    <input id="signup-username" type="text" name="username" placeholder="Username" required><br>
                </div>
                <div class="field">
                    <input id="signup-email" type="email" name="email" placeholder="Email" required><br>
                </div>
                <div class="field">
                    <input id="signup-password" type="password" name="password" placeholder="Password" required><br>
                </div>    
                <div class="field">
                    <input id="signup-confirm-password" type="password" name="confirmPassword" placeholder="Confirm Password" required><br>
                </div>

                <!-- Sign Up Button -->
                <input id="signup-submit" class="submit" type="submit" name="register" value="Sign Up">

                <p class="redirect-message">Already on Dialogue? <a href="./login.php">Sign In</a></p>
            </form>
        </div>
    </div>
</body>
</html>