
<?php
error_reporting(E_ERROR);
$user = 'Anshul Pratap Singh';
$title = 'Confirm Purchase Request';
require_once 'DBConn.php';
$view = false;
$conn = Connection::getConnection();

$pid = 0;
$show = FALSE;
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['iid'])) {
        $iid = $_GET['iid'];
        $pid = $_GET['pid'];
        $query = "DELETE FROM Item WHERE Iid = " . $iid;
        $conn->query($query);
        $query = "SELECT * FROM Item WHERE pid = ?";
        $stmt = $conn->prepare($query);
        $stmt->execute(array($pid));
        $count = $stmt->rowCount();
        if ($count > 0) {

            $query = "SELECT Iid, Make, Model, Submodel, Cyear, Color, Body, Quantity, Price, Total FROM Item WHERE pid = ?";
            $stmt = $conn->prepare($query);
            $stmt->execute(array($pid));
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $query = "SELECT Pid,Pdate,(SELECT SUM(Quantity) FROM Item where pid = " . $pid . ")as Car, (SELECT SUM(Total) FROM Item where pid = " . $pid . ")as Total"
                    . " FROM Purchase WHERE pid = ?";
            $stmt = $conn->prepare($query);
            $stmt->execute(array($pid));
            $rec = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $purchase = $rec[0];
            $show = TRUE;
        } else {
            $query = "DELETE FROM Purchase WHERE Pid = " . $pid;
            $conn->query($query);
            header("Location:purchase.php");
        }
    }
} else {
    $pid = $_POST['pid'];
}
if (isset($_POST['submit'])) {
    if ($pid > 0) {
        $query = "SELECT Iid, Make, Model, Submodel, Cyear, Color, Body, Quantity, Price, Total FROM Item WHERE pid = ?";
        $stmt = $conn->prepare($query);
        $stmt->execute(array($pid));
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $query = "SELECT Pid,Pdate,(SELECT SUM(Quantity) FROM Item where pid = " . $pid . ")as Car, (SELECT SUM(Total) FROM Item where pid = " . $pid . ")as Total"
                . " FROM Purchase WHERE pid = ?";
        $stmt = $conn->prepare($query);
        $stmt->execute(array($pid));
        $rec = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $purchase = $rec[0];

        $show = TRUE;
    } else {
        header("Location:purchase.php");
    }
} else if (isset($_POST['confirm'])) {
    $pid = $_POST['pid'];
    $show = true;
    $view = true;
    $query = "SELECT Iid, Make, Model, Submodel, Cyear, Color, Body, Quantity, Price, Total FROM Item WHERE pid = ?";
    $stmt = $conn->prepare($query);
    $stmt->execute(array($pid));
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $query = "SELECT Pid,Pdate,(SELECT SUM(Quantity) FROM Item where pid = " . $pid . ")as Car, (SELECT SUM(Total) FROM Item where pid = " . $pid . ")as Total"
            . " FROM Purchase WHERE pid = ?";
    $stmt = $conn->prepare($query);
    $stmt->execute(array($pid));
    $rec = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $purchase = $rec[0];
    session_unset();
    //session_destroy();
    //session_reset();
} else if (isset($_POST['cancel'])) {
    if ($pid > 0) {
        $query = "DELETE FROM Item WHERE Pid = " . $pid;
        $conn->query($query);
        $query = "DELETE FROM Purchase WHERE Pid = " . $pid;
        $conn->query($query);
    }
    header("Location:index.php");
}
if ($show) {
//$pid = $_POST['pid'];
    include 'head.php';
    ?>

    <div class="panel-header">


        <h2 id="_default"><i class="icon-box-add on-left"></i>Confirm Purchase Request</h2>
    </div>
    <div class="no-margin no-padding">
        <form class="no-padding bordered" method="POST" action="temp.php">
            <div class="grid">
                <fieldset>
                    <input type="hidden" name="pid" value="<?php echo $pid; ?>">
                    <legend></legend>
                    <div class="row">
                        <div class="span3">
                            <label class="subheader-secondary">Purchase Request#</label>
                        </div>
                        <div class="span1">
                            <div class="input-control select warning-state size3">
                                <label class="subheader-secondary"><?php echo $purchase['Pid']; ?></label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="span3">
                            <label class="subheader-secondary">Date</label>
                        </div>
                        <div class="span1">
                            <div class="input-control select warning-state size3">
                                <label class="subheader-secondary"><?php echo $purchase['Pdate']; ?></label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="span3">
                            <label class="subheader-secondary">Total Cars</label>
                        </div>
                        <div class="span1">
                            <div class="input-control select warning-state size3">
                                <label class="subheader-secondary"><?php echo $purchase['Car']; ?></label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="span3">
                            <label class="subheader-secondary">Total Cost</label>
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

                                    <th class="text-left"><p class="subheader">Make</p></th>
                            <th class="text-left"><p class="subheader">Model</p></th>
                            <th class="text-left"><p class="subheader">Sub-model</p></th>
                            <th class="text-center"><p class="subheader">Year</p></th>
                            <th class="text-left"><p class="subheader">Color</p></th>
                            <th class="text-left"><p class="subheader">Body Type</p></th>
                            <th class="text-left"><p class="subheader">Price</p></th>
                            <th class="text-left"><p class="subheader">Quantity</p></th>
                            <th class="text-left"><p class="subheader">Subtotal</p></th>
                            <?php
                            if (!$view) {
                                ?>
                                <th class="text-left"><p class="subheader">Action</p></th>
                            <?php } ?>
                            </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (isset($data)) {
                                    foreach ($data as $row) {
                                        ?>
                                        <tr>
                                            <td class = "right"><p class = "item-title-secondary"><?php echo $row['Make']; ?></p></td>
                                            <td class = "right"><p class = "item-title-secondary"><?php echo $row['Model']; ?></p></td>
                                            <td class = "right"><p class = "item-title-secondary"><?php echo $row['Submodel']; ?></p></td>
                                            <td class = "right"><p class = "item-title-secondary"><?php echo $row['Cyear']; ?></p></td>
                                            <td class = "right"><p class = "item-title-secondary"><?php echo $row['Color']; ?></p></td>
                                            <td class = "right"><p class = "item-title-secondary"><?php echo $row['Body']; ?></p></td>
                                            <td class = "right"><p class = "item-title-secondary"><?php echo $row['Price']; ?></p></td>
                                            <td class = "right"><p class = "item-title-secondary"><?php echo $row['Quantity']; ?></p></td>
                                            <td class = "right"><p class = "item-title-secondary"><?php echo $row['Total']; ?></p></td>
                                            <?php
                                            if (!$view) {
                                                ?>
                                                <td class="right"><p class="item-title-secondary"><a href="temp.php?iid=<?php echo $row['Iid']; ?>& pid=<?php echo $pid; ?>">Remove</a></p></td>
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
                            <div class="span3">
                                <a class="button large" href="purchase.php?pid=<?php echo $pid; ?>">Back</a>
                            </div>
                            <div class="span1 offset2">
                                <input class="large primary" type="submit" name="confirm" value="Confirm">
                            </div>
                            <div class="span1 offset2">
                                <!--<input class="large inverse" type="submit" name="cancel" value="Cancel">-->
                                <a class="button large inverse" href="home.php">Cancel</a>
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
    <?php
    include 'foot.php';
}
?>