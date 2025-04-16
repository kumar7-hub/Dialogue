<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>
    <link type="text/css" rel="stylesheet" href="./styles/auth.css">
    <link type="text/css" rel="stylesheet" href="./styles/contact.css">
</head>
<body>
    <div class="container">
        <h1 class="title" style="width: 16vw;">Contact Us</h1>

        <div class="content-container contact">
            <form id="contact-form" class="left-column" action="contact.php" method="GET" autocomplete="off">
                <div style="margin-top: 40px;">
                    <input id="uname" type="text" name="uname" placeholder="Username" required><br>
                </div>

                <div class="field">
                    <input id="email" type="email" name="email" placeholder="Email" required><br>
                </div>

                <div class="field">
                    <textarea id="message" class="message" name="message" cols="35" rows="15" placeholder="Message" required></textarea>
                </div>

                <input id="contact-submit" class="submit" type="submit" name="contactSubmit" value="Send Message">
            </form>

            <div class="right-column">
                <p style="font-weight: bold; font-size: 1.2vw;">Have a question, concern, or feedback?</p>
                <p>Fill out this form!</p>
            </div>
        </div>
    </div>
</body>
</html>