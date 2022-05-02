<?php
if (!isset($_SESSION)) {
    @ob_start();
    session_start();
}
// echo $_POST['process'];
if ($_POST['process'] == "Email") {
    include 'DBlogin.php';
    $new_email = $_POST['emailAddress'];
    $password = $_POST['password'];
    //Server Side checking to see if any fields  and Confirm Password is correct
    if (empty($new_email)) {
        header('HTTP/1.1 400 Bad Request Server');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - New Email Address is empty. Please fill it out.'));
    }
    if (empty($password)) {
        header('HTTP/1.1 400 Bad Request Server');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - Password is empty. Please fill it out.'));
    }

    if (strlen($new_email) < 4) {
        header('HTTP/1.1 400 Bad Request Server');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - Email length is too weak. It must be a minimum of 4 characters.'));
    }

    if (strlen($new_email) > 255) {
        header('HTTP/1.1 400 Bad Request Server');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - Email length is too strong. It must be a maximum of 255 characters.'));
    }


    // Server Side checking to see if email is in the correct format (From: https://ihateregex.io/expr/email/#)
    if (!(preg_match('/[^@ \t\r\n]+@[^@ \t\r\n]+\.[^@ \t\r\n]+/', $new_email))) {
        header('HTTP/1.1 400 Bad Request Server');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - Incorrect Format for email. Please fill it out correctly. \'FirstPartOfEmail@EmailDomain.com\'. '));
    }

    //Password complexity has not been met, therefore, don't go to the database to check if it is correct
    if (!(preg_match('/[a-zA-Z]{5}/', $password))) {
        header('HTTP/1.1 400 Bad Request Server');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - Password not correct'));
    }
    // //Password complexity has not been met, therefore, don't go to the database to check if it is correct
    if (!(preg_match('/[0-9]{2}/', $password))) {
        header('HTTP/1.1 400 Bad Request Server');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - Password not correct'));
    }
    // Server Side checking to see if Password has enough complexity (first check, at least 12 characters)
    if (strlen($password) < 11) {
        header('HTTP/1.1 400 Bad Request Server');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - Password not correct'));
    }

    if (strlen($password) > 127) {
        header('HTTP/1.1 400 Bad Request Server');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - Password not correct'));
    }

    $hash = password_hash($password, PASSWORD_BCRYPT, array('cost' => 11));
    $userID = $_SESSION['userID'];

    $conn = mysqli_connect($host, $user, $pass, $database);
    $stmt = $conn->prepare("SELECT userPassword From user where userID = ?");
    $stmt->bind_param("s", $userID);
    $stmt->execute();
    $res = $stmt->get_result();

    $userFromDB = mysqli_fetch_all($res, MYSQLI_ASSOC);
    // $rows = mysqli_num_rows($result);

    // echo $stmt;
    if (password_verify($password, $userFromDB[0]['userPassword'])) {




        $conn = new mysqli($host, $user, $pass, $database);
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
            $stmt = $conn->prepare("UPDATE user SET userEmail = ? where userID = ?");
            $stmt->bind_param("i", $userEmail, $userID);
           if ($stmt->execute()) {
                echo "Record updated successfully";
                $_SESSION['userEmail'] = $new_email;
           } else {
                echo "Error updating record: " . $conn->error;
                header('HTTP/1.1 Internal Server Error');
                header('Content-Type: application/json; charset=UTF-8');
                die(json_encode('ERROR - Cannot execute Update Query'));
           }
    } else {
        header('HTTP/1.1 400 Bad Request Server');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - Password not correct'));
    }
} else if (
    $_POST['process'] == "Password"
) {
    include 'DBlogin.php';
    $oldPassword = $_POST['oldPassword'];
    $newPassword = $_POST['newPassword'];
    $confirmPassword = $_POST['confirmPassword'];

    //Server Side checking to see if any fields  and Confirm Password is correct
    if (empty($oldPassword)) {
        header('HTTP/1.1 400 Bad Request Server');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - Old Password is empty. Please fill it out.'));
    }
    if (empty($newPassword)) {
        header('HTTP/1.1 400 Bad Request Server');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - New Password is empty. Please fill it out.'));
    }

    if (empty($confirmPassword)) {
        header('HTTP/1.1 400 Bad Request Server');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - Confirm New Password is empty. Please fill it out.'));
    }

    //Password complexity has not been met, therefore, don't go to the database to check if it is correct
    if (!(preg_match('/[a-zA-Z]{5}/', $newPassword))) {
        header('HTTP/1.1 400 Bad Request Server');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - Password complexity has not been met. The password needs to have at least 5 letters (Uppercase or lowercase)'));
    }
    // //Password complexity has not been met, therefore, don't go to the database to check if it is correct
    if (!(preg_match('/[0-9]{2}/', $newPassword))) {
        header('HTTP/1.1 400 Bad Request Server');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - Password complexity has not been met. The password needs to have at least 2 numbers'));
    }
    // Server Side checking to see if Password has enough complexity (first check, at least 12 characters)
    if (strlen($newPassword) < 11) {
        header('HTTP/1.1 400 Bad Request Server');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - Password length is not strong enough. It must be a minimum of 12 characters.'));
    }

    if (strlen($newPassword) > 127) {
        header('HTTP/1.1 400 Bad Request Server');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - Password length is too strong. It must be within 128 characters.'));
    }

    if ($newPassword != $confirmPassword) {
        header('HTTP/1.1 400 Bad Request Server');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - Confirm New Password does not match New Password'));
    }

    $userID = $_SESSION['userID'];

    $conn = mysqli_connect($host, $user, $pass, $database);
    $stmt = $conn->prepare("SELECT userPassword From user where userID = ?");
    $stmt->bind_param("s", $userID);
    $stmt->execute();
    $res = $stmt->get_result();

    $userFromDB = mysqli_fetch_all($res, MYSQLI_ASSOC);

    if (password_verify($oldPassword, $userFromDB[0]['userPassword'])) {
        $hashNewPassword = password_hash($newPassword, PASSWORD_BCRYPT, array('cost' => 11));

        $stmt2 = $conn->prepare("UPDATE user SET userPassword = ? where userID = ?");
        $stmt2->bind_param("si", $hashNewPassword, $userID);

        
        if ($stmt2->execute()) {
            header('HTTP/1.1 200 OK');
            header('Content-Type: application/json; charset=UTF-8');
            die(json_encode('Executed successfully'));
        } else {
            header('HTTP/1.1 500 Internal Server Error');
            header('Content-Type: application/json; charset=UTF-8');
            die(json_encode('ERROR - Could not execute the password change action to the Database'));
        }
    } else {
        header('HTTP/1.1 400 Bad Request Server');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - Password not correct'));
    }
} else if ($_POST['process'] == "Address") {
    include 'DBlogin.php';
    $title = $_POST['title'];
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $addressLine1 = $_POST['addressLine1'];
    $addressLine2 = $_POST['addressLine2'];
    $townCity = $_POST['townCity'];
    $county = $_POST['county'];
    $postCode = $_POST['postcode'];

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
        die(json_encode('ERROR - Town City is empty. Please fill it out.'));
    }
    if (empty($county)) {
        header('HTTP/1.1 400 Bad Request Server');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - County is empty. Please fill it out.'));
    }
    if (empty($postCode)) {
        header('HTTP/1.1 400 Bad Request Server');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - Post Code is empty. Please fill it out.'));
    }
    if ($title !== "Mr" || $title !== "Master" ||
    $title !== "Miss" || $title !== "Mrs" || $title !== "Ms" ||
    $title !== "Dr") {
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

    if (strlen($country) < 2) {
        header('HTTP/1.1 400 Bad Request Server');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - County length is too weak. It must be a minimum of 2 characters.'));
    }

    if (strlen($country) > 255) {
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






            $userID = $_SESSION['userID'];

            $conn = mysqli_connect($host, $user, $pass, $database);
            $stmt = $conn->prepare("update address a INNER JOIN user u ON a.addressID = u.mainAddressID 
     set a.title = ?, a.firstName = ?, a.lastName = ?, a.addressLine1 = ?, a.addressLine2 = ?, a.townCity = ?, a.county = ?, a.postcode = ? 
     where u.userID = ?");
            $stmt->bind_param("ssssssssi", $title, $firstName, $lastName, $addressLine1, $addressLine2, $townCity, $county, $postCode, $userID);


            if ($stmt->execute()) {
                $_SESSION['userFirstName'] = $firstName;
                header('HTTP/1.1 200 OK');
                header('Content-Type: application/json; charset=UTF-8');
                die(json_encode('Updated address successfully'));
            } else {
                header('HTTP/1.1 500 Internal Server Error');
                header('Content-Type: application/json; charset=UTF-8');
                die(json_encode('ERROR - Could not execute the password change action to the Database'));
    }
} else {
    header('HTTP/1.1 400 Bad Request Server');
    header('Content-Type: application/json; charset=UTF-8');
    die(json_encode('ERROR - You are in an incorrect state. Please refresh the page or go to the home page'));
}