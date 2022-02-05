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
                <input type="text" id="searchBookText" name="search" autocomplete="off"
                       placeholder="Search for a book...">
                <input id="submit" type="submit" value="Search"/>
            </div>
            <div id="Filter">
                <div class="centreText bodyOfFilter col-12" onclick="ToggleFilter()">
                    <h2>Filters</h2>
                    <img alt="Expand Study Footer Section icon" src="images/MasterPage/Expand Icon.png">
                </div>
                <div id="FilterContents">
                    <div class="box col-12">
                        <div class="box-content" id="Library">
                            <h1>Libraries:</h1>
                            <!--Placeholder for getting results from database-->
                            <label class="container">Hogwarts School of Witchcraft and Wizardry
                                <input type="checkbox" name="Hogwarts1" checked="checked">
                                <span class="checkmark"></span>
                            </label>

                            <label class="container">The Rowena Ravenclaw Library
                                <input type="checkbox" name="Hogwarts2" checked="checked">
                                <span class="checkmark"></span>
                            </label>

                            <label class="container">The Source Of Magic Library
                                <input type="checkbox" name="Hogwarts3" checked="checked">
                                <span class="checkmark"></span>
                            </label>

                            <label class="container">Hello, this is a really long title because I have nothing better to
                                do with
                                my time than time this when I should really be going to bed. Please bed, take me
                                <input type="checkbox" checked="checked">
                                <span class="checkmark"></span>
                            </label>

                            <label class="container">Please
                                <input type="checkbox" checked="checked">
                                <span class="checkmark"></span>
                            </label>

                            <label class="container">Show
                                <input type="checkbox" checked="checked">
                                <span class="checkmark"></span>
                            </label>

                            <label class="container">Me
                                <input type="checkbox" checked="checked">
                                <span class="checkmark"></span>
                            </label>

                            <label class="container">This
                                <input type="checkbox" checked="checked">
                                <span class="checkmark"></span>
                            </label>

                            <label class="container">Checkbox
                                <input type="checkbox" checked="checked">
                                <span class="checkmark"></span>
                            </label>
                        </div>
                    </div>


                    <div class="box col-12">
                        <div class="box-content" id="Genre">
                            <h1>Genre:</h1>
                            <!--Placeholder for getting results from database-->
                            <label class="container">Genre1
                                <input type="checkbox" checked="checked">
                                <span class="checkmark"></span>
                            </label>

                            <label class="container">Genre2
                                <input type="checkbox" checked="checked">
                                <span class="checkmark"></span>
                            </label>

                            <label class="container">Genre3
                                <input type="checkbox" checked="checked">
                                <span class="checkmark"></span>
                            </label>

                            <label class="container">Genre4
                                <input type="checkbox" checked="checked">
                                <span class="checkmark"></span>
                            </label>

                            <label class="container">Genre5
                                <input type="checkbox" checked="checked">
                                <span class="checkmark"></span>
                            </label>

                            <label class="container">Genre6
                                <input type="checkbox" checked="checked">
                                <span class="checkmark"></span>
                            </label
                        </div>
                    </div>


                    <div class="box col-3 centreText" id="Year">
                        <div class="box-content">
                            <h1>Year:</h1>
                            <select>
                                <option value="DefaultYear">Select Year</option>
                                <option value="2000">2000</option>
                                <option value="2001">2001</option>
                                <option value="2002">2002</option>
                                <option value="2003">2003</option>
                                <option value="2004">2004</option>
                                <option value="2005">2005</option>
                                <option value="2006">2006</option>
                                <option value="2007">2007</option>
                                <option value="2008">2008</option>
                                <option value="2009">2009</option>
                                <option value="2010">2010</option>
                                <option value="2011">2011</option>
                                <option value="2012">2012</option>
                                <option value="2013">2013</option>
                                <option value="2014">2014</option>
                                <option value="2015">2015</option>
                                <option value="2016">2016</option>
                                <option value="2017">2017</option>
                                <option value="2018">2018</option>
                                <option value="2019">2019</option>
                                <option value="2020">2020</option>
                            </select>
                        </div>
                    </div>

                    <div class="box col-5 centreText" id="Publisher">
                        <div class="box-content">
                            <h1>Publisher:</h1>
                            <input type="text" name="PublisherTextbox" autocomplete="off"
                                   placeholder="Search by Publisher">
                        </div>
                    </div>

                    <div class="box col-6 centreText" id="Author">
                        <div class="box-content">
                            <h1>Author(s):</h1>
                            <input type="text" name="Author" autocomplete="off" placeholder="Search by Author(s)">
                            <p id="Tip"><span class="blink_me">Tip:</span> Separate Authors with a comma (,)</p>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

</div>


<script>
    $(document).ready(function () {
        var count = 1;
        setInterval(function () {
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