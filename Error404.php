<head>
    <title>Error 404 - Gadget Gainey Store</title>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="keywords" content="Gadget Gainey, Gadget, Ecommerce, Online, Shop, Kids Toys, Toys, Technology, Gainey, Ashley Gainey">
    <meta name="author" content="Ashley Gainey">
    <meta name="description" content="Oops, we can't find the page you are looking for.">
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body>
    <?php include "./header.php" ?>

    <div id="mainBody">
        <h1>Well...Things here didn't go to plan!</h1>
        <div class="imageBodyContainer">
            <img class="polaroidsImg" src="images/404.png" />
            <div class="containing">
                <p>Sorry, we couldn't find that page you were looking for. It might be an old link or it has been moved.</p>
                <p>Try one of the pages below instead!</p>


                <a href="index.php">
                    <p>Home Page</p>
                    <a id="previousButton" onclick="goBack()">
                        <p>Go back to previous page</p>
                    </a>
                </a>
            </div>
        </div>
    </div>
</body>


<?php include "./footer.php" ?>

<style>
    .imageBodyContainer {
        display: flex;
        flex-wrap: nowrap;
    }

    .polaroidsImg {
        width: 20%;
        height: 20%;
        margin-left: 20%;
    }


    .containing {
        padding: 0% 10% 10% 10%;
    }
</style>