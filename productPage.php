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
                <div id="productImageSliderContainer" class="productImageSliderContainer">
                    <div id="productImageSliderItems">
                        <?php $i = 0;
                        foreach ($productImage as $product) {
                            $i++; ?>
                        <?php
                            $productImagePath = "images/products/" . $productID . "/" . $productImage[$i - 1];
                            echo "<a id='IndividualProductImageSliderItem" . $i . "' class='productImageSliderItem'><img alt='" . $productAltText[$i - 1] . "' src='" . $productImagePath . "' class='productSliderImage'></a>";
                        } ?>
                    </div>
                    <?php
                    $arrLength = count($productImage);
                    if ($arrLength > 1) {
                        echo "<div class='productImageSliderArrows'>
                            <a id='leftArrow' onclick='moveToPreviousImage()'>
                                <img src='images/Home/Right Arrow.svg' alt='Previous Image' />
                            </a>
                            <a id='rightArrow' onclick='moveToNextImage()'>
                                <img src='images/Home/Right Arrow.svg' alt='Next Image' />
                            </a>
                        </div>" ?>
                    <?php } ?>

                    <div class="indicatorDiv">
                        <div class="productImageSliderIndicators">
                            <?php $i = 0;
                            if ($arrLength > 1) {
                            ?>
                                <?php foreach ($productImage as $product) {
                                    $i++;
                                    echo "<a class='productImageSliderIndicatorsButton' id='productImageSliderIndicatorsButton" . $i . "' onclick='goToSlide($i)'></a>" ?>
                            <?php }
                            } ?>

                        </div>
                    </div>
                </div>
            </div>


            <div class="card">
                <div class="cardInformation">
                    <h1 class="productTitle"> <?php echo $productTitle ?></h1>
                    <h1 class="productPrice">£<?php echo $productPrice ?></h2>
                        <h3 class="productFreeDelivery">Free Delivery & Returns</h3>
                </div>
                <form id="addToProductForm" action="/account_welcome.php" method="post" onclick="addToBasket()">
                    <div id="AddtoBasketButtonDiv">
                        <?php
                        $basketItem = 0;
                        if (isset($_SESSION['basket'])) {
                            if (count($_SESSION['basket']) > 0) {
                                if (!empty($_SESSION['basket'][$_GET['productID']]))
                                    $basketItem = $_SESSION['basket'][$_GET['productID']];
                            }
                        }


                        $totalQuantity = $productQuantity - $basketItem;
                        if ($totalQuantity == 0) {
                            // echo "OutOfStock";
                            echo "<button id='AddToBasketButton' type='submit' class='btn btn-success disabled'>
                            <i class='fa fa-shopping-basket'></i><h5 id='basketText'>Out Of Stock</h5></button>";
                        } else if ($basketItem > 9) {
                            // echo "QuantityLimit";
                            echo "<button id='AddToBasketButton' type='submit' class='btn btn-success disabled'>
                            <i class='fa fa-shopping-basket'></i><h5 id='basketText'>Quantity Limit hit</h5></button>";
                        } else {
                            // echo "NormalBasket";
                            echo "<button id='AddToBasketButton' type='submit' class='btn btn-success'>
                            <i class='fa fa-shopping-basket'></i><h5 id='basketText'>Add to Basket</h5></button>";
                        }
                        ?>

                    </div>
                    <p id="errorMessage"></p>
                </form>
            </div>
        </div>



        <div class="descriptionBox">
            <h3 class="productDescriptionTitle">Description of Product</h3>
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
    }

    #errorMessage {
        text-align: center;
        margin-top: 20px;
    }



    .card {
        border-radius: 12.5px;
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

    .productDescription {
        margin-left: 20px;
        margin-right: 20px;
        font-size: 25px;
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

    .btn-success {
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

    #AddtoBasketButtonDiv {
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

    /*

    .card {
        display: inline;
    } */






























    /* HDHJDJDJKDSKDBHI_DJOIWJHDSJAHBUDYH&UDYIWIAD */
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

    .card {
        box-shadow: 0 0 0 5px #FFFFFF;
        border-radius: 2%;
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

    .cardArea {
        display: inline-block;
        width: 100%;
    }

    .cardContainer {
        display: inline-block;
        width: 250px;
    }

    .writingOfCard a {
        font-size: 25px;
        ;
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


    .leftPart {
        float: left;
    }

    .rightPart {
        float: right;
    }


    .indicatorDiv,
    .black {
        position: absolute;
        width: 100%;
        bottom: 10px;
    }

    .productImageSliderIndicators {
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


    #productImageSliderIndicators {
        position: absolute;
        top: 50%;
        cursor: pointer;

    }

    .cardInformation .productTitle,
    .cardInformation .productPrice,
    .cardInformation .productFreeDelivery {
        margin-top: 20px;
        margin-bottom: 20px;
    }
</style>

<script>
    var oldArea;
    var timer;
    var sliderNum = 1;
    var prevSliderNum = 1;
    var picturesLength;
    changeIndictors();
    var timer;

    var productQuantityTimes = 0;
    var productQuantityAfterLoad = 0;
    var productQuantityBeforeLoad = <?php
                                    if (isset($_SESSION['basket'])) {
                                        if (count($_SESSION['basket']) > 0) {
                                            if (!empty($_SESSION['basket'][$_GET['productID']])) {
                                                echo $_SESSION['basket'][$_GET['productID']];
                                            } else {
                                                echo 0;
                                            }
                                        } else {
                                            echo 0;
                                        }
                                    } else {
                                        echo 0;
                                    }
                                    ?>;



    var element = document.getElementById("AddToBasketButton");
    if (element.classList.contains("disabled")) {
        document.getElementById("AddToBasketButton").disabled = true;
    }


    // $('#MainContent').css('padding-bottom', $('footer').height() - 50);
    prevSliderNum = 1;
    picturesLength = <?php echo $arrLength ?>;

    if (picturesLength > 1) {
        changeIndictors()
    }


    let lengthOfSlider = picturesLength * 100;

    let result = lengthOfSlider + "%";

    // console.log(picturesLength)
    // Resolves issue with only one image then get rid of the margin left -100 (no image will be shown after, so don't need a margin left)
    if (picturesLength == 1) {
        document.getElementById("productImageSliderItems").style.marginLeft = 0;
    }
    document.getElementById("productImageSliderItems").style.width = result
    // changeIndictors();





    let content = document.getElementById('productImageSliderItems');
    let firstChild = content.firstElementChild;
    let lastChild = content.lastElementChild;
    firstChild.before(lastChild);

    // console.log(firstChild);

    document.getElementById("productImageSliderItems").style.marginLeft = "-100%";




    function moveToNextImage() {
        prevSliderNum = sliderNum;
        sliderNum++;

        let content = document.getElementById('productImageSliderItems');
        let firstChild = content.firstElementChild;
        let lastChild = content.lastElementChild;
        lastChild.after(firstChild);

        document.getElementById("productImageSliderItems").style.marginLeft = "-100%";

        var picturePath = picturesLength + 1;
        if (sliderNum === picturePath) {
            sliderNum = 1;
        }
        changeIndictors()
    }

    function moveToPreviousImage() {
        prevSliderNum = sliderNum;
        sliderNum--;

        let content = document.getElementById('productImageSliderItems');
        let firstChild = content.firstElementChild;
        let lastChild = content.lastElementChild;
        firstChild.before(lastChild);

        document.getElementById("productImageSliderItems").style.marginLeft = "-100%";

        if (sliderNum === 0) {
            sliderNum = picturesLength;
        }
        changeIndictors()
    }

    function goToSlide(slide) {
        var currentSlide = sliderNum;

        var wantToGoTo = slide
        var slidesToGoNextTo;


        if (wantToGoTo > currentSlide) {
            slidesToGoNextTo = wantToGoTo - currentSlide;
            console.log("slidesToGoNextTo" + slidesToGoNextTo);

            for (let i = 0; i < slidesToGoNextTo; i++) {
                moveToNextImage();
            }
        } else if (currentSlide > wantToGoTo) {
            slidesToGoNextTo = currentSlide - wantToGoTo;
            console.log("slidesToGoNextTo" + slidesToGoNextTo);
            for (let i = 0; i < slidesToGoNextTo; i++) {
                moveToPreviousImage();
            }
        }
    }

    function changeIndictors() {
        var previousIndictor = document.getElementById("productImageSliderIndicatorsButton" + prevSliderNum);
        // console.log(previousIndictor);
        previousIndictor.style.backgroundColor = "#1a1862";

        var sliderIndictors = document.getElementById("productImageSliderIndicatorsButton" + sliderNum);
        // console.log(sliderIndictors);
        sliderIndictors.style.backgroundColor = "red";
    }
    var productFromDB = <?php echo $productQuantity ?>;

    function addToBasket() {
        event.preventDefault();

        if (document.getElementById('AddToBasketButton').disabled == false) {
            var productID = "<?php echo $productID ?>";
            console.log(productID);
            let xhr = new XMLHttpRequest();

            xhr.open('POST', "basket_process.php", true)
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhr.send("product_id=" + productID + "&quantity=" + 1);

            // Create an event to receive the return.
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    document.getElementById("errorMessage").style.display = "block";

                    document.getElementById('errorMessage').innerHTML = "Added to basket"

                    document.getElementById('errorMessage').style.opacity = "1";
                    // document.getElementById('errorMessage').style.removeProperty("opacity");
                    document.getElementById('errorMessage').style.filter = "alpha(opacity='1')";
                    setTimeout(function() {
                        fade(document.getElementById('errorMessage'));
                    }, 2000);

                    var result = JSON.parse(xhr.responseText);

                    if (result == "NewItem") {
                        var basketcount = document.getElementById('basketCount').innerHTML;
                        basketcount++;
                        if (basketcount > 0) {
                            document.getElementById("basketCount").style.display = "inline";
                        } else {
                            document.getElementById("basketCount").style.display = "none";
                        }
                        document.getElementById("basketCount").innerHTML = basketcount;
                    }

                    productQuantityAfterLoad++;

                    var productQuantityTotal = productQuantityBeforeLoad + productQuantityAfterLoad;


                    if (productQuantityTotal === 10) {
                        console.log("limit");
                        var element = document.getElementById("AddToBasketButton");
                        element.classList.add("disabled");
                        document.getElementById('basketText').innerHTML = "Quantity Limit hit";
                        document.getElementById("AddToBasketButton").disabled = true;

                    } else if (productFromDB === productQuantityTotal) {
                        console.log("NowOutOfStock");
                        var element = document.getElementById("AddToBasketButton");
                        element.classList.add("disabled");
                        document.getElementById('basketText').innerHTML = "Out Of Stock";
                        document.getElementById("AddToBasketButton").disabled = true;

                    } else {
                        console.log("Fine");
                    }
                }
            }
        }
    }


    function fade(element) {
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
</script>