<?php
require_once("project/KWHTracking/Modules/ModuleKWHTracking.php"); 
date_default_timezone_set("Asia/Jakarta");
$YearNow = date("Y");
$DateNow = date("m/d/Y");
if(!session_is_registered("UIDWebTrax"))
{
    ?>
    <script language="javascript">
        window.location.replace("https://webtrax.formulatrix.com/");
    </script>
    <?php
    exit();
}
/*if($AccessLogin != "Administrator")
{
    ?>
    <script language="javascript">
        window.location.href = "home.php";
    </script>
    <?php
    exit();
}*/
if(($AccessLogin != "Administrator") && ($AccessLogin != "Guest" || $StatusGuestAsAdmin != "1"))
{
    ?>
    <script language="javascript">
        window.location.href = "home.php";
    </script>
    <?php
    exit();
}

if(isset($_SESSION['ImportKWHTrackingPSM']))
{
    echo $_SESSION['ImportKWHTrackingPSM'];
    unset($_SESSION['ImportKWHTrackingPSM']);
}
?><script src="project/kwhtracking/lib/libformkwhtrackingpsm.js?no=<?php echo base64_encode(date("mdyHis")); ?>"></script>
<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb breadcrumb-detail">
            <li><a href="home.php">Home</a></li>
            <li class="active"><a href="home.php?link=11">Administration : Manage Electricy Usage Semarang</a></li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-md-12">&nbsp;</div>
    <div class="col-sm-12 div-content-top">
        <div class="row" id="content-div">
            <div class="col-md-12">
                <div class="col-md-4 col-btn">
                    <button class="btn btn-default btn-bigger btn-block btn-labeled" data-button-title="ImportData">
                    <span class="btn-label"><i class="glyphicon glyphicon-import"></i></span> Import Data</button>
                </div>
                <div class="col-md-4 col-btn">
                    <button class="btn btn-default btn-bigger btn-block btn-labeled" data-button-title="ViewData">
                    <span class="btn-label"><i class="glyphicon glyphicon-th-list"></i></span> View Data</button>
                </div>
                <div class="col-md-4 col-btn">
                    <button class="btn btn-default btn-bigger btn-block btn-labeled" data-button-title="GenerateData">
                    <span class="btn-label"><i class="glyphicon glyphicon-cog"></i></span> Generate Data</button>
                </div>
            </div>
        </div>
    </div>
</div>
