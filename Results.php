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
        <div id="leftTopArea" class="NunitoFont">
            <p id="ResultsFor"> Results Found:
                <!--                --><?php
                //                if (isset($_GET['search'])) {
                //                    echo $_GET['search'];
                //                }
                //                ?><!--'-->
            </p>
        </div>
        <div id="rightTopArea">
            &nbsp;<a href="search.php">
                <div id="SearchBook">
                    <img alt="Search Book - White Magnifying Glass with a white book inside it"
                         src="images/MasterPage/Search.png"/>
                </div>
            </a>
        </div>
    </div>
    <div id="MainContent">
        <div id="Filter" class="NunitoFont">
            <div id="bodyOfFilter">
                <div class="centreText bodyOfFilter col-6" onclick="ToggleFilter()">
                    <h2>Filters</h2>
                    <img alt="Expand Study Footer Section icon" src="images/MasterPage/Expand Icon.png">
                </div>
            </div>

            <div id="FilterContents">
                <div class="box col-6">
                    <div class="box-content" id="Library">
                        <h1>Libraries:</h1>
                        <!--Placeholder for getting results from database-->
                        <label class="container">Hogwarts School of Witchcraft and Wizardry
                            <input type="checkbox" checked="checked">
                            <span class="checkmark"></span>
                        </label>

                        <label class="container">The Rowena Ravenclaw Library
                            <input type="checkbox" checked="checked">
                            <span class="checkmark"></span>
                        </label>

                        <label class="container">The Source Of Magic Library
                            <input type="checkbox" checked="checked">
                            <span class="checkmark"></span>
                        </label>

                        <label class="container">Hello, this is a really long title because I have nothing better to do
                            with
                            my time than time this when I should really be going to bed. Please bed, take me
                            <input type="checkbox" checked="checked">
                            <span class="checkmark"></span>
                        </label>

                        <label class="container">Please
                            <input type="checkbox" checked="checked">
                            <span class="checkmark"></span>
                        </label>

                        <label class="container">Show
                            <input type="checkbox" checked="checked">
                            <span class="checkmark"></span>
                        </label>

                        <label class="container">Me
                            <input type="checkbox" checked="checked">
                            <span class="checkmark"></span>
                        </label>

                        <label class="container">This
                            <input type="checkbox" checked="checked">
                            <span class="checkmark"></span>
                        </label>

                        <label class="container">Checkbox
                            <input type="checkbox" checked="checked">
                            <span class="checkmark"></span>
                        </label>
                    </div>
                </div>


                <div class="box col-6">
                    <div class="box-content" id="Genre">
                        <h1>Genre:</h1>
                        <!--Placeholder for getting results from database-->
                        <label class="container">Genre1
                            <input type="checkbox" checked="checked">
                            <span class="checkmark"></span>
                        </label>

                        <label class="container">Genre2
                            <input type="checkbox" checked="checked">
                            <span class="checkmark"></span>
                        </label>

                        <label class="container">Genre3
                            <input type="checkbox" checked="checked">
                            <span class="checkmark"></span>
                        </label>

                        <label class="container">Genre4
                            <input type="checkbox" checked="checked">
                            <span class="checkmark"></span>
                        </label>

                        <label class="container">Genre5
                            <input type="checkbox" checked="checked">
                            <span class="checkmark"></span>
                        </label>

                        <label class="container">Genre6
                            <input type="checkbox" checked="checked">
                            <span class="checkmark"></span>
                        </label
                    </div>
                </div>

                <div class="box col-3 centreText" id="Year">
                    <div class="box-content">
                        <h1>Year:</h1>
                        <select>
                            <option value="DefaultYear">Select Year</option>
                            <option value="2000">2000</option>
                            <option value="2001">2001</option>
                            <option value="2002">2002</option>
                            <option value="2003">2003</option>
                            <option value="2004">2004</option>
                            <option value="2005">2005</option>
                            <option value="2006">2006</option>
                            <option value="2007">2007</option>
                            <option value="2008">2008</option>
                            <option value="2009">2009</option>
                            <option value="2010">2010</option>
                            <option value="2011">2011</option>
                            <option value="2012">2012</option>
                            <option value="2013">2013</option>
                            <option value="2014">2014</option>
                            <option value="2015">2015</option>
                            <option value="2016">2016</option>
                            <option value="2017">2017</option>
                            <option value="2018">2018</option>
                            <option value="2019">2019</option>
                            <option value="2020">2020</option>
                        </select>
                    </div>
                </div>

                <div class="box col-5 centreText" id="Publisher">
                    <div class="box-content">
                        <h1>Publisher:</h1>
                        <input type="text" name="PublisherTextbox" autocomplete="off" placeholder="Search by Publisher">
                    </div>
                </div>

                <div class="box col-6 centreText" id="Author">
                    <div class="box-content">
                        <h1>Author(s):</h1>
                        <input type="text" name="Author" autocomplete="off" placeholder="Search by Author(s)">
                        <p id="Tip"><span class="blink_me">Tip:</span> Separate Authors with a comma (,)</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="AllResults">
        <div class="SingleResult col-5">
            <a href="Book_Details.php">
                <div class="ResultBox NunitoFont col-5">
                    <div class="AllInfo">
                        <div class="ImageHolder">
                            <img alt="Book Title" src="images/Results/No Image Available.png">
                        </div>
                        <div class="AllOtherInfo">
                            <div class="IndividualInfo TitleDiv">
                                <h2 class="BookTitleLabel">Title:</h2>
                                <p class="BookTitle">I'm at a payphone, trying to call home,All of my change, I spent on you, Where have the times gone?</p>
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
                    </div>
                    <div class="MoreDetailsButton">
                        <a href="Book_Details.php">More Details >></a>
                    </div>
                </div>
            </a>
        </div>
        <div class="SingleResult col-5">
            <a href="Book_Details.php">
                <div class="ResultBox NunitoFont col-5">
                    <div class="AllInfo">
                        <div class="ImageHolder">
                            <img alt="Book Title" src="images/Results/No Image Available.png">
                        </div>
                        <div class="AllOtherInfo">
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
                    </div>
                    <div class="MoreDetailsButton">
                        <a href="Book_Details.php">More Details >></a>
                    </div>
                </div>
            </a>
        </div>
        <div class="SingleResult col-5">
            <a href="Book_Details.php">
                <div class="ResultBox NunitoFont col-5">
                    <div class="AllInfo">
                        <div class="ImageHolder">
                            <img alt="Book Title" src="images/Results/No Image Available.png">
                        </div>
                        <div class="AllOtherInfo">
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
                    </div>
                    <div class="MoreDetailsButton">
                        <a href="Book_Details.php">More Details >></a>
                    </div>
                </div>
            </a>
        </div>
        <div class="SingleResult col-5">
            <a href="Book_Details.php">
                <div class="ResultBox NunitoFont col-5">
                    <div class="AllInfo">
                        <div class="ImageHolder">
                            <img alt="Book Title" src="images/Results/No Image Available.png">
                        </div>
                        <div class="AllOtherInfo">
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
                    </div>
                    <div class="MoreDetailsButton">
                        <a href="Book_Details.php">More Details >></a>
                    </div>
                </div>
            </a>
        </div>
        <div class="SingleResult col-5">
            <a href="Book_Details.php">
                <div class="ResultBox NunitoFont col-5">
                    <div class="AllInfo">
                        <div class="ImageHolder">
                            <img alt="Book Title" src="images/Results/No Image Available.png">
                        </div>
                        <div class="AllOtherInfo">
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
                    </div>
                    <div class="MoreDetailsButton">
                        <a href="Book_Details.php">More Details >></a>
                    </div>
                </div>
            </a>
        </div>
        <div class="SingleResult col-5">
            <a href="Book_Details.php">
                <div class="ResultBox NunitoFont col-5">
                    <div class="AllInfo">
                        <div class="ImageHolder">
                            <img alt="Book Title" src="images/Results/No Image Available.png">
                        </div>
                        <div class="AllOtherInfo">
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
                    </div>
                    <div class="MoreDetailsButton">
                        <a href="Book_Details.php">More Details >></a>
                    </div>
                </div>
            </a>
        </div>
        <div class="SingleResult col-5">
            <a href="Book_Details.php">
                <div class="ResultBox NunitoFont col-5">
                    <div class="AllInfo">
                        <div class="ImageHolder">
                            <img alt="Book Title" src="images/Results/No Image Available.png">
                        </div>
                        <div class="AllOtherInfo">
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
                    </div>
                    <div class="MoreDetailsButton">
                        <a href="Book_Details.php">More Details >></a>
                    </div>
                </div>
            </a>
        </div>


    </div>
</div>

<div id="showMoreResultsDiv" class="centreDiv">
    <div class="centreText ShowMoreResults NunitoFont" onclick="ShowMoreResults()">
        <h2>Show More Results</h2>
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