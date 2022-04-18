<?php
// print_r('session here: ' . isset($_SESSION));

if (!isset($_SESSION)) {
    @ob_start();
    @session_start();
}

// print_r('session: ' . $_SESSION['userID']);

if (!isset($_SESSION['basket'])) {
    header('Location: basket.php');
}

if (!isset($_SESSION['userID'])) {
    $_SESSION['comeBackToCheckOut'] = true;
    header('Location: Login.php');
}


// Query for getting the address

// select u.userID, ua.addressID, ua.addressLine1, ua.addressLine2, ua.townCity, ua.county, ua.postcode from user u INNER JOIN user_address ua ON u.mainAddressID = ua.addressID where u.userID  = 13;
include 'DBlogin.php';

$conn = mysqli_connect($host, $user, $pass, $database);


$stmt = $conn->prepare("select u.userID, u.userTitle, u.userFirstName, u.userLastName, ua.addressID, ua.addressLine1, ua.addressLine2, ua.townCity, ua.county, ua.postcode from user u INNER JOIN user_address ua ON u.mainAddressID = ua.addressID where u.userID  = ?");
$stmt->bind_param("i", $_SESSION['userID']);

$stmt->execute();

$res = $stmt->get_result();
$mainaddressDB = mysqli_fetch_all($res, MYSQLI_ASSOC);
// print_r($mainaddressDB);
$mainAddressUserTitle = $mainaddressDB[0]['userTitle'];
$mainAddressUserFirstName = $mainaddressDB[0]['userFirstName'];
$mainAddressUserLastName = $mainaddressDB[0]['userLastName'];
$mainAddressUserAddressID = $mainaddressDB[0]['addressID'];

$mainAddressUserAddressLine1 = $mainaddressDB[0]['addressLine1'];
$mainAddressUserAddressLine2 = $mainaddressDB[0]['addressLine2'];
$mainAddressUserTownCity = $mainaddressDB[0]['townCity'];
$mainAddressUserCounty = $mainaddressDB[0]['county'];
$mainAddressUserPostCode = $mainaddressDB[0]['postcode'];

$strFirstPart = implode(' ', array($mainAddressUserTitle, $mainAddressUserFirstName, $mainAddressUserLastName));



$strSecondPart = implode(' ', array($mainAddressUserAddressLine1, $mainAddressUserTownCity, $mainAddressUserCounty, $mainAddressUserPostCode));




if (empty($mainAddressUserAddressLine2)) {
    $strSecondPart = implode(', ', array($mainAddressUserAddressLine1, $mainAddressUserTownCity, $mainAddressUserCounty));
} else {
    $strSecondPart = implode(' ', array($mainAddressUserAddressLine1, $mainAddressUserAddressLine2, $mainAddressUserTownCity, $mainAddressUserCounty));
}

$mainAddressDisplay = $strFirstPart . ", " . $strSecondPart . ". " . $mainAddressUserPostCode;
// echo $mainAddressDisplay;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">


    <title>Checkout - Gadget Gainey Store</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <!--    Offline use -->
    <script src="jquery-3.4.1.min.js"></script>
</head>

<body>
    <?php include "./header.php" ?>
    <div id="mainBody">
        <div class="title">
            <i class="fa fa-shopping-cart"></i>
            <h1>Checkout</h1>
        </div>
        <div class="totalContainer">
            <div class="totalContainer2">
                <div class="total">
                    <h1 class="totalHeader">Total:</h1>
                    <?php
                    echo "<h1 class='totalAmount'>£" . number_format($_SESSION["total"], 2) . "<h1>";
                    ?>
                </div>
            </div>
        </div>
        <div class="allElementss">
            <div class="allElements">
                <form action="placeOrder.php" id="OrderForm" method="post" onsubmit="payment()">

                    <h1 class="billingAddress">Billing Address</h1>






                    <div class="radioGroup">
                        <label class="container" id="labelB1" for="B1">Main Address: <?php echo $mainAddressDisplay ?>
                            <input type="radio" id="B1" name="billingAddress" value="BillingADDRESSSTORED" onchange="getRadioSelected(this)">
                            <span class=" checkmark"></span>
                        </label>
                    </div>
                    <div class="radioGroup">
                        <label class="container" id="labelB2" for="B2">Use a new Address
                            <input type="radio" id="B2" name="billingAddress" value="BillingNewAddress" onchange="getRadioSelected(this)">
                            <span class=" checkmark"></span>
                    </div>
                    <div class="allElementss">
                        <div class="allElements">
                            <div id="billingAddressForm">
                                <div class="cardContainer">
                                    <h3>Billing Address - Add new address</h3>
                                    <p>Title<span class="required">*</span></p>
                                    <select id="billingTitle" name="title" id="title">
                                        <option value="Mr">Mr</option>
                                        <option value="Mrs">Mrs</option>
                                        <option value="Miss">Miss</option>
                                        <option value="Ms">Ms</option>
                                        <option value="Dr">Dr</option>
                                    </select>
                                </div>

                                <div class="cardContainer">
                                    <p>First Name<span class="required">*</span></p>
                                    <input id='billingFirstName' type='text' class='searchInput' placeholder='First Name' pattern='^\D+$' minlength=2 maxlength=255>
                                </div>
                                <div class="cardContainer">
                                    <p>Last Name<span class="required">*</span></p>
                                    <input id='billingLastName' type='text' class='searchInput' placeholder='Last Name' pattern='^\D+$' minlength=2 maxlength=255>

                                </div>
                                <div class="cardContainer">
                                    <p>Address Line 1<span class="required">*</span></p>
                                    <input id='billingAddressLine1' type='text' class='searchInput' placeholder='Address Line 1' minlength=2 maxlength=255>
                                </div>
                                <div class="cardContainer">
                                    <p>Address Line 2</p>
                                    <input id='billingAddressLine2' type='text' class='searchInput' placeholder='Address Line 2' minlength=2 maxlength=255>
                                </div>
                                <div class="cardContainer">
                                    <p>Town/City<span class="required">*</span></p>
                                    <input id='billingTownCity' type='text' class='searchInput' placeholder='Town/City' minlength=2 maxlength=255>
                                </div>
                                <div class="cardContainer">
                                    <p>County<span class="required">*</span></p>
                                    <input id='billingCounty' type='text' class='searchInput' placeholder='County' minlength=2 maxlength=255>
                                </div>
                                <div class="cardContainer">
                                    <p>Post Code<span class="required">*</span></p>
                                    <input id='billingPostCode' type='text' class='searchInput' placeholder='Post Code' minlength=5 maxlength=8>
                                </div>
                                <!-- TODO Ashley: CHANGE TO FORM FOR EVERYTHING THAT WILL BE SUBMITTED -->
                                <!-- <input type="submit" value="Save"> -->
                            </div>
                        </div>
                    </div>



                    <hr>

                    <h1 class="billingAddress">Delivery Address</h1>
                    <div class="radioGroup">
                        <label class="container" id="labelD1" for="D1">Use Same As Billing Address
                            <input type="radio" id="D1" name="deliveryAddress" value="UseSameAsBillingAddress" onchange="getRadioSelected(this)">
                            <span class=" checkmark"></span>
                    </div>



                    <div class="radioGroup">
                        <label class="container" id="labelD2" for="D2">Main Address: <?php echo $mainAddressDisplay ?>
                            <input type="radio" id="D2" name="deliveryAddress" value="DeliveryADDRESSSTORED" onchange="getRadioSelected(this)">
                            <span class=" checkmark"></span>
                    </div>


                    <div class="radioGroup">
                        <label class="container" id="labelD3" for="D3">Use a new Address
                            <input type="radio" id="D3" name="deliveryAddress" value="DeliveryNewAddress" onchange="getRadioSelected(this)">
                            <span class=" checkmark"></span>
                    </div>
                    <div class="allElementss" id="AddressChange">
                        <div class="allElements">
                            <div id="deliveryAddressForm">
                                <div class="cardContainer">
                                    <h3>Delivery Address - Add new address</h3>
                                    <p>Title<span class="required">*</span></p>
                                    <select id="deliveryTitle" name="title" id="title">
                                        <option value="Mr">Mr</option>
                                        <option value="Mrs">Mrs</option>
                                        <option value="Miss">Miss</option>
                                        <option value="Ms">Ms</option>
                                        <option value="Dr">Dr</option>
                                    </select>
                                </div>

                                <div class="cardContainer">
                                    <p>First Name<span class="required">*</span></p>
                                    <input id='deliveryFirstName' type='text' class='searchInput' placeholder='First Name' pattern='^\D+$' minlength=2 maxlength=255>
                                </div>
                                <div class="cardContainer">
                                    <p>Last Name<span class="required">*</span></p>
                                    <input id='deliveryLastName' type='text' class='searchInput' placeholder='Last Name' pattern='^\D+$' minlength=2 maxlength=255>
                                </div>
                                <div class="cardContainer">
                                    <p>Address Line 1<span class="required">*</span></p>
                                    <input id='deliveryAddressLine1' type='text' class='searchInput' placeholder='Address Line 1' minlength=2 maxlength=255>
                                </div>
                                <div class="cardContainer">
                                    <p>Address Line 2</p>
                                    <input id='deliveryAddressLine2' type='text' class='searchInput' placeholder='Address Line 2' minlength=2 maxlength=255>
                                </div>
                                <div class="cardContainer">
                                    <p>Town/City<span class="required">*</span></p>
                                    <input id='deliveryTownCity' type='text' class='searchInput' placeholder='Town/City' minlength=2 maxlength=255>
                                </div>
                                <div class="cardContainer">
                                    <p>County<span class="required">*</span></p>
                                    <input id='deliveryCounty' type='text' class='searchInput' placeholder='County' minlength=2 maxlength=255>
                                </div>
                                <div class="cardContainer">
                                    <p>Post Code<span class="required">*</span></p>
                                    <input id='deliveryPostCode' type='text' class='searchInput' placeholder='Post Code' minlength=5 maxlength=8>
                                </div>
                                <!-- TODO Ashley: CHANGE TO FORM FOR EVERYTHING THAT WILL BE SUBMITTED -->
                                <!-- <input type="submit" value="Save"> -->
                            </div>
                        </div>
                    </div>
                    <!-- <input type="submit" value="Register"> -->
                    <hr>
                    <h1 class="deliveryAddress">Payment</h1>
                    <!-- 
            <div class="checkoutDiv">
                <div class="cardContainer rightPart">

                    <a href="">
                        <div class="card">
                            <div class="writingOfCard">
                                <img src="images/Checkout/PayPal button.png" alt="Girl in a jacket">
                                <h1>Checkout</h1>
                            </div>
                    </a>
                </div>
            </div> -->

                    <div class="button">
                        <div class="card">
                            <input type="image" src="images/Checkout/PayPal button.png" class="slider__img">
                        </div>
                    </div>
            </div>
            </form>

        </div>
        <p id="regMessage"></p>
    </div>



    <?php include "./footer.php" ?>
</body>
<style>
    #mainBody {
        margin-left: 50px;
        margin-right: 50px;
        margin-bottom: 50px;
    }

    .navToolButtons i {
        font-size: 5em;
        padding-bottom: 10px;
        color: #FFFFFF
    }


    #NoProducts {
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .title {
        display: inline-block;
        color: black;
        margin-top: 50px;
    }

    .allElements {
        max-width: 50%;
    }

    .title i {
        display: inline;
        font-size: 3em;
        color: #000;
        margin-right: 10px;
    }

    .total {
        width: 100%;
        float: left;
    }


    .billingAddress {
        margin-top: 20px;
    }

    hr {
        width: 70%;
        height: 2px;
        /* border-top: 5px solid #FFFFFF; */
        background-color: #FFFFFF;
        border-radius: 50px;
        opacity: 0.9;
        margin-bottom: 20px;
    }

    .title h1 {
        display: inline;
        margin-top: 50px;
        margin-bottom: 50px;
    }

    .radioGroup {
        display: block;
        margin-top: 20px;
        margin-bottom: 20px;
        margin-left: 10px;
        margin-right: 10px;
    }

    #deliveryAddressForm,
    #billingAddressForm {
        display: none;
    }

    .containerProductImage {
        width: 10%;
        display: inline-block;
    }

    .quantityOfProduct h1 {
        float: right;
    }

    .containerProductDetails {
        width: 75%;
        display: inline-block;
        float: right;
    }

    .slider__img {
        display: block;
        width: 100%;
    }

    .required {
        color: red;
        margin-left: 2px;
    }

    .cardContainer h3 {
        margin-bottom: 10px;
    }

    .totalContainer {
        display: flex;
        justify-content: center;
        align-items: center;



        position: -webkit-sticky;
        position: sticky;
        top: 0;
        z-index: 1;
    }

    .allElementss {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .allElementss {
        color: #000;
    }

    .totalHeader {
        float: left;
    }

    .totalAmount {
        float: right;
    }

    .productImage {
        box-shadow: 0 0 0 5px #FFFFFF;
        border-radius: 2%;
        height: 200px;
        width: 20%;
        float: left;
    }

    .checkoutDiv {
        margin-top: 50px;
        width: 100%;
        margin-left: auto;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .total h1 {
        display: inline;
        margin: 5px;
        /* float: right; */
    }

    .total {
        box-shadow: 0 0 0 5px #FFFFFF;
        border-radius: 2%;
        background-color: #FFFFFF;
        float: right;
        margin-top: 10px;
        margin-bottom: 10px;
    }

    .productDetails {
        box-shadow: 0 0 0 5px #FFFFFF;
        border-radius: 2%;
        height: 200px;
        background-color: #FFFFFF;
        /* float: right; */
        position: relative;
    }

    .titleOfProduct {
        display: inline-block;
        float: left;
        /* width: 80%; */

        word-wrap: break-word;
    }

    .quantityOfProduct {
        display: inline-block;
        float: right;
        /* width: 15%; */
    }

    /* .productImage {
            position: relative;
            overflow: hidden;
            box-shadow: 0 0 0 5px #FFFFFF;
            border-radius: 2%;

            height: 200px;
            width: 200px;
        } */

    .productImage img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }


    .individualProduct {
        margin-bottom: 50px;
        height: 200px;
    }

    .firstRow {
        padding-left: 2%;
        padding-right: 2%;
        padding-top: 2%;
        width: 97%;
    }

    .mainInfoOfProduct {
        border-radius: 12.5px;
        background-color: #FFFFFF;
        color: #000000;
        /*margin-top: 10px;*/
        word-break: break-all;
        display: inline-block;
        margin-left: 5px;
        margin-right: 5px;
        margin-top: 15px;
        margin-bottom: 15px;



        padding-left: 5px;
        padding-right: 5px;
        padding-top: 15px;
        padding-bottom: 15px;

        width: 90%;
        text-align: left;
        min-height: 200px;
        word-break: keep-all;
    }

    .RemoveProduct i,
    .RemoveProduct h4 {
        display: inline;
    }


    .RemoveProduct:hover {
        color: #1a1862;
        cursor: pointer;
        text-decoration: underline;
        text-decoration-color: #1a1862;
        text-decoration-style: double;
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
        background: url('images/Down%20Arrow.png') no-repeat right #FFFFFF;
        -webkit-appearance: none;
        background-position-x: 70px;

        border-style: solid;
        border-width: 2px;
        border-color: #000000;
    }


    .secondRow {
        float: right;
        position: absolute;
        bottom: 0;
        padding-left: 2%;
        padding-right: 2%;
        padding-top: 2%;
        width: 97%;
    }

    .RemoveProduct {
        bottom: 0;
        position: absolute
    }


    .button {
        display: inline-block;
        width: 200px;
        cursor: pointer;

        margin-left: auto;
        margin-right: auto;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    /* .card {
        box-shadow: 0 0 0 5px #FFFFFF;
        border-radius: 2%;
        height: 75px;
        overflow: hidden;
        position: relative;
        padding: 10px;
        background-color: #1a1862;
    } */

    .card .writingOfCard h1 {
        color: #FFFFFF;
        font-size: 25px;

        text-decoration: none;
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


































    /* The container */
    .container {
        display: block;
        position: relative;
        padding-left: 35px;
        margin-bottom: 12px;
        cursor: pointer;
        font-size: 22px;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
    }

    /* Hide the browser's default radio button */
    .container input {
        position: absolute;
        opacity: 0;
        cursor: pointer;
    }

    /* Create a custom radio button */
    .checkmark {
        position: absolute;
        top: 0;
        left: 0;
        height: 25px;
        width: 25px;
        border-style: solid;
        border-width: 2px;
        border-color: #FFFFFF;
        border-radius: 50%;
    }

    /* On mouse-over, add a grey background color */
    .container:hover input~.checkmark {
        background-color: #aaa;
    }

    /* When the radio button is checked, add a blue background */
    .container input:checked~.checkmark {
        background-color: #1a1862;
    }
























    /* FOR THE NEW BILLING/DELIVERY ADDRESS FORMS */
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

    input[type="text"] {
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
    function getRadioSelected(el) {
        if (el.id == "B1") {
            hideShowMainAddressForDeliveryAddress(el)
            ShowSection(false, true);


        } else if (el.id == "B2") {
            hideShowMainAddressForDeliveryAddress(el)
            ShowSection(true, true);

        } else if (el.id == "D1" || el.id == "D2") {
            ShowSection(false, false);

        } else if (el.id == "D3") {
            ShowSection(true, false);
        }


        showBillingDeliveryValidation();
    }


    //If user clicks on the add new address for either the billing or delivery then display the area
    function ShowSection(show, sectionIsBilling) {
        var sectionPart;

        if (sectionIsBilling) {
            sectionPart = "billing"
        } else {
            sectionPart = "delivery"
        }

        var section = sectionPart + "AddressForm";

        if (show) {
            $("#" + section).show(500);
            // turnSectionRequiredOnOff();
            turnSectionRequiredOnOff(sectionPart, true);


        } else {
            turnSectionRequiredOnOff(sectionPart, false);
            $("#" + section).hide(500);
        }
    }


    function turnSectionRequiredOnOff(section, required) {
        document.getElementById(section + "Title").required = required;
        document.getElementById(section + "FirstName").required = required;
        document.getElementById(section + "LastName").required = required;
        document.getElementById(section + "AddressLine1").required = required;
        document.getElementById(section + "TownCity").required = required;
        document.getElementById(section + "County").required = required;
        document.getElementById(section + "PostCode").required = required;
    }


    function hideShowMainAddressForDeliveryAddress(el) {
        if (el.id == "B1") {
            console.log("correct");
            document.getElementById("D2").style.display = "none";
            document.getElementById("D2").checked = false;
            document.getElementById("labelD2").style.display = "none";
        } else {
            document.getElementById("D2").style.display = "block";
            document.getElementById("labelD2").style.display = "block";
        }
        console.log(el.value);
    }

    //Update the message and remove the message if both are now selected (if the message has been shown before)
    function showBillingDeliveryValidation() {
        var billingAddressChecked = document.querySelector('input[name = "billingAddress"]:checked');
        var deliveryAddressChecked = document.querySelector('input[name = "deliveryAddress"]:checked');
        var outputMessage = ""
        if (billingAddressChecked && deliveryAddressChecked) {
            outputMessage = "";
            document.getElementById("regMessage").style.display = "none";
        }
    }


    function showHideMessage(show, message) {
        if (show) {
            document.getElementById("regMessage").innerHTML = message;
            document.getElementById("regMessage").style.display = "block";
        } else {
            document.getElementById("regMessage").style.display = "none";
        }
    }

    function payment() {
        event.preventDefault();
        //Validation to see if both are not selected
        var billingAddressChecked = document.querySelector('input[name = "billingAddress"]:checked');
        var deliveryAddressChecked = document.querySelector('input[name = "deliveryAddress"]:checked');
        var outputMessage = ""
        if (billingAddressChecked && deliveryAddressChecked) {
            outputMessage = "";
            document.getElementById("regMessage").style.display = "none";
            showHideMessage(false, null);
        } else {
            outputMessage = "Both billing and delivery address needs to be filled out. Please select a value or enter a new address";
            showHideMessage(true, outputMessage);
            return false;
        }



        var billingAddressStored = false;
        billingAddressStored = (billingAddressChecked.value == "BillingADDRESSSTORED")

        var billingFirstName;
        var billingLastName;
        var billingAddressLine1;
        var billingAddressLine2;
        var billingTownCity;
        var billingCounty;
        var billingPostCode;

        var billingMethod = 0;
        if (!billingAddressStored) {
            billingMethod = 2;
            billingFirstName = document.getElementById("billingFirstName").value;

            if (!billingFirstName) {
                outputMessage = "Billing Address: First Name cannot be blank, please fill out that field.";
                showHideMessage(true, outputMessage);
                return false;
            }
            billingLastName = document.getElementById("billingLastName").value;
            if (!billingLastName) {
                outputMessage = "Billing Address: Last Name cannot be blank, please fill out that field.";
                showHideMessage(true, outputMessage);
                return false;
            }
            billingAddressLine1 = document.getElementById("billingAddressLine1").value;
            if (!billingAddressLine1) {
                outputMessage = "Billing Address: Address Line 1 cannot be blank, please fill out that field.";
                showHideMessage(true, outputMessage);
                return false;
            }
            billingAddressLine2 = document.getElementById("billingAddressLine2").value;

            billingTownCity = document.getElementById("billingTownCity").value;
            if (!billingTownCity) {
                outputMessage = "Billing Address: Town/City cannot be blank, please fill out that field.";
                showHideMessage(true, outputMessage);
                return false;
            }
            billingCounty = document.getElementById("billingCounty").value;
            if (!billingCounty) {
                outputMessage = "Billing Address: County cannot be blank, please fill out that field.";
                showHideMessage(true, outputMessage);
                return false;
            }
            billingPostCode = document.getElementById("billingPostCode").value;
            if (!billingPostCode) {
                outputMessage = "Billing Address: Post Code cannot be blank, please fill out that field.";
                showHideMessage(true, outputMessage);
                return false;
            }
        } else {
            billingMethod = 1;
        }



        var deliveryAddressStored;

        var deliveryFirstName;
        var deliveryLastName;
        var deliveryAddressLine1;
        var deliveryAddressLine2;
        var deliveryTownCity;
        var deliveryCounty;
        var deliveryPostCode;

        var deliveryMethod = 0;

        if (deliveryAddressChecked) {
            if (deliveryAddressChecked.value == "deliveryADDRESSSTORED") {
                deliveryAddressStored = true;
                deliveryMethod = 2;
                // Incorrect values... shouldn't be able to select Main Address for both billing and delivery
            } else if (deliveryAddressChecked.value == "deliveryADDRESSSTORED" &&
                billingAddressChecked.value == "billingADDRESSSTORED") {
                outputMessage = "Invalid state - Please contact support for more details.";
                showHideMessage(true, outputMessage);
                return false;
                //If selected the delivery is the same as billing address
            } else if (deliveryAddressChecked.value == "UseSameAsBillingAddress") {
                //If the billing address is a new address (not main address)
                if (!billingAddressStored) {
                    deliveryFirstName = billingFirstName;
                    deliveryLastName = billingLastName;
                    deliveryAddressLine1 = billingAddressLine1;
                    deliveryAddressLine2 = billingAddressLine2;
                    deliveryTownCity = billingTownCity;
                    deliveryCounty = billingCounty;
                    deliveryPostCode = billingPostCode;
                    deliveryMethod = 1;
                } else {
                    //And if the billing address is the main address
                    deliveryAddressStored = true;
                    deliveryMethod = 2;
                }
                // If New Address for delivery
            } else if (deliveryAddressChecked.value == "DeliveryNewAddress") {
                deliveryMethod = 3;
                deliveryFirstName = document.getElementById("deliveryFirstName").value;

                if (!deliveryFirstName) {
                    outputMessage = "Delivery Address: First Name cannot be blank, please fill out that field.";
                    showHideMessage(true, outputMessage);
                    return false;
                }
                deliveryLastName = document.getElementById("deliveryLastName").value;
                if (!deliveryLastName) {
                    outputMessage = "Delivery Address: Last Name cannot be blank, please fill out that field.";
                    showHideMessage(true, outputMessage);
                    return false;
                }
                deliveryAddressLine1 = document.getElementById("deliveryAddressLine1").value;
                if (!deliveryAddressLine1) {
                    outputMessage = "Delivery Address: Address Line 1 cannot be blank, please fill out that field.";
                    showHideMessage(true, outputMessage);
                    return false;
                }
                deliveryAddressLine2 = document.getElementById("deliveryAddressLine2").value;

                deliveryTownCity = document.getElementById("deliveryTownCity").value;
                if (!deliveryTownCity) {
                    outputMessage = "Delivery Address: Town/City cannot be blank, please fill out that field.";
                    showHideMessage(true, outputMessage);
                    return false;
                }
                deliveryCounty = document.getElementById("deliveryCounty").value;
                if (!deliveryCounty) {
                    outputMessage = "Delivery Address: County cannot be blank, please fill out that field.";
                    showHideMessage(true, outputMessage);
                    return false;
                }
                deliveryPostCode = document.getElementById("deliveryPostCode").value;
                if (!deliveryPostCode) {
                    outputMessage = "Delivery Address: Post Code cannot be blank, please fill out that field.";
                    showHideMessage(true, outputMessage);
                    return false;
                }
            } else {
                //No other options - shouldn't get to this state.
                outputMessage = "Invalid state - Please contact support for more details.";
                showHideMessage(true, outputMessage);
                return false;
            }
        }

        // outputMessage = "Successful so far"
        showHideMessage(true, outputMessage);

        $.ajax({
            url: "placeOrder.php",
            type: "post",
            dataType: 'json',
            data: {
                "billingMethod": billingMethod,
                "deliveryMethod": deliveryMethod,

                // Send in billing details (even if null - won't be checked anyway)
                "billingFirstName": billingFirstName,
                "billingLastName": billingLastName,
                "billingAddressLine1": billingAddressLine1,
                "billingAddressLine2": billingAddressLine2,
                "billingTownCity": billingTownCity,
                "billingCounty": billingCounty,
                "billingPostCode": billingPostCode,


                // Send in delivery details (even if null - won't be checked anyway)
                "deliveryFirstName": deliveryFirstName,
                "deliveryLastName": deliveryLastName,
                "deliveryAddressLine1": deliveryAddressLine1,
                "deliveryAddressLine2": deliveryAddressLine2,
                "deliveryTownCity": deliveryTownCity,
                "deliveryCounty": deliveryCounty,
                "deliveryPostCode": deliveryPostCode
            },
            success: function(result) {
                document.getElementById("regMessage").style.display = "block";
                // document.getElementById('regMessage').innerHTML = xhr.status + " " + xhr.responseText.replaceAll('"', '');
                window.location.href = "order_complete.php";
            },
            error: function(data) {
                $("#regMessage").text(data.responseText);
            },
        });






    }
</script>

</html>