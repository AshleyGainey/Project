<?php
// print_r('session here: ' . isset($_SESSION));

if (!isset($_SESSION)) {
    @ob_start();
    @session_start();
}

// print_r('session: ' . $_SESSION['userID']);

if (isset($_SESSION['userID'])) {
    header('Location: account_welcome.php');
}

if (isset($_SESSION['comeBackToCheckOut'])) {
    $comeBackToCheckOut = true;
} else {
    $comeBackToCheckOut = false;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">


    <title>Login/Register - Gadget Gainey Store</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <!--    Offline use -->
    <script src="jquery-3.4.1.min.js"></script>
</head>

<body>
    <?php include "./header.php" ?>

    <div id="mainBody">
        <div class="buttons">
            <div class="button location">
                <div id="LoginButton" class="buttonContainer selected">
                    <div class="writingOfButton">
                        <a href="#Login" onclick="ShowSection(false, true)">Login</a>
                    </div>
                </div>
            </div>
            <div class="button location">
                <div id="RegisterButton" class="buttonContainer unselected">
                    <div class="writingOfButton">
                        <a href="#Register" onclick="ShowSection(true, true)">Register</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="allElementss" id="Login">
            <div class="allElements">
                <form id="LoginForm" action="/account_welcome.php" method="post">
                    <div class="cardContainer">
                        <p>Email</p>
                        <input id="loginEmail" type="text" class="searchInput" placeholder="Email">
                    </div>
                    <div class="cardContainer">
                        <p>Password</p>
                        <input id="loginPassword" type="password" class="searchInput" placeholder="Password">
                    </div>
                    <input type="submit" value="Login">
                </form>
            </div>
        </div>

        <div class="allElementss" id="Register">
            <div class="allElements">
                <h5>Account Details</h5>
                <form action="/account_welcome.php" id="RegisterForm" method="post">
                    <div class="cardContainer inline">
                        <p>Email<span class="required">*</span></p>
                        <input id="regEmail" type="text" class="searchInput" placeholder="Email" minlength=4 maxlength=128>
                    </div>
                    <div class="cardContainer inline">
                        <p>Password<span class="required">*</span></p>
                        <input id="regPassword" type="password" class="searchInput" placeholder="Password" minlength=12 maxlength=128>
                    </div>
                    <div id="passwordValidationMessage">
                        <h2>Password must contain at least:</h2>
                        <p id="lowerCase" class="invalid">5 Letters</p>
                        <p id="number" class="invalid">2 Numbers</p>
                        <br>
                        <h2>And Must Be:</h2>
                        <p id="minLength" class="invalid">A Minimum of 12 characters</p>
                        <p id="maxLength" class="valid">A Maximum of 128 characters</p>
                    </div>
                    <div class="cardContainer floatRight">
                        <p>Confirm Password<span class="required">*</span></p>
                        <input id="regConfirmPassword" type="password" class="searchInput" placeholder="Confirm Password" minlength=12 maxlength=128>
                    </div>
                    <div id="confirmPasswordValidationMessage">
                        <p id="passwordMatch" class="hide">Passwords do not match</p>
                    </div>
                    <h5>Address Details</h5>
                    <div class="cardContainer">
                        <p>Title<span class="required">*</span></p>
                        <select id="regTitle" name="title" id="title" required>
                            <option value="Mr">Mr</option>
                            <option value="Mrs">Mrs</option>
                            <option value="Miss">Miss</option>
                            <option value="Ms">Ms</option>
                            <option value="Dr">Dr</option>
                        </select>
                    </div>

                    <div class="cardContainer">
                        <p>First Name<span class="required">*</span></p>
                        <input id="regFirstName" type="text" class="searchInput" placeholder="First Name" required pattern="^\D+$" minlength=2 maxlength=255 required>
                    </div>
                    <div class="cardContainer">
                        <p>Last Name<span class="required">*</span></p>
                        <input id="regLastName" type="text" class="searchInput" placeholder="Last Name" required pattern="^\D+$" minlength=2 maxlength=255 required>
                    </div>
                    <div class="cardContainer">
                        <p>Address Line 1<span class="required">*</span></p>
                        <input id="regAddressLine1" type="text" class="searchInput" placeholder="Address Line 1" minlength=2 maxlength=255 required>
                    </div>
                    <div class="cardContainer">
                        <p>Address Line 2</p>
                        <input id="regAddressLine2" type="text" class="searchInput" placeholder="Address Line 2" minlength=2 maxlength=255>
                    </div>
                    <div class="cardContainer">
                        <p>Town/City<span class="required">*</span></p>
                        <input id="regTownCity" type="text" class="searchInput" placeholder="Town/City" minlength=2 maxlength=255 required>
                    </div>
                    <div class="cardContainer">
                        <p>County<span class="required">*</span></p>
                        <input id="regCounty" type="text" class="searchInput" placeholder="County" minlength=2 maxlength=255 required>
                    </div>
                    <div class="cardContainer">
                        <p>Post Code<span class="required">*</span></p>
                        <input id="regPostCode" type="text" class="searchInput" placeholder="Post Code" minlength=5 maxlength=8 required>
                    </div>
                    <input type="submit" value="Register">
                </form>
            </div>
        </div>
    </div>
    <p id="regMessage"></p>
    </div>
    </div>
    </div>
    </div>
    <?php include "./footer.php" ?>
</body>

</html>
<style>
    #mainBody {
        margin-top: 30px;
        margin-left: 50px;
        margin-right: 50px;

        /* Was having an issue if I typed more than expected for the search, then it would destroy the padding 
	so have added word-wrap, this should apply to the main_container, no matter whether it is a heading 
	(h1, h2, h3 etc.), paragraph (p) or something other */
        word-wrap: break-word;

    }

    .location {
        margin: 50px;
    }

    .doAction {
        margin-left: 20px;
    }

    .buttonContainer {
        box-shadow: 0 0 0 5px #FFFFFF;
        border-radius: 2%;
        height: 75px;
        width: 250px;
        overflow: hidden;
        position: relative;
    }

    input[type=submit] {
        margin-left: 10px;
        box-shadow: 0 0 0 5px #FFFFFF;
        border-radius: 2%;
        height: 75px;
        width: 250px;
        overflow: hidden;
        position: relative;
        background-color: #1a1862;
    }

    .firstRow {
        display: inline-block;
        width: 100%;
        margin-top: 50px;
    }

    .unselected {
        background-color: #464646;
        color: #FFFFFF
    }

    .selected {
        background-color: #1a1862;
        color: #FFFFFF
    }

    .inline {
        /* display: inline-block; */
    }

    .buttons {
        margin-top: 50px;
        width: 100%;
        /* float: left; */
        margin-left: auto;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .buttonContainer .writingOfButton a {
        color: #FFFFFF
    }

    /* 
    .cardContainer {
        display: inline-block;
    } */

    .writingOfButton a {
        font-size: 25px;
        ;
        text-decoration: none;
        color: #000000;
        position: relative;
    }

    .writingOfButton img {
        float: right;
        width: 20px;
        margin-right: 2px;
    }

    .required {
        color: red;
        margin-left: 2px;
    }

    .writingOfButton {
        margin-top: 10px;
        text-align: center;
        margin: 0;
        position: absolute;
        top: 50%;
        left: 50%;
        -ms-transform: translate(-50%, -50%);
        transform: translate(-50%, -50%);
        width: 100%
    }

    .leftPart {
        float: left;
    }

    .rightPart {
        float: right;
    }

    .disabled input {
        border: 3px solid #000000;
        color: #000000;
    }

    input {
        margin: 0;
        padding: 0;
        height: 30px;
        font-size: 1.5em;
        border-radius: 25px;
        padding-top: 2%;
        padding-bottom: 2%;
        padding-right: 2%;
        padding-left: 3%;
        background: none;
        font-family: Century-Gothic, sans-serif;
        color: #FFFFFF;
        border: 3px solid #FFFFFF;
        outline: none;

    }

    .allElementss {
        display: flex;
        align-items: center;
        justify-content: center;
    }


    .cardContainer {
        margin-top: 20px;
        margin-bottom: 20px;
        /* width: 20%; */
    }

    .invalid,
    #passwordMatch {
        color: red;
    }

    .valid {
        color: green;
    }


    select:hover {
        border-color: #1a1862;
        cursor: pointer;
        border-width: 3px;
    }

    select {
        width: 100px;
        padding: 5px;
        font-size: 16px;
        line-height: 1;
        border-radius: 25px;
        height: 34px;
        background: url('images/Down%20Arrow.png') no-repeat right;
        -webkit-appearance: none;
        background-position-x: 70px;
        color: #FFFFFF;
        border-style: solid;
        border-width: 2px;
        border-color: #FFFFFF;
    }

    select option {
        background-color: #bfbfbf;
    }

    .hide {
        display: none;
    }

    .show {
        display: inline;
    }

    #regMessage {
        display: none;
    }


    #confirmPasswordValidationMessage {
        margin-bottom: 10px;
    }
</style>

<script>
    $(document).ready(function() {
        activeModes();
        // isAlreadyLoggedIn();
    });

    var passwordRequirementMet = 0;


    //If user clicks on either buttons (Register/Login) then change the form and show which
    // form is active by the colour of the button
    function ShowSection(ShowRegister, animation) {
        var show;
        var hide;

        if (ShowRegister === true) {
            show = "Register";
            hide = "Login";

        } else {
            show = "Login";
            hide = "Register";
        }

        if (animation) {
            $("#" + hide).hide(500);
            $("#" + show).delay(500).show(500);
        } else {
            $("#" + hide).hide();
            $("#" + show).show();
        }

        $("#" + hide + "Button").removeClass("selected");
        $("#" + hide + "Button").addClass("unselected");

        $("#" + show + "Button").addClass("selected");
        $("#" + show + "Button").removeClass("unselected");
    }


    //     Check URL to either show Login or Register
    function activeModes() {
        //See if Login.php is set to Register or Login by the Login.php#Register or Login.php#Login
        var url = window.location.href;
        var afterURL = url.substring(url.indexOf("#") + 1);

        if (afterURL.length != 0) {
            //Show Register if #Register
            if (afterURL == "Register") {
                ShowSection(true, false);

            } else {
                //Show Login if anything other than Register
                ShowSection(false, false);
            }
        } else {
            // Show Login if nothing there
            ShowSection(false, false);
        }
    }





    //If the password match is shown and the confirm password is the same as the password, then remove the error
    $("#regConfirmPassword").keyup(function() {
        var passwordText = document.getElementById("regPassword").value;
        var confirmPasswordText = document.getElementById("regConfirmPassword").value;
        if (passwordText == confirmPasswordText) {
            $("#passwordMatch").addClass("hide");
            $("#passwordMatch").removeClass("show");
        }
    });

    //If the password match is shown and the confirm password is the same as the password, then remove the error
    $("#regPassword").keyup(function() {
        var passwordText = document.getElementById("regPassword").value;
        var confirmPasswordText = document.getElementById("regConfirmPassword").value;
        if (passwordText == confirmPasswordText) {
            $("#passwordMatch").addClass("hide");
            $("#passwordMatch").removeClass("show");
        }
    });



    // Show/Hide the password doesn't match error based on what the password and the confirm password text is.
    $("#regConfirmPassword").focusout(function() {
        var passwordText = document.getElementById("regPassword").value;
        var confirmPasswordText = document.getElementById("regConfirmPassword").value;


        if (passwordText !== confirmPasswordText) {
            $("#passwordMatch").removeClass("hide");
            $("#passwordMatch").addClass("show");
        } else {
            $("#passwordMatch").addClass("hide");
            $("#passwordMatch").removeClass("show");
        }
    });



    //When the user presses a key on regPassword then do the following
    $("#regPassword").keyup(function() {
        var passwordText = document.getElementById("regPassword").value;
        var confirmPasswordText = document.getElementById("regConfirmPassword").value;

        //Check if 5 Uppercase or lowercase letters are in the textbox
        if (passwordText.match(/[a-zA-Z]{5}/g)) {
            $("#lowerCase").removeClass("invalid");
            $("#lowerCase").addClass("valid");
            passwordRequirementMet++;
        } else {
            $("#lowerCase").removeClass("valid");
            $("#lowerCase").addClass("invalid");
            passwordRequirementMet--;
        }

        //Check if Numbers are in the textbox
        if (passwordText.match(/[0-9]{2}/g)) {
            $("#number").removeClass("invalid");
            $("#number").addClass("valid");
            passwordRequirementMet++;
        } else {
            $("#number").removeClass("valid");
            $("#number").addClass("invalid");
            passwordRequirementMet--;
        }

        //Check if the Password hits the min length
        if (passwordText.length >= 12) {
            $("#minLength").removeClass("invalid");
            $("#minLength").addClass("valid");
            passwordRequirementMet++;
        } else {
            $("#minLength").removeClass("valid");
            $("#minLength").addClass("invalid");
            passwordRequirementMet--;
        }

        //Check if the Password doesn't hit the max length
        if (passwordText.length < 128) {
            $("#maxLength").removeClass("invalid");
            $("#maxLength").addClass("valid");
            passwordRequirementMet++;
        } else {
            $("#maxLength").removeClass("valid");
            $("#maxLength").addClass("invalid");
            passwordRequirementMet--;
        }

        if (confirmPasswordText != "") {
            if (passwordText !== confirmPasswordText) {
                $("#passwordMatch").removeClass("hide");
                $("#passwordMatch").addClass("show");
            } else {
                $("#passwordMatch").addClass("hide");
                $("#passwordMatch").removeClass("show");
            }
        }
    });


    $("#RegisterForm").submit(function(event) {

        event.preventDefault();
        var emailAddress = $("#regEmail").val();
        var password = $("#regPassword").val();

        //Personal Information values
        var title = $("#regTitle").val();
        var firstName = $("#regFirstName").val();
        var lastName = $("#regLastName").val();
        var addressLine1 = $("#regAddressLine1").val();
        var addressLine2 = $("#regAddressLine2").val();
        var townCity = $("#regTownCity").val();
        var county = $("#regCounty").val();
        var postcode = $("#regPostCode").val();
        //Do more validation with Ajax this time
        $("#regMessage").load("passwordCheck.php", {
            emailAddress: emailAddress,
            password: password,
            title: title,
            firstName: firstName,
            lastName: lastName,
            addressLine1: addressLine1,
            addressLine2: addressLine2,
            townCity: townCity,
            county: county,
            postcode: postcode,
            Register: true
        }, function(response, status, xhr) {
            debugger;
            if (status == "error") {
                var message = "An error occured while registering. Please see below :";
                document.getElementById("regMessage").style.display = "block";
                $("#regMessage").html(message + xhr.status + "" + xhr.statusText)
            }
            if (status == "success") {
                var goTocheckout = "<?php
                                    if (isset($_SESSION['comeBackToCheckOut'])) {
                                        echo true;
                                    } else {
                                        echo false;
                                    }
                                    ?>";

                if (goTocheckout) {
                    <?php
                    unset($_SESSION['comeBackToCheckOut']);
                    ?>
                    window.location.href = "checkout.php";
                } else {
                    window.location.href = "account_welcome.php#Register";
                }
            }
        })
    });





    $("#LoginForm").submit(function(event) {
        event.preventDefault();
        var emailAddress = $("#loginEmail").val();
        var password = $("#loginPassword").val();
        //Do more validation with Ajax this time
        $("#regMessage").load("passwordCheck.php", {
            emailAddress: emailAddress,
            password: password,
            Login: true
        }, function(response, status, xhr) {
            if (status == "error") {
                // var message = "An error occured while trying to do this action.";
                document.getElementById("regMessage").style.display = "block";
                document.getElementById('regMessage').innerHTML = xhr.status + " " + xhr.responseText.replaceAll('"', '');
            }
            if (status == "success") {
                debugger;


                var goTocheckout = "<?php echo $comeBackToCheckOut ?>";


                if (goTocheckout) {
                    <?php
                    unset($_SESSION['comeBackToCheckOut']);
                    $comeBackToCheckOut = null;
                    ?>
                    window.location.href = "checkout.php";
                } else {
                    window.location.href = "account_welcome.php#Login";
                }
            }
        })
    });
</script>