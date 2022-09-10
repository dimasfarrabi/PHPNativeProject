<?php
require_once("project/Security/Modules/ModuleSecurity.php");
date_default_timezone_set("Asia/Jakarta");
$YearNow = date("Y");
$DateNow = date("m/d/Y");
$Yesterday = date("m/d/Y",strtotime("-1 day"));
if(!session_is_registered("UIDProTrax"))
{
    ?>
    <script language="javascript">
        window.location.replace("https://protrax.formulatrix.com/");
    </script>
    <?php
    exit();
}

if((trim($AccessLogin) != "Manager"))
{
    if($RDataUserWebtrax['MnAdmin'] != "1")  
    {
        ?>
        <script language="javascript">
            window.location.replace("https://protrax.formulatrix.com/");
        </script>
        <?php
        exit();
    }
}
else
{
    if($RDataUserWebtrax['MnSecurity'] != "1")
    {
        ?>
        <script language="javascript">
            window.location.replace("https://protrax.formulatrix.com/");
        </script>
        <?php
        exit();
    }
}

$QDataGuest = GET_DATA_LOGIN_BY_USERNAME_ONLY($UserNameSession,$linkHRISWebTrax);
$RDataGuest = mssql_fetch_assoc($QDataGuest);
$LocCompany = $RDataGuest['Company'];
# check data before
$QCheckData1 = GET_DATA_KWH_TRACKING_BEFORE($Yesterday,$LocCompany,$linkHRISWebTrax);
$QCheckData2 = GET_DATA_KWH_TRACKING_BEFORE_SECURITY($Yesterday,$LocCompany,$linkHRISWebTrax);
$RowCheck1 = mssql_num_rows($QCheckData1);
$RowCheck2 = mssql_num_rows($QCheckData2);
$TotalCheckRow = $RowCheck1 + $RowCheck2;
if($TotalCheckRow == 0)
{
    $FormInput = '';
    $BtnAdd = '';
}
else
{
    if($RowCheck1 != "0")
    {
        $RCheckData1 = mssql_fetch_assoc($QCheckData1);
        $ValKWH = trim($RCheckData1['KWH']);
        $FormInput = ' value="'.$ValKWH.'" disabled';
        $BtnAdd = ' disabled';
    }
    if($RowCheck2 != "0")
    {
        $RCheckData2 = mssql_fetch_assoc($QCheckData2);
        $ValKWH = trim($RCheckData2['Usage']);
        $FormInput = ' value="'.$ValKWH.'" disabled';
        $BtnAdd = ' disabled';
    }
}

if(isset($_SESSION['ImportKWHTrackingFI']))
{
    echo $_SESSION['ImportKWHTrackingFI'];
    unset($_SESSION['ImportKWHTrackingFI']);
}
?>
<?php //<!--<script src="lib/datetimepicker-master/jquery.js"></script>--> ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.full.min.js" integrity="sha512-AIOTidJAcHBH2G/oZv9viEGXRqDNmfdPVPYOYKGy3fti0xIplnlgMHUGfuNRzC6FkzIo0iIxgFnr9RikFxK+sw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<link rel="stylesheet" type="text/css" href="lib/datetimepicker-master/jquery.datetimepicker.css"/>
<script src="project/KWHTracking/lib/LibFormKWHTrackingFI.js?no=<?php echo base64_encode(date("mdyHis")); ?>"></script>
<div class="row">
    <div class="col-md-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-detail ">
                <li class="breadcrumb-item"><a href="home.php">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Building Management : Manage Electricity Usage Salatiga Old</li>
            </ol>
        </nav>
    </div>
</div>
<div class="row">
    <div class="col-md-12">&nbsp;</div>
    <div class="col-sm-12 div-content-top">
        <div class="row" id="content-div">
            <div class="col-md-4 d-grid col-btn">
                <button type="button" class="btn btn-secondary btn-bigger btn-labeled" data-button-title="ImportData">
                <span class="btn-label"><i class="bi bi-download"></i></span> IMPORT DATA</button>
            </div>
            <div class="col-md-4 d-grid col-btn">
                <button type="button" class="btn btn-secondary btn-bigger btn-labeled" data-button-title="ViewData">
                <span class="btn-label"><i class="bi bi-list-task"></i></span> VIEW DATA</button>
            </div>
            <div class="col-md-4 d-grid col-btn">
                <button type="button" class="btn btn-secondary btn-bigger btn-labeled" data-button-title="GenerateData">
                <span class="btn-label"><i class="bi bi-gear-fill"></i></span> GENERATE DATA</button>
            </div>
        </div>
    </div>
</div>