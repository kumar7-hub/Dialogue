<?php
    require_once 'config.php';

    $error = "";
    $postDiv = "";
    $modals = "";
    $modalID = 0;
    $rows = [];
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

    // Fetch topic from URL, else default to empty string (home page)
    $topic = isset($_GET['topic']) && in_array($_GET['topic'], array_keys($categoryStyles)) ? $_GET['topic'] : '';
    // Fetch user's search input, else default to empty string
    $search = isset($_POST['search']) ? htmlspecialchars(trim($_POST['search'])) : '';

    try {
        // Create connection to database
        $db = getConnection();

        // Add user's post comment to database
        if (isset($_SESSION['loggedIn']) && isset($_POST['commentButton']) && isset($_GET['idPost'])) {
            $userComment = htmlspecialchars(trim($_POST['userComment']));
        
            if (!empty($userComment)) {
                $query = $db->prepare("INSERT INTO Comments (uid, pid, comment) VALUES (?, ?, ?)");
                $query->bind_param('iis', $_SESSION['uid'], $_GET['idPost'], $userComment);
                $query->execute();
            }

            // Redirect to the same page to avoid resubmission
            if (!empty($topic)) header("Location: index.php?topic={$topic}");
            else header("Location: index.php");
        }

        // Add user's post to database
        if (isset($_SESSION['loggedIn']) && isset($_POST['postSubmit']) && isset($_POST['categoryID'])) {
            $postTitle = htmlspecialchars(trim($_POST['postTitle']));
            $postContent = htmlspecialchars(trim($_POST['postContent']));
        
            if (!empty($postTitle) && !empty($postContent)) {
                $query = $db->prepare("INSERT INTO Post (uid, cid, title, content) VALUES (?, ?, ?, ?)");
                $query->bind_param('iiss', $_SESSION['uid'], $_POST['categoryID'], $postTitle, $postContent);
                $query->execute();
            }
        }

        // Retrieve posts from database
        $query = $db->prepare("CALL DisplayPosts(?, ?)");
        $query->bind_param('ss', $topic, $search);
        $query->execute();

        $result = $query->get_result();
        if ($result->num_rows > 0) $rows = $result->fetch_all(MYSQLI_ASSOC);

        forEach($rows as $row) {

            $likes = $row['likeCount'] ?? 0;
            $color = $categoryStyles[$row['name']]['color'];
            $icon = $categoryStyles[$row['name']]['icon'];
            $modalID += 1;

            // Check if user has liked the post
            isset($_SESSION['postIDS']) && in_array($row['pid'], $_SESSION['postIDS']) ? $thumbsUpClass = 'thumbs-up-success' : $thumbsUpClass = 'thumbs-up';

            // Build posts html
            $postDiv .= "<div class='post category-{$row['name']}' data-bs-toggle='modal' data-bs-target='#modal-{$modalID}'>
                            <div>
                                <div class='post-info'>
                                    <span><i class='fa-solid fa-{$icon}' style='color: {$color};'></i> &bull; {$row['username']}</span>
                                    <span style='color: lightgray;'>{$row['created_at']}</span>
                                </div>
                                <span class='post-title'>{$row['title']}</span>
                            </div>
                        </div>";

            // Build modals html
            $modals .= "<div class='modal' id='modal-{$modalID}'>
                            <div class='modal-dialog modal-dialog-scrollable'>
                                <div id='{$row['pid']}' class='modal-content'>
                                    <div class='modal-header'>
                                        <h4 class='modal-title'>{$row['title']}</h4>
                                        <button type='button' class='btn-close' data-bs-dismiss='modal'></button>
                                    </div>

                                    <div class='post-info'>
                                        <div>
                                            <span><strong style='color: {$color};'>{$row['name']}</strong> &bull; {$row['username']} &bull; <span style='color: lightgray;'>{$row['created_at']}</span></span>
                                        </div>
                                        <div>
                                            <i class='fa-solid fa-thumbs-up {$thumbsUpClass}'></i>
                                            <span class='like-count'>{$likes}</span>
                                        </div>
                                    </div>

                                    <div class='modal-body'>
                                        <p>{$row['content']}</p>

                                        <div class='comment-header'>Comments</div>
                                    </div>

                                    <form class='modal-footer' action='index.php?topic={$topic}&idPost={$row['pid']}' method='POST'>
                                        <textarea class='comment-field' name='userComment' cols='35' rows='15' placeholder='Comment' required></textarea>
                                         <p class='error' style='margin-top: 10px;'></p>
                                        <input class='comment-button' type='submit' name='commentButton' value='Comment'>
                                    </form>
                                </div>
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
                if (!empty($topic)) echo $topic;
                else echo 'Home';
            ?>
        </h1>

        <!-- Display Posts -->
        <?= $postDiv ?>
        <!-- Display Post Modals -->
        <?= $modals ?>

        <script>
            // Fetch all elements (post modals) with class 'modal'
            const posts = document.querySelectorAll('.modal');

            posts.forEach(async (post) => {

                const postID = post.querySelector('.modal-content').id;
                let thumbsUp = post.querySelector('i');
                let likeCount = post.querySelector('.like-count');
                let modalBody = post.querySelector('.modal-body');
                let commentForm = post.querySelector('.modal-footer');
                let error = post.querySelector('.error');

                let commentSection = document.createElement('div');
                commentSection.classList.add('comment');
                
                // Source: https://developer.mozilla.org/en-US/docs/Web/API/XMLHttpRequest_API/Using_FormData_Objects
                const postData = new FormData();
                postData.append('post_id', postID);

                // Fetch comments for the post
                const out = await fetch('fetchComments.php', {
                    method: 'POST', 
                    body: postData
                });

                const output = await out.json();
                if (output.success) {
                    const comments = output.postComments;
                    // Build comment section
                    comments.forEach(comment => {
                        commentSection.innerHTML += `<div>
                                                        <div class='comment-info'>
                                                            <span style='color: #ff65be;'>${comment['username']}</span>
                                                            <span style='color: lightgray;'>${comment['created_at']}</span>
                                                        </div>
                                                        <span class='comment-title'>${comment['comment']}</span>
                                                    </div>`;
                    });

                    modalBody.appendChild(commentSection);
                }

                // Add event listener to thumbs-up icon
                thumbsUp.addEventListener('click', async () => {

                    const formData = new FormData();
                    formData.append('postID', postID);

                    // Fetch like/unlike status
                    const res = await fetch('postLike.php', {
                        method: 'POST',
                        body: formData
                    });

                    const result = await res.json();
                    if (result.success) {
                        // Post is liked 
                        if (result.liked) {
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
                    // User not logged in
                    else alert(result.message);
                });

                // Comment form submission validation
                commentForm.addEventListener('submit', event => {
                    let loggedIn = <?= isset($_SESSION['username']) ? 'true' : 'false' ?>;
                    let userComment = commentForm.userComment.value.trim();

                    if (!loggedIn || !userComment) {
                        event.preventDefault();
                        !loggedIn ? alert('You must be logged in to comment on posts') : error.innerHTML = '*Comment cannot be empty*';

                        // Clear the comment textarea 
                        commentForm.reset();
                    }
                });

                // Clear error message and comment textarea after post modal closes
                post.addEventListener('hidden.bs.modal', () => {
                    error.innerHTML = '';
                    commentForm.reset();
                });
            });
        </script>

        <!-- Create Post Modal -->
        <div class='modal' id='createPost'>
            <div class='modal-dialog modal-dialog-scrollable'>
                <div class='modal-content'>
                    <div class='modal-header'>
                        <h4 class='modal-title'>Create Post</h4>
                        <button type='button' class='btn-close' data-bs-dismiss='modal'></button>
                    </div>

                    <form id='create-post-form' class='modal-body' action='<?= !empty($topic) ? "index.php?topic={$topic}" : 'index.php'?>' method='POST' style='padding: 0px;'>
                        <div class="category-selector">
                            <input id="tech" type="radio" name="categoryID" value="1" hidden/>
                            <label for="tech" style="color: cyan;">Technology</label>

                            <input id="travel" type="radio" name="categoryID" value="2" hidden />
                            <label for="travel" style="color: rgb(245, 21, 245);">Travel</label>

                            <input id="food" type="radio" name="categoryID" value="3" hidden />
                            <label for="food" style="color: orange;">Food</label>

                            <input id="lifestyle" type="radio" name="categoryID" value="4" hidden />
                            <label for="lifestyle" style="color: gold;">Lifestyle</label>

                            <input id="cars" type="radio" name="categoryID" value="5" hidden />
                            <label for="cars" style="color: springgreen;">Cars</label>

                            <input id="sports" type="radio" name="categoryID" value="6" hidden />
                            <label for="sports" style="color: red;">Sports</label>
                        </div>

                        <div style='padding: 20px;'>
                            <div>
                                <input id="title-post" class='comment-field title-post' type="text" name="postTitle" placeholder="Title" autocomplete="off" required><br>
                            </div>
                            <textarea id='postContent' class='comment-field' name='postContent' cols='35' rows='15' placeholder='Text' required></textarea>
                            <p id='category-error' class='error' style="margin-top: 10px;"></p>
                            <input class='modal-footer comment-button' type='submit' name='postSubmit' value='Create'>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <script>
            const createPostForm = document.getElementById('create-post-form');
            const createPostModal = document.getElementById('createPost');
            let error = document.getElementById('category-error');

            // Create post form submission validation
            createPostForm.addEventListener('submit', event => {
                let categoryID = createPostForm.categoryID.value;
                let postTitle = createPostForm.postTitle.value.trim();
                let postContent = createPostForm.postContent.value.trim();
               
                // Check if fields are empty
                if (!categoryID || !postTitle || !postContent) {
                    event.preventDefault();
                    !categoryID ? error.innerHTML = '*Please select a category*' : error.innerHTML = '*Please fill in all fields*';

                    // Clear fields
                    createPostForm.reset();
                }
            });

            // Clear error message and fields after create post modal closes
            createPostModal.addEventListener('hidden.bs.modal', () => {
                error.innerHTML = '';
                createPostForm.reset();
            });
        </script>


        <!-- <div class='modal' id='createPost'>
            <div class='modal-dialog modal-dialog-scrollable'>
                <div class='modal-content'>
                    <div class='modal-header'>
                        <h4 class='modal-title'>Tips before purchasing a new car?</h4>
                        <button type='button' class='btn-close' data-bs-dismiss='modal'></button>
                    </div>

                    <div class='post-info'>
                        <div>
                            <span><strong style='color: cyan;'>Technology</strong> &bull; pikachu &bull; <span style='color: lightgray;'>2025-04-19</span></span>
                        </div>
                        <div>
                            <i class='fa-solid fa-thumbs-up thumbs-up'></i>
                            <span class='like-count'>0</span>
                        </div>
                    </div>

                    <div class='modal-body'>
                        <div class="post-content">
                            Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
                        </div>

                        <div class="comment-container">
                            <div class='comment-header'>Comments</div>

                            <div class='comment'>
                                <div>
                                    <div class='comment-info'>
                                        <span style='color: cyan;'>sonic</span>
                                        <span style='color: lightgray;'>2025-04-19</span>
                                    </div>
                                    <span class='comment-title'>Watch youtube reviews before buying</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <form class='modal-footer' action='index.php' method='POST'>
                        <textarea class='comment-field' name='userComment' cols="35" rows="15" placeholder='Comment'></textarea>
                    </form>

                </div>
            </div>
        </div> -->


    </div>
</body>
</html>