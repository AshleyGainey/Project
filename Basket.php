<?php
//Check to see if product has been added to the basket from the Add to Basket button on the product page
if (isset($_POST['product_id'], $_POST['quantity']) && is_numeric($_POST['product_id']) &&  is_numeric($_POST['quantity'])) {
    //Make sure the quantity and product ID are integers and set variables to use later on.
    $basket_product_ID = (int)$_POST['product_id'];
    $basket_quantity = (int)$_POST['quantity'];

    //Verify that the product is in the database (users then can't manipulate the system and add products that aren't there) 
    // and put the result in an array
    $stmt = $pdo->prepare('Select * from products where id = ?');
    $stmt->execute([$_POST['product_id']]);
    $basket_product = $stmt->fetch(PDO::FETCH_ASSOC);

    //If there is a product in the database (the product exists)
    if ($basket_product && $basket_quantity > 0) {
        if (isset($_SESSION['basket']) && is_array($_SESSION['basket'])) {
            //If any product at all is already exists in the basket
            if (array_key_exists($basket_product_ID, $_SESSION['basket'])) {
                //Update the quantity of the product since the product is already in the basket.
                $_SESSION['basket'][$basket_product_ID] += $basket_quantity;
            } else {
                //Add the product to the basket, due to it not being in there already
                $_SESSION['basket'][$basket_product_ID] = $basket_quantity;
            }
        } else {
            //Add the first product to the basket - there were no products in the basket previously 
            // (add the product ID as the key and the quantity as the value)
            $_SESSION['basket'] += array($basket_product_ID => $basket_quantity);
        }
    }
}

//Remove from basket by looking at the URL paramater of remove (which is the product id), check to see if it is in the basket and is numerical
if (isset($_GET['remove']) && is_numeric($_GET['remove']) && isset($_SESSION['basket']) && isset($_SESSION['basket'][$_GET['remove']])) {
    unset($_SESSION['basket'][$_GET['remove']]);
}

if (isset($_POST['update']) && isset($_SESSION['basket'])) {
    foreach ($_POST as $key => $value) {
        if (strpos($key, 'quantity') !== false && is_numeric($value)) {
            $id = str_replace('quantity-', '', $k);
            $basket_product_quantity = (int)$value;
            if (is_numeric($id) && isset($_SESSION['basket'][$id]) && $basket_product_quantity > 0) {
                $_SESSION['basket'][$id] = $basket_product_quantity;
            }
        }
    }
}


if (isset($_POST['placeorder']) && isset($_SESSION['basket']) && !empty($_SESSION['basket'])) {
    header('Location: Change_Email.php');
}



$productsInBasket = isset($_SESSION['basket']) ? $_SESSION['basket'] : array();
$prods = array();
$subtotal = 0.00;

if ($productsInBasket) {
    $arraytoQs = implode(',', array_fill(0, count($productsInBasket), '?'));
    $stmt = $pdo->prepare('SELECT * from products WHERE product_id IN (' . $arraytoQs . ')');
    $stmt->execute(array_keys($productsInBasket));
    $productsBackFromDB = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($productsBackFromDB as $indProduct) {
        $subtotal += (float)$indProduct['price'] * (int)$productsInBasket[$indProduct['id']];
    }
}
?>








<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">


    <title>Your Basket - Gadget Gainey Store</title>
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
            <i class="fa fa-shopping-basket"></i>
            <h1>Your Basket</h1>
        </div>
        <form action="Basket.php" method="post">

            <div class="individualProduct row">
                <div class="containerProduct col-2">
                    <div class="productImage">
                        <img src="Images\Home\Gadget Gainey - No Image Available.gif" class="slider__img">
                    </div>
                </div>
                <div class="containerProductDetails col-8">
                    <div class="productDetails">
                        <div class="firstRow">
                            <div class="titleOfProduct">
                                <h1>Title Of Product</h1>
                            </div>
                            <div class="quantityOfProduct">
                                <label>Quantity:</label>

                                <select name="quantity" id="quantity">
                                    <?php
                                    $quantity = 4;
                                    if ($quantity > 10) {
                                        $quantity = 10;
                                    }
                                    for ($i = 1; $i < $quantity + 1; $i++) {
                                        echo "<option value='" . $i . "'>" . $i . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="secondRow">
                            <div class="RemoveProduct">
                                <i class="fa fa-times" aria-hidden="true"></i>
                                <h4>Remove</h4>
                            </div>
                            <div class="quantityOfProduct">
                                <h1>£100.00<h1>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="totalContainer">
                <div class="total">
                    <h1 class="totalHeader">Total:<h1>
                            <h1 class="totalAmount">£100.00<h1>
                </div>
            </div>
            <div class="checkoutDiv">
                <div class="cardContainer rightPart">
                    <a href="#">

                        <div class="card">
                            <div class="writingOfCard">
                                <h1>Checkout</h1>
                            </div>
                    </a>
                </div>
            </div>
    </div>
    </div>

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

    .containerProductImage {
        width: 10%;
        display: inline-block;
    }


    .containerProductDetails {
        width: 75%;
        display: inline-block;
        float: right;
    }

    .totalContainer {
        width: 100%;
        display: inline-block;
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
        margin-bottom: 50px;
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


    .cardContainer {
        display: inline-block;
        width: 250px;
    }

    .card {
        box-shadow: 0 0 0 5px #FFFFFF;
        border-radius: 2%;
        height: 75px;
        overflow: hidden;
        position: relative;
        padding: 10px;
        background-color: #1a1862;
    }

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
</style>
<script>

</script>

</html>