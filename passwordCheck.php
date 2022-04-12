<?php
if (!isset($_SESSION)) {
    @ob_start();
    session_start();
}
//If registering
if (isset($_POST['Register'])) {
    include 'DBlogin.php';
    $password = $_POST['password'];
    $hash = password_hash($password, PASSWORD_BCRYPT, array('cost' => 11));

    $conn = mysqli_connect($host, $user, $pass, $database);


    $stmt = $conn->prepare("SELECT UserID From user where userEmail = ?");
    $email =    $_POST['emailAddress'];
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

    print_r('Still continuing');

    $stmt = $conn->prepare("INSERT INTO user_address (addressLine1, addressLine2, townCity, county, postcode)
VALUES (?, ?, ?, ?, ?)");

    $addressLine1 = $_POST['addressLine1'];
    $addressLine2 = $_POST['addressLine2'];
    $townCity =    $_POST['townCity'];
    $county =    $_POST['county'];
    $postcode =    $_POST['postcode'];

    $stmt->bind_param("sssss", $addressLine1, $addressLine2, $townCity, $county, $postcode);

    $stmt->execute();
    $addressID = mysqli_insert_id($conn);

    // print_r($addressID);
    // $res = $stmt->get_result();

    // $row = $res->fetch_assoc();









    $stmt2 = $conn->prepare("INSERT INTO user (userTitle, userFirstName, userLastName, userEmail, userPassword, mainAddressID, typeOfUser)
VALUES (?, ?, ?, ?, ?, ?, ?)");

    $title = $_POST['title'];
    $firstName = $_POST['firstName'];
    $lastName =    $_POST['lastName'];
    $typeOfUser =    "Customer";

    $stmt2->bind_param("sssssis", $title, $firstName, $lastName, $email, $hash, $addressID, $typeOfUser);
    $stmt2->execute();

    $userID = mysqli_insert_id($conn);

    $_SESSION['userID'] = $userID;
    $_SESSION['userEmail'] = $email;
    $_SESSION['userFirstName'] = $firstName;
    $_SESSION['userLastName'] = $lastName;
    $_SESSION["register"] = false;

} else if (isset($_POST['Login'])) {
    include 'DBlogin.php';

    $conn = mysqli_connect($host, $user, $pass, $database);

    if (!$conn) {
        header('HTTP/1.1 500 Internal Server Error');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - Please contact us with this issue.'));
        echo 'Connection error: ' . mysqli_connect_error();
    }

    $email =    $_POST['emailAddress'];
    $password = $_POST['password'];

    $query = 'SELECT userID, userEmail, userFirstName, userLastName, userPassword From user where userEmail = "' . $email . '" LIMIT 1';

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
        $_SESSION['userFirstName'] = $user[0]["userFirstName"];
        $_SESSION['userLastName'] = $user[0]["userLastName"];
        $_SESSION["register"] = false;
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