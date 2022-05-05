<?php
if (!isset($_SESSION)) {
    @ob_start();
    @session_start();
}


if (!isset($_SESSION['userID'])) {
    header('Location: Login.php');
}


include 'DatabaseLoginDetails.php';

$conn = mysqli_connect($host, $user, $pass, $database);


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
$stmt->bind_param("i", $_SESSION['userID']);

$stmt->execute();

$res = $stmt->get_result();
$allOrdersTiedToAccount = mysqli_fetch_all($res, MYSQLI_ASSOC);
// print_r($allOrdersTiedToAccount);
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="keywords" content="Gadget Gainey, Gadget, Ecommerce, Online, Shop, Kids Toys, Toys, Technology, Gainey, Ashley Gainey">
    <meta name="author" content="Ashley Gainey">
    <meta name="description" content="View Order History, purchased on Gadget Gainey!">


    <title>Order History - Gadget Gainey Store</title>
    <link rel="stylesheet" type="text/css" href="sharedStyles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body>
    <?php include "./header.php" ?>

    <div id="bodyOfPage">
        <div class="title">
            <i class="fa fa-file-text"></i>
            <h1>Order History</h1>
        </div>
        <?php
        if (empty($allOrdersTiedToAccount)) {
            echo "<div class='NoOrders'>
            <h1>No Order have been placed with this account</h1>
            </div>";
        } else {

            foreach ($allOrdersTiedToAccount as $order) {
                //Get each order
                $orderID = $order['orderID'];
                $totalPrice = $order['totalPrice'];
                $DateAndTime = $order['DateAndTime'];

                $billingTitle = $order['billingTitle'];
                $billingFirstName = $order['billingFirstName'];
                $billingLastName = $order['billingLastName'];
                $billingAddressLine1 = $order['billingAddressLine1'];
                $billingAddressLine2 = $order['billingAddressLine2'];
                $billingTownCity = $order['billingtownCity'];
                $billingCounty = $order['billingCounty'];
                $billingPostCode = $order['billingPostCode'];

                $deliveryTitle = $order['deliveryTitle'];
                $deliveryFirstName = $order['deliveryFirstName'];
                $deliveryLastName = $order['deliveryLastName'];
                $deliveryAddressLine1 = $order['deliveryAddressLine1'];
                $deliveryAddressLine2 = $order['deliveryAddressLine2'];
                $deliveryTownCity = $order['deliverytownCity'];
                $deliveryCounty = $order['deliveryCounty'];
                $deliveryPostCode = $order['deliveryPostCode'];

                $date = date_create($DateAndTime);
                // echo "Date and Time:" . $DateAndTime;

                echo "<div class='eachOrder'>
            <div class='eachOrderOuter'>
                <div class='dateOfOrderDiv'>
                    <h2>Order Placed: " .
                    date_format($date, "l dS F Y") . " at " . date_format($date, "H:i") .
                    "</h1>
                </div>
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


                // Get each product from the order
                $stmt = $conn->prepare("select p.productID, op.productPriceAtTime, op.productQuantity, p.productTitle, pi.productImageFileName, pi.productImageAltText from order_product op


INNER JOIN product p ON op.productID = p.productID
INNER JOIN product_image pi ON p.productID = pi.productID



 where pi.displayOrder = 1 and op.orderID = ?");
                $stmt->bind_param("i", $orderID);
                $stmt->execute();

                $res = $stmt->get_result();
                $productsPurchased = mysqli_fetch_all(
                    $res,
                    MYSQLI_ASSOC
                );
                $total = 0;

                foreach ($productsPurchased as $individualProduct) {
                    $productID = $individualProduct['productID'];
                    $productPriceAtTime = $individualProduct['productPriceAtTime'];
                    $productQuantity = $individualProduct['productQuantity'];
                    $productTitle = $individualProduct['productTitle'];
                    $productImageFileName = $individualProduct['productImageFileName'];
                    $productImageAltText = $individualProduct['productImageAltText'];



                    echo  "<div class='eachProduct'>
                    <a href='productPage.php?productID=" . $productID . "'.>
                            <div class='productImage'>";
                    $productSCR = 'Images/products/' . $productID . '/' . $productImageFileName;


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

                    $productPrice = $productPriceAtTime;
                    if ($productQuantity > 1) {
                        echo "<h5>Price Per Quantity: £<span id='productPriceQuantity'>" . number_format($productPriceAtTime, 2) . "</span></h3>";
                    }

                    $quantityPrice = $productPrice * $productQuantity;
                    $total += $quantityPrice;
                    echo "<h3 class='totalPriceOfQuantity'>£" . number_format($quantityPrice, 2) . "</h1>";

                    echo "</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </a>";
                }
                echo  "<div class='BillingDeliveryAddressButton' onclick='hideShowBillingDeliveryAddress(" . $orderID . ")'>
                        <h2 class='left'>Billing & Delivery Address</h2>
                        <img class='right' src='images/Down Arrow - Big.png' alt='Billing and Delivery Address - Right Arrow' />
                    </div>
                    <div class='billingDeliveryAddressOuterContainer'> 
                    <div id='billingDeliveryAddressInnerContainer" . $orderID .
                    "' style='display:none;'>
                        <div class='billingAddress'>
                            <h2>Billing Address</h2>
                            <div class='nameBilling'>
                                <h3 class='titleBilling'>" . $billingTitle . "</h3>
                                <h3 class='FirstNameBilling'>" . $billingFirstName . "</h3>
                                <h3 class='FirstNameBilling'>" . $billingLastName . "</h3>
                            </div>
                            <h3 class='addressLine1Billing'>" . $billingAddressLine1 . "</h3>";
                if (!empty($billingAddressLine2)) {
                    echo "<h3 class='addressLine2Billing'>" . $billingAddressLine2 . "</h3>";
                }
                echo "<h3 class='townCityBilling'>" . $billingTownCity . "</h3>
                            <h3 class='countyBilling'>" . $billingCounty . "</h3>
                            <h3 class='postCodeBilling'>" . $billingPostCode .
                    "</h3>
                        </div>
                        <div class='deliveryAddress'>
                            <h2>Delivery Address</h2>
                            <div class='nameBilling'>
                                 <h3 class='titleBilling'>" . $deliveryTitle . "</h3>
                                <h3 class='FirstNameBilling'>" . $deliveryFirstName . "</h3>
                                <h3 class='FirstNameBilling'>" . $deliveryLastName . "</h3>
                            </div>
                            <h3 class='addressLine1Billing'>" . $deliveryAddressLine1 . "</h3>";
                if (!empty($deliveryAddressLine2)) {
                    echo "<h3 class='addressLine2Billing'>" . $deliveryAddressLine2 . "</h3>";
                }
                echo "<h3 class='townCityBilling'>" . $deliveryTownCity . "</h3>
                            <h3 class='countyBilling'>" . $deliveryCounty . "</h3>
                            <h3 class='postCodeBilling'>" . $deliveryPostCode . "</h3>
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
    </div>
    </div>

    <?php include "./footer.php " ?>
</body>

</html>
<style>
    #bodyOfPage {
        margin-top: 30px;
        margin-left: 50px;
        margin-right: 50px;
    }


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

    .billingDeliveryAddressOuterContainer {
        /* display: flex; */
        width: 100%;
        /* display: flex; */
        justify-content: center;
        align-items: center;
        text-align: center;
        /* display: none; */
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

    .leftSection,
    .rightSection {
        display: inline-block;
    }

    .dateOfOrderDiv {
        /* text-align: center; */

        margin-left: 50px;
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
        padding-bottom: 20px;
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

    .rightSection {
        float: right;
    }

    .rowOne {
        /* height: 100px; */
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
        width: 80%;

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

    .totalPriceOfQuantity {
        float: right;
    }
</style>

<script>
    function hideShowBillingDeliveryAddress(orderID) {
        var section = document.getElementById('billingDeliveryAddressInnerContainer' + orderID)
        if (section.style.display === 'none' ||
            section.style.display === '') {
            // console.log("Not visible");
            fade(section, true)
        } else {
            // console.log("visible");
            fade(section, false)
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