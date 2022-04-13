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
include 'DBlogin.php';

$conn = mysqli_connect($host, $user, $pass, $database);


$stmt = $conn->prepare("select u.userID, u.userTitle, u.userFirstName, u.userLastName, ua.addressID, ua.addressLine1, ua.addressLine2, ua.townCity, ua.county, ua.postcode from user u INNER JOIN user_address ua ON u.mainAddressID = ua.addressID where u.userID  = ?");
$userID =    $_SESSION['userID'];
$stmt->bind_param("s", $userID);

$stmt->execute();

$res = $stmt->get_result();
$mainaddressDB = mysqli_fetch_all($res, MYSQLI_ASSOC);
print_r($mainaddressDB);

$userTitle = $mainaddressDB[0]['userTitle'];
$userFirstName = $mainaddressDB[0]['userFirstName'];
$userLastName = $mainaddressDB[0]['userLastName'];
$userAddressID = $mainaddressDB[0]['addressID'];

$userAddressLine1 = $mainaddressDB[0]['addressLine1'];
$userAddressLine2 = $mainaddressDB[0]['addressLine2'];
$userTownCity = $mainaddressDB[0]['townCity'];
$userCounty = $mainaddressDB[0]['county'];
$userPostCode = $mainaddressDB[0]['postcode'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">


    <title>Change Address Details - Gadget Gainey Store</title>
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
        <div class="allElementss" id="AddressChange">
            <div class="allElements">
                <form action="/account_welcome.php" id="MainAddressForm" method="post">
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
                        <?php
                        echo "<input id='regFirstName' type='text' class='searchInput' value='" . $userFirstName . "' placeholder='First Name' required pattern='^\D+$' minlength=2 maxlength=255 required>";

                        ?>
                    </div>
                    <div class="cardContainer ">
                        <p>Last Name</p>
                        <?php
                        echo "<input id='regLastName' type='text' class='searchInput' value='" . $userLastName . "' placeholder='Last Name' required pattern='^\D+$' minlength=2 maxlength=255 required>";
                        ?>
                    </div>
                    <div class="cardContainer">
                        <p>Address Line 1</p>
                        <?php
                        echo "<input id='regAddressLine1' type='text' class='searchInput' value='" . $userAddressLine1 . "'  placeholder='Address Line 1' minlength=2 maxlength=255 required>";
                        ?>
                    </div>
                    <div class="cardContainer">
                        <p>Address Line 2</p>
                        <?php
                        echo "<input id='regAddressLine2' type='text' class='searchInput' value='" . $userAddressLine2 . "' placeholder='Address Line 2' minlength=2 maxlength=255>";
                        ?>
                    </div>
                    <div class="cardContainer">
                        <p>Town/City</p>
                        <?php
                        echo "<input id='regTownCity' type='text' class='searchInput' value='" . $userTownCity . "' placeholder='Town/City' minlength=2 maxlength=255 required>";
                        ?>
                    </div>
                    <div class="cardContainer">
                        <p>County</p>
                        <?php
                        echo "<input id='regCounty' type='text' class='searchInput' value='" . $userCounty . "' placeholder='County' minlength=2 maxlength=255 required>"
                        ?>
                    </div>
                    <div class="cardContainer">
                        <p>Post Code</p>
                        <?php
                        echo  "<input id='regPostCode' type='text' class='searchInput' value='" . $userPostCode . "' placeholder='Post Code' minlength=5 maxlength=8 required>"
                        ?>
                    </div>
                    <input type="submit" value="Save">
                </form>
                <p id="regMessage"></p>
            </div>
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

    .location {
        margin: 50px;
    }

    .doAction {
        margin-left: 20px;
    }

    .buttonContainer {
        box-shadow: 0 0 0 5px #FFFFFF;
        border-radius: 2%;
        height: 75px;
        width: 250px;
        overflow: hidden;
        position: relative;
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

    .firstRow {
        display: inline-block;
        width: 100%;
        margin-top: 50px;
    }

    .unselected {
        background-color: #464646;
        color: #FFFFFF
    }

    .selected {
        background-color: #1a1862;
        color: #FFFFFF
    }

    .inline {
        /* display: inline-block; */
    }

    input[type="text"] {
        padding: 9%;
    } 
    .buttons {
        margin-top: 50px;
        width: 100%;
        /* float: left; */
        margin-left: auto;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .buttonContainer .writingOfButton a {
        color: #FFFFFF
    }

    /* 
    .cardContainer {
        display: inline-block;
    } */

    .writingOfButton a {
        font-size: 25px;
        ;
        text-decoration: none;
        color: #000000;
        position: relative;
    }

    .writingOfButton img {
        float: right;
        width: 20px;
        margin-right: 2px;
    }

    .writingOfButton {
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

    }

    .allElementss {
        display: flex;
        align-items: center;
        justify-content: center;
    }


    .cardContainer {
        margin-top: 20px;
        margin-bottom: 20px;
        /* width: 20%; */
    }

    .invalid,
    #passwordMatch {
        color: red;
    }

    .valid {
        color: green;
    }


    select:hover {
        border-color: #1a1862;
        cursor: pointer;
        border-width: 3px;
    }

    select {
        width: 100px;
        padding: 5px;
        font-size: 16px;
        line-height: 1;
        border-radius: 25px;
        height: 34px;
        background: url('images/Down%20Arrow.png') no-repeat right;
        -webkit-appearance: none;
        background-position-x: 70px;
        color: #FFFFFF;
        border-style: solid;
        border-width: 2px;
        border-color: #FFFFFF;
    }

    select option {
        background-color: #bfbfbf;
    }

    .hide {
        display: none;
    }

    .show {
        display: inline;
    }

    #regMessage {
        display: none;
    }


    #confirmPasswordValidationMessage {
        margin-bottom: 10px;
    }
</style>

<script>
    // $("#RegisterForm").submit(function(event) {

    //     event.preventDefault();
    //     var emailAddress = $("#regEmail").val();
    //     var password = $("#regPassword").val();

    //     //Personal Information values
    //     var title = $("#regTitle").val();
    //     var firstName = $("#regFirstName").val();
    //     var lastName = $("#regLastName").val();
    //     var addressLine1 = $("#regAddressLine1").val();
    //     var addressLine2 = $("#regAddressLine2").val();
    //     var townCity = $("#regTownCity").val();
    //     var county = $("#regCounty").val();
    //     var postcode = $("#regPostCode").val();
    //     //Do more validation with Ajax this time
    //     $("#regMessage").load("passwordCheck.php", {
    //         emailAddress: emailAddress,
    //         password: password,
    //         title: title,
    //         firstName: firstName,
    //         lastName: lastName,
    //         addressLine1: addressLine1,
    //         addressLine2: addressLine2,
    //         townCity: townCity,
    //         county: county,
    //         postcode: postcode,
    //         Register: true
    //     }, function(response, status, xhr) {
    //         debugger;
    //         if (status == "error") {
    //             var message = "An error occured while registering. Please see below :";
    //             document.getElementById("regMessage").style.display = "block";
    //             $("#regMessage").html(message + xhr.status + "" + xhr.statusText)
    //         }
    //         if (status == "success") {
    //             window.location.href = "account_welcome.php";
    //         }
    //     })
    // });
</script>