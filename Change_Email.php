<?php
// If the session hasn't started. Start it (can then use session variables)
if (!isset($_SESSION)) {
    @ob_start();
    session_start();
}
//If you try to come to this page (using the URL or by navigating to it) and you haven't signed in yet, redirect to the Login page to sign in
if (!isset($_SESSION['userID'])) {
    header('Location: Login.php');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Shows what the title of the tab is-->
    <title>Change Your Email - Gadget Gainey Store</title>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <!-- Put a viewport on this page -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Keywords of the site for search engine optimisation -->
    <meta name="keywords" content="Gadget Gainey, Gadget, Ecommerce, Online, Shop, Kids Toys, Toys, Technology, Gainey, Ashley Gainey">
    <!-- Icons for Gadget Gainey - Based on the size and who uses them -->
    <link rel="apple-touch-icon" sizes="180x180" href="images/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="images/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="images/favicon/favicon-16x16.png">
    <link rel="manifest" href="images/favicon/site.webmanifest">
    <link rel="mask-icon" href="images/favicon/safari-pinned-tab.svg" color="#5bbad5">
    <link rel="shortcut icon" href="images/favicon/favicon.ico">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="msapplication-config" content="images/favicon/browserconfig.xml">
    <meta name="theme-color" content="#ffffff">
    <!-- Author of the site -->
    <meta name="author" content="Ashley Gainey">
    <!-- Description of the page -->
    <meta name="description" content="Change your email of your Gadget Gainey account!">

    <!-- Link to the shared classes and ID style sheet -->
    <link rel="stylesheet" type="text/css" href="sharedStyles.css">
</head>

<body>
    <!-- Accessibility Feature to help Screen Readers and to skip the Tab Counter to the main content -->
    <a id="skipToContent" href="#bodyOfPage">
        Skip to Main Content
    </a>
    <!-- Add the header at the top before any other material -->
    <?php include "./header.php" ?>

    <div id="bodyOfPage">
        <div>
            <!-- Title of Page -->
            <h2>Change Your Email</h2>
        </div>
        <!-- Form to submit updated values -->
        <form id="EmailForm" action="" method="post" onsubmit="changeEmail()">
            <div class="firstRowOfCards">
                <!-- Description of field with the item adding a class of disabled to show it in black text-->
                <div class="fieldContainer leftPart disabled">
                    <p>Old Email</p>
                    <!-- Textfield of the old Email address (in a disabled state), the value equal to the old email from the session variable of userEmail -->
                    <?php
                    echo "<input type='text' id='oldEmail' class='changeOrAddDetailsInput' disabled value='" . $_SESSION['userEmail'] . "' > ";
                    ?>
                </div>
                <div class="fieldContainer rightPart">
                    <!-- Description of field and required span -->
                    <p>New Email<span class="required">*</span></p>
                    <!-- Create an text input element - it will be used to get the requested new email. -->
                    <input type="email" id="newEmail" class="changeOrAddDetailsInput" placeholder="New Email" minlength=4 maxlength=254 required>
                </div>
            </div>
            <div class="secondRowOfCards">
                <div class="fieldContainer rightPart">
                    <!-- Description of field and required span -->
                    <p>Your Password<span class="required">*</span></p>
                    <!-- For this change, they will need to enter their password, therefore, create a new text input and have it as the password textfield -->
                    <input type="password" id="password" class="changeOrAddDetailsInput" placeholder="Your Password" minlength=12 maxlength=128 required>
                </div>
            </div>

            <div class="secondRowOfCards">
                <div class="fieldContainer rightPart">
                    <!-- Create a button of type submit which will send the data to the backend  -->
                    <input type="submit" class="changeOrAddDetailsInput" value="Save">
                </div>
            </div>
        </form>
    </div>
    <!-- Make text that will display any errors if there are any. -->
    <p id="errorMessage"></p>
    <!-- Add the footer at the bottom after any other material -->
    <?php include "./footer.php" ?>
</body>

</html>
<style>
    .disabled input {
        border: 3px solid #000000;
        color: #000000;
    }

    .fieldContainer {
        display: inline-block;
        width: 20%;
    }
</style>

<script>
    // Some client side validation
    function changeEmail() {
        //Prevent the form action that was going to happen
        event.preventDefault();
        //Get the new email address and password value
        var emailAddress = document.getElementById("newEmail").value;
        var password = document.getElementById("password").value;

        //Get the first name value and check if it empty, if it is, display error
        if (!emailAddress) {
            outputMessage = "New Email Address cannot be blank, please fill out that field.";
            showHideMessage(true, outputMessage);
            return false;
        }
        //Get the last name value and check if it empty, if it is, display error
        if (!password) {
            outputMessage = "Password cannot be blank, please fill out that field.";
            showHideMessage(true, outputMessage);
            return false;
        }



        // If passed all checks, post the results to the backend for further validation and if passed them, then add to the database
        let xhr = new XMLHttpRequest();
        xhr.open('POST', "change_details.php", true)
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.send("emailAddress=" + emailAddress +
            "&password=" + password +
            "&process=" + "Email"
        );

        // On return of the call
        xhr.onreadystatechange = function() {
            //See if it is ready and the status is OK
            if (xhr.readyState == 4 && xhr.status == 200) {
                // If passed and successful, take the user back to the Account Welcome page
                window.location.href = "account_welcome.php";
            } else if (xhr.readyState == 4 && (xhr.status == 400 || xhr.status == 500)) {
                // If not, show the error message in the errorMessage element with what it got back from the PHP file
                message = xhr.status + " " + JSON.parse(xhr.responseText);
                showHideMessage(true, message)
            }
        }
    }

    //Method to show and hide the errorMessage and set its value
    function showHideMessage(show, message) {
        if (show) {
            document.getElementById("errorMessage").innerHTML = message;
            document.getElementById("errorMessage").style.display = "block";
        } else {
            document.getElementById("errorMessage").style.display = "none";
        }
    }
</script>