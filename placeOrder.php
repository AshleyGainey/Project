<?php
// If the session hasn't started. Start it (can then use session variables)
if (!isset($_SESSION)) {
    @ob_start();
    session_start();
}
// Global variables of the Address ID's
$mainAddressID;
$billingAddressID;
$deliveryAddressID;

function getMainAddressID()
{
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

    //Prepared Statement - query to select the main address ID from the user
    $stmt = $conn->prepare("select mainAddressID from user where userID = ?");
    //Binding the User ID from the session to the prepared statement
    $stmt->bind_param("i", $_SESSION['userID']);

    //Execute the query
    if (!$stmt->execute()) {
        //Error trying to execute the query to find the main address
        header('HTTP/1.1 500 Internal Server Error');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - Could not process your order. Could not find your Main Address'));
    }

    // Query has executed correctly. So put the result that has come back into a variable
    $res = $stmt->get_result();
    $mainaddressDB = mysqli_fetch_all($res, MYSQLI_ASSOC);


    // To edit a global variable, you have to declare you want to before changing it.
    global $mainAddressID;

    // The main address ID is what has come back from the database
    $mainAddressID = $mainaddressDB[0]['mainAddressID'];
}

//Billing Part - check to see if delivery method is defined and is numerical
if (isset($_POST['billingMethod']) && is_numeric($_POST['billingMethod'])) {
    // Get the billing method
    $billingMethod = $_POST['billingMethod'];
    if ($billingMethod == 1) {
        // They Selected the main address for billing;
        getMainAddressID();
        $billingAddressID = $mainAddressID;
    } else if ($billingMethod == 2) {
        // They Selected and entered the new address for billing
        //Check if already in DB (if it is a main address or just a normal address - and if so, use that ID)
        if (isset($_POST['billingTitle']) && isset($_POST['billingFirstName']) && isset($_POST['billingLastName']) && isset($_POST['billingAddressLine1']) && isset($_POST['billingTownCity']) && isset($_POST['billingCounty']) && isset($_POST['billingPostCode'])) {
            // Get the billing values from the post request and trim whitespaces from them
            $billingTitle = $_POST['billingTitle'];
            $billingFirstName = trim($_POST['billingFirstName']);
            $billingLastName = trim($_POST['billingLastName']);
            $billingAddressLine1 = trim($_POST['billingAddressLine1']);
            $billingAddressLine2 = trim($_POST['billingAddressLine2']);
            $billingTownCity = trim($_POST['billingTownCity']);
            //Remove any space (for instance 'L1 34Q') from Postcode
            $billingPostCode = str_replace(' ', '', $_POST['billingPostCode']);
            $billingCounty = trim($_POST['billingCounty']);

            //Server Side checking to see if any (but address line 2) fields are empty and if so, don't execute further
            if (empty($billingTitle)) {
                header('HTTP/1.1 400 Bad Request');
                header('Content-Type: application/json; charset=UTF-8');
                die(json_encode('ERROR - Billing Title is empty. Please fill it out.'));
            }
            if (empty($billingFirstName)) {
                header('HTTP/1.1 400 Bad Request');
                header('Content-Type: application/json; charset=UTF-8');
                die(json_encode('ERROR - Billing First Name is empty. Please fill it out.'));
            }
            if (empty($billingLastName)) {
                header('HTTP/1.1 400 Bad Request');
                header('Content-Type: application/json; charset=UTF-8');
                die(json_encode('ERROR - Billing Last Name is empty. Please fill it out.'));
            }
            if (empty($billingAddressLine1)) {
                header('HTTP/1.1 400 Bad Request');
                header('Content-Type: application/json; charset=UTF-8');
                die(json_encode('ERROR - Billing Address Line 1 is empty. Please fill it out.'));
            }
            if (empty($billingTownCity)) {
                header('HTTP/1.1 400 Bad Request');
                header('Content-Type: application/json; charset=UTF-8');
                die(json_encode('ERROR - Billing Town/City is empty. Please fill it out.'));
            }
            if (empty($billingPostCode)) {
                header('HTTP/1.1 400 Bad Request');
                header('Content-Type: application/json; charset=UTF-8');
                die(json_encode('ERROR - Billing County is empty. Please fill it out.'));
            }
            if (empty($billingCounty)) {
                header('HTTP/1.1 400 Bad Request');
                header('Content-Type: application/json; charset=UTF-8');
                die(json_encode('ERROR - Billing Postcode is empty. Please fill it out.'));
            }

            //Server side validation to check if the billing title is either Mr, Master, Miss, Mrs, Ms or Dr.
            if (
                $billingTitle !== "Mr" && $billingTitle !== "Master" &&
                $billingTitle !== "Miss" && $billingTitle !== "Mrs" && $billingTitle !== "Ms" &&
                $billingTitle !== "Dr"
            ) {
                header('HTTP/1.1 400 Bad Request');
                header('Content-Type: application/json; charset=UTF-8');
                die(json_encode('ERROR - Billing Title from list is not selected - Please select from the list'));
            }

            //Server side validation to check if the billing first name is more than a character
            if (strlen($billingFirstName) < 2) {
                header('HTTP/1.1 400 Bad Request');
                header('Content-Type: application/json; charset=UTF-8');
                die(json_encode('ERROR - Billing First Name length is too weak. It must be a minimum of 2 characters.'));
            }
            //Server side validation to check if the billing first name is less than 256 characters
            if (strlen($billingFirstName) > 255) {
                header('HTTP/1.1 400 Bad Request');
                header('Content-Type: application/json; charset=UTF-8');
                die(json_encode('ERROR - Billing First Name length is too strong. It must be a maximum of 255 characters.'));
            }
            //Server side validation to check if the billing first name doesn't contain any numbers in it
            if (!(preg_match('/^\D+$/', $billingFirstName))) {
                header('HTTP/1.1 400 Bad Request');
                header('Content-Type: application/json; charset=UTF-8');
                die(json_encode('ERROR - Incorrect Format for Billing First Name. First Name should not contain numbers'));
            }

            //Server side validation to check if the billing last name is more than a character
            if (strlen($billingLastName) < 2) {
                header('HTTP/1.1 400 Bad Request');
                header('Content-Type: application/json; charset=UTF-8');
                die(json_encode('ERROR - Billing Last Name length is too weak. It must be a minimum of 2 characters.'));
            }

            //Server side validation to check if the billing last name is less than 256 characters
            if (strlen($billingLastName) > 255) {
                header('HTTP/1.1 400 Bad Request');
                header('Content-Type: application/json; charset=UTF-8');
                die(json_encode('ERROR - Billing Last Name length is too strong. It must be a maximum of 255 characters.'));
            }

            //Server side validation to check if the billing last name doesn't contain any numbers in it
            if (!(preg_match('/^\D+$/', $billingLastName))) {
                header('HTTP/1.1 400 Bad Request');
                header('Content-Type: application/json; charset=UTF-8');
                die(json_encode('ERROR - Incorrect Format for Billing Last Name. First Name should not contain numbers:' . $billingLastName));
            }

            // Server side validation to check if the billing address line 1 is more than a character
            if (strlen($billingAddressLine1) < 2) {
                header('HTTP/1.1 400 Bad Request');
                header('Content-Type: application/json; charset=UTF-8');
                die(json_encode('ERROR - Billing Address Line 1 length is too weak. It must be a minimum of 2 characters.'));
            }

            // Server side validation to check if the billing address line 1 is less than 256 characters
            if (strlen($billingAddressLine1) > 255) {
                header('HTTP/1.1 400 Bad Request');
                header('Content-Type: application/json; charset=UTF-8');
                die(json_encode('ERROR - Billing Address Line 1 length is too strong. It must be a maximum of 255 characters.'));
            }

            // Server side validation to check when the billing address Line 2 is not empty, if it is more than a character
            if (!empty($billingAddressLine2) && strlen($billingAddressLine2) < 2) {
                header('HTTP/1.1 400 Bad Request');
                header('Content-Type: application/json; charset=UTF-8');
                die(json_encode('ERROR - Billing Address Line 2 length is too weak. It must be a minimum of 2 characters if not blank.'));
            }

            // Server side validation to check when the billing address Line 2 is not empty, if it is less than 256 characters
            if (!empty($addressLine2) && strlen($addressLine2) > 255) {
                header('HTTP/1.1 400 Bad Request');
                header('Content-Type: application/json; charset=UTF-8');
                die(json_encode('ERROR - Address Line 2 length is too strong. It must be a maximum of 255 characters.'));
            }

            // Server side validation to check if the billing Town/City is more than a character
            if (strlen($billingTownCity) < 2) {
                header('HTTP/1.1 400 Bad Request');
                header('Content-Type: application/json; charset=UTF-8');
                die(json_encode('ERROR - Billing Town/City length is too weak. It must be a minimum of 2 characters.'));
            }
            // Server side validation to check if the billing Town/City is less than 256 characters
            if (strlen($billingTownCity) > 58) {
                header('HTTP/1.1 400 Bad Request');
                header('Content-Type: application/json; charset=UTF-8');
                die(json_encode('ERROR - Billing Town/City length is too strong. It must be a maximum of 58 characters.'));
            }

            // Server side validation to check if the billing postCode is more than 4 characters
            if (strlen($billingPostCode) < 5) {
                header('HTTP/1.1 400 Bad Request');
                header('Content-Type: application/json; charset=UTF-8');
                die(json_encode('ERROR - Billing Postcode length is too weak. It must be a minimum of 2 characters.'));
            }

            // Server side validation to check if the billing postCode is less than 9 characters e.g SW1A 2AA (8 characters)
            if (strlen($billingPostCode) > 8) {
                header('HTTP/1.1 400 Bad Request');
                header('Content-Type: application/json; charset=UTF-8');
                die(json_encode('ERROR - Billing Postcode length is too strong. It must be a maximum of 255 characters.'));
            }

            // Server side validation to check if the billing county is more than a character
            if (strlen($billingCounty) < 2) {
                header('HTTP/1.1 400 Bad Request');
                header('Content-Type: application/json; charset=UTF-8');
                die(json_encode('ERROR - Billing County length is too weak. It must be a minimum of 2 characters.'));
            }

            // Server side validation to check if the billing county is less than 256 characters
            if (strlen($billingCounty) > 255) {
                header('HTTP/1.1 400 Bad Request');
                header('Content-Type: application/json; charset=UTF-8');
                die(json_encode('ERROR - Billing County length is too strong. It must be a maximum of 255 characters.'));
            }

            // Has passed all validation

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
            //Prepared Statement - query to see if the address is already in the database
            $stmt = $conn->prepare("SELECT addressID from address where title = ? AND firstName = ? AND lastName = ? AND addressLine1 = ? AND addressLine2 = ? AND townCity = ? AND county = ? AND postcode = ?");
            //Binding the address information we got from the front end and passing it through to our prepared statement
            $stmt->bind_param("ssssssss", $billingTitle, $billingFirstName, $billingLastName, $billingAddressLine1, $billingAddressLine2, $billingTownCity, $billingCounty, $billingPostCode);

            //Execute the query
            if (!$stmt->execute()) {
                //Error trying to execute seeing if an address is already in the DB, send back a 500 Internal Server Error HTTP response.
                header('HTTP/1.1 500 Internal Server Error');
                header('Content-Type: application/json; charset=UTF-8');
                die(json_encode('ERROR - Could not process your order. Cannot execute query to find Address in the DB for billing'));
            }
            //Get results from the database and put them in the AddressFromDB variable
            $res = $stmt->get_result();
            $addressFromDB = mysqli_fetch_all($res, MYSQLI_ASSOC);

            // If found an address in the DB, that the user has entered...
            if (mysqli_num_rows($res) > 0) {
                //..then instead of adding a new row to the database, use that Address ID
                // Set the Billing Address ID to the one from the DB.
                $billingAddressID = $addressFromDB[0]['addressID'];
            } else {
                //If not already in DB, then send the details gathered to the DB by using a prepared statement
                $stmt = $conn->prepare("INSERT INTO address (title, firstName, lastName, addressLine1, addressLine2, townCity, county, postcode)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                //Binding the billing title, billing first name, billing last name, billing address line 1, billing address line 2 (can be blank/null), billing town or city, billing county and billing postcode with the prepared statement
                $stmt->bind_param("ssssssss", $billingTitle, $billingFirstName, $billingLastName, $billingAddressLine1, $billingAddressLine2, $billingTownCity, $billingCounty, $billingPostCode);
                //Execute the query
                if (!$stmt->execute()) {
                    header('HTTP/1.1 500 Internal Server Error');
                    header('Content-Type: application/json; charset=UTF-8');
                    die(json_encode('ERROR - Could not process your order. Cannot execute query to insert address in the DB - Billing'));
                }
                // Successfully added to the Database and therefore edit the billing address ID to change it to the address ID found in the DB
                $billingAddressID = mysqli_insert_id($conn);
            }
        } else {
            //Not all required fields are set, don't send request to DB.
            header('HTTP/1.1 400 Bad Request');
            header('Content-Type: application/json; charset=UTF-8');
            die(json_encode('ERROR - Not all required fields are filled out for the Billing address, please fill them out.'));
            return false;
        }
    } else {
        //If not billing address method 1 or 2 (should never get to this state)
        header('HTTP/1.1 400 Bad Request');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - Invalid data for the Billing Address. Please contact support if problem persists'));
        return false;
    }
} else {
    //The Billing Method is not set/is not numeric (should never get to this state) 
    header('HTTP/1.1 Internal Server Error');
    header('Content-Type: application/json; charset=UTF-8');
    die(json_encode('ERROR - Problem with billing data that was sent to us. Please contact support if problem persists'));
    return false;
}

//Delivery Part - check to see if delivery method is defined and is numerical
if (isset($_POST['deliveryMethod']) && is_numeric($_POST['deliveryMethod'])) {
    $deliveryMethod = $_POST['deliveryMethod'];
    // If delivery address is the same as billing address (and is not main address)
    if ($deliveryMethod == 1) {
        // Edit the variable of delivery address ID and equal it to the billing address
        $deliveryAddressID = $billingAddressID;
        // If delivery address is the main address
    } else if ($deliveryMethod == 2) {
        // If the main address hasn't been used for the billing address, then go and set the main 
        // address ID variable by going to getMainAddressID() method
        if (!isset($mainAddressID)) {
            getMainAddressID();
        }
        // Then set the variable of deliveryAddressID to the ID in mainAddressID
        $deliveryAddressID = $mainAddressID;
        // They Selected and entered the new address for billing
    } else if ($deliveryMethod == 3) {
        //Check if already in DB (if it is a main address or just a normal address - and if so, use that ID)
        if (isset($_POST['deliveryFirstName']) && isset($_POST['deliveryLastName']) && isset($_POST['deliveryAddressLine1']) && isset($_POST['deliveryTownCity']) && isset($_POST['deliveryCounty']) && isset($_POST['deliveryPostCode'])) {

            // Get the delivery values from the post request and trim whitespaces from them
            $deliveryTitle = $_POST['deliveryTitle'];
            $deliveryFirstName = trim($_POST['deliveryFirstName']);
            $deliveryLastName = trim($_POST['deliveryLastName']);
            $deliveryAddressLine1 = trim($_POST['deliveryAddressLine1']);
            $deliveryAddressLine2 = trim($_POST['deliveryAddressLine2']);
            $deliveryTownCity = trim($_POST['deliveryTownCity']);
            //Remove any space (for instance 'L1 34Q') from Postcode
            $deliveryPostCode = str_replace(' ', '', $_POST['deliveryPostCode']);
            $deliveryCounty = trim($_POST['deliveryCounty']);

            //Server Side checking to see if any (but address line 2) fields are empty and if so, don't execute further
            if (empty($deliveryTitle)) {
                header('HTTP/1.1 400 Bad Request');
                header('Content-Type: application/json; charset=UTF-8');
                die(json_encode('ERROR - Delivery Title is empty. Please fill it out.'));
            }
            if (empty($deliveryFirstName)) {
                header('HTTP/1.1 400 Bad Request');
                header('Content-Type: application/json; charset=UTF-8');
                die(json_encode('ERROR - Delivery First Name is empty. Please fill it out.'));
            }
            if (empty($deliveryLastName)) {
                header('HTTP/1.1 400 Bad Request');
                header('Content-Type: application/json; charset=UTF-8');
                die(json_encode('ERROR - Delivery Last Name is empty. Please fill it out.'));
            }
            if (empty($deliveryAddressLine1)) {
                header('HTTP/1.1 400 Bad Request');
                header('Content-Type: application/json; charset=UTF-8');
                die(json_encode('ERROR - Delivery Address Line 1 is empty. Please fill it out.'));
            }
            if (empty($deliveryTownCity)) {
                header('HTTP/1.1 400 Bad Request');
                header('Content-Type: application/json; charset=UTF-8');
                die(json_encode('ERROR - Delivery Town/City is empty. Please fill it out.'));
            }
            if (empty($deliveryCounty)) {
                header('HTTP/1.1 400 Bad Request');
                header('Content-Type: application/json; charset=UTF-8');
                die(json_encode('ERROR - Delivery County is empty. Please fill it out.'));
            }
            if (empty($deliveryPostCode)) {
                header('HTTP/1.1 400 Bad Request');
                header('Content-Type: application/json; charset=UTF-8');
                die(json_encode('ERROR - Delivery Postcode is empty. Please fill it out.'));
            }

            //Server side validation to check if the delivery title is either Mr, Master, Miss, Mrs, Ms or Dr.
            if (
                $deliveryTitle !== "Mr" && $deliveryTitle !== "Master" &&
                $deliveryTitle !== "Miss" && $deliveryTitle !== "Mrs" && $deliveryTitle !== "Ms" &&
                $deliveryTitle !== "Dr"
            ) {
                header('HTTP/1.1 400 Bad Request');
                header('Content-Type: application/json; charset=UTF-8');
                die(json_encode('ERROR - Delivery Title from list is not selected - Please select from the list'));
            }

            //Server side validation to check if the delivery first name is more than a character
            if (strlen($deliveryFirstName) < 2) {
                header('HTTP/1.1 400 Bad Request');
                header('Content-Type: application/json; charset=UTF-8');
                die(json_encode('ERROR - Delivery First Name length is too weak. It must be a minimum of 2 characters.'));
            }
            //Server side validation to check if the delivery first name is less than 256 characters
            if (strlen($deliveryFirstName) > 255) {
                header('HTTP/1.1 400 Bad Request');
                header('Content-Type: application/json; charset=UTF-8');
                die(json_encode('ERROR - Delivery First Name length is too strong. It must be a maximum of 255 characters.'));
            }
            //Server side validation to check if the delivery first name doesn't contain any numbers in it
            if (!(preg_match('/^\D+$/', $deliveryFirstName))) {
                header('HTTP/1.1 400 Bad Request');
                header('Content-Type: application/json; charset=UTF-8');
                die(json_encode('ERROR - Incorrect Format for Delivery First Name. First Name should not contain numbers'));
            }

            //Server side validation to check if the delivery last name is more than a character
            if (strlen($deliveryLastName) < 2) {
                header('HTTP/1.1 400 Bad Request');
                header('Content-Type: application/json; charset=UTF-8');
                die(json_encode('ERROR - Delivery Last Name length is too weak. It must be a minimum of 2 characters.'));
            }

            //Server side validation to check if the delivery last name is less than 256 characters
            if (strlen($deliveryLastName) > 255) {
                header('HTTP/1.1 400 Bad Request');
                header('Content-Type: application/json; charset=UTF-8');
                die(json_encode('ERROR - Delivery Last Name length is too strong. It must be a maximum of 255 characters.'));
            }

            //Server side validation to check if the delivery last name doesn't contain any numbers in it
            if (!(preg_match('/^\D+$/', $deliveryLastName))) {
                header('HTTP/1.1 400 Bad Request');
                header('Content-Type: application/json; charset=UTF-8');
                die(json_encode('ERROR - Incorrect Format for Delivery Last Name. Last Name should not contain numbers'));
            }

            // Server side validation to check if the delivery address line 1 is more than a character
            if (strlen($deliveryAddressLine1) < 2) {
                header('HTTP/1.1 400 Bad Request');
                header('Content-Type: application/json; charset=UTF-8');
                die(json_encode('ERROR - Delivery Address Line 1 length is too weak. It must be a minimum of 2 characters.'));
            }

            // Server side validation to check if the delivery address line 1 is less than 256 characters
            if (strlen($deliveryAddressLine1) > 255) {
                header('HTTP/1.1 400 Bad Request');
                header('Content-Type: application/json; charset=UTF-8');
                die(json_encode('ERROR - Delivery Address Line 1 length is too strong. It must be a maximum of 255 characters.'));
            }

            // Server side validation to check when the delivery address Line 2 is not empty, if it is less than 256 characters
            if (!empty($deliveryAddressLine2) && strlen($deliveryAddressLine2) < 2) {
                header('HTTP/1.1 400 Bad Request');
                header('Content-Type: application/json; charset=UTF-8');
                die(json_encode('ERROR - Delivery Address Line 2 length is too weak. It must be a minimum of 2 characters if not blank.'));
            }

            // Server side validation to check when the delivery address Line 2 is not empty, if it is less than 256 characters
            if (!empty($deliveryAddressLine2) && strlen($deliveryAddressLine2) > 255) {
                header('HTTP/1.1 400 Bad Request');
                header('Content-Type: application/json; charset=UTF-8');
                die(json_encode('ERROR - Delivery Address Line 2 length is too strong. It must be a maximum of 255 characters.'));
            }

            // Server side validation to check if the delivery Town/City is more than a character
            if (strlen($deliveryTownCity) < 2) {
                header('HTTP/1.1 400 Bad Request');
                header('Content-Type: application/json; charset=UTF-8');
                die(json_encode('ERROR - Delivery Town/City length is too weak. It must be a minimum of 2 characters.'));
            }
            // Server side validation to check if the delivery Town/City is less than 256 characters
            if (strlen($deliveryTownCity) > 58) {
                header('HTTP/1.1 400 Bad Request');
                header('Content-Type: application/json; charset=UTF-8');
                die(json_encode('ERROR - Delivery Town/City length is too strong. It must be a maximum of 58 characters.'));
            }
            // Server side validation to check if the delivery county is more than a character
            if (strlen($deliveryCounty) < 2) {
                header('HTTP/1.1 400 Bad Request');
                header('Content-Type: application/json; charset=UTF-8');
                die(json_encode('ERROR - Delivery County length is too weak. It must be a minimum of 2 characters.'));
            }

            // Server side validation to check if the delivery county is less than 256 characters
            if (strlen($deliveryCounty) > 255) {
                header('HTTP/1.1 400 Bad Request');
                header('Content-Type: application/json; charset=UTF-8');
                die(json_encode('ERROR - Delivery County length is too strong. It must be a maximum of 255 characters.'));
            }
            // Server side validation to check if the delivery postCode is more than 4 characters
            if (strlen($deliveryPostCode) < 2) {
                header('HTTP/1.1 400 Bad Request');
                header('Content-Type: application/json; charset=UTF-8');
                die(json_encode('ERROR - Delivery Postcode length is too weak. It must be a minimum of 2 characters.'));
            }
            // Server side validation to check if the delivery postCode is less than 9 characters e.g SW1A 2AA (8 characters)
            if (strlen($deliveryPostCode) > 255) {
                header('HTTP/1.1 400 Bad Request');
                header('Content-Type: application/json; charset=UTF-8');
                die(json_encode('ERROR - Delivery Postcode length is too strong. It must be a maximum of 255 characters.'));
            }

            // Has passed all validation

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
            //Prepared Statement - query to see if the address is already in the database
            $stmt = $conn->prepare("SELECT addressID from address where title = ? AND firstName = ? AND lastName = ? AND addressLine1 = ? AND addressLine2 = ? AND townCity = ? AND county = ? AND postcode = ?");
            //Binding the address information we got from the front end and passing it through to our prepared statement
            $stmt->bind_param("ssssssss", $deliveryTitle, $deliveryFirstName, $deliveryLastName, $deliveryAddressLine1, $deliveryAddressLine2, $deliveryTownCity, $deliveryCounty, $deliveryPostCode);

            //Execute the query
            if (!$stmt->execute()) {
                //Error trying to execute seeing if an address is already in the DB, send back a 500 Internal Server Error HTTP response.
                header('HTTP/1.1 500 Internal Server Error');
                header('Content-Type: application/json; charset=UTF-8');
                die(json_encode('ERROR - Could not process your order. Cannot execute query to find Address in the DB for delivery'));
            }
            //Get results from the database and put them in the AddressFromDB variable
            $res = $stmt->get_result();
            $addressFromDB = mysqli_fetch_all($res, MYSQLI_ASSOC);

            // If found an address in the DB, that the user has entered...
            if (mysqli_num_rows($res) > 0) {
                //..then instead of adding a new row to the database, use that Address ID
                // Set the delivery Address ID to the one from the DB.
                $deliveryAddressID = $addressFromDB[0]['addressID'];
            } else {
                //If not already in DB, then send the details gathered to the DB by using a prepared statement
                $stmt = $conn->prepare("INSERT INTO address (title, firstName, lastName, addressLine1, addressLine2, townCity, county, postcode)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

                //Binding the delivery title, delivery first name, delivery last name, delivery address line 1, delivery address line 2 (can be blank/null), delivery town or city, delivery county and delivery postcode with the prepared statement
                $stmt->bind_param(
                    "ssssssss",
                    $deliveryTitle,
                    $deliveryFirstName,
                    $deliveryLastName,
                    $deliveryAddressLine1,
                    $deliveryAddressLine2,
                    $deliveryTownCity,
                    $deliveryCounty,
                    $deliveryPostCode
                );

                //Execute the query
                if (!$stmt->execute()) {
                    header('HTTP/1.1 500 Internal Server Error');
                    header('Content-Type: application/json; charset=UTF-8');
                    die(json_encode('ERROR - Could not process your order. Cannot execute query to insert address in the DB - Delivery'));
                }
                // Successfully added to the Database and therefore edit the delivery address ID to change it to the address ID found in the DB
                $deliveryAddressID = mysqli_insert_id($conn);
            }
        } else {
            //Not all required fields are set, don't send request to DB.
            header('HTTP/1.1 400 Bad Request');
            header('Content-Type: application/json; charset=UTF-8');
            die(json_encode('ERROR - Not all required fields are filled out for the Delivery address, please fill them out.'));
        }
    } else {
        //If not delivery address method 1, 2 or 3 (should never get to this state)
        header('HTTP/1.1 400 Bad Request');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - Invalid data for the Delivery Address. Please contact support if problem persists.'));
        return false;
    }
} else {
    //The Delivery Method is not set/is not numeric (should never get to this state) 
    header('HTTP/1.1 Internal Server Error');
    header('Content-Type: application/json; charset=UTF-8');
    die(json_encode('ERROR - Problem with delivery data that was sent to us. Please contact support if problem persists.'));
    return false;
}
//Done the addresses, now actually order the items

// Have arrays for each product information that we need to put into the DB 
// (discuss this in the report: but this should be done a better way - should use JSON in the future)
$orderProduct_ProductID = array();
$orderProduct_ProductPrice = array();
$orderProduct_ProductQuantity = array();
$product_Quantity = array();


// For every item in the basket (get the product ID (the Key) and the quantity of the product (the value))
foreach ($_SESSION['basket'] as $productID => $productQuantity) {
    // Validation to see if the Quantity in the basket is over 10 - (validation has been done before this point but just double check)
    if ($productQuantity > 10) {
        // send an error back to the front end if quantity of current product is more than 10
        header('HTTP/1.1 400 Bad Request');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - Cannot proceed your order due to the quantity of an item in your basket exceeds the customer limit of 10'));
        return false;
    }
    //Get the Database Details
    include 'DatabaseLoginDetails.php';
    //Make a connect to the database
    $conn = mysqli_connect($host, $user, $pass, $database);
    // Check connection
    if (!$conn) {
        header('HTTP/1.1 500 Internal Server Error');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - Connection to the database has not been established'));
    } else  if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    //Prepared Statement - query to get the product price and the total inventory of that product
    $stmt = $conn->prepare("SELECT productPrice, productTotalQuantity from product where productID = ?");
    //Binding the Product ID of the current product and passing it through to our prepared statement
    $stmt->bind_param("s", $productID);

    //Execute the query
    if (!$stmt->execute()) {
        //Error trying to execute the query of getting the price and total inventory
        header('HTTP/1.1 500 Internal Server Error');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - Could not process your order. Could not get product information to place the order'));
    }

    //Get results from the database and put them in the productDetails variable
    $res = $stmt->get_result();
    $productDetails = mysqli_fetch_all($res, MYSQLI_ASSOC);

    // Put the product price from the DB in a variable
    $productPrice = $productDetails[0]['productPrice'];
    // Put the total inventory we have in stock from the DB in a variable.
    $quantityOfProductDB = $productDetails[0]['productTotalQuantity'];

    // If the quantity of what they ordered is more of what is in the database then error 
    // (validation has been done before this point (Product Page) but just double check)
    if ($productQuantity > $quantityOfProductDB) {
        header('HTTP/1.1 400 Bad Request');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - Cannot proceed your order due to the quantity of an item in your basket exceeds what we have in stock'));
        return false;
    }

    // If not, add the product to individual arrays for the product information
    array_push($orderProduct_ProductID, $productID);
    array_push($orderProduct_ProductPrice, $productPrice);
    array_push($orderProduct_ProductQuantity, $productQuantity);
    array_push($product_Quantity, $quantityOfProductDB);
}

// For Each order, calulate the total of this order
$totalOfOrder = 0.00;
// echo count($orderProduct_ProductPrice);
for ($x = 0; $x < count($orderProduct_ProductPrice); $x++) {
    // The total product price is the quantity of the product (get from the productQuantity array) by the price (get from the productPrice array)
    $quantityPrice = $orderProduct_ProductQuantity[$x] * $orderProduct_ProductPrice[$x];
    // Add to the total
    $totalOfOrder = $totalOfOrder + $quantityPrice;
}

// Get the date and time of now to add that to the order
$order_dateTime = date("Y-m-d H:i:s");


// Insert into purchase_order
//Prepared Statement - query to add the order to the database
$stmt = $conn->prepare("INSERT INTO purchase_order (userID, totalPrice, DateAndTime, billingAddressID, deliveryAddressID)
    VALUES (?, ?, ?, ?, ?)");
//Binding the user ID, the total cost of the order, the date and time of the order and the billing/delivery ID (that was just produced) information and passing it through to the prepared statement
$stmt->bind_param("idsii", $_SESSION['userID'], $totalOfOrder, $order_dateTime, $billingAddressID, $deliveryAddressID);

//Execute the query
if (!$stmt->execute()) {
    //Error trying to execute inserting the order into the database, send back a 500 Internal Server Error HTTP response.
    header('HTTP/1.1 500 Internal Server Error');
    header('Content-Type: application/json; charset=UTF-8');
    die(json_encode('ERROR - Could not process your order. Cannot execute the query to insert the order into the DB'));
}

// Successfully added to the Database and therefore the Order ID variable (used for Order_Product)
$orderID = mysqli_insert_id($conn);


//After getting orderID, for each product in an order, put it in the order_product table.
for ($x = 0; $x < count($orderProduct_ProductPrice); $x++) {
    // Getting the current Product information needed for the insert into the order Product
    $productID = $orderProduct_ProductID[$x];
    $productBought = $orderProduct_ProductQuantity[$x];

    // Prepare a statement that will insert into Order Product
    $stmt = $conn->prepare("INSERT INTO order_product (orderID, productID, productPriceAtTime, productQuantity)
    VALUES (?, ?, ?, ?)");

    //Binding the order ID, product ID and current Product Price, and the quanitity that was bought into the statement
    $stmt->bind_param(
        "iidi",
        $orderID,
        $productID,
        $orderProduct_ProductPrice[$x],
        $productBought
    );

    //Execute the query
    if (!$stmt->execute()) {
        // If it doesn't execute, then show an error
        header('HTTP/1.1 500 Internal Server Error');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - Could not process your order. Error occured trying to tie the products into the order'));
    }

    // Get the Total Inventory of the current product and calculate the new Inventory value (after the order has been placed)
    $productInventoryFromDB = $product_Quantity[$x];
    $productInventoryLeft = $productInventoryFromDB - $productBought;


    //    Prepared statement to update Products table to reduce what is in the inventory
    $stmt = $conn->prepare("UPDATE product SET productTotalQuantity = ? WHERE productID = ?");

    //Binding the Product ID and the updated inventory
    $stmt->bind_param("ii", $productInventoryLeft, $productID);

    // Execute the query
    if (!$stmt->execute()) {
        // If it doesn't execute, then show an error
        header('HTTP/1.1 500 Internal Server Error');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - Could not process your order. Could not update the inventory after order.'));
    }
}

//If got to this stage, everything went well, therefore set the Order ID variable, stored in the session, to the orderID variable 
$_SESSION['orderID'] = $orderID;
// and send a 200 OK HTTP Response to the frontend
header('HTTP/1.1 200 OK');
header('Content-Type: application/json; charset=UTF-8');
die(json_encode('Order Placed successfully'));
?>