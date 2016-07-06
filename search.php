
<?php
error_reporting(E_ERROR);
$user = 'Anshul Pratap Singh';
$title = 'Search Perchase Request';
include 'head.php';
require_once 'DBConn.php';
$conn = Connection::getConnection();
$query = "SELECT * FROM session where skey = 'user'";
$stmt = $conn->prepare($query);
$stmt->execute();
$rec = $stmt->fetchAll(PDO::FETCH_ASSOC);
$val = $rec[0]['value'];
$st = ($val == 'tucker' ? 'GM' : 'Mr. Tucker');
$query = "SELECT P.Pid as Pid,Pdate,(SELECT SUM(Quantity) FROM Item I1 where I1.pid =  P.pid)as Car, "
        . "(SELECT SUM(Total) FROM Item I2 where I2.pid =  P.pid)as Total, Status, Ldate FROM Purchase P WHERE Status IN ('Pending','Approved By " . $st . "')";
$stmt = $conn->prepare($query);
$stmt->execute();
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<script src="js/jquery/jquery.dataTables.js"></script>
<div class="panel">
    <div class="panel-header">
        <h2 id="_default"><i class="icon-search on-left"></i><b>Select Purchase Request</b></h2>
    </div>
    <div class="panel-content">
        <div class="grid">
            <div class="row">

                <table id="example" class="table bordered dataTable">
                    <thead>
                        <tr class="info">

                            <th class="text-center"><p class="subheader"><b>PR#</b></p></th>
                    <th class="text-center"><p class="subheader"><b>Date</b></p></th>
                    <th class="text-center small-size small"><p class="subheader"><b>Total Car</b></p></th>
                    <th class="text-center"><p class="subheader"><b>Total Price</b></p></th>
                    <th class="text-center"><p class="subheader"><b>Status</b></p></th>
                    <th class="text-center"><p class="subheader"><b>Last Update</b></p></th>            
                    <th class="text-center"></th>
                    </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (isset($data)) {
                            foreach ($data as $row) {
                                ?>
                                <tr>
                                    <td class="text-center"><p class="item-title-secondary"><?php echo $row['Pid']; ?></p></td>
                                    <td class="text-center"><p class="item-title-secondary"><?php echo $row['Pdate']; ?></p></td>
                                    <td class="text-center"><p class="item-title-secondary"><?php echo $row['Car']; ?></p></td>
                                    <td class="text-center"><p class="item-title-secondary">$<?php echo $row['Total']; ?></p></td>
                                    <td class="text-center"><p class="item-title-secondary"><?php echo $row['Status']; ?></p></td>
                                    <td class="text-center"><p class="item-title-secondary"><?php echo $row['Ldate']; ?></p></td>
                                    <td class="text-center"><p class="item-title-secondary"><a href="approve.php?pid=<?php echo $row['Pid']; ?>">Select</a></p></td>
                                </tr>
                                <?php
                            }
                        }
                        ?>
                    </tbody>

                    <tfoot></tfoot>
                </table>
                <script>
                    function filterGlobal() {
                        $('#example').DataTable().search(
                                $('#global_filter').val(),
                                $('#global_regex').prop('checked'),
                                $('#global_smart').prop('checked')
                                ).draw();
                    }

                    function filterColumn(i) {
                        $('#example').DataTable().column(i).search(
                                $('#col' + i + '_filter').val(),
                                $('#col' + i + '_regex').prop('checked'),
                                $('#col' + i + '_smart').prop('checked')
                                ).draw();
                    }

                    $(document).ready(function () {
                        $('#example').dataTable();

                        $('input.global_filter').on('keyup click', function () {
                            filterGlobal();
                        });

                        $('input.column_filter').on('keyup click', function () {
                            filterColumn($(this).parents('tr').attr('data-column'));
                        });
                    });
                </script>

            </div>
            <div class="row">
                <div class="span3 offset8">
                                <!--<input class="large inverse" type="submit" name="cancel" value="Cancel">-->
                    <a class="button large inverse" href="home.php">Close</a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
include 'foot.php';
?>