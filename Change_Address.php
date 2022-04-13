<?php
if (!isset($_SESSION)) {
    @ob_start();
    session_start();
}

if (!isset($_SESSION['userID'])) {
    header('Location: Login.php');
}

// Query for getting the address

// select u.userID, ua.addressID, ua.addressLine1, ua.addressLine2, ua.townCity, ua.county, ua.postcode from user u INNER JOIN user_address ua ON u.mainAddressID = ua.addressID where u.userID  = 13;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">


    <title>Change Your Main Address - Gadget Gainey Store</title>
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
            <p>Change Your Main Address</p>
        </div>
        <div class="allElementss" id="Register">
            <div class="allElements">
                <form id="PasswordForm" action="/account_welcome.php" method="post">
                    <h5>Address Details</h5>
                    <div class="cardContainer">
                        <p>Title</p>
                        <select id="regTitle" name="title" id="title" required>
                            <option value="Mr">Mr</option>
                            <option value="Mrs">Mrs</option>
                            <option value="Miss">Miss</option>
                            <option value="Ms">Ms</option>
                            <option value="Dr">Dr</option>
                        </select>
                    </div>

                    <div class="cardContainer">
                        <p>First Name</p>
                        <input id="regFirstName" type="text" class="searchInput" placeholder="First Name" required pattern="^\D+$" minlength=2 maxlength=255 required>
                    </div>
                    <div class="cardContainer">
                        <p>Last Name</p>
                        <input id="regLastName" type="text" class="searchInput" placeholder="Last Name" required pattern="^\D+$" minlength=2 maxlength=255 required>
                    </div>
                    <div class="cardContainer">
                        <p>Address Line 1</p>
                        <input id="regAddressLine1" type="text" class="searchInput" placeholder="Address Line 1" minlength=2 maxlength=255 required>
                    </div>
                    <div class="cardContainer">
                        <p>Address Line 2</p>
                        <input id="regAddressLine2" type="text" class="searchInput" placeholder="Address Line 2" minlength=2 maxlength=255>
                    </div>
                    <div class="cardContainer">
                        <p>Town/City</p>
                        <input id="regTownCity" type="text" class="searchInput" placeholder="Town/City" minlength=2 maxlength=255 required>
                    </div>
                    <div class="cardContainer">
                        <p>County</p>
                        <input id="regCounty" type="text" class="searchInput" placeholder="County" minlength=2 maxlength=255 required>
                    </div>
                    <div class="cardContainer">
                        <p>Post Code</p>
                        <input id="regPostCode" type="text" class="searchInput" placeholder="Post Code" minlength=5 maxlength=8 required>
                    </div>
                    <input type="submit" value="Register">
                </form>
                <p id="regMessage"></p>
            </div>
        </div>
    </div>

    </div>
    <?php include "./footer.php" ?>
</body>

</html>
<style>
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

    .allElementss {
        display: flex;
        align-items: center;
        justify-content: center;
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

    /* 
    #regMessage {
        display: none;
    } */

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
        width: 250px;
        overflow: hidden;
        position: relative;
        background-color: #1a1862;
    }
</style>

<script>
    $("#PasswordForm").submit(function(event) {

        event.preventDefault();
        var oldPassword = $("#oldPassword").val();
        var newPassword = $("#newPassword").val();
        var confirmPassword = $("#confirmPassword").val();
        debugger;
        //Do more validation with Ajax this time
        $("#regMessage").load("change_details.php", {
            oldPassword: oldPassword,
            newPassword: newPassword,
            confirmPassword: confirmPassword,
            process: "Password"
        }, function(response, status, xhr) {
            debugger;
            if (status == "error") {
                document.getElementById("regMessage").style.display = "block";
                // var message = "An error occured while trying to do this action.";
                document.getElementById('regMessage').innerHTML = xhr.status + " " + xhr.responseText.replaceAll('"', '');
            }
            if (status == "success") {
                window.location.href = "account_welcome.php";
            }
        })
    });
</script>