
<?php
error_reporting(E_ERROR);

$user = 'Anshul Pratap Singh';
$title = 'Approve PR';
include 'head.php';
require_once 'DBConn.php';
$conn = Connection::getConnection();
$pid = 0;
$show = FALSE;
$status = "";
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $pid = $_GET['pid'];
    $query = "SELECT Iid, Make, Model, Submodel, Cyear, Color, Body, Quantity, Price, Total FROM Item WHERE pid = ?";
    $stmt = $conn->prepare($query);
    $stmt->execute(array($pid));
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $query = "SELECT Pid,Status,Pdate,Ldate,(SELECT SUM(Quantity) FROM Item where pid = " . $pid . ")as Car, (SELECT SUM(Total) FROM Item where pid = " . $pid . ")as Total"
            . " FROM Purchase WHERE pid = ?";
    $stmt = $conn->prepare($query);
    $stmt->execute(array($pid));
    $rec = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $purchase = $rec[0];
    $show = TRUE;
    $status = $purchase['Status'];
} else if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
}
if ($show) {
    ?>
    <div class="panel">
        <div class="panel-header">


            <h2 id="_default"><i class="icon-checkmark on-left"></i><b>Approve Purchase Request</b></h2>
        </div>
        <div class="panel-content">
            <form class="no-padding bordered" method="POST" action="procesapr.php">
                <div class="grid">
                    <fieldset>

                        <legend></legend>
                        <input type="hidden" name="pid" value="<?php echo $pid; ?>">
                        <input type="hidden" name="status" value="<?php echo $purchase['Status']; ?>">
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
                            <div class="span3">
                                <label class="subheader-secondary"><b>Status</b></label>
                            </div>
                            <div class="span1">
                                <div class="input-control select warning-state size3">
                                    <label class="subheader-secondary"><?php echo $purchase['Status']; ?></label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="span3">
                                <label class="subheader-secondary"><b>Last Updated On</b></label>
                            </div>
                            <div class="span1">
                                <div class="input-control select warning-state size3">
                                    <label class="subheader-secondary"><?php echo $purchase['Ldate']; ?></label>
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
                            <div class="span1 offset2">

                            </div>
                            <div class="span1 offset2">                            
                                <a class="button large inverse" href="search.php">Back</a>
                            </div>
                            <div class="span1 offset2">
                                <input class="large danger" type="submit" name="reject" value="Reject">
                            </div>
                            <div class="span1 offset2">
                                <input class="large success" type="submit" name="approve" value="Approve">
                            </div>
                            
                        </div>

                    </fieldset>
                </div>
            </form>

        </div>
    </div>
    <?php
} else {
    header("Location:search.php");
}
include 'foot.php';
?>