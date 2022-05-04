<?php
// If the session hasn't started. Start it (can then use session variables)
if (!isset($_SESSION)) {
    @ob_start();
    @session_start();
}
?>

<head>
    <!-- Get the header stylesheet as well as import version 4.7 (all free version) icon pack style sheet from Front Awesome -->
    <link rel="stylesheet" type="text/css" href="header.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<header>
    <!-- For the header: Three secrions, logo, search and the buttons section -->
        <!-- Logo section contains the logo image and if clicked, go to the Home Page -->
        <div id="logoSection">
            <a href="index.php" id="LeftSide">
                <img id="Gadget Gainey Logo" src="images/Header/Gadget Gainey Logo (Smaller).png" alt="Gadget Gainey - Two White G's joining together with the text saying Gadget Gainey" width="249" height="148" />
            </a>
        </div>

        <!-- Search section contains the search input and if entered something into search and press enter/press the magnifying glass button,  do some client side validation and then submit the data to results.php-->
        <div id="searchSection">
            <div id="searchBarDiv">
                <form id="searchForm" action="Results.php" method="get" onsubmit="searchValidation()">
                    <input type="search" class="searchInput" placeholder="Search our catalogue..." autocomplete="off" name="search" id="searchInput" required>
                    <button type="submit" id="searchButton">
                        <i class="fa fa-search"></i>
                    </button>
                </form>
            </div>
        </div>

        <!-- Buttons section contains the basket button and the Account button -->
        <div id="buttonsSection">
            <!-- If clicked the div, go to the basket page -->
            <a id="basket" href="Basket.php">
                <div class="headerSideButtons">
                    <i class="fa fa-shopping-basket"></i>
                    <!-- Show a count of what is in the basket by getting the basket variable in the session (if already defined) -->
                    <?php
                    $basketItems = 0;
                    if (isset($_SESSION['basket'])) {
                        $basketItems = count($_SESSION['basket']);
                    }
                    echo "<span id='basketCount'>" . $basketItems . "</span>";
                    ?>
                    <h2>Basket</h2>
                </div>
            </a>

            <!-- If the user is logged in and their First Name is defined, then link them to the Account Welcome page. If not logged in, go to the Login/Register page. -->
            <?php
            if (isset($_SESSION["userFirstName"])) {
                echo "<a id='account' href='account_welcome.php'>";
            } else {
                echo "<a id='account' href='Login.php'>";
            }
            ?>
            <div class="headerSideButtons">
                <i class="fa fa-user-circle-o"></i>
                <!-- The account text is either the user's first name (if they are logged in) or it is just "Account" if not -->
                <h2 id="accountText"><?php
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
<script>
    // Some Client Side Validation
    function searchValidation() {
        // Prevent the action
        event.preventDefault();
        // Get the value from the search textbox, trim it and put it back in the searchbar
        var search = document.getElementById("searchInput").value.trim();
        document.getElementById("searchInput").value = search;
        // If the string length of what is in the searchbar (after trim) is more than 0, then submit the form
        // TODO Ashley: Do Server Side Validation on this
        if (search && search.length > 0) {
            document.getElementById("searchForm").submit()
        }
    }
    // When on load, get the current count of items in the basket.
    // If count is over 0, then show it. If it isn't (if it is 0), then hide it.
    var count = document.getElementById('basketCount').innerHTML;
    if (count > 0) {
        document.getElementById("basketCount").style.display = "inline";
    } else {
        document.getElementById("basketCount").style.display = "none";
    }
</script>
</header>