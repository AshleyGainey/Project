<?php
if (!isset($_SESSION)) {
    @ob_start();
    session_start();
}
//If registering
if (isset($_POST['Register'])) {
    include 'DatabaseLoginDetails.php';
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
    // $postcode = trim($_POST['password']);
    $password = $_POST['password'];
    $confirmPassword = trim($_POST['confirmPassword']);

    //Server Side checking to see if any fields  and Confirm Password is correct
    if (empty($email)) {
        header('HTTP/1.1 400 Bad Request Server');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - Email Address is empty. Please fill it out.'));
    }
    if (empty($password)) {
        header('HTTP/1.1 400 Bad Request Server');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - Password is empty. Please fill it out.'));
    }
    if (empty($confirmPassword)) {
        header('HTTP/1.1 400 Bad Request Server');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - Confirm Password is empty. Please fill it out.'));
    }
    if (empty($title)) {
        header('HTTP/1.1 400 Bad Request Server');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - Title is empty. Please fill it out.'));
    }
    if (empty($firstName)) {
        header('HTTP/1.1 400 Bad Request Server');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - First Name is empty. Please fill it out.'));
    }
    if (empty($lastName)) {
        header('HTTP/1.1 400 Bad Request Server');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - Last Name is empty. Please fill it out.'));
    }
    if (empty($addressLine1)) {
        header('HTTP/1.1 400 Bad Request Server');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - Address Line 1 is empty. Please fill it out.'));
    }
    if (empty($townCity)) {
        header('HTTP/1.1 400 Bad Request Server');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - Town/City is empty. Please fill it out.'));
    }
    if (empty($county)) {
        header('HTTP/1.1 400 Bad Request Server');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - County is empty. Please fill it out.'));
    }
    if (empty($postcode)) {
        header('HTTP/1.1 400 Bad Request Server');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - Postcode is empty. Please fill it out.'));
    }
    // Server Side checking to see if email is in the correct format (From: https://ihateregex.io/expr/email/#)
    if (!(preg_match('/[^@ \t\r\n]+@[^@ \t\r\n]+\.[^@ \t\r\n]+/', $email))) {
        header('HTTP/1.1 400 Bad Request Server');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - Incorrect Format for email. Please fill it out correctly. \'FirstPartOfEmail@EmailDomain.com\'. '));
    } 

    // Server Side checking to see if Password has enough complexity (first check, at least 12 characters)
    if (strlen($password) < 11) {
        header('HTTP/1.1 400 Bad Request Server');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - Password length is not strong enough. It must be a minimum of 12 characters.'));
    }

    if (strlen($password) > 127) {
        header('HTTP/1.1 400 Bad Request Server');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - Password length is too strong. It must be within 128 characters.'));
    }
    if (!$title === "Mr" || !$title === "Master" ||
        !$title === "Miss" || !$title === "Mrs" || !$title === "Ms" ||
        !$title === "Dr") {
        header('HTTP/1.1 400 Bad Request Server');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - Title from list is not selected - Please select from the list'));
    }


    if (strlen($firstName) < 2) {
        header('HTTP/1.1 400 Bad Request Server');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - First Name length is too weak. It must be a minimum of 2 characters.'));
    }

    if (strlen($firstName) > 255) {
        header('HTTP/1.1 400 Bad Request Server');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - First Name length is too strong. It must be a maximum of 255 characters.'));
    }
    if (!(preg_match('/^\D+$/', $firstName))) {
        header('HTTP/1.1 400 Bad Request Server');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - Incorrect Format for First Name. First Name should not contain numbers'));
    }

    if (strlen($lastName) < 2) {
        header('HTTP/1.1 400 Bad Request Server');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - Last Name length is too weak. It must be a minimum of 2 characters.'));
    }

    if (strlen($lastName) > 255) {
        header('HTTP/1.1 400 Bad Request Server');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - Last Name length is too strong. It must be a maximum of 255 characters.'));
    }

    if (!(preg_match('/^\D+$/', $lastName))) {
        header('HTTP/1.1 400 Bad Request Server');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - Incorrect Format for Last Name. Last Name should not contain numbers'));
    }

    if (!(preg_match('/[a-zA-Z]{5}/', $password))) {
        header('HTTP/1.1 400 Bad Request Server');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - Password complexity has not been met. The password needs to have at least 5 letters (Uppercase or lowercase)'));
        // Server Side checking to see if Password has enough complexity (third check, at least 2 numbers)
    }
    if (!(preg_match('/[0-9]{2}/', $password))) {
        header('HTTP/1.1 400 Bad Request Server');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - Password complexity has not been met. The password needs to have at least 2 numbers'));
    }

    if (strlen($addressLine1) < 2) {
        header('HTTP/1.1 400 Bad Request Server');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - Address Line 1 length is too weak. It must be a minimum of 2 characters.'));
    }

    if (strlen($addressLine1) > 255) {
        header('HTTP/1.1 400 Bad Request Server');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - Address Line 1 length is too strong. It must be a maximum of 255 characters.'));
    }

    if (!empty($addressLine2) && strlen($addressLine2) < 2) {
        header('HTTP/1.1 400 Bad Request Server');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - Address Line 2 length is too weak. It must be a minimum of 2 characters if not blank.'));
    }

    if (strlen($addressLine2) > 255) {
        header('HTTP/1.1 400 Bad Request Server');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - Address Line 2 length is too strong. It must be a maximum of 255 characters.'));
    }

    if (strlen($townCity) < 2) {
        header('HTTP/1.1 400 Bad Request Server');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - Town/City length is too weak. It must be a minimum of 2 characters.'));
    }

    if (strlen($townCity) > 255) {
        header('HTTP/1.1 400 Bad Request Server');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - Town/City length is too strong. It must be a maximum of 255 characters.'));
    }

    if (strlen($county) < 2) {
        header('HTTP/1.1 400 Bad Request Server');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - County length is too weak. It must be a minimum of 2 characters.'));
    }

    if (strlen($county) > 255) {
        header('HTTP/1.1 400 Bad Request Server');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - County length is too strong. It must be a maximum of 255 characters.'));
    }

    if (strlen($postcode) < 2) {
        header('HTTP/1.1 400 Bad Request Server');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - Postcode length is too weak. It must be a minimum of 2 characters.'));
    }

    if (strlen($postcode) > 8) {
        header('HTTP/1.1 400 Bad Request Server');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - Postcode length is too strong. It must be a maximum of 8 characters.'));
    }

    //Server Side checking to see if Password and Confirm Password is correct
    if ($password !== $confirmPassword) {
        header('HTTP/1.1 400 Bad Request Server');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - Password & Confirm Password is not correct'));
    } 

    $hash = password_hash($password, PASSWORD_BCRYPT, array('cost' => 11));
    
    $conn = mysqli_connect($host, $user, $pass, $database);


    $stmt = $conn->prepare("SELECT UserID From user where userEmail = ?");
    $stmt->bind_param("s", $email);

    $stmt->execute();

    $res = $stmt->get_result();
    if ($res->num_rows > 0) {
        $stmt->close();
        // echo "Is already in DB";
        header('HTTP/1.1 400 Bad Request Server');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - There is already an account with that email address. Please Log in or Register with a different email address'));
    }

    // print_r('Still continuing');

    $stmt = $conn->prepare(
        "INSERT INTO address (title, firstName, lastName, addressLine1, addressLine2, townCity, county, postcode)
VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
    );

    $stmt->bind_param("ssssssss", $title, $firstName, $lastName, $addressLine1, $addressLine2, $townCity, $county, $postcode);

    $stmt->execute();
    $addressID = mysqli_insert_id($conn);


    $stmt2 = $conn->prepare("INSERT INTO user (userEmail, userPassword, mainAddressID, typeOfUser)
VALUES (?, ?, ?, ?)");

    $typeOfUser =    "Customer";

    $stmt2->bind_param("ssis", $email, $hash, $addressID, $typeOfUser);
    $stmt2->execute();

    $userID = mysqli_insert_id($conn);

    $_SESSION['userID'] = $userID;
    $_SESSION['userEmail'] = $email;
    $_SESSION['userFirstName'] = $firstName;
    $_SESSION['userLastName'] = $lastName;
} else if (isset($_POST['Login'])) {
    include 'DatabaseLoginDetails.php';
    $email = trim($_POST['emailAddress']);

    $password = $_POST['password'];

    if (empty($email)) {
        header('HTTP/1.1 400 Bad Request Server');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - Email Address is empty. Please fill it out.'));
    }
    if (empty($password)) {
        header('HTTP/1.1 400 Bad Request Server');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - Password is empty. Please fill it out.'));
    }

    // Server Side checking to see if email is in the correct format (From: https://ihateregex.io/expr/email/#)
    if (!(preg_match('/[^@ \t\r\n]+@[^@ \t\r\n]+\.[^@ \t\r\n]+/', $email))) {
        header('HTTP/1.1 400 Bad Request Server');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - Incorrect Format for email. Please fill it out correctly. \'FirstPartOfEmail@EmailDomain.com\'. '));
    }


    if (strlen($email) < 4) {
        header('HTTP/1.1 400 Bad Request Server');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - Email length is too weak. It must be a minimum of 4 characters.'));
    }

    if (strlen($email) > 255) {
        header('HTTP/1.1 400 Bad Request Server');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - Email length is too strong. It must be a maximum of 255 characters.'));
    }

    if (strlen($password) < 12) {
        header('HTTP/1.1 400 Bad Request Server');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - Password length is too weak. It must be a minimum of 12 characters.'));
    }

    if (strlen($password) > 128) {
        header('HTTP/1.1 400 Bad Request Server');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - Password length is too strong. It must be a maximum of 128 characters.'));
    }


    $conn = mysqli_connect($host, $user, $pass, $database);

    if (!$conn) {
        header('HTTP/1.1 500 Internal Server Error');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - Please contact us with this issue.'));
        echo 'Connection error: ' . mysqli_connect_error();
    }


    $stmt = $conn->prepare('SELECT u.userID, u.userEmail, u.userPassword, a.firstName, a.lastName From user u INNER JOIN address a ON u.mainAddressID = a.addressID where userEmail = ? LIMIT 1');
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();

    $user = mysqli_fetch_all($result, MYSQLI_ASSOC);
    $rows = mysqli_num_rows($result);


    if ($rows == 0) {
        header('HTTP/1.1 400 Bad Request Server');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - Account with that email address does not exist'));
        return false;
    }


    if (password_verify($password, $user[0]['userPassword'])) {

        header('Content-Type: application/json');
        print json_encode($result);

        // header('Location: Error404.php');
        $_SESSION['userID'] = $user[0]["userID"];
        $_SESSION['userEmail'] = $user[0]["userEmail"];
        $_SESSION['userFirstName'] = $user[0]["firstName"];
        // header('Location: Login.php');
        exit();

        header('HTTP/1.1 200 OK');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('Success'));
        // echo "Password verified +" .  $_SESSION['userFirstName'];
        
    } else {
        header('HTTP/1.1 400 Bad Request Server');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - Password not correct'));

        // echo "Password Incorrect";
        return false;
    }

    //Free  memory and close the connection
    mysqli_free_result($result);
    mysqli_close($conn);
}
?>