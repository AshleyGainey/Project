<?php
// If the session hasn't started. Start it (can then use session variables)
if (!isset($_SESSION)) {
    @ob_start();
    session_start();
}
// Trim the whitespace from the search on the server side
$searchTerm = trim($_GET['search']);

if (!(strlen($searchTerm) > 0)) {
    //Couldn't execute query so stop there
    header('HTTP/1.1 400 Bad Request');
    header('Content-Type: application/json; charset=UTF-8');
    die(json_encode('ERROR - Search Term cannot be blank.'));
}

// Variables for the dynamic query.
$firstPartOfBind = "";
$SecondPartOfBind = [];

// Make the first part of the query, selecting the product information. 
// And set up the second part of the query - getting products that have the words that the search term has in it
$query =
    'SELECT p.productID, p.productTitle, 
p.productDescription, p.productPrice, pi.productImageFilename, pi.productImageAltText
        FROM product 
AS p RIGHT JOIN product_image pi
 ON pi.productID = p.productID WHERE pi.displayOrder = 1 AND (p.productTitle LIKE ';

// Split all the words in the search Term and put them in an array
$searchExploded = explode(" ", $searchTerm);

// For the amount of words in the search Term, do a loop...
for ($i = 0; $i < sizeof($searchExploded); $i++) {
    // ...adding "LIKE %'SEARCH_TERM'%" to the query.
    array_push($SecondPartOfBind, "%" . $searchExploded[$i] . "%");

    // And adding an s (for string) to the bind query to represent what type of data we are putting into the query
    $firstPartOfBind = $firstPartOfBind . "s";

    //Add a question mark to the query to show that we have added a paramater to the query
    $query = $query . "?";

    // If it is not the last search term
    if ($i !== sizeof($searchExploded) - 1) {
        // Then add to the query that there will be another search term so do "AND p.productTitle LIKE"
        $query = $query . " AND p.productTitle LIKE ";
    } else {
        // If it is the last search term, then end the query by closing the bracket
        $query = $query . ")";
    }
}

//Get the Database login details
include 'DatabaseLoginDetails.php';

//Connect to the database
$conn = mysqli_connect($host, $user, $pass, $database);

// Check connection and stop if there is an error
if (!$conn) {
    header('HTTP/1.1 500 Internal Server Error');
    header('Content-Type: application/json; charset=UTF-8');
    die(json_encode('ERROR - Connection to the database has not been established'));
} else  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
// after making the query, prepare it
$stmt = $conn->prepare($query);
// Put what type of data in the first parameter and what the data is in the second parameter
$stmt->bind_param($firstPartOfBind, ...$SecondPartOfBind);

// Execute the query
if (!$stmt->execute()) {
    //Couldn't execute query so stop there
    header('HTTP/1.1 500 Internal Server Error');
    header('Content-Type: application/json; charset=UTF-8');
    die(json_encode('ERROR - Could not get results from the search'));
}

// Get the results back from the database
$result = $stmt->get_result();
$products = $result->fetch_all(MYSQLI_ASSOC);
// Store how many products have come back from the query
$rows = sizeOf($products);

//Free  memory and close the connection
mysqli_free_result($result);
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <!-- Put a viewport on this page -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Make a dynamic title of the page - It will hold the search term and then what website it is -->
    <title>Results for "<?php echo $searchTerm; ?>" - Gadget Gainey Store</title>
    <!-- Keywords of the site for search engine optimisation -->
    <meta name="keywords" content="Gadget Gainey, Gadget, Ecommerce, Online, Shop, Kids Toys, Toys, Technology, Gainey, Ashley Gainey">
    <!-- Author of the site -->
    <meta name="author" content="Ashley Gainey">
    <!-- Description of the page -->
    <meta name="description" content="Find products for the result search of '<?php echo $searchTerm; ?>'">
    <!-- Don't let any search engine index this page -->
    <meta name="robots" content="noindex" />
    <!-- Link to the shared classes and IDs style sheet -->
    <link rel="stylesheet" type="text/css" href="sharedStyles.css">
</head>

<body>
    <!-- Add the header at the top before any other material -->
    <?php include "./header.php" ?>
    <div id="bodyOfPage">
        <div id="resultsFor">
            <!-- Show the Trimmed Search Term -->
            <h1>
                <?php
                echo $searchTerm;
                ?>
            </h1>
            <h4>
                <!-- Show how many products where found -->
                <?php
                // If no products where found, then show No Products Found
                if ($rows == 0) {
                    echo "NO PRODUCTS FOUND";
                    // If one product was found, then take the user to that product
                } else if ($rows == 1) {
                    $item = $products[0]["productID"];
                    header('Location: productPage.php?productID=' . $item);
                    // If more than one products were found, output the plural of product (products)
                } else {
                    echo "(" . $rows . " products found)";
                } ?>
            </h4>
        </div>
        <!-- Horizontal line after the Search Term and how many products were found to separate  the content -->
        <hr>
        <!-- Div to hold all the results -->
        <div id="AllResults">
            <div class="row">
                <!-- For every product -->
                <?php foreach ($products as $product) { ?>
                    <div class="card">
                        <!-- Wrap the card around an anchor tag which links to the current product we are looping through -->
                        <?php echo "<a href='productPage.php?productID=" . $product['productID'] . "'>" ?>
                        <!-- Holds all the details of each product -->
                        <div class="cardContent">
                            <!-- Holds the Image -->
                            <div class="ImageHolder">

                                <?php
                                // Individual Product Image, ID will be the ProductID of the current product result
                                // It will get the image from the product images folder and then the current Product ID, concat with / and the filename that is stored in the database (now in the products result variable)
                                //and the alternative text of the image is in the database (now in the products result variable)
                                $productImagePath = "images/products/" . $product["productID"] . "/" . $product["productImageFilename"];
                                echo "<img src= '" . $productImagePath . "'alt='" . $product["productImageAltText"] . "'>"
                                ?> </div>
                            <!-- Container to hold all of other product information (title, price) -->
                            <div class="AllOtherInfo">
                                <div class="IndividualInfo TitleDiv">
                                    <h3 class="productTitle"> <?php echo  $product['productTitle'] ?></h6>
                                        <h4 class="productPrice"> <?php echo  "£" . $product['productPrice'] ?></h6>
                                </div>
                                <!-- Container to hold the See More Section -->
                                <div class="SeeMoreDiv">
                                    <!-- Title for See More -->
                                    <h4 class="SeeMoreTitle">See More</h6>
                                        <!-- Right Arrow to show that the user will navigate to the product page -->
                                        <img class='seeMoreImage' src='images/Home/Right Arrow.svg' alt='See More of this product - Right Arrow' />
                                </div>
                            </div>
                        </div>
                        </a>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
    <!-- Add the footer at the bottom after any other material -->
    <?php include "./footer.php" ?>
</body>

</html>
<style>
    .SeeMoreDiv {
        display: inline;
    }

    .seeMoreImage {
        width: 30px;
        display: block;
        margin-left: auto;
        margin-right: auto;
        display: inline;
    }

    .IndividualInfo {
        margin-bottom: 10px;
    }

    .IndividualInfo h3 {
        font-size: 20px;
    }

    .cardContent {
        padding-top: 10px;
        padding-left: 10px;
        padding-right: 10px;
    }

    .ImageHolder {
        float: left;
        margin-right: 15px;

        display: inline-block;

        width: 100%;
        height: 20vh;
    }

    .row {
        text-align: center;
    }


    .ImageHolder img {
        object-fit: cover;

        width: 100%;
        height: 20vh;
    }

    .card {
        border-radius: 12.5px;
        background-color: #FFFFFF;
        color: #000000;
        word-break: break-all;
        display: inline-block;
        margin: 5px;
        padding: 5px;

        width: 300px;
        text-align: left;
        min-height: 200px;

        word-break: keep-all;
    }

    .SeeMoreTitle {
        display: inline;
    }

    hr {
        width: 40%;
        border-top: 5px solid #1a1862;
        border-radius: 50px;
        opacity: 0.5;
        margin-bottom: 20px;

        margin-left: auto;
        margin-right: auto;
    }

    a {
        text-decoration: none;
        color: black;
    }

    #resultsFor {
        text-align: center;
    }
</style>
<script>
    // Get the search term and put it in the Search bar
    var search = "<?php echo $searchTerm ?>";
    if (search) {
        document.getElementById('searchInput').value = search;
    }
</script>