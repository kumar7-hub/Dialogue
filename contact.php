<?php
    require_once 'config.php';

    $error = "";
    $customMessage = [
        'p1' => "Have a question, concern, or feedback?",
        'p2' => "Fill out this form!"
    ];
    $categories = ['Technology', 'Travel', 'Food', 'Lifestyle', 'Cars', 'Sports'];

    // Redirect to category page if topic is accessed from contact page
    if (isset($_GET['topic']) && in_array($_GET['topic'], $categories)) {
        header("Location: index.php?topic={$_GET['topic']}");
        exit;
    }

    if (isset($_POST['contactSubmit'])) {

        $name = htmlspecialchars($_POST['name']);
        $email = htmlspecialchars($_POST['email']);
        $userMessage = htmlspecialchars($_POST['message']);

        // Check if fields are empty 
        if (empty($name) || empty($email) || empty($userMessage)) $error = "Please fill in all fields";
        // Check if email format is valid 
        else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $error = "Invalid email format";

        if (empty($error)) {
            try {
                // Send mail 
                $to      = 'skumar2@csub.edu';
                $subject = "Dialogue - {$name}";
                $message = $userMessage;
                $headers = array(
                    'From' => $email,
                    'Reply-To' => $email,
                    'X-Mailer' => 'PHP/' . phpversion()
                );

                $success = mail($to, $subject, $message, $headers);

                if ($success) {
                    $customMessage['p1'] = "Thank you for your message!";
                    $customMessage['p2'] = "We'll get back to you ASAP";
                }
                else {
                    $customMessage['p1'] = "An error occurred";
                    $customMessage['p2'] = "Please try again later";
                }
            }
            catch (Exception $e) {
                error_log("Send mail error: {$e->getMessage()}\n");

                // Alert user
                $message = "An error occurred. Please try again later.";
                echo "<script>alert('$message');</script>";
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>
    <script src="https://kit.fontawesome.com/329329b608.js" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="./styles/nav.css">
    <link type="text/css" rel="stylesheet" href="./styles/auth.css">
    <link type="text/css" rel="stylesheet" href="./styles/contact.css">
</head>
<body>
    <?php include 'nav.php'; ?>

    <div class="container">
        <h1 id="contact-us" class="title">Contact Us</h1>

        <div class="content-container contact">
            <!-- Left Side -->
            <form id="contact-form" class="left-column" action="contact.php" method="POST" autocomplete="off">
                <!-- Contact Input Fields -->
                <div style="margin-top: 40px;">
                    <input id="name" type="text" name="name" placeholder="Name" autocomplete="off" required><br>
                </div>
                <div class="field">
                    <input id="email" type="email" name="email" placeholder="Email" autocomplete="off" required><br>
                </div>
                <div class="field">
                    <textarea id="message" class="message" name="message" cols="35" rows="15" placeholder="Message" autocomplete="off" required></textarea>
                </div>

                <?php
                    if ($error) echo "<p class='error'>*{$error}*</p>";
                ?>

                <!-- Send Message Button -->
                <input id="contact-submit" class="submit" type="submit" name="contactSubmit" value="Send Message">
            </form>

            <!-- Right Side -->
            <div class="right-column">
                <p style="font-weight: bold; font-size: 1.2vw;"><?= $customMessage['p1'] ?></p>
                <p><?= $customMessage['p2'] ?></p>
            </div>
        </div>
    </div>
</body>
</html>