<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Search - Hogwarts University Library</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="stylesheet" type="text/css" href="search_style.css">
    <link rel="stylesheet" type="text/css" href="filters.css">
    <link rel="stylesheet" type="text/css" href="responsiveness.css">

    <link href="https://fonts.googleapis.com/css?family=Nunito|Roboto+Condensed" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
</head>

<body id="Searchbody">
    <h1 id="exit" onclick="goBack()">X</h1>
    <div class="MakeDivHorVerCentre">
        <div id="wholeSearchBar">
            <form action="Results.php">
                <div id="searchForm">
                    <input type="text" id="searchBookText" name="search" autocomplete="off" placeholder="Search for a book...">
                    <input id="submit" type="submit" value="Search" />
                </div>
            </form>
        </div>

    </div>


    <script>
        $(document).ready(function() {
            var count = 1;
            setInterval(function() {
                count++;
                var dots = new Array(count % 5).join('.');
                $('#searchBookText').attr('placeholder', "Search for a book" + dots);
            }, 1000);
        });

        function goBack() {
            window.history.back();
        }


        function ToggleFilter() {
            // $("#FilterContents").slideToggle();
            // $("#wholeSearchBar").slideToggle();

            if ($("#FilterContents").css('display') == 'none') {
                $("#FilterContents").delay(500).show(1000);
                $("#searchForm").hide(1000);
                $(".bodyOfFilter h2").text("Close Filters to Search");
                $(".bodyOfFilter img").addClass("rotateArrow");
            } else {
                $("#FilterContents").hide(1000);
                $("#searchForm").delay(500).show(1000);
                $(".bodyOfFilter img").removeClass("rotateArrow");
                $(".bodyOfFilter h2").text("Filters");
            }
        }
    </script>
    </div>
</body>

</html>