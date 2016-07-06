<?php
//error_reporting(E_ERROR);
require_once 'DBConn.php';
$conn = Connection::getConnection();
$pid = -1;
if (isset($_GET['pid'])) {
    $pid = $_GET['pid'];
} else {
    $query = "SELECT * FROM session WHERE skey = 'pid'";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    if ($stmt->rowCount() > 0) {
        $rec = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $pid = $rec[0]['value'];
    } else {
        $pid = 0;
    }
}
$user = 'Anshul Pratap Singh';
$title = 'New Purchase Request';
include 'head.php';
require_once "formvalidator.php";
$show_form = true;
$isErr = false;
$err = array("make" => false, "model" => false, "submodel" => false, "year" => false, "color" => false, "body" => false, "quantity" => false);

$make = "";
$model = "";
$submodel = "";
$year = "";
$color = "";
$body = "";
$price = "";
$quantity = "";
$data = null;
if (isset($_POST['submit'])) {
    $make = $_POST['make'];
    $model = $_POST['model'];
    $submodel = $_POST['submodel'];
    $year = $_POST['year'];
    $color = $_POST['color'];
    $body = $_POST['body'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];
    $validator = new FormValidator();
    $validator->addValidation("quantity", "req", "3");
    $validator->addValidation("quantity", "num", "4");
    $errMsg = "";
    if ($make == '0') {
        $isErr = true;
        $err['make'] = TRUE;
    }
    if ($model == '0') {
        $isErr = true;
        $err['model'] = TRUE;
    }
    if ($submodel == '0') {
        $err['submodel'] = TRUE;
        $isErr = true;
    }
    if (!$isErr) {
        $query = "SELECT Price as price FROM car where make = '" . $make . "' AND model = '" . $model . "' AND submodel = '" . $submodel . "'";        
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $rec1 = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $row1 = $rec1[0];
        $price = $row1['price'];
    }
    if ($year == '0') {
        $isErr = true;
        $err['year'] = true;
    }
    if ($color == '0') {
        $isErr = true;
        $err['color'] = true;
    }
    if ($body == '0') {
        $isErr = true;
        $err['body'] = true;
    }
    if (!isset($quantity)) {
        $isErr = true;
        $err['quantity'] = TRUE;
    } else {
        if ($quantity <= '0') {
            $isErr = true;
            $err['quantity'] = TRUE;
        }
    }
    if (!$isErr) {
        if ($validator->ValidateForm()) {
            $isErr = false;
            //Create PID and Item

            $dt = date("Y-m-d");
            $dt = date('Y-m-d', strtotime("-1 day"));
            if ($pid <= 0) {
                $query = "INSERT INTO Purchase (Salesmanager, Status, Pdate, Ldate, AppGM, AppT, isReject) "
                        . "VALUES (?,'Pending',?,?,FALSE,FALSE,FALSE)";
                $stmt = $conn->prepare($query);
                $stmt->execute(array($user, $dt, $dt));
                $query = "SELECT MAX(PID) as Pid FROM Purchase";
                $stmt = $conn->prepare($query);
                $stmt->execute();
                $arr = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $ele = $arr[0];
                $pid = $ele['Pid'];

                $query = "delete from session where skey = 'pid'";
                $stmt = $conn->prepare($query);
                $stmt->execute();

                $query = "Insert into session (skey,value) VALUES ('pid',?)";
                $stmt = $conn->prepare($query);
                $stmt->execute(array($pid));
            }
            $total = $quantity * $price;
            $query = "INSERT INTO Item (Pid, Make, Model, Submodel, Cyear, Color, Body, Quantity, Price, Total)"
                    . "VALUES (?,?,?,?,?,?,?,?,?,?)";
            $stmt = $conn->prepare($query);
            try {
                $stmt->execute(array($pid, $make, $model, $submodel, $year, $color, $body, $quantity, $price, $total));
            } catch (PDOException $ex) {
                
            }
        } else {
            $isErr = true;
            $error_hash = $validator->GetErrors();
            foreach ($error_hash as $inpname => $inp_err) {
                switch ($inp_err) {
                    case '3':
                    case '4':
                        $err['quantity'] = true;
                        break;
                }
            }
            $errMsg = "Please provide valid value for highlighted fields";
        }
    } else {
        $errMsg = "Highlighted fields are required";
    }
}
if ($pid > 0) {
    $query = "SELECT Make, Model, Submodel, Cyear, Color, Body, Quantity, Price, Total FROM Item WHERE pid = ?";
    $stmt = $conn->prepare($query);
    $stmt->execute(array($pid));
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
<script>
    function makeChange() {
        $("#model").html("");
        $("#model").load("getter.php?get=model&column=make&choice=" + $("#make").val());
        $("#submodel").html("");
//        $("#year").html("");
        $("#submodel").html("<option value='0'>Please Choose Option</option>");
        $("#year").html("<option value='0'>Please Choose Option</option>");
    }
    function modelChange() {
        $("#submodel").html("");
        $("#submodel").load("getter.php?get=submodel&column=model&choice=" + $("#model").val());
//        $("#year").html("");
//        $("#year").html("<option value='0'>Please Choose Option</option>");
    }
    function subChange() {
        $("#price").load("get.php?make=" + $("#make").val() + "&model=" + $("#model").val() + "&submodel=" + $("#submodel").val());
    }

</script>

<div class="panel">
    <div class="panel-header">


        <h2 id="_default"><i class="icon-layers on-left"></i><b>Create Purchase Request</b></h2>
    </div>
    <div class="panel-content">
        <form method="POST" action="#" class="no-padding bordered" >
            <div class="grid">
                <fieldset>

                    <legend></legend>
                    <legend class="fg-red" style="display: <?php
if ($isErr === true) {
    echo 'block';
} else {
    echo 'none';
}
?>">

                        <p class="fg-red"><?php echo $errMsg; ?></p>
                    </legend>
                    <input type="hidden" name="pid" value="<?php echo $pid; ?>">
                    <div class="row">
                        <div class="span3">
                            <label class="subheader-secondary"><b>Select Make</b></label>
                        </div>
                        <div class="span1">
                            <div class="input-control select size3 <?php
                    if ($err['make']) {
                        echo 'error-state';
                    }
?>">

                                <select name="make" id="make" onchange="makeChange()">
                                    <option value="0">Please Choose Option</option>
                                    <?php
                                    $query = "SELECT Distinct Make as make FROM car";
                                    $stmt = $conn->prepare($query);
                                    $stmt->execute();
                                    $makes = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                    foreach ($makes as $make) {
                                        echo '<option value="' . $make['make'] . '">' . $make['make'] . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="span3 offset3">
                            <label class="subheader-secondary"><b>Select Model</b></label>
                        </div>
                        <div class="span1">
                            <div class="input-control select size3 <?php
                                    if ($err['model']) {
                                        echo 'error-state';
                                    }
                                    ?>">

                                <select name="model" id="model" onchange="modelChange()">
                                    <option value="0">Please Choose Option</option>                                
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="span3">
                            <label class="subheader-secondary"><b>Select Class/Group</b></label>
                        </div>
                        <div class="span1">
                            <div class="input-control select size3 <?php
                            if ($err['submodel']) {
                                echo 'error-state';
                            }
                                    ?>">

                                <select name="submodel" id="submodel" onchange="subChange()">
                                    <option value="0">Please Choose Option</option>         
                                </select>
                            </div>
                        </div>

                        <div class="span3 offset3">
                            <label class="subheader-secondary"><b>Select Body Type</b></label>
                        </div>

                        <div class="span1">
                            <div class="input-control select size3 <?php
                            if ($err['year']) {
                                echo 'error-state';
                            }
                                    ?>">

                                <select name="year">
                                    <option value="0">Please Choose Option</option>
                                    <option value="Sedan">Sedan</option>
                                    <option value="SUV">SUV</option>
                                    <option value="Crossover">Crossover</option>
                                    <option value="Truck">Truck</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="span3">
                            <label class="subheader-secondary"><b>Select Year</b></label>
                        </div>
                        <div class="span1">
                            <div class="input-control select size3 <?php
                            if ($err['body']) {
                                echo 'error-state';
                            }
                                    ?>">

                                <select name="body">
                                    <option value="0">Please Choose Option</option>
                                    <option>2015</option>
                                    <option>2014</option>
                                    <option>2013</option>
                                    <option>2012</option>
                                    <option>2011</option>
                                    <option>2010</option>
                                    <option>2009</option>
                                    <option>2008</option>
                                    <option>2007</option>
                                    <option>2006</option>
                                    <option>2005</option>
                                    <option>2004</option>
                                    <option>2003</option>
                                    <option>2002</option>
                                    <option>2001</option>     
                                </select>
                            </div>
                        </div>
                        <div class="span3 offset3">
                            <label class="subheader-secondary"><b>Select Color</b></label>
                        </div>
                        <div class="span1">
                            <div class="input-control select size3 <?php
                            if ($err['color']) {
                                echo 'error-state';
                            }
                                    ?>">

                                <select name="color">
                                    <option value="0">Please Choose Option</option>
                                    <option>Black</option>
                                    <option>White</option>
                                    <option>Silver</option>
                                    <option>Gray</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="span3">
                            <label class="subheader-secondary"><b>Dealer Price</b></label>
                        </div>
                        <div class="span1">
                            <label class="subheader-secondary" id='price'>$0.00</label>
                        </div>

                        <div class="span3 offset3">
                            <label class="subheader-secondary"><b>Enter Quantity</b></label>
                        </div>
                        <div class="span1">
                            <div class="input-control text size3 <?php
                            if ($err['quantity']) {
                                echo 'error-state';
                            }
                                    ?>" data-role="input-control">
                                <input type="text" name="quantity" placeholder="Please enter quantity">
                                <button class="btn-clear" tabindex="-1"></button>
                            </div>

                        </div>
                    </div>
                    <div class="row">
                        <div class="span3 offset3">
                            <input class="large" type="submit" name="reset" value="Clear  ">
                        </div>
                        <div class="span1 offset3">
                            <input class="large" type="submit" name="submit" value="Add   ">
                        </div>

                    </div>
                </fieldset>
            </div>
        </form>
        <form method="POST" action="processpur.php" onsubmit="msg()">
            <div class="grid">
                <fieldset>
                    <input type="hidden" name="pid" value="<?php echo $pid; ?>">
                    <legend></legend>
                    <legend style="display: none">Please provide following information to create purchase request</legend>
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
                        <div class="span3 offset3">
                            <!--<input class="large inverse" type="submit" name="cancel" value="Cancel">-->
                            <a class="button large inverse" href="home.php">Close</a>
                        </div>
                        <div class="span1 offset3">
                            <!--<a class="button large success" name="submit" onclick="msg()" >Next</a>-->
                            <input class="large success" name="submit" type="submit" value="Next  ">
                        </div>
                    </div>
                </fieldset>
            </div>
        </form>
    </div>
</div>
<?php
include 'foot.php';
?>