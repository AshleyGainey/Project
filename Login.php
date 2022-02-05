<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login/Register - Hogwarts University</title>
    <link href="https://fonts.googleapis.com/css?family=Nunito|Roboto+Condensed" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>

    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="stylesheet" type="text/css" href="responsiveness.css">
    <link rel="stylesheet" type="text/css" href="login_style.css">


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
                <img alt="Search Book - White Magnifying Glass with a white book inside it"
                     src="images/MasterPage/Search.png"/>
            </div>
        </a>
    </div>
    <!--</div>-->
    <div id="MainContent" class="centreDiv">
        <div id="LoginRegisterButtons" class="centreDiv col-12">
            <div class="centreDiv Modes ActiveMode" id="LoginMode">
                <a href="#Login">
                    <div class="centreText NunitoFont col-2 " onclick="ShowSection(false)">
                        <h2>Login</h2>
                    </div>
                </a>
            </div>

            <div class="centreDiv Modes" id="RegisterMode">
                <a href="#Register">
                    <div class="centreText NunitoFont col-2" onclick="ShowSection(true)">
                        <h2>Register</h2>
                </a>
            </div>
        </div>
    </div>

    <div>
    <form action="Account.php" id="LoginForm" class="centreDiv">
        <input type="text" id="LUsername" name="LUsername" autocomplete="off"
               placeholder="Username/Student ID">
        <input type="password" id="LPassword" name="LPassword" autocomplete="off"
               placeholder="Password">
        <input class="submit" type="submit" value="Login"/>
    </form>
    </div>

    <div>
        <form action="Account.php" id="RegisterForm" class="centreDiv">
            <input type="text" id="RUsername" name="RUsername" autocomplete="off"
                   placeholder="Username/Student ID">


            <input type="password" id="RPassword" name="RPassword" autocomplete="off"
                   placeholder="Password">
            <div id="split" class="NunitoFont">
                <h1> Personal Details:</h1>
                <hr>
            </div>


            <input type="text" id="RFirstName" name="RFirstName" autocomplete="off"
                   placeholder="First Name">

            <input type="text" id="RLastName" name="RLastName" autocomplete="off"
                   placeholder="Last Name">


            <input type="email" id="REmail" name="REMail" autocomplete="off"
                   placeholder="Email Address">


            <input class="submit" type="submit" value="Register"/>
        </form>
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
    });

    /* Open when someone clicks on the span element */
    function openNav() {

        $('#myNav').animate({width: '100%'}, 600);
    }


    /* Close when someone clicks on the "x" symbol inside the overlay */
    function closeNav() {
        $("#myNav").animate({width: '0%'}, 600);
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

        //
        // $("#MainContent").delay(500).animate({
        //     'padding-bottom': $('footer').height()
        // }, "slow");


        oldArea = Area;

        // $('#MainContent').animate({padding-bottom: $('footer').height()});


        // $(+Area + ".SlideDowns").slideToggle();
        // console.log("Hello, this is coming down here");
    }


    $(window).resize(function () {
        console.log($('footer').height());

    });

    $(document).ready(function () {
        var url = window.location.href;
        var hash = url.substring(url.indexOf("#") + 1);

        if (hash.length != 0) {
            if (hash == "Register") {
                $("#LoginForm").hide();
                $("#RegisterForm").show();

                $("#LoginMode").removeClass("ActiveMode");
                $("#RegisterMode").addClass("ActiveMode");

            } else {
                $("#LoginForm").show();
                $("#RegisterForm").hide();

                $("#LoginMode").addClass("ActiveMode");
                $("#RegisterMode").removeClass("ActiveMode");

            }
        } else {
            $("#LoginForm").show();
            $("#RegisterForm").hide();

            $("#LoginMode").addClass("ActiveMode");
            $("#RegisterMode").removeClass("ActiveMode");

        }
    });

    function ShowSection(ShowRegister) {
        var AreaShow = "";
        var AreaHide = "";

        console.log("In the function");
        if (ShowRegister === true) {
            AreaShow = "Register";
            AreaHide = "Login";

        } else {
            AreaShow = "Login";
            AreaHide = "Register";

        }

        $("#" + AreaHide + "Form").hide(1000);
        $("#" + AreaShow + "Form").delay(500).show(1000);


        $("#" + AreaHide + "Mode").removeClass("ActiveMode");
        $("#" + AreaShow + "Mode").addClass("ActiveMode");

    }


</script>
</html>