<?php
if (!isset($_SESSION)) {
    @ob_start();
    @session_start();
}


if (!isset($_SESSION['userID'])) {
    header('Location: Login.php');
}


include 'DBlogin.php';

$conn = mysqli_connect($host, $user, $pass, $database);


$stmt = $conn->prepare("select po.orderID, po.totalPrice, po.DateAndTime,

bua.addressLine1 As billingAddressLine1, bua.addressLine2 As billingAddressLine2, bua.townCity As billingtownCity, bua.county As billingCounty, bua.postCode As billingPostCode,
dua.addressLine1 As deliveryAddressLine1, dua.addressLine2 As deliveryAddressLine2, dua.townCity As deliverytownCity, dua.county As deliveryCounty, dua.postCode As deliveryPostCode


FROM purchase_order po

INNER JOIN user_address AS bua
ON po.billingAddressID = bua.addressID


INNER JOIN user_address AS dua
ON po.billingAddressID = dua.addressID



where po.UserID = ?");
$stmt->bind_param("i", $_SESSION['userID']);

$stmt->execute();

$res = $stmt->get_result();
$allOrdersTiedToAccount = mysqli_fetch_all($res, MYSQLI_ASSOC);
// print_r($allOrdersTiedToAccount);

foreach ($allOrdersTiedToAccount as $order) {
    //Get each order
    $orderID = $order['orderID'];
    $totalPrice = $order['totalPrice'];
    $DateAndTime = $order['DateAndTime'];
    $billingAddressLine1 = $order['billingAddressLine1'];
    $billingAddressLine2 = $order['billingAddressLine2'];
    $billingTownCity = $order['billingtownCity'];
    $billingCounty = $order['billingCounty'];
    $billingPostCode = $order['billingPostCode'];

    $deliveryAddressLine1 = $order['deliveryAddressLine1'];
    $deliveryAddressLine2 = $order['deliveryAddressLine2'];
    $deliveryTownCity = $order['deliverytownCity'];
    $deliveryCounty = $order['deliveryCounty'];
    $deliveryPostCode = $order['deliveryPostCode'];



    // Get each product from the order
    $stmt = $conn->prepare("select p.productID, op.productPriceAtTime, op.productQuantity, p.productTitle, pi.productImageFileName, pi.productImageAltText from order_product op


INNER JOIN product p ON op.productID = p.productID
INNER JOIN product_image pi ON p.productID = pi.productID



 where pi.displayOrder = 1 and op.orderID = ?");
    $stmt->bind_param("i", $orderID);
    $stmt->execute();

    $res = $stmt->get_result();
    $individualProduct = mysqli_fetch_all($res, MYSQLI_ASSOC);

    print_r($individualProduct);
}



















?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">


    <title>Order Details - Gadget Gainey Store</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <!--    Offline use -->
    <script src="jquery-3.4.1.min.js"></script>
</head>

<body>
    <?php include "./header.php" ?>

    <div id="mainBody">
        <p>Order Details</p>
        <div class="eachOrder">
            <div class="eachOrderOuter">
                <div class="dateOfOrderDiv">
                    <h2>Order Placed: 18/04/2022 11:23</h1>
                </div>
                <div class="eachOrderInner">
                    <div class="rowOne">
                        <div class="leftPart">
                            <h2>Order Number: 000</h1>
                            <!-- echo sprintf('%05d', $orderID); -->
                        </div>
                        <div class="rightPart">
                            <h2>Total Price: £00.00</h1>
                        </div>
                    </div>
                    <div class="rowTwo">
                        <div class="eachProduct">
                            <div class="productImage">
                                <img src="Images\Home\Gadget Gainey - No Image Available.gif" class="slider__img">
                            </div>
                            <div class="containerProductDetails col-8">
                                <div class="productDetails">
                                    <div class="firstRow">
                                        <div class="titleOfProduct">
                                            <h3>Title Of Product</h3>
                                        </div>
                                        <div class="quantityOfProduct">
                                            <h3>Quantity:</h3>
                                        </div>
                                    </div>
                                    <div class="secondRow">
                                        <div class="priceOfProduct">
                                            <h3>£100.00</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="BillingDeliveryAddressButton" onclick="hideShowBillingDeliveryAddress()">
                        <h2 class="left">Billing & Delivery Address</h2>
                        <img class="right" src="images/Down Arrow - Big.png" alt="Billing and Delivery Address - Right Arrow" />
                    </div>
                    <div id="billingDeliveryAddressContainer">
                        <div class="billingAddress">
                            <h2>Billing Address</h2>
                            <div class="nameBilling">
                                <h3 class="titleBilling">Mr</h3>
                                <h3 class="FirstNameBilling">Ashley</h3>
                                <h3 class="FirstNameBilling">Gainey</h3>
                            </div>
                            <h3 class="addressLine1Billing">Address Line 1</h3>
                            <h3 class="addressLine2Billing">Address Line 2</h3>
                            <h3 class="townCityBilling">Town/City</h3>
                            <h3 class="countyBilling">County</h3>
                            <h3 class="postCodeBilling">Postcode</h3>
                        </div>
                        <div class="deliveryAddress">
                            <h2>Delivery Address</h2>
                            <div class="nameBilling">
                                <h3 class="titleBilling">Mr</h3>
                                <h3 class="FirstNameBilling">Ashley</h3>
                                <h3 class="FirstNameBilling">Gainey</h3>
                            </div>
                            <h3 class="addressLine1Billing">Address Line 1</h3>
                            <h3 class="addressLine2Billing">Address Line 2</h3>
                            <h3 class="townCityBilling">Town/City</h3>
                            <h3 class="countyBilling">County</h3>
                            <h3 class="postCodeBilling">Postcode</h3>

                        </div>
                    </div>
                </div>

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

    /* .eachOrder {
        display: flex;
        justify-content: center;
        align-items: center; */
    /* } */



    .productDetails {
        box-shadow: 0 0 0 5px #FFFFFF;
        border-radius: 2%;
        height: 200px;
        background-color: #FFFFFF;
        /* float: right; */
        position: relative;
    }


    .billingAddress,
    .deliveryAddress {
        box-shadow: 0 0 0 5px #FFFFFF;
        border-radius: 2%;
        /* height: 200px; */
        background-color: #FFFFFF;
        /* float: right; */
        width: 45%;
        position: relative;
        display: inline-block;
        margin-left: 10px;
        margin-right: 10px;
    }

    #billingDeliveryAddressContainer {
        /* display: flex; */
        width: 100%;
        /* display: flex; */
        justify-content: center;
        align-items: center;
        display: none;
    }

    .eachOrderInner {
        font-family: Century-Gothic, sans-serif;
        word-break: keep-all;
        /* white-space: pre-wrap; */
        margin-left: 20px;
        margin-right: 20px;
        font-size: 25px;
        border-radius: 12.5px;
        /* background-color: #FFFFFF; */
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
        border-style: solid;
        border-width: 5px;
        border-color: white;
        min-height: 200px;
        word-break: keep-all;
    }

    #billingDeliveryAddressContainer {
        display: none;
    }

    .leftPart,
    .rightPart {
        display: inline-block;
    }

    .dateOfOrderDiv {
        text-align: center;
    }

    .writingOfCard a {
        font-size: 25px;
        text-decoration: none;
        color: #000000;
        position: relative;
    }

    .writingOfCard img {
        float: right;
        width: 20px;
    }

    .writingOfCard {
        margin-top: 10px;
        text-align: center;
    }

    .card {
        box-shadow: 0 0 0 5px #FFFFFF;
        border-radius: 2%;
        height: 75px;
        overflow: hidden;
        position: relative;
        padding: 10px;
    }

    .firstRow {
        display: inline-block;
        width: 100%;
        margin-top: 50px;
    }

    .nameBilling {
        display: block;
    }

    .nameBilling h3 {
        display: inline;
    }

    .eachProduct {
        margin: 10px;
        height: 200px;
    }

    /* 
    .secondRow {
        margin-top: 50px;
        width: 100%;
        float: left;
        margin-left: auto;
        display: flex;
        justify-content: center;
        align-items: center;
    } */

    .cardContainer {
        display: inline-block;
        width: 250px;
    }

    .writingOfCard a {
        font-size: 25px;
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

    .rowOne {
        height: 100px;
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
        /* margin-right: 30px; */
        width: 5%;
    }

    /* .BillingDeliveryAddress h2 {
        display: flex;
        justify-content: center;
        align-items: center;
    } */

    .productImage {
        box-shadow: 0 0 0 5px #000000;
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
        /* width: 15%; */
    }

    .quantityPriceOfProduct h1 {
        float: right;
    }



    .left {
        float: left;
    }

    .right {
        float: right;
    }


    .priceOfProduct {
        float: right;
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
    }


    .rowTwo {
        /* float: right;
        position: absolute; */
        /* bottom: 0;
        padding-left: 2%;
        padding-right: 2%;
        padding-top: 2%;
        width: 97%; */
    }
</style>

<script>
    function hideShowBillingDeliveryAddress() {
        // debugger;
        if (document.getElementById('billingDeliveryAddressContainer').style.display === 'none' || document.getElementById('billingDeliveryAddressContainer').style.display === '') {
            console.log("Not visible");
            fade(document.getElementById("billingDeliveryAddressContainer"), true)
        } else {
            console.log("visible");
            fade(document.getElementById("billingDeliveryAddressContainer"), false)
        }
    }


    function fade(element, fadeIn) {
        if (fadeIn) {
            var op = 0.1; // initial opacity
            element.style.display = 'flex';
            var timer = setInterval(function() {
                if (op >= 1) {
                    clearInterval(timer);
                }
                element.style.opacity = op;
                element.style.filter = 'alpha(opacity=' + op * 100 + ")";
                op += op * 0.1;
            }, 10);
        } else {
            var op = 1; // initial opacity
            var timer = setInterval(function() {
                if (op <= 0.1) {
                    clearInterval(timer);
                    element.style.display = 'none';
                }
                element.style.opacity = op;
                element.style.filter = 'alpha(opacity=' + op * 100 + ")";
                op -= op * 0.1;
            }, 10);
        }
    }
</script>