            <?php
            include 'DBlogin.php';

            $conn = mysqli_connect($host, $user, $pass, $database);

            if (!$conn) {
                echo 'Connection error: ' . mysqli_connect_error();
            }

            $query =
                'SELECT p.productID, p.productTitle, 
p.productDescription, p.productPrice, pi.productImageFilename, pi.productImageAltText
        FROM product 
AS p RIGHT JOIN product_image pi
 ON pi.productID = p.productID WHERE  pi.displayOrder = 1 AND p.productTitle LIKE "%'
                . $_GET['search'] . '%"';

            $result = mysqli_query($conn, $query);

            $products = mysqli_fetch_all($result, MYSQLI_ASSOC);
            $rows = mysqli_num_rows($result);

            print_r($products);

            //Free  memory and close the connection
            mysqli_free_result($result);
            mysqli_close($conn)
            ?>

            <!DOCTYPE html>
            <html lang="en">

            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1">


                <title>Results for <?php echo $_GET['search']; ?> - Gadget Gainey Store</title>
                <link rel="stylesheet" type="text/css" href="style.css">
                <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
                <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

                <!--    Offline use -->
                <script src="jquery-3.4.1.min.js"></script>
            </head>

            <body>
                <?php include "./header.html" ?>

                <div id="mainBody">
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
                                <div class="card NunitoFont col-5">
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
                    <?php include "./footer.php" ?>
            </body>

            </html>
            <style>
                .productImage {
                    width: 48px;
                    height: 48px;
                }

                .seeMoreDiv {
                    display: inline-block;
                }



                .seeMoreTitle {
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