<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Results - Hogwarts University Library</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="stylesheet" type="text/css" href="Results.css">
    <link rel="stylesheet" type="text/css" href="responsiveness.css">
    <link rel="stylesheet" type="text/css" href="book_details.css">
    <script type="text/javascript"
            src="https://platform-api.sharethis.com/js/sharethis.js#property=5e80fd5f0a259f00192f1dce&product=inline-share-buttons"
            async="async"></script>
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
                <div class="ImageHolder">
                    <img alt="Book Title" class="cover"
                         src="https://images-na.ssl-images-amazon.com/images/I/91RQbHZZR0L.jpg">
                </div>
                <div class="AllOtherInfo" id="TopInfo">
                    <div class="IndividualInfo TitleDiv">
                        <h2 class="BookTitleLabel">Title:</h2>
                        <p class="BookTitle">Title</p>
                    </div>

                    <div class="IndividualInfo AuthorDiv">
                        <h4 class="Author(s)Label">Authors:</h4>
                        <p class="Author(s)">Author</p>
                    </div>


                    <div class="IndividualInfo YearDiv">
                        <h4 class="YearLabel">Year:</h4>
                        <p class="Year">Year</p>
                    </div>

                    <div class="IndividualInfo GenreDiv">
                        <h4 class="Genres">Genres:</h4>
                        <p class="Genre">Genre</p>
                    </div>
                </div>
                <br>
                <div id="Actions" class="DifferentSection">
                    <h1>Actions</h1>
                    <hr>
                    <div id="ActionButtons" class="col-12">
                        <div id="Loan" class="containerForActions col-3" onclick="Loan()">
                            <img alt="Loan Book - A book with an arrow coming out of the book"
                                 src="images/Book Details/Loan Book.png">
                            <h3>Loan</h3>
                        </div>

                        <div id="CopyLink" class="containerForActions col-3" onclick="CopyLink()">
                            <img alt="Loan Book - A book with an arrow coming out of the book"
                                 src="images/Book Details/Copy Link.png">
                            <h3>Copy Link</h3>
                        </div>

                        <div id="Share" class="containerForActions col-3" onclick="Share()">
                            <img alt="Share Link Book - A book with an arrow coming out of the book"
                                 src="images/Book Details/Share.png">
                            <h3>Share</h3>
                        </div>


                        <div id="Citation" class="containerForActions col-3">
                            <div onclick="Citation()">
                                <img alt="Loan Book - A book with an arrow coming out of the book"
                                     src="images/Book Details/Citation.png">
                                <h3>Citation</h3>
                            </div>


                            <div id="CitationHolder">
                                <p>Text Sample</p>
                                <div id="CopyCitation" onclick="CopyCitation()">
                                    <img alt="Copy Citation - A book with an arrow coming out of the book"
                                         src="images/Book Details/Copy - White.png">
                                    <p>Copy</p>
                                </div>
                            </div>
                        </div>


                        <div id="PrintInfo" class="containerForActions col-3">
                            <a onclick="Print()">
                                <img alt="Loan Book - A book with an arrow coming out of the book"
                                     src="images/Book Details/Print(1).png">
                                <h3>Print</h3>
                            </a>
                        </div>
                    </div>
                </div>

                <div id="Details" class="DifferentSection">
                    <h1>Details</h1>
                    <hr>

                    <table>
                        <tr id="Title">
                            <th>Title:</th>
                            <td>Title</td>
                        </tr>
                        <tr id="ISBN">
                            <th>ISBN:</th>
                            <td>ISBN</td>
                        </tr>
                        <tr id="Author">
                            <th>Author(s):</th>
                            <td>Author</td>
                        </tr>
                        <tr id="Publisher">
                            <th>Publisher:</th>
                            <td>Publisher</td>
                        </tr>
                        <tr id="Year">
                            <th>Year:</th>
                            <td>Year</td>
                        </tr>
                        <tr id="Genre">
                            <th>Genre(s):</th>
                            <td>Genre</td>
                        </tr>

                        <tr id="LibraryStored">
                            <th>Library Stored:</th>
                            <td>Library</td>
                        </tr>
                        <tr id="Available">
                            <th>Available:</th>
                            <td>No</td>
                        </tr>

                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="sharethis-inline-share-buttons"></div>

    <div id="alert" class="NunitoFont">
        <p></p>
    </div>

    <div class="box"></div>
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

    function Print() {
        window.print();
    }

    function Citation() {
        // $("#FilterContents").css('width')

        // if ($("#FilterContents").css('display') == 'none') {


        if ($("#CitationHolder").css('display') == "none") {
            $("#Citation h3").text("Close Citation");
        } else {
            $("#Citation h3").text("Citation");
        }


        // $("#CitationHolder").css.display("block");
        $("#CitationHolder").slideToggle(500);

        var newWidth = $("#Citation").width();
        $("#CitationHolder").width(newWidth);
    }

    function Share() {
        alert("Hello! You've found a feature where I could implement an API");
    }

    function CopyLink() {
        Copy(window.location.href, "Link");
    }


    function Copy(toBeCopied, component) {
        // var text = toBeCopied;

        var tempElement = document.createElement('input'),
            toBeCopied;

        document.body.appendChild(tempElement);
        tempElement.value = toBeCopied;
        tempElement.select();
        document.execCommand('copy');
        document.body.removeChild(tempElement);

        var message = "Copied " + component + " to clipboard";
        CopyAlterTimer(message);
    }

    function CopyCitation() {
        var text = $('#CitationHolder p:first-child').text();

        Copy(text, "Citation");
        $("#CitationHolder").slideToggle(500);

    }

    function CopyAlterTimer(message) {
        AlterTimer(true, message, 2500);

    }


    function Loan() {
        LoanedAlterTimer();
    }


    function AlterTimer(success, message, totalSecs) {
        var usingClass = "";

        var sliderSecs = totalSecs - 1000;
        if (success === true) {
            usingClass = "successful"
        } else {
            usingClass = "failure";
        }

        setTimeout(
            function () {
                $('#alert').addClass(usingClass);

                $('#alert').text(message);

                $('#alert').slideDown("slow");

            }, 100);

        setTimeout(
            function () {
                $('#alert').css("transition", "all " + sliderSecs + "ms linear");

                $('#alert').css("background-position", "left bottom");
            }, 1000);


        setTimeout(
            function () {
                $('#alert').css("transition", "");
                $('#alert').slideUp("slow");
            }, totalSecs);

        $('#alert').css("background-color", "");
        $('#alert').css("background-position", "");
        $('#alert').removeClass(usingClass);


    }


    function LoanedAlterTimer() {
        var message = "Successfully Loaned Book";
        AlterTimer(true, message, 5000);
    }

    function fillAlert() {
    }
</script>
</html>