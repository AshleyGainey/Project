<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Error 404 - Gadget Gainey Store</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <!--    Offline use -->
    <script src="jquery-3.4.1.min.js"></script>
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