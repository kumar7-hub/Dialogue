<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In</title>
    <link type="text/css" rel="stylesheet" href="../styles/auth.css">
</head>
<body>
    <div class="container">
        <h1 class="title">Dialogue</h1>

        <div class="content-container">
            <h2 class="form-header">Sign In</h2>

            <form id="login-form" class="form" action="login.php" method="POST" autocomplete="off">
                <!-- Login Input Fields -->
                <div>
                    <input id="login-username" type="text" name="username" placeholder="Username" required><br>
                </div>
                <div class="field">
                    <input id="login-password" type="password" name="password" placeholder="Password" required><br>
                </div>    

                <!-- Sign In Button -->
                <input id="login-submit" class="submit" type="submit" name="login" value="Sign In">

                <p class="redirect-message">Don't have an account? <a href="./register.php">Sign Up</a></p>
            </form>
        </div>
    </div>
</body>
</html>