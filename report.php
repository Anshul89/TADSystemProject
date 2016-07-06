
<?php
error_reporting(E_ERROR);
$user = 'Anshul Pratap Singh';
$title = 'Inventory Report';
include 'head.php';
require_once "formvalidator.php";
require_once "DBConn.php";
$err = false;
$showForm = true;
$showReport = FALSE;
$isStart;

if (isset($_POST['submit'])) {
    $validator = new FormValidator();
    $validator->addValidation("sdate", "req", "1");
    $validator->addValidation("edate", "req", "2");
    if ($validator->ValidateForm()) {
        $showForm = true;
        $showReport = TRUE;
        $sdate = $_POST['sdate'];
        $edate = $_POST['edate'];
        $type = $_POST['r1'];
        $stmt->execute();
    } else {
        $err = true;
        $error_hash = $validator->GetErrors();
        foreach ($error_hash as $inpname => $inp_err) {
            if ($inp_err == "1") {
                $isStart = true;
            } else {
                $isStart = false;
            }
        }
    }
}
if ($showForm) {
    ?>
    <div class="panel">
        <div class="panel-header">
            <h2 id="_default"><i class="icon-box-add on-left"></i>Generate Inventory Report</h2>
        </div>
        <div class="panel-content">
            <form class="no-padding bordered" method="POST" action="#">
                <div class="grid">
                    <fieldset>

                        <legend></legend>
                        <legend class="fg-red" style=<?php
                        if ($err === true) {
                            echo '"display: block"';
                        } else {
                            echo '"display: none"';
                        }
                        ?>>Error: Both dates are required</legend>
                        <div class="row">
                            <div class="span3">
                                <label class="subheader-secondary">Start of Inventory Date</label>
                            </div>
                            <div class="span1">
                                <div class="input-control text size3 <?php
                                if ($err && $isStart) {
                                    echo 'error-state';
                                }
                                ?>" data-role="datepicker" data-format="yyyy-mm-dd" data-effect="slide" data-week-start="1" >
                                    <input type="text" id="sdate" name = "sdate">
                                    <button class="btn-date"></button>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="span3">
                                <label class="subheader-secondary">End of Inventory Date</label>
                            </div>
                            <div class="span1">
                                <div class="input-control text size3 " data-format="yyyy-mm-dd" data-role="datepicker" data-effect="slide" data-week-start="1" >
                                    <input class="<?php
                                    if ($err && $isEnd) {
                                        echo 'error-state';
                                    }
                                    ?>" type="text" id="edate" name="edate">
                                    <button class="btn-date"></button>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="span3">
                                <label class="subheader-secondary">Car Type</label>
                            </div>
                            <div class="span4">
                                <div class="input-control radio default-style" data-role="input-control">
                                    <label>
                                        <input type="radio" name="r1" value="all" checked />
                                        <span class="check"></span>
                                        All
                                    </label>
                                </div>
                                <div class="input-control radio  default-style" data-role="input-control">
                                    <label>
                                        <input type="radio" name="r1" value="New"/>
                                        <span class="check"></span>
                                        New Car
                                    </label>
                                </div>
                                <div class="input-control radio  default-style" data-role="input-control">
                                    <label>
                                        <input type="radio" name="r1" value="Used"/>
                                        <span class="check"></span>
                                        Used Car
                                    </label>
                                </div>

                            </div>
                        </div>

                        <div class="row">
                            <div class="span3">
                                <input class="large" type="reset" value="Reset">
                            </div>
                            <div class="span1 offset3">
                                <input class="large primary" type="submit" name="submit" value="Generate">
                            </div>
                        </div>

                    </fieldset>
                </div>
                </br></br>
            </form>
            <?php
            if ($showReport) {
                echo '</br>';
                echo '<legend></legend>';
                $conn = Connection::getConnection();
                $query = "SELECT vin, ctype, make, model, submodel, cyear, body, color, stock, cdate FROM Inventory  ";
                $query1 = "SELECT distinct cdate FROM Inventory ";
                if (isset($sdate) && isset($edate) && isset($type)) {
                    $query1 = $query1 . "WHERE cdate >= '" . $sdate . "' AND cdate <= '" . $edate . "'";
                }

                if (strcmp($type, "all") != 0) {
                    $query1 = $query1 . " AND ctype = '" . $type . "'";
                }
                $query1 = $query1 . " Order By cdate";
                $stmt = $conn->prepare($query1);
                $stmt->execute();
                $arr = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $max = $stmt->rowCount();
                $curdate;
                ?>
                <div class="panel-header">                
                    <h2 class="text-center" id="_default"><i class="icon-database on-right"></i>  Inventory Stock Report</h2>
                    <h4 class="text-center" id="_default">From <?php echo $sdate ?> to <?php echo $edate ?></h4>

                    <legend></legend>
                </div>

                <div class="listview-outlook" data-role="listview" style="margin-top: 20px">                      
                    <?php
                    foreach ($arr as $col) {
                        $cdate = $col['cdate'];
                        echo '<div class = "list-group ">';
                        echo '<a href = "" class = "group-title h3"><p class = "subheader">' . $cdate . '</p></a>';
                        echo '<div class = "group-content">';
                        echo '<table class="table bordered dataTable">';
                        echo '<tr class="info">';
                        echo '<td>Car Type</td>';
                        echo '<td>VIN#</td>';
                        echo '<td>Stock#</td>';
                        echo '<td>Make</td>';
                        echo '<td>Model</td>';
                        echo '<td>Sub-model</td>';
                        echo '<td>Year</td>';
                        echo '<td>Body</td>';
                        echo '<td>Color</td>';
                        echo '</tr>';
                        $temp = $query . " WHERE cdate = '" . $cdate . "'";
                        if (strcmp($type, "all") != 0) {
                            $temp = $temp . " AND ctype = '" . $type . "'";
                        }
                        $temp = $temp . " Order By cdate";
                        $stmt = $conn->prepare($temp);
                        $stmt->execute();
                        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        foreach ($data as $row) {
                            echo '<tr>';
                            echo '<td>' . $row['ctype'] . '</td>';
                            echo '<td>' . $row['vin'] . '</td>';
                            echo '<td>' . $row['stock'] . '</td>';
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
                <?php
            }
            echo '</div>';
            include 'foot.php';
        }
        ?>
    </div>
