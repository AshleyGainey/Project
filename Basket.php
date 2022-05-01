<?php
// print_r('session here: ' . isset($_SESSION));

if (!isset($_SESSION)) {
    @ob_start();
    @session_start();
}

// print_r('session: ' . $_SESSION['userID']);

$picturesURLCarousel = array();
$productPriceArray = array();
$totalPriceOfEachProduct = array();
// $productQuantityArray = array();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">


    <title>Your Basket - Gadget Gainey Store</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body>
    <?php include "./header.php" ?>
    <div id="mainBody">
        <div class="title">
            <i class="fa fa-shopping-basket"></i>
            <h1>Your Basket</h1>
        </div>
        <form action="Basket.php" id="basketForm" method="post">
            <?php
            $_SESSION["total"] = 0;

            if (!isset($_SESSION["basket"])) {
                $invalidBasket = 1;
                echo "<h1 id='NoProducts'>No Products In the basket</h1>";
            } else if (count($_SESSION["basket"]) == 0) {
                $invalidBasket = 1;
                echo "<h1 id='NoProducts'>No Products In the basket</h1>";
            } else {
                $invalidBasket = 0;
                foreach ($_SESSION["basket"] as $basketItem => $basketItem_value) {
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
                    <?php
                    echo "<div id='individualProduct" . $basketItem . "' class='individualProduct row'>";
                    ?>

                    <div class="containerProduct col-2">
                        <div class="productImage">
                            <?php
                            // echo  "<a href='productPage.php?productID=" . $basketItem . "'>";

                            $productImagePath = "images/products/" . $basketItem . "/" . $basket_product[0]["productImageFilename"];
                            echo "<a href='productPage.php?productID=" . $basketItem . "'><img src='" . $productImagePath . "' alt='" .
                                $basket_product[0]["productImageAltText"]  . "' ></a>";

                            // echo "<\a>";
                            ?>

                            <!-- <img src="Images\Home\Gadget Gainey - No Image Available.gif" class="slider__img"> -->
                        </div>
                    </div>

                    <div class="containerProductDetails col-8">
                        <div class="productDetails">
                            <div class="firstRow">
                                <div class="titleOfProduct">
                                    <?php
                                    // echo  "<a href='productPage.php?productID=" . $basketItem . "'>";
                                    echo "<a href='productPage.php?productID=" . $basketItem . "'><h1>" . $basket_product[0]["productTitle"] . "</h1></a>" ?>

                                </div>
                                <div class="quantityOfProduct">
                                    <label>Quantity:</label>

                                    <?php
                                    echo "<select name='quantity' id='quantity" . $basketItem . "' onchange='changeQuantity(" . $basketItem . ")'>'"

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
                            <?php
                            echo "<div id='quantityPriceOfProduct" . $basketItem . "' class='quantityPriceOfProduct '>"
                            ?>
                            <?php
                            $productPrice = $basket_product[0]["productPrice"];

                            $productPriceArray[$basketItem] = $productPrice;


                            // $productQuantityArray[$basketItem] = $quantitySelected;
                            if ($quantitySelected > 1) {
                                echo "<h3 id='quantityPerPrice" . $basketItem . "'>Price Per Quantity: £<span id='productPriceQuantity'>" . number_format($productPrice, 2) . "</span></h3>";
                            } else {
                                echo "<h3 id='quantityPerPrice" . $basketItem . "' class='hiddenQuantity'>Price Per Quantity: £<span id='productPriceQuantity'>" . number_format($productPrice, 2) . "</span></h3>";
                            }



                            $quantityPrice = $productPrice * $quantitySelected;
                            $totalPriceOfEachProduct[$basketItem] = $quantityPrice;
                            $_SESSION["total"] += $quantityPrice;



                            echo "<h1 id='totalPriceOfQuantity" . $basketItem . "' >£" . number_format($quantityPrice, 2) . "</h1>";
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
    <div id="totalContainer">
        <div class="total">
            <h1 class="totalHeader">Total:<h1>
                    <?php
                    // echo "<h1 class='totalAmount'>£" . number_format($_SESSION["total"], 2) . "<h1>";
                    echo "<h1 id='totalAmount'> £" . number_format($_SESSION["total"], 2)  . "<h1>";
                    ?>

        </div>
    </div>
    <div id="checkoutDiv">
        <div class="cardContainer rightPart">
            <a href="checkout.php">

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


    .quantityPriceOfProduct h1 {
        float: right;
    }

    .containerProductDetails {
        width: 75%;
        display: inline-block;
        float: right;
    }

    #totalContainer {
        width: 100%;
        display: inline-block;
    }

    .totalHeader {
        float: left;
    }

    #totalAmount {
        float: right;
    }

    .productImage {
        box-shadow: 0 0 0 5px #FFFFFF;
        border-radius: 2%;
        height: 200px;
        width: 20%;
        float: left;
    }

    #checkoutDiv {
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
    }

    .hiddenQuantity {
        display: none;
    }

    a {
        text-decoration: none;
        color: black;
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


    .quantityPriceOfProduct {
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
    var totalPriceOfEachProduct = <?php echo json_encode($totalPriceOfEachProduct); ?>;

    function changeQuantity(id) {
        var x = document.getElementById("quantity" + id).value;

        let xhr = new XMLHttpRequest();

        xhr.open('POST', "basket_process.php", true)
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.send("update=" + id + "&quantity=" + x);


        // Create an event to receive the return.
        xhr.onreadystatechange = function() {
            if (xhr.readyState == 4 && xhr.status == 200) {
                var passedPriceArray = <?php echo json_encode($productPriceArray); ?>;
                var totalValueOfProduct = passedPriceArray[id] * x;

                if (x > 1) {
                    var element = document.getElementById("quantityPerPrice" + id);
                    element.classList.remove("hiddenQuantity");
                } else {
                    var element = document.getElementById("quantityPerPrice" + id);
                    element.classList.add("hiddenQuantity");
                }

                document.getElementById("totalPriceOfQuantity" + id).innerHTML = "£" + totalValueOfProduct.toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");

                totalPriceOfEachProduct[id] = totalValueOfProduct;
                var total = 0;
                for (const [key, value] of Object.entries(totalPriceOfEachProduct)) {
                    console.log(key, value);
                    total = total + value
                }

                // debugger;
                document.getElementById("totalAmount").innerHTML = "£" + total.toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");


                var data = xhr.responseText;
                console.log(data);
            }
        }
    }


    function removeProductFromBasket(id) {

        let xhr = new XMLHttpRequest();

        xhr.open('POST', "basket_process.php", true)
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.send("remove=" + id);


        // Create an event to receive the return.
        xhr.onreadystatechange = function() {
            if (xhr.readyState == 4 && xhr.status == 200) {
                var basketcount = document.getElementById('cartCount').innerHTML;
                basketcount--;

                if (basketcount > 0) {
                    document.getElementById("cartCount").style.display = "inline";
                } else {
                    document.getElementById("cartCount").style.display = "none";

                    const removeCheckout = document.getElementById("checkoutDiv");
                    removeCheckout.remove();
                    const removeTotal = document.getElementById("totalContainer");
                    removeTotal.remove();
                    addNoProducts();
                }
                document.getElementById("cartCount").innerHTML = basketcount;

                removeFadeOut(document.getElementById('individualProduct' + id), 500);
                totalPriceOfEachProduct[id] = 0;
                var total = 0;
                for (const [key, value] of Object.entries(totalPriceOfEachProduct)) {
                    console.log(key, value);
                    total = total + value
                }
                
                document.getElementById("totalAmount").innerHTML = "£" + total.toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");

                var data = xhr.responseText;
                console.log(data);
            }
        }
    }




    function addNoProducts() {
        setTimeout(function() {
            const header = document.createElement("h1");

            const node = document.createTextNode("No Products In the basket");
            header.appendChild(node);
            header.setAttribute("id", "NoProducts");

            const addNoProducts = document.getElementById("basketForm");

            addNoProducts.appendChild(header);
        }, 500);
    }


    function removeFadeOut(el, speed) {
        var seconds = speed / 1000;
        el.style.transition = "opacity " + seconds + "s ease";

        el.style.opacity = 0;
        setTimeout(function() {
            el.parentNode.removeChild(el);
        }, speed);
    }

    function updateTotalPrice() {
        <?php
        if (count($_SESSION["basket"]) == 0) {
            $invalidBasket = 1;
        } ?>
    }
</script>

</html>