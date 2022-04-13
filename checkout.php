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
            <i class="fa fa-shopping-cart"></i>
            <h1>Confirm your Order</h1>
        </div>
        <form action="placeOrder.php" method="post">
            <?php
            // $total = $_POST[];
            $total = $_SESSION["total"];
            echo "hello" . $total;
            ?>
            <?php
            if ($invalidBasket == 0) {
            ?>
                <div class="totalContainer">
                    <div class="total">
                        <h1 class="totalHeader">Total:</h1>
                        <?php

                        echo "<h1 class='totalAmount'>£" . number_format($total, 2) . "<h1>";
                        ?>
                    </div>
                </div>
                <h1 class="">Billing Address:</h1>
                <div class="checkoutDiv">
                    <div class="cardContainer rightPart">
                        <a href="">
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
        color: #FFFFFF;
        margin-right: 10px;
    }

    .total {
        margin-top: 50px;
        width: 100%;
        float: left;

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
        width: 20%;
        display: inline-block;
        display: block;
        margin: 0 auto;
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

        $.ajax({
            url: "basket_process.php", //the page containing php script
            type: "post", //request type,
            dataType: 'json',
            data: {
                "update": id,
                "quantity": x
            },
            success: function(result) {
                debugger;
                // document.getElementById("regMessage").style.display = "block";
                // document.getElementById('regMessage').innerHTML = xhr.status + " " + xhr.responseText.replaceAll('"', '');
                // console.log(result.abc);
            }
        });
    }


    function removeProductFromBasket(id) {

        // debugger;

        $.ajax({
            url: 'basket_process.php',
            type: 'POST',
            data: {
                "remove": id
            },
            success: function(response) {
                removeFadeOut(document.getElementById('individualProduct' + id), 500);
                updateTotalPrice();
                // debugger;


                // if (status == "error") {
                // var message = "Sorry but there was an error. Please contact the University IT Support for more details: ";
                // } else {
                //     alert(id);
                //     $("#resultsFound").text(response);
                // }
            }
        });


        function removeFadeOut(el, speed) {
            var seconds = speed / 1000;
            el.style.transition = "opacity " + seconds + "s ease";

            el.style.opacity = 0;
            setTimeout(function() {
                el.parentNode.removeChild(el);
            }, speed);
        }


    }

    function updateTotalPrice() {
        <?php
        if (count($_SESSION["basket"]) == 0) {
            $invalidBasket = 1;
        } ?>
        // debugger;
    }
</script>

</html>