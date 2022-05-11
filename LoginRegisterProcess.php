<?php
// If the session hasn't started. Start it (can then use session variables)
if (!isset($_SESSION)) {
    @ob_start();
    session_start();
}

//If the process is registering
if (isset($_POST['Register'])) {

    // Check if any of the POST data that is supposed to be filled in is empty.
    if (
        !isset($_POST['emailAddress']) || !isset($_POST['title']) || !isset($_POST['firstName']) || !isset($_POST['lastName'])
        || !isset($_POST['addressLine1']) || !isset($_POST['townCity']) || !isset($_POST['county']) || !isset($_POST['postcode'])
        || !isset($_POST['password']) || !isset($_POST['confirmPassword'])
    ) {
        header('HTTP/1.1 400 Bad Request');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - At least one field that is required in the registering form has not been entered. Please fill it out.'));
    }

    // Get details from the post request and trim whitespaces from most of the details (excluding the password/confirm password)
    $email =  trim($_POST['emailAddress']);
    $title = trim($_POST['title']);
    $firstName = trim($_POST['firstName']);
    $lastName = trim($_POST['lastName']);
    $addressLine1 = trim($_POST['addressLine1']);
    $addressLine2 = trim($_POST['addressLine2']);
    $townCity =    trim($_POST['townCity']);
    $county =   trim($_POST['county']);
    //Remove any space (for instance 'L1 34Q') from Postcode
    $postcode = str_replace(' ', '', $_POST['postcode']);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];

    //Server Side checking to see if any fields are empty and if so, don't execute further
    if (empty($email)) {
        header('HTTP/1.1 400 Bad Request');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - Email Address is empty. Please fill it out.'));
    }
    if (empty($password)) {
        header('HTTP/1.1 400 Bad Request');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - Password is empty. Please fill it out.'));
    }
    if (empty($confirmPassword)) {
        header('HTTP/1.1 400 Bad Request');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - Confirm Password is empty. Please fill it out.'));
    }
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
        die(json_encode('ERROR - Town/City is empty. Please fill it out.'));
    }
    if (empty($county)) {
        header('HTTP/1.1 400 Bad Request');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - County is empty. Please fill it out.'));
    }
    if (empty($postcode)) {
        header('HTTP/1.1 400 Bad Request');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - Postcode is empty. Please fill it out.'));
    }
    // Server Side checking to see if email is in the correct format (From: https://ihateregex.io/expr/email/#)
    if (!(preg_match('/[^@ \t\r\n]+@[^@ \t\r\n]+\.[^@ \t\r\n]+/', $email))) {
        header('HTTP/1.1 400 Bad Request');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - Incorrect Format for email. Please fill it out correctly. \'FirstPartOfEmail@EmailDomain.com\'. '));
    }
    // Server Side checking to see if email is at least 5 characters long
    if (strlen($email) < 4) {
        header('HTTP/1.1 400 Bad Request');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - Email length is too weak. It must be a minimum of 4 characters.'));
    }
    // Server Side checking to see if email is at no more than 255 characters long
    if (strlen($email) > 255) {
        header('HTTP/1.1 400 Bad Request');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - Email length is too strong. It must be a maximum of 255 characters.'));
    }

    // Server Side checking to see if Password has enough complexity (first check, at least 12 characters)
    if (strlen($password) < 11) {
        header('HTTP/1.1 400 Bad Request');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - Password length is not strong enough. It must be a minimum of 12 characters.'));
    }
    // Server Side checking to see if Password has too complex (second check, no more than 128 characters)
    if (strlen($password) > 128) {
        header('HTTP/1.1 400 Bad Request');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - Password length is too strong. It must be within 128 characters.'));
    }
    // Server Side checking to see if Password has too complex (third check to see if the password has at least 5 characters)
    if (!(preg_match('/[a-zA-Z]{5}/', $password))) {
        header('HTTP/1.1 400 Bad Request');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - Password complexity has not been met. The password needs to have at least 5 letters (Uppercase or lowercase)'));
    }
    // Server Side checking to see if Password has enough complexity (fourth check, at least 2 numbers)
    if (!(preg_match('/[0-9]{2}/', $password))) {
        header('HTTP/1.1 400 Bad Request');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - Password complexity has not been met. The password needs to have at least 2 numbers'));
    }

    //Server side validation to check if the title is either Mr, Master, Miss, Mrs, Ms or Dr.
    if (
        !$title === "Mr" || !$title === "Master" ||
        !$title === "Miss" || !$title === "Mrs" || !$title === "Ms" ||
        !$title === "Dr"
    ) {
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
        header('HTTP/1.1 400 Bad Request');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - Address Line 2 length is too weak. It must be a minimum of 2 characters if not blank.'));
    }

    // Server side validation to check when the address Line 2 is not empty, if it is less than 256 characters
    if (!empty($addressLine2) && strlen($addressLine2) > 255) {
        header('HTTP/1.1 400 Bad Request');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - Address Line 2 length is too strong. It must be a maximum of 255 characters.'));
    }

    // Server side validation to check if the Town/City is more than a character
    if (strlen($townCity) < 2) {
        header('HTTP/1.1 400 Bad Request');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - Town/City length is too weak. It must be a minimum of 2 characters.'));
    }

    // Server side validation to check if the Town/City is less than 58 characters
    if (strlen($townCity) > 58) {
        header('HTTP/1.1 400 Bad Request');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - Town/City length is too strong. It must be a maximum of 58 characters.'));
    }
    // Server side validation to check if the postCode is more than 4 characters
    if (strlen($postcode) < 5) {
        header('HTTP/1.1 400 Bad Request');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - Postcode length is too weak. It must be a minimum of 2 characters.'));
    }
    // Server side validation to check if the postCode is less than 9 characters e.g SW1A 2AA (8 characters)
    if (strlen($postcode) > 8) {
        header('HTTP/1.1 400 Bad Request');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - Postcode length is too strong. It must be a maximum of 8 characters.'));
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
    if (strlen($postcode) < 5) {
        header('HTTP/1.1 400 Bad Request');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - Postcode length is too weak. It must be a minimum of 2 characters.'));
    }
    // Server side validation to check if the postCode is less than 9 characters e.g SW1A 2AA (8 characters)
    if (strlen($postcode) > 8) {
        header('HTTP/1.1 400 Bad Request');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - Postcode length is too strong. It must be a maximum of 8 characters.'));
    }

    //Server Side checking to see if Password and Confirm Password is correct
    if ($password !== $confirmPassword) {
        header('HTTP/1.1 400 Bad Request');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - Password & Confirm Password is not correct'));
    }
    //Get the Database Details
    include 'DatabaseLoginDetails.php';
    //Make a connect to the database
    $conn = mysqli_connect($host, $user, $pass, $database);

    // Error with the connection of the database. So don't execute further
    if (!$conn) {
        header('HTTP/1.1 500 Internal Server Error');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - Connection to the database has not been established'));
    } else  if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    //Prepared Statement - query to see if the user has already signed up using the same email address
    $stmt = $conn->prepare("SELECT UserID From user where userEmail = ?");
    //Binding the email with the prepared statement
    $stmt->bind_param("s", $email);

    //Execute the query
    if (!$stmt->execute()) {
        //Error trying to execute seeing if the user is already in the DB and send back a 500 Internal Server Error HTTP response.
        $stmt->close();
        header('HTTP/1.1 500 Internal Server Error');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - Could not register you for an account - Failed at checking if user exists in DB'));
    }

    //Get results from the database
    $res = $stmt->get_result();
    // And if there is a row... then it is already in the data and produce an error (don't do anything more)
    if ($res->num_rows > 0) {
        // Close the connection and return an error
        $stmt->close();
        header('HTTP/1.1 400 Bad Request');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - There is already an account with that email address. Please Log in or Register with a different email address'));
    }
    // If not in the database, then register them.

    //Prepared Statement - query to register the user, putting the second part of their information into the database
    $stmt = $conn->prepare(
        "INSERT INTO address (title, firstName, lastName, addressLine1, addressLine2, townCity, county, postcode)
VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
    );
    //Binding the title, first name, last name, address line 1, address line 2 (can be blank/null), town or city, county and postcode with the prepared statement
    $stmt->bind_param("ssssssss", $title, $firstName, $lastName, $addressLine1, $addressLine2, $townCity, $county, $postcode);

    //Execute the query
    if (!$stmt->execute()) {
        //Error trying to execute Insert query. Send back a 500 Internal Server Error HTTP response.
        $stmt->close();
        header('HTTP/1.1 500 Internal Server Error');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - Could not register you for an account - Failed at inserting address into the DB'));
    }
    // If query executed successfully, then get the ID from what was just inserted, so we can use that into the next query of adding the user.
    $addressID = mysqli_insert_id($conn);


    // Get the password that the user made, and hashing it using the Bcrypt method
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT, array('cost' => 11));

    //Prepared Statement - query to register the user, putting the first part of their information into the database
    $stmt2 = $conn->prepare("INSERT INTO user (userEmail, userPassword, mainAddressID, typeOfUser)
VALUES (?, ?, ?, ?)");

    // Type of User is Customer - Haven't implemented login for Admin. Will be implemented by September 2022 - so for now, just say the type of user is Customer
    $typeOfUser = "Customer";

    //Binding the email, the hashed Password, the ID of the main address that we just created and the type of the user with the prepared statement
    $stmt2->bind_param("ssis", $email, $hashedPassword, $addressID, $typeOfUser);
    //Execute the query
    if (!$stmt2->execute()) {
        //Error trying to execute Insert query. Send back a 500 Internal Server Error HTTP response.
        $stmt2->close();
        header('HTTP/1.1 500 Internal Server Error');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - Could not register you for an account - Failed at inserting address into the DB'));
    }
    // Executed correctly - Get the ID of the user of who we just inserted and put it in a Session, 
    // in order to know who is logged in and use that variable further down the line.
    $_SESSION['userID'] = mysqli_insert_id($conn);;
    // Also add the email and the first name into variables that is stored in the session - easier than going into the database with how frequently we use these variables
    $_SESSION['userEmail'] = $email;
    $_SESSION['userFirstName'] = $firstName;
    //If the process is Login
} else if (isset($_POST['Login'])) {

    // if (!isset($_POST['emailAddress']) && !isset($_POST['password'])) {
    //     header('HTTP/1.1 400 Bad Request');
    //     header('Content-Type: application/json; charset=UTF-8');
    //     die(json_encode('ERROR - Email Address or Password is empty. Please fill it out.'));
    // }

    // Get details from the post request and trim whitespaces from the email address
    $email = trim($_POST['emailAddress']);
    $password = $_POST['password'];

    //Server Side checking to see if any fields are empty and if so, don't execute further
    if (empty($email)) {
        header('HTTP/1.1 400 Bad Request');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - Email Address is empty. Please fill it out.'));
    }
    if (empty($password)) {
        header('HTTP/1.1 400 Bad Request');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - Password is empty. Please fill it out.'));
    }

    // Server Side checking to see if email is in the correct format (From: https://ihateregex.io/expr/email/#)
    if (!(preg_match('/[^@ \t\r\n]+@[^@ \t\r\n]+\.[^@ \t\r\n]+/', $email))) {
        header('HTTP/1.1 400 Bad Request');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - Incorrect Format for email. Please fill it out correctly. \'FirstPartOfEmail@EmailDomain.com\'. '));
    }

    // Server Side checking to see if email is at least 3 characters long
    if (strlen($email) < 3) {
        header('HTTP/1.1 400 Bad Request');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - Email length is too weak. It must be a minimum of 4 characters.'));
    }
    // Server Side checking to see if email is at no more than 254 characters long
    if (strlen($email) > 254) {
        header('HTTP/1.1 400 Bad Request');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - Email length is too strong. It must be a maximum of 254 characters.'));
    }
    // Server Side checking to see if Password has enough complexity (first check, at least 12 characters)
    if (strlen($password) < 11) {
        header('HTTP/1.1 400 Bad Request');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - Password length is too weak. It must be a minimum of 12 characters.'));
    }
    // Server Side checking to see if Password has too complex (second check, no more than 128 characters)
    if (strlen($password) > 128) {
        header('HTTP/1.1 400 Bad Request');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - Password length is too strong. It must be a maximum of 128 characters.'));
    }
    //Get the Database Details
    include 'DatabaseLoginDetails.php';
    //Make a connect to the database
    $conn = mysqli_connect($host, $user, $pass, $database);

    // Error with the connection of the database. So don't execute further
    if (!$conn) {
        header('HTTP/1.1 500 Internal Server Error');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - Connection to the database has not been established'));
    } else  if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    //Prepared Statement - query to see if the user is in the DB by checking the email address
    $stmt = $conn->prepare('SELECT u.userID, u.userPassword, a.firstName From user u INNER JOIN address a ON u.mainAddressID = a.addressID where userEmail = ? LIMIT 1');
    //Binding the email with the prepared statement
    $stmt->bind_param('s', $email);

    //Execute the query
    if (!$stmt->execute()) {
        //Error trying to execute seeing if the user is in the DB from the given email and send back a 500 Internal Server Error HTTP response.
        $stmt->close();
        header('HTTP/1.1 500 Internal Server Error');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - Could not register you for an account - Failed at checking if user exists in DB'));
    }

    // Query has executed correctly. So put how many results come back (will only be 0 or 1)
    $result = $stmt->get_result();
    $rows = mysqli_num_rows($result);

    // If there is no results that means there is no account with the email address that provided
    if ($rows == 0) {
        header('HTTP/1.1 400 Bad Request');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - Account with that email address does not exist'));
        return false;
    }

    //User is in the database so put the result in a variable
    $user = mysqli_fetch_all($result, MYSQLI_ASSOC);
    // Get the Password stored in the database for the user and see if the password entered 
    // by the user is the same by using the password_verify method
    if (password_verify($password, $user[0]['userPassword'])) {
        // Password successful
        // Get the ID, Email and First Name (from the DB result) of the user that has just logged in and put it in a Session, 
        // in order to know who is logged in and use that variable further down the line.
        $_SESSION['userID'] = $user[0]["userID"];
        $_SESSION['userEmail'] = $email;
        $_SESSION['userFirstName'] = $user[0]["firstName"];

        //Report back to say that the login has been successful
        header('HTTP/1.1 200 OK');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('Login Successful'));


        //Free memory and close the connection
        mysqli_free_result($result);
        mysqli_close($conn);
        return true;
    } else {
        // If the password is not correct then tell the user that.
        header('HTTP/1.1 400 Bad Request');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - Password not correct'));
        return false;
    }
}
