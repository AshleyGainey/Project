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
    if (empty($new_email) || empty($password)) {
        header('HTTP/1.1 400 Bad Request Server');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - All required fields need to be filled out'));
    } else {
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

        $sql = "UPDATE user SET userEmail = '" . $new_email . "' where userID = " . $userID;


        if ($conn->query($sql) === TRUE) {
            echo "Record updated successfully";
            $_SESSION['userEmail'] = $new_email;
        } else {
            echo "Error updating record: " . $conn->error;
            header('HTTP/1.1 Internal Server Error');
            header('Content-Type: application/json; charset=UTF-8');
            die(json_encode('ERROR' + $conn->error));
        }
    } else {
        header('HTTP/1.1 400 Bad Request Server');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - Password not correct'));
    }
    }
} else if (
    $_POST['process'] == "Password"
) {
    include 'DBlogin.php';
    $oldPassword = $_POST['oldPassword'];
    $newPassword = $_POST['newPassword'];
    $confirmPassword = $_POST['confirmPassword'];

    if (empty($oldPassword) || empty($newPassword) || empty($confirmPassword)) {
        header('HTTP/1.1 400 Bad Request Server');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - All required fields need to be filled out'));
    } else {
    


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
    }
} else if ($_POST['process'] == "Address") {
    // update address a INNER JOIN user u ON a.addressID = u.mainAddressID 
    // set a.title = ?, a.firstName = ?, a.lastName = ?, a.addressLine1 = ?, a.addressLine2 = ?, a.townCity = ?, a.county = ?, a.postCode = ? 
    // where u.userID = ?;

    include 'DBlogin.php';
    $title = $_POST['title'];
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $addressLine1 = $_POST['addressLine1'];
    $addressLine2 = $_POST['addressLine2'];
    $townCity = $_POST['townCity'];
    $county = $_POST['county'];
    $postCode = $_POST['postcode'];



    if (empty($title) || empty($firstName) || empty($addressLine1) || empty($townCity) || empty($county) || empty($postCode)) {
        header('HTTP/1.1 400 Bad Request Server');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - All required fields need to be filled out'));
    } else {
        if (
            !$title === "Mr" || !$title === "Master" ||
            !$title === "Miss" || !$title === "Mrs" || !$title === "Ms" ||
            !$title === "Dr"
        ) {
            header('HTTP/1.1 400 Bad Request Server');
            header('Content-Type: application/json; charset=UTF-8');
            die(json_encode('ERROR - Title from list is not selected'));
        } else {

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
        }
    }
} else {
    header('HTTP/1.1 400 Bad Request Server');
    header('Content-Type: application/json; charset=UTF-8');
    die(json_encode('ERROR - You are in an incorrect state. Please refresh the page or go to the home page'));
}