<?php
if (!isset($_SESSION)) {
    @ob_start();
    session_start();
}

if (!isset($_SESSION['userID'])) {
    header('Location: Login.php');
}

// Query for getting the address

include 'DBlogin.php';

$conn = mysqli_connect($host, $user, $pass, $database);


$stmt = $conn->prepare("select ua.title, ua.firstName, ua.lastName, ua.addressID, ua.addressLine1, ua.addressLine2, ua.townCity, ua.county, ua.postcode from user u INNER JOIN address ua ON u.mainAddressID = ua.addressID where u.userID  = ?");
$userID =    $_SESSION['userID'];
$stmt->bind_param("s", $userID);

$stmt->execute();

$res = $stmt->get_result();
$mainaddressDB = mysqli_fetch_all($res, MYSQLI_ASSOC);
// print_r($mainaddressDB);

$userTitle = $mainaddressDB[0]['title'];
$userFirstName = $mainaddressDB[0]['firstName'];
$userLastName = $mainaddressDB[0]['lastName'];
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body>
    <?php include "./header.php" ?>

    <div id="mainBody">
        <div>
            <p>Change Your Main Address</p>
        </div>
        <div class="allElementss" id="AddressChange">
            <div class="allElements">
                <form action="account_welcome.php" id="MainAddressForm" method="post" onsubmit="changeAddress()">
                    <div class="cardContainer">
                        <p>Title<span class="required">*</span></p>
                        <select id="title" name="title" id="title" required>
                            <?php
                            if ($userTitle == "Mr") {
                                echo "<option value='Mr'selected>Mr</option>";
                            } else {
                                echo "<option value='Mr'>Mr</option>";
                            }

                            if ($userTitle == "Mr") {
                                echo "<option value='Master' selected>Master</option>";
                            } else {
                                echo "<option value='Master'>Master</option>";
                            }

                            if ($userTitle == "Mrs") {
                                echo "<option value='Mrs' selected>Mrs</option>";
                            } else {
                                echo "<option value='Mrs'>Mrs</option>";
                            }

                            if ($userTitle == "Miss") {
                                echo "<option value='Miss' selected>Miss</option>";
                            } else {
                                echo "<option value='Miss'>Miss</option>";
                            }

                            if ($userTitle == "Ms") {
                                echo "<option value='Ms' selected>Ms</option>";
                            } else {
                                echo "<option value='Ms'>Ms</option>";
                            }
                            if ($userTitle == "Dr") {
                                echo "<option value='Dr' selected>Dr</option>";
                            } else {
                                echo "<option value='Dr'>Dr</option>";
                            }
                            ?>


                        </select>
                    </div>

                    <div class="cardContainer">
                        <p>First Name<span class="required">*</span></p>
                        <?php
                        echo "<input id='firstName' type='text' class='searchInput' value='" . $userFirstName . "' placeholder='First Name' required pattern='^\D+$' minlength=2 maxlength=255 required>";

                        ?>
                    </div>
                    <div class="cardContainer ">
                        <p>Last Name<span class="required">*</span></p>
                        <?php
                        echo "<input id='lastName' type='text' class='searchInput' value='" . $userLastName . "' placeholder='Last Name' required pattern='^\D+$' minlength=2 maxlength=255 required>";
                        ?>
                    </div>
                    <div class="cardContainer">
                        <p>Address Line 1<span class="required">*</span></p>
                        <?php
                        echo "<input id='addressLine1' type='text' class='searchInput' value='" . $userAddressLine1 . "'  placeholder='Address Line 1' minlength=2 maxlength=255 required>";
                        ?>
                    </div>
                    <div class="cardContainer">
                        <p>Address Line 2</p>
                        <?php
                        echo "<input id='addressLine2' type='text' class='searchInput' value='" . $userAddressLine2 . "' placeholder='Address Line 2' minlength=2 maxlength=255>";
                        ?>
                    </div>
                    <div class="cardContainer">
                        <p>Town/City<span class="required">*</span></p>
                        <?php
                        echo "<input id='townCity' type='text' class='searchInput' value='" . $userTownCity . "' placeholder='Town/City' minlength=2 maxlength=255 required>";
                        ?>
                    </div>
                    <div class="cardContainer">
                        <p>County<span class="required">*</span></p>
                        <?php
                        echo "<input id='county' type='text' class='searchInput' value='" . $userCounty . "' placeholder='County' minlength=2 maxlength=255 required>"
                        ?>
                    </div>
                    <div class="cardContainer">
                        <p>Post Code<span class="required">*</span></p>
                        <?php
                        echo  "<input id='postCode' type='text' class='searchInput' value='" . $userPostCode . "' placeholder='Post Code' minlength=5 maxlength=8 required>"
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

    .required {
        color: red;
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

    /* input[type="text"] {
        padding: 9%;
    } */

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
    function changeAddress() {
        event.preventDefault();

        title = document.getElementById("title").value;
        if (title != "Master" && title != "Mr" &&
            title != "Mrs" && title != "Ms" && title != "Miss" &&
            title != "Dr") {
            outputMessage = "Delivery Address: Not a value Name Title. Please fill the section in correctly";
            showHideMessage(true, outputMessage);
            return false;
        }

        firstName = document.getElementById("firstName").value;
        if (!firstName) {
            outputMessage = "Delivery Address: First Name cannot be blank, please fill out that field.";
            showHideMessage(true, outputMessage);
            return false;
        }
        lastName = document.getElementById("lastName").value;
        if (!lastName) {
            outputMessage = "Delivery Address: Last Name cannot be blank, please fill out that field.";
            showHideMessage(true, outputMessage);
            return false;
        }
        addressLine1 = document.getElementById("addressLine1").value;
        if (!addressLine1) {
            outputMessage = "Delivery Address: Address Line 1 cannot be blank, please fill out that field.";
            showHideMessage(true, outputMessage);
            return false;
        }
        addressLine2 = document.getElementById("addressLine2").value;

        townCity = document.getElementById("townCity").value;
        if (!townCity) {
            outputMessage = "Delivery Address: Town/City cannot be blank, please fill out that field.";
            showHideMessage(true, outputMessage);
            return false;
        }
        county = document.getElementById("county").value;
        if (!county) {
            outputMessage = "Delivery Address: County cannot be blank, please fill out that field.";
            showHideMessage(true, outputMessage);
            return false;
        }
        postCode = document.getElementById("postCode").value;
        if (!postCode) {
            outputMessage = "Delivery Address: Post Code cannot be blank, please fill out that field.";
            showHideMessage(true, outputMessage);
            return false;
        }


        let xhr = new XMLHttpRequest();

        xhr.open('POST', "change_details.php", true)
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.send("title=" + title +
            "&firstName=" + firstName +
            "&lastName=" + lastName +
            "&addressLine1=" + addressLine1 +
            "&addressLine2=" + addressLine2 +
            "&townCity=" + townCity +
            "&county=" + county +
            "&postcode=" + postCode +
            "&process=" + "Address"
        );


        // Create an event to receive the return.
        xhr.onreadystatechange = function() {
            if (xhr.readyState == 4 && xhr.status == 200) {
                window.location.href = "account_welcome.php";
            } else if (xhr.readyState == 4 && (xhr.status == 400 || xhr.status == 500)) {
                    document.getElementById("regMessage").style.display = "block";
                    document.getElementById('regMessage').innerHTML = xhr.status + " " + xhr.responseText;
                    console.log(xhr.responseText);
                }
            }
        }
</script>