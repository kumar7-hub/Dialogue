-- DROP ALL VIEWS
DROP VIEW IF EXISTS PostLikes;

-- VIEWS

-- View 1: Count number of likes for each post
CREATE VIEW PostLikes AS 
SELECT pid, COUNT(uid) AS likeCount
FROM Likes GROUP BY pid;