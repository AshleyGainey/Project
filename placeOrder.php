<?php
if (!isset($_SESSION)) {
    @ob_start();
    session_start();
}

$mainAddressID;
$billingAddressID;
$deliveryAddressID;



function getMainAddressID()
{
    include 'DBlogin.php';

    $conn = mysqli_connect($host, $user, $pass, $database);


    $userID = $_SESSION['userID'];
    $stmt = $conn->prepare("select mainAddressID from user where userID = ?");
    $stmt->bind_param("i", $userID);

    $stmt->execute();

    $res = $stmt->get_result();
    $mainaddressDB = mysqli_fetch_all($res, MYSQLI_ASSOC);



    global $mainAddressID;


    $mainAddressID = $mainaddressDB[0]['mainAddressID'];

    // $res = $stmt->get_result();
    // if ($res->num_rows > 0) {
    //     $stmt->close();
    //     // echo "Is already in DB";
    //     header('HTTP/1.1 400 Bad Request Server');
    //     header('Content-Type: application/json; charset=UTF-8');
    //     die(json_encode('ERROR - There is already an account with that email address. Please Log in or Register with a different email address'));

    //     return false;
    // }
    // echo
}

//Billing Part
if (isset($_POST['billingMethod']) && is_numeric($_POST['billingMethod'])) {
    $bilingMethod = $_POST['billingMethod'];
    if ($bilingMethod == 1) {
        echo "Selected main address billing";
        getMainAddressID();
        global $billingAddressID;
        $billingAddressID = $mainAddressID;
        echo "billingAddressIs: " . $billingAddressID;
    } else if ($bilingMethod == 2) {
        //New address
        echo "Selected new address: billing";




        //Check if already in DB (main address or just a normal address)
        if (isset($_POST['billingFirstName']) && isset($_POST['billingLastName']) && isset($_POST['billingAddressLine1']) && isset($_POST['billingTownCity']) && isset($_POST['billingCounty']) && isset($_POST['billingPostCode'])) {

            include 'DBlogin.php';

            $conn = mysqli_connect($host, $user, $pass, $database);

            $sql = "select addressID from user_address where addressLine1 = ? AND addressLine2 = ? AND townCity = ? AND county = ? AND postcode = ?";


            $billingAddressLine1 = $_POST['billingAddressLine1'];
            $billingAddressLine2 = $_POST['billingAddressLine2'];
            $billingTownCity = $_POST['billingTownCity'];
            $billingCounty = $_POST['billingCounty'];
            $billingPostCode = $_POST['billingPostCode'];


            echo "billingAddressLine2 is equal to: " . $billingAddressLine2;
            // if (empty($billingAddressLine2)) {
            //     $billingAddressLine2 = null;
            // }


            echo $sql;
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssss", $billingAddressLine1, $billingAddressLine2, $billingTownCity, $billingCounty, $billingPostCode);

            $stmt->execute();

            $res = $stmt->get_result();
            $mainaddressDB = mysqli_fetch_all($res, MYSQLI_ASSOC);
            echo "Resulttttt";

            if (mysqli_num_rows($res) > 0) {
                //In DB, set billingAddressID to the DB version
                global $billingAddressID;
                $billingAddressID = $mainaddressDB[0]['mainAddressID'];
            } else {
                //If not already in DB, then send it to the DB.


                //TODO Ashley; Come back to.... NO FIRST AND LAST NAME FOR ADDDRESS.
                $stmt = $conn->prepare("INSERT INTO user_address (addressLine1, addressLine2, townCity, county, postcode)
VALUES (?, ?, ?, ?, ?)");

                $addressLine1 = $_POST['billingAddressLine1'];
                $addressLine2 = $_POST['billingAddressLine2'];
                $townCity =    $_POST['billingTownCity'];
                $county =    $_POST['billingCounty'];
                $postcode =    $_POST['billingPostCode'];

                $stmt->bind_param("sssss", $addressLine1, $addressLine2, $townCity, $county, $postcode);

                $stmt->execute();
                global $mainAddressID;
                $mainAddressID = mysqli_insert_id($conn);
            }
        } else {
            //Not all required fields are set, don't send request to DB.
        }
    } else {
        echo "Invalidddddd";
    }



    // echo "In Place Order:";
    // print_r($_SESSION['basket']);
} else {
    echo "In Place Order: BUT IT FUCKED UP";
}

echo "MainAddressID is: " . $mainAddressID;

//Delivery Part
if (isset($_POST['deliveryMethod']) && is_numeric($_POST['deliveryMethod'])) {
    $deliveryMethod = $_POST['deliveryMethod'];
    if ($deliveryMethod == 1) {
        echo "- Same as billing address: delivery (NOT MAIN ADDRESS)";
    } else if ($deliveryMethod == 2) {
        echo "- Selected main address: delivery";
        if (!isset($mainAddressID)) {
            getMainAddressID();
        }
        global $deliveryAddressID;
        $deliveryAddressID = $mainAddressID;
        echo "deliveryAddressIs: " . $deliveryAddressID;
    } else if ($deliveryMethod == 3) {
        echo "- Selected new address: delivery";
    } else {
        echo "Invalidddddd";
    }



    // echo "In Place Order:";
    // print_r($_SESSION['basket']);
} else {
    echo "In Place Order: BUT IT FUCKED UP";
}
