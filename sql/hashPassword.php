<?php
    require_once '../../public_html/3680/final/config.php';

    // Mock user data plaintext password hash
    try {
        // Create connection to database
        $db = getConnection();

        // Fetch users' ids and passwords 
        $result = $db->query("SELECT uid, password FROM User");
        if ($result->num_rows > 0) $rows = $result->fetch_all(MYSQLI_ASSOC);

        // Hash users' password
        foreach($rows as $row) {
            $passwordHash = password_hash($row['password'], PASSWORD_DEFAULT);
            $db->query("UPDATE User SET password = '{$passwordHash}' WHERE uid = {$row['uid']}");
        }

        // Close connection to database
        closeConnection($db);
    }
    catch (Exception $e) {
        echo "Hash password error: {$e->getMessage()}\n";
    }
?>