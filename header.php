<div class="clearfix bg-black">    
    <a class="place-left" href="#" title="">
        <h1 class="fg-blue"><span class="icon-grid-view"></span><strong> Tucker Auto Dealership System</strong></h1>
    </a>
</div>
<nav class="navigation-bar dark">
    <nav class="navigation-bar-content fg-white">            
        <div class="element">                
            <a class="dropdown-toggle" href="#"><strong>Purchase Request</strong></a>
            <ul class="dropdown-menu dark fg-white" data-role="dropdown">
                <li><a href="purchase.php">Create New</a></li>
                <li><a href="search.php">Approve Existing</a></li>                    
            </ul>
        </div>            
        <span class="element-divider"></span>
        <div class="element">                
            <a class="dropdown-toggle" href="#"><strong>Create Report</strong></a>
            <ul class="dropdown-menu dark fg-white" data-role="dropdown">
                <li><a href="report.php">Inventory Report</a></li>
            </ul>
        </div>
        <span class="element-divider"></span>

        <div class="element place-right">
            <a class="dropdown-toggle" href="#">
                <span class="icon-locked-2"></span>
            </a>
            <ul class="dropdown-menu dark fg-white place-right" data-role="dropdown">
                <li><a href="#">Log Out</a></li>                    
            </ul>
        </div>                                    
        <button class="element image-button image-left place-right">
            <strong>
                <?php
                if (!isset($user)) {
                    $user = "Guest";
                }
                echo $user;
                ?>       
            </strong>
        </button>
    </nav>
</nav>

<!--</div> -->

