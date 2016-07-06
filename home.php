
<?php

error_reporting(E_ERROR);
$title = 'Home';
require_once 'DBConn.php';
$conn = Connection::getConnection();
$query = "delete from session where skey = 'pid'";
$stmt = $conn->prepare($query);
$stmt->execute();
include 'head.php';
?>
<script>
    function codeAddress() {

        var oldURL = document.referrer;
        if (oldURL.indexOf("purchase1.php") > -1) {
            swal("Purchase Request Cancelled", "The purchase request is not created", "error");
        }
    }
    window.onload = codeAddress;
</script>
<div class="main-content no-overflow margin20 padding20">
    <div class="panel">
        <div class="panel-header">
            <h2 id="_default"><i class="icon-loading on-left"></i><b>Welcome</b></h2>
        </div>
        <div class="panel-content">

        </div>
    </div>
</div>
<?php

include 'foot.php';
?>