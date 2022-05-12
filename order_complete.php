<?php
// If the session hasn't started. Start it (can then use session variables)
if (!isset($_SESSION)) {
    @ob_start();
    @session_start();
}

//If you try to come to this page (using the URL or by navigating to it) and the Order ID has not been set, it means that the order didn't go through/the user didn't actually place an order
// so therefore, redirect them to the checkout page
if (!isset($_SESSION['orderID'])) {
    header('Location: checkout.php');
}
// The Order ID variable is equal to the session's Order ID. And then remove the Order ID variable from the session
$orderID = $_SESSION['orderID'];
unset($_SESSION['orderID']);

// Since we are here with a successful order completion, remove the basket variable from the session, removing the products out of the basket
unset($_SESSION['basket']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <!-- Put a viewport on this page -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Keywords of the site for search engine optimisation -->
    <meta name="keywords" content="Gadget Gainey, Gadget, Ecommerce, Online, Shop, Kids Toys, Toys, Technology, Gainey, Ashley Gainey">
    <!-- Icons for Gadget Gainey - Based on the size and who uses them -->
    <link rel="apple-touch-icon" sizes="180x180" href="images/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="images/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="images/favicon/favicon-16x16.png">
    <link rel="manifest" href="images/favicon/site.webmanifest">
    <link rel="mask-icon" href="images/favicon/safari-pinned-tab.svg" color="#5bbad5">
    <link rel="shortcut icon" href="images/favicon/favicon.ico">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="msapplication-config" content="images/favicon/browserconfig.xml">
    <meta name="theme-color" content="#ffffff">
    <!-- Author of the site -->
    <meta name="author" content="Ashley Gainey">
    <!-- Description of the page -->
    <meta name="description" content="Order Complete!">

    <!-- Don't let any search engine index this page -->
    <meta name="robots" content="noindex" />
    <!-- Shows what the title of the tab is-->
    <title>Order Complete! - Gadget Gainey Store</title>
    <!-- Link to the shared classes and IDs style sheet -->
    <link rel="stylesheet" type="text/css" href="sharedStyles.css">
</head>

<body>
    <!-- Add the header at the top before any other material -->
    <?php include "./header.php" ?>

    <div id="bodyOfPage">
        <!-- Div to Hold all the information -->
        <div id="orderComplete">
            <!-- Image to represent that the order has been successful -->
            <img src="Images\OrderComplete\confetti-svgrepo-com.svg" id="confettiIcon">
            <!-- Header as confirmation for the order has been successful -->
            <h1> Your Order is Complete!</h1>
            <!-- Show the Order Number (and pad it out to 5 digits) e.g 00001 -->
            <h2> Order Number:
                <?php
                echo sprintf('%05d', $orderID);
                ?>
            </h2>

            <!-- Show a button (with the arrow) for the user to click to see their Order Details -->
            <div class="cardArea">
                <div class="cardContainer">
                    <div class="card">
                        <div class="writingOfCard">
                            <a href="order_history.php">Order Details<img src="images/Home/Right Arrow.svg" alt="Order Details" /></a>
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
    #orderComplete {
        text-align: center;
        position: absolute;
        top: 50%;
        left: 50%;
        margin-right: -50%;
        transform: translate(-50%, -50%);
    }

    #confettiIcon {
        width: 200px;
        margin-bottom: 50px;
        color: #FFFFFF;
    }

    .cardArea {
        display: inline-block;
        margin-top: 50px;
    }

    .cardContainer {
        width: 250px;
    }

    .card {
        box-shadow: 0 0 0 5px #FFFFFF;
        border-radius: 2%;
        height: 75px;
        width: 250px;
        overflow: hidden;
        position: relative;
    }

    .writingOfCard {
        position: absolute;
        top: 50%;
        left: 50%;
        -ms-transform: translate(-50%, -50%);
        transform: translate(-50%, -50%);
        width: 100%
    }

    .writingOfCard a {
        font-size: 25px;
        text-decoration: none;
        color: #000000;
    }

    .writingOfCard img {
        float: right;
        width: 20px;
        margin-right: 5px;
    }
</style>