<?php
    session_start();
    include_once("classes/Connect.php");
    $_SESSION['connect'] = new Connect();
?>

<html>
    <head>
        <!---PLUGINS/LIBRARIES--->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">


        <!---INCLUDED LOCAL FILES--->
        <link type="text/css" rel="stylesheet" href="style/index.css">
        <script src="js/index.js"></script>
    </head>
    <body>
        <div class="container">
            <div class="row main">
                <div class="col-md-12" style="width:500px;">
                    <div class="row">
                        <div class="col-md-4">
                            <button type="button" class="btn btn-primary row_btn" onclick="get_rows()">get rows</button>
                        </div>
                        <div class="col-md-4">
                            <div class="row">
                                <button type="button" class="btn btn-primary more_btn" onclick="get_more()">get more</button>
                                <input class="more_input" size="6" maxlength="3" value="1"></input>
                            </div>
                            <div class="row">
                                <span class="more_message"></span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="row">
                                <button type="button" class="btn btn-primary less_btn" onclick="show_less()">show less</button>
                                <input class="less_input" size="6" maxlength="3" value="1"></input>
                            </div>
                            <div class="row">
                                <span class="less_message"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 data" style="width:500px; height: 1200px; overflow-y: auto;">
                    <div class="row headings">
                        <div class="col-md-2">
                            <span>NAME</span>
                        </div>
                        <div class="col-md-2">
                            <span>CALORIES</span>
                        </div>
                        <div class="col-md-2">
                            <span>CATEGORY</span>
                        </div>
                        <div class="col-md-2">
                            <span>INGREDIENTS</span>
                        </div>
                        <div class="col-md-2">
                            <span>PREPARE TIME</span>
                        </div>
                        <div class="col-md-2">
                            <span>COST</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>

</html>

