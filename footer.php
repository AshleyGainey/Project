<footer id="footer">
    <!-- 3 sections (width 33%), each having a category title and at least 2 Sub Categories -->
    <!-- This section is about Gadget Gainey -->
    <div id="AboutGGCategory" class="category">
        <div class="categoryTitle">
            <h1>About Us</h1>
        </div>
        <!-- With 3 sub categories -->
        <div class="SubCategory">
            <a href="Error404.php">About Gadget Gainey</a>
            <a href="Error404.php">Delivery & Returns</a>
            <a href="Error404.php">Our Customer Service Promise</a>
        </div>
    </div>
    <!-- This section is the Account management section -->
    <div id="AccountCategory" class="category">
        <div class="categoryTitle">
            <h1>Account</h1>
        </div>
        <!-- With 2 sub categories -->
        <div class="SubCategory">
            <a href="account_welcome.php">My Account</a>
            <a href="order_history.php">Order History</a>
        </div>
    </div>
    <!-- This section is the getting in contact section -->
    <div id="ContactUsCategory" class="category">
        <div class="categoryTitle">
            <h1>Contact Us</h1>
        </div>
        <!-- With 2 sub categories -->
        <div class="SubCategory">
            <a href="Error404.php">Help Community</a>
            <a href="Error404.php">Get in Touch</a>
        </div>
    </div>
    <!-- This Copyright section has a width of 100% and is displayed at the bottom, using PHP to get the current year -->
    <div id="Copyright">
        <h4>© Copyright <?php echo date("Y"); ?> Gadget Gainey</h4>
    </div>
</footer>
<style>
    #footer {
        position: absolute;
        bottom: 0;
        width: 100%;
        clear: both;
        background: #1a1862;
    }

    .category,
    #Copyright {
        padding-top: 5px;
        padding-bottom: 5px;
    }

    .category img {
        max-width: 20px;
        float: right;
    }

    #footer h1,
    #footer img {
        display: inline;
        padding: 10px;
        font-size: 2em;
        font-weight: normal;
    }

    #footer h4 {
        color: #bebebe;
        text-align: center;
        font-size: 0.7em;
    }

    #footer a {
        text-decoration: none;
        cursor: pointer;
    }

    #footer h1 {
        color: #FFFFFF;
    }

    .SubCategory a {
        color: #FFFFFF;
        text-decoration: none;
        padding: 5px;
        font-size: 1em;
        display: block;
    }

    .SubCategory {
        padding-left: 10px;
        cursor: pointer;
    }
</style>