<?php
// If the session hasn't started. Start it (can then use session variables)
if (!isset($_SESSION)) {
    @ob_start();
    @session_start();
}

//If you try to come to this page (using the URL or by navigating to it) and you haven't signed in yet, redirect to the Login page to sign in
if (!isset($_SESSION['userID'])) {
    header('Location: Login.php');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Shows what the title of the tab is-->
    <title>Account Details - Gadget Gainey Store</title>
    <!-- Character set for the page -->
    <meta charset="UTF-8">
    <!-- What type is this page? HTML-->
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <!-- Put a viewport on this page -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Keywords of the site for search engine optimisation -->
    <meta name="keywords" content="Gadget Gainey, Gadget, Ecommerce, Online, Shop, Kids Toys, Toys, Technology, Gainey, Ashley Gainey">
    <!-- Author of the site -->
    <meta name="author" content="Ashley Gainey">
    <!-- Description of the page -->
    <meta name="description" content="Change Your Account Details of your Gadget Gainey account. Details include Changing your Email, Changing your password and changing your main address.">

    <!-- Link to the style sheet -->
    <link rel="stylesheet" type="text/css" href="sharedStyles.css">
</head>

<body>
    <!-- Add the header at the top before any other material -->
    <?php include "./header.php" ?>

    <div id="bodyOfPage">
        <div id="title">
            <!-- Title of Page -->
            <h2>Account Details</h2>
        </div>
        <div id="allCards">
            <div id="firstRowOfCards">
                <!-- Show Change Your Email as a card with text and an image of the right arrow -->
                <div class="cardContainer leftPart">
                    <div class="card">
                        <div class="writingOfCard">
                            <a href="Change_Email.php">Change Your Email</a><img src="images/Home/Right Arrow.svg" alt="Change Your Email - Right Arrow" />
                        </div>
                    </div>
                </div>
                <!-- Show Change Your Password as a card with text and an image of the right arrow -->
                <div class="cardContainer rightPart">
                    <div class="card">
                        <div class="writingOfCard">
                            <a href="Change_Password.php">Change Your Password</a><img src="images/Home/Right Arrow.svg" alt="Change Your Password - Right Arrow" />
                        </div>
                    </div>
                </div>
            </div>
            <div id="secondRowOfCards">
                <div class="cardContainer">
                    <div class="card">
                        <!-- On a separate  row, show Change Your Main Address as a card with text and an image of the right arrow -->
                        <div class="writingOfCard">
                            <a href="Change_Address.php">Change Your Main Address<img src="images/Home/Right Arrow.svg" alt="Change Main Address - Right Arrow" /></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Add the footer at the bottom after any other material -->
    <?php include "./footer.php" ?>
</body>

</html>
<style>
    #firstRowOfCards {
        display: inline-block;
        width: 100%;
        margin-top: 50px;
    }

    #secondRowOfCards {
        margin-top: 50px;
        width: 100%;
        float: left;
        margin-left: auto;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .card {
        box-shadow: 0 0 0 5px #FFFFFF;
        border-radius: 2%;
        height: 75px;
        overflow: hidden;
        position: relative;
        padding: 10px;
    }

    .cardContainer {
        display: inline-block;
        width: 250px;
        cursor: pointer;
    }

    .writingOfCard {
        margin-top: 10px;
        text-align: center;
        margin: 0;
        position: absolute;
        top: 50%;
        left: 50%;
        -ms-transform: translate(-50%, -50%);
        transform: translate(-50%, -50%);
        width: 100%;
        display: inline-flex;
    }

    .writingOfCard a {
        text-decoration: none;
        position: relative;

        font-size: 25px;
        color: #000000;
    }

    .writingOfCard img {
        float: right;
        width: 20px;
        margin-right: 2px;
    }

    .leftPart {
        float: left;
    }

    .rightPart {
        float: right;
    }
</style>