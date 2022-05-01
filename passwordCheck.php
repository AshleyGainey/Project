<?php
if (!isset($_SESSION)) {
    @ob_start();
    session_start();
}
//If registering
if (isset($_POST['Register'])) {
    include 'DBlogin.php';
    $email =    $_POST['emailAddress'];
    $title = $_POST['title'];
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $addressLine1 = $_POST['addressLine1'];
    $addressLine2 = $_POST['addressLine2'];
    $townCity =    $_POST['townCity'];
    $county =    $_POST['county'];
    $postcode =    $_POST['postcode'];

    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];

    //Server Side checking to see if any feilds  and Confirm Password is correct
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
        // Server Side checking to see if Password has enough complexity (second check, less than 128 characters)
    }


    if (strlen($password) > 127) {
        header('HTTP/1.1 400 Bad Request Server');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - Password length is too strong. It must be within 128 characters.'));
        // Server Side checking to see if Password has enough complexity (third check, at least 5 letters)
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

        return false;
    }

    // print_r('Still continuing');

    $stmt = $conn->prepare(
        "INSERT INTO address (title, firstName, lastName, addressLine1, addressLine2, townCity, county, postcode)
VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
    );

    $stmt->bind_param("ssssssss", $title, $firstName, $lastName, $addressLine1, $addressLine2, $townCity, $county, $postcode);

    $stmt->execute();
    $addressID = mysqli_insert_id($conn);

    // print_r($addressID);
    // $res = $stmt->get_result();

    // $row = $res->fetch_assoc();









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
    include 'DBlogin.php';
    $email =    $_POST['emailAddress'];
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


    $conn = mysqli_connect($host, $user, $pass, $database);

    if (!$conn) {
        header('HTTP/1.1 500 Internal Server Error');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - Please contact us with this issue.'));
        echo 'Connection error: ' . mysqli_connect_error();
    }

   
    $query = 'SELECT u.userID, u.userEmail, u.userPassword, a.firstName, a.lastName From user u INNER JOIN address a ON u.mainAddressID = a.addressID where userEmail = "' . $email . '" LIMIT 1';


    $result = mysqli_query($conn, $query);

    $user = mysqli_fetch_all($result, MYSQLI_ASSOC);
    $rows = mysqli_num_rows($result);


    if ($rows == 0) {
        header('HTTP/1.1 400 Bad Request Server');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - Account with that email address does not exist'));
        return false;
    }


    // print_r($user);
    if (password_verify($password, $user[0]['userPassword'])) {

        header('Content-Type: application/json');
        print json_encode($result);

        // header('Location: Error404.php');
        $_SESSION['userID'] = $user[0]["userID"];
        $_SESSION['userEmail'] = $user[0]["userEmail"];
        $_SESSION['userFirstName'] = $user[0]["firstName"];
        // header('Location: Login.php');
        exit();

        return true;
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