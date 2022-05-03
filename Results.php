            <?php
                                include 'DatabaseLoginDetails.php';

            $conn = mysqli_connect($host, $user, $pass, $database);

            if (trim($_GET['search'])) {
            }


            if (!$conn) {
                echo 'Connection error: ' . mysqli_connect_error();
            }
            $firstPartOfBind = "";
            $SecondPartOfBind = [];
            //Prepare statement cannot work with dynamic statements - Therefore, we need a different way to prevent SQL Injection
            $query =
                'SELECT p.productID, p.productTitle, 
p.productDescription, p.productPrice, pi.productImageFilename, pi.productImageAltText
        FROM product 
AS p RIGHT JOIN product_image pi
 ON pi.productID = p.productID WHERE pi.displayOrder = 1';

            $query = $query . " AND (p.productTitle LIKE ";
            // $query = $_GET['search'];
            $search_exploded = explode(" ", $_GET['search']);

            for ($i = 0; $i < sizeof($search_exploded); $i++) {
                $firstPartOfBind = $firstPartOfBind . "s";

                $item = "%" . $search_exploded[$i] . "%";

                array_push($SecondPartOfBind, $item);

                $query = $query . "?";

                if ($i !== sizeof($search_exploded) - 1) {
                    $query = $query . " AND p.productTitle LIKE ";
                } else {
                    $query = $query . ")";
                }
            }

            // echo $query;
            $stmt = $conn->prepare($query);
            $stmt->bind_param($firstPartOfBind, ...$SecondPartOfBind);

            $stmt->execute();
            $result = $stmt->get_result();
            $products = $result->fetch_all(MYSQLI_ASSOC);
            // $rows = mysqli_num_rows($result);
            $rows = sizeOf($products);

            //Free  memory and close the connection
            mysqli_free_result($result);
            mysqli_close($conn);





            ?>

            <!DOCTYPE html>
            <html lang="en">

            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1">


                <title>Results for "<?php echo $_GET['search']; ?>" - Gadget Gainey Store</title>
                <link rel="stylesheet" type="text/css" href="sharedStyles.css">
                <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
            </head>

            <body>
                <?php include "./header.php" ?>

                <div id="bodyOfPage">
                    <div id="resultsFor">
                        <h1>
                            <?php
                            echo $_GET['search'];
                            ?>
                        </h1>
                        <h4><?php
                            if ($rows == 0) {
                                echo "NO PRODUCTS FOUND";
                            } else if ($rows == 1) {
                                echo "(" . $rows . " product found)";
                            } else {
                                echo "(" . $rows . " products found)";
                            } ?></h4>
                    </div>
                    <hr>
                    <div id="AllResults">
                        <div class="row">
                            <?php foreach ($products as $product) { ?>
                                <div class="card NunitoFont col-2">
                                    <?php echo "<a href='productPage.php?productID=" . $product['productID'] . "'>" ?>
                                    <div class="cardContent">
                                        <div class="ImageHolder ImageMarginAndPadding">
                                            <?php
                                            $productImagePath = "images/products/" . $product["productID"] . "/" . $product["productImageFilename"];
                                            echo "<img src= '" . $productImagePath . "'alt='" . $product["productImageAltText"] . "'>"
                                            ?> </div>
                                        <div class="AllOtherInfo">
                                            <div class="IndividualInfo TitleDiv">
                                                <h3 class="productTitle"> <?php echo  $product['productTitle'] ?></h6>
                                                    <h4 class="productPrice"> <?php echo  "£" . $product['productPrice'] ?></h6>
                                            </div>
                                            <div class="SeeMoreDiv">
                                                <h4 class="SeeMoreTitle">See More</h6>
                                                    <?php
                                                    echo "<img onclick=productPage.php?productID=" . $product['productID'] . " class='seeMoreImage' src='images/Home/Right Arrow.svg' alt='See More of this product'/>"
                                                    ?>
                                            </div>
                                        </div>
                                    </div>
                                    <?php echo "</a>" ?>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                        <?php include "./footer.php" ?>
            </body>

            </html>
            <style>
                .productImage {
                    width: 48px;
                    height: 48px;
                }

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

                .ImageMarginAndPadding {
                    padding-right: 10px;
                    margin-right: 15px;
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

                .BookTitle {
                    text-align: center;
                    margin-right: 20px;
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
                    /*margin-top: 10px;*/
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

                .ShowMoreResults {
                    border-radius: 12.5px;
                    background-color: #c30003;
                    color: #FFFFFF;
                    margin-top: 20px;
                    margin-bottom: 20px;
                    display: inline-block;
                    padding: 15px;
                    cursor: pointer;
                }

                /* 
                .card {
                    box-shadow: 0 0 0 5px #FFFFFF;
                    border-radius: 2%;
                    height: 75px;
                    overflow: hidden;
                    position: relative;
                    padding: 10px;
                } */

                #resultsFor {
                    text-align: center;
                }
            </style>
            <script>
                var search = "<?php echo $_GET['search'] ?>";
                if (search) {
                    document.getElementById('searchInput').value = search;
                }
            </script>