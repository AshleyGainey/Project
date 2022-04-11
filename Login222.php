<!DOCTYPE html>
<html lang="en">
<?php
session_start()
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="images/MasterPage/android-chrome-192x192.png"/>
    <title>Login/Register - Hogwarts University</title>
    <link href="https://fonts.googleapis.com/css?family=Nunito|Roboto+Condensed" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script type="text/javascript" src="my_script.js"></script>
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="stylesheet" type="text/css" href="responsiveness.css">
    <link rel="stylesheet" type="text/css" href="login_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body class="NunitoFont">
<div>
    <header>
        <a class="skipToContent" href="#MainContent">
            Skip to content
        </a>
        <div id="header">
            <a href="index.php">
                <img id="logo"
                     src="images/MasterPage/Hogwarts Logo (No Background) - Small.png"
                     alt="Hogwarts University Logo - A yellow Crest with the words 'Draco Dormiens Nunquam Titillandus'
                 written on it and has got four animals in the four different sections with a H in the middle. These
                 sections are different colours representing the four houses, red being a Lion representing Gryffindor
                 house, green being a Snake - representing Slytherin house, yellow being a Badger - representing
                 Hufflepuff and blue being a Ravenclaw - representing the Ravenclaw house."/>
                <h1 class="hogwartsFont">Hogwarts University</h1>
            </a>


            <div id="hamburgerMenu" class="HeaderRightButton">
                <a href="#0">
                    <img alt="Menu icon - three white rounded rectangles stacked on top of each other"
                         src="images/MasterPage/Hamburger Menu.png"/>
                    <h2 class="NunitoFont">Menu</h2>
                </a>
            </div>

            <!--Full Screen Nav Bar for Mobile Users-->

            <div id="navigationFullScreenMenu">

                <!--Exit Button to go back to the page-->
                <div><a class="exitButton" href="#0">X</a></div>
                <div class="fullScreenContent">
                    <a href="#0">About Hogwarts University</a>
                    <hr>
                    <a href="#0">News</a>
                    <hr>
                    <a href="#0">Support</a>
                    <hr>
                    <a id="loginOrAccountHamburger">Login/Register</a>
                </div>
            </div>

            <a id="LoginMenuHolder" class="HeaderRightButton" href="Login.php">
                <img alt="Login/Register icon" src="images/MasterPage/Account Icon.png"/>
                <h2 id="loginOrAccountNormal" class="NunitoFont">Login/Register</h2>
            </a>

            <div id="fullWidthNavBar" class="NunitoFont">
                <ul>
                    <li><a href="#0">About Hogwart's University</a></li>
                    <li><a href="#0">News</a></li>
                    <li><a href="#0">Support</a></li>
                </ul>
            </div>
    </header>
    <div id="topArea">
        &nbsp;<a href="search.php">
            <div id="SearchBook">
                <img alt="Search Book - White Magnifying Glass with a white book inside it"
                     src="images/MasterPage/Search.png"/>
                <p>Search Books</p>
            </div>
        </a>
    </div>
    <!--</div>-->
    <div id="MainContent" class="centreDiv">
        <div id="LoginRegisterButtons" class="centreDiv col-12">
            <div class="centreDiv Modes ActiveMode" id="LoginMode">
                <a href="#Login" onclick="ShowSection(false)">
                    <div class="centreText NunitoFont col-2 ">
                        <h2>Login</h2>
                    </div>
                </a>
            </div>

            <div class="centreDiv Modes" id="RegisterMode">
                <a href="#Register" onclick="ShowSection(true)">
                    <div class="centreText NunitoFont col-2 ">
                        <h2>Register</h2>
                    </div>
                </a>
            </div>

        </div>
    </div>

    <div>
        <form action="validateRegisterOrLogin.php" id="LoginForm" class="centreDiv" method="POST">
            <input id="lUsername" type="text" name="lUsername" autocomplete="off"
                   placeholder="Username/Student ID" minlength=6 maxlength=6
                   required>


            <input id="lPassword" type="password" name="lPassword" autocomplete="off"
                   placeholder="Password" minlength=8 maxlength=99 required>
            <br>
            <input id="lSubmit" class="submit" type="submit" value="Login"/>
            <p class="message"></p>
        </form>
    </div>

    <div>
        <form action="account.php" method="POST" id="RegisterForm" class="centreDiv">
            <input id="rUsername" type="text" name="rUsername" autocomplete="off"
                   placeholder="Username/Student ID" minlength=6 maxlength=6
                   required>


            <input id="rPassword" type="password" name="rPassword" autocomplete="off"
                   placeholder="Password" minlength=8 maxlength=99
                   onfocusin="passwordRequirement(true)"
                   onfocusout="passwordRequirement(false)"
                   required>


            <div id="passwordValidationMessage">
                <h2>Password must contain at least:</h2>
                <p id="lowerCase" class="invalid">1 Lowercase letter</p>
                <p id="upperCase" class="invalid">1 Uppercase letter</p>
                <p id="number" class="invalid">1 Number</p>
                <p id="special" class="invalid">1 Special Character</p>

                <br>
                <h2>And Must Be:</h2>

                <p id="minLength" class="invalid">A Minimum of 8 characters</p>
                <p id="maxLength" class="valid">A Max of 99 characters</p>

            </div>

            <div id="split" class="NunitoFont">
                <h1> Personal Details:</h1>
                <hr class="showFullWidth">
            </div>


            <input type="text" id="rFirstName" name="rFirstName" autocomplete="off"
                   placeholder="First Name" maxlength=99 pattern="^\D+$" required>

            <input type="text" id="rLastName" name="rLastName" autocomplete="off"
                   placeholder="Last Name" maxlength=99 pattern="^\D+$" required>


            <input type="email" id="rEmail" name="rEMail" autocomplete="off"
                   placeholder="Email Address" maxlength=254 required>


            <input id="rSubmit" type="submit" value="Register"/>
            <p class="rMessage"></p>

        </form>
    </div>
</div>
</body>

<footer class="NunitoFont col-12">
    <div id="Study" class="category col-3">
        <a class="categoryHeader">
            <div>
                <h1>Study</h1>
                <img class="footerExpandArrow" alt="Expand Study Footer Section icon"
                     src="images/MasterPage/Expand Icon.png">
            </div>
        </a>

        <div class="SubCategory">
            <a href="Search.php">Search Books</a>
            <a href="Login.php">Login</a>
            <a href="#0" onclick="ShowSection(true)">Register</a>
        </div>
    </div>

    <div id="About" class="category col-4">
        <a class="categoryHeader">
            <div>
                <h1>About</h1>
                <img class="footerExpandArrow" alt="Expand Study Footer Section icon"
                     src="images/MasterPage/Expand Icon.png">
            </div>
        </a>

        <div class="SubCategory">
            <a href="#">About Hogwarts University</a>
            <a href="libraries.php">Our Libraries</a>
            <a href="#">University Policies</a>
        </div>
    </div>

    <div id="GetInTouch" class="category col-3">
        <a class="categoryHeader">
            <div>
                <h1>Support</h1>
                <img class="footerExpandArrow" alt="Expand Study Footer Section icon"
                     src="images/MasterPage/Expand Icon.png">
            </div>
        </a>

        <div class="SubCategory">
            <a href="#">Contact Us</a>
            <a href="#">FAQ's</a>
            <a href="#">Help</a>
        </div>
    </div>
    <div id="Copyright" class="col-12">
        <h4>© Copyright <?php
            echo date("Y"); ?> Hogwarts University</h4>
    </div>
</footer>
<script>
    var oldArea = null;
    var sameArea = false;
    var passwordText = document.getElementById("rPassword");
    $(document).ready(function () {
        //Checks if user is already logged in, doesn't need to be on this page if they are.
        isAlreadyLoggedIn();

        displayAccountOrLoginText();

        //Make the footer device friendly
        footerResponsive();


        //On resize, go to the footer Responsive
        $(window).resize(function () {
            footerResponsive();
        });


        //See whether URL has #Register at the end of it. And switch between login and register when first loading the page
        activeModes();

        //Client Side Validation for Login Username
        $("#lUsername").keypress(function (e) {
            // if letter
            if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                return false;
            }
        });


        //Client Side Validation for Register Username
        $("#rUsername").keypress(function (e) {
            // if letter
            if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                return false;
            }
        });
        //Client Side Validation for Register First Name
        $("#rFirstName").keypress(function (e) {

            if (!(e.which < 48 || e.which > 57)) {
                return false;
            }
        });

        //Client Side Validation for Register Last Name
        $("#rLastName").keypress(function (e) {

            if (!(e.which < 48 || e.which > 57)) {
                return false;
            }
        });

        $("#LoginForm").submit(function (event) {
            event.preventDefault();
            var username = $("#lUsername").val();
            var password = $("#lPassword").val();
            var submit = $("#lSubmit").val();

            //Do more validation with Ajax this time
            $(".message").load("validateRegisterOrLogin.php", {
                username: username,
                password: password,
                lSubmit: submit
            });
        });

        $("#RegisterForm").submit(function (event) {
            event.preventDefault();
            var username = $("#rUsername").val();
            var password = $("#rPassword").val();

            //Personal Information values
            var firstName = $("#rFirstName").val();
            var lastName = $("#rLastName").val();
            var emailAddress = $("#rEmail").val();
            var submit = $("#rSubmit").val();

            //Do more validation with Ajax this time
            $(".rMessage").load("validateRegisterOrLogin.php", {
                username: username,
                password: password,
                firstName: firstName,
                lastName: lastName,
                email: emailAddress,
                rSubmit: submit
            }, function (response, status, xhr) {

                if (status == "error") {
                    var message = "Sorry but there was an error: ";
                    $("#AllResults").html(message + xhr.status + "" + xhr.statusText)
                }
            });
        })
    });

    //If logged in, skip to account if accidentally got to this URL
    function isAlreadyLoggedIn() {
        var isAlreadyLoggedIn = <?php
            if (isset($_SESSION['userID'])) {
                echo 'true';
            } else {
                echo 'false';
            }
            ?>;

        if (isAlreadyLoggedIn) {
            window.location.replace("account.php");
        }
    }


    //Tried to put in separate js but cannot due to errors.
    //This will expand the footer if in mobile mode.
    function expandFooter(Area) {
        //If first time expanding
        if (oldArea == null) {
            //Then rotate the arrow
            $("#" + Area + "> a > div > img").addClass("rotateArrow");
            sameArea = false;
        } else {
            //If not opening/closing the same area
            if (oldArea != Area) {
                //It must be different area, so slide every sub category up and then change the rotation of arrows
                $(".SubCategory").slideUp();
                $("#" + oldArea + "> a > div > img").removeClass("rotateArrow");
                $("#" + Area + "> a > div > img").addClass("rotateArrow");
                sameArea = false;
            } else {
                // If it is the same area then old area is null and rotate the arrow back.
                oldArea = null;
                $("#" + Area + "> a > div > img").removeClass("rotateArrow");
                sameArea = true;
            }
        }

        //Expand or collapse Area/
        $("#" + Area + " > div").slideToggle();

        if (sameArea) {
            oldArea = null;
        } else {
            oldArea = Area;
        }
    }


   //     Change between Login and Register via the URL
   function activeModes() {

        //See if Login.php is set to Register or Login by the Login.php#Register
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
    }
    //If user clicks on either buttons (Register/Login) then change the form and show which
    // form is active by the colour of the button
    function ShowSection(ShowRegister) {
        var AreaShow = "";
        var AreaHide = "";

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


    //Register Password Strength Checker
    //    if password textbox on focus, then show the password requirement div, if not.
    //    Then don't display it/hide it
    function passwordRequirement(showPasswordRequirement) {
        if (showPasswordRequirement) {
            $("#passwordValidationMessage").css('display', 'block');
        } else {
            $("#passwordValidationMessage").css('display', 'none');
        }
    }


    //When the user presses a key on RPassword then do the following
    $("#rPassword").keyup(function () {

        //Check if Lowercase is in the textbox
        if (passwordText.value.match(/[a-z]/g)) {
            $("#lowerCase").removeClass("invalid");
            $("#lowerCase").addClass("valid");
        } else {
            $("#lowerCase").removeClass("valid");
            $("#lowerCase").addClass("invalid");
        }

        //Check if Uppercase is in the textbox
        if (passwordText.value.match(/[A-Z]/g)) {
            $("#upperCase").removeClass("invalid");
            $("#upperCase").addClass("valid");
        } else {
            upperCase
            $("#upperCase").removeClass("valid");
            $("#upperCase").addClass("invalid");
        }

        //Check if Numbers are in the textbox
        var numbers = /[0-9]/g;
        if (passwordText.value.match(numbers)) {
            $("#number").removeClass("invalid");
            $("#number").addClass("valid");
        } else {
            $("#number").removeClass("valid");
            $("#number").addClass("invalid");
        }

        //Check if the Password hits the min length
        if (passwordText.value.length >= 8) {
            $("#minLength").removeClass("invalid");
            $("#minLength").addClass("valid");
        } else {
            $("#minLength").removeClass("valid");
            $("#minLength").addClass("invalid");
        }

        //Check if the Password doesn't hit the max length
        if (passwordText.value.length < 100) {
            $("#maxLength").removeClass("invalid");
            $("#maxLength").addClass("valid");
        } else {
            $("#maxLength").removeClass("valid");
            $("#maxLength").addClass("invalid");
        }

        //Check if accepted special characters are in the textbox
        var special = /^[ A-Za-z0-9_@./#&+-]*$/;
        if (!special.test(passwordText.value)) {
            $("#special").removeClass("invalid");
            $("#special").addClass("valid");
        } else {
            $("#special").removeClass("valid");
            $("#special").addClass("invalid");
        }
    });

    //    Make the footer responsive
    function footerResponsive() {
        //If Desktop

        //If Scrollbar
        if ($(document).height() > $(window).height()) {
            $width = $(window).width() + 10;
        } else {
            $width = $(window).width();
        }

        if ($width > 700) {

            // Don't let Study, About and Support be focusable on keyboard
            $('.categoryHeader').removeAttr("href");
            // Don't allow the headers to be clickable when sub categories are shown
            $('.categoryHeader *').removeAttr('onclick');
            $(".SubCategory").css("display", "inline-block");

            $('.categoryHeader').prop("onclick", null);

        } else {
            //If in mobile mode

            // Add a href so it can be focusable for keyboard
            $('.categoryHeader').attr('href', '#0');

            // Allow headers in footer to be clicked and expanded
            $("#Study > .categoryHeader").attr("onclick", 'expandFooter("Study")');
            $("#About > .categoryHeader").attr("onclick", 'expandFooter("About")');
            $("#GetInTouch > .categoryHeader").attr("onclick", 'expandFooter("GetInTouch")');
            $(".SubCategory").css("display", "none");
        }
    }

    // Respond to if there is already a user logged in
    function displayAccountOrLoginText() {
        //Check if user is logged in
        var sessionSet = <?php
            if (isset($_SESSION['userID'])) {
                echo 'true';
            } else {
                echo 'false';
            }
            ?>;

        //If they aren't then Display Login/Register and change href to login,
        //If they are then Display My Account and change href to Account.php,
        if (!sessionSet) {
            var LoginRegisterText = "Login/Register";
            var href = "Login.php";
        } else {
            var LoginRegisterText = "My Account";
            var href = "Account.php";
        }

        //Change attributes and text in the Hamburger and Login/Register
        $('#loginOrAccountHamburger').attr('href', href);
        $('#LoginMenuHolder').attr('href', href);

        $('#loginOrAccountHamburger').text(LoginRegisterText);
        $('#loginOrAccountNormal').text(LoginRegisterText);
    }

</script>
</html>