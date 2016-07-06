<?php
error_reporting(E_ERROR);
require_once 'DBConn.php';
$conn = Connection::getConnection();
$query = "SELECT * FROM session where skey = 'user'";
$stmt = $conn->prepare($query);
$stmt->execute();
$rec = $stmt->fetchAll(PDO::FETCH_ASSOC);
$val = $rec[0]['value'];
$sales = FALSE;
if ($val == 'sales') {
    $sales = TRUE;
}
$user = ($val == 'tucker' ? 'Mr. Tucker' : ($val == 'sales' ? 'Sales Manager' : 'General Manager'));
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

        <script src="js/sweetalert.min.js"></script> 
        <link rel="stylesheet" type="text/css" href="css/sweetalert.css">

        <!-- Load JavaScript Libraries -->
        <script src="js/jquery/jquery.min.js"></script>
        <script src="js/jquery/jquery.widget.min.js"></script>

        <!-- Metro UI CSS JavaScript plugins -->
        <script src="js/load-metro.js"></script>

        <!-- Local JavaScript -->
        <script src="js/github.info.js"></script>
        <script src="http://www.google-analytics.com/ga.js"></script>

        <title><?php echo $title; ?></title>

    </head>
    <body class="metro">


        <div class="container no-margin no-padding bg-white" style="height:auto">
            <div class="no-margin no-padding clearfix">

                <div class="clearfix bg-dark">                    
                    <a class="place-left" href="#" title="">
                        <h1 class="fg-blue padding10">   <span class="icon-steering-wheel"></span>  Tucker Auto Dealership</h1>
                    </a>

                    <div class="element place-top-right">                        
                        <br><br>
                        <h4 class="fg-lightBlue padding10"><?php echo $user;?>
                            |<span class="icon-locked-2"></span><a href="index.php">Log Out</a></h4>
                    </div>
                </div>



            </div>

            <div class="grid clearfix no-margin no-padding" style="height:100%">
                <div class="row no-margin no-padding" style="height:100%">
                    <div class="span3 no-margin no-padding" style="height:100%">
                        <nav class="sidebar">
                            <ul>                                
                                <li class=""><h3 class="no-margin no-padding"><a href="home.php"><i class="icon-home"></i>Home</a></h3></li>                                
                                <?php
                                $href = 'purchase.php';
                                if (!$sales) {
                                    $dis = 'disabled';
                                    $href = 'home.php';
                                } else {
                                    $dis = '';
                                }
                                ?>
                                <li class="divider"></li>
                                <li class="<?php echo $dis; ?>"><h4 class="no-margin no-padding">
                                        <a href="<?php echo $href; ?>"><center>Create Purchase Request</center></a></h4></li>
                                <li class="divider"></li>
                                <?php
                                $href1 = 'search.php';
                                if ($sales) {
                                    $href1 = 'home.php';
                                    $dis = 'disabled';
                                } else {
                                    $dis = '';
                                }
                                ?>
                                <li class="<?php echo $dis; ?>"><h4 class="no-margin no-padding">
                                        <a href="<?php echo $href1; ?>"><center>Approve Purchase Request</center></a></h4></li>                                        
                                <li class="divider"></li>
                                <li class=""><h4 class="no-margin no-padding"><a href="report_1.php"><center>Generate Inventory Report</center></a></h4></li>
                            </ul>
                        </nav>
                    </div>
                    <div class="main-content no-overflow margin20 padding20">