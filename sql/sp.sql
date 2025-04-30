-- DROP ALL STORED PROCEDURES  
DROP PROCEDURE IF EXISTS CreateUserAccount;
DROP PROCEDURE IF EXISTS DisplayPosts;
DROP PROCEDURE IF EXISTS UpdatePostLikes;

-- STORED PROCEDURES 

-- Procedure 1: Creates a new user account in the User table
DELIMITER //
CREATE PROCEDURE CreateUserAccount(username VARCHAR(50), password VARCHAR(225), email VARCHAR(100))
BEGIN
    SET @countID = -1;
    SET @countEmail = -1;

    -- Check if username already exists
    SELECT COUNT(*) INTO @countID
    FROM User WHERE User.username = username;

    -- Check if email already exists 
    SELECT COUNT(*) INTO @countEmail
    FROM User WHERE User.email = email;

    -- If username and email already exists, return a message
    if @countID > 0 THEN
        SELECT 'Username already exists' AS Message;
    elseif @countEmail > 0 THEN
        SELECT 'Email already exists' AS Message;
    else
        -- Insert new user account
        INSERT INTO User (username, password, email)
        VALUES (username, password, email);

        -- Retrieve userID of new user
        SELECT uid FROM User WHERE User.username = username;
    end if;
END //
DELIMITER ;


-- Procedure 2: Displays posts to user based on selected category and search
DELIMITER //
CREATE PROCEDURE DisplayPosts(category VARCHAR(50), search TEXT)
BEGIN
    SET @searchRequest = '%';

    -- If search is not empty, set search request to user's input
    if search != '' THEN 
        SET @searchRequest = CONCAT('%', search, '%');
    end if;

    -- If no category is selected, display all posts on home page
    if category = '' THEN 
        SELECT Post.pid, username, title, content, Post.created_at, name, likeCount
        FROM User 
        JOIN Post ON User.uid = Post.uid 
        JOIN Category ON Post.cid = Category.cid 
        LEFT JOIN PostLikes ON Post.pid = PostLikes.pid
        WHERE title LIKE @searchRequest
        ORDER BY Post.created_at DESC, RAND()
        LIMIT 20;
    else 
        -- If a category is selected, display posts from that category
        SELECT Post.pid, username, title, content, Post.created_at, name, likeCount
        FROM User 
        JOIN Post ON User.uid = Post.uid 
        JOIN Category ON Post.cid = Category.cid 
        LEFT JOIN PostLikes ON Post.pid = PostLikes.pid
        WHERE name = category AND title LIKE @searchRequest
        ORDER BY Post.created_at DESC;
    end if;
END //
DELIMITER ;


-- Procedure 3: Updates the user's like status on a post
DELIMITER //
CREATE PROCEDURE UpdatePostLikes(userID INT, postID INT)
BEGIN
    SET @postLiked = -1;

    -- Check if user already liked the post
    SELECT COUNT(*) INTO @postLiked 
    FROM Likes 
    WHERE uid = userID AND pid = postID;

    if @postLiked > 0 THEN 
        -- Unlike the post
        DELETE FROM Likes
        WHERE uid = userID AND pid = postID;

        SELECT 'unliked' AS Message;
    else
        -- Like the post
        INSERT INTO Likes (uid, pid) 
        VALUES (userID, postID);

        SELECT 'liked' AS Message;
    end if;
END //
DELIMITER ;