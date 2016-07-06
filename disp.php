
<?php
error_reporting(E_ERROR);
$title = 'Inventory Report';
require_once 'DBConn.php';
include 'head.php';
$conn = Connection::getConnection();

$select = "SELECT vin, ctype, status, make, model, submodel, cyear, body, color, stock, cdate FROM inventory ";
$date = "SELECT distinct status FROM inventory ";
$where = "";
$order = " Order By cdate";
$used = FALSE;
$sdate = "";
$edate = "";
if (isset($_POST['submit'])) {
    $start = "";
    $end = "";
    $type = "";
    $a = false;
    $b = FALSE;
    $c = FALSE;
    if (isset($_POST['sdate']) && !empty($_POST['sdate'])) {
        $start = " cdate >= '" . $_POST['sdate'] . "'";
        $a = TRUE;
        $sdate = $_POST['sdate'];
    }
    if (isset($_POST['edate']) && !empty($_POST['edate'])) {
        $end = " cdate <= '" . $_POST['edate'] . "'";
        $b = TRUE;
        $edate = $_POST['edate'];
    }
    if (isset($_POST['r1']) && !empty($_POST['r1'])) {
        if ($_POST['r1'] == 'all') {
            $type = "";
        } else {
            if ($_POST['r1'] == 'Used') {
                $used = true;
            }
            $c = TRUE;
            $type = " ctype = '" . $_POST['r1'] . "'";
        }
    }
    $query1 = "";
    $query = "";
    if (!$a && !$b && $c) {
        $where = " WHERE ";
        $where = $where . $type;
    } else if (!$a && $b && !$c) {
        $where = " WHERE ";
        $where = $where . $end;
    } else if (!$a && $b && $c) {
        $where = " WHERE ";
        $where = $where . $end . ' AND ' . $type;
    } else if ($a && !$b && !$c) {
        $where = " WHERE ";
        $where = $where . $start;
    } else if ($a && !$b && $c) {
        $where = " WHERE ";
        $where = $where . $start . ' AND ' . $type;
    } else if ($a && $b && !$c) {
        $where = " WHERE ";
        $where = $where . $start . ' AND ' . $end;
    } else if ($a && $b && $c) {
        $where = " WHERE ";
        $where = $where . $start . ' AND ' . $end . ' AND ' . $type;
    } else {
        $reset = true;
        $where = "";
    }
}


$query = $select . $where . " ORDER BY cdate";
$stmt = $conn->prepare($query);
//echo $query;
$stmt->execute();
$arr = $stmt->fetchAll(PDO::FETCH_ASSOC);
$max = $stmt->rowCount();
if ($sdate == "") {
    $sdate = $arr[0]['cdate'];
}
if ($edate == "") {
    $edate = $arr[$max - 1]['cdate'];
}

$query1 = $date . $where . $order;

$stmt = $conn->prepare($query1);
$stmt->execute();
$arr = $stmt->fetchAll(PDO::FETCH_ASSOC);
$max = $stmt->rowCount();


$avlCount = 0;
$soldCount = 0;
$newCount = 0;
$useCount = 0;
$total = 0;
?>
<div class="panel no-margin no-padding" id="report">
    <div class="panel-header">                
        <h2 class="text-center header" id="_default"><i class="icon-cabinet on-right"></i>  Inventory Stock Report</h2>
        <br><h4 class="text-center subheader">From <?php echo $sdate ?> to <?php echo $edate ?></h4>
        <legend></legend>
    </div>
    <div class="panel-content">
        <div class="grid">
            <div class="row">
                <div class="listview-outlook" data-role="listview" style="margin-top: 20px">                      
                    <?php
                    foreach ($arr as $col) {
                        $status = $col['status'];
                        echo '<div class = "list-group ">';
                        echo '<a href = "" class = "group-title h3"><p class = "subheader">Car Status: ' . $status . '</p></a>';
                        echo '<div class = "group-content">';
                        echo '<table class="table bordered dataTable">';
                        echo '<tr class="info">';
                        echo '<td>Car Type</td>';
                        echo '<td>VIN#</td>';
                        echo '<td>Stock#</td>';
                        echo '<td>Aquire Date</td>';
                        echo '<td>Make</td>';
                        echo '<td>Model</td>';
                        echo '<td>Class</td>';
                        echo '<td>Year</td>';
                        echo '<td>Body</td>';
                        echo '<td>Color</td>';
                        echo '</tr>';
                        $temp = $select . " WHERE status = '" . $status . "'";
                        if ($c) {
                            $temp = $temp . " AND " . $type;
                        }
                        $temp = $temp . " Order By status";
                        $stmt = $conn->prepare($temp);
                        $stmt->execute();
                        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        foreach ($data as $row) {
                            $total ++;
                            if ($row['status'] == 'Available') {
                                $avlCount++;
                            } else {
                                $soldCount++;
                            }
                            if ($row['ctype'] == 'Used') {
                                $useCount++;
                            } else {
                                $newCount++;
                            }
                            echo '<tr>';
                            echo '<td>' . $row['ctype'] . '</td>';
                            echo '<td>' . $row['vin'] . '</td>';
                            echo '<td>' . $row['stock'] . '</td>';
                            echo '<td>' . $row['cdate'] . '</td>';
                            echo '<td>' . $row['make'] . '</td>';
                            echo '<td>' . $row['model'] . '</td>';
                            echo '<td>' . $row['submodel'] . '</td>';
                            echo '<td>' . $row['cyear'] . '</td>';
                            echo '<td>' . $row['body'] . '</td>';
                            echo '<td>' . $row['color'] . '</td>';
                            echo '</tr>';
                        }
                        echo '</table>';
                        echo '</div>';
                        echo '</div>';
                    }
                    ?>                    
                </div>
            </div>
            <legend></legend>
            <div class="row">    

                <div class="span4">
                    <label class="subheader"><b>Available Cars:</b><?php echo ' ' . $avlCount; ?></label>                    
                </div>
                <div class="span4">
                    <label class="subheader"><b>Sold Cars:</b><?php echo ' ' . $soldCount; ?></label>                    
                </div>

            </div>
            <?php if (!$c) { ?>
                <div class="row">

                    <div class="span4">
                        <label class="subheader"><b>New Cars:</b><?php echo ' ' . $newCount; ?></label>                        
                    </div>
                    <div class="span4">
                        <label class="subheader"><b>Used Cars:</b><?php echo ' ' . $useCount; ?></label>                        
                    </div>

                </div>
            <?php } ?>
            <div class="row">
                <center>
                    <div class="span6">
                        <label class="subheader"><b>Total Cars:</b><?php echo ' ' . $total; ?></label>
                    </div>
                </center>
            </div>
        </div>
        <legend></legend>

        <div class="row">

            <div class="span3 offset3">
                <a class="button large inverse" href="report_1.php">Back</a>
            </div>
            <div class="span3">
                <a class="button large inverse" href="home.php">Close</a>
            </div>

        </div>

    </div>
</div>
</div>
<?php
include 'foot.php';
?>