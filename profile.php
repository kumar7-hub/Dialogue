<?php
    require_once 'config.php';

    $email = "";

    // Redirect to home page if user is not logged in
    if (!isset($_SESSION['loggedIn'])) {
        header("Location: index.php");
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
    }
    catch (Exception $e) {
        error_log("Settings error: {$e->getMessage()}\n");

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
    <title>Settings</title>
    <script src="https://kit.fontawesome.com/329329b608.js" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="./styles/nav.css">
    <link rel="stylesheet" href="./styles/home.css">
</head>
<body>
    <?php include 'nav.php'; ?>

    <div class="container">
        <h1 id="home-title" style="margin-bottom: 10px;"><?="Hi, {$_SESSION['username']}"?></h1>
        <h3 class='settings-email'><?=$email?></h3>

        <a class="post settings settings-link" href="userActivity.php?activity=userPosts">Posts</a>

        <a class="post settings settings-link" href="userActivity.php?activity=userComments">Comments</a>

        <a class="post settings settings-link" href="userActivity.php?activity=userLikes">Likes</a>

        <a class="post settings settings-link" href="changePassword.php" style="margin-top: 100px;">Change Password</a>

        <a class="post settings settings-link" href="delete.php" style="color: #ff4d4d;">Delete Account</a>
    
    </div>
</body>
</html>