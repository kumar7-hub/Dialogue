<?php
    require_once 'config.php';

    $error = "";
    $postDiv = "";
    $categoryColors = [
        'Technology' => "cyan",
        'Travel' => "rgb(245, 21, 245)",
        'Food' => "orange",
        'Lifestyle' => "gold",
        'Cars' => "springgreen",
        'Sports' => "red"
    ];

    // Fetch topic from URL, else default to empty string (home page)
    $topic = $_GET['topic'] ?? '';
    // 
    $search = htmlspecialchars($_POST['search']) ?? '';

    try {
        // Create connection to database
        $db = getConnection();

        // Retrieve posts from database
        $query = $db->prepare("CALL DisplayPosts(?, ?)");
        $query->bind_param('ss', $topic, $search);
        $query->execute();

        $result = $query->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $likes = $row['likes'] ?? 0;
                $color = $categoryColors[$row['name']] ?? 'gray';

                // Build post div
                $postDiv .= "<div id='{$row['pid']}' class='post'>
                                <div>
                                    <div class='postInfo'>
                                        <div>
                                            <span><strong style='color: {$color};'>{$row['name']}</strong> &bull; pikachu &bull; <span style='color: lightgray;'>{$row['created_at']}</span></span>
                                        </div>
                                        <span style='color: salmon;'>{$likes}</span>
                                    </div>
                                    <span class='postTitle'>{$row['title']}</span>
                                </div>
                            </div>";
            }
        }

        // Close connection to database 
        closeConnection($db);
    }
    catch (Exception $e) {
        error_log("Home page error: {$e->getMessage()}\n");

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
    <title>Dialogue</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="./styles/nav.css">
    <link rel="stylesheet" href="./styles/home.css">
</head>
<body>
    <?php include 'nav.php'; ?>

    <div class="container">
        <h1 id="home-title">
            <?php
                if ($topic !== '') echo $topic;
                else echo 'Home';
            ?>
        </h1>

        <!-- Display posts -->
        <?= $postDiv ?>

        <!-- <div class="post">
            <div>
                <div class="postInfo">
                    <div><span><strong style="color: orange;">Technology</strong> &bull; pikachu &bull; <span style="color: lightgray;">2025-04-17</span></span></div>
                    <span style="color: salmon;">10</span>
                </div>
                <span class="postTitle">Is it worth getting TSA PreCheck?</span>
            </div>
        </div>

        <div class="post">
            <div>
                <div class="postInfo">
                    <span><strong style="color: orange;">Lifestyle</strong> &bull; pikachu &bull; 2025-04-17</span>
                    <span style="color: salmon;">10</span>
                </div>
                <span class="postTitle">Best places for men's apparel?</span>
            </div>
        </div> -->
    </div>
</body>
    <script src="script.js"></script>
</html>