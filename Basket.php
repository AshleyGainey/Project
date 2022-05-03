<?php
// If the session hasn't started. Start it (can then use session variables)
if (!isset($_SESSION)) {
    @ob_start();
    @session_start();
}
//Store Product Price's in an array for later
$productPriceArray = array();
//Store Total Price's in an array for later
$totalPriceOfEachProduct = array();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Shows what the title of the tab is-->
    <title>Your Basket - Gadget Gainey Store</title>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <!-- Put a viewport on this page -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Keywords of the site for search engine optimisation -->
    <meta name="keywords" content="Gadget Gainey, Gadget, Ecommerce, Online, Shop, Kids Toys, Toys, Technology, Gainey, Ashley Gainey">
    <!-- Author of the site -->
    <meta name="author" content="Ashley Gainey">
    <!-- Description of the page -->
    <meta name="description" content="View, edit and remove from your basket!">

    <!-- Link to the shared classes and ID style sheet -->
    <link rel="stylesheet" type="text/css" href="sharedStyles.css">
</head>

<body>
    <?php include "./header.php" ?>
    <div id="bodyOfPage">
        <div class="title">
            <!-- Title of Page with an icon (better accessibility) -->
            <i class="fa fa-shopping-basket"></i>
            <h1>Your Basket</h1>
        </div>
        <?php
        $total = 0;

        // Check to see if there is a basket session set, if not, don't show any other elements other than 
        // displaying a header saying 'No products in the basket'
        if (!isset($_SESSION["basket"])) {
            $invalidBasket = 1;
            echo "<h1 id='NoProducts'>No Products in the basket</h1>";
        } else if (count($_SESSION["basket"]) == 0) {
            $invalidBasket = 1;
            echo "<h1 id='NoProducts'>No Products in the basket</h1>";
        } else {
            // Not invalid so keep looping through the basket session array, getting out the key (the Product ID) and the value (the quantity)
            $invalidBasket = 0;
            foreach ($_SESSION["basket"] as $basketItem => $basketItem_value) {
                include 'DatabaseLoginDetails.php';

                $conn = new mysqli($host, $user, $pass, $database);
                // Check connection
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }
                //Select all the information needed for the product (product title, image (alt text and image file name), price, the quantity in the DB.
                $stmt = $conn->prepare("SELECT p.productTitle, pi.productImageFilename, pi.productImageAltText, p.productPrice, p.productTotalQuantity FROM product p INNER JOIN product_image pi ON p.productID = pi.productID
where p.productID = ? AND pi.displayOrder = 1");
                $stmt->bind_param("i", $basketItem);

                if (!$stmt->execute()) {
                    header('HTTP/1.1 500 Internal Server Error');
                    header('Content-Type: application/json; charset=UTF-8');
                    die(json_encode('ERROR - Could not retrieve the data of the item from the database.'));
                }
                //Get results from the database
                $res = $stmt->get_result();
                $basket_product = mysqli_fetch_all($res, MYSQLI_ASSOC);

                // Now add the structure of the product information
                // Starting with a div container to contain everything of that product
                echo "<div id='individualProduct" . $basketItem . "' class='individualProduct'>";
        ?>
                <!-- Container for the whole of the Product Image -->
                <div class="containerProduct">
                    <!-- Container for the Product Image -->
                    <div class="productImage">
                        <!-- insert the Product Image, getting the correct URL and the alternative text for the image -->
                        <?php
                        $productImagePath = "images/products/" . $basketItem . "/" . $basket_product[0]["productImageFilename"];
                        echo "<a href='productPage.php?productID=" . $basketItem . "'><img src='" . $productImagePath . "' alt='" .
                            $basket_product[0]["productImageAltText"]  . "' ></a>";
                        ?>
                    </div>
                </div>
                <!-- Container for all the other information of the product -->
                <div class="containerProductDetails">
                    <div class="productDetails">
                        <!-- First Row includes the title of the product and the quantity in the basket-->
                        <div class="firstRow">
                            <div class="titleOfProduct">
                                <?php
                                //Output the Title, with a link to the original product
                                echo "<a href='productPage.php?productID=" . $basketItem . "'><h1>" . $basket_product[0]["productTitle"] . "</h1></a>" ?>

                            </div>
                            <div class="quantityOfProduct">
                                <label>Quantity:</label>
                                <?php
                                //Output the Quantity Dropdown
                                echo "<select name='quantity' id='quantity" . $basketItem . "' onchange='changeQuantity(" . $basketItem . ")'>'"
                                ?>


                                <?php
                                //Get the quantity from the Database and if over 10, then just say the user can order the limit for a user (10)
                                $quantity = $basket_product[0]["productTotalQuantity"];
                                // Also get the quantity that is in the basket for that product
                                $quantitySelected = $basketItem_value;

                                if ($quantity > 10) {
                                    $quantity = 10;
                                }
                                // Loop through, adding the dropdown values (1-10 if database holds more than 10 as its total quantity, 1-x if the database holds less than 10 (x being what the database has))
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
                        <!-- Second row contains the Remove Product button and the quantity prices of the product in the basket -->
                        <div class="secondRow">
                            <?php
                            echo "<div class='RemoveProduct' onclick='removeProductFromBasket(" . $basketItem . ")'>"
                            ?>
                            <i class="fa fa-times" aria-hidden="true"></i>
                            <h4>Remove</h4>
                        </div>
                        <?php
                        // Div to hold the prices (appending the product id for the div id)
                        echo "<div id='quantityPriceOfProduct" . $basketItem . "' class='quantityPriceOfProduct'>"
                        ?>
                        <?php
                        $productPrice = $basket_product[0]["productPrice"];

                        $productPriceArray[$basketItem] = $productPrice;

                        // If the quantity from the basket is more than 1, then display text telling the user how much it is for one of the products. If not more than one, then create it, but add the hiddenQuantity class which hides the element.
                        if ($quantitySelected > 1) {
                            echo "<h3 id='quantityPerPrice" . $basketItem . "'>Price Per Quantity: £<span id='productPriceQuantity'>" . number_format($productPrice, 2) . "</span></h3>";
                        } else {
                            echo "<h3 id='quantityPerPrice" . $basketItem . "' class='hiddenQuantity'>Price Per Quantity: £<span id='productPriceQuantity'>" . number_format($productPrice, 2) . "</span></h3>";
                        }

                        // Work out the total amount for the product (price times quantity) and add it to the total price of the basket
                        $quantityPrice = $productPrice * $quantitySelected;
                        $totalPriceOfEachProduct[$basketItem] = $quantityPrice;
                        $total += $quantityPrice;


                        // Output the price with the quantity included
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
// After printing out each product in the basket, and only if there are items in the basket
if ($invalidBasket == 0) {
?>
    <!-- Then output the total price that is in the basket -->
    <div id="totalContainer">
        <div class="total">
            <h1 class="totalHeader">Total:<h1>
                    <?php
                    // echo "<h1 class='totalAmount'>£" . number_format($_SESSION["total"], 2) . "<h1>";
                    echo "<h1 id='totalAmount'> £" . number_format($total, 2)  . "<h1>";
                    ?>

        </div>
    </div>
    <!-- And show a checkout button to get to the checkout page-->
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
    //When first loaded, get the total Price of each product
    var totalPriceOfEachProduct = <?php echo json_encode($totalPriceOfEachProduct); ?>;

    //When triggered (by the quantity select dropdown)
    function changeQuantity(id) {
        //Get the quantity from the dropdown
        var quantity = document.getElementById("quantity" + id).value;

        //Then make a request to the basket_process.php and send in the values
        let xhr = new XMLHttpRequest();
        xhr.open('POST', "basket_process.php", true)
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.send("update=" + id + "&quantity=" + quantity);


        // On return of the call
        xhr.onreadystatechange = function() {
            //See if it is ready and the status is OK
            if (xhr.readyState == 4 && xhr.status == 200) {
                //If it is, then work out the new total price on the client side 
                //By getting all of the prices from the PHP variable productPriceArray
                var passedPriceArray = <?php echo json_encode($productPriceArray); ?>;
                //Then getting the price of the relevant product, times that by the updated quantity
                var totalValueOfProduct = passedPriceArray[id] * quantity;
                //Logic to show/hide the quantity per product if over/under the quantity of 1
                if (x > 1) {
                    var element = document.getElementById("quantityPerPrice" + id);
                    element.classList.remove("hiddenQuantity");
                } else {
                    var element = document.getElementById("quantityPerPrice" + id);
                    element.classList.add("hiddenQuantity");
                }

                //Update the total product price
                document.getElementById("totalPriceOfQuantity" + id).innerHTML = "£" + totalValueOfProduct.toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                // Update the array, at the index of the product id, of every total product page
                totalPriceOfEachProduct[id] = totalValueOfProduct;
                //Now calculate the new total overall price by looping through the totalPriceOfEachProduct array
                var total = 0;
                for (const [key, value] of Object.entries(totalPriceOfEachProduct)) {
                    console.log(key, value);
                    total = total + value
                }

                // Display the total back to the page
                document.getElementById("totalAmount").innerHTML = "£" + total.toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            } else {
                //If other than status OK or it is not ready, then log it to console for IT Technicians to take a look at it if the user contacts us
                var data = xhr.responseText;
                console.log(data);
            }
        }
    }


    function removeProductFromBasket(id) {

        //Make a request to the basket_process.php and send in the value of the id we want to remove
        let xhr = new XMLHttpRequest();
        xhr.open('POST', "basket_process.php", true)
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.send("remove=" + id);


        // On return of the call
        xhr.onreadystatechange = function() {
            //See if it is ready and the status is OK
            if (xhr.readyState == 4 && xhr.status == 200) {
                //If it is, then we decrease the basket count seen at the top and write it to the DOM.
                var basketcount = document.getElementById('basketCount').innerHTML;
                basketcount--;

                //If more than 0, keep it displaying, 

                if (basketcount > 0) {
                    document.getElementById("basketCount").style.display = "inline";
                } else {
                    // if basket count is no longer more than 0, then hide it from the view of the user 
                    document.getElementById("basketCount").style.display = "none";

                    // and also remove the checkout button and total container
                    const removeCheckout = document.getElementById("checkoutDiv");
                    removeCheckout.remove();
                    const removeTotal = document.getElementById("totalContainer");
                    removeTotal.remove();
                    addNoProducts();
                }
                document.getElementById("basketCount").innerHTML = basketcount;

                //Removing the DOM element by getting the element and then the product id that is being removed and fade it out
                removeFadeOut(document.getElementById('individualProduct' + id));
                //Removed value so therefore update the price of that product to 0.
                totalPriceOfEachProduct[id] = 0;

                //Now calculate the new total overall price by looping through the totalPriceOfEachProduct array
                var total = 0;
                for (const [key, value] of Object.entries(totalPriceOfEachProduct)) {
                    console.log(key, value);
                    total = total + value
                }
                // Display the total back to the page
                document.getElementById("totalAmount").innerHTML = "£" + total.toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");

                var data = xhr.responseText;
                console.log(data);
            } else {
                //If other than status OK or it is not ready, then log it to console for IT Technicians to take a look at it if the user contacts us
                var data = xhr.responseText;
                console.log(data);
            }
        }
    }



    //Last product has been removed so therefore show the No Procducts in the basket text. 
    //Do this by creating the header and then adding it to the basketForm
    function addNoProducts() {
        setTimeout(function() {
            const header = document.createElement("h1");

            const node = document.createTextNode("No Products in the basket");
            header.appendChild(node);
            header.setAttribute("id", "NoProducts");

            const addNoProducts = document.getElementById("basketForm");

            addNoProducts.appendChild(header);
        }, 500);
    }

    //Fade out the element that is being passed in by changing the opacity.
    function removeFadeOut(element) {
        element.style.transition = "opacity " + 0.5 + "s ease";

        element.style.opacity = 0;
        setTimeout(function() {
            element.parentNode.removeChild(element);
        }, 0.5);
    }
</script>

</html>