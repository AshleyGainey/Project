<?php
// If the session hasn't started. Start it (can then use session variables)
if (!isset($_SESSION)) {
    @ob_start();
    session_start();
}
// If the user is changing their Email address
if ($_POST['process'] == "Email") {
    // Get the new email address and the password that they have entered
    $new_email = $_POST['emailAddress'];
    $password = $_POST['password'];
    //Server Side validation to see if any fields are empty
    if (empty($new_email)) {
        header('HTTP/1.1 400 Bad Request');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - New Email Address is empty. Please fill it out.'));
    }
    if (empty($password)) {
        header('HTTP/1.1 400 Bad Request');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - Password is empty. Please fill it out.'));
    }
    // Server Side validation to see if new email is at least 4 characters long.
    if (strlen($new_email) < 4) {
        header('HTTP/1.1 400 Bad Request');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - Email length is too weak. It must be a minimum of 4 characters.'));
    }

    // Server Side validation to see if new email is not longer than 254 characters long
    if (strlen($new_email) > 254) {
        header('HTTP/1.1 400 Bad Request');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - Email length is too strong. It must be a maximum of 254 characters.'));
    }


    // Server Side validation to see if email is in the correct format (From: https://ihateregex.io/expr/email/#)
    if (!(preg_match('/[^@ \t\r\n]+@[^@ \t\r\n]+\.[^@ \t\r\n]+/', $new_email))) {
        header('HTTP/1.1 400 Bad Request');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - Incorrect Format for email. Please fill it out correctly. \'FirstPartOfEmail@EmailDomain.com\'. '));
    }

    // Server Side validation to see if Password has enough complexity, if not, therefore, don't go to the database to check if it is correct (At least 5 letters)
    if (!(preg_match('/[a-zA-Z]{5}/', $password))) {
        header('HTTP/1.1 400 Bad Request');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - Password not correct'));
    }
    // Server Side validation to see if Password has enough complexity, if not, therefore, don't go to the database to check if it is correct (At least 2 numbers)
    if (!(preg_match('/[0-9]{2}/', $password))) {
        header('HTTP/1.1 400 Bad Request');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - Password not correct'));
    }
    // // Server Side validation to see if Password has enough complexity, if not, therefore, don't go to the database to check if it is correct (At least 12 characters)
    if (strlen($password) < 11) {
        header('HTTP/1.1 400 Bad Request');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - Password not correct'));
    }
    // Server Side validation to see if Password has enough complexity, if not, therefore, don't go to the database to check if it is correct (Maximum 128 characters)
    if (strlen($password) > 128) {
        header('HTTP/1.1 400 Bad Request');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - Password not correct'));
    }

    //If email and password validation found no faults...
    // Get the user ID from the session
    $userID = $_SESSION['userID'];

    //Get the Database login details
    include 'DatabaseLoginDetails.php';
    //Make the connection
    $conn = mysqli_connect($host, $user, $pass, $database);
    //Prepare the statement - query to get the user password from the database so it can be compared
    $stmt = $conn->prepare("SELECT userPassword From user where userID = ?");
    //Bind the variable to the prepared statement
    $stmt->bind_param("s", $userID);
    // Execute the query
    if (!$stmt->execute()) {
        //Couldn't execute query so stop there
        header('HTTP/1.1 500 Internal Server Error');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - Could not update your email address.'));
    }

    //Get results from the database
    $res = $stmt->get_result();
    //Store the result in a variable
    $userFromDB = mysqli_fetch_all($res, MYSQLI_ASSOC);

    //Check if the password they have entered is the same as what is in the database by using password_verify (have to use if encrypted)
    if (password_verify($password, $userFromDB[0]['userPassword'])) {

        // If correct, then we can update the record
        $conn = new mysqli($host, $user, $pass, $database);
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        //Prepare the statement - query to update the user's email
        $stmt = $conn->prepare("UPDATE user SET userEmail = ? where userID = ?");
        // Bind the parameters of email and the user's ID to the query
        $stmt->bind_param("si", $userEmail, $userID);
        // Execute query
        if ($stmt->execute()) {
            //The sessions email address is now the email address put in by the user
            $_SESSION['userEmail'] = $new_email;
            //Send back an ok message
            header('HTTP/1.1 200 OK');
            header('Content-Type: application/json; charset=UTF-8');
            die(json_encode('Executed successfully'));
        } else {
            //Cannot execute the email query so therefore send back a 500 Internal Server Error with the message of ERROR - Cannot execute Update Query
            echo "Error updating record: " . $conn->error;
            header('HTTP/1.1 500 Internal Server Error');
            header('Content-Type: application/json; charset=UTF-8');
            die(json_encode('ERROR - Cannot execute Update Query'));
        }
    } else {
        //If the hashed password coming back from the query is not the same as the hash password that was typed in via the user, then tell the user that the password was not correct
        header('HTTP/1.1 400 Bad Request');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - Password not correct'));
    }
    //If the changing process is password instead
} else if ($_POST['process'] == "Password") {
    // Check values from POST to see if they are not set.
    if (!isset($_POST['oldPassword']) || isset($_POST['newPassword'])|| isset($_POST['confirmPassword'])) {
        header('HTTP/1.1 400 Bad Request');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - Fields haven\'t been filled out correctly'));
    }

    //Get values of oldPassword, newPassword and confirmPassword from the POST request
    $oldPassword = $_POST['oldPassword'];
    $newPassword = $_POST['newPassword'];
    $confirmPassword = $_POST['confirmPassword'];

    //Server Side validation to see if any fields are empty
    if (empty($oldPassword)) {
        header('HTTP/1.1 400 Bad Request');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - Old Password is empty. Please fill it out.'));
    }
    if (empty($newPassword)) {
        header('HTTP/1.1 400 Bad Request');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - New Password is empty. Please fill it out.'));
    }

    if (empty($confirmPassword)) {
        header('HTTP/1.1 400 Bad Request');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - Confirm New Password is empty. Please fill it out.'));
    }
    // Server Side validation to see if the new password has enough complexity. (At least 5 letters)
    if (!(preg_match('/[a-zA-Z]{5}/', $newPassword))) {
        header('HTTP/1.1 400 Bad Request');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - Password complexity has not been met. The password needs to have at least 5 letters (Uppercase or lowercase)'));
    }
    // Server Side validation to see if the new password has enough complexity. (At least 2 numbers)
    if (!(preg_match('/[0-9]{2}/', $newPassword))) {
        header('HTTP/1.1 400 Bad Request');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - Password complexity has not been met. The password needs to have at least 2 numbers'));
    }
    // Server Side validation to see if the new password has enough complexity. (At least 12 characters)
    if (strlen($newPassword) < 11) {
        header('HTTP/1.1 400 Bad Request');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - Password length is not strong enough. It must be a minimum of 12 characters.'));
    }
    // Server Side validation to see if the new password has enough complexity. (Below 128 characters)
    if (strlen($newPassword) > 128) {
        header('HTTP/1.1 400 Bad Request');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - Password length is too strong. It must be within 128 characters.'));
    }
    // Server Side validation to see if the new password is correct to the confirm new password one.
    if ($newPassword != $confirmPassword) {
        header('HTTP/1.1 400 Bad Request');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - Confirm New Password does not match New Password'));
    }
    //Set userID to the session's UserID
    $userID = $_SESSION['userID'];

    include 'DatabaseLoginDetails.php';

    //Connect to the database
    $conn = mysqli_connect($host, $user, $pass, $database);
    //Prepare the statement - query to select the user's hashed password from the database
    $stmt = $conn->prepare("SELECT userPassword From user where userID = ?");
    //Bind the parameter of UserID to the prepared statement
    $stmt->bind_param("s", $userID);
    //Execute the query
    if (!$stmt->execute()) {
        //Couldn't execute query so stop there
        header('HTTP/1.1 500 Internal Server Error');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - Could not update your password.'));
    }


    //Get the result from that query and put it in a variable
    $res = $stmt->get_result();
    $userFromDB = mysqli_fetch_all($res, MYSQLI_ASSOC);

    //Now check to see if the old password is equal to the one in the database

    if (password_verify($oldPassword, $userFromDB[0]['userPassword'])) {
        // If it is, then hash the new password
        $hashNewPassword = password_hash($newPassword, PASSWORD_BCRYPT, array('cost' => 11));
        // and then make a query to update the user's password that is stored in the database.
        $stmt2 = $conn->prepare("UPDATE user SET userPassword = ? where userID = ?");
        // bind the variables needed for the query (userID and the new password (hashed) to the prepared statement
        $stmt2->bind_param("si", $hashNewPassword, $userID);

        //Execute the query
        if ($stmt2->execute()) {
            //Field updated successfully
            header('HTTP/1.1 200 OK');
            header('Content-Type: application/json; charset=UTF-8');
            die(json_encode('Updated password successfully'));
        } else {
            //Couldn't execute query
            header('HTTP/1.1 500 Internal Server Error');
            header('Content-Type: application/json; charset=UTF-8');
            die(json_encode('ERROR - Could not execute the password change action to the Database'));
        }
    } else {
        //If the hashed password coming back from the query is not the same as the hashed old password that was typed in via the user, then tell the user that the password was not correct
        header('HTTP/1.1 400 Bad Request');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - Password not correct'));
    }
    //If the changing process is address instead
} else if ($_POST['process'] == "Address") {
    if (!isset($_POST['title']) || !isset($_POST['firstName']) || !isset($_POST['lastName']) || !isset($_POST['addressLine1']) || !isset($_POST['townCity']) || !isset($_POST['county'])|| !isset($_POST['postcode'])) {
        header('HTTP/1.1 400 Bad Request');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - You haven\'t filled out all required fields that are needed for your main address'));
    }
    //Get all the fields that were posted through and trim most of them
     $title = trim($_POST['title']);
    $firstName = trim($_POST['firstName']);
    $lastName = trim($_POST['lastName']);
    $addressLine1 = trim($_POST['addressLine1']);
    $addressLine2 = trim($_POST['addressLine2']);
    $townCity =    trim($_POST['townCity']);
    $county =   trim($_POST['county']);
    //Remove any space (for instance 'L1 34Q') from Postcode
    $postCode = str_replace(' ', '', $_POST['postcode']);

    //Server side validation to check if any of the fields (except address line 2) are empty
    if (empty($title)) {
        header('HTTP/1.1 400 Bad Request');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - Title is empty. Please fill it out.'));
    }

    if (empty($firstName)) {
        header('HTTP/1.1 400 Bad Request');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - First Name is empty. Please fill it out.'));
    }
    if (empty($lastName)) {
        header('HTTP/1.1 400 Bad Request');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - Last Name is empty. Please fill it out.'));
    }
    if (empty($addressLine1)) {
        header('HTTP/1.1 400 Bad Request');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - Address Line 1 is empty. Please fill it out.'));
    }
    if (empty($townCity)) {
        header('HTTP/1.1 400 Bad Request');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - Town City is empty. Please fill it out.'));
    }
    if (empty($county)) {
        header('HTTP/1.1 400 Bad Request');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - County is empty. Please fill it out.'));
    }
    if (empty($postCode)) {
        header('HTTP/1.1 400 Bad Request');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - Post Code is empty. Please fill it out.'));
    }
    //Server side validation to check if the title is either Mr, Master, Miss, Mrs, Ms or Dr.
    if (!$title === "Mr" || !$title == "Master" ||
        !$title === "Miss" || !$title === "Mrs" || !$title === "Ms" ||
        !$title === "Dr") {
        header('HTTP/1.1 400 Bad Request');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - Title from list is not selected - Please select from the list'));
    }
    //Server side validation to check if the first name is more than a character
    if (strlen($firstName) < 2) {
        header('HTTP/1.1 400 Bad Request');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - First Name length is too weak. It must be a minimum of 2 characters.'));
    }
    //Server side validation to check if the first name is less than 256 characters
    if (strlen($firstName) > 255) {
        header('HTTP/1.1 400 Bad Request');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - First Name length is too strong. It must be a maximum of 255 characters.'));
    }
    //Server side validation to check if the first name doesn't contain any numbers in it
    if (!(preg_match('/^\D+$/', $firstName))) {
        header('HTTP/1.1 400 Bad Request');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - Incorrect Format for First Name. First Name should not contain numbers'));
    }

    //Server side validation to check if the last name is more than a character
    if (strlen($lastName) < 2) {
        header('HTTP/1.1 400 Bad Request');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - Last Name length is too weak. It must be a minimum of 2 characters.'));
    }
    //Server side validation to check if the last name is less than 256 characters
    if (strlen($lastName) > 255) {
        header('HTTP/1.1 400 Bad Request');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - Last Name length is too strong. It must be a maximum of 255 characters.'));
    }

    //Server side validation to check if the last name doesn't contain any numbers in it
    if (!(preg_match('/^\D+$/', $lastName))) {
        header('HTTP/1.1 400 Bad Request');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - Incorrect Format for Last Name. Last Name should not contain numbers'));
    }

    // Server side validation to check if the address line 1 is more than a character
    if (strlen($addressLine1) < 2) {
        header('HTTP/1.1 400 Bad Request');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - Address Line 1 length is too weak. It must be a minimum of 2 characters.'));
    }
    // Server side validation to check if the address line 1 is less than 256 characters
    if (strlen($addressLine1) > 255) {
        header('HTTP/1.1 400 Bad Request');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - Address Line 1 length is too strong. It must be a maximum of 255 characters.'));
    }
    // Server side validation to check when the address Line 2 is not empty, if it is more than a character
    if (!empty($addressLine2) && strlen($addressLine2) < 2) {
        header('cardContainer400 Bad Request');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - Address Line 2 length is too weak. It must be a minimum of 2 characters if not blank.'));
    }

    // Server side validation to check when the address Line 2 is not empty, if it is less than 256 characters
    if (!empty($addressLine2) && strlen($addressLine2) > 255) {
        header('HTTP/1.1 400 Bad Request');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - Address Line 2 length is too strong. It must be a maximum of 255 characters.'));
    }

    // Server side validation to check if the city/town is more than a character
    if (strlen($townCity) < 2) {
        header('HTTP/1.1 400 Bad Request');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - City/Town length is too weak. It must be a minimum of 2 characters.'));
    }
    // Server side validation to check if the city/town is less than 58 characters
    if (strlen($townCity) > 58) {
        header('HTTP/1.1 400 Bad Request');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - City/Town length is too strong. It must be a maximum of 58 characters.'));
    }

    // Server side validation to check if the county is more than a character
    if (strlen($county) < 2) {
        header('HTTP/1.1 400 Bad Request');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - County length is too weak. It must be a minimum of 2 characters.'));
    }
    // Server side validation to check if the county is less than 256 characters
    if (strlen($county) > 255) {
        header('HTTP/1.1 400 Bad Request');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - County length is too strong. It must be a maximum of 255 characters.'));
    }

    // Server side validation to check if the postCode is more than 4 characters
    if (strlen($postCode) < 5) {
        header('HTTP/1.1 400 Bad Request');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - Postcode length is too weak. It must be a minimum of 2 characters.'));
    }
    // Server side validation to check if the postCode is less than 9 characters e.g SW1A 2AA (8 characters)
    if (strlen($postCode) > 8) {
        header('HTTP/1.1 400 Bad Request');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - Postcode length is too strong. It must be a maximum of 8 characters.'));
    }





    //All validation has passed. Therefore, get the UserID from the current session.
    $userID = $_SESSION['userID'];
    //Get the Database Details
    include 'DatabaseLoginDetails.php';
    //Make a connect to the database
    $conn = mysqli_connect($host, $user, $pass, $database);
    //Prepared Statement - query to update the main address with the post data that was sent through
    $stmt = $conn->prepare("update address a INNER JOIN user u ON a.addressID = u.mainAddressID 
     set a.title = ?, a.firstName = ?, a.lastName = ?, a.addressLine1 = ?, a.addressLine2 = ?, a.townCity = ?, a.county = ?, a.postcode = ? 
     where u.userID = ?");
    //Binding the posted data and the UserID with the prepared statement
    $stmt->bind_param("ssssssssi", $title, $firstName, $lastName, $addressLine1, $addressLine2, $townCity, $county, $postCode, $userID);

    //Execute the query
    if ($stmt->execute()) {
        //Success so update the user's first name in the session variable we store (even if they didn't update it)
        $_SESSION['userFirstName'] = $firstName;
        //Send back and OK HTTP response saying the address has been updated
        header('HTTP/1.1 200 OK');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('Updated address successfully'));
    } else {
        //Error trying to execute the address change and send back a 500 Internal Server Error HTTP response.
        header('HTTP/1.1 500 Internal Server Error');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - Could not execute the address change action to the Database'));
    }
} else {
    //If the process is anything else (can't be), then throw an error message
    header('HTTP/1.1 400 Bad Request');
    header('Content-Type: application/json; charset=UTF-8');
    die(json_encode('ERROR - You are in an incorrect state. Please refresh the page or go to the home page'));
}
