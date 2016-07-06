
<?php
$user = 'Anshul Pratap Singh';
$title = 'Home';
include 'head.php';
require_once 'DBConn.php';
$conn = Connection::getConnection();
$err = false;
$sdate = $_GET['sdate'];
$edate = $_GET['edate'];
$type = $_GET['type'];
if (!isset($sdate) || !isset($edate) || !isset($type)) {
    $sdate = $_SESSION['sdate'];
    $edate = $_SESSION['edate'];
    $type = $_SESSION['sdate'];
}
$query = "SELECT vin, ctype, make, model, submodel, cyear, body, color, stock, cdate FROM Inventory  ";
if (isset($sdate) && isset($edate) && isset($type)) {
    $query = $query . "WHERE cdate > '" . $sdate . "' AND cdate < '" . $edate . "'";
    if (strcmp($type, "all") != 0) {
        $query = $query . " AND ctype = '" . $type . "'";
    }
}
echo  $query;

$query = $query . " Order By cdate,ctype,vin";
$stmt = $conn->prepare($query);
$stmt->execute();
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);
$count = $stmt->rowCount();
$query = "SELECT distinct cdate FROM Inventory Order By cdate";
$stmt = $conn->prepare($query);
$stmt->execute();
$arr = $stmt->fetchAll(PDO::FETCH_ASSOC);
$max = $stmt->rowCount();
$curdate;
?>
<div class="panel-header">


    <a href="report.php"<span class="icon-previous"><p>Back</p></span></a>
    <h2 class="text-center" id="_default"><i class="icon-database on-right"></i>  Inventory Stock Report</h2>
    <h4 class="text-center" id="_default">From <?php echo $sdate ?> to <?php echo $edate ?></h4>

    <legend></legend>
</div>

<div class="listview-outlook" data-role="listview" style="margin-top: 20px">
    <div class="list-group ">

    </div>
    <?php
    $ptr = 0;
    $temp = $arr[$ptr];
    $curdate = $temp['cdate'];
    $flag = true;

    echo '<div class = "list-group collapsed">';
    echo '<a href = "" class = "group-title h3"><p class = "subheader">' . $curdate . '</p></a>';
    echo '<div class = "group-content">';
    $insert = false;

    for ($ind = 0; $ind < $count; $ind++) {
        $row = $data[$ind];
        if ($insert) {
            echo '<div class = "list-group collapsed">';
            echo '<a href = "" class = "group-title h3"><p class = "subheader">' . $row['cdate'] . '</p></a>';
            echo '<div class = "group-content">';

            $insert = false;
        }

        echo '<a class="list" href="#">';
        echo '<div class="list-content">';
        echo '<span class="list-title large-size">Car Type: ' . $row['ctype'];
        echo '</span>';
        echo '<span class="list-content large-size">VIN#: ' . $row['vin'];
        echo '<span class="place-right">Stock#: ' . $row['stock'] . '</span>';
        echo '</span>';
        echo '<span class="list-content large-size">Make: ' . $row['make'];
        echo '<span class="place-right">Year: ' . $row['cyear'] . '</span>';
        echo '</span>';
        echo '<span class="list-content large-size">Model: ' . $row['model'];
        echo '<span class="place-right">Sub-model: ' . $row['submodel'] . '</span>';
        echo '</span>';
        echo '<span class="list-content large-size">Body: ' . $row['body'];
        echo '<span class="place-right">Color: ' . $row['color'] . '</span>';
        echo '</span>';
        echo '</div>';
        echo '</a>';
        $j = $ind + 1;
        if ($j <= $count - 1 && $flag) {
            $next = $data[$j];
            if (strcmp($curdate, $next['cdate']) !== 0) {
                echo '</div>';
                echo '</div>';
                $insert = true;
                if ($ptr < $max - 1) {
                    $temp = $arr[$ptr];
                    $curdate = $temp['cdate'];
                    $ptr++;
                }
                if ($ptr == $max - 1) {
                    $flag = false;
                }
            }
        }
    }
    echo '</div>';
    echo '</div>';

    ;
    ?>


</div>

<legend></legend>
<?php
include 'foot.php';
?>