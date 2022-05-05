<head>
    <!-- Get the link to the style sheet for the footer -->
    <link rel="stylesheet" type="text/css" href="footer.css">
</head>


<footer id="footer">
    <!-- 3 sections (width 33%), each having a category title and at least 2 Sub Categories -->
    <!-- This section is about Gadget Gainey -->
    <div id="AboutGGCategory" class="category">
        <div class="categoryTitle">
            <h1>About Us</h1>
        </div>
        <!-- With 3 sub categories -->
        <div class="SubCategory">
            <a href="#">About Gadget Gainey</a>
            <a href="#">Delivery & Returns</a>
            <a href="#">Our Customer Service Promise</a>
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
            <a href="order_history.php">Order Details</a>
        </div>
    </div>
    <!-- This section is the getting in contact section -->
    <div id="ContactUsCategory" class="category">
        <div class="categoryTitle">
            <h1>Contact Us</h1>
        </div>
        <!-- With 2 sub categories -->
        <div class="SubCategory">
            <a href="#">Help Community</a>
            <a href="#">Get in Touch</a>
        </div>
    </div>
    <!-- This Copyright section has a width of 100% and is displayed at the bottom, using PHP to get the current year -->
    <div id="Copyright">
        <h4>© Copyright <?php echo date("Y"); ?> Gadget Gainey</h4>
    </div>
</footer>