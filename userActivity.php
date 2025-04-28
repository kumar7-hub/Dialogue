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

        // Create connection to database
        $db = getConnection();

        if (isset($_POST['deleteActivityButton'])) {

            if (isset($_POST['deletePosts']) && count($_POST['deletePosts']) > 0) {
                $postIds = $_POST['deletePosts'];
                $postIdCount = count($postIds);
                $args = array_merge([str_repeat('i', $postIdCount)], $postIds);

                // Overwrite the postIDs with their addresses
                for ($i = 1; $i <= $postIdCount; $i++) {
                    $args[$i] = &$args[$i];
                }

                // Dynamically create the placeholders for prepared statement
                $postIdPlaceholders = implode(', ', array_fill(0, $postIdCount, '?'));
                // Delete user's posts from database
                $query = $db->prepare("DELETE FROM Post WHERE pid IN ({$postIdPlaceholders})");
                call_user_func_array([$query, 'bind_param'], $args);
                $query->execute();
            }
            else if (isset($_POST['deleteComments']) && count($_POST['deleteComments']) > 0) {
                $commentIds = $_POST['deleteComments'];
                $commentIdCount = count($commentIds);
                $args = array_merge([str_repeat('i', $commentIdCount)], $commentIds);

                // Overwrite the commentIDs with their addresses
                for ($i = 1; $i <= $commentIdCount; $i++) {
                    $args[$i] = &$args[$i];
                }

                // Dynamically create the placeholders for prepared statement
                $commentIdPlaceholders = implode(', ', array_fill(0, $commentIdCount, '?'));
                // Delete user's comments from database
                $query = $db->prepare("DELETE FROM Comments WHERE commentID IN ({$commentIdPlaceholders})");
                call_user_func_array([$query, 'bind_param'], $args);
                $query->execute();
            }
            else if (isset($_POST['deleteLikes']) && count($_POST['deleteLikes']) > 0) {
                $postIds = $_POST['deleteLikes'];
                $postIdCount = count($postIds);
                $args = array_merge([str_repeat('i', $postIdCount)], $postIds);

                // Overwrite the postIDs with their addresses
                for ($i = 1; $i <= $postIdCount; $i++) {
                    $args[$i] = &$args[$i];
                }

                // Dynamically create the placeholders for prepared statement
                $postIdPlaceholders = implode(', ', array_fill(0, $postIdCount, '?'));
                // Delete user's liked posts from database
                $query = $db->prepare("DELETE FROM Likes WHERE pid IN ({$postIdPlaceholders})");
                call_user_func_array([$query, 'bind_param'], $args);
                $query->execute();

                // Remove postID(s) of unliked post(s) from session
                $_SESSION['postIDS'] = array_diff($_SESSION['postIDS'], $postIds);
            }
        }

        if ($activity === 'Posts') {
            // Retrieve user's created posts from database
            $query = $db->prepare("SELECT pid, title, content, created_at, name FROM Post JOIN Category ON Post.cid = Category.cid WHERE uid = ? ORDER BY created_at DESC");
            $query->bind_param('i', $_SESSION['uid']);
            $query->execute();

            $result = $query->get_result();
            if ($result->num_rows > 0) $rows = $result->fetch_all(MYSQLI_ASSOC);
            else $error = "You have not created any posts";
                
            foreach($rows as $row) {
                $color = $categoryStyles[$row['name']]['color'];
                $icon = $categoryStyles[$row['name']]['icon'];

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
        }
        else if ($activity === 'Comments') {
            // Retrieve user's comments from database
            $query = $db->prepare("SELECT commentID, title, comment, Comments.created_at, name FROM Post JOIN Comments on Post.pid = Comments.pid JOIN Category on Post.cid = Category.cid WHERE Comments.uid = ? ORDER BY Comments.created_at DESC");
            $query->bind_param('i', $_SESSION['uid']);
            $query->execute();

            $result = $query->get_result();
            if ($result->num_rows > 0) $rows = $result->fetch_all(MYSQLI_ASSOC);
            else $error = "You have not commented on any posts";

            foreach($rows as $row) {
                $color = $categoryStyles[$row['name']]['color'];
                $icon = $categoryStyles[$row['name']]['icon'];

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
        }
        else {
            // Retrieve user's liked posts from database
            $query = $db->prepare("SELECT Likes.pid, title, name FROM Likes JOIN Post ON Likes.pid = Post.pid JOIN Category ON Category.cid = Post.cid WHERE Likes.uid = ?");
            $query->bind_param('i', $_SESSION['uid']);
            $query->execute();

            $result = $query->get_result();
            if ($result->num_rows > 0) $rows = $result->fetch_all(MYSQLI_ASSOC);
            else $error = "You have not liked any posts";

            foreach ($rows as $row) {
                $color = $categoryStyles[$row['name']]['color'];
                $icon = $categoryStyles[$row['name']]['icon'];

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