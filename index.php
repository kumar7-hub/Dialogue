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
    $topic = isset($_GET['topic']) && in_array($_GET['topic'], array_keys($categoryColors)) ? $_GET['topic'] : '';
    // Fetch user's search input, else default to empty string
    $search = isset($_POST['search']) ? htmlspecialchars(trim($_POST['search'])) : '';

    try {
        // Create connection to database
        $db = getConnection();

        // Retrieve posts from database
        $query = $db->prepare("CALL DisplayPosts(?, ?)");
        $query->bind_param('ss', $topic, $search);
        $query->execute();

        $result = $query->get_result();
        if ($result->num_rows > 0) $rows = $result->fetch_all(MYSQLI_ASSOC);

        // Build posts html
        forEach($rows as $row) {
            $likes = $row['likeCount'] ?? 0;
            $color = $categoryColors[$row['name']] ?? 'gray';

            // Check if user has liked the post
            isset($_SESSION['postIDS']) && in_array($row['pid'], $_SESSION['postIDS']) ? $thumbsUpClass = 'thumbs-up-success' : $thumbsUpClass = 'thumbs-up';

            $postDiv .= "<div id='{$row['pid']}' class='post'>
                            <div>
                                <div class='post-info'>
                                    <div>
                                        <span><strong style='color: {$color};'>{$row['name']}</strong> &bull; pikachu &bull; <span style='color: lightgray;'>{$row['created_at']}</span></span>
                                    </div>
                                    <div>
                                        <i class='fa-solid fa-thumbs-up {$thumbsUpClass}'></i>
                                        <span class='like-count'>{$likes}</span>
                                    </div>
                                </div>
                                <span class='post-title'>{$row['title']}</span>
                            </div>
                        </div>";
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
    <script src="https://kit.fontawesome.com/329329b608.js" crossorigin="anonymous"></script>
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

        <script>
            // Fetch all elements (posts) with class 'post'
            const posts = document.querySelectorAll('.post');

            posts.forEach(post => {
                const postID = post.id;
                let thumbsUp = post.querySelector('i');
                let likeCount = post.querySelector('.like-count');

                // Add event listeners to all posts
                post.addEventListener('click', () => {
                    // console.log(post);
                });

                // Add event listener to thumbs-up icon
                thumbsUp.addEventListener('click', async () => {
                    // Source: https://developer.mozilla.org/en-US/docs/Web/API/XMLHttpRequest_API/Using_FormData_Objects
                    const formData = new FormData();
                    formData.append('postID', postID);

                    const res = await fetch('postLike.php', {
                        method: 'POST',
                        body: formData
                    });

                    const result = await res.json();
                    if (result.success) {
                        // Post is liked 
                        if (result.status) {
                            thumbsUp.classList.remove('thumbs-up');
                            thumbsUp.classList.add('thumbs-up-success');
                            likeCount.innerHTML = Number(likeCount.innerHTML) + 1;
                        }
                        // Post is unliked
                        else {
                            thumbsUp.classList.remove('thumbs-up-success');
                            thumbsUp.classList.add('thumbs-up');
                            likeCount.innerHTML = Number(likeCount.innerHTML) - 1;
                        }
                    } 
                    else alert(result.message);
                });
            });
        </script>

        <!-- <div class="post">
            <div>
                <div class="postInfo">
                    <div>
                        <span><strong style="color: orange;">Technology</strong> &bull; pikachu &bull; <span style="color: lightgray;">2025-04-17</span></span>
                    </div>
                    <div>
                        <i class="fa-solid fa-thumbs-up thumbs-up"></i>
                        <span style="color: salmon;">10</span>
                    </div>
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
</html>