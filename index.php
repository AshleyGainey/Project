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
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
//Prepare the statement - query to select how many products there are in the product table
$stmt = $conn->prepare("SELECT COUNT(ProductID) AS NumberOfProducts FROM Product");

//And execute the query
if (!$stmt->execute()) {
    //Couldn't execute query so stop there
    header('HTTP/1.1 500 Internal Server Error');
    header('Content-Type: application/json; charset=UTF-8');
    die(json_encode('ERROR - Could not get the products to show for carousel'));
}

//Get result from the database and place it in numOfProductsInDB
$res = $stmt->get_result();
$resultSet = mysqli_fetch_all($res, MYSQLI_ASSOC);
$numOfProductsInDB =  $resultSet[0]['NumberOfProducts'];

// Store the Product ID's that we have randomised in an array
$productIDCarousel = [];

// For loop to make 6 random numbers which will be used to get the products from the DB.
for ($sliderProducts = 0; $sliderProducts < 6; $sliderProducts = $sliderProducts + 1) {
    // Generate random product id (from what is stored in DB)
    $productID = rand(1, $numOfProductsInDB);

    //If it is not the first item being inserted.
    if ($sliderProducts > 0) {
        $foundInproductIDCarousel = 0;
        // Check if ID is not already in the array
        while ($foundInproductIDCarousel == 0) {
            for ($arrayFinding = 0; $arrayFinding < $sliderProducts; $arrayFinding++) {
                if ($productIDCarousel[$arrayFinding] == $productID) {
                    $foundInproductIDCarousel = 1;
                }
            }
            //Found ID in the array so therefore make a new random number
            if ($foundInproductIDCarousel == 1) {
                $productID = rand(1, $numOfProductsInDB);
                $foundInproductIDCarousel = 0;
            } else {
                $foundInproductIDCarousel = 1;
            }
        }
    }
    // Add to the array
    $productIDCarousel[$sliderProducts] = $productID;
}

// Store the Carousel Image filename and Alternative text in their own arrays
$picturesURLCarousel = array();
$picturesAltCarousel = array();

// Loop through the productIDCarousel
for ($carouselProduct = 0; $carouselProduct < count($productIDCarousel); $carouselProduct++) {
    // Get the random Product ID from the current position of productIDCarousel
    $productID = $productIDCarousel[$carouselProduct];

    //Prepare the statement - query to select the Image File Name and Image Alternative Text for that image given a Product ID
    $stmt = $conn->prepare("SELECT productCarouselImageFileName, productCarouselImageAltText from product where productID = ?");
    //Pass in the ID
    $stmt->bind_param("i", $productID);
    //And execute the query
    if ($stmt->execute()) {
        // If successful, get the result and store it in the ProductDetails variable
        $res = $stmt->get_result();
        $productDetails = mysqli_fetch_all($res, MYSQLI_ASSOC);

        // Now store the columns from the result set into their own variables
        $productFileName = $productDetails[0]['productCarouselImageFileName'];
        $productAlt = $productDetails[0]['productCarouselImageAltText'];
        // And to prevent errors using quotation marks, replace them with speech marks
        $productAlt = str_replace("'", '"', $productAlt);

        // Push them values into the Carousel arrays
        array_push($picturesURLCarousel, $productFileName);
        array_push($picturesAltCarousel, $productAlt);
    } else {
        // If not successful, then show an error
        header('HTTP/1.1 500 Internal Server Error');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - Could not get the products to show for carousel'));
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <!-- Put a viewport on this page -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Keywords of the site for search engine optimisation -->
    <meta name="keywords" content="Gadget Gainey, Gadget, Ecommerce, Online, Shop, Kids Toys, Toys, Technology, Gainey, Ashley Gainey">
    <!-- Author of the site -->
    <meta name="author" content="Ashley Gainey">
    <!-- Description of the page -->
    <meta name="description" content="Welcome to the world of Gadget Gainey! Providing the best products with a great price. 
    Providing kids toys, technology, accessories to you with amazing customer service! We care for our customers which is key to success!">
    <!-- Shows what the title of the tab is-->
    <title>Gadget Gainey Store</title>
    <!-- Link to the shared classes and ID style sheet -->
    <link rel="stylesheet" type="text/css" href="sharedStyles.css">
</head>

<body>
    <!-- Add the header at the top before any other material -->
    <?php include "./header.php" ?>
    <div id="bodyOfPage">
        <!-- Carousel Div -->
        <div id="carouselContainter" onmouseenter="carouselMouseEnter()" onmouseleave="carouselMouseLeave()">
        <!-- Div to hold the images-->
            <div id="carouselItems">
                <?php
                // For every item in Picture URL Carousel (should be 6)
                for ($item = 0; $item < count($picturesURLCarousel); $item++) {
                    // Get the Product ID from the carousel array
                    $productID = $productIDCarousel[$item];
                    // Individual Image, ID will be IndividualCarouselItem[ID], clicking the image will link to the product (by the productID), 
                    //it will get the image from the carousel Images folder and then the filename that is stored in the database and put in the array 
                    //and the alternative text of the image is from the database/array
                    echo "<a id='IndividualCarouselItem" . ($item + 1) . "' class='IndividualCarouselItem' href='productPage.php?productID=" . $productID . "'><img src='Images/Home/carouselImages/" . $picturesURLCarousel[$item] . "' alt='" . $picturesAltCarousel[$item] . "' class='carouselImage '>
                        </a>";
                }
                ?>
            </div>

            <!-- Navigating Buttons, left and right -->
            <div class="carouselArrows">
                <a id="leftArrow" onclick="moveToPreviousImage()">
                    <img src="images/Home/Right Arrow.svg" alt="Next Slide" />
                </a>
                <a id="rightArrow" onclick="moveToNextImage()">
                    <img src="images/Home/Right Arrow.svg" alt="Previous Slide" />
                </a>
            </div>

            <!-- Navigating Buttons, selecting which carousel image they want to go to, on click they go to the JS method called goToCarouselItem and pass in the current indicator -->
            <div class="indicatorDiv">
                <div class="carouselItemIndicators">
                    <a class="carouselIndicatorButton" id="carouselIndicatorButton1" onclick="goToCarouselItem(1)">
                    </a>
                    <a class="carouselIndicatorButton" id="carouselIndicatorButton2" onclick="goToCarouselItem(2)">
                    </a>
                    <a class="carouselIndicatorButton" id="carouselIndicatorButton3" onclick="goToCarouselItem(3)">
                    </a>
                    <a class="carouselIndicatorButton" id="carouselIndicatorButton4" onclick="goToCarouselItem(4)">
                    </a>
                    <a class="carouselIndicatorButton" id="carouselIndicatorButton5" onclick="goToCarouselItem(5)">
                    </a>
                    <a class="carouselIndicatorButton" id="carouselIndicatorButton6" onclick="goToCarouselItem(6)">
                    </a>
                </div>
            </div>
        </div>
        <!-- Div to hold the cards -->
        <div class="cardArea">
            <!-- Individual Card container (including image and writing) -->
            <div class="fieldContainer leftSection">
                <!-- Individual Card Image Div  -->
                <div class="card">
                    <!-- Image for Card -->
                    <a href="#"><img src="Images\Home\About Gadget Gainey.png" class="cardImage">
                    </a>
                </div>
                <!-- Writing for card -->
                <div class="writingOfCard">
                    <a href="#">About Us<img src="images/Home/Right Arrow.svg" alt="About Us" /></a>
                </div>
            </div>
            <!-- Individual Card container (including image and writing) -->
            <div class="fieldContainer rightSection">
                <!-- Individual Card Image Div  -->
                <div class="card">
                        <!-- Image for Card -->
                    <a href="#"><img src="Images\Home\Contact Us (without logo).png" class="cardImage">
                    </a>
                </div>
                <!-- Writing for card -->
                <div class="writingOfCard">
                    <a href="#">Contact Us<img src="images/Home/Right Arrow.svg" alt="Contact Us" /></a>
                </div>
            </div>
        </div>
    </div>
    <!-- Add the footer at the bottom after any other material -->
    <?php include "./footer.php" ?>
    <style>
        #carouselContainter {
            margin: auto;
            position: relative;
            overflow: hidden;
            box-shadow: 0 0 0 5px #FFFFFF;
            border-radius: 2%;
            margin-bottom: 50px;
            max-width: 1000px;
        }

        #carouselItems {
            display: flex;
            width: 600%;
        }

        .IndividualCarouselItem {
            width: 100%;
        }

        .card {
            box-shadow: 0 0 0 5px #FFFFFF;
            border-radius: 2%;
            overflow: hidden;
        }

        .cardImage,
        .carouselImage {
            display: block;
            width: 100%;
        }

        .carouselItemIndicators {
            /* Put the indicators in the middle of the carousel */
            text-align: center;
            top: 50%;
            cursor: pointer;
        }

        .carouselIndicatorButton:hover,
        .carouselArrows a:hover {
            opacity: 50%;
        }

        .carouselArrows a {
            position: absolute;
            top: 50%;
            cursor: pointer;
        }

        #leftArrow {
            left: 5px;
            transform: rotate(180deg) translateY(50%);
        }

        #rightArrow {
            right: 5px;
            transform: translateY(-50%);
        }

        #carouselItemIndicators {
            position: absolute;
            top: 50%;
            cursor: pointer;
        }

        .cardArea {
            display: inline-block;
            width: 100%;
        }

        .fieldContainer {
            display: inline-block;
            width: 250px;
        }

        .writingOfCard {
            margin-top: 10px;
            text-align: center;
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

        .leftSection {
            float: left;
        }

        .rightSection {
            float: right;
        }

        .indicatorDiv {
            position: absolute;
            width: 100%;
            bottom: 10px;
        }

        .carouselIndicatorButton {
            background-color: #1a1862;
            border: 5px solid #FFFFFF;
            color: white;
            padding: 10px;
            display: inline-block;
            font-size: 16px;
            margin: 4px 2px;
            cursor: pointer;
            border-radius: 50%;
            margin-left: 10px;
            margin-right: 10px;
        }
    </style>
    <script>
        // Declare variables to see where the carousel is and where it was previously
        var previousCarouselImageNum = 1;
        var currentCarouselImageNum = 1;
        // Interval for autoplay
        var interval = 0;
        var allowAutoPlay = true;
        // Set the indicators on the carousel
        changeIndicators();
        // Autoplay carousel
        autoplay();

        // move the last image to the first place
        let content = document.getElementById('carouselItems');
        let firstChild = content.firstElementChild;
        let lastChild = content.lastElementChild;
        firstChild.before(lastChild);
        // And have an offset of -100 to show the "first", actually second, element
        document.getElementById("carouselItems").style.marginLeft = "-100%";

        // Autoplay the carousel (every 5 seconds, then move to the next image)
        function autoplay() {
            if (allowAutoPlay) {
                interval = setInterval(function() {
                    moveToNextImage();
                }, 5000);
            }

        }
        // Move to the previous carousel image
        function moveToPreviousImage() {
            // Change the last carousel image number to the current carousel image number (before moving it)
            previousCarouselImageNum = currentCarouselImageNum;
            // Decrement the current carousel image number (telling it that we are now on the previous image)
            currentCarouselImageNum--;

            // move the last image to the first place
            let content = document.getElementById('carouselItems');
            let firstChild = content.firstElementChild;
            let lastChild = content.lastElementChild;
            firstChild.before(lastChild);
            // And have an offset of -100 to show the "first", actually second, element
            document.getElementById("carouselItems").style.marginLeft = "-100%";

            // If the current Carousel Image Number variable is 0, then loop back around to 6 (6 items in the carousel)
            if (currentCarouselImageNum === 0) {
                currentCarouselImageNum = 6;
            }

            // Change the Carousel Page indicator Buttons 
            changeIndicators();
            // Due to activity on the carousel, reset the variable of interval (so they will need to wait 5 seconds for autoplay to kick in and move to the next image)
            clearInterval(interval);
        }

        function moveToNextImage() {
            // Change the last carousel image number to the current carousel image number (before moving it)
            previousCarouselImageNum = currentCarouselImageNum;
            // Increment the current carousel image number (telling it that we are now on the current image)
            currentCarouselImageNum++;

            // move the first image to the last place
            let content = document.getElementById('carouselItems');
            let firstChild = content.firstElementChild;
            let lastChild = content.lastElementChild;
            lastChild.after(firstChild);
            // And have an offset of -100 to show the "first", actually second, element
            document.getElementById("carouselItems").style.marginLeft = "-100%";

            // If the current Carousel Image Number variable is 7, then loop back around to 1 (6 items in the carousel)
            if (currentCarouselImageNum === 7) {
                currentCarouselImageNum = 1;
            }
            // Change the Carousel Page indicator Buttons 
            changeIndicators()
            // Due to activity on the carousel, reset the variable of interval (so they will need to wait 5 seconds for autoplay to kick in and move to the next image)
            clearInterval(interval);
        }



        // If mouse is on the carousel, then clear the interval (preventing the carousel to move onto the next item)
        function carouselMouseEnter() {
            allowAutoPlay = false;
            clearInterval(interval);
        }
        // If mouse is off the carousel, then autoplay (carousel can move onto the next item)
        function carouselMouseLeave() {
            allowAutoPlay = true;
            autoplay();
        }

        // Specifically say which carousel image you want to go to (it will loop the moveToNextImage method)
        function goToCarouselItem(slide) {
            var currentSlide = currentCarouselImageNum;

            var wantToGoTo = slide
            var slidesToGoNextTo;

            // Determined which way to use, either going previous or going next depending on which one is nearer to the target
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

        // Change the indicator colour
        function changeIndicators() {
            // Change the previous indicator back to normal blue colour
            var previousIndicator = document.getElementById("carouselIndicatorButton" + previousCarouselImageNum);
            previousIndicator.style.backgroundColor = "#1a1862";

            // Change the current indicator to red colour
            var nextIndicator = document.getElementById("carouselIndicatorButton" + currentCarouselImageNum);
            nextIndicator.style.backgroundColor = "red";
        }
    </script>

</html>