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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">


    <title>My Account - Gadget Gainey Store</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <!--    Offline use -->
    <script src="jquery-3.4.1.min.js"></script>
</head>

<body>
    <?php include "./header.php" ?>

    <div id="mainBody">
        <div>
            <?php
            echo "<p>Welcome ";
            if (isset($_SESSION['register'])) {
                if ($_SESSION["register"] == false) {
                    echo "back ";
                }
                if (isset($_SESSION['userFirstName'])) {
                echo $_SESSION["userFirstName"] . "</p>";
                } else {
                    echo "</p>";
                }
            }
            ?>
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
                <div class="card" id="logOut">
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
</script>