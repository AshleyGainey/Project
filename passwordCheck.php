<?php
session_start();
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
        echo "Is already in DB";
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

    print_r($addressID);
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
} else if (isset($_POST['Login'])) {
    include 'DBlogin.php';

    $conn = mysqli_connect($host, $user, $pass, $database);

    if (!$conn) {
        echo 'Connection error: ' . mysqli_connect_error();
    }

    $email =    $_POST['emailAddress'];
    $password = $_POST['password'];

    $query = 'SELECT userID, userEmail, userFirstName, userLastName, userPassword From user where userEmail = "' . $email . '" LIMIT 1';

    $result = mysqli_query($conn, $query);

    $user = mysqli_fetch_all($result, MYSQLI_ASSOC);
    $rows = mysqli_num_rows($result);


    if ($rows == 0) {
        echo "Not in DB";
        return false;
    }


    // print_r($user);
    if (password_verify($password, $user[0]['userPassword'])) {

        // header('Location: Error404.php');
        $_SESSION['userID'] = $user[0]["userID"];
        $_SESSION['userEmail'] = $user[0]["userEmail"];
        $_SESSION['userFirstName'] = $user[0]["userFirstName"];
        $_SESSION['userLastName'] = $user[0]["userLastName"];
        $_SESSION['userID'] = $user[0]["userID"];
        echo "Password verified +" .  $_SESSION['userFirstName'];
    } else {
        echo "Fuck you Syed";
    }

    //Free  memory and close the connection
    mysqli_free_result($result);
    mysqli_close($conn);
}
