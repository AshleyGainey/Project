<?php
// If the session hasn't started. Start it (can then use session variables)
if (!isset($_SESSION)) {
    @ob_start();
    session_start();
}

//Check to see if product has been added to the basket from the Add to Basket button on the product page
if (isset($_POST['product_id'], $_POST['quantity']) && is_numeric($_POST['product_id']) &&  is_numeric($_POST['quantity'])) {
    //Make sure the quantity and product ID are integers and set variables to use later on.
    $basket_product_ID = (int)$_POST['product_id'];
    $basket_quantity = (int)$_POST['quantity'];

    include 'DatabaseLoginDetails.php';

    $conn = new mysqli($host, $user, $pass, $database);
    // Check connection
    if (!$conn) {
        header('HTTP/1.1 500 Internal Server Error');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - Connection to the database has not been established'));
    } else  if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }


    //Verify that the product is in the database (users then can't manipulate the system and add products that aren't there) 
    // and put the result in an array
    $stmt = $conn->prepare("SELECT * From product where productID = ?");
    $stmt->bind_param("i", $basket_product_ID);

    if (!$stmt->execute()) {
        //Couldn't execute query so stop there
        header('HTTP/1.1 500 Internal Server Error');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR - Could not add the item to your basket.'));
    }

    // Get the results back from the database
    $basket_product = $stmt->get_result();

    //If there is a product in the database (the product exists)
    if ($basket_product && $basket_quantity > 0) {
        if (isset($_SESSION['basket']) && is_array($_SESSION['basket'])) {
            //If any product at all is already exists in the basket
            if (array_key_exists($basket_product_ID, $_SESSION['basket'])) {
                //Update the quantity of the product since the product is already in the basket.
                $_SESSION['basket'][$basket_product_ID] += $basket_quantity;

                //Report back to say that the item has been added
                header('HTTP/1.1 200 OK');
                header('Content-Type: application/json; charset=UTF-8');
                die(json_encode('Exists'));;
            } else {
                //Add the product to the basket, due to it not being in there already
                $_SESSION['basket'][$basket_product_ID] = $basket_quantity;

                //Report back to say that the item has been added
                header('HTTP/1.1 200 OK');
                header('Content-Type: application/json; charset=UTF-8');
                die(json_encode('NewItem'));;
            }
        } else {
            //Add the first product to the basket - there were no products in the basket previously 
            // (add the product ID as the key and the quantity as the value)
            $_SESSION['basket'] = array($basket_product_ID => $basket_quantity);
            //Report back to say that the item has been added
            header('HTTP/1.1 200 OK');
            header('Content-Type: application/json; charset=UTF-8');
            die(json_encode('NewItem'));;
        }
    } else {
        //Product doesn't exist so report back
        header('HTTP/1.1 400 Bad Request');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR: Invalid request: Product does not exist'));
    }
}

if (isset($_POST['update'])) {
    //Update the basket by looking at the URL parameter of update (which is the product id) and the quantity of the requested product, check to see if it is in the basket and is numerical
    if (isset($_POST['quantity']) && isset($_SESSION['basket']) && is_numeric($_POST['update']) && is_numeric($_POST['quantity']) && isset($_SESSION['basket'][$_POST['update']])) {
        $_SESSION['basket'][$_POST['update']] = $_POST['quantity'];

        //Report back to say that it has been updated
        header('HTTP/1.1 200 OK');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('Updated'));;
    } else {
        //If the data was dirty (/bad), report back saying an error.
        header('HTTP/1.1 400 Bad Request');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode('ERROR: Invalid data while updating the product from the basket'));
    }
}

//Remove from basket by looking at the URL paramater of remove (which is the product id), check to see if it is in the basket and is numerical
if (isset($_POST['remove']) && is_numeric($_POST['remove']) && isset($_SESSION['basket']) && isset($_SESSION['basket'][$_POST['remove']])) {
    //Remove from basket
    unset($_SESSION['basket'][$_POST['remove']]);
    //Report back to say that it has been removed
    header('HTTP/1.1 200 OK');
    header('Content-Type: application/json; charset=UTF-8');
    die(json_encode('Removed'));;
} else {
    //If the data was dirty (/bad), report back saying an error.
    header('HTTP/1.1 400 Bad Request');
    header('Content-Type: application/json; charset=UTF-8');
    die(json_encode('ERROR: Invalid data while removing the product from the basket'));
}
