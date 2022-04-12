<?php

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
            <?php
            $total = 0;

            if (!isset($_SESSION["basket"])) {
                $invalidBasket = 1;
                echo "<h1 id='NoProducts'>No Products In the basket</h1>";
            } else if (count($_SESSION["basket"]) == 0){
                $invalidBasket = 1;
                echo "<h1 id='NoProducts'>No Products In the basket</h1>";
            } else {
                $invalidBasket = 0;
                foreach ($_SESSION["basket"] as $basketItem => $basketItem_value) {
                    // echo
                    // "HRERERERERERERERRERER" . $basketItem;
                    // echo "xjbfdsgjfdfj" . $basketItem_value;


                    include 'DBlogin.php';

                    $conn = new mysqli($host, $user, $pass, $database);
                    // Check connection
                    if ($conn->connect_error) {
                        die("Connection failed: " . $conn->connect_error);
                    }

                    $stmt = $conn->prepare("SELECT p.productTitle, pi.productImageFilename, pi.productImageAltText, p.productPrice, p.productTotalQuantity FROM product p INNER JOIN product_image pi ON p.productID = pi.productID
where p.productID = ? AND pi.displayOrder = 1");
                    $stmt->bind_param("i", $basketItem);
                    $stmt->execute();
                    $res = $stmt->get_result();
                    $basket_product = mysqli_fetch_all($res, MYSQLI_ASSOC);
                    // print_r($basket_product);
            ?>
                    <p id="regMessage"></p>
                    <div class="individualProduct row">
                        <div class="containerProduct col-2">
                            <div class="productImage">
                                <?php
                                $productImagePath = "images/products/" . $basketItem . "/" . $basket_product[0]["productImageFilename"];
                                echo "<img src='" . $productImagePath . "' alt='" .
                                    $basket_product[0]["productImageAltText"]  . "' >" ?>
                                <!-- <img src="Images\Home\Gadget Gainey - No Image Available.gif" class="slider__img"> -->
                            </div>
                        </div>
                        <div class="containerProductDetails col-8">
                            <div class="productDetails">
                                <div class="firstRow">
                                    <div class="titleOfProduct">
                                        <?php
                                        echo "<h1>" . $basket_product[0]["productTitle"] . "</h1>" ?>

                                        <!-- <h1>Title Of Product</h1> -->
                                    </div>
                                    <div class="quantityOfProduct">
                                        <label>Quantity:</label>

                                        <?php
                                        echo "<select name='quantity' id='quantity' onchange='changeQuantity(" . $basketItem . ")'>'"

                                        ?>

                                        <?php
                                        $quantity = $basket_product[0]["productTotalQuantity"];
                                        $quantitySelected = $basketItem_value;


                                        if ($quantity > 10) {
                                            $quantity = 10;
                                        }
                                        for ($i = 1; $i < $quantity + 1; $i++) {
                                            $selectOption = "<option value='" . $i . "'";
                                            if ($i == $quantitySelected) {
                                                $selectOption = $selectOption . " selected";
                                            }
                                            $selectOption = $selectOption .
                                                ">" . $i . "</option>";
                                            echo $selectOption;
                                        }
                                        ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="secondRow">
                                    <?php
                                    echo "<div class='RemoveProduct' onclick='removeProductFromBasket(" . $basketItem . ")'>"
                                    ?>
                                    <i class="fa fa-times" aria-hidden="true"></i>
                                    <h4>Remove</h4>
                                </div>
                                <div class="quantityOfProduct">
                                    <?php
                                    $productPrice = $basket_product[0]["productPrice"];
                                    if ($quantitySelected > 1) {
                                        echo "<h3>Price Per Quantity: £" . number_format($productPrice, 2) . "</h3>";
                                    }

                                    $quantityPrice = $productPrice * $quantitySelected;
                                    $total += $quantityPrice;
                                    echo "<h1>£" . number_format($quantityPrice, 2) . "</h1>";
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
    </div>
<?php
                }
            }
?>
<?php
if ($invalidBasket == 0) {
?>
    <div class="totalContainer">
        <div class="total">
            <h1 class="totalHeader">Total:<h1>
                    <?php

                    echo "<h1 class='totalAmount'>£" . number_format($total, 2) . "<h1>";
                    ?>

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
<?php
}
?>
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


    #NoProducts {
        display: flex;
        justify-content: center;
        align-items: center;
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

    .quantityOfProduct h1 {
        float: right;
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
    function changeQuantity(id) {
        var x = document.getElementById("quantity").value;
        // alert(x);
        // debugger;
        $("#regMessage").load("basket_process.php", {
            update: id,
            quantity: x
        }, function(response, status, xhr) {
            debugger;
            document.getElementById("regMessage").style.display = "block";
            document.getElementById('regMessage').innerHTML = xhr.status + " " + xhr.responseText.replaceAll('"', '');

            // if (status == "error") {
            //     // var message = "An error occured while trying to do this action.";
            //     document.getElementById('regMessage').innerHTML = xhr.status + " " + xhr.responseText.replaceAll('"', '');
            // }
            if (status == "success") {
                // window.location.href = "account_welcome.php";
            }
        })
    }


    function removeProductFromBasket(id) {
        alert(id);
        // debugger;
        $("#regMessage").load("basket_process.php", {
            remove: id,
        }, function(response, status, xhr) {
            debugger;
            document.getElementById("regMessage").style.display = "block";
            document.getElementById('regMessage').innerHTML = xhr.status + " " + xhr.responseText.replaceAll('"', '');

            // if (status == "error") {
            //     // var message = "An error occured while trying to do this action.";
            //     document.getElementById('regMessage').innerHTML = xhr.status + " " + xhr.responseText.replaceAll('"', '');
            // }
            if (status == "success") {
                // window.location.href = "account_welcome.php";
            }
        })
    }
</script>

</html>