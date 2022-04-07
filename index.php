<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">


    <title>Gadget Gainey Store</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <!--    Offline use -->
    <script src="jquery-3.4.1.min.js"></script>
</head>

<body>
    <?php include "./header.html" ?>
    <div id="mainBody">
        <!-- Old Triangle method -->
        <!-- <div class="triangle"></div> -->
        <div class="carousel">
            <div class="row visible-md visible-lg">
                <div id="contenedor-slider" class="contenedor-slider">
                    <div id="slider" class="slider">
                        <a id="slider1" class="slider__section" href=""><img src="Images\Home\Gadget Gainey - No Image Available.gif" class="slider__img">
                        </a>
                        <a id="slider2" class="slider__section" href=""><img src="Images\Home\Welcome To Hogwarts University background 2.png" class="slider__img">
                        </a>
                        <a id="slider3" class="slider__section" href=""><img src="Images\Home\Right Arrow.svg" class="slider__img">
                        </a>
                        <a id="slider4" class="slider__section" href=""><img src="Images\Home\Gadget Gainey - No Image Available.gif" class="slider__img">
                        </a>
                        <a id="slider5" class="slider__section" href=""><img src="Images\Home\Gadget Gainey - No Image Available.gif" class="slider__img">
                        </a>
                        <a id="slider6" class="slider__section"><img src="Images\Home\Gadget Gainey - No Image Available.gif" class="slider__img">
                        </a>
                    </div>

                    <div class="sliderArrows">
                        <a href=" #" id="left">
                            <img src="images/Home/Right Arrow.svg" alt="Next Slide" />
                        </a>
                        <a href="#" id="right">
                            <img src="images/Home/Right Arrow.svg" alt="Previous Slide" />
                        </a>
                    </div>

                    <div class="indicatorDiv">
                        <div class="sliderIndicators">
                            <a href=" #" class="button" id="sliderIndicatorsButton1" onclick="goToSlide(1)">
                            </a>
                            <a href=" #" class="button" id="sliderIndicatorsButton2" onclick="goToSlide(2)">
                            </a>
                            <a href=" #" class="button" id="sliderIndicatorsButton3" onclick="goToSlide(3)">
                            </a>
                            <a href=" #" class="button" id="sliderIndicatorsButton4" onclick="goToSlide(4)">
                            </a>
                            <a href=" #" class="button" id="sliderIndicatorsButton5" onclick="goToSlide(5)">
                            </a>
                            <a href=" #" class="button" id="sliderIndicatorsButton6" onclick="goToSlide(6)">
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="cardArea">
            <div class="cardContainer leftPart">
                <div class="card">
                    <a href="#"><img src="Images\Home\Gadget Gainey - No Image Available.gif" class="slider__img">
                    </a>
                </div>
                <div class="writingOfCard">
                    <a href="#">About Us<img src="images/Home/Right Arrow.svg" alt="About Us" /></a>
                </div>
            </div>

            <div class="cardContainer rightPart">
                <div class="card">
                    <a href="#"><img src="Images\Home\Gadget Gainey - No Image Available.gif" class="slider__img">
                    </a>
                </div>
                <div class="writingOfCard">
                    <a href="#">Contact Us<img src="images/Home/Right Arrow.svg" alt="Contact Us" /></a>
                </div>
            </div>
        </div>
    </div>


    <?php include "./footer.php" ?>
    <style>
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
            margin-bottom: 50px;

        }

        .card {
            box-shadow: 0 0 0 5px #FFFFFF;
            border-radius: 2%;
            height: 125px;
            width: 250px;
            overflow: hidden;
        }


        .contenedor-slider {
            margin: auto;
            position: relative;
            overflow: hidden;
            box-shadow: 0 0 0 5px #FFFFFF;
            border-radius: 2%;
            height: 500px;
            max-width: 1000px;
        }


        .slider {
            display: flex;
            width: 600%;

        }

        .slider__section {
            width: 100%;
        }



        .slider__img {
            display: block;
            width: 100%;
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
            margin-left: 10px;
            margin-right: 10px;
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











        #first {
            background-color: #1a1862;
            border-radius: 50%;
            width: 500px;
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
        $(document).ready(function() {
            // $('#MainContent').css('padding-bottom', $('footer').height() - 50);
            prevSliderNum = 1;
            changeIndictors();

        });

        var timer;
        var sliderNum;
        var prevSliderNum

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

            if (sliderNum === 7) {
                sliderNum = 1;
            }
            changeIndictors()
            clearInterval(interval);
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
                sliderNum = 6;
            }
            changeIndictors()
            clearInterval(interval);
        }

        function autoplay() {
            interval = setInterval(function() {
                moveNext();
                console.log("sliderNum" + sliderNum);
            }, 5000);
        }

        $('.sliderArrows #right').on('click', function() {
            moveNext();
            clearInterval(interval);
            autoplay();
            console.log("sliderNum" + sliderNum);
        });

        $('#slider').mouseenter(function() {
            clearInterval(interval);
        });

        $('#slider').mouseleave(function() {
            autoplay();
        });

        $('.sliderArrows #left').on('click', function() {
            movePrev();
            autoplay();
            console.log("sliderNum" + sliderNum);
        });



        autoplay();





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

</html>