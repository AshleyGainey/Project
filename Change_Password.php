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
    <title>Change Your Password - Gadget Gainey Store</title>
    <!-- Character set for the page -->
    <meta charset="UTF-8">
    <!-- What type is this page? HTML-->
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <!-- Put a viewport on this page -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Keywords of the site for search engine optimisation -->
    <meta name="keywords" content="Gadget Gainey, Gadget, Ecommerce, Online, Shop, Kids Toys, Toys, Technology, Gainey, Ashley Gainey">
    <!-- Author of the site -->
    <meta name="author" content="Ashley Gainey">
    <!-- Description of the page -->
    <meta name="description" content="Change your password of your Gadget Gainey account!">
    <!-- Link to the shared classes and ID style sheet -->
    <link rel="stylesheet" type="text/css" href="sharedStyles.css">
</head>

<body>
    <!-- Add the header at the top before any other material -->

    <?php include "./header.php" ?>

    <div id="bodyOfPage">
        <div>
            <!-- Title of Page -->
            <h2>Change Your Password</h2>
        </div>
        <form id="PasswordForm" action="" method="post" onsubmit="ChangePassword()">
            <!-- Form to submit updated values -->
            <div class="cardArea">
                <div class="firstRowOfCards">
                    <!-- Description of field and required span -->
                    <div class="fieldContainer leftPart">
                        <p>Old Password<span class="required">*</span></p>
                        <!-- Textfield of the old Password -->
                        <input id="oldPassword" type="password" class="changeOrAddDetailsInput" placeholder="Old Password" required minlength=12 maxlength=128>
                    </div>

                    <div class="fieldContainer rightPart">
                        <!-- Description of field and required span -->
                        <p>New Password<span class="required">*</span></p>
                        <!-- Textfield of the new Password -->
                        <input type="password" id="newPassword" class="changeOrAddDetailsInput" placeholder="New Password" required minlength=12 maxlength=128>
                    </div>
                </div>
                <div class="secondRowOfCards">
                    <div class="fieldContainer rightPart">
                        <!-- Description of field and required span -->
                        <p>Confirm New Password<span class="required">*</span></p>
                        <!-- Textfield of confirming the new password -->
                        <input type="password" id="confirmPassword" class="changeOrAddDetailsInput" placeholder="Confirm New Password" required minlength=12 maxlength=128>
                    </div>
                </div>

                <div class="secondRowOfCards">
                    <div class="fieldContainer rightPart">
                        <!-- Create a button of type submit which will send the data to the backend  -->
                        <input type="submit" class="changeOrAddDetailsInput" value="Save">
                    </div>
                </div>
            </div>
        </form>
        <p id="errorMessage"></p>
    </div>

    </div>
    <?php include "./footer.php" ?>
</body>

</html>
<style>
    .fieldContainer {
        display: inline-block;
        width: 20%;
    }
</style>

<script>
    // Some client side validation
    function ChangePassword() {
        //Prevent the form action that was going to happen
        event.preventDefault();
        //Get the old password, the new password and confirm new password values from the textfields
        var oldPassword = document.getElementById("oldPassword").value;
        var newPassword = document.getElementById("newPassword").value;
        var confirmPassword = document.getElementById("confirmPassword").value;

        //Get the old password value and check if it empty, if it is, display error
        if (!oldPassword) {
            outputMessage = "Old Password cannot be blank, please fill out that field.";
            showHideMessage(true, outputMessage);
            return false;
        }
        //Get the new password value and check if it empty, if it is, display error
        if (!newPassword) {
            outputMessage = "New Password cannot be blank, please fill out that field.";
            showHideMessage(true, outputMessage);
            return false;
        }
        //Get the confirm password value and check if it empty, if it is, display error
        if (!confirmPassword) {
            outputMessage = "Confirm New Password cannot be blank, please fill out that field.";
            showHideMessage(true, outputMessage);
            return false;
        }

        // If passed all checks, post the results to the backend for further validation and if passed them, then add to the database
        let xhr = new XMLHttpRequest();
        xhr.open('POST', "change_details.php", true)
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.send("oldPassword=" + oldPassword +
            "&newPassword=" + newPassword +
            "&confirmPassword=" + confirmPassword +
            "&process=" + "Password"
        );

        // On return of the call
        xhr.onreadystatechange = function() {
            //See if it is ready and the status is OK
            if (xhr.readyState == 4 && xhr.status == 200) {
                // If passed and successful, take the user back to the Account Welcome page
                window.location.href = "account_welcome.php";
            } else if (xhr.readyState == 4 && (xhr.status == 400 || xhr.status == 500)) {
                // If not, show the error message in the errorMessage element with what it got back from the PHP file
                document.getElementById("errorMessage").style.display = "block";
                document.getElementById('errorMessage').innerHTML = xhr.status + " " + xhr.responseText.replaceAll('"', '');
                console.log(xhr.responseText);
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