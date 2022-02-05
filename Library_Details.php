<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Library Details - Hogwarts University Library</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <!--    <link rel="stylesheet" type="text/css" href="Results.css">-->
    <link rel="stylesheet" type="text/css" href="responsiveness.css">
    <link rel="stylesheet" type="text/css" href="book_details.css">
    <link rel="stylesheet" type="text/css" href="Library Details.css">

    <link href="https://fonts.googleapis.com/css?family=Nunito|Roboto+Condensed" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <!--TODO: Remove when submitting coursework-->
    <!--    Offline use -->
    <script src="jquery-3.4.1.min.js"></script>
</head>
<body>
<header>
    <div id="header">
        <a href="index.php" id="LeftSide">
            <img id="logo"
                 src="images/MasterPage/Hogwarts Logo (No Background) - Small.png"
                 alt="Hogwarts University Logo - A yellow Crest that has got four animals in it with different colours, red, green, yellow and blue. "/>
            <h1 class="hogwartsFont">Hogwarts University</h1>
        </a>
        <div id="hamburgerMenu" class="HeaderRightButton" onclick="openNav()">
            <img alt="Menu icon" src="images/MasterPage/Hamburger Menu.png"/>
            <h2 class="NunitoFont">Menu</h2>
        </div>

        <!-- The overlay -->
        <div id="myNav" class="overlay">

            <!-- Button to close the overlay navigation -->
            <div><a class="closebtn" onclick="closeNav()">&times;</a>
            </div>

            <!-- Overlay content -->
            <div class="overlay-content NunitoFont">
                <a href="#">About Hogwarts University</a>
                <hr>
                <a href="#">News</a>
                <hr>
                <a href="#">Support</a>
                <hr>
                <a id="LoginOrAccount" href="Login.php">Login/Register</a>
            </div>
        </div>

        <a href="Login.php">
            <div id="LoginMenuHolder" class="HeaderRightButton">
                <img alt="Login/Register icon" src="images/MasterPage/Account Icon.png"/>
                <h2 class="NunitoFont">Login/Register</h2>
            </div>
        </a>

        <div id="NavBar" class="NunitoFont">
            <ul>
                <li><a href="#home">About Hogwart's University</a></li>
                <li><a href="#news">News</a></li>
                <li><a href="#contact">Support</a></li>
            </ul>
        </div>
    </div>
</header>
<div id="topArea">
    &nbsp;<a href="search.php">
        <div id="SearchBook">
            <img alt="Search Book - White Magnifying Glass with a white book inside it"
                 src="images/MasterPage/Search.png"/>
        </div>
    </a>
</div>
<div id="MainContent">
    <div class="SingleResult col-12">
        <div class="ResultBox centreDiv NunitoFont col-12">
            <div class="AllInfo">
                <div class="topPart">
                    <div id="PhotoOfLibrary" class="col-6">
                        <img alt="Book Title" src="images/Results/No Image Available.png">
                    </div>
                    <div class="AllOtherInfo col-6" id="TopInfo">
                        <div class="IndividualInfo TitleDiv">
                            <h2 class="BookTitle">Title</h2>
                        </div>
                    </div>
                </div>
                <br>

                <div id="Location" class="DifferentSection">
                    <h1>Location</h1>
                    <hr>
                    <div id="LocationDetails">
                        <h2 id="LibraryName">Library Name</h2>
                        <p id="FirstLineAddress">Address Line 1</p>
                        <p id="SecondLineAddress">Address Line 2</p>
                        <p id="TownCity">Town/City</p>
                        <p id="County">County</p>
                        <p id="Postcode">Postcode</p>
                    </div>
                </div>


                <div id="Telephone" class="DifferentSection">
                    <h1>Get In Touch</h1>
                    <hr>
                    <div id="TelephoneDetails">
                        <h3> Telephone:</h3>
                        <p id="TelephoneNumber">000000000000</p>
                    </div>
                </div>
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
        <h4>© Copyright 2020 Hogwarts University</h4>
    </div>
</footer>
<script>
    var oldArea;
    $(document).ready(function () {
        // $('#MainContent').css('padding-bottom', $('footer').height() - 50);


        var text = $('#Available td').text();
        console.log("Available is" + text);
        var comparingText = 'Yes';

        if (text == comparingText) {
            $('#Available').css('color', '#034e0e');
        } else {
            $('#Available').css('color', '#c30003');

        }
        ;

        $('#Available').css('color',)
    });

    /* Open when someone clicks on the span element */
    function openNav() {

        $('#myNav').animate({width: '100%'}, 600);
    }


    /* Close when someone clicks on the "x" symbol inside the overlay */
    function closeNav() {
        $("#myNav").animate({width: '0%'}, 600);
    }

    function ShowMoreResults() {
        console.log("Has entered Show More Results function");
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
                $("#" + Area + "> a > div > img").removeClass("rotateArrow");
            }
        }


        $("#" + Area + " > div").slideToggle();

        oldArea = Area;


    }

    function ToggleFilter() {
        // $("#FilterContents").slideToggle();
        // $("#wholeSearchBar").slideToggle();

        if ($("#FilterContents").css('display') == 'none') {
            $("#FilterContents").show(1000);
            $(".bodyOfFilter h2").text("Close Filters");
            $(".bodyOfFilter img").addClass("rotateArrow");
        } else {
            $("#FilterContents").hide(1000);
            $(".bodyOfFilter img").removeClass("rotateArrow");
            $(".bodyOfFilter h2").text("Filters");
        }
    }
</script>
</html>