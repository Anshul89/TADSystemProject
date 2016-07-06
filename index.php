<?php
session_unset();
//session_destroy();
//session_reset();
error_reporting(E_ERROR);
require_once 'DBConn.php';
$conn = Connection::getConnection();
$query = "DELETE FROM session";
$stmt = $conn->prepare($query);
$stmt->execute();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $val = $_POST['r1'];
    $query = "INSERT INTO session VALUE('user',?,'')";
    $stmt = $conn->prepare($query);
    $stmt->execute(array($val));
    header("location: home.php", TRUE);
} else {
    ?>
    <!DOCTYPE html>
    <html>
        <head>
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
            <meta name="product" content="Metro UI CSS Framework">
            <meta name="keywords" content="Metro, UI, CSS, Framework, jquery">
            <meta name="description" content="Simple responsive css framework">
            <meta name="author" content="Sergey S. Pimenov, Ukraine, Kiev">

            <link href="css/metro-bootstrap.css" rel="stylesheet">
            <!--<link href="css/metro-bootstrap-responsive.css" rel="stylesheet">-->
            <link href="css/iconFont.css" rel="stylesheet">

            <!-- Load JavaScript Libraries -->
            <script src="js/jquery/jquery.min.js"></script>
            <script src="js/jquery/jquery.widget.min.js"></script>

            <!-- Metro UI CSS JavaScript plugins -->
            <script src="js/load-metro.js"></script>

            <!-- Local JavaScript -->
            <script src="js/github.info.js"></script>
            <script src="js/ga.js"></script>

            <title>Log In</title>

        </head>
        <body class="metro" >

            <script>
                function myFunction() {
                    alert("Page is loaded");
                    $.Notify({
                        shadow: true,
                        position: 'bottom-right',
                        content: "Metro UI CSS is awesome!!!"
                    });
                }
            </script>
            <div class="container no-margin no-padding bg-white" style="height:auto">
                <div class="no-margin no-padding clearfix">

                    <div class="clearfix bg-dark">                    
                        <a class="place-left" href="#" title="">
                            <h1 class="fg-lightBlue padding10">   <span class="icon-steering-wheel"></span>  Tucker Auto Dealership</h1>
                        </a>

                    </div>
                </div>

                <div class="grid clearfix no-margin no-padding" style="height:100%">
                    <div class="row no-margin no-padding" style="height:100%">
                        <div class="span3 no-margin no-padding" style="height:100%">
                            <nav class="sidebar">
                                <ul>                                
                                    <li class="disabled"><h3 class="no-margin no-padding"><a href="#"><i class="icon-home"></i>Home</a></h3></li>                                
                                    <li class="divider"></li>
                                    <li class="disabled">
                                        <h4 class="no-margin no-padding"><center><a href="#">Create Purchase Request
                                                </a></center></h4></li>
                                    <li class="divider"></li>
                                    <li class="disabled">
                                        <h4 class="no-margin no-padding"><a href="#">
                                                <center>Approve Purchase Request</center></a></h4></li>
                                    <li class="disabled"><h4 class="no-margin no-padding"><a href="#">                                               
                                                <center>Generate Inventory Report</center></a></h4></li>
                                    <li class="divider"></li>
                                </ul>
                            </nav>
                        </div>
                        <div class="main-content no-overflow margin20 padding20">
                            <div class="panel">
                                <div class="panel-header">
                                    <h2 id="_default"><i class="icon-key-2 on-left"></i><b>Log In</b></h2>
                                </div>
                                <div class="panel-content">
                                    <form action="#" method="POST">
                                        <div class="grid">
                                            <fieldset>

                                                <legend></legend>
                                                <div class="row">
                                                    <div class="span3">
                                                        <label class="subheader-secondary"><b>Choose User Role</b></label>
                                                    </div>
                                                    <div class="span8 ">
                                                        <div class="input-control radio default-style" data-role="input-control">
                                                            <label>
                                                                <input type="radio" name="r1" value="sales" checked />
                                                                <span class="check"></span>
                                                                <b>New Car - Sales Manager</b>
                                                            </label>
                                                        </div>
                                                        <div class="input-control radio  default-style" data-role="input-control">
                                                            <label>
                                                                <input type="radio" name="r1" value="general"/>
                                                                <span class="check"></span>
                                                                <b>General Manager</b>
                                                            </label>
                                                        </div>
                                                        <div class="input-control radio  default-style" data-role="input-control">
                                                            <label>
                                                                <input type="radio" name="r1" value="tucker"/>
                                                                <span class="check"></span>
                                                                <b>Mr. Tucker</b>
                                                            </label>
                                                        </div>

                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="span1 offset3">
                                                        <input class="large primary" type="submit" name="submit" value="Select">
                                                    </div>                                                
                                                </div>
                                            </fieldset>
                                        </div>

                                    </form>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </body>
    </html>
    <?php
}
?>