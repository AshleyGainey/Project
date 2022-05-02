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
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="keywords" content="Gadget Gainey, Gadget, Ecommerce, Online, Shop, Kids Toys, Toys, Technology, Gainey, Ashley Gainey">
    <meta name="author" content="Ashley Gainey">
    <meta name="description" content="Login or Register for a Gadget Gainey account to get access to your order history!">
    <title>Login/Register - Gadget Gainey Store</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body>
    <?php include "./header.php" ?>

    <div id="mainBody">
        <div class="buttons">
            <div class="button location">
                <div id="LoginButton" class="buttonContainer selected">
                    <div class="writingOfButton">
                        <a href="#Login" onclick="ShowSection(false)">Login</a>
                    </div>
                </div>
            </div>
            <div class="button location">
                <div id="RegisterButton" class="buttonContainer unselected">
                    <div class="writingOfButton">
                        <a href="#Register" onclick="ShowSection(true)">Register</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="allElementss" id="Login">
            <div class="allElements">
                <form id="LoginForm" action="" method="post" onsubmit="loginFormSubmit()">
                    <div class="cardContainer">
                        <p>Email</p>
                        <input id="loginEmail" type="email" class="searchInput" placeholder="Email" minlength=4 maxlength=255 required>
                    </div>
                    <div class="cardContainer">
                        <p>Password</p>
                        <input id="loginPassword" type="password" class="searchInput" placeholder="Password" minlength=12 maxlength=128 required>
                    </div>
                    <input type="submit" value="Login">
                </form>
            </div>
        </div>

        <div class="allElementss" id="Register">
            <div class="allElements">
                <h5>Account Details</h5>
                <form action="" id="RegisterForm" method="post" onsubmit="registerFormSubmit()">
                    <div class="cardContainer inline">
                        <p>Email<span class="required">*</span></p>
                        <input id="regEmail" type="email" class="searchInput" placeholder="Email" minlength=4 maxlength=128 required>
                    </div>
                    <div class="cardContainer inline">
                        <p>Password<span class="required">*</span></p>
                        <input id="regPassword" type="password" class="searchInput" placeholder="Password" minlength=12 maxlength=128 onkeyup="keyUpRegPassword()" required>
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
                        <input id="regConfirmPassword" type="password" class="searchInput" placeholder="Confirm Password" onfocusout="focusOutRegConfirmPassword()" onkeyup="keyUpRegConfirmPassword()" minlength=12 maxlength=128 required>
                    </div>
                    <div id="confirmPasswordValidationMessage">
                        <p id="passwordMatch" class="hide">Passwords do not match</p>
                    </div>
                    <h5>Address Details</h5>
                    <div class="cardContainer">
                        <p>Title<span class="required">*</span></p>
                        <select id="regTitle" name="title" id="title" required>
                            <option value="Mr">Mr</option>
                            <option value="Master">Master</option>
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
        padding-top: 2px;
        padding-bottom: 2px;
        padding-right: 2px;
        padding-left: 10px;
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
    activeModes();
    var passwordRequirementMet = 0;


    //If user clicks on either buttons (Register/Login) then change the form and show which
    // form is active by the colour of the button
    function ShowSection(ShowRegister) {
        var show = null;
        var hide = null;

        if (ShowRegister === true) {
            show = "Register";
            hide = "Login";

        } else {
            show = "Login";
            hide = "Register";
        }


        var hideSection = document.getElementById(hide);
        hideSection.style.display = 'none';


        var showSection = document.getElementById(show);
        showSection.style.display = 'flex';


        var hideButton = document.getElementById(hide + "Button");
        hideButton.classList.add("unselected");
        hideButton.classList.remove("selected");


        var show = document.getElementById(show + "Button");
        show.classList.add("selected");
        show.classList.remove("unselected");
    }


    //     Check URL to either show Login or Register
    function activeModes() {
        //See if Login.php is set to Register or Login by the Login.php#Register or Login.php#Login
        var url = window.location.href;
        var afterURL = url.substring(url.indexOf("#") + 1);

        if (afterURL.length != 0) {
            //Show Register if #Register
            if (afterURL == "Register") {
                ShowSection(true);

            } else {
                //Show Login if anything other than Register
                ShowSection(false);
            }
        } else {
            // Show Login if nothing there
            ShowSection(false);
        }
    }





    //If the password match is shown and the confirm password is the same as the password, then remove the error
    function keyUpRegConfirmPassword() {
        var passwordText = document.getElementById("regPassword").value;
        var confirmPasswordText = document.getElementById("regConfirmPassword").value;
        if (passwordText == confirmPasswordText) {
            var element = document.getElementById("passwordMatch");
            element.classList.add("hide");
            element.classList.remove("show");
        }
    }

    // Show/Hide the password doesn't match error based on what the password and the confirm password text is.
    function focusOutRegConfirmPassword() {
        var passwordText = document.getElementById("regPassword").value;
        var confirmPasswordText = document.getElementById("regConfirmPassword").value;


        if (passwordText !== confirmPasswordText) {
            var element = document.getElementById("passwordMatch");
            element.classList.remove("hide");
            element.classList.add("show");
        } else {
            var element = document.getElementById("passwordMatch");
            element.classList.add("hide");
            element.classList.remove("show");
        }
    }

    function keyUpRegPassword() {
        var passwordText = document.getElementById("regPassword").value;
        var confirmPasswordText = document.getElementById("regConfirmPassword").value;

        //Check if 5 Uppercase or lowercase letters are in the textbox
        if (passwordText.match(/[a-zA-Z]{5}/g)) {
            var lowerCase = document.getElementById("lowerCase");
            lowerCase.classList.remove("invalid");
            lowerCase.classList.add("valid");
            passwordRequirementMet++;
        } else {
            var lowerCase = document.getElementById("lowerCase");
            lowerCase.classList.remove("valid");
            lowerCase.classList.add("invalid");
            passwordRequirementMet--;
        }

        //Check if Numbers are in the textbox
        if (passwordText.match(/[0-9]{2}/g)) {
            var number = document.getElementById("number");
            number.classList.remove("invalid");
            number.classList.add("valid");
            passwordRequirementMet++;
        } else {
            var number = document.getElementById("number");
            number.classList.remove("valid");
            number.classList.add("invalid");
            passwordRequirementMet--;
        }

        //Check if the Password hits the min length
        if (passwordText.length >= 12) {
            var minLength = document.getElementById("minLength");
            minLength.classList.remove("invalid");
            minLength.classList.add("valid");
            passwordRequirementMet++;
        } else {
            var minLength = document.getElementById("minLength");
            minLength.classList.remove("valid");
            minLength.classList.add("invalid");
            passwordRequirementMet--;
        }

        //Check if the Password doesn't hit the max length
        if (passwordText.length < 128) {
            var maxLength = document.getElementById("maxLength");
            maxLength.classList.remove("invalid");
            maxLength.classList.add("valid");
            passwordRequirementMet++;
        } else {
            var maxLength = document.getElementById("maxLength");
            maxLength.classList.remove("valid");
            maxLength.classList.add("invalid");
            passwordRequirementMet--;
        }

        if (confirmPasswordText != "") {
            if (passwordText !== confirmPasswordText) {
                var passwordMatch = document.getElementById("passwordMatch");
                passwordMatch.classList.remove("hide");
                passwordMatch.classList.add("show");
            } else {
                var passwordMatch = document.getElementById("passwordMatch");
                passwordMatch.classList.remove("show");
                passwordMatch.classList.add("hide");
            }
        }
    }


    function registerFormSubmit() {

        event.preventDefault();
        var emailAddress = document.getElementById("regEmail").value;
        var password = document.getElementById("regPassword").value;
        var confirmPassword = document.getElementById("regConfirmPassword").value;


        //Personal Information values
        var title = document.getElementById("regTitle").value;
        var firstName = document.getElementById("regFirstName").value;
        var lastName = document.getElementById("regLastName").value;
        var addressLine1 = document.getElementById("regAddressLine1").value;
        var addressLine2 = document.getElementById("regAddressLine2").value;
        var townCity = document.getElementById("regTownCity").value;
        var county = document.getElementById("regCounty").value;
        var postcode = document.getElementById("regPostCode").value;
        var title = document.getElementById("regTitle").value;


        let xhr = new XMLHttpRequest();

        xhr.open('POST', "LoginRegisterProcess.php", true)
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.send("emailAddress=" + emailAddress +
            "&password=" + password +
            "&confirmPassword=" + confirmPassword +
            "&title=" + title +
            "&firstName=" + firstName +
            "&lastName=" + lastName +
            "&addressLine1=" + addressLine1 +
            "&addressLine2=" + addressLine2 +
            "&townCity=" + townCity +
            "&county=" + county +
            "&postcode=" + postcode +
            "&Register=" + true);


        // Create an event to receive the return.
        xhr.onreadystatechange = function() {
            if (xhr.readyState == 4 && xhr.status == 200) {
                document.getElementById("regMessage").style.display = "none";

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
            } else {
                var message = "An error occured while registering. Please see below :";
                document.getElementById("regMessage").style.display = "block";
                document.getElementById('regMessage').innerHTML = xhr.status + " " + xhr.responseText;
            }
        }
    }





    function loginFormSubmit() {
        event.preventDefault();
        var emailAddress = document.getElementById("loginEmail").value;
        var password = document.getElementById("loginPassword").value;


        let xhr = new XMLHttpRequest();

        xhr.open('POST', "LoginRegisterProcess.php", true)
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.send("emailAddress=" + emailAddress + "&password=" + password + "&Login=" + true);


        // Create an event to receive the return.
        xhr.onreadystatechange = function() {
            if (xhr.readyState == 4 && xhr.status == 200) {
                document.getElementById("regMessage").style.display = "none";

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
            } else {
                document.getElementById("regMessage").style.display = "block";
                document.getElementById('regMessage').innerHTML = xhr.status + " " + xhr.responseText.replaceAll('"', '');
            }
        }
    }
</script>