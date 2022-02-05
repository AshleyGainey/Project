<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Results - Hogwarts University Library</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="stylesheet" type="text/css" href="Results.css">
    <link rel="stylesheet" type="text/css" href="filters.css">
    <link rel="stylesheet" type="text/css" href="responsiveness.css">
    <link rel="stylesheet" type="text/css" href="account_style.css">

    <link href="https://fonts.googleapis.com/css?family=Nunito|Roboto+Condensed" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <!--TODO: Remove when submitting coursework-->
    <!--    Offline use -->
    <script src="jquery-3.4.1.min.js"></script>
</head>
<body>
<div>
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
                <div><a class="closebtn" onclick="closeNav()">&times;</a></div>

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
        <div id="rightTopArea">
            &nbsp;<a href="search.php">
                <div id="SearchBook">
                    <img alt="Search Book - White Magnifying Glass with a white book inside it"
                         src="images/MasterPage/Search.png"/>
                </div>
            </a>
        </div>
    </div>
    <div id="MainContent" class="centreText">
        <div id="WelcomeBack" class="NunitoFont">
            <p id="ResultsFor">Welcome Back
                <?php
                if (isset($_GET['Name'])) {
                    echo $_GET['Name'];
                } else {
                }
                ?>
            </p>
        </div>
        <div id="Filter" class="NunitoFont">
            <div class="centreText bodyOfFilter col-7" onclick="ToggleFilter()">
                <h2>Books currently loaned by you (200)</h2>
                <img alt="Expand Study Footer Section icon" src="images/MasterPage/Expand Icon.png">
            </div>
            <div id="FilterContents">
                <div id="AllResults">
                    <div class="SingleResult">
                        <a href="Book_Details.php">
                            <div class="ResultBox NunitoFont col-6">
                                <div class="AllInfo">
                                    <div class="ImageHolder">
                                        <img alt="Book Title" src="images/Results/No Image Available.png">
                                    </div>
                                    <div class="AccountAllOtherInfo">
                                        <div class="IndividualInfo TitleDiv">
                                            <h2 class="BookTitleLabel">Title:</h2>
                                            <p class="BookTitle">Title</p>
                                        </div>
                                    </div>
                                    <div class="ReturnBook">
                                        <a href="">
                                            <img style="vertical-align:middle; display:inline"
                                                 src="images/AccountPage/Return Book.png">
                                            <h3>Return</h3>
                                        </a>
                                    </div>

                                    <!--                            <div class="MoreDetailsButton">-->
                                    <!--                                <a href="Book_Details.php">More Details >></a>-->
                                    <!--                            </div>-->
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="SingleResult">
                        <a href="Book_Details.php">
                            <div class="ResultBox NunitoFont col-6">
                                <div class="AllInfo">
                                    <div class="ImageHolder">
                                        <img alt="Book Title" src="images/Results/No Image Available.png">
                                    </div>
                                    <div class="AccountAllOtherInfo">
                                        <div class="IndividualInfo TitleDiv">
                                            <h2 class="BookTitleLabel">Title:</h2>
                                            <p class="BookTitle">Title</p>
                                        </div>
                                    </div>
                                    <div class="ReturnBook">
                                        <a href="">
                                            <img style="vertical-align:middle; display:inline"
                                                 src="images/AccountPage/Return Book.png">
                                            <h3>Return</h3>
                                        </a>
                                    </div>

                                    <!--                            <div class="MoreDetailsButton">-->
                                    <!--                                <a href="Book_Details.php">More Details >></a>-->
                                    <!--                            </div>-->
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="SingleResult">
                        <a href="Book_Details.php">
                            <div class="ResultBox NunitoFont col-6">
                                <div class="AllInfo">
                                    <div class="ImageHolder">
                                        <img alt="Book Title" src="images/Results/No Image Available.png">
                                    </div>
                                    <div class="AccountAllOtherInfo">
                                        <div class="IndividualInfo TitleDiv">
                                            <h2 class="BookTitleLabel">Title:</h2>
                                            <p class="BookTitle">Title</p>
                                        </div>
                                    </div>
                                    <div class="ReturnBook">
                                        <a href="">
                                            <img style="vertical-align:middle; display:inline"
                                                 src="images/AccountPage/Return Book.png">
                                            <h3>Return</h3>
                                        </a>
                                    </div>

                                    <!--                            <div class="MoreDetailsButton">-->
                                    <!--                                <a href="Book_Details.php">More Details >></a>-->
                                    <!--                            </div>-->
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="SingleResult">
                        <a href="Book_Details.php">
                            <div class="ResultBox NunitoFont col-6">
                                <div class="AllInfo">
                                    <div class="ImageHolder">
                                        <img alt="Book Title" src="images/Results/No Image Available.png">
                                    </div>
                                    <div class="AccountAllOtherInfo">
                                        <div class="IndividualInfo TitleDiv">
                                            <h2 class="BookTitleLabel">Title:</h2>
                                            <p class="BookTitle">Title</p>
                                        </div>
                                    </div>
                                    <div class="ReturnBook">
                                        <a href="">
                                            <img style="vertical-align:middle; display:inline"
                                                 src="images/AccountPage/Return Book.png">
                                            <h3>Return</h3>
                                        </a>
                                    </div>

                                    <!--                            <div class="MoreDetailsButton">-->
                                    <!--                                <a href="Book_Details.php">More Details >></a>-->
                                    <!--                            </div>-->
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="SingleResult">
                        <a href="Book_Details.php">
                            <div class="ResultBox NunitoFont col-6">
                                <div class="AllInfo">
                                    <div class="ImageHolder">
                                        <img alt="Book Title" src="images/Results/No Image Available.png">
                                    </div>
                                    <div class="AccountAllOtherInfo">
                                        <div class="IndividualInfo TitleDiv">
                                            <h2 class="BookTitleLabel">Title:</h2>
                                            <p class="BookTitle">Title</p>
                                        </div>
                                    </div>
                                    <div class="ReturnBook">
                                        <a href="">
                                            <img style="vertical-align:middle; display:inline"
                                                 src="images/AccountPage/Return Book.png">
                                            <h3>Return</h3>
                                        </a>
                                    </div>

                                    <!--                            <div class="MoreDetailsButton">-->
                                    <!--                                <a href="Book_Details.php">More Details >></a>-->
                                    <!--                            </div>-->
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="SingleResult">
                        <a href="Book_Details.php">
                            <div class="ResultBox NunitoFont col-6">
                                <div class="AllInfo">
                                    <div class="ImageHolder">
                                        <img alt="Book Title" src="images/Results/No Image Available.png">
                                    </div>
                                    <div class="AccountAllOtherInfo">
                                        <div class="IndividualInfo TitleDiv">
                                            <h2 class="BookTitleLabel">Title:</h2>
                                            <p class="BookTitle">Title</p>
                                        </div>
                                    </div>
                                    <div class="ReturnBook">
                                        <a href="">
                                            <img style="vertical-align:middle; display:inline"
                                                 src="images/AccountPage/Return Book.png">
                                            <h3>Return</h3>
                                        </a>
                                    </div>

                                    <!--                            <div class="MoreDetailsButton">-->
                                    <!--                                <a href="Book_Details.php">More Details >></a>-->
                                    <!--                            </div>-->
                                </div>
                            </div>
                        </a>
                    </div>
                </div>

                <div id="showMoreResultsDiv">
                    <div class="ShowMoreResults NunitoFont" onclick="ShowMoreResults()">
                        <h2>Show More Results</h2>
                    </div>
                    <hr id="splitter">
                </div>
            </div>
        </div>

        <div id="FavGenre">
            <div class="FavouriteGenre NunitoFont col-7">
                <h3>Your most loaned Book Genre is:</h3>
                <h3 id="GenrePlaceholder">GENRE</h3>
                <h3>with <span id="numberOfLoans">__</span> Loans</h3>
            </div>
        </div>


        <div id="Account" class="centreText">
            <div class="centreText AccountSettings NunitoFont col-7">
                <h2>Account Settings</h2>
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

        // if ($('#ResultsFor').text().length > 30) {
        //     $('#ResultsFor').text($('#ResultsFor').text().substring(0, 30) + '...');
        // }
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
            $(".bodyOfFilter h2").text("Close");
            $(".bodyOfFilter img").addClass("rotateArrow");
        } else {
            $(".bodyOfFilter img").removeClass("rotateArrow");
            $(".bodyOfFilter h2").text("Books currently loaned by you (200)");
        }

        $("#FilterContents").slideToggle(1000);

    }
</script>
</html>