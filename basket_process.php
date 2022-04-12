<?php
if (!isset($_SESSION)) {
    @ob_start();
    session_start();
}

if (isset($_SESSION['basket'])) {
    echo "In Basket:";
    // print_r($_SESSION['basket']);
} else {
    echo "In Basket: Nothing in basket";
}


// echo "Hi1";
//Check to see if product has been added to the basket from the Add to Basket button on the product page
if (isset($_POST['product_id'], $_POST['quantity']) && is_numeric($_POST['product_id']) &&  is_numeric($_POST['quantity'])) {
    //Make sure the quantity and product ID are integers and set variables to use later on.
    $basket_product_ID = (int)$_POST['product_id'];
    $basket_quantity = (int)$_POST['quantity'];
    echo "Hello2";

    include 'DBlogin.php';

    $conn = new mysqli($host, $user, $pass, $database);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }


    //Verify that the product is in the database (users then can't manipulate the system and add products that aren't there) 
    // and put the result in an array
    // $stmt = $conn->prepare('Select * from product where productID = ?');
    // $stmt->execute([$_POST['product_id']]);





    $stmt = $conn->prepare("SELECT * From product where productID = ?");
    $stmt->bind_param("i", $basket_product_ID);
    $stmt->execute();
    $basket_product = $stmt->get_result();





    // $basket_product = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "Hiii3";
    //If there is a product in the database (the product exists)
    if ($basket_product && $basket_quantity > 0) {
        echo "Hi4";
        if (isset($_SESSION['basket']) && is_array($_SESSION['basket'])) {
            echo "Hi5";
            //If any product at all is already exists in the basket
            if (array_key_exists($basket_product_ID, $_SESSION['basket'])) {
                echo "Hi6";
                //Update the quantity of the product since the product is already in the basket.
                $_SESSION['basket'][$basket_product_ID] += $basket_quantity;
                $_SESSION['basketQuantity'] = count($_SESSION['basket']);
            } else {
                echo "Hi7";
                //Add the product to the basket, due to it not being in there already
                $_SESSION['basket'][$basket_product_ID] = $basket_quantity;
                $_SESSION['basketQuantity'] = count($_SESSION['basket']);
            }
        } else {
            echo "Hi8";
            //Add the first product to the basket - there were no products in the basket previously 
            // (add the product ID as the key and the quantity as the value)
            $_SESSION['basket'] = array($basket_product_ID => $basket_quantity);
            $_SESSION['basketQuantity'] = count($_SESSION['basket']);
        }
    }
}
echo "Hi9";
//Remove from basket by looking at the URL paramater of remove (which is the product id), check to see if it is in the basket and is numerical
if (isset($_POST['remove']) && is_numeric($_POST['remove']) && isset($_SESSION['basket']) && isset($_SESSION['basket'][$_POST['remove']])) {
    // echo "Hi10";
    // print_r($_SESSION['basket']);
    // print_r($_SESSION['basket'][$_POST['remove']]);
    unset($_SESSION['basket'][$_POST['remove']]);
    $_SESSION['basketQuantity'] = count($_SESSION['basket']);
}


echo "Hi11";
if (isset($_POST['update'], $_POST['quantity']) && isset($_SESSION['basket'])) {
    echo "------Hi12: ";

    print_r($_SESSION['basket'][$_POST['update']]);

    $_SESSION['basket'][$_POST['update']] = $_POST['quantity'];

    echo "------Hi13: ";
    print_r($_SESSION['basket'][$_POST['update']]);
    $_SESSION['basketQuantity'] = count($_SESSION['basket']);
}


// if (isset($_POST['placeorder']) && isset($_SESSION['basket']) && !empty($_SESSION['basket'])) {
//     header('Location: PlaceOrder.php');
// }



// $productsInBasket = isset($_SESSION['basket']) ? $_SESSION['basket'] : array();
// $prods = array();
// $subtotal = 0.00;

// if ($productsInBasket) {
//     $arraytoQs = implode(',', array_fill(0, count($productsInBasket), '?'));
//     $stmt = $pdo->prepare('SELECT * from products WHERE product_id IN (' . $arraytoQs . ')');
//     $stmt->execute(array_keys($productsInBasket));
//     $productsBackFromDB = $stmt->fetchAll(PDO::FETCH_ASSOC);
//     foreach ($productsBackFromDB as $indProduct) {
//         $subtotal += (float)$indProduct['price'] * (int)$productsInBasket[$indProduct['id']];
//     }
// }
