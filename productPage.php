<?php
// If the session hasn't started. Start it (can then use session variables)
if (!isset($_SESSION)) {
    @ob_start();
    @session_start();
}

//Include the Login Details to access the DB
include 'DatabaseLoginDetails.php';

//Connect to the database
$conn = mysqli_connect($host, $user, $pass, $database);

// Check connection and stop if there is an error
if (!$conn) {
    echo 'Connection error: ' . mysqli_connect_error();
}

// Validation to prevent dirty data
if (!is_numeric($_GET['productID'])) {
    header('Location: Error404.php');
}

//Prepare the statement - query to select the product information (Product Title)
$stmt = $conn->prepare("SELECT p.productID, p.productTitle, 
p.productDescription, p.productPrice, p.productTotalQuantity, pi.productImageFilename, pi.productImageAltText, pi.displayOrder
        FROM product 
AS p RIGHT JOIN product_image pi
 ON pi.productID = p.productID where p.productID = ?");

// Variable product ID is equal to the product ID variable being sent in via the GET method
$productID = $_GET['productID'];

// Bind the product ID to the prepared statement
$stmt->bind_param("i", $productID);

// Execute the query
if (!$stmt->execute()) {
    //Couldn't execute query so stop there
    header('HTTP/1.1 500 Internal Server Error');
    header('Content-Type: application/json; charset=UTF-8');
    die(json_encode('ERROR - Could not get the product details for the item'));
}

// Get the result back and put it in the productInfo variable
$result = $stmt->get_result();
$productInfo = mysqli_fetch_all($result, MYSQLI_ASSOC);

// If there is no results for the product ID that has been sent by the get method, then redirect to the Error404 page
if (empty($productInfo)) {
    header('Location: Error404.php');
} else {
    // If there is a/multiple results then declare new variables to put the information in
    $productTitle;
    $productDescription;
    $productDescriptionCorrect;
    $productPrice;
    $productQuantity = 0;
    $productImage = [];
    $productAltText = [];


    $i = 0;
    // For every result that has come back from the DB (can have multiple due to having multiple product images)
    foreach ($productInfo as $product) {
        // If it is the first time coming into the for loop, then set the product title, description, quantity and price
        if ($i == 0) {
            $productTitle = $product['productTitle'];
            $productDescription = $product['productDescription'];

            $productDescriptionCorrect = str_replace("\\n", "\n", $productDescription);
            $productQuantity = $product['productTotalQuantity'];
            $productPrice = $product['productPrice'];
        }
        // For every result in the database, then add the filename of the product image and the alternative text to an array
        // This can be improved by using an object array instead but time pressures
        array_push($productImage, $product['productImageFilename']);
        array_push($productAltText, $product['productImageAltText']);
        // Increase the index
        $i++;
    }
}

//Free  memory and close the connection
mysqli_free_result($result);
mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <!-- Put a viewport on this page -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Make a dynamic title of the page - It will be the product Title and then what website it is -->
    <title><?php echo $productTitle; ?> - Gadget Gainey Store </title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Keywords of the site for search engine optimisation -->
    <meta name="keywords" content="Gadget Gainey, Gadget, Ecommerce, Online, Shop, Kids Toys, Toys, Technology, Gainey, Ashley Gainey">
    <!-- Author of the site -->
    <meta name="author" content="Ashley Gainey">
    <!-- Make a dynamic description of the page for SEO (Search Engine descriptions of the page), this dynamic description will be 100 words of the description of the product (with an ellipsis at the end)-->
    <meta name="description" <?php
                                $productDescriptionForMeta = str_replace("\n", " ", $productDescription);
                                $productDescriptionForMeta = str_replace('"', '', $productDescriptionForMeta);

                                $productDescriptionForMeta = implode(' ', array_slice(explode(' ', $productDescriptionForMeta), 0, 100));
                                $productDescriptionForMeta = $productDescriptionForMeta . "...";

                                echo "content='" . $productTitle . " - " . $productDescriptionForMeta . "'" ?>>
    <!-- Link to the shared classes and IDs style sheet -->
    <link rel="stylesheet" type="text/css" href="sharedStyles.css">
</head>

<body>
    <!-- Add the header at the top before any other material -->
    <?php include "./header.php" ?>
    <div id="bodyOfPage">
        <div class="firstRow">
            <div>
                <!-- Product Image Slider Div -->
                <div id="productImageSliderContainer" class="productImageSliderContainer">
                    <!-- Div to hold the images-->
                    <div id="productImageSliderItems">
                        <?php $i = 0;
                        // For every item in Picture Image array (number is dynamic)
                        foreach ($productImage as $product) {
                            $i++;
                            // Individual Image, ID will be the current index of the for loop.
                            // It will get the image from the product images folder and then the current Product ID, concat with / and the filename that is stored in the array
                            //and the alternative text of the image is from the database/array too
                            $productImagePath = "images/products/" . $productID . "/" . $productImage[$i - 1];
                            echo "<a id='IndividualProductImageSliderItem" . $i . "' class='productImageSliderItem'><img alt='" . $productAltText[$i - 1] . "' src='" . $productImagePath . "' class='productSliderImage'></a>";
                        } ?>
                    </div>
                    <?php
                    // If there is more than one image then make the navigation buttons
                    $arrLength = count($productImage);
                    if ($arrLength > 1) {
                        // Navigating Buttons, left and right
                        echo "<div class='productImageSliderArrows'>
                            <a id='leftArrow' onclick='moveToPreviousImage()'>
                                <img src='images/Home/Right Arrow.svg' alt='Previous Image' />
                            </a>
                            <a id='rightArrow' onclick='moveToNextImage()'>
                                <img src='images/Home/Right Arrow.svg' alt='Next Image' />
                            </a>
                        </div>" ?>
                    <?php } ?>

                    <!-- Navigating Buttons, selecting which product image they want to go to, on click they go to the JS method called goToProductImageSlider and pass in the current product image number -->
                    <div class="indicatorDiv">
                        <div class="productImageSliderIndicators">
                            <?php $i = 0;
                            // If there is more than one image then make the indicators
                            if ($arrLength > 1) {
                                foreach ($productImage as $product) {
                                    $i++;
                                    echo "<a class='productImageSliderIndicatorsButton' id='productImageSliderIndicatorsButton" . $i . "' onclick='goToProductImageSlider($i)'></a>";
                                }
                            } ?>
                        </div>
                    </div>
                </div>
            </div>


            <!-- Container to hold the Title, Price, the Free Delivery and the Add to Basket button -->
            <div class="card">
                <!-- Container that holds the title, price and free delivery -->
                <div class="cardInformation">
                    <h1 class="productTitle"> <?php echo $productTitle ?></h1>
                    <h1 class="productPrice">£<?php echo $productPrice ?></h2>
                        <h3 class="productFreeDelivery">Free Delivery & Returns</h3>
                </div>
                <!-- Then a form to pass a request to the back end when the user clicks on the Add to Basket button -->
                <form id="addToProductForm" action="" method="post" onclick="addToBasket()">
                    <div id="addToBasketButtonDiv">
                        <?php
                        // Check to see if this product is in the basket and add the quantity to a variable
                        $basketItem = 0;
                        if (isset($_SESSION['basket'])) {
                            if (count($_SESSION['basket']) > 0) {
                                if (!empty($_SESSION['basket'][$_GET['productID']]))
                                    $basketItem = $_SESSION['basket'][$_GET['productID']];
                            }
                        }

                        // The total inventory (theorically - hasn't been ordered yet) is the inventory now take away the current quantity of the product in the basket
                        $totalQuantity = $productQuantity - $basketItem;

                        // And from there, we can use that logic to make the 'Add to basket' button
                        // If the total inventory after factoring in the existing basket quantity is 0...
                        if ($totalQuantity == 0) {
                            // ...then it means it is out of stock and therefore, disable the button and that is show out of stock
                            echo "<button id='AddToBasketButton' type='submit' class='disabled'>
                            <i class='fa fa-shopping-basket'></i><h5 id='basketText'>Out Of Stock</h5></button>";
                            // If the quantity of the product in the basket is 10...
                        } else if ($basketItem > 9) {
                            //...then the user has reached the limit that they can buy of this item and therefore disable the button 
                            // and that they have reached their limit
                            echo "<button id='AddToBasketButton' type='submit' class='disabled'>
                            <i class='fa fa-shopping-basket'></i><h5 id='basketText'>Quantity Limit hit</h5></button>";
                        } else {
                            // If anything else, it is in stock and can show the normal, Add to Basket button
                            echo "<button id='AddToBasketButton' type='submit' class='enabled'>
                            <i class='fa fa-shopping-basket'></i><h5 id='basketText'>Add to Basket</h5></button>";
                        }
                        ?>
                    </div>
                    <!-- Make text that will display any errors/success message if there are any -->
                    <p id="errorMessage"></p>
                </form>
            </div>
        </div>



        <!-- Div Container that holds the description -->
        <div class="descriptionBox">
            <!-- Title for the Description -->
            <h3 class="productDescriptionTitle">Description of Product</h3>
            <!-- Preformatted tag that converts the non formatted description (like a new line) to formatted text -->
            <?php echo '<pre class="productDescription">' . str_replace('\n', "\n", $productDescriptionCorrect) . '</pre>'; ?>
        </div>
    </div>
    <!-- Add the footer at the bottom after any other material -->
    <?php include "./footer.php" ?>
</body>

</html>
<style>
    .productPrice {
        text-align: center;
    }

    .productFreeDelivery {
        text-align: center;
    }

    .productDescription {
        font-family: Century-Gothic, sans-serif;
        word-break: keep-all;
        white-space: pre-wrap;

        margin-left: 20px;
        margin-right: 20px;
        font-size: 25px;
    }

    #errorMessage {
        text-align: center;
        margin-top: 20px;
    }

    .card {
        background-color: #FFFFFF;
        color: #000000;
        /*margin-top: 10px;*/
        word-break: break-all;
        display: inline-block;
        margin-left: 20px;
        margin-right: 20px;
        margin-top: 20px;
        margin-bottom: px;



        padding-left: 5px;
        padding-right: 5px;
        padding-top: 15px;
        padding-bottom: 15px;

        width: 40%;
        text-align: left;
        min-height: 200px;
        word-break: keep-all;

        box-shadow: 0 0 0 5px #FFFFFF;
        border-radius: 2%;
    }

    .descriptionBox {
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


    .productDescriptionTitle {
        margin-left: 10px;
        margin-right: 10px;
        margin-bottom: 10px;
        font-size: 35px;
    }

    #AddToBasketButton {
        padding: 10px;
        width: 200px;
        border: none;

        color: white;
        font-size: 20px;
        font-weight: bold;
        border-radius: 10px;
        text-align: center;
        margin: 0 auto;
    }

    .enabled {
        cursor: pointer;
        background-color: #1a1862;
    }

    .disabled {
        background-color: #333333;
        cursor: not-allowed;
    }

    #AddToBasketButton i {
        font-size: 5em;
        padding-bottom: 10px;
        color: #FFFFFF;
        display: block;
    }

    #addToBasketButtonDiv {
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .productTitle {
        margin-left: 20px;
    }


    .firstRow {
        display: flex;
    }


    .productImageSliderIndicatorsButton {
        background-color: #1a1862;
        border: 5px solid #FFFFFF;
        color: white;
        padding: 10px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 16px;
        margin: 4px 2px;
        cursor: pointer;
        border-radius: 50%;
    }


    .productImageSliderContainer {
        margin: auto;
        position: relative;
        overflow: hidden;
        box-shadow: 0 0 0 5px #FFFFFF;
        border-radius: 2%;
        max-height: 600px;
        max-width: 600px;
    }


    #productImageSliderItems {
        display: flex;
    }

    .productImageSliderItem {
        width: 100%;
    }

    .productSliderImage {
        display: block;
        width: 100%;
        display: block;
        width: 100%;
        height: auto;
    }

    .productImageSliderArrows a {
        position: absolute;
        top: 50%;
        cursor: pointer;
    }

    .productImageSliderArrows a:hover,
    .productImageSliderArrows a:hover {
        opacity: 50%;
    }


    .indicatorDiv {
        position: absolute;
        width: 100%;
        bottom: 10px;
    }

    .productImageSliderIndicators {
        position: absolute;
        top: 50%;
        cursor: pointer;
        text-align: center;
    }

    .productImageSliderIndicators a {
        top: 50%;
        cursor: pointer;
    }

    .productImageSliderIndicators a:hover,
    .productImageSliderIndicators a:hover {
        opacity: 50%;

    }

    #leftArrow {
        left: 5px;
        transform: rotate(180deg) translateY(50%);
    }

    #rightArrow {
        right: 5px;
        transform: translateY(-50%);
    }

    .cardInformation .productTitle,
    .cardInformation .productPrice,
    .cardInformation .productFreeDelivery {
        margin-top: 20px;
        margin-bottom: 20px;
    }
</style>

<script>
    // Declare variables to see where the product image slider is at and where it was previously
    var currentProductImageNum = 1;
    var prevcurrentProductImageNum = 1;

    // Variable to store the amount of images that are tied to the product
    var picturesLength = <?php echo $arrLength ?>;;

    // Get the inventory from the database and put it in a variable
    // There needs to be a better way of doing this (what about security??? The users can see how many are in the DB)
    var productFromDB = <?php echo $productQuantity ?>;


    // If there is more than one image, change the indicator colours on the product image slider
    if (picturesLength > 1) {
        changeIndicators()
    }

    // Variable to track how many items have been bought while on this page
    var productQuantityAfterLoad = 0;
    // Get the current quantity (on first load of this page) of the product that the user has bought (from the basket)
    var productQuantityBeforeLoad =
        <?php
        // If the basket has been initalised
        if (isset($_SESSION['basket'])) {
            // If there are any items in the basket
            if (count($_SESSION['basket']) > 0) {
                // If the product is found in the basket variable in the session...
                if (!empty($_SESSION['basket'][$_GET['productID']])) {
                    //...then return the product quantity the user has currently in their basket
                    echo $_SESSION['basket'][$_GET['productID']];
                } else {
                    // If the item is not in the basket, then return 0
                    echo 0;
                }
            } else {
                // If there are no items in the basket, then return 0
                echo 0;
            }
        } else {
            // If the basket is not set, then return 0
            echo 0;
        }
        ?>;

    // If the Add to Basket button has a class of disabled on it, on first load, then disable the button
    var element = document.getElementById("AddToBasketButton");
    if (element.classList.contains("disabled")) {
        element.disabled = true;
    }

    // Move the last image to the first place
    let content = document.getElementById('productImageSliderItems');
    let firstChild = content.firstElementChild;
    let lastChild = content.lastElementChild;
    firstChild.before(lastChild);

    // Dynamically calculate the width that the product image slider should be, by getting the length and multiplying it by 100 and putting a percentage on the end
    let widthOfProductImageSlider = (picturesLength * 100) + "%";
    content.style.width = widthOfProductImageSlider;

    // If there is only 1 image...
    if (picturesLength == 1) {
        //..Then we don't want to have an offset of -100%... instead margin left is just 0
        content.style.marginLeft = 0;
    } else {
        // if there is more than one image, have an offset of -100 to show the "first", actually second, element
        content.style.marginLeft = "-100%";
    }

    // Move to the next product image
    function moveToNextImage() {
        // Change the last product image number to the current product image number (before moving it)
        prevcurrentProductImageNum = currentProductImageNum;
        // Increment the current product image number (telling it that we are now on the current image)
        currentProductImageNum++;
        // The total count of how many product images there are is the length of the pictures plus 1
        productImageCount = picturesLength + 1;

        // Move the first image to the last place
        let content = document.getElementById('productImageSliderItems');
        let firstChild = content.firstElementChild;
        let lastChild = content.lastElementChild;
        lastChild.after(firstChild);

        // And have an offset of -100 to show the "first", actually second, element
        content.style.marginLeft = "-100%";

        // If the current product image number is equal to the amount of pictures that the product has then loop back around to 1 (first picture item in array)
        if (currentProductImageNum === productImageCount) {
            currentProductImageNum = 1;
        }
        // Change the Product Image indicator Buttons 
        changeIndicators()
    }

    // Move to the previous product image
    function moveToPreviousImage() {
        // Change the last product image number to the current product image number (before moving it)
        prevcurrentProductImageNum = currentProductImageNum;
        // Decrement the current product image number (telling it that we are now on the previous image)
        currentProductImageNum--;

        // move the last image to the first place
        let content = document.getElementById('productImageSliderItems');
        let firstChild = content.firstElementChild;
        let lastChild = content.lastElementChild;
        firstChild.before(lastChild);

        // And have an offset of -100 to show the "first", actually second, element
        content.style.marginLeft = "-100%";

        // If the current product Image Number variable is 0, then loop back around to the amount of pictures there are for the product
        if (currentProductImageNum === 0) {
            currentProductImageNum = picturesLength;
        }
        // Change the Product Image indicator Buttons
        changeIndicators()
    }

    // Specifically say which product image you want to go to (it will loop the moveToNextImage method)
    function goToProductImageSlider(slide) {
        var currentSlide = currentProductImageNum;

        var wantToGoTo = slide
        var slideToGoNextTo;

        // Determined which way to use, either going previous or going next depending on which one is nearer to the target
        if (wantToGoTo > currentSlide) {
            slideToGoNextTo = wantToGoTo - currentSlide;
            for (let i = 0; i < slideToGoNextTo; i++) {
                moveToNextImage();
            }
        } else if (currentSlide > wantToGoTo) {
            slideToGoNextTo = currentSlide - wantToGoTo;
            for (let i = 0; i < slideToGoNextTo; i++) {
                moveToPreviousImage();
            }
        }
    }

    // Change the indicator colour
    function changeIndicators() {
        // Change the previous indicator back to normal blue colour
        var previousIndicator = document.getElementById("productImageSliderIndicatorsButton" + prevcurrentProductImageNum);
        previousIndicator.style.backgroundColor = "#1a1862";

        // Change the current indicator to red colour
        var nextIndicator = document.getElementById("productImageSliderIndicatorsButton" + currentProductImageNum);
        nextIndicator.style.backgroundColor = "red";
    }


    function addToBasket() {
        // Prevent the form from being submitted by the default action
        event.preventDefault();

        // If the Add to Basket button is disabled then don't do this
        if (document.getElementById('AddToBasketButton').disabled == false) {
            // Get the product ID of the current product
            var productID = "<?php echo $productID ?>";

            //Then make a request to the basket_process.php and send in the Product ID and the quantity of 1 (they are buying 1 product quantity at a time)
            let xhr = new XMLHttpRequest();
            xhr.open('POST', "basket_process.php", true)
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhr.send("product_id=" + productID + "&quantity=" + 1);

            // On return of the call
            xhr.onreadystatechange = function() {
                //See if it is ready and the status is OK
                if (xhr.readyState == 4 && xhr.status == 200) {
                    //If it is, then show the errorMessage to actually display a success message, that it has been added to Basket
                    document.getElementById("errorMessage").style.display = "block";
                    document.getElementById('errorMessage').innerHTML = "Added to basket"
                    document.getElementById('errorMessage').style.opacity = "1";
                    document.getElementById('errorMessage').style.filter = "alpha(opacity='1')";
                    // Then fade the message out after 2 seconds
                    setTimeout(function() {
                        fadeOut(document.getElementById('errorMessage'));
                    }, 2000);

                    // If the message coming back from the php file says that it is a New Item that has been added to the basket (and not just updating the quantity of a product)
                    var result = JSON.parse(xhr.responseText);
                    if (result == "NewItem") {
                        // then we want to increase the basket count from x to x+1.
                        var basketCountItem = document.getElementById('basketCount');
                        var basketcount = basketCountItem.innerHTML;
                        basketcount++;
                        // Add show the Basket Count Item (it won't be show if the previous value was 0)
                        basketCountItem.style.display = "inline";
                        // Set the increased basket count back to the item.
                        basketCountItem.innerHTML = basketcount;
                    }

                    // The Product's quantity in the basket (after this page has loaded) has been increased, so increase the variable
                    productQuantityAfterLoad++;

                    // Work out the total quantity that is going to be bought (/that is in the basket) by adding the before and after load variables together
                    var productQuantityTotal = productQuantityBeforeLoad + productQuantityAfterLoad;


                    // If that value is the maximum that the user can buy (they can only buy a max of 10 of the same product)
                    if (productQuantityTotal === 10) {
                        // Then change the Add to basket button to disable it and change the Basket Text to provide feedback to the user that they have reached the quantity limit of this product
                        var element = document.getElementById("AddToBasketButton");
                        element.classList.add("disabled");
                        element.classList.remove("enabled");
                        element.disabled = true;
                        document.getElementById('basketText').innerHTML = "Quantity Limit hit";
                        // If the total quantity the user has bought is equal to how many are in the database
                    } else if (productFromDB === productQuantityTotal) {
                        // Then change the Add to basket button to disable it and change the Basket Text to provide feedback to the user that after buying that product to that quantity, there is no more in stock.
                        var element = document.getElementById("AddToBasketButton");
                        element.classList.add("disabled");
                        element.classList.remove("enabled");
                        element.disabled = true;
                        document.getElementById('basketText').innerHTML = "Out Of Stock";
                    }
                    // If anything else, then it is fine and the basket button can remain the same
                }
            }
        }
    }

    // Fades out the error message element
    function fadeOut(element) {
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
</script>