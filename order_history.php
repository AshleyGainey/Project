<?php
// If the session hasn't started. Start it (can then use session variables)
if (!isset($_SESSION)) {
    @ob_start();
    @session_start();
}

//If you try to come to this page (using the URL or by navigating to it) and the User ID has not been set, it means that the user hasn't been logged in
// so therefore, redirect them to the Login Page
if (!isset($_SESSION['userID'])) {
    header('Location: Login.php');
}

//Get the Database Details
include 'DatabaseLoginDetails.php';

//Make a connect to the database
$conn = mysqli_connect($host, $user, $pass, $database);

// Error with the connection of the database. So don't execute further
if (!$conn) {
    header('HTTP/1.1 500 Internal Server Error');
    header('Content-Type: application/json; charset=UTF-8');
    die(json_encode('ERROR - Connection to the database has not been established'));
} else  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

//Prepared Statement - query to get all order details tied to the user (latest first). 
// For the page:
// - Get each order - Get information of the:
// --- Billing Address
// --- Delivery Address
// --- Date and Time it was ordered
// --- Total Price of order
$stmt = $conn->prepare(
    "SELECT po.orderID, po.totalPrice, po.DateAndTime,

ba.title As billingTitle, ba.firstName As billingFirstName, ba.lastName As billingLastName, ba.addressLine1 As billingAddressLine1, ba.addressLine2 As billingAddressLine2, ba.townCity As billingtownCity, ba.county As billingCounty, ba.postCode As billingPostCode,
da.title As deliveryTitle, da.firstName As deliveryFirstName, da.lastName As deliveryLastName, da.addressLine1 As deliveryAddressLine1, da.addressLine2 As deliveryAddressLine2, da.townCity As deliverytownCity, da.county As deliveryCounty, da.postCode As deliveryPostCode


FROM purchase_order po

INNER JOIN address AS ba
ON po.billingAddressID = ba.addressID


INNER JOIN address AS da
ON po.deliveryAddressID = da.addressID



where po.UserID = ? ORDER BY po.DateAndTime DESC;"
);
//Binding the User ID with the prepared statement
$stmt->bind_param("i", $_SESSION['userID']);

//Execute the query
if (!$stmt->execute()) {
    //Error trying to execute seeing if the user is already in the DB and send back a 500 Internal Server Error HTTP response.
    $stmt->close();
    header('HTTP/1.1 500 Internal Server Error');
    header('Content-Type: application/json; charset=UTF-8');
    die(json_encode('ERROR - Could not get your order history from the Database'));
}
//Get results from the database and put them in a variable
$res = $stmt->get_result();
$allOrdersTiedToAccount = mysqli_fetch_all($res, MYSQLI_ASSOC);
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
    <meta name="description" content="View Order History, purchased on Gadget Gainey!">
    <!-- Shows what the title of the tab is-->
    <title>Order History - Gadget Gainey Store</title>
    <!-- Link to the shared classes and IDs style sheet -->
    <link rel="stylesheet" type="text/css" href="sharedStyles.css">
</head>

<body>
    <!-- Add the header at the top before any other material -->
    <?php include "./header.php" ?>
    <div id="bodyOfPage">
        <div class="title">
            <!-- Title of Page with Icon -->
            <i class="fa fa-file-text"></i>
            <h1>Order History</h1>
        </div>
        <?php
        // If there are no Orders tied to the User then show a No Order Message
        if (empty($allOrdersTiedToAccount)) {
            echo "<div class='NoOrders'>
            <h1>No Order have been placed with this account</h1>
            </div>";
        } else {
            // Else if there are orders, loop through each order
            foreach ($allOrdersTiedToAccount as $order) {
                //Get information about the order. Including the OrderID, 
                // Total Price and the date of time that it was placed
                $orderID = $order['orderID'];
                $totalPrice = $order['totalPrice'];
                $DateAndTime = $order['DateAndTime'];

                // Then get the billing information of the user
                $billingTitle = $order['billingTitle'];
                $billingFirstName = $order['billingFirstName'];
                $billingLastName = $order['billingLastName'];
                $billingAddressLine1 = $order['billingAddressLine1'];
                $billingAddressLine2 = $order['billingAddressLine2'];
                $billingTownCity = $order['billingtownCity'];
                $billingCounty = $order['billingCounty'];
                $billingPostCode = $order['billingPostCode'];

                // Then get the delivery information of the user
                $deliveryTitle = $order['deliveryTitle'];
                $deliveryFirstName = $order['deliveryFirstName'];
                $deliveryLastName = $order['deliveryLastName'];
                $deliveryAddressLine1 = $order['deliveryAddressLine1'];
                $deliveryAddressLine2 = $order['deliveryAddressLine2'];
                $deliveryTownCity = $order['deliverytownCity'];
                $deliveryCounty = $order['deliveryCounty'];
                $deliveryPostCode = $order['deliveryPostCode'];

                // Change the Date and Time into a Date object so we can manipulate it
                $date = date_create($DateAndTime);

                // Create a Div holding the information of the order. And then an outer Of the Each Order that holds the order date and time (that has been formatted)
                echo "<div class='eachOrder'>
            <div class='eachOrderOuter'>
                <div class='dateOfOrderDiv'>
                    <h2>Order Placed: " .
                    date_format($date, "l dS F Y") . " at " . date_format($date, "H:i") .
                    "</h1>
                </div>";
                // Then inside the inner div, we can then hold the order number (padded to 5 digits) and the total price.
                echo "
                <div class='eachOrderInner'>
                    <div class='rowOne'>
                        <div class='leftSection'>
                            <h2>Order Number: " . sprintf('%05d', $orderID) .
                    "</h1>
                        </div>
                        <div class='rightSection'>
                            <h2>Total Price: £" . number_format($totalPrice, 2) .
                    "</h1>
                        </div>
                    </div>
                    <div class='rowTwo'>
                    ";

                //Prepared Statement - query to get the product information for each product on that order.
                // Get each product from the order
                // --- Each product that was on the order - Get information of the:
                // ----- Product Title
                // ----- Product Image and Alternative Text for Image
                // ----- Product Quantity Bought
                // ----- Product Price at the time of purchase
                $stmt = $conn->prepare("select p.productID, op.productPriceAtTime, op.productQuantity, p.productTitle, pi.productImageFileName, pi.productImageAltText from order_product op
                    INNER JOIN product p ON op.productID = p.productID
                    INNER JOIN product_image pi ON p.productID = pi.productID
                        where pi.displayOrder = 1 and op.orderID = ?");
                //Binding the current Order with the prepared statement
                $stmt->bind_param("i", $orderID);
                //Execute the query
                if (!$stmt->execute()) {
                    //Error trying to execute find the products that are tied to the order and send back a 500 Internal Server Error HTTP response.
                    $stmt->close();
                    header('HTTP/1.1 500 Internal Server Error');
                    header('Content-Type: application/json; charset=UTF-8');
                    die(json_encode('ERROR - Could not register you for an account - Failed at checking if user exists in DB'));
                }

                // If success in executing the query, get the resuls and store it in the productPurchased variable
                $res = $stmt->get_result();
                $productsPurchased = mysqli_fetch_all(
                    $res,
                    MYSQLI_ASSOC
                );
                // For each product on the order, then put the information in their own variables
                foreach ($productsPurchased as $individualProduct) {
                    $productID = $individualProduct['productID'];
                    $productPriceAtTime = $individualProduct['productPriceAtTime'];
                    $productQuantity = $individualProduct['productQuantity'];
                    $productTitle = $individualProduct['productTitle'];
                    $productImageFileName = $individualProduct['productImageFileName'];
                    $productImageAltText = $individualProduct['productImageAltText'];

                    // Have a div holding each product in it
                    echo  "<div class='eachProduct'>";
                    // With this div, if they click on the div, they will be taken to the product page of that product
                    echo "<a href='productPage.php?productID=" . $productID . "'.>
                            <div class='productImage'>";
                    // Make a product source variable that holds the URL of the product source with the file name
                    $productSCR = 'Images/products/' . $productID . '/' . $productImageFileName;
                    // Then hold the product Image in this div
                    echo   "<img src='" . $productSCR . "' alt='" . $productImageAltText . "' class='slider__img'>
                            </div>
                            <div class='containerProductDetails col-8'>
                                <div class='productDetails'>
                                    <div class='firstRow'>
                                        <div class='titleOfProduct'>
                                            <h3>" . $productTitle . "</h3>
                                        </div>
                                        <div class='quantityOfProduct'>
                                            <h3>Quantity: " . $productQuantity . "</h3>
                                        </div>
                                    </div>
                                    <div class='secondRow'>
                                        <div class='priceOfProduct'>";
                    // If quantity is more than 1 then show the Price Per Quantity message, if not, don't show
                    $productPrice = $productPriceAtTime;
                    if ($productQuantity > 1) {
                        echo "<h5>Price Per Quantity: £<span id='productPriceQuantity'>" . number_format($productPriceAtTime, 2) . "</span></h3>";
                    }
                    // Output the product price at the time times the quantity 
                    $quantityPrice = $productPrice * $productQuantity;
                    echo "<h3 class='totalPriceOfQuantity'>£" . number_format($quantityPrice, 2) . "</h1>";

                    echo "</div>
                        </div>
                    </div>
                </div>
            </div>
        </a>";
                }
                // After all the products from that order has been outputted, then make the billing and delivery address part
                // Div for the button to show/hide the billing and delivery address details
                echo  "<div class='BillingDeliveryAddressButton' onclick='hideShowBillingDeliveryAddress(" . $orderID . ")'>
                        <h2 class='left'>Billing & Delivery Address</h2>
                        <img class='right' src='images/OrderHistory/Down Arrow - Big.png' alt='Billing and Delivery Address - Right Arrow' />
                    </div>";
                // Now make the billing container with all the billing information
                echo "<div class='billingDeliveryAddressOuterContainer'> 
                    <div id='billingDeliveryAddressInnerContainer" . $orderID .
                    "' class='billingDeliveryAddressInnerContainer'>
                        <div class='billingAddress'>
                            <h2>Billing Address</h2>
                            <div class='nameBilling'>
                                <h3 class='titleBilling'>" . $billingTitle . "</h3>
                                <h3 class='FirstNameBilling'>" . $billingFirstName . "</h3>
                                <h3 class='FirstNameBilling'>" . $billingLastName . "</h3>
                            </div>
                            <h3 class='addressLine1Billing'>" . $billingAddressLine1 . "</h3>";
                // (if address line 2 is blank, then don't output that line)
                if (!empty($billingAddressLine2)) {
                    echo "<h3 class='addressLine2Billing'>" . $billingAddressLine2 . "</h3>";
                }
                echo "<h3 class='townCityBilling'>" . $billingTownCity . "</h3>
                            <h3 class='countyBilling'>" . $billingCounty . "</h3>
                            <h3 class='postCodeBilling'>" . $billingPostCode .
                    "</h3>
                        </div>";
                // Now make the delivery container with all the delivery information
                echo "<div class='deliveryAddress'>
                            <h2>Delivery Address</h2>
                            <div class='nameDelivery'>
                                 <h3 class='titleDelivery'>" . $deliveryTitle . "</h3>
                                <h3 class='FirstNameDelivery'>" . $deliveryFirstName . "</h3>
                                <h3 class='FirstNameDelivery'>" . $deliveryLastName . "</h3>
                            </div>
                            <h3 class='addressLine1Delivery'>" . $deliveryAddressLine1 . "</h3>";
                // (if address line 2 is blank, then don't output that line) 
                if (!empty($deliveryAddressLine2)) {
                    echo "<h3 class='addressLine2Delivery'>" . $deliveryAddressLine2 . "</h3>";
                }
                echo "<h3 class='townCityDelivery'>" . $deliveryTownCity . "</h3>
                            <h3 class='countyDelivery'>" . $deliveryCounty . "</h3>
                            <h3 class='postCodeDelivery'>" . $deliveryPostCode . "</h3>
                        </div>
                    </div>
                </div>
            </div>
            </div>
        </div> 
        </div>";
            }
        }
        ?>
    </div>
    <!-- Add the footer at the bottom after any other material -->
    <?php include "./footer.php " ?>
</body>

</html>
<style>
    .title {
        display: inline-block;
        color: white;
        margin: 50px;
    }

    .title i {
        display: inline;
        font-size: 3em;
        color: #FFFFFF
    }

    .title h1 {
        display: inline;
    }

    .NoOrders {
        text-align: center;
    }

    .productDetails {
        box-shadow: 0 0 0 5px #FFFFFF;
        border-radius: 2%;
        height: 200px;
        background-color: #FFFFFF;
        position: relative;
    }

    .billingAddress,
    .deliveryAddress {
        box-shadow: 0 0 0 5px #FFFFFF;
        border-radius: 2%;
        background-color: #FFFFFF;
        width: 45%;
        position: relative;
        display: inline-block;
        margin-left: 10px;
        margin-right: 10px;
    }

    .billingDeliveryAddressOuterContainer {
        width: 100%;
        justify-content: center;
        align-items: center;
        text-align: center;
    }

    .eachOrder {
        margin-bottom: 50px;
    }

    .eachProduct a {
        color: black;
    }

    .eachOrderInner {
        font-family: Century-Gothic, sans-serif;
        word-break: keep-all;
        margin-left: 20px;
        margin-right: 20px;
        font-size: 25px;
        border-radius: 12.5px;
        color: #000000;
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
        border-style: solid;
        border-width: 5px;
        border-color: white;
        min-height: 200px;
        word-break: keep-all;
    }

    .leftSection,
    .rightSection {
        display: inline-block;
    }


    .rightSection {
        float: right;
    }

    .dateOfOrderDiv {
        margin-left: 50px;
    }

    .firstRow {
        display: inline-block;
        width: 100%;
        margin-top: 50px;
    }

    .nameBilling,
    .nameDelivery {
        display: block;
    }

    .nameBilling h3,
    .nameDelivery h3 {
        display: inline;
    }

    .eachProduct {
        margin: 10px;
        height: 200px;
        padding-bottom: 20px;
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

    .BillingDeliveryAddressButton {
        padding-top: 10px;
        padding-bottom: 10px;
        margin-top: 20px;
        margin-bottom: 20px;
        display: inline-block;
        width: 100%;
        box-shadow: 0 0 0 5px #000000;
        border-radius: 2%;
        display: flex;
        justify-content: center;
        align-items: center;
        cursor: pointer;
    }

    .BillingDeliveryAddressButton h2,
    .BillingDeliveryAddressButton img {
        display: inline;
    }

    .BillingDeliveryAddressButton img {
        width: 5%;
    }

    .productImage {
        box-shadow: 0 0 0 5px #FFFFFF;
        border-radius: 2%;
        height: 200px;
        width: 20%;
        float: left;
        display: inline-block;
    }

    .productImage img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .containerProductDetails {
        width: 75%;
        display: inline-block;
        float: right;
    }

    .titleOfProduct,
    .quantityOfProduct {
        display: inline;
    }

    .quantityPriceOfProduct {
        display: inline-block;
        float: right;
    }

    .quantityPriceOfProduct h1 {
        float: right;
    }

    .priceOfProduct {
        float: right;
    }

    .titleOfProduct {
        display: inline-block;
        float: left;
        width: 80%;
        word-wrap: break-word;
    }

    .quantityOfProduct {
        display: inline-block;
        float: right;
    }

    .totalPriceOfQuantity {
        float: right;
    }

    .billingDeliveryAddressInnerContainer {
        display: none;
    }
</style>

<script>
    // Show or Hide the Billing and Delivery Address Section
    function hideShowBillingDeliveryAddress(orderID) {
        // Get the element and see if it is hidden or not
        var section = document.getElementById('billingDeliveryAddressInnerContainer' + orderID)
        // If hidden
        if (section.style.display === 'none' ||
            section.style.display === '') {
            // Fade in
            fade(section, true)
        } else {
            // If showing, fade Out
            fade(section, false)
        }
    }


    // Fade in and out, depending on the parameters
    function fade(element, fadeIn) {
        // Fade In the element
        if (fadeIn) {
            var opacity = 0.1;
            element.style.display = 'flex';
            var timer = setInterval(function() {
                if (opacity >= 1) {
                    clearInterval(timer);
                }
                element.style.opacity = opacity;
                element.style.filter = 'alpha(opacity=' + opacity * 100 + ")";
                opacity += opacity * 0.1;
            }, 10);
        } else {
            // Fade Out the element
            var opacity = 1;
            var timer = setInterval(function() {
                if (opacity <= 0.1) {
                    clearInterval(timer);
                    element.style.display = 'none';
                }
                element.style.opacity = opacity;
                element.style.filter = 'alpha(opacity=' + opacity * 100 + ")";
                opacity -= opacity * 0.1;
            }, 10);
        }
    }
</script>