<?php
    require_once 'config.php';

    if (isset($_POST['post_id'])) {
        try {
            $comments = [];

            // Create connection to database
            $db = getConnection();
            
            // Fetch post comments from database
            $query = $db->prepare("SELECT username, comment, Comments.created_at FROM User JOIN Comments ON User.uid = Comments.uid WHERE pid = ? ORDER BY Comments.created_at DESC");
            $query->bind_param('i', $_POST['post_id']);
            $query->execute();
            $result = $query->get_result();

            if ($result->num_rows > 0) {
                $comments = $result->fetch_all(MYSQLI_ASSOC);
                // Send success response
                echo json_encode(['success' => true, 'postComments' => $comments]);
            }
            // Post has no comments
            else echo json_encode(['success' => false, 'message' => 'Post has no comments']);

            // Close connection to database
            closeConnection($db);
        }
        catch (Exception $e) {
            error_log("Fetch comments error: {$e->getMessage()}\n");
        }
    }
?>