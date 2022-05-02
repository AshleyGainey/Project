<?php
if (!isset($_SESSION)) {
    @ob_start();
    session_start();
}
// print_r('UserID: ' . $_SESSION['userID']);
// if (!isset($_SESSION['userID'])) {
//     header('Location: Login.php#hello');
// }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>My Account - Gadget Gainey Store</title>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="keywords" content="Gadget Gainey, Gadget, Ecommerce, Online, Shop, Kids Toys, Toys, Technology, Gainey, Ashley Gainey">
    <meta name="author" content="Ashley Gainey">
    <meta name="description" content="Welcome to your Gadget Gainey account! Change your account details and look at your order history!">

    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body>
    <?php include "./header.php" ?>

    <div id="mainBody">
        <div>
            <p id="welcome">Welcome!</p>
        </div>
        <div class="cardArea">
            <div class="firstRow">
                <div class="cardContainer leftPart">
                    <div class="card">
                        <div class="writingOfCard">
                            <a href="account_details.php">Account Details<img src="images/Home/Right Arrow.svg" alt="Order Details" /></a>
                        </div>
                    </div>
                </div>

                <div class="cardContainer rightPart">
                    <div class="card">
                        <div class="writingOfCard">
                            <a href="order_details.php">Order History<img src="images/Home/Right Arrow.svg" alt="Order Details" /></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="secondRow">
                <div class="card cardContainer" id="logOut">
                    <div class="writingOfCard">
                        <a>Log Out</a>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <?php include "./footer.php" ?>
</body>

</html>
<style>
    #confettiIcon {
        width: 200px;
        margin-bottom: 50px;
        color: #FFFFFF;
    }

    #orderComplete {
        text-align: center;
        position: absolute;
        top: 50%;
        left: 50%;
        margin-right: -50%;
        transform: translate(-50%, -50%);
    }

    #orderComplete h1 {
        margin-bottom: 10px;
    }

    #mainBody {
        margin-top: 30px;
        margin-left: 50px;
        margin-right: 50px;

        /* Was having an issue if I typed more than expected for the search, then it would destroy the padding 
	so have added word-wrap, this should apply to the main_container, no matter whether it is a heading 
	(h1, h2, h3 etc.), paragraph (p) or something other */
        word-wrap: break-word;

    }



    .card {
        box-shadow: 0 0 0 5px #FFFFFF;
        border-radius: 2%;
        height: 75px;
        width: 250px;
        overflow: hidden;
        position: relative;
    }

    .firstRow {
        display: inline-block;
        width: 100%;
        margin-top: 50px;
    }

    .secondRow .card {
        background-color: #1a1862;
        color: #FFFFFF
    }

    .secondRow {
        margin-top: 50px;
        width: 100%;
        float: left;
        margin-left: auto;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .secondRow .card .writingOfCard a {
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

    .leftPart {
        float: left;
    }

    .rightPart {
        float: right;
    }
</style>

<script>
    document.getElementById("logOut").onclick = function() {
        document.location = 'logout.php';
    };

    document.addEventListener("DOMContentLoaded", function() {
        activeModes();
    });

    // window.addEventListener("load", function() {
    //     activeModes();
    // });

    function activeModes() {
        //See if account_welcome.php is set to Register or Login by the account_welcome.php#Register or account_welcome.php#Login and show 'welcome back' or 'welcome' message
        var url = window.location.href;
        var afterURL = url.substring(url.indexOf("#") + 1);

        if (afterURL.length != 0) {
            //Show Register if #Register
            if (afterURL == "Register") {
                document.getElementById("welcome").innerHTML = "Welcome" + "<?php echo ", " . $_SESSION["userFirstName"] . "!" ?>"

            } else {
                //Show Login if anything other than Register
                document.getElementById("welcome").innerHTML = "Welcome back" + "<?php echo ", " . $_SESSION["userFirstName"] . "!" ?>"
            }
        } else {
            document.getElementById("welcome").innerHTML = "Welcome" + "<?php echo ", " . $_SESSION["userFirstName"] . "!" ?>"
        }
    }
</script>