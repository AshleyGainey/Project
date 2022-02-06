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

    <div class="carousel">
        <div class="row visible-md visible-lg">
            <div id="contenedor-slider" class="contenedor-slider">
                <div id="slider" class="slider">
                    <a id="slider1" class="slider__section" href=""><img src="Images\Home\Loading.png" class="slider__img">
                    </a>
                    <a id="slider2" class="slider__section" href=""><img src="Images\Home\Loading.png" class="slider__img">
                    </a>
                    <a id="slider3" class="slider__section" href=""><img src="Images\Home\Loading.png" class="slider__img">
                    </a>
                    <a id="slider4" class="slider__section" href=""><img src="Images\Home\Loading.png" class="slider__img">
                    </a>
                    <a id="slider5" class="slider__section" href=""><img src="Images\Home\Loading.png" class="slider__img">
                        </section>
                        <a id="slider6" class="slider__section"><img src="Images\Home\Loading.png" class="slider__img">
                            </section>
                </div>
                <div id="btn-prev" class="btn-prev">&#60;</div>
                <div id="btn-next" class="btn-next">&#62;</div>

            </div>

        </div>


    </div>


    <?php include "./footer.html" ?>

    <style>
       





























        .contenedor-slider {
            margin: auto;
            position: relative;
            overflow: hidden;
            box-shadow: 0 0 0 5px #143576;
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
            height: 100%;
        }

        .btn-prev,
        .btn-next {
            width: 50px;
            height: 40px;
            background: #143576;
            color: white;
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            font-size: 25px;
            text-align: center;
            border-radius: 25%;
            font-family: Arial Black;
            cursor: pointer;
        }

        .btn-prev:hover,
        .btn-next:hover {
            background: white;
            color: #143576;

        }

        .btn-prev {
            left: 5px;
        }

        .btn-next {
            right: 5px;
        }
    </style>


    <script>
        var oldArea;
        $(document).ready(function() {
            // $('#MainContent').css('padding-bottom', $('footer').height() - 50);
        });

        /* Open when someone clicks on the span element */
        function openNav() {

            $('#myNav').animate({
                width: '100%'
            }, 600);
        }


        /* Close when someone clicks on the "x" symbol inside the overlay */
        function closeNav() {
            $("#myNav").animate({
                width: '0%'
            }, 600);
        }

        function expandFooter(Area) {

            if (oldArea == null) {
                $("#" + Area + "> a > div > img").addClass("rotateArrow");
            } else {
                if (oldArea != Area) {
                    $(".SubCategory").slideUp();
                    $("#" + oldArea + "> a > div > img").removeClass("rotateArrow");
                    $("#" + Area + "> a > div > img").addClass("rotateArrow");
                } else {
                    oldArea = null;
                    $("#" + Area + "> a > div > img").removeClass("rotateArrow");
                }
            }

            $("#" + Area + " > div").slideToggle();

            //
            // $("#MainContent").delay(500).animate({
            //     'padding-bottom': $('footer').height()
            // }, "slow");


            oldArea = Area;

            // if (old!= Area) {
            //     oldArea = Area;
            // } else {
            //
            // }
            // $('#MainContent').animate({padding-bottom: $('footer').height()});


            // $(+Area + ".SlideDowns").slideToggle();
            // console.log("Hello, this is coming down here");
        }


        $(window).resize(function() {
            console.log($('footer').height());

        });

        $("#visitLibraries").mouseover(function() {
            $("#visitLibraries h2").css("background", "#034e0e")
        });
        $("#visitLibraries").mouseleave(function() {
            $("#visitLibraries h2").css("background", "#003898")
        });

        $("#news").mouseover(function() {
            $("#news h2").css("background", "#034e0e")
        });
        $("#news").mouseleave(function() {
            $("#news h2").css("background", "#003898")
        });


        $(document).ready(function() {
            $(document).trigger('fontfaceapplied');
        });
    </script>

</html>