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

    if (!$stmt->execute()) {
        header('HTTP/1.1 500 Internal Server Error');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - Could not process your order.'));
    }

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


    // print_r($_SESSION['basket']);


    $bilingMethod = $_POST['billingMethod'];
    if ($bilingMethod == 1) {
        // echo "Selected main address billing";
        getMainAddressID();
        global $billingAddressID;
        $billingAddressID = $mainAddressID;
        // echo "billingAddressIs: " . $billingAddressID;
    } else if ($bilingMethod == 2) {
        //New address
        // echo "Selected new address: billing";




        //Check if already in DB (main address or just a normal address)
        if (isset($_POST['billingFirstName']) && isset($_POST['billingLastName']) && isset($_POST['billingAddressLine1']) && isset($_POST['billingTownCity']) && isset($_POST['billingCounty']) && isset($_POST['billingPostCode'])) {

            include 'DBlogin.php';

            $conn = mysqli_connect($host, $user, $pass, $database);

            $sql = "select addressID from address where title = ? AND firstName = ? AND lastName = ? AND addressLine1 = ? AND addressLine2 = ? AND townCity = ? AND county = ? AND postcode = ?";


            $billingTitle = $_POST['billingTitle'];
            $billingFirstName = $_POST['billingFirstName'];
            $billingLastName = $_POST['billingLastName'];
            $billingAddressLine1 = $_POST['billingAddressLine1'];
            $billingAddressLine2 = $_POST['billingAddressLine2'];
            $billingTownCity = $_POST['billingTownCity'];
            $billingCounty = $_POST['billingCounty'];
            $billingPostCode = $_POST['billingPostCode'];


            // if (!isset($billingAddressLine2)) {
            //     echo "actually going into this code";

            // }
            // // echo "billingAddressLine2 is equal to: " . $billingAddressLine2;

            unset($billingAddressLine2);
            
            // echo $sql;
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssssss", $billingTitle, $billingFirstName, $billingLastName, $billingAddressLine1, $billingAddressLine2, $billingTownCity, $billingCounty, $billingPostCode);

            if (!$stmt->execute()) {
                header('HTTP/1.1 500 Internal Server Error');
                header('Content-Type: application/json; charset=UTF-8');
                die(json_encode('ERROR - Could not process your order.'));
            }

            $res = $stmt->get_result();
            $mainaddressDB = mysqli_fetch_all($res, MYSQLI_ASSOC);
            // echo "Resulttttt";

            if (mysqli_num_rows($res) > 0) {
                //In DB, set billingAddressID to the DB version
                global $billingAddressID;
                $billingAddressID = $mainaddressDB[0]['addressID'];
            } else {
                //If not already in DB, then send it to the DB.


                //TODO Ashley; Come back to.... NO FIRST AND LAST NAME FOR ADDDRESS.
                $stmt = $conn->prepare("INSERT INTO address (title, firstName, lastName, addressLine1, addressLine2, townCity, county, postcode)
VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

                $title = $_POST['billingTitle'];
                $firstName = $_POST['billingFirstName'];
                $lastName = $_POST['billingLastName'];
                $addressLine1 = $_POST['billingAddressLine1'];
                $addressLine2 = $_POST['billingAddressLine2'];
                $townCity =    $_POST['billingTownCity'];
                $county =    $_POST['billingCounty'];
                $postcode =    $_POST['billingPostCode'];

                $stmt->bind_param("ssssssss", $title, $firstName, $lastName, $addressLine1, $addressLine2, $townCity, $county, $postcode);

                if (!$stmt->execute()) {
                    header('HTTP/1.1 500 Internal Server Error');
                    header('Content-Type: application/json; charset=UTF-8');
                    die(json_encode('ERROR - Could not process your order.'));
                }
                global $mainAddressID;
                $billingAddressID = mysqli_insert_id($conn);
            }
        } else {
            header('HTTP/1.1 400 Bad Request Server');
            header('Content-Type: application/json; charset=UTF-8');
            die(json_encode('ERROR - Not all required fields are filled out for the Billing address, please fill them out.'));
            return false;
            //Not all required fields are set, don't send request to DB.
        }
    } else {
        header('HTTP/1.1 400 Bad Request Server');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - Invalid data for the Billing Address. Please contact support if problem persists'));
        return false;
    }
    // echo "In Place Order:";
    // print_r($_SESSION['basket']);
} else {
    header('HTTP/1.1 Internal Server Error');
    header('Content-Type: application/json; charset=UTF-8');
    die(json_encode('ERROR - Problem with data that was sent to us. Please contact support if problem persists'));
    return false;
}
// echo "BillingAddress is: " . $billingAddressID;



//Delivery Part
if (isset($_POST['deliveryMethod']) && is_numeric($_POST['deliveryMethod'])) {
    $deliveryMethod = $_POST['deliveryMethod'];
    if ($deliveryMethod == 1) {
        global $deliveryAddressID;
        $billingAddressID = $deliveryAddressID;
        // echo "- Same as delivery address: delivery (NOT MAIN ADDRESS): " . $deliveryAddressID;
    } else if ($deliveryMethod == 2) {
        // echo "- Selected main address: delivery";
        if (!isset($mainAddressID)) {
            getMainAddressID();
        }
        global $deliveryAddressID;
        $deliveryAddressID = $mainAddressID;
        // echo "deliveryAddressIs: " . $deliveryAddressID;
    } else if ($deliveryMethod == 3) {
        // echo "- Selected new address: delivery";

        //Check if already in DB (main address or just a normal address)
        if (isset($_POST['deliveryFirstName']) && isset($_POST['deliveryLastName']) && isset($_POST['deliveryAddressLine1']) && isset($_POST['deliveryTownCity']) && isset($_POST['deliveryCounty']) && isset($_POST['deliveryPostCode'])) {

            include 'DBlogin.php';

            $conn = mysqli_connect($host, $user, $pass, $database);

            $sql = "select addressID from address where title = ? AND firstName = ? AND lastName = ? AND addressLine1 = ? AND addressLine2 = ? AND townCity = ? AND county = ? AND postcode = ?";

            $deliveryTitle = $_POST['deliveryTitle'];
            $deliveryFirstName = $_POST['deliveryFirstName'];
            $deliveryLastName = $_POST['deliveryLastName'];
            $deliveryAddressLine1 = $_POST['deliveryAddressLine1'];
            $deliveryAddressLine2 = $_POST['deliveryAddressLine2'];
            $deliveryTownCity = $_POST['deliveryTownCity'];
            $deliveryCounty = $_POST['deliveryCounty'];
            $deliveryPostCode = $_POST['deliveryPostCode'];


            // echo "deliveryAddressLine2 is equal to: " . $deliveryAddressLine2;
            // if (empty($deliveryAddressLine2)) {
            //     $deliveryAddressLine2 = null;
            // }


            // echo $sql;
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssssss", $deliveryTitle, $deliveryFirstName, $deliveryLastName, $deliveryAddressLine1, $deliveryAddressLine2, $deliveryTownCity, $deliveryCounty, $deliveryPostCode);

            if (!$stmt->execute()) {
                header('HTTP/1.1 500 Internal Server Error');
                header('Content-Type: application/json; charset=UTF-8');
                die(json_encode('ERROR - Could not process your order.'));
            }

            $res = $stmt->get_result();
            $addressFound = mysqli_fetch_all($res, MYSQLI_ASSOC);
            // echo "Resulttttt";

            if (mysqli_num_rows($res) > 0) {
                //In DB, set deliveryAddressID to the DB version
                global $deliveryAddressID;
                $deliveryAddressID = $addressFound[0]['addressID'];
            } else {
                //If not already in DB, then send it to the DB.


                //TODO Ashley; Come back to.... NO FIRST AND LAST NAME FOR ADDDRESS.
                $stmt = $conn->prepare("INSERT INTO address (title, firstName, lastName, addressLine1, addressLine2, townCity, county, postcode)
VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

                $title = $_POST['deliveryTitle'];
                $firstName = $_POST['deliveryFirstName'];
                $lastName = $_POST['deliveryLastName'];
                $addressLine1 = $_POST['deliveryAddressLine1'];
                $addressLine2 = $_POST['deliveryAddressLine2'];
                $townCity =    $_POST['deliveryTownCity'];
                $county =    $_POST['deliveryCounty'];
                $postcode =    $_POST['deliveryPostCode'];

                $stmt->bind_param("ssssssss", $title,
                    $firstName,
                    $lastName,
                    $addressLine1,
                    $addressLine2,
                    $townCity,
                    $county,
                    $postcode
                );

                if (!$stmt->execute()) {
                    header('HTTP/1.1 500 Internal Server Error');
                    header('Content-Type: application/json; charset=UTF-8');
                    die(json_encode('ERROR - Could not process your order.'));
                }
                global $mainAddressID;
                $deliveryAddressID = mysqli_insert_id($conn);
            }
        } else {
            header('HTTP/1.1 400 Bad Request Server');
            header('Content-Type: application/json; charset=UTF-8');
            die(json_encode('ERROR - Not all required fields are filled out for the Delivery address, please fill them out.'));
            //Not all required fields are set, don't send request to DB.
        }
    } else {
        header('HTTP/1.1 400 Bad Request Server');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - Invalid data for the Billing Address. Please contact support if problem persists.'));
        return false;
    }
    // echo "In Place Order:";
    // print_r($_SESSION['basket']);
} else {
    header('HTTP/1.1 Internal Server Error');
    header('Content-Type: application/json; charset=UTF-8');
    die(json_encode('ERROR - Problem with data that was sent to us. Please contact support if problem persists.'));
    return false;
}
// echo "BillingAddress is: " . $billingAddressID;
// echo "DeliveryAddress is: " . $deliveryAddressID;

$orderProduct_ProductID = array();
$orderProduct_ProductPrice = array();
$orderProduct_ProductQuantity = array();
$product_Quantity = array();


foreach ($_SESSION['basket'] as $productID => $productQuantity) {
    include 'DBlogin.php';

    $conn = mysqli_connect($host, $user, $pass, $database);



    //Get data from database
    $sql = "select productPrice, productTotalQuantity from product where productID = ?";

    // echo $sql;
    // echo $productID;
    $stmt = $conn->prepare($sql);

    $stmt->bind_param("s", $productID);

    if (!$stmt->execute()) {
        header('HTTP/1.1 500 Internal Server Error');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - Could not process your order.'));
    }


    $res = $stmt->get_result();
    $productDetails = mysqli_fetch_all($res, MYSQLI_ASSOC);
    // print_r($productDetails);

    $productPrice = $productDetails[0]['productPrice'];
    $quantityOfProductDB =
        $productDetails[0]['productTotalQuantity'];


    if ($productQuantity > $quantityOfProductDB) {
        header('HTTP/1.1 400 Bad Request Server');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - Cannot proceed with order due to the quantity of an item in your basket exceeds what we have in stock'));
        return false;
    }


    //TODO Ashley: Add and all but nothing in - for now, if one product exceeds it, then all other products are purchased (maybe add to array instead)
    //ACID: https://stackoverflow.com/a/6655570/14757054


    array_push($orderProduct_ProductID, $productID);
    array_push($orderProduct_ProductPrice, $productPrice);
    array_push($orderProduct_ProductQuantity, $productQuantity);
    array_push($product_Quantity, $quantityOfProductDB);
}





//Each order
$totalOfOrder = 0.00;
// echo count($orderProduct_ProductPrice);
for ($x = 0; $x < count($orderProduct_ProductPrice); $x++) {
    $quantityPrice = $orderProduct_ProductQuantity[$x] * $orderProduct_ProductPrice[$x];
    $totalOfOrder = $totalOfOrder + $quantityPrice;
}

// echo "totalllllllll: £" . $totalOfOrder;


$order_dateTime = date("Y-m-d H:i:s");


// Insert into purchase_order
$stmt = $conn->prepare("INSERT INTO purchase_order (userID, totalPrice, DateAndTime, billingAddressID, deliveryAddressID)
    VALUES (?, ?, ?, ?, ?)");


$stmt->bind_param("idsii", $_SESSION['userID'], $totalOfOrder, $order_dateTime, $billingAddressID, $deliveryAddressID);

if (!$stmt->execute()) {
    header('HTTP/1.1 500 Internal Server Error');
    header('Content-Type: application/json; charset=UTF-8');
    die(json_encode('ERROR - Could not process your order.'));
}

$orderID = mysqli_insert_id($conn);
$_SESSION['orderID'] = $orderID;

$wentOk = 0;
//After getting orderID, put the order in for each product
for ($x = 0; $x < count($orderProduct_ProductPrice); $x++) {
    $productFromDB = $product_Quantity[$x];
    $productBought = $orderProduct_ProductQuantity[$x];
    $productIntoDB = $productFromDB - $productBought;
    $productID = $orderProduct_ProductID[$x];



    //    INSERT INTO ORDER_PRODUCT
    $stmt = $conn->prepare("INSERT INTO order_product (orderID, productID, productPriceAtTime, productQuantity)
    VALUES (?, ?, ?, ?)");



    $stmt->bind_param(
        "iidi",
        $orderID,
        $productID,
        $orderProduct_ProductPrice[$x],
        $productBought
    );

    if (!$stmt->execute()) {
        header('HTTP/1.1 500 Internal Server Error');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - Could not process your order.'));
    }


    //    Update Products table to reduce quantity
    $stmt = $conn->prepare("UPDATE product SET productTotalQuantity = ? WHERE productID = ?");



    $stmt->bind_param("ii", $productIntoDB, $productID);

    if ($stmt->execute()) {
        $wentOk++;
    } else {
        header('HTTP/1.1 500 Internal Server Error');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - Could not process your order.'));
    }
}

if ($wentOk == count($orderProduct_ProductPrice)) {
    header('HTTP/1.1 200 OK');
    header('Content-Type: application/json; charset=UTF-8');
    die(json_encode('Order Placed successfully'));
}