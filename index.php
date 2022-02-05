<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">


    <title>Home: Gadget Gainey Store</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="index.css">
    <link rel="stylesheet" type="text/css" href="responsiveness.css">


    <!--    Offline use -->
    <script src="jquery-3.4.1.min.js"></script>

</head>

<body>
    <div>
        <header>
            <div id="header">
                <a href="index.php" id="LeftSide">
                    <img id="Gadget Gainey Logo" src="images/Header/Gadget Gainey Logo (Smaller).png" 
                    alt="Gadget Gainey - Two White G's joining together with the text saying Gadget Gainey"
                    width="249" height="148"/>
                </a>

                <div class="searchBarDiv">
                    <div class="search">
                        <input type="text" class="searchInput" placeholder="Search our catalogue...">
                        <button type="submit" class="searchButton">
                            <i class="fa fa-search"></i>
                        </button>
                    </div>
                </div>

    <div id="hamburgerMenu" class="HeaderRightButton" onclick="openNav()">
        <img alt="Menu icon" src="images/MasterPage/Hamburger Menu.png" />
        <h2 class="NunitoFont">Menu</h2>
    </div>

    <!-- The overlay -->
    <div id="myNav" class="overlay">

        <!-- Button to close the overlay navigation -->
        <div><a class="closebtn" onclick="closeNav()">&times;</a></div>

        <!-- Overlay content -->
        <div class="overlay-content NunitoFont">
            <a href="#">About Hogwarts University</a>
            <hr>
            <a href="#">News</a>
            <hr>
            <a href="#">Support</a>
            <hr>
            <a id="LoginOrAccount" href="Login.php">Account</a>
        </div>
    </div>

    <a id="Basket" href="Login.php">
        <div id="LoginMenuHolder" class="HeaderRightButton">
        <i class="fa fa-shopping-basket" aria-hidden="true"></i>
        <span class='badge' id='lblCartCount'>1</span>
        </div>
    </a>

    <a id="LoginMenu" href="Login.php">
        <div id="LoginMenuHolder" class="HeaderRightButton">
        <i class="fa fa-user-circle-o" aria-hidden="true"></i>
            <h2 class="NunitoFont">Account</h2>
        </div>
    </a>
  

    <div id="NavBar" class="NunitoFont">
        <ul>
            <li><a href="Results.php">About Hogwart's University</a></li>
            <li><a href="#news">News</a></li>
            <li><a href="#contact">Support</a></li>
        </ul>
    </div>
    </div>
    </header>
    <div id="topArea">
        &nbsp;<a href="search.php">
            <div id="SearchBook">
                <img alt="Search Book - White Magnifying Glass with a white book inside it" src="images/MasterPage/Search.png" />
            </div>
        </a>
    </div>
    <div id="MainContent">
        <div id="WelcomeTo" class="centreDiv">
            <img alt="Hogwarts University Logo with the Text of Welcome To Hogwarts University" src="images/Home/Welcome To Hogwarts University.gif">

        </div>

        <div class="boxed-content col-12 centreDiv">
            <div id="visitLibraries" class="box-section NunitoFont centreText col-3">
                <a href="libraries.php">
                    <img src="images/Home/Boxed Content/Library.jfif">
                    <h2 class="bottom-right">Library</h2>
                </a>
            </div>
            <div id="news" class="box-section NunitoFont centreText col-3">
                <img src="images/Home/Boxed Content/News2.png">
                <h2 class="bottom-right">News</h2>
            </div>
        </div>
    </div>
    </div>
</body>

<footer class="NunitoFont">
    <div id="Study" class="category">
        <a onclick="expandFooter('Study')">
            <div>
                <h1>Study</h1>
                <img alt="Expand Study Footer Section icon" src="images/MasterPage/Expand Icon.png">
            </div>
        </a>

        <div class="SubCategory">
            <a href="Search.php">Search Books</a>
            <a id="LoginFooter" href="Login.php">Login/Register</a>
        </div>
    </div>

    <div id="About" class="category">
        <a onclick="expandFooter('About')">
            <div>
                <h1>About</h1>
                <img alt="Expand Study Footer Section icon" src="images/MasterPage/Expand Icon.png">
            </div>
        </a>

        <div class="SubCategory">
            <a href="#">About Hogwarts University</a>
            <a href="#">Our Libraries</a>
            <a href="#">University Policies</a>
        </div>
    </div>

    <div id="GetInTouch" class="category">
        <a onclick="expandFooter('GetInTouch')">
            <div>
                <h1>Support</h1>
                <img alt="Expand Study Footer Section icon" src="images/MasterPage/Expand Icon.png">
            </div>
        </a>

        <div class="SubCategory">
            <a href="#">Contact Us</a>
            <a href="#">FAQ's</a>
            <a href="#">Help</a>
        </div>
    </div>
    <div id="Copyright">
        <h4>© Copyright 2022 Gadget Gainey</h4>
    </div>
</footer>

<style>
    @font-face {
        font-family: Century-Gothic;
        src: url('fonts/CenturyGothic.ttf') format("truetype");
    }

 body {
    font-family: Century-Gothic, sans-serif;
 }
    .search {
        width: 100%;
        position: relative;
        display: flex;
    }

    .searchInput {
        width: 300px;
        height: 25px;
        font-size: 1em;
        border-radius: 25px;
        padding-top: 2%;
        padding-bottom: 2%;
        padding-right: 2%;
        padding-left: 3%;
        background: none;
        font-family: Century-Gothic, sans-serif;
        color: #FFFFFF;


        width: 100%;
        border: 3px solid #FFFFFF;
        height: 20px;
        outline: none;
    }

    .searchTerm:hover {
        color: #FFFFFF;
    }

    .searchButton {
        width: 60px;
        border: 3px solid #FFFFFF;
        background: #1a1862;
        text-align: center;
        color: #fff;
        border-radius: 25px;
        cursor: pointer;
        font-size: 1.5em;
        /* position:absolute; top:0; right:0; */
    }

    /*Resize the wrap to see the search bar change!*/
    .searchBarDiv {
        width: 40%;
        display: inline-block;
    }

    ::placeholder {
        color: #FFFFFF
    }

    #LoginMenuHolder i {
        font-size: 7em;
        padding-bottom: 10px;
        color: #FFFFFF
    }

    /* Problem - won't reseize */
    #LeftSide img {
        max-width: 100%;
        height: auto;
    }


    #lblCartCount {
    font-size: 2.5em;
    background: #008528;
    color: #fff;
    padding: 0 5px;
    margin-left: -25px;
    vertical-align: bottom;
}
.badge {
  padding-left: 9px;
  padding-right: 9px;
  -webkit-border-radius: 9px;
  -moz-border-radius: 9px;
  border-radius: 25%;
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


    // $(document).ready(function () {
    // });
</script>

</html>