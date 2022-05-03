<?php
if (!isset($_SESSION)) {
    @ob_start();
    session_start();
}

if (!isset($_SESSION['userID'])) {
    header('Location: Login.php');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Change Your Email - Gadget Gainey Store</title>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="keywords" content="Gadget Gainey, Gadget, Ecommerce, Online, Shop, Kids Toys, Toys, Technology, Gainey, Ashley Gainey">
    <meta name="author" content="Ashley Gainey">
    <meta name="description" content="Change your email of your Gadget Gainey account!">

    <link rel="stylesheet" type="text/css" href="sharedStyles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body>
    <?php include "./header.php" ?>

    <div id="bodyOfPage">
        <div>
            <p>Change Your Email</p>
        </div>
        <form id="EmailForm" action="/account_welcome.php" method="post" onsubmit="ChangeEmail()">
            <div class="cardArea">
                <div class="firstRow">
                    <div class="cardContainer leftPart disabled">
                        <p>Old Email</p>
                        <?php
                        echo "<input type='text' id='oldEmail' class='searchInput' disabled value='" . $_SESSION['userEmail'] . "' > ";
                        ?>
                    </div>

                    <div class="cardContainer rightPart">
                        <p>New Email</p>
                        <input type="text" id="newEmail" class="searchInput" placeholder="New Email" required>
                    </div>
                </div>
                <div class="secondRow">
                    <div class="cardContainer rightPart">
                        <p>Your Password</p>
                        <input type="password" id="password" class="searchInput" placeholder="Your Password" required>
                    </div>
                </div>

                <div class="secondRow">
                    <div class="cardContainer rightPart">
                        <input type="submit" value="Save">
                    </div>
                </div>
            </div>
        </form>
    </div>
    <p id="regMessage"></p>
    </div>
    <?php include "./footer.php" ?>
</body>

</html>
<style>
    #bodyOfPage {
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
        /* float: left; */
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
        width: 20%;
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

    #regMessage {
        margin-top: 25px;
        text-align: center;
        display: none;
    }

    .disabled input {
        border: 3px solid #000000;
        color: #000000;
    }

    input {
        margin: 0;
        padding: 0;
        height: 30px;
        font-size: 1.5em;
        border-radius: 25px;
        padding-top: 2%;
        padding-bottom: 2%;
        padding-right: 2%;
        padding-left: 3%;
        background: none;
        font-family: Century-Gothic, sans-serif;
        color: #FFFFFF;
        border: 3px solid #FFFFFF;
        outline: none;
        width: 100%;
    }

    input[type=submit] {
        margin-left: 10px;
        box-shadow: 0 0 0 5px #FFFFFF;
        border-radius: 2%;
        height: 75px;
        /* width: 250px; */
        overflow: hidden;
        position: relative;
        background-color: #1a1862;
    }
</style>

<script>
    function ChangeEmail() {
        event.preventDefault();

        var emailAddress = document.getElementById("newEmail").value;
        var password = document.getElementById("password").value;

        let xhr = new XMLHttpRequest();

        xhr.open('POST', "change_details.php", true)
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.send("emailAddress=" + emailAddress +
            "&password=" + password +
            "&process=" + "Email"
        );


        // Create an event to receive the return.
        xhr.onreadystatechange = function() {
            if (xhr.readyState == 4 && xhr.status == 200) {
                window.location.href = "account_welcome.php";
            } else if (xhr.readyState == 4 && (xhr.status == 400 || xhr.status == 500)) {
                document.getElementById("regMessage").style.display = "block";
                // var message = "An error occured while trying to do this action.";
                document.getElementById('regMessage').innerHTML = xhr.status + " " + xhr.responseText.replaceAll('"', '');
                console.log(xhr.responseText);
            }
        }

    }
</script>