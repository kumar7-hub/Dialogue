<?php
    require_once 'config.php';

    $activity = "";
    $userData = "";
    $rows = [];
    $error = "";
    $activities = ['Posts', 'Comments', 'Likes'];
    $categoryStyles = [
        'Technology' => [
            'color' => "cyan",
            'icon' => "computer"
        ],     
        'Travel' => [
            'color' => "rgb(245, 21, 245)",
            'icon' => "plane"
        ],
        'Food' => [
            'color' => "orange",
            'icon' => "utensils"
        ],
        'Lifestyle' => [
            'color' => "gold",
            'icon' => "user"
        ],
        'Cars' => [
            'color' => "springgreen",
            'icon' => "car"
        ],
        'Sports' => [
            'color' => "red",
            'icon' => "medal"
        ]
    ];

    // Redirect to home page if user is not logged in
    if (!isset($_SESSION['loggedIn'])) {
        header("Location: index.php");
        exit;
    }
    // Redirect to profile page if activity is not set or in the activities array
    else if (!isset($_GET['activity']) || !in_array($_GET['activity'], $activities)) {
        header("Location: profile.php");
        exit;
    }

    try {
        $activity = $_GET['activity']; 
        $name = "delete{$activity}";

        // Create connection to database
        $db = getConnection();

        if (isset($_POST['deleteActivityButton']) && isset($_POST[$name]) && count($_POST[$name]) > 0) {

            $query = "";
            $ids = $_POST[$name];

            // Add userID to args if deleting likes
            if ($name === 'deleteLikes') $args = array_merge([str_repeat('i', count($ids)+1)], $ids, [$_SESSION['uid']]);
            else $args = array_merge([str_repeat('i', count($ids))], $ids);

            // Substract the placeholder types from idCount
            $idCount = count($args) - 1;

            // Overwrite the ids with their addresses
            for ($i = 1; $i <= $idCount; $i++) {
                $args[$i] = &$args[$i];
            }

            // Substract the userID (uid) placeholder if deleting likes
            if ($name === 'deleteLikes') $idCount--;

            // Dynamically create the placeholders for prepared statement
            $idPlaceholders = implode(', ', array_fill(0, $idCount, '?'));

            // Delete user's posts from database
            if ($name === 'deletePosts') $query = "DELETE FROM Post WHERE pid IN ({$idPlaceholders})";
            // Delete user's comments from database
            else if ($name === 'deleteComments') $query = "DELETE FROM Comments WHERE commentID IN ({$idPlaceholders})";
            // Delete user's liked posts from database
            else if ($name === 'deleteLikes') $query = "DELETE FROM Likes WHERE pid IN ({$idPlaceholders}) AND uid = ?";

            $query = $db->prepare($query);

            call_user_func_array([$query, 'bind_param'], $args);
            $query->execute();

            // Remove postID(s) of unliked post(s) from session
            if ($name === 'deleteLikes') $_SESSION['postIDS'] = array_diff($_SESSION['postIDS'], $ids);
        }


        // Retrieve user's created posts from database
        if ($activity === 'Posts') $query = "SELECT pid, title, content, created_at, name 
                                             FROM Post 
                                             JOIN Category ON Post.cid = Category.cid 
                                             WHERE uid = ? 
                                             ORDER BY created_at DESC";

        // Retrieve user's comments from database
        else if ($activity === 'Comments') $query = "SELECT commentID, title, comment, Comments.created_at, name 
                                                     FROM Post 
                                                     JOIN Comments on Post.pid = Comments.pid 
                                                     JOIN Category on Post.cid = Category.cid 
                                                     WHERE Comments.uid = ? 
                                                     ORDER BY Comments.created_at DESC";
        
        // Retrieve user's liked posts from database
        else $query = "SELECT Likes.pid, title, name 
                       FROM Likes 
                       JOIN Post ON Likes.pid = Post.pid 
                       JOIN Category ON Category.cid = Post.cid 
                       WHERE Likes.uid = ?";

        $query = $db->prepare($query);
        $query->bind_param('i', $_SESSION['uid']);
        $query->execute();

        $result = $query->get_result();
        
        if ($result->num_rows > 0) $rows = $result->fetch_all(MYSQLI_ASSOC);
        else if ($activity === 'Posts') $error = "You have not created any posts";
        else if ($activity === 'Comments') $error = "You have not commented on any posts";
        else $error = "You have not liked any posts";

        foreach($rows as $row) {
            $color = $categoryStyles[$row['name']]['color'];
            $icon = $categoryStyles[$row['name']]['icon'];

            if ($activity === 'Posts') {
                // Build created posts html
                $userData .=   "<div class='user-content'>
                                    <label for='{$row['pid']}' class='user-data-info'>
                                        <div class='post-info'>
                                            <span><i class='fa-solid fa-{$icon}' style='color: {$color};'></i></span>
                                            <span style='color: lightgray;'>{$row['created_at']}</span>
                                        </div>
                                        <p class='user-post-title' style='color: #caf0f8;'>{$row['title']}</p>
                                        <div>
                                            <p class='user-post-content'>{$row['content']}</p>
                                        </div>
                                   </label>

                                    <input id='{$row['pid']}' type='checkbox' name='delete{$activity}[]' value='{$row['pid']}'>
                               </div>";
            }
            else if ($activity === 'Comments') {
                // Build commented posts html
                $userData .=   "<div class='user-content'>
                                    <label for='{$row['commentID']}' class='user-data-info'>
                                        <div class='post-info'>
                                            <span><i class='fa-solid fa-{$icon}' style='color: {$color};'></i></span>
                                            <span style='color: lightgray;'>{$row['created_at']}</span>
                                        </div>
                                        <p class='user-post-title' style='color: #caf0f8;'>{$row['title']}</p>
                                        <div>
                                            <p class='user-post-content'>{$row['comment']}</p>
                                        </div>
                                   </label>

                                    <input id='{$row['commentID']}' type='checkbox' name='delete{$activity}[]' value='{$row['commentID']}'>
                               </div>";
            }
            else {
                // Build liked posts html
                $userData .=   "<div class='user-content'>
                                    <label for='{$row['pid']}' class='user-data-info'>
                                        <div class='post-info'>
                                            <span><i class='fa-solid fa-{$icon}' style='color: {$color};'></i></span>
                                        </div>
                                        <p class='user-post-title' style='color: #caf0f8; margin-bottom: 15px;'>{$row['title']}</p>
                                   </label>

                                    <input id='{$row['pid']}' type='checkbox' name='delete{$activity}[]' value='{$row['pid']}'>
                               </div>";
            }

        }

        // Close connection to database 
        closeConnection($db);
    }
    catch (Exception $e) {
        error_log("Delete {$activity} error: {$e->getMessage()}\n");

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
    <title>Document</title>
    <script src="https://kit.fontawesome.com/329329b608.js" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="./styles/nav.css">
    <link rel="stylesheet" href="./styles/home.css">
    <link rel="stylesheet" href="./styles/userActivity.css">
</head>
<body>
    <?php include 'nav.php'; ?>

    <div class="container">
        <h1 id="home-title"><?=$activity?></h1>

        <?php
            if ($error) echo "<p class='error' style='font-size: 1.3vw;'>*{$error}*</p>";
            else {
                // Display User's Data
                echo  "<form id='userActivity' class='content-container' action='userActivity.php?activity={$activity}' method='POST'>
                            {$userData}
                            <input class='user-delete-button' type='submit' name='deleteActivityButton' value='Delete'>
                       </form>";
            }
        ?>
    </div>
</body>
</html>