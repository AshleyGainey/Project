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


include 'DatabaseLoginDetails.php';
// Connect to the DB
$conn = mysqli_connect($host, $user, $pass, $database);

// Query for getting the main address, binding the userID to the query
$stmt = $conn->prepare("select ua.title, ua.firstName, ua.lastName, ua.addressID, ua.addressLine1, ua.addressLine2, ua.townCity, ua.county, ua.postcode from user u INNER JOIN address ua ON u.mainAddressID = ua.addressID where u.userID  = ?");
$userID =    $_SESSION['userID'];
$stmt->bind_param("s", $userID);

if (!$stmt->execute()) {
    header('HTTP/1.1 500 Internal Server Error');
    header('Content-Type: application/json; charset=UTF-8');
    die(json_encode('ERROR - Could not load the main address for this user.'));
}

$res = $stmt->get_result();
//Get the result(s) from the query
$mainaddressDB = mysqli_fetch_all($res, MYSQLI_ASSOC);
//Save the first result (should only be one anyway) to the respected variables
$userTitle = $mainaddressDB[0]['title'];
$userFirstName = $mainaddressDB[0]['firstName'];
$userLastName = $mainaddressDB[0]['lastName'];
$userAddressID = $mainaddressDB[0]['addressID'];

$userAddressLine1 = $mainaddressDB[0]['addressLine1'];
$userAddressLine2 = $mainaddressDB[0]['addressLine2'];
$userTownCity = $mainaddressDB[0]['townCity'];
$userCounty = $mainaddressDB[0]['county'];
$userPostCode = $mainaddressDB[0]['postcode'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Shows what the title of the tab is-->
    <title>Change Address Details - Gadget Gainey Store</title>
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
    <meta name="description" content="Change details of your main address!">

    <!-- Link to the style sheet -->
    <link rel="stylesheet" type="text/css" href="sharedStyles.css">
</head>

<body>
    <!-- Add the header at the top before any other material -->
    <?php include "./header.php" ?>

    <div id="bodyOfPage">
        <div>
            <!-- Title of Page -->
            <h2>Change Your Main Address</h2>
        </div>
        <div id="AddressChangeContainer">
            <!-- Form to submit updated values -->
            <form action="" id="MainAddressForm" method="post" onsubmit="changeAddress()">
                <div class="fieldContainer">
                    <!-- Description of field and required span -->
                    <p>Title<span class="required">*</span></p>
                    <!-- Dropdown of the title of the user - Either Mr, Master, Mrs, Miss, Ms, Dr 
                    and select one of them depending on what is stored in the DB -->
                    <select id="title" name="title" id="title" required>
                        <?php
                        if ($userTitle == "Mr") {
                            echo "<option value='Mr'selected>Mr</option>";
                        } else {
                            echo "<option value='Mr'>Mr</option>";
                        }

                        if ($userTitle == "Master") {
                            echo "<option value='Master' selected>Master</option>";
                        } else {
                            echo "<option value='Master'>Master</option>";
                        }

                        if ($userTitle == "Mrs") {
                            echo "<option value='Mrs' selected>Mrs</option>";
                        } else {
                            echo "<option value='Mrs'>Mrs</option>";
                        }

                        if ($userTitle == "Miss") {
                            echo "<option value='Miss' selected>Miss</option>";
                        } else {
                            echo "<option value='Miss'>Miss</option>";
                        }

                        if ($userTitle == "Ms") {
                            echo "<option value='Ms' selected>Ms</option>";
                        } else {
                            echo "<option value='Ms'>Ms</option>";
                        }
                        if ($userTitle == "Dr") {
                            echo "<option value='Dr' selected>Dr</option>";
                        } else {
                            echo "<option value='Dr'>Dr</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="fieldContainer">
                    <!-- Description of field and required span -->
                    <p>First Name<span class="required">*</span></p>
                    <!-- Create an text input element with the value being the first name that is in the DB -->
                    <?php
                    echo "<input id='firstName' type='text' class='changeDetailsInput' value='" . $userFirstName . "' placeholder='First Name' required pattern='^\D+$' minlength=2 maxlength=255 required>";
                    ?>
                </div>
                <div class="fieldContainer ">
                    <p>Last Name<span class="required">*</span></p>
                    <!-- Create an text input element with the value being the last name that is in the DB -->
                    <?php
                    echo "<input id='lastName' type='text' class='changeDetailsInput' value='" . $userLastName . "' placeholder='Last Name' required pattern='^\D+$' minlength=2 maxlength=255 required>";
                    ?>
                </div>
                <div class="fieldContainer">
                    <p>Address Line 1<span class="required">*</span></p>
                    <!-- Create an text input element with the value being the address line 1 that is in the DB -->
                    <?php
                    echo "<input id='addressLine1' type='text' class='changeDetailsInput' value='" . $userAddressLine1 . "'  placeholder='Address Line 1' minlength=2 maxlength=255 required>";
                    ?>
                </div>
                <div class="fieldContainer">
                    <p>Address Line 2</p>
                    <!-- Create an text input element with the value being the address line 2 (if filled out) that is in the DB -->
                    <?php
                    echo "<input id='addressLine2' type='text' class='changeDetailsInput' value='" . $userAddressLine2 . "' placeholder='Address Line 2' minlength=2 maxlength=255>";
                    ?>
                </div>
                <div class="fieldContainer">
                    <p>Town/City<span class="required">*</span></p>
                    <!-- Create an text input element with the value being the Town/City that is in the DB -->
                    <?php
                    echo "<input id='townCity' type='text' class='changeDetailsInput' value='" . $userTownCity . "' placeholder='Town/City' minlength=2 maxlength=255 required>";
                    ?>
                </div>
                <div class="fieldContainer">
                    <p>County<span class="required">*</span></p>
                    <!-- Create an text input element with the value being the County that is in the DB -->
                    <?php
                    echo "<input id='county' type='text' class='changeDetailsInput' value='" . $userCounty . "' placeholder='County' minlength=2 maxlength=255 required>"
                    ?>
                </div>
                <div class="fieldContainer">
                    <p>Post Code<span class="required">*</span></p>
                    <!-- Create an text input element with the value being the Post Code that is in the DB -->
                    <?php
                    echo  "<input id='postCode' type='text' class='changeDetailsInput' value='" . $userPostCode . "' placeholder='Post Code' minlength=5 maxlength=8 required>"
                    ?>
                </div>
                <!-- Create a button of type submit which will send the data to the backend  -->
                <input type="submit" class="changeDetailsInput" value=" Save">
            </form>
            <!-- Make text that will display any errors if there are any. -->
            <p id="errorMessage"></p>
        </div>
    </div>
    <!-- Add the footer at the bottom after any other material -->
    <?php include "./footer.php" ?>
</body>

</html>
<style>
    #AddressChangeContainer {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .fieldContainer {
        margin-top: 20px;
        margin-bottom: 20px;
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

    select:hover {
        border-color: #1a1862;
        cursor: pointer;
        border-width: 3px;
    }
</style>

<script>
    // Some client side validation
    function changeAddress() {
        //Prevent the form action that was going to happen
        event.preventDefault();

        //Get the title value and check if it is Master, Mr, Mrs, Ms, MIss or Dr (any valid input), if not, display an error
        title = document.getElementById("title").value;
        if (title != "Master" && title != "Mr" &&
            title != "Mrs" && title != "Ms" && title != "Miss" &&
            title != "Dr") {
            outputMessage = "Not a value Name Title. Please fill the section in correctly";
            showHideMessage(true, outputMessage);
            return false;
        }

        //Get the first name value and check if it empty, if it is, display error
        firstName = document.getElementById("firstName").value;
        if (!firstName) {
            outputMessage = "First Name cannot be blank, please fill out that field.";
            showHideMessage(true, outputMessage);
            return false;
        }
        //Get the last name value and check if it empty, if it is, display error
        lastName = document.getElementById("lastName").value;
        if (!lastName) {
            outputMessage = "Last Name cannot be blank, please fill out that field.";
            showHideMessage(true, outputMessage);
            return false;
        }
        //Get the address line 1 value and check if it empty, if it is, display error
        addressLine1 = document.getElementById("addressLine1").value;
        if (!addressLine1) {
            outputMessage = "Address Line 1 cannot be blank, please fill out that field.";
            showHideMessage(true, outputMessage);
            return false;
        }
        addressLine2 = document.getElementById("addressLine2").value;

        //Get the Town/City value and check if it empty, if it is, display error
        townCity = document.getElementById("townCity").value;
        if (!townCity) {
            outputMessage = "Town/City cannot be blank, please fill out that field.";
            showHideMessage(true, outputMessage);
            return false;
        }
        //Get the County value and check if it empty, if it is, display error
        county = document.getElementById("county").value;
        if (!county) {
            outputMessage = "County cannot be blank, please fill out that field.";
            showHideMessage(true, outputMessage);
            return false;
        }
        //Get the Postcode value and check if it empty, if it is, display error
        postCode = document.getElementById("postCode").value;
        if (!postCode) {
            outputMessage = "Post Code cannot be blank, please fill out that field.";
            showHideMessage(true, outputMessage);
            return false;
        }
        // If passed all checks, post the results to the backend for further validation and if passed them, then add to the database
        let xhr = new XMLHttpRequest();

        xhr.open('POST', "change_details.php", true)
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.send("title=" + title +
            "&firstName=" + firstName +
            "&lastName=" + lastName +
            "&addressLine1=" + addressLine1 +
            "&addressLine2=" + addressLine2 +
            "&townCity=" + townCity +
            "&county=" + county +
            "&postcode=" + postCode +
            "&process=" + "Address"
        );

        // On return of the call
        xhr.onreadystatechange = function() {
            //See if it is ready and the status is OK
            if (xhr.readyState == 4 && xhr.status == 200) {
                // If passed and successful, take the user back to the Account Welcome page
                window.location.href = "account_welcome.php";
            } else if (xhr.readyState == 4 && (xhr.status == 400 || xhr.status == 500)) {
                // If not, show the error message in the errorMessage element
                document.getElementById("errorMessage").style.display = "block";
                document.getElementById('errorMessage').innerHTML = xhr.status + " " + xhr.responseText;
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