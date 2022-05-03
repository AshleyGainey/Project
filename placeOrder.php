<?php
if (!isset($_SESSION)) {
    @ob_start();
    session_start();
}

$mainAddressID;
$billingAddressID;
$deliveryAddressID;



function getMainAddressID() {
    include 'DatabaseLoginDetails.php';

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

            include 'DatabaseLoginDetails.php';

            $conn = mysqli_connect($host, $user, $pass, $database);

            $billingTitle = $_POST['billingTitle'];
            //Server side validation for Billing Title
            if ($billingTitle !== "Mr" || $billingTitle !== "Master" ||
                $billingTitle !== "Miss" || $billingTitle !== "Mrs" || $billingTitle !== "Ms" ||
                $billingTitle !== "Dr") {
                header('HTTP/1.1 400 Bad Request Server');
                header('Content-Type: application/json; charset=UTF-8');
                die(json_encode('ERROR - Billing Title from list is not selected - Please select from the list'));
            }




            $billingFirstName = $_POST['billingFirstName'];
          //Server side validation for Billing Title
            if (strlen($billingFirstName) < 2) {
                header('HTTP/1.1 400 Bad Request Server');
                header('Content-Type: application/json; charset=UTF-8');
                die(json_encode('ERROR - Billing First Name length is too weak. It must be a minimum of 2 characters.'));
            }

            if (strlen($billingFirstName) > 255) {
                header('HTTP/1.1 400 Bad Request Server');
                header('Content-Type: application/json; charset=UTF-8');
                die(json_encode('ERROR - Billing First Name length is too strong. It must be a maximum of 255 characters.'));
            }
            if (!(preg_match('/^\D+$/', $billingFirstName))) {
                header('HTTP/1.1 400 Bad Request Server');
                header('Content-Type: application/json; charset=UTF-8');
                die(json_encode('ERROR - Incorrect Format for Billing First Name. First Name should not contain numbers'));
            }


            $billingLastName = $_POST['billingLastName'];
            if (strlen($billingLastName) < 2) {
                header('HTTP/1.1 400 Bad Request Server');
                header('Content-Type: application/json; charset=UTF-8');
                die(json_encode('ERROR - Billing Last Name length is too weak. It must be a minimum of 2 characters.'));
            }

            if (strlen($billingLastName) > 255) {
                header('HTTP/1.1 400 Bad Request Server');
                header('Content-Type: application/json; charset=UTF-8');
                die(json_encode('ERROR - BillingLast Name length is too strong. It must be a maximum of 255 characters.'));
            }

            if (!(preg_match('/^\D+$/', $billingLastName))) {
                header('HTTP/1.1 400 Bad Request Server');
                header('Content-Type: application/json; charset=UTF-8');
                die(json_encode('ERROR - Incorrect Format for Billing Last Name. Last Name should not contain numbers'));
            }


            $billingAddressLine1 = $_POST['billingAddressLine1'];
            if (strlen($billingAddressLine1) < 2) {
                header('HTTP/1.1 400 Bad Request Server');
                header('Content-Type: application/json; charset=UTF-8');
                die(json_encode('ERROR - Billing Address Line 1 length is too weak. It must be a minimum of 2 characters.'));
            }

            if (strlen($billingAddressLine1) > 255) {
                header('HTTP/1.1 400 Bad Request Server');
                header('Content-Type: application/json; charset=UTF-8');
                die(json_encode('ERROR - Billing Address Line 1 length is too strong. It must be a maximum of 255 characters.'));
            }

            $billingAddressLine2 = $_POST['billingAddressLine2'];
            if (!empty($billingAddressLine2) && strlen($billingAddressLine2) < 2) {
                header('HTTP/1.1 400 Bad Request Server');
                header('Content-Type: application/json; charset=UTF-8');
                die(json_encode('ERROR - Billing Address Line 2 length is too weak. It must be a minimum of 2 characters if not blank.'));
            }

            $billingTownCity = $_POST['billingTownCity'];
            if (strlen($billingTownCity) < 2) {
                header('HTTP/1.1 400 Bad Request Server');
                header('Content-Type: application/json; charset=UTF-8');
                die(json_encode('ERROR - Billing Town/City length is too weak. It must be a minimum of 2 characters.'));
            }

            if (strlen($billingTownCity) > 255) {
                header('HTTP/1.1 400 Bad Request Server');
                header('Content-Type: application/json; charset=UTF-8');
                die(json_encode('ERROR - Billing Town/City length is too strong. It must be a maximum of 255 characters.'));
            }

            $billingCounty = $_POST['billingCounty'];
            if (strlen($billingCounty) < 2) {
                header('HTTP/1.1 400 Bad Request Server');
                header('Content-Type: application/json; charset=UTF-8');
                die(json_encode('ERROR - Billing County length is too weak. It must be a minimum of 2 characters.'));
            }

            if (strlen($billingCounty) > 255) {
                header('HTTP/1.1 400 Bad Request Server');
                header('Content-Type: application/json; charset=UTF-8');
                die(json_encode('ERROR - Billing County length is too strong. It must be a maximum of 255 characters.'));
            }

            $billingPostCode = $_POST['billingPostCode'];
            if (strlen($billingPostCode) < 2) {
                header('HTTP/1.1 400 Bad Request Server');
                header('Content-Type: application/json; charset=UTF-8');
                die(json_encode('ERROR - Billing Postcode length is too weak. It must be a minimum of 2 characters.'));
            }

            if (strlen($billingPostCode) > 255) {
                header('HTTP/1.1 400 Bad Request Server');
                header('Content-Type: application/json; charset=UTF-8');
                die(json_encode('ERROR - Billing Postcode length is too strong. It must be a maximum of 255 characters.'));
            }


            $stmt = $conn->prepare("SELECT addressID from address where title = ? AND firstName = ? AND lastName = ? AND addressLine1 = ? AND addressLine2 = ? AND townCity = ? AND county = ? AND postcode = ?");
            $stmt->bind_param("ssssssss", $billingTitle, $billingFirstName, $billingLastName, $billingAddressLine1, $billingAddressLine2, $billingTownCity, $billingCounty, $billingPostCode);

            if (!$stmt->execute()) {
                header('HTTP/1.1 500 Internal Server Error');
                header('Content-Type: application/json; charset=UTF-8');
                die(json_encode('ERROR - Could not process your order.'));
            }

            $res = $stmt->get_result();
            $mainaddressDB = mysqli_fetch_all($res, MYSQLI_ASSOC);

            if (mysqli_num_rows($res) > 0) {
                //In DB, set billingAddressID to the DB version
                global $billingAddressID;
                $billingAddressID = $mainaddressDB[0]['addressID'];
            } else {
                //If not already in DB, then send it to the DB.


                $stmt = $conn->prepare("INSERT INTO address (title, firstName, lastName, addressLine1, addressLine2, townCity, county, postcode)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

                $stmt->bind_param("ssssssss", $billingTitle, $billingFirstName, $billingLastName, $billingAddressLine1, $billingAddressLine2, $billingTownCity, $billingCounty, $billingPostCode);

                if (!$stmt->execute()) {
                    header('HTTP/1.1 500 Internal Server Error');
                    header('Content-Type: application/json; charset=UTF-8');
                    die(json_encode('ERROR - Could not process your order.'));
                }
                global $mainAddressID;
                $billingAddressID = mysqli_insert_id($conn);
            }
        } else {
            //Not all required fields are set, don't send request to DB.
            header('HTTP/1.1 400 Bad Request Server');
            header('Content-Type: application/json; charset=UTF-8');
            die(json_encode('ERROR - Not all required fields are filled out for the Billing address, please fill them out.'));
            return false;
        }
    } else {
        //If not billing address method 1 or 2
        header('HTTP/1.1 400 Bad Request Server');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - Invalid data for the Billing Address. Please contact support if problem persists'));
        return false;
    }
} else {
    header('HTTP/1.1 Internal Server Error');
    header('Content-Type: application/json; charset=UTF-8');
    die(json_encode('ERROR - Problem with data that was sent to us. Please contact support if problem persists'));
    return false;
}

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

            include 'DatabaseLoginDetails.php';

            $conn = mysqli_connect($host, $user, $pass, $database);


            $deliveryTitle = $_POST['deliveryTitle'];
            if (
                $deliveryTitle !== "Mr" || $deliveryTitle !== "Master" ||
                $deliveryTitle !== "Miss" || $deliveryTitle !== "Mrs" || $deliveryTitle !== "Ms" ||
                $deliveryTitle !== "Dr"
            ) {
                header('HTTP/1.1 400 Bad Request Server');
                header('Content-Type: application/json; charset=UTF-8');
                die(json_encode('ERROR - Delivery Title from list is not selected - Please select from the list'));
            }

            $deliveryFirstName = $_POST['deliveryFirstName'];
            //Server side validation for Billing Title
            if (strlen($deliveryFirstName) < 2) {
                header('HTTP/1.1 400 Bad Request Server');
                header('Content-Type: application/json; charset=UTF-8');
                die(json_encode('ERROR - Delivery First Name length is too weak. It must be a minimum of 2 characters.'));
            }

            if (strlen($deliveryFirstName) > 255) {
                header('HTTP/1.1 400 Bad Request Server');
                header('Content-Type: application/json; charset=UTF-8');
                die(json_encode('ERROR - Delivery First Name length is too strong. It must be a maximum of 255 characters.'));
            }
            if (!(preg_match('/^\D+$/', $deliveryFirstName))) {
                header('HTTP/1.1 400 Bad Request Server');
                header('Content-Type: application/json; charset=UTF-8');
                die(json_encode('ERROR - Incorrect Format for Delivery First Name. First Name should not contain numbers'));
            }

            $deliveryLastName = $_POST['deliveryLastName'];
            if (strlen($deliveryLastName) < 2) {
                header('HTTP/1.1 400 Bad Request Server');
                header('Content-Type: application/json; charset=UTF-8');
                die(json_encode('ERROR - Delivery Last Name length is too weak. It must be a minimum of 2 characters.'));
            }

            if (strlen($deliveryLastName) > 255) {
                header('HTTP/1.1 400 Bad Request Server');
                header('Content-Type: application/json; charset=UTF-8');
                die(json_encode('ERROR - Delivery Last Name length is too strong. It must be a maximum of 255 characters.'));
            }

            if (!(preg_match('/^\D+$/', $deliveryLastName))) {
                header('HTTP/1.1 400 Bad Request Server');
                header('Content-Type: application/json; charset=UTF-8');
                die(json_encode('ERROR - Incorrect Format for Delivery Last Name. Last Name should not contain numbers'));
            }

            $deliveryAddressLine1 = $_POST['deliveryAddressLine1'];
            if (strlen($deliveryAddressLine1) < 2) {
                header('HTTP/1.1 400 Bad Request Server');
                header('Content-Type: application/json; charset=UTF-8');
                die(json_encode('ERROR - Delivery Address Line 1 length is too weak. It must be a minimum of 2 characters.'));
            }

            if (strlen($deliveryAddressLine1) > 255) {
                header('HTTP/1.1 400 Bad Request Server');
                header('Content-Type: application/json; charset=UTF-8');
                die(json_encode('ERROR - Delivery Address Line 1 length is too strong. It must be a maximum of 255 characters.'));
            }

            $deliveryAddressLine2 = $_POST['deliveryAddressLine2'];
            if (!empty($deliveryAddressLine2) && strlen($deliveryAddressLine2) < 2) {
                header('HTTP/1.1 400 Bad Request Server');
                header('Content-Type: application/json; charset=UTF-8');
                die(json_encode('ERROR - Delivery Address Line 2 length is too weak. It must be a minimum of 2 characters if not blank.'));
            }

            $deliveryTownCity = $_POST['deliveryTownCity'];
            if (strlen($deliveryTownCity) < 2) {
                header('HTTP/1.1 400 Bad Request Server');
                header('Content-Type: application/json; charset=UTF-8');
                die(json_encode('ERROR - Delivery Town/City length is too weak. It must be a minimum of 2 characters.'));
            }

            if (strlen($deliveryTownCity) > 255) {
                header('HTTP/1.1 400 Bad Request Server');
                header('Content-Type: application/json; charset=UTF-8');
                die(json_encode('ERROR - Delivery Town/City length is too strong. It must be a maximum of 255 characters.'));
            }

            $deliveryCounty = $_POST['deliveryCounty'];
            if (strlen($deliveryCounty) < 2) {
                header('HTTP/1.1 400 Bad Request Server');
                header('Content-Type: application/json; charset=UTF-8');
                die(json_encode('ERROR - Delivery County length is too weak. It must be a minimum of 2 characters.'));
            }

            if (strlen($deliveryCounty) > 255) {
                header('HTTP/1.1 400 Bad Request Server');
                header('Content-Type: application/json; charset=UTF-8');
                die(json_encode('ERROR - Delivery County length is too strong. It must be a maximum of 255 characters.'));
            }

            $deliveryPostCode = $_POST['deliveryPostCode'];

            if (strlen($deliveryPostCode) < 2) {
                header('HTTP/1.1 400 Bad Request Server');
                header('Content-Type: application/json; charset=UTF-8');
                die(json_encode('ERROR - Delivery Postcode length is too weak. It must be a minimum of 2 characters.'));
            }

            if (strlen($deliveryPostCode) > 255) {
                header('HTTP/1.1 400 Bad Request Server');
                header('Content-Type: application/json; charset=UTF-8');
                die(json_encode('ERROR - Delivery Postcode length is too strong. It must be a maximum of 255 characters.'));
            }

            $stmt = $conn->prepare("SELECT addressID from address where title = ? AND firstName = ? AND lastName = ? AND addressLine1 = ? AND addressLine2 = ? AND townCity = ? AND county = ? AND postcode = ?");
            $stmt->bind_param("ssssssss", $deliveryTitle, $deliveryFirstName, $deliveryLastName, $deliveryAddressLine1, $deliveryAddressLine2, $deliveryTownCity, $deliveryCounty, $deliveryPostCode);

            if (!$stmt->execute()) {
                header('HTTP/1.1 500 Internal Server Error');
                header('Content-Type: application/json; charset=UTF-8');
                die(json_encode('ERROR - Could not process your order.'));
            }

            $res = $stmt->get_result();
            $addressFound = mysqli_fetch_all($res, MYSQLI_ASSOC);

            if (mysqli_num_rows($res) > 0) {
                //In DB, set deliveryAddressID to the DB version
                global $deliveryAddressID;
                $deliveryAddressID = $addressFound[0]['addressID'];
            } else {
                //If not already in DB, then send it to the DB.


                //TODO Ashley; Come back to.... NO FIRST AND LAST NAME FOR ADDDRESS.
                $stmt = $conn->prepare("INSERT INTO address (title, firstName, lastName, addressLine1, addressLine2, townCity, county, postcode)
VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

                $stmt->bind_param("ssssssss",
                    $deliveryTitle,
                    $deliveryFirstName,
                    $deliveryLastName,
                    $deliveryAddressLine1,
                    $deliveryAddressLine2,
                    $deliveryTownCity,
                    $deliveryCounty,
                    $deliveryPostCode
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
        die(json_encode('ERROR - Invalid data for the Delivery Address. Please contact support if problem persists.'));
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
    if ($productQuantity > 10) {
        header('HTTP/1.1 400 Bad Request Server');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - Cannot proceed your order due to the quantity of an item in your basket exceeds the customer limit of 10'));
        return false;
    }

    include 'DatabaseLoginDetails.php';

    $conn = mysqli_connect($host, $user, $pass, $database);

    // echo $sql;
    // echo $productID;
    $stmt = $conn->prepare("SELECT productPrice, productTotalQuantity from product where productID = ?");

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
        die(json_encode('ERROR - Cannot proceed your order due to the quantity of an item in your basket exceeds what we have in stock'));
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