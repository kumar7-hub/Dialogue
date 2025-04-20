<?php
    require_once 'config.php';

    if (isset($_SESSION['username']) && isset($_POST['postID'])) {
        try {
            // Create connection to database
            $db = getConnection();

            // Add or remove like from database
            $query = $db->prepare("CALL UpdatePostLikes(?, ?)");
            $query->bind_param('si', $_SESSION['username'], $_POST['postID']);
            $query->execute();

            $result = $query->get_result();
            $row = $result->fetch_assoc();

            if ($row['Message'] === 'liked') {
                $liked = true;
                // Add postID of liked post to session
                $_SESSION['postIDS'][] = $_POST['postID'];
            }
            else {
                $liked = false;
                // Remove postID of unliked post from session
                $_SESSION['postIDS'] = array_diff($_SESSION['postIDS'], [$_POST['postID']]);
            }

            // Close connection to database
            closeConnection($db);

            // Send success response
            echo json_encode(['success' => true, 'liked' => $liked]);
        }
        catch (Exception $e) {
            error_log("Post like error: {$e->getMessage()}\n");
        }
    }
    else {
        // Send error response
        echo json_encode(['success' => false, 'message' => 'You must be logged in to like posts']);
    }
?>