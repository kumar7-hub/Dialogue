<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Account</title>
    <link rel="stylesheet" href="../styles/auth.css">
</head>
<body>
    <div class="container">
        <h1 class="title">Dialogue</h1>

        <div class="content-container">
            <h2 class="form-header">Delete Account</h2>

            <form id="delete-form" class="form" action="delete.php" method="POST" autocomplete="off">
                <!-- Delete Input Fields -->
                <div>
                    <input id="delete-username" type="text" name="username" placeholder="Username" required><br>
                </div>
                <div class="field">
                    <input id="delete-password" type="password" name="password" placeholder="Password" required><br>
                </div>    

                <!-- Delete Account Button -->
                <input id="delete-submit" class="submit" type="submit" name="deleteAccount" value="Delete Account" style="margin-bottom: 10px;">
            </form>
        </div>
    </div>
</body>
</html>