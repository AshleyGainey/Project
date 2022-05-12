<?php
// If the session hasn't started. Start it (can then use session variables)
if (!isset($_SESSION)) {
    @ob_start();
    @session_start();
}

// If the basket variable in the session hasn't been set (and if you try to come to this page - using the URL), redirect to the Basket page to say that there are no items in the basket
if (!isset($_SESSION['basket'])) {
    header('Location: basket.php');
}

// If the basket variable in the session is empty (no items in it) (and if you try to come to this page - using the URL), redirect to the Basket page to say that there are no items in the basket
if (empty($_SESSION['basket'])) {
    header('Location: basket.php');
}


// Users will need to be logged in to use this section, so if they are not logged in then redirect them to the Login page, 
//setting a flag where it brings them back to this page after they have logged in to true
if (!isset($_SESSION['userID'])) {
    $_SESSION['comeBackToCheckOut'] = true;
    header('Location: Login.php');
}


// Declearing some variables at the start. An array to hold the Prices of all the products in the basket, 
$productPriceArray = array();
// another array to hold the quantity of each product 
$productQuantityArray = array();
//and the last one is to hold a numeric value of the total price of all the products
$total = 0;
///For every product in the basket array, stored in the session, get the key (the product id) and the value 
// and put them in variables $item and $item_value. This for loop will calulate the total price
foreach ($_SESSION["basket"] as $item => $item_value) {
    //Include the Login Details to access the DB
    include 'DatabaseLoginDetails.php';

    //Connect to the database
    $conn = new mysqli($host, $user, $pass, $database);
    // Check connection and stop if there is an error
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    //Prepare the statement - query to select the product Price given a product ID
    $stmt = $conn->prepare("SELECT productPrice FROM product where productID = ?");
    //Pass in the ID
    $stmt->bind_param("i", $item);
    //And execute the query
    if (!$stmt->execute()) {
        //Couldn't execute query so stop there
        header('HTTP/1.1 500 Internal Server Error');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - Could not get the product price of the item you added to basket'));
    }
    //Get the result from the query and then store it in $basket_product
    $res = $stmt->get_result();
    $basket_product = mysqli_fetch_all($res, MYSQLI_ASSOC);

    // Multiply the products price to how many they ordered
    $productTotal = $basket_product[0]["productPrice"] * $item_value;
    // Add that calulation to the total variable that we declared on Line 14.
    $total = $total + $productTotal;
}
// Next, get the main address for the billing and delivery parts of the page.

//Include the Login Details to access the DB
include 'DatabaseLoginDetails.php';
//Connect to the database
$conn = mysqli_connect($host, $user, $pass, $database);

//Prepare the statement - query to select details of the user's address
$stmt = $conn->prepare("select a.title, a.firstName, a.lastName, a.addressID, a.addressLine1, a.addressLine2, a.townCity, a.county, a.postcode from user u INNER JOIN address a ON u.mainAddressID = a.addressID where u.userID  = ?");
// Send in the user's ID to get the main address details tied to the user
$stmt->bind_param("i", $_SESSION['userID']);

// Execute the query
if (!$stmt->execute()) {
    //Couldn't execute query so stop there
    header('HTTP/1.1 500 Internal Server Error');
    header('Content-Type: application/json; charset=UTF-8');
    die(json_encode('ERROR - Could not get your Main Address'));
}

//Get the result from the query and then store it in $mainaddressDB
$res = $stmt->get_result();
$mainaddressDB = mysqli_fetch_all($res, MYSQLI_ASSOC);
// From there, store each part of the address in their own variables
$mainAddressUserTitle = $mainaddressDB[0]['title'];
$mainAddressUserFirstName = $mainaddressDB[0]['firstName'];
$mainAddressUserLastName = $mainaddressDB[0]['lastName'];
$mainAddressUserAddressID = $mainaddressDB[0]['addressID'];

$mainAddressUserAddressLine1 = $mainaddressDB[0]['addressLine1'];
$mainAddressUserAddressLine2 = $mainaddressDB[0]['addressLine2'];
$mainAddressUserTownCity = $mainaddressDB[0]['townCity'];
$mainAddressUserCounty = $mainaddressDB[0]['county'];
$mainAddressUserPostCode = $mainaddressDB[0]['postcode'];
// Now prepare a string where it shows this information in a single element

// First Part, is their name (title, first and last name)
$strFirstPart = implode(' ', array($mainAddressUserTitle, $mainAddressUserFirstName, $mainAddressUserLastName));

// Second Part, is their address (check if address line is empty or null, if it is, don't include it (means there isn't two space when billing address is empty) with a comma seperating each of them values
if (empty($mainAddressUserAddressLine2)) {
    $strSecondPart = implode(', ', array($mainAddressUserAddressLine1, $mainAddressUserTownCity, $mainAddressUserCounty));
} else {
    $strSecondPart = implode(', ', array($mainAddressUserAddressLine1, $mainAddressUserAddressLine2, $mainAddressUserTownCity, $mainAddressUserCounty));
}
// Part them parts together with a comma between and after them and then at the end, put their postcode
$mainAddressDisplay = $strFirstPart . ", " . $strSecondPart . ". " . $mainAddressUserPostCode;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Shows what the title of the tab is-->
    <title>Checkout - Gadget Gainey Store</title>
    <!-- Character set for the page -->
    <meta charset="UTF-8">
    <!-- What type is this page? HTML-->
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
    <meta name="description" content="Checkout your Gadget Gainey products!">
    <!-- Don't let any search engine index this page -->
    <meta name="robots" content="noindex" />
    <!-- Link to the shared classes and ID style sheet -->
    <link rel="stylesheet" type="text/css" href="sharedStyles.css">
</head>

<body>
    <!-- Add the header at the top before any other material -->
    <?php include "./header.php" ?>
    <div id="bodyOfPage">
        <div class="title">
            <!-- Title of Page with an icon-->
            <i class="fa fa-shopping-cart"></i>
            <h1>Checkout</h1>
        </div>
        <!-- Total of the basket is at the top and is a sticky -->
        <div class="totalContainer">
            <div class="total">
                <h1 class="totalHeader">Total:</h1>
                <?php
                // Put the total variable into the header.
                echo "<h1 class='totalAmount'>£" . number_format($total, 2) . "<h1>";
                ?>
            </div>
        </div>
        <div class="centreElements">
            <div class="allElements">
                <!-- Form to send the data off correctly  -->
                <form action="" id="OrderForm" method="post" onsubmit="payment()">
                    <!-- Billing Address section -->
                    <h1 class="addressHeading">Billing Address</h1>
                    <!-- Options to select what the user wants (Main Address: B1 | New Address: B2)  -->
                    <div class="radioGroup">
                        <!-- For B1, Output the main Address in the display variable we made earlier -->
                        <label class="radioContainer" id="labelB1" for="B1">Main Address: <?php echo $mainAddressDisplay ?>
                            <input type="radio" id="B1" name="billingAddress" value="BillingADDRESSSTORED" onchange="getRadioSelected(this)">
                            <span class="tick"></span>
                        </label>
                    </div>
                    <div class="radioGroup">
                        <!-- For B2, Output "Use a new address"-->
                        <label class="radioContainer" id="labelB2" for="B2">Use a new Address
                            <input type="radio" id="B2" name="billingAddress" value="BillingNewAddress" onchange="getRadioSelected(this)">
                            <span class="tick"></span>
                    </div>
                    <div class="centreElements">
                        <div class="allElements">
                            <!-- If selected, use a new address for billing, then show the Billing Address form with the fields for adding a new address -->
                            <div id="billingAddressForm">
                                <div class="fieldContainer">
                                    <h3>Billing Address - Add new address</h3>
                                    <!-- Description of field and required span -->
                                    <p>Title<span class="required">*</span></p>
                                    <!-- Dropdown of the title of the billing person - Either Mr, Master, Mrs, Miss, Ms, Dr -->
                                    <select id="billingTitle" name="title" id="title">
                                        <option value="Mr">Mr</option>
                                        <option value="Master">Master</option>
                                        <option value="Mrs">Mrs</option>
                                        <option value="Miss">Miss</option>
                                        <option value="Ms">Ms</option>
                                        <option value="Dr">Dr</option>
                                    </select>
                                </div>

                                <div class="fieldContainer">
                                    <!-- Description of field and required span -->
                                    <p>First Name<span class="required">*</span></p>
                                    <!-- Create an text input for billing first name -->
                                    <input id='billingFirstName' type='text' class='changeOrAddDetailsInput' placeholder='First Name' pattern='^\D+$' minlength=2 maxlength=255>
                                </div>
                                <div class="fieldContainer">
                                    <!-- Description of field and required span -->
                                    <p>Last Name<span class="required">*</span></p>
                                    <!-- Create an text input for billing last name -->
                                    <input id='billingLastName' type='text' class='changeOrAddDetailsInput' placeholder='Last Name' pattern='^\D+$' minlength=2 maxlength=255>
                                </div>
                                <div class="fieldContainer">
                                    <!-- Description of field and required span -->
                                    <p>Address Line 1<span class="required">*</span></p>
                                    <!-- Create an text input for billing address line 1 -->
                                    <input id='billingAddressLine1' type='text' class='changeOrAddDetailsInput' placeholder='Address Line 1' minlength=2 maxlength=255>
                                </div>
                                <div class="fieldContainer">
                                    <!-- Description of field -->
                                    <p>Address Line 2</p>
                                    <!-- Create an text input for billing address line 2 -->
                                    <input id='billingAddressLine2' type='text' class='changeOrAddDetailsInput' placeholder='Address Line 2' maxlength=255>
                                </div>
                                <div class="fieldContainer">
                                    <!-- Description of field and required span -->
                                    <p>Town/City<span class="required">*</span></p>
                                    <!-- Create an text input for billing Town or city -->
                                    <input id='billingTownCity' type='text' class='changeOrAddDetailsInput' placeholder='Town/City' minlength=2 maxlength=58>
                                </div>
                                <div class="fieldContainer">
                                    <!-- Description of field and required span -->
                                    <p>County<span class="required">*</span></p>
                                    <!-- Create an text input for billing county -->
                                    <input id='billingCounty' type='text' class='changeOrAddDetailsInput' placeholder='County' minlength=2 maxlength=255>
                                </div>
                                <div class="fieldContainer">
                                    <!-- Description of field and required span -->
                                    <p>Post Code<span class="required">*</span></p>
                                    <!-- Create an text input for billing postcode -->
                                    <input id='billingPostCode' type='text' class='changeOrAddDetailsInput' placeholder='Post Code' minlength=5 maxlength=8>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Line to separate billing address from delivery address -->
                    <hr>
                    <!-- Delivery Address section -->
                    <h1 class="addressHeading">Delivery Address</h1>
                    <!-- Options to select what the user wants (Same as Billing Address: D1 | Main Address: D2 | New Address: D3 )  -->
                    <div class="radioGroup">
                        <!-- For D1, Output a radio input of "Use same as Billing Address"-->
                        <label class="radioContainer" id="labelD1" for="D1">Use Same As Billing Address
                            <input type="radio" id="D1" name="deliveryAddress" value="UseSameAsBillingAddress" onchange="getRadioSelected(this)">
                            <span class="tick"></span>
                    </div>
                    <div class="radioGroup">
                        <!-- For D2, Output the main Address in the display variable we made earlier-->
                        <label class="radioContainer" id="labelD2" for="D2">Main Address: <?php echo $mainAddressDisplay ?>
                            <input type="radio" id="D2" name="deliveryAddress" value="DeliveryADDRESSSTORED" onchange="getRadioSelected(this)">
                            <span class="tick"></span>
                    </div>
                    <div class="radioGroup">
                        <!-- For D3, Output "Use a new address"-->
                        <label class="radioContainer" id="labelD3" for="D3">Use a new Address
                            <input type="radio" id="D3" name="deliveryAddress" value="DeliveryNewAddress" onchange="getRadioSelected(this)">
                            <span class="tick"></span>
                    </div>
                    <div class="centreElements">
                        <div class="allElements">
                            <!-- If selected, use a new address for delivery, then show the Delivery Address form with the fields for adding a new address -->
                            <div id="deliveryAddressForm">
                                <div class="fieldContainer">
                                    <h3>Delivery Address - Add new address</h3>
                                    <!-- Description of field and required span -->
                                    <p>Title<span class="required">*</span></p>
                                    <!-- Dropdown of the title of the billing person - Either Mr, Master, Mrs, Miss, Ms, Dr -->
                                    <select id="deliveryTitle" name="title" id="title">
                                        <option value="Mr">Mr</option>
                                        <option value="Master">Master</option>
                                        <option value="Mrs">Mrs</option>
                                        <option value="Miss">Miss</option>
                                        <option value="Ms">Ms</option>
                                        <option value="Dr">Dr</option>
                                    </select>
                                </div>

                                <div class="fieldContainer">
                                    <!-- Description of field and required span -->
                                    <p>First Name<span class="required">*</span></p>
                                    <!-- Create an text input for delivery first name -->
                                    <input id='deliveryFirstName' type='text' class='changeOrAddDetailsInput' placeholder='First Name' pattern='^\D+$' minlength=2 maxlength=255>
                                </div>
                                <div class="fieldContainer">
                                    <!-- Description of field and required span -->
                                    <p>Last Name<span class="required">*</span></p>
                                    <!-- Create an text input for delivery last name -->
                                    <input id='deliveryLastName' type='text' class='changeOrAddDetailsInput' placeholder='Last Name' pattern='^\D+$' minlength=2 maxlength=255>
                                </div>
                                <div class="fieldContainer">
                                    <!-- Description of field and required span -->
                                    <p>Address Line 1<span class="required">*</span></p>
                                    <!-- Create an text input for delivery address line 1 -->
                                    <input id='deliveryAddressLine1' type='text' class='changeOrAddDetailsInput' placeholder='Address Line 1' minlength=2 maxlength=255>
                                </div>
                                <div class="fieldContainer">
                                    <!-- Description of field -->
                                    <p>Address Line 2</p>
                                    <!-- Create an text input for delivery address line 2 -->
                                    <input id='deliveryAddressLine2' type='text' class='changeOrAddDetailsInput' placeholder='Address Line 2' minlength=2 maxlength=255>
                                </div>
                                <div class="fieldContainer">
                                    <!-- Description of field and required span -->
                                    <p>Town/City<span class="required">*</span></p>
                                    <!-- Create an text input for delivery Town or city -->
                                    <input id='deliveryTownCity' type='text' class='changeOrAddDetailsInput' placeholder='Town/City' minlength=2 maxlength=58>
                                </div>
                                <div class="fieldContainer">
                                    <!-- Description of field and required span -->
                                    <p>County<span class="required">*</span></p>
                                    <!-- Create an text input for delivery county -->
                                    <input id='deliveryCounty' type='text' class='changeOrAddDetailsInput' placeholder='County' minlength=2 maxlength=255>
                                </div>
                                <div class="fieldContainer">
                                    <!-- Description of field and required span -->
                                    <p>Post Code<span class="required">*</span></p>
                                    <!-- Create an text input for delivery postcode -->
                                    <input id='deliveryPostCode' type='text' class='changeOrAddDetailsInput' placeholder='Post Code' minlength=5 maxlength=8>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Separate the Delivery Address from the Payment -->
                    <hr>
                    <h1 class="deliveryAddress">Payment</h1>
                    <!-- Show a button for PayPal Payment -->
                    <div class="button">
                        <div class="card">
                            <input type="image" src="images/Checkout/PayPal button.png" class="paymentImage">
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- Make text that will display any errors if there are any. -->
        <p id="errorMessage"></p>
    </div>
    <!-- Add the footer at the bottom after any other material -->
    <?php include "./footer.php" ?>
</body>
<style>
    /* All the elements in this div, set them to max width of 50% */
    .allElements {
        max-width: 50%;
    }

    .title {
        display: inline-block;
        color: black;
    }

    .title h1 {
        display: inline;
    }

    .title i {
        display: inline;
        font-size: 3em;
        color: #000;
        margin-right: 10px;
    }

    .addressHeading {
        margin-top: 20px;
    }

    hr {
        width: 70%;
        height: 2px;
        background-color: #FFFFFF;
        border-radius: 50px;
        opacity: 0.9;
        margin-bottom: 20px;
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

    .paymentImage {
        display: block;
        width: 100%;
    }

    /* Add space between the title of the New Address and the other fields */
    .fieldContainer h3 {
        margin-bottom: 10px;
    }

    /* Centre the Total Container, as well as make it sticky (it sticks to the top while you scroll) */
    .totalContainer {
        display: flex;
        justify-content: center;
        align-items: center;

        position: -webkit-sticky;
        position: sticky;
        top: 0;
        z-index: 1;
    }

    .totalHeader {
        float: left;
    }

    .totalAmount {
        float: right;
    }

    .total {
        box-shadow: 0 0 0 5px #FFFFFF;
        border-radius: 2%;
        background-color: #FFFFFF;
        float: right;
        margin-top: 10px;
        margin-bottom: 10px;
    }

    .total h1 {
        display: inline;
        margin: 5px;
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

    .radioContainer {
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

    /* Hide the default radio button */
    .radioContainer input {
        position: absolute;
        opacity: 0;
        cursor: pointer;
    }

    /* Create a new, custom radio button */
    .tick {
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
    .radioContainer:hover input~.tick {
        background-color: #aaa;
    }

    /* When the radio button is checked, add a blue background */
    .radioContainer input:checked~.tick {
        background-color: #1a1862;
    }

    .centreElements {
        display: flex;
        align-items: center;
        justify-content: center;
    }


    .fieldContainer {
        margin-top: 20px;
        margin-bottom: 20px;
        /* width: 20%; */
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

    select:hover {
        border-color: #1a1862;
        cursor: pointer;
        border-width: 3px;
    }
</style>
<script>
    // See which Radio button has been selected
    function getRadioSelected(element) {
        // If Main Address for Billing
        if (element.id == "B1") {
            hideShowMainAddressForDeliveryAddress(element)
            // Hide the AddressForm for Billing
            ShowSection(false, true);
            // If New Address for Billing
        } else if (element.id == "B2") {
            hideShowMainAddressForDeliveryAddress(element)
            // Show the AddressForm for Billing
            ShowSection(true, true);
            // If Same As Billing Address or Main Address for Delivery
        } else if (element.id == "D1" || element.id == "D2") {
            // Hide the AddressForm for Delivery
            ShowSection(false, false);
            // If Add new Address for Delivery
        } else if (element.id == "D3") {
            // Hide the AddressForm for Delivery
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
            var element = document.getElementById(section);
            element.style.display = 'inline-block'
            turnSectionRequiredOnOff(sectionPart, true);
        } else {
            turnSectionRequiredOnOff(sectionPart, false);
            var element = document.getElementById(section);
            element.style.display = 'none'
        }
    }


    // Switch the section's input fields required part on and off depending on whether they have selected/deselected the 'Add New address' radio button
    function turnSectionRequiredOnOff(section, required) {
        document.getElementById(section + "Title").required = required;
        document.getElementById(section + "FirstName").required = required;
        document.getElementById(section + "LastName").required = required;
        document.getElementById(section + "AddressLine1").required = required;
        document.getElementById(section + "TownCity").required = required;
        document.getElementById(section + "County").required = required;
        document.getElementById(section + "PostCode").required = required;
    }


    function hideShowMainAddressForDeliveryAddress(element) {
        // Will hide Main Address radio element (and instead the user will have to press 'Same as Billing address' or 'Add new address), if anything else, then display the Main Address for Delivery
        if (element.id == "B1") {
            document.getElementById("D2").style.display = "none";
            document.getElementById("D2").checked = false;
            document.getElementById("labelD2").style.display = "none";
        } else {
            document.getElementById("D2").style.display = "block";
            document.getElementById("labelD2").style.display = "block";
        }
    }

    //Update the message and remove the message if both, billing address and delivery address have been selected (if the message has been shown before)
    function showBillingDeliveryValidation() {
        var billingAddressChecked = document.querySelector('input[name = "billingAddress"]:checked');
        var deliveryAddressChecked = document.querySelector('input[name = "deliveryAddress"]:checked');
        var outputMessage = ""
        if (billingAddressChecked && deliveryAddressChecked) {
            outputMessage = "";
            document.getElementById("errorMessage").style.display = "none";
        }
    }

    // Show or Hide the Error Message
    function showHideMessage(show, message) {
        if (show) {
            document.getElementById("errorMessage").innerHTML = message;
            document.getElementById("errorMessage").style.display = "block";
        } else {
            document.getElementById("errorMessage").style.display = "none";
        }
    }

    // Form has been submitted so now do the following
    function payment() {
        // Prevent the form being sent by the default action
        event.preventDefault();

        //Validation to see if both are not selected
        var billingAddressChecked = document.querySelector('input[name = "billingAddress"]:checked');
        var deliveryAddressChecked = document.querySelector('input[name = "deliveryAddress"]:checked');
        var outputMessage = ""
        if (billingAddressChecked && deliveryAddressChecked) {
            outputMessage = "";
            document.getElementById("errorMessage").style.display = "none";
            showHideMessage(false, null);
        } else {
            outputMessage = "Both billing and delivery address needs to be filled out. Please select a value or enter a new address";
            showHideMessage(true, outputMessage);
            return false;
        }


        // Check to see if the user has pressed 'Main Address'
        var billingAddressStored = false;
        billingAddressStored = (billingAddressChecked.value == "BillingADDRESSSTORED")

        // Declare variables for the Billing details
        var billingTitle;
        var billingFirstName;
        var billingLastName;
        var billingAddressLine1;
        var billingAddressLine2;
        var billingTownCity;
        var billingCounty;
        var billingPostCode;

        var billingMethod = 0;
        // If not, Main Address, it is therefore Billing Method 2 (new address)
        if (!billingAddressStored) {
            billingMethod = 2;

            // Some client side validation, check if billing Title is either Master, Mr, Mrs, Ms, Miss or Dr. If not, then it is in an incorrect state and show error
            billingTitle = document.getElementById("billingTitle").value;

            if (billingTitle != "Master" && billingTitle != "Mr" &&
                billingTitle != "Mrs" && billingTitle != "Ms" && billingTitle != "Miss" &&
                billingTitle != "Dr") {
                outputMessage = "Billing Address: Not a valuid Name Title. Please fill the section in correctly";
                showHideMessage(true, outputMessage);
                return false;
            }
            // Some client side validation, check if billing First Name is blank, if it is show error
            billingFirstName = document.getElementById("billingFirstName").value;

            if (!billingFirstName) {
                outputMessage = "Billing Address: First Name cannot be blank, please fill out that field.";
                showHideMessage(true, outputMessage);
                return false;
            }
            // Some client side validation, check if billing Last Name is blank, if it is show error
            billingLastName = document.getElementById("billingLastName").value;
            if (!billingLastName) {
                outputMessage = "Billing Address: Last Name cannot be blank, please fill out that field.";
                showHideMessage(true, outputMessage);
                return false;
            }
            // Some client side validation, check if billing Address Line 1 is blank, if it is show error
            billingAddressLine1 = document.getElementById("billingAddressLine1").value;
            if (!billingAddressLine1) {
                outputMessage = "Billing Address: Address Line 1 cannot be blank, please fill out that field.";
                showHideMessage(true, outputMessage);
                return false;
            }
            billingAddressLine2 = document.getElementById("billingAddressLine2").value;
            // Some client side validation, check if billing Town/City is blank, if it is show error
            billingTownCity = document.getElementById("billingTownCity").value;
            if (!billingTownCity) {
                outputMessage = "Billing Address: Town/City cannot be blank, please fill out that field.";
                showHideMessage(true, outputMessage);
                return false;
            }
            // Some client side validation, check if billing County is blank, if it is show error
            billingCounty = document.getElementById("billingCounty").value;
            if (!billingCounty) {
                outputMessage = "Billing Address: County cannot be blank, please fill out that field.";
                showHideMessage(true, outputMessage);
                return false;
            }
            // Some client side validation, check if billing Postcode is blank, if it is show error
            billingPostCode = document.getElementById("billingPostCode").value;
            if (!billingPostCode) {
                outputMessage = "Billing Address: Post Code cannot be blank, please fill out that field.";
                showHideMessage(true, outputMessage);
                return false;
            }
        } else {
            // If billing method is main address, then say BillingMethod is 1
            billingMethod = 1;
        }


        // Declare variables to store the values of the delivery details
        var deliveryTitle;
        var deliveryFirstName;
        var deliveryLastName;
        var deliveryAddressLine1;
        var deliveryAddressLine2;
        var deliveryTownCity;
        var deliveryCounty;
        var deliveryPostCode;

        var deliveryMethod = 0;
        // If Delivery Address and the billing address is Main Address (it means it is in an incorrect state - Delivery address gets removed from view if Billing Address is Main Address)
        if (deliveryAddressChecked.value == "deliveryADDRESSSTORED" &&
            billingAddressChecked.value == "billingADDRESSSTORED") {
            outputMessage = "Invalid state - Please contact support for more details.";
            showHideMessage(true, outputMessage);
            return false;
            //If selected the delivery is the same as billing address
        } else if (deliveryAddressChecked.value == "deliveryADDRESSSTORED") {
            deliveryMethod = 2;
            // Incorrect values... shouldn't be able to select Main Address for both billing and delivery
        } else if (deliveryAddressChecked.value == "UseSameAsBillingAddress") {
            //If the billing address is a new address (not main address) so delivery details is equal to the billing details
            if (!billingAddressStored) {
                deliveryTitle = billingTitle;
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
                deliveryMethod = 2;
            }
            // If New Address for delivery
        } else if (deliveryAddressChecked.value == "DeliveryNewAddress") {
            deliveryMethod = 3;

            // Some client side validation, check if delivery Title is either Master, Mr, Mrs, Ms, Miss or Dr. If not, then it is in an incorrect state and show error
            deliveryTitle = document.getElementById("deliveryTitle").value;
            if (deliveryTitle != "Master" && deliveryTitle != "Mr" &&
                deliveryTitle != "Mrs" && deliveryTitle != "Ms" && deliveryTitle != "Miss" &&
                deliveryTitle != "Dr") {
                outputMessage = "Delivery Address: Not a valid Name Title. Please fill the section in correctly";
                showHideMessage(true, outputMessage);
                return false;
            }

            // Some client side validation, check if delivery First Name is blank, if it is show error
            deliveryFirstName = document.getElementById("deliveryFirstName").value;
            if (!deliveryFirstName) {
                outputMessage = "Delivery Address: First Name cannot be blank, please fill out that field.";
                showHideMessage(true, outputMessage);
                return false;
            }
            // Some client side validation, check if delivery Last Name is blank, if it is show error
            deliveryLastName = document.getElementById("deliveryLastName").value;
            if (!deliveryLastName) {
                outputMessage = "Delivery Address: Last Name cannot be blank, please fill out that field.";
                showHideMessage(true, outputMessage);
                return false;
            }
            // Some client side validation, check if delivery Address Line 1 is blank, if it is show error
            deliveryAddressLine1 = document.getElementById("deliveryAddressLine1").value;
            if (!deliveryAddressLine1) {
                outputMessage = "Delivery Address: Address Line 1 cannot be blank, please fill out that field.";
                showHideMessage(true, outputMessage);
                return false;
            }
            deliveryAddressLine2 = document.getElementById("deliveryAddressLine2").value;

            // Some client side validation, check if delivery Town/City is blank, if it is show error
            deliveryTownCity = document.getElementById("deliveryTownCity").value;
            if (!deliveryTownCity) {
                outputMessage = "Delivery Address: Town/City cannot be blank, please fill out that field.";
                showHideMessage(true, outputMessage);
                return false;
            }
            // Some client side validation, check if delivery County is blank, if it is show error
            deliveryCounty = document.getElementById("deliveryCounty").value;
            if (!deliveryCounty) {
                outputMessage = "Delivery Address: County cannot be blank, please fill out that field.";
                showHideMessage(true, outputMessage);
                return false;
            }
            // Some client side validation, check if delivery Postcode is blank, if it is show error
            deliveryPostCode = document.getElementById("deliveryPostCode").value;
            if (!deliveryPostCode) {
                outputMessage = "Delivery Address: Post Code cannot be blank, please fill out that field.";
                showHideMessage(true, outputMessage);
                return false;
            }
        } else {
            //No other options - shouldn't get to this state.
            outputMessage = "Invalid state Delivery - Please contact support for more details.";
            showHideMessage(true, outputMessage);
            return false;
        }
        // Hide the message as no error has happened so for
        showHideMessage(true, outputMessage);

        // If passed all checks, post the results to the backend for further validation and if passed them, then add to the database
        let xhr = new XMLHttpRequest();
        xhr.open('POST', "placeOrder.php", true)
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.send("billingMethod=" + billingMethod +
            "&deliveryMethod=" + deliveryMethod +

            "&billingTitle=" + billingTitle +
            "&billingFirstName=" + billingLastName +
            "&billingLastName=" + billingAddressLine1 +
            "&billingAddressLine1=" + billingAddressLine2 +
            "&billingAddressLine2=" + billingTownCity +
            "&billingTownCity=" + billingCounty +
            "&billingPostCode=" + billingPostCode +


            "&deliveryTitle=" + deliveryTitle +
            "&deliveryFirstName=" + deliveryLastName +
            "&deliveryLastName=" + deliveryAddressLine1 +
            "&deliveryAddressLine1=" + deliveryAddressLine2 +
            "&deliveryAddressLine2=" + deliveryTownCity +
            "&deliveryTownCity=" + deliveryCounty +
            "&deliveryPostCode=" + deliveryPostCode
        );


        // On return of the call
        xhr.onreadystatechange = function() {
            //See if it is ready and the status is OK
            if (xhr.readyState == 4 && xhr.status == 200) {
                // If passed and successful, take the user to order_complete page
                window.location.href = "order_complete.php";
            } else if (xhr.readyState == 4 && (xhr.status == 400 || xhr.status == 500)) {
                // If not, show the error message in the errorMessage element with what it got back from the PHP file
                document.getElementById("errorMessage").style.display = "block";
                document.getElementById('errorMessage').innerHTML = xhr.status + " " + JSON.parse(xhr.responseText);
            }
        }
    }
</script>

</html>