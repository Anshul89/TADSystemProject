
<?php
error_reporting(E_ERROR);
$user = 'Anshul Pratap Singh';
$title = 'Inventory Report';
include 'head.php';
require_once "DBConn.php";
$err = false;
?>
<div class="panel" id="ignorePDF">
    <div class="panel-header">
        <h2 id="_default"><i class="icon-printer on-left"></i><b>Generate Inventory Report</b></h2>
    </div>
    <div class="panel-content">
        <form class="no-padding bordered" method="POST" action="disp.php">
            <div class="grid">
                <fieldset>

                    <legend></legend>
                    <div class="row">
                        <div class="span4">
                            <label class="subheader-secondary"><b>Start of Acquire Date[Optional]</b></label>
                        </div>
                        <div class="span1">
                            <div class="input-control text size3" data-role="datepicker" data-format="yyyy-mm-dd" data-effect="slide" data-week-start="1" >
                                <input type="text" id="sdate" name = "sdate">
                                <button class="btn-date"></button>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="span4">
                            <label class="subheader-secondary"><b>End of Acquire Date[Optional]</b></label>
                        </div>
                        <div class="span1">
                            <div class="input-control text size3 " data-format="yyyy-mm-dd" data-role="datepicker" data-effect="slide" data-week-start="1" >
                                <input class="" type="text" id="edate" name="edate">
                                <button class="btn-date"></button>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="span4">
                            <label class="subheader-secondary"><b>Car Type</b></label>
                        </div>
                        <div class="span6">
                            <div class="input-control radio default-style" data-role="input-control">
                                <label class="item-title">
                                    <input type="radio" name="r1" value="all" checked />
                                    <span class="check"></span>
                                    All  
                                </label>
                            </div>
                            <div class="input-control radio  default-style" data-role="input-control">
                                <label class="item-title offset1">
                                    <input type="radio" name="r1" value="New"/>
                                    <span class="check"></span>
                                    New Car
                                </label>
                            </div>
                            <div class="input-control radio  default-style" data-role="input-control">
                                <label class="item-title offset1">
                                    <input type="radio" name="r1" value="Used"/>
                                    <span class="check"></span>
                                    Used Car
                                </label>
                            </div>

                        </div>
                    </div>
                    <div class="row">
                    </div>
                    <div class="row">
                    </div>
                    <div class="row">
                        <div class="span1 offset2">
                            <input class="large inverse" type="reset" value="Reset">
                        </div>
                        <div class="span1 offset2">
                            <a class="button large inverse" href="home.php">Close</a>
                        </div>
                        <div class="span1 offset2">
                            <input class="large success" type="submit" name="submit" value="Generate">
                        </div>
                    </div>

                </fieldset>
            </div>            
        </form>
    </div>
</div>

<?php
//    echo '</div>';
include 'foot.php';
?>
</div>
