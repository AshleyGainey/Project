<?php
if (!isset($_SESSION)) {
    @ob_start();
    @session_start();
}
?>


<head>
    <link rel="stylesheet" type="text/css" href="header.css">
</head>

<header>
    <div id="header">
        <div id="logoSection">
            <a href="index.php" id="LeftSide">
                <img id="Gadget Gainey Logo" src="images/Header/Gadget Gainey Logo (Smaller).png" alt="Gadget Gainey - Two White G's joining together with the text saying Gadget Gainey" width="249" height="148" />
            </a>
        </div>

        <div id="searchSection">
            <div class="searchBarDiv">
                <div class="search">
                    <form action="Results.php">
                        <input type="search" class="searchInput" placeholder="Search our catalogue..." autocomplete="off" name="search" value="">
                        <button type="submit" class="searchButton">
                            <i class="fa fa-search"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>


        <div id="buttonsSection">
            <a id="basket" href="Basket.php">
                <div class="navToolButtons">
                    <i class="fa fa-shopping-basket"></i>
                    <?php
                    $quantity;
                    if (isset($_SESSION['basketQuantity'])) {
                        $quantity = $_SESSION['basketQuantity'];
                        //TODO Ashley: How to update this when something has been added.
                        echo "<span id='cartCount'>" . $quantity . "</span>";
                    }                    
                    ?>
                    <h2>Basket</h2>
                </div>
            </a>


            <?php
            if (isset($_SESSION["userFirstName"])) {
                echo "<a id='account' href='account_welcome.php'>";
            } else {
                echo "<a id='account' href='Login.php'>";
            }
            ?>
            <div class="navToolButtons">
                <i class="fa fa-user-circle-o"></i>
                <h2><?php
                    if (isset($_SESSION["userFirstName"])) {
                        echo $_SESSION['userFirstName'];
                    } else {
                        echo "Account";
                    }
                    ?></h2>


            </div>
            </a>
        </div>
</header>