
<?php
error_reporting(E_ERROR);
$title = 'Confirm Purchase Request';
require_once 'DBConn.php';
$view = false;
$conn = Connection::getConnection();

$pid = 0;
$show = FALSE;
$query = "SELECT * FROM session WHERE skey = 'pid'";
$stmt = $conn->prepare($query);
$stmt->execute();

if ($stmt->rowCount() > 0) {
    $rec = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $pid = $rec[0]['value'];
} else {
    $pid = 0;
}
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['iid'])) {
        $iid = $_GET['iid'];
        $pid = $_GET['pid'];
        $query = "DELETE FROM Item WHERE Iid = " . $iid;
        $conn->query($query);
    }
} else {
    
}

$query = "SELECT Iid, Make, Model, Submodel, Cyear, Color, Body, Quantity, Price, Total FROM Item WHERE pid = ?";
$stmt = $conn->prepare($query);
$stmt->execute(array($pid));
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);
if ($stmt->rowCount() == 0) {
    header("Location: purchase.php", TRUE);
    exit();
}
$query = "SELECT Pid,Pdate,(SELECT SUM(Quantity) FROM Item where pid = " . $pid . ")as Car, (SELECT SUM(Total) FROM Item where pid = " . $pid . ")as Total"
        . " FROM Purchase WHERE pid = ?";
$stmt = $conn->prepare($query);
$stmt->execute(array($pid));
$rec = $stmt->fetchAll(PDO::FETCH_ASSOC);
$purchase = $rec[0];

//$pid = $_POST['pid'];
include 'head.php';
?>
<script type="text/javascript">

    function dispMsg() {
        alert("Purchase request created");
    }
</script>
<div class="panel">
    <div class="panel-header">


        <h2 id="_default"><i class="icon-help on-left"></i><b>Confirm Purchase Request</b></h2>
    </div>
    <div class="panel-content">
        <form class="no-padding bordered" id="form1" onsubmit="return confirm('Are you sure you want to submit this form?');" method="POST" action="procesconf.php">
            <div class="grid">
                <fieldset>
                    <input type="hidden" name="pid" value="<?php echo $pid; ?>">
                    <legend></legend>
                    <div class="row">
                        <div class="span3">
                            <label class="subheader-secondary"><b>Purchase Request#</b></label>
                        </div>
                        <div class="span1">
                            <div class="input-control select warning-state size3">
                                <label class="subheader-secondary"><?php echo $purchase['Pid']; ?></label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="span3">
                            <label class="subheader-secondary"><b>Created On</b></label>
                        </div>
                        <div class="span1">
                            <div class="input-control select warning-state size3">
                                <label class="subheader-secondary"><?php echo $purchase['Pdate']; ?></label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="span3">
                            <label class="subheader-secondary"><b>Total Cars</b></label>
                        </div>
                        <div class="span1">
                            <div class="input-control select warning-state size3">
                                <label class="subheader-secondary"><?php echo $purchase['Car']; ?></label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="span3">
                            <label class="subheader-secondary"><b>Total Cost</b></label>
                        </div>
                        <div class="span1">
                            <div class="input-control select warning-state size3">
                                <label class="subheader-secondary">$<?php echo $purchase['Total']; ?></label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <table class="table bordered dataTable">
                            <thead>
                                <tr class="info">

                                    <th class="text-center"><p class="subheader"><b>Make</b></p></th>
                            <th class="text-center"><p class="subheader"><b>Model</b></p></th>
                            <th class="text-center"><p class="subheader"><b>Class</b></p></th>
                            <th class="text-center"><p class="subheader"><b>Body Type</b></p></th>
                            <th class="text-center"><p class="subheader"><b>Year</b></p></th>
                            <th class="text-center"><p class="subheader"><b>Color</b></p></th>                            
                            <th class="text-center"><p class="subheader"><b>Price</b></p></th>
                            <th class="text-center"><p class="subheader"><b>Quantity</b></p></th>
                            <th class="text-center"><p class="subheader"><b>Subtotal</b></p></th>
                            <?php
                            if (!$view) {
                                ?>
                                <th class="text-center"><p class="subheader"></p></th>
                            <?php } ?>
                            </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (isset($data)) {
                                    foreach ($data as $row) {
                                        ?>
                                        <tr>
                                            <td class = "text-center"><p class = "item-title-secondary"><?php echo $row['Make']; ?></p></td>
                                            <td class = "text-center"><p class = "item-title-secondary"><?php echo $row['Model']; ?></p></td>
                                            <td class = "text-center"><p class = "item-title-secondary"><?php echo $row['Submodel']; ?></p></td>
                                            <td class = "text-center"><p class = "item-title-secondary"><?php echo $row['Cyear']; ?></p></td>
                                            <td class = "text-center"><p class = "item-title-secondary"><?php echo $row['Body']; ?></p></td>
                                            <td class = "text-center"><p class = "item-title-secondary"><?php echo $row['Color']; ?></p></td>
                                            <td class = "text-center"><p class = "item-title-secondary">$<?php echo $row['Price']; ?></p></td>
                                            <td class = "text-center"><p class = "item-title-secondary"><?php echo $row['Quantity']; ?></p></td>
                                            <td class = "text-center"><p class = "item-title-secondary">$<?php echo $row['Total']; ?></p></td>
                                            <?php
                                            if (!$view) {
                                                ?>
                                                <td class="right"><p class="item-title-secondary"><a href="purchase1.php?iid=<?php echo $row['Iid']; ?>& pid=<?php echo $pid; ?>">Remove</a></p></td>
                                            <?php } ?>
                                        </tr>
                                        <?php
                                    }
                                }
                                ?>

                            </tbody>

                            <tfoot></tfoot>
                        </table> 
                    </div>
                    <div class="row">
                        <?php
                        if (!$view) {
                            ?>
                            <div class="span1 offset2">

                            </div>
                            <div class="span1 offset2">
                                <a class="button large inverse" href="purchase.php?pid=<?php echo $pid; ?>">Back</a>
                            </div>                            
                            <div class="span1 offset2">
                                <input class="large danger" type="submit" name="cancel" value="Cancel">
                                <!--<a class="button large inverse" href="home.php">Cancel</a>-->
                            </div>
                            <div class="span1 offset2">
                                <!--<a class="button large success"  onclick="submit()" >Confirm</a>-->
                                <input class="large success" type="submit" name="confirm" value="Confirm">
                            </div>
                            <?php
                        } else {
                            ?>
                            <div class="span1 offset5">
                                <a class="button large" href="home.php">Home</a>
                            </div>
                        <?php }
                        ?>
                    </div>


                </fieldset>
            </div>
        </form>

    </div>
</div>
<?php
include 'foot.php';
?>