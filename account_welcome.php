<?php
// If the session hasn't started. Start it (can then use session variables)
if (!isset($_SESSION)) {
    @ob_start();
    session_start();
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
    <title>My Account - Gadget Gainey Store</title>
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
    <meta name="description" content="Welcome to your Gadget Gainey account! Change your account details and look at your order history!">

    <link rel="stylesheet" type="text/css" href="sharedStyles.css">
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> -->
</head>

<body>
    <?php include "./header.php" ?>

    <div id="bodyOfPage">
        <div>
            <!-- Show Welcome message (with be generated in JavaScript) -->
            <h2 id="welcome">Welcome!</h2>
        </div>
        <div class="allCards">
            <div class="firstRowOfCards">
                <!-- Show Account Details with text around a container -->
                <div class="cardContainer leftPart">
                    <div class="card">
                        <div class="writingOfCard">
                            <a href="account_details.php">Account Details<img src="images/Home/Right Arrow.svg" alt="Account Details - Right Arrow" /></a>
                        </div>
                    </div>
                </div>
                <!-- Show Order History Details with text around a container -->
                <div class="cardContainer rightPart">
                    <div class="card">
                        <div class="writingOfCard">
                            <a href="order_history.php">Order History<img src="images/Home/Right Arrow.svg" alt="Order History - Right Arrow" /></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="secondRowOfCards">
                <!-- On a separate row, show Log Out text around a container -->
                <div class="card cardContainer" id="logOut">
                    <div class="writingOfCard">
                        <a href="logout.php">Log Out</a>
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
    .card {
        box-shadow: 0 0 0 5px #FFFFFF;
        border-radius: 2%;
        height: 75px;
        width: 250px;
        overflow: hidden;
        position: relative;
    }

    .firstRowOfCards {
        display: inline-block;
        width: 100%;
        margin-top: 50px;
    }

    .secondRowOfCards .card {
        background-color: #1a1862;
        color: #FFFFFF
    }

    .secondRowOfCards {
        margin-top: 50px;
        width: 100%;
        float: left;
        margin-left: auto;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .secondRowOfCards .card .writingOfCard a {
        color: #FFFFFF
    }

    .cardContainer {
        display: inline-block;
        width: 250px;
        cursor: pointer;
    }

    .writingOfCard a {
        font-size: 25px;
        ;
        text-decoration: none;
        color: #000000;
        position: relative;
    }

    .writingOfCard img {
        float: right;
        width: 20px;
        margin-right: 2px;
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
        width: 100%
    }
</style>

<script>
    //See if account_welcome.php is set to Register or Login by the account_welcome.php#Register or account_welcome.php#Login and show 'welcome back' or 'welcome' message
    var url = window.location.href;
    var afterURL = url.substring(url.indexOf("#") + 1);

    if (afterURL.length != 0) {
        //Show Register if #Register
        if (afterURL == "Register") {
            document.getElementById("welcome").innerHTML = "Welcome" + "<?php echo ", " . $_SESSION["userFirstName"] . "!" ?>"
        } else if (afterURL == "Login") {
            //Show Login if Login
            document.getElementById("welcome").innerHTML = "Welcome back" + "<?php echo ", " . $_SESSION["userFirstName"] . "!" ?>"
        } else {
            // Show 'Welcome' if the value after the URL is not Register or Login
            document.getElementById("welcome").innerHTML = "Welcome" + "<?php echo ", " . $_SESSION["userFirstName"] . "!" ?>"
        }
    } else {
        // Show 'Welcome back' if there is nothing after the URL (means they have already logged in before)
        document.getElementById("welcome").innerHTML = "Welcome back" + "<?php echo ", " . $_SESSION["userFirstName"] . "!" ?>"
    }
</script>