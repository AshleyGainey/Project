<?php
// If the session hasn't started. Start it (can then use session variables)
if (!isset($_SESSION)) {
    @ob_start();
    @session_start();
}

// If you try to come to this page (using the URL or by navigating to it) and you have already signed in, redirect to the Welcome page
if (isset($_SESSION['userID'])) {
    header('Location: account_welcome.php');
}

// If you have come here due to the Checkout page, then make a flag variable to go back to the checkout page when successful at login/register
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
    <!-- Put a viewport on this page -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Keywords of the site for search engine optimisation -->
    <meta name="keywords" content="Gadget Gainey, Gadget, Ecommerce, Online, Shop, Kids Toys, Toys, Technology, Gainey, Ashley Gainey">
    <!-- Author of the site -->
    <meta name="author" content="Ashley Gainey">
    <!-- Description of the page -->
    <meta name="description" content="Login or Register for a Gadget Gainey account to get access to your order history!">
    <!-- Shows what the title of the tab is-->
    <title>Login/Register - Gadget Gainey Store</title>
    <!-- Link to the shared classes and ID style sheet -->
    <link rel="stylesheet" type="text/css" href="sharedStyles.css">
</head>

<body>
    <!-- Add the header at the top before any other material -->
    <?php include "./header.php" ?>
    <div id="bodyOfPage">
        <!-- Div to hold both buttons -->
        <div id="loginRegisterButtons">
            <!-- Container for individual button, the Login button -->
            <div id="LoginButton" class="IndividualButton selected">
                <div class="writingOfButton">
                    <!-- Writing of the Button -->
                    <a href="#Login" onclick="ShowSection(false)">Login</a>
                </div>
            </div>
            <!-- Container for individual button -->
            <div id="RegisterButton" class="IndividualButton unselected">
                <div class="writingOfButton">
                    <!-- Writing of the Button -->
                    <a href="#Register" onclick="ShowSection(true)">Register</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Container to hold Login Form -->
    <div class="divForForm" id="Login">
        <!-- Login Form, and on submit, go to loginFormSubmit method and post the data after the client validation -->
        <form id="loginForm" action="" method="post" onsubmit="loginFormSubmit()">
            <div class="fieldContainer">
                <!-- Description of field and required span -->
                <p>Email<span class="required">*</span></p>
                <!-- Create an text input element with the type of email and some client side validation (min and max, required) -->
                <input id="loginEmail" type="email" class="changeOrAddDetailsInput" placeholder="Email" minlength=4 maxlength=255 required>
            </div>
            <div class="fieldContainer">
                <!-- Description of field and required span -->
                <p>Password<span class="required">*</span></p>
                <!-- Create an text input element with the type of password and some client side validation (min and max, required) -->
                <input id="loginPassword" type="password" class="changeOrAddDetailsInput" placeholder="Password" minlength=12 maxlength=128 required>
            </div>
            <!-- Create a button of type submit which will send the data to the backend  -->
            <input type="submit" class="changeOrAddDetailsInput" value="Login">
        </form>
    </div>

    <!-- Container to hold Register Form -->
    <div class="divForForm" id="Register">
        <!-- Register Form, and on submit, go to registerFormSubmit method and post the data after the client validation -->
        <form action="" id="registerForm" method="post" onsubmit="registerFormSubmit()">
            <h3>Account Details</h3>
            <div class="fieldContainer">
                <!-- Description of field and required span -->
                <p>Email<span class="required">*</span></p>
                <!-- Create an text input element with the type of email and some client side validation (min and max, required) -->
                <input id="regEmail" type="email" class="changeOrAddDetailsInput" placeholder="Email" minlength=4 maxlength=128 required>
            </div>
            <div class="fieldContainer">
                <!-- Description of field and required span -->
                <p>Password<span class="required">*</span></p>
                <!-- Create an text input element with the type of password and some client side validation (min and max, required) and check on Key up to check the password complexity -->
                <input id="regPassword" type="password" class="changeOrAddDetailsInput" placeholder="Password" minlength=12 maxlength=128 onkeyup="keyUpRegPassword()" required>
            </div>
            <!-- Show validation to the user and whether it has been met -->
            <div id="passwordValidationMessage">
                <h2>Password must contain at least:</h2>
                <p id="lowerCase" class="invalid">5 Letters</p>
                <p id="number" class="invalid">2 Numbers</p>
                <br>
                <h2>And Must Be:</h2>
                <p id="minLength" class="invalid">A Minimum of 12 characters</p>
                <p id="maxLength" class="valid">A Maximum of 128 characters</p>
            </div>
            <div class="fieldContainer">
                <!-- Description of field and required span -->
                <p>Confirm Password<span class="required">*</span></p>
                <!-- Create an text input element with the type of password and some client side validation (min and max, required) and check on Out of focus to see if the passwords match up to check the password complexity -->
                <input id="regConfirmPassword" type="password" class="changeOrAddDetailsInput" placeholder="Confirm Password" onfocusout="focusOutRegConfirmPassword()" onkeyup="keyUpRegConfirmPassword()" minlength=12 maxlength=128 required>
            </div>
            <!-- Display Validation Message if the Passwords (Password and Confirm Password) do not match -->
            <div id="confirmPasswordValidationMessage">
                <p id="passwordMatch" class="hide">Passwords do not match</p>
            </div>
            <br>
            <h3>Address Details</h3>
            <div class="fieldContainer">
                <!-- Description of field and required span -->
                <p>Title<span class="required">*</span></p>
                <!-- Dropdown of the title of the new user's Title - Either Mr, Master, Mrs, Miss, Ms or Dr -->
                <select id="regTitle" name="title" id="title" required>
                    <option value="Mr">Mr</option>
                    <option value="Master">Master</option>
                    <option value="Mrs">Mrs</option>
                    <option value="Miss">Miss</option>
                    <option value="Ms">Ms</option>
                    <option value="Dr">Dr</option>
                </select>
            </div>

            <div class="fieldContainer">
                <!-- Description of field and required span -->
                <p>First Name<span class="required">*</span></p>
                <!-- Create an text input for first name of the new user and some client side validation (min and max, required, regex pattern (not numbers)) -->
                <input id="regFirstName" type="text" class="changeOrAddDetailsInput" placeholder="First Name" required pattern="^\D+$" minlength=2 maxlength=255 required>
            </div>
            <div class="fieldContainer">
                <!-- Description of field and required span -->
                <p>Last Name<span class="required">*</span></p>
                <!-- Create an text input for last name of the new user and some client side validation (min and max, required, regex pattern (not numbers)) -->
                <input id="regLastName" type="text" class="changeOrAddDetailsInput" placeholder="Last Name" required pattern="^\D+$" minlength=2 maxlength=255 required>
            </div>
            <div class="fieldContainer">
                <!-- Description of field and required span -->
                <p>Address Line 1<span class="required">*</span></p>
                <!-- Create an text input for address line 1 of the new user and some client side validation (min and max, required) -->
                <input id="regAddressLine1" type="text" class="changeOrAddDetailsInput" placeholder="Address Line 1" minlength=2 maxlength=255 required>
            </div>
            <div class="fieldContainer">
                <!-- Description of field and required span -->
                <p>Address Line 2</p>
                <!-- Create an text input for address line 2 of the new user and some client side validation (min and max) -->
                <input id="regAddressLine2" type="text" class="changeOrAddDetailsInput" placeholder="Address Line 2" minlength=2 maxlength=255>
            </div>
            <div class="fieldContainer">
                <!-- Description of field and required span and some client side validation (min and max, required)-->
                <p>Town/City<span class="required">*</span></p>
                <!-- Create an text input for Town/City of the new user and some client side validation (min and max, required) -->
                <input id="regTownCity" type="text" class="changeOrAddDetailsInput" placeholder="Town/City" minlength=2 maxlength=58 required>
            </div>
            <div class="fieldContainer">
                <!-- Description of field and required span -->
                <p>County<span class="required">*</span></p>
                <!-- Create an text input for County of the new user and some client side validation (min and max, required) -->
                <input id="regCounty" type="text" class="changeOrAddDetailsInput" placeholder="County" minlength=2 maxlength=255 required>
            </div>
            <div class="fieldContainer">
                <!-- Description of field and required span -->
                <p>Post Code<span class="required">*</span></p>
                <!-- Create an text input for Post Code of the new user and some client side validation (min and max, required) -->
                <input id="regPostCode" type="text" class="changeOrAddDetailsInput" placeholder="Post Code" minlength=5 maxlength=8 required>
            </div>
            <!-- Create a button of type submit which will send the data to the backend  -->
            <input type="submit" class="changeOrAddDetailsInput" value="Register">
        </form>
    </div>
    <!-- Make text that will display any errors if there are any -->
    <p id="errorMessage"></p>
    <!-- Add the footer at the bottom after any other material -->
    <?php include "./footer.php" ?>
</body>

</html>
<style>
    .IndividualButton {
        margin: 30px;
        box-shadow: 0 0 0 5px #FFFFFF;
        border-radius: 2%;
        height: 75px;
        width: 250px;
        overflow: hidden;
        position: relative;
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

    #loginRegisterButtons {
        margin: 50px;
        width: 100%;
        margin-left: auto;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .IndividualButton .writingOfButton a {
        color: #FFFFFF
    }

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

    .divForForm {
        display: flex;
        align-items: center;
        justify-content: center;
    }


    .fieldContainer {
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

    #errorMessage {
        display: none;
    }


    #confirmPasswordValidationMessage {
        margin-bottom: 10px;
    }
</style>

<script>
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

    var passwordRequirementMet = 0;

    //If user clicks on either buttons (Register/Login) then change the forms around and show which
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

        document.getElementById(hide).style.display = 'none';
        document.getElementById(show).style.display = 'flex';

        var hideButton = document.getElementById(hide + "Button");
        hideButton.classList.add("unselected");
        hideButton.classList.remove("selected");

        var show = document.getElementById(show + "Button");
        show.classList.add("selected");
        show.classList.remove("unselected");
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

    // Client Side Validation for Password checking and update the class list of the validation messages if they have changed.
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

        // Check to see if Password is the same as Confirm Password
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

    // On Submitting the register form
    function registerFormSubmit() {
        // Prevent the default action
        event.preventDefault();
        // Get the personal information that was entered for the register form
        var emailAddress = document.getElementById("regEmail").value;
        var password = document.getElementById("regPassword").value;
        var confirmPassword = document.getElementById("regConfirmPassword").value;
        //Address Part of the register form
        var title = document.getElementById("regTitle").value;
        var firstName = document.getElementById("regFirstName").value;
        var lastName = document.getElementById("regLastName").value;
        var addressLine1 = document.getElementById("regAddressLine1").value;
        var addressLine2 = document.getElementById("regAddressLine2").value;
        var townCity = document.getElementById("regTownCity").value;
        var county = document.getElementById("regCounty").value;
        var postcode = document.getElementById("regPostCode").value;
        var title = document.getElementById("regTitle").value;


        // Then send the data to the server side (LoginRegisterProcess.php) for server client validation and if passed, then add to the DB
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

        // On return of the call
        xhr.onreadystatechange = function() {
            //See if it is ready and the status is OK
            if (xhr.readyState == 4 && xhr.status == 200) {
                // If passed and successful, don't display the error message field
                document.getElementById("errorMessage").style.display = "none";

                // If passed and successful, take the user back to the checkout if the php variable is set/if comeBackToCheckOut is true, 
                //if it isn't, then go to Account Welcome page
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
                // If not, show the error message in the errorMessage element with what it got back from the PHP file
                document.getElementById("errorMessage").style.display = "block";
                document.getElementById('errorMessage').innerHTML = xhr.status + " " + xhr.responseText;
            }
        }
    }

    // On Submitting the login form
    function loginFormSubmit() {
        // Prevent the default action
        event.preventDefault();
        var emailAddress = document.getElementById("loginEmail").value;
        var password = document.getElementById("loginPassword").value;

        // Then send the data to the server side (LoginRegisterProcess.php) for server client validation and if passed, then add to the DB
        let xhr = new XMLHttpRequest();
        xhr.open('POST', "LoginRegisterProcess.php", true)
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.send("emailAddress=" + emailAddress + "&password=" + password + "&Login=" + true);

        // On return of the call
        xhr.onreadystatechange = function() {
            //See if it is ready and the status is OK
            if (xhr.readyState == 4 && xhr.status == 200) {
                // If passed and successful, don't display the error message field
                document.getElementById("errorMessage").style.display = "none";

                // If passed and successful, take the user back to the checkout if the php variable is set/if comeBackToCheckOut is true, 
                //if it isn't, then go to Account Welcome page
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
                // If not, show the error message in the errorMessage element with what it got back from the PHP file
                document.getElementById("errorMessage").style.display = "block";
                document.getElementById('errorMessage').innerHTML = xhr.status + " " + xhr.responseText.replaceAll('"', '');
            }
        }
    }
</script>