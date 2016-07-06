
<?php
error_reporting(E_ERROR);
require_once 'DBConn.php';
$conn = Connection::getConnection();
$pid = 0;
$title = 'View Purchase Request';
$query = "SELECT * FROM session WHERE skey = 'pid'";
$stmt = $conn->prepare($query);
$stmt->execute();

if ($stmt->rowCount() > 0) {
    $rec = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $pid = $rec[0]['value'];
    $query = "DELETE FROM session WHERE skey = 'pid'";
    $stmt = $conn->prepare($query);
    $stmt->execute();
} else {
    $pid = 0;
}
if ($pid == 0) {
    header("Location: home.php", TRUE);
} else {
    $query = "SELECT Pid,Status,Pdate,Ldate,(SELECT SUM(Quantity) FROM Item where pid = " . $pid . ")as Car, (SELECT SUM(Total) FROM Item where pid = " . $pid . ")as Total"
            . " FROM Purchase WHERE pid = ?";
    $stmt = $conn->prepare($query);
    $stmt->execute(array($pid));
    $rec = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $purchase = $rec[0];
    $status = $purchase['Status'];
    $query = "SELECT Iid, Make, Model, Submodel, Cyear, Color, Body, Quantity, Price, Total FROM Item WHERE pid = ?";
    $stmt = $conn->prepare($query);
    $stmt->execute(array($pid));
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    include 'head.php';
    ?>
    <script>
        function codeAddress() {

            var oldURL = document.referrer;
            if (oldURL.indexOf("purchase1.php") > -1) {
                swal("Purchase Request Created", "The purchase request is stored successfully", "success");
            }
            else if (oldURL.indexOf("approve.php") > -1) {
            <?php if ($status == 'Rejected') { ?>
                    swal("The purchase request is cancelled", "Purchase Request Updated", "error");
            <?php } else { ?>
                    swal("The purchase request is approved", "Purchase Request Updated", "success");
            <?php } ?>
            }
        }
        window.onload = codeAddress;
    </script>
    <div class="panel">
        <div class="panel-header">


            <h2 id="_default"><i class="icon-copy on-left"></i><b>View Purchase Request</b></h2>
        </div>
        <div class="panel-content">
            <form class="no-padding bordered">
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
                            <div class="span1 offset8">
                                <a class="button large inverse" href="home.php">Close</a>
                            </div>
                        </div>

                    </fieldset>
                </div>

            </form>

        </div>
    </div>
    <?php
    include 'foot.php';
}
?>