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
p.productDescription, p.productPrice, pi.productImageFilename, pi.productImageAltText, pi.displayOrder
        FROM product 
AS p RIGHT JOIN product_image pi
 ON pi.productID = p.productID where p.productID = ' . $_GET['productID'];

$result = mysqli_query($conn, $query);

$productInfo = mysqli_fetch_all($result, MYSQLI_ASSOC);

print_r($productInfo);

$invalid = 0;
if (empty($productInfo)) {
    $invalid = 1;
    header('Location: Error404.php');
} else {
    $productTitle;
    $productDescription;
    $productDescriptionCorrect;
    $productPrice;

    $productImage = [];
    $productAltText = [];


    $i = 0;
    foreach ($productInfo as $product) {
        if ($i == 0) {
            $productID = $product['productID'];
            $productTitle = $product['productTitle'];
            $productDescription = $product['productDescription'];
            $productDescriptionCorrect = str_replace("\\n", "\n", $productDescription);

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
    <title>
        <?php echo $productTitle; ?> - Gadget Gainey Store </title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <!--    Offline use -->
    <script src="jquery-3.4.1.min.js"></script>
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
                            <a id='left'>
                                <img src='images/Home/Right Arrow.svg' alt='Next Slide' />
                            </a>
                            <a id='right'>
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
                <form class="form-item" method="post" action="?action=add&id=<?php echo $row["id"]; ?>">
                    <div id="AddToBasketButton">
                        <i class="fa fa-shopping-basket"></i>
                        <p class="textOfButton">Add to Basket</p>
                    </div>
                    <input type="submit" name="add_to_cart" class="btn btn-outline-secondary btn-sm" value="Add to cart">
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
        background-color: #1a1862;
        padding: 10px;
        max-width: 200px;
        border: none;
        cursor: pointer;

        color: white;
        font-size: 20px;
        font-weight: bold;
        border-radius: 10px;
        text-align: center;
        margin: 0 auto;
    }

    #AddToBasketButton i {
        font-size: 5em;
        padding-bottom: 10px;
        color: #FFFFFF
    }


    .productTitle {
        margin-left: 20px;
    }


    .row {
        display: inline-block;
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
    var sliderNum;
    var prevSliderNum
    var picturesLength
    $(document).ready(function() {
        // $('#MainContent').css('padding-bottom', $('footer').height() - 50);
        prevSliderNum = 1;
        picturesLength = "<?php echo $arrLength ?>";
        picturesLength = parseInt(picturesLength);

        if (picturesLength > 1) {
            changeIndictors()
        }


        let lengthOfSlider = picturesLength * 100;

        let result = lengthOfSlider + "%";

        console.log(picturesLength)
        // Resolves issue with only one image then get rid of the margin left -100 (no image will be shown after, so don't need a margin left)
        if (picturesLength == 1) {
            document.getElementById("slider").style.marginLeft = 0;
        }
        document.getElementById("slider").style.width = result
        // changeIndictors();
    });

    sliderNum = 1;
    // set a timed function call to wait for product xml to load
    timer = window.setInterval(function() {

        // Check if products have loaded
    }, 100);

    // Your $(document).ready() event script code goes below here -v

    // Variable for the slider itself
    var slider = $('#slider');




    // move the last image to the first place
    $('#slider .slider__section:last').insertBefore('#slider .slider__section:first');
    // display the first image with a margin of -100%
    slider.css('margin-left', '-' + 100 + '%');

    function moveNext() {
        prevSliderNum = sliderNum;
        sliderNum++;
        slider.animate({
            marginLeft: '-' + 200 + '%'
        }, 700, function() {
            $('#slider .slider__section:first').insertAfter('#slider .slider__section:last');
            slider.css('margin-left', '-' + 100 + '%');
        });
        var helolo = picturesLength + 1;
        if (sliderNum === helolo) {
            sliderNum = 1;
        }
        console.log(document.getElementById("slider").style.width);
        changeIndictors()
    }

    function movePrev() {
        prevSliderNum = sliderNum;
        sliderNum--;
        slider.animate({
            marginLeft: 0
        }, 700, function() {
            $('#slider .slider__section:last').insertBefore('#slider .slider__section:first');
            slider.css('margin-left', '-' + 100 + '%');
        });

        if (sliderNum === 0) {
            sliderNum = picturesLength;
        }
        changeIndictors()
    }



    $('.sliderArrows #right').on('click', function() {
        moveNext();
        console.log("sliderNum" + sliderNum);
    });


    $('.sliderArrows #left').on('click', function() {
        movePrev();
        console.log("sliderNum" + sliderNum);
    });




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
        var even = document.getElementById("sliderIndicatorsButton" + prevSliderNum);
        console.log(even);
        even.style.backgroundColor = "#1a1862";

        var even = document.getElementById("sliderIndicatorsButton" + sliderNum);
        console.log(even);
        even.style.backgroundColor = "red";
    }
</script>