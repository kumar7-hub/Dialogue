-- DROP ALL TRIGGERS
DROP TRIGGER IF EXISTS before_user_delete;
DROP TRIGGER IF EXISTS after_user_delete;
DROP TRIGGER IF EXISTS after_post_delete;

-- TRIGGERS

-- Trigger 1: Archive user's posts before a user is deleted
DELIMITER //
CREATE TRIGGER before_user_delete
BEFORE DELETE ON User
FOR EACH ROW
BEGIN
    INSERT INTO PostArchives (pid, uid, cid, title, content, created_at)
    SELECT pid, uid, cid, title, content, created_at
    FROM Post WHERE Post.uid = OLD.uid;
END//
DELIMITER ;


-- Trigger 2: Archive user after a user is deleted
DELIMITER //
CREATE TRIGGER after_user_delete
AFTER DELETE ON User
FOR EACH ROW
BEGIN
    INSERT INTO UserArchives (uid, username, email, created_at)
    VALUES (OLD.uid, OLD.username, OLD.email, OLD.created_at);
END//
DELIMITER ;


-- Trigger 3: Archive post after a post is deleted
DELIMITER //
CREATE TRIGGER after_post_delete
AFTER DELETE ON Post
FOR EACH ROW
BEGIN
    INSERT INTO PostArchives (pid, uid, cid, title, content, created_at)
    VALUES (OLD.pid, OLD.uid, OLD.cid, OLD.title, OLD.content, OLD.created_at);
END//
DELIMITER ;