<!DOCTYPE html>
<html lang="en">
<?php
include 'DBlogin.php';

$conn = mysqli_connect($host, $user, $pass, $database);

if (!$conn) {
    echo 'Connection error: ' . mysqli_connect_error();
}


$query =
    'SELECT p.productID, p.productTitle, 
p.productDescription, p.productPrice, p.productTotalQuantity, pi.productImageFilename, pi.productImageAltText, pi.displayOrder
        FROM product 
AS p RIGHT JOIN product_image pi
 ON pi.productID = p.productID where p.productID = ' . $_GET['productID'];

$result = mysqli_query($conn, $query);

$productInfo = mysqli_fetch_all($result, MYSQLI_ASSOC);

// print_r($productInfo);

$invalid = 0;
if (empty($productInfo)) {
    $invalid = 1;
    header('Location: Error404.php');
} else {
    $productTitle;
    $productDescription;
    $productDescriptionCorrect;
    $productPrice;
    $productQuantity = 0;
    $productImage = [];
    $productAltText = [];


    $i = 0;
    foreach ($productInfo as $product) {
        if ($i == 0) {
            $productID = $product['productID'];
            $productTitle = $product['productTitle'];
            $productDescription = $product['productDescription'];

            $productDescriptionCorrect = str_replace("\\n", "\n", $productDescription);
            $productQuantity = $product['productTotalQuantity'];
            $productPrice = $product['productPrice'];
        }

        array_push($productImage, $product['productImageFilename']);
        array_push($productAltText, $product['productImageAltText']);

        // array_push($productImage, (object)[
        //     $product['displayOrder'] => $product['productImageFilename']
        // ]);
        // array_push($productAltText, (object)[
        //     $product['displayOrder'] => $product['productImageAltText']
        // ]);
        $i++;
    }
}

//Free  memory and close the connection
mysqli_free_result($result);
mysqli_close($conn)
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="style.css">
    <title><?php echo $productTitle; ?> - Gadget Gainey Store </title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="keywords" content="Gadget Gainey, Gadget, Ecommerce, Online, Shop, Kids Toys, Toys, Technology, Gainey, Ashley Gainey">
    <meta name="author" content="Ashley Gainey">

    <meta name="description" <?php
                                $productDescriptionForMeta = str_replace("\n", " ", $productDescription);
                                $productDescriptionForMeta = str_replace('"', '', $productDescriptionForMeta);

                                $productDescriptionForMeta = implode(' ', array_slice(explode(' ', $productDescriptionForMeta), 0, 100));
                                $productDescriptionForMeta = $productDescriptionForMeta . "...";
    
    echo "content='" . $productTitle . " - " . $productDescriptionForMeta . "'" ?>>


    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

</head>

<body>
    <?php include "./header.php" ?>

    <div id="mainBody">
        <div class="row">
            <div class="carousel">
                <div class="row visible-md visible-lg">
                    <div id="contenedor-slider" class="contenedor-slider">
                        <div id="slider">
                            <?php $i = 0;
                            foreach ($productImage as $product) {
                                $i++; ?>
                                <?php
                                $productImagePath = "images/products/" . $productID . "/" . $productImage[$i - 1];


                                echo "<a id='slider" . $i . "' class='slider__section'><img alt='" . $productAltText[$i - 1] . "' src='" . $productImagePath . "' class='slider__img'></a>" ?>

                            <?php } ?>
                            </a>
                        </div>
                        <?php
                        $arrLength = count($productImage);
                        if ($arrLength > 1) {
                            echo "<div class='sliderArrows'>
                            <a id='left' id='left' onclick='left()'>
                                <img src='images/Home/Right Arrow.svg' alt='Next Slide' />
                            </a>
                            <a id='right' id='right' onclick='right()'>
                                <img src='images/Home/Right Arrow.svg' alt='Previous Slide' />
                            </a>
                        </div>" ?>
                        <?php } ?>

                        <div class="indicatorDiv">
                            <div class="sliderIndicators">
                                <?php $i = 0;
                                if ($arrLength > 1) {
                                ?>

                                    <?php foreach ($productImage as $product) {
                                        $i++;
                                        echo "<a class='button' id='sliderIndicatorsButton" . $i . "' onclick='goToSlide($i)'></a>" ?>
                                <?php }
                                } ?>

                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="card NunitoFont col-6">
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
                    <p id="regMessage"></p>
                </form>
            </div>
        </div>



        <div class="descriptionBox">
            <h3 class="productDescriptionTitle">Description of Product</h3>
            <?php echo '<pre class="productDescription">' . str_replace('\n', "\n", $productDescriptionCorrect) . '</pre>'; ?>
        </div>
    </div>
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

    #regMessage {
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


    .row {
        display: flex;
    }


    .carousel {
        display: inline;
    }

    /*

    .card {
        display: inline;
    } */






























    /* HDHJDJDJKDSKDBHI_DJOIWJHDSJAHBUDYH&UDYIWIAD */
    .button {
        background-color: #1a1862;
        /* Green */
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


    .carousel {
        /* margin-bottom: 50px; */

    }

    .card {
        box-shadow: 0 0 0 5px #FFFFFF;
        border-radius: 2%;
        /* height: 125px;
        width: 250px;
        overflow: hidden; */


    }


    .contenedor-slider {
        margin: auto;
        position: relative;
        overflow: hidden;
        box-shadow: 0 0 0 5px #FFFFFF;
        border-radius: 2%;
        max-height: 600px;
        max-width: 600px;
    }


    #slider {
        display: flex;
    }

    .slider__section {
        width: 100%;
    }



    .slider__img {
        display: block;
        width: 100%;
        display: block;
        width: 100%;
        height: auto;
    }

    .sliderArrows a {
        position: absolute;
        top: 50%;
        cursor: pointer;
    }

    .sliderArrows a:hover,
    .sliderArrows a:hover {
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

    .sliderIndicators {
        text-align: center;
    }

    .sliderIndicators a {
        top: 50%;
        cursor: pointer;
    }

    .sliderIndicators a:hover,
    .sliderIndicators a:hover {
        opacity: 50%;

    }

    #left {
        left: 5px;
        transform: rotate(180deg) translateY(50%);
    }

    #right {
        right: 5px;
        transform: translateY(-50%);
    }


    /* Add a margin to the main_container that is inside the main body, this is because we 
want something up against the Nav (for instance the breadcrumb/the carousel)*/
    #mainBody {
        margin-top: 30px;
        margin-left: 50px;
        margin-right: 50px;

        /* Was having an issue if I typed more than expected for the search, then it would destroy the padding 
	so have added word-wrap, this should apply to the main_container, no matter whether it is a heading 
	(h1, h2, h3 etc.), paragraph (p) or something other */
        word-wrap: break-word;

    }

    #sliderIndicators {
        position: absolute;
        top: 50%;
        cursor: pointer;

    }


    .button {
        /* margin-left: 10px;
        margin-right: 10px; */
    }


    #sliderIndicators .button {
        position: absolute;
        top: 50%;
        cursor: pointer;
        margin-right: 5px;
    }

    #sliderIndicators button:hover,
    #sliderIndicators button:hover {
        opacity: 50%;

    }

    #first {
        left: 5px;
        transform: rotate(180deg) translateY(50%);
    }

    #right {
        right: 5px;
        transform: translateY(-50%);
    }


    /* Old Traingle method

        .triangle {
            position: relative;
            background-color: #1a1862;
            text-align: left;
        }

        .triangle:before,
        .triangle:after {
            content: '';
            position: absolute;
            background-color: inherit;
        }

        .triangle,
        .triangle:before,
        .triangle:after {
            width: 2em;
            height: 2em;
            border-top-right-radius: 50%;
            border-right: 4px solid white;
            border-top: 4px solid white;

        }

        .triangle {
            transform: rotate(-90deg) skewX(-30deg) scale(1, .866);
        }

        .triangle:before {
            transform: rotate(-135deg) skewX(-45deg) scale(1.414, .707) translate(0, -50%);
        }

        .triangle:after {
            transform: rotate(135deg) skewY(-45deg) scale(.707, 1.414) translate(50%);
        } */




    .cardInformation .productTitle,
    .cardInformation .productPrice,
    .cardInformation .productFreeDelivery {
        margin-top: 20px;
        margin-bottom: 20px;
    }






    #first {
        background-color: #1a1862;
        border-radius: 50%;
        width: 500px;
    }

    #mainBody {
        margin: 50px;
    }

    .black:after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(to bottom, transparent 0%, black 100%);
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
        document.getElementById("slider").style.marginLeft = 0;
    }
    document.getElementById("slider").style.width = result
    // changeIndictors();




    var slider = document.getElementById("slider");

    // debugger;
    let content = document.getElementById('slider');
    let firstChild = content.firstElementChild;
    let lastChild = content.lastElementChild;
    firstChild.before(lastChild);

    // // debugger;
    // console.log(firstChild);





    // move the last image to the first place
    // $('#slider .slider__section:last').insertBefore('#slider .slider__section:first');
    // display the first image with a margin of -100%
    // slider.css('margin-left', '-' + 100 + '%');
    document.getElementById("slider").style.marginLeft = "-100%";




    function moveNext() {
        prevSliderNum = sliderNum;
        sliderNum++;

        let content = document.getElementById('slider');
        let firstChild = content.firstElementChild;
        let lastChild = content.lastElementChild;
        // debugger;
        lastChild.after(firstChild);

        document.getElementById("slider").style.marginLeft = "-100%";

        var picturePath = picturesLength + 1;
        if (sliderNum === picturePath) {
            sliderNum = 1;
        }
        changeIndictors()
    }

    function movePrev() {
        prevSliderNum = sliderNum;
        sliderNum--;

        let content = document.getElementById('slider');
        let firstChild = content.firstElementChild;
        let lastChild = content.lastElementChild;
        // debugger;
        firstChild.before(lastChild);

        document.getElementById("slider").style.marginLeft = "-100%";

        if (sliderNum === 0) {
            sliderNum = picturesLength;
        }
        changeIndictors()
    }

    function left() {
        movePrev();
        // console.log("sliderNum" + sliderNum);
    }

    function right() {
        moveNext();
        // console.log("sliderNum" + sliderNum);
    }




    function goToSlide(slide) {
        var currentSlide = sliderNum;

        var wantToGoTo = slide
        var slidesToGoNextTo;


        if (wantToGoTo > currentSlide) {
            slidesToGoNextTo = wantToGoTo - currentSlide;
            console.log("slidesToGoNextTo" + slidesToGoNextTo);

            for (let i = 0; i < slidesToGoNextTo; i++) {
                moveNext();
            }
        } else if (currentSlide > wantToGoTo) {
            slidesToGoNextTo = currentSlide - wantToGoTo;
            console.log("slidesToGoNextTo" + slidesToGoNextTo);
            for (let i = 0; i < slidesToGoNextTo; i++) {
                movePrev();
            }
        }
    }

    function changeIndictors() {
        // debugger;
        var previousIndictor = document.getElementById("sliderIndicatorsButton" + prevSliderNum);
        // console.log(previousIndictor);
        // debugger;
        previousIndictor.style.backgroundColor = "#1a1862";

        var sliderIndictors = document.getElementById("sliderIndicatorsButton" + sliderNum);
        // debugger;
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
            debugger;

            // Create an event to receive the return.
            xhr.onreadystatechange = function() {
                debugger;
                if (xhr.readyState == 4 && xhr.status == 200) {
                    document.getElementById("regMessage").style.display = "block";

                    document.getElementById('regMessage').innerHTML = "Added to basket"

                    document.getElementById('regMessage').style.opacity = "1";
                    // document.getElementById('regMessage').style.removeProperty("opacity");
                    document.getElementById('regMessage').style.filter = "alpha(opacity='1')";
                    setTimeout(function() {
                        fade(document.getElementById('regMessage'));
                    }, 2000);

                    var result = JSON.parse(xhr.responseText);

                    debugger;
                    if (result == "NewItem") {
                        debugger;
                        var basketcount = document.getElementById('cartCount').innerHTML;
                        basketcount++;
                        debugger;
                        if (basketcount > 0) {
                            document.getElementById("cartCount").style.display = "inline";
                        } else {
                            document.getElementById("cartCount").style.display = "none";
                        }
                        document.getElementById("cartCount").innerHTML = basketcount;
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