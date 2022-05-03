<?php
if (!isset($_SESSION)) {
    @ob_start();
    @session_start();
}
?>


<head>
    <link rel="stylesheet" type="text/css" href="header.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
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
                    <form id="searchForm" action="Results.php" method="get" onsubmit="searchValidation()">
                        <input type="search" class="searchInput" placeholder="Search our catalogue..." autocomplete="off" name="search" id="searchInput" required>
                        <button type="submit" id="searchButton">
                            <i class="fa fa-search"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>


        <div id="buttonsSection">
            <a id="basket" href="Basket.php">
                <div class="headerSideButtons">
                    <i class="fa fa-shopping-basket"></i>
                    <?php
                    $quantity = 0;
                    if (isset($_SESSION['basket'])) {
                        $quantity = count($_SESSION['basket']);
                    }
                    echo "<span id='basketCount'>" . $quantity . "</span>";
                    ?>
                    <h2>Basket</h2>
                </div>
            </a>

            </body>


            <?php
            if (isset($_SESSION["userFirstName"])) {
                echo "<a id='account' href='account_welcome.php'>";
            } else {
                echo "<a id='account' href='Login.php'>";
            }
            ?>
            <div class="headerSideButtons">
                <i class="fa fa-user-circle-o"></i>
                <h2 id="userFirstName"><?php
                                        if (isset($_SESSION["userFirstName"])) {
                                            echo $_SESSION['userFirstName'];
                                        } else {
                                            echo "Account";
                                        }
                                        ?></h2>


            </div>
            </a>
        </div>
        <script>
            function searchValidation() {
                event.preventDefault();
                var search = document.getElementById("searchInput").value.trim();
                document.getElementById("searchInput").value = search;
                if (search && search.length > 0) {
                    document.getElementById("searchForm").submit()
                }
            }

            var count = document.getElementById('basketCount');
            if (count != null) {
                count = count.innerHTML;
                if (count > 0) {
                    document.getElementById("basketCount").style.display = "inline";
                } else {
                    document.getElementById("basketCount").style.display = "none";
                }
            }
        </script>
</header>