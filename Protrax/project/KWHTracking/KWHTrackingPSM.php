<?php
require_once("project/KWHTracking/Modules/ModuleKWHTracking.php");
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

# data session
$FullName = strtoupper(base64_decode($_SESSION['FullNameUserProTrax']));
$UserNameSession = base64_decode(base64_decode($_SESSION['UIDProTrax']));
# data protrax user
$BolProdAcc = false;
$QDataUserWebtrax = GET_DATA_LOGIN_BY_USERNAME_ONLY($UserNameSession,$linkHRISWebTrax);
if(mssql_num_rows($QDataUserWebtrax) > 0)
{
    $RDataUserWebtrax = mssql_fetch_assoc($QDataUserWebtrax);
    $TypeUser = trim($RDataUserWebtrax['TypeUser']);
    $_SESSION['LoginMode'] = base64_encode($TypeUser);
    $AccessLogin = base64_decode($_SESSION['LoginMode']);   
}
else # kondisi tidak terdaftar di protrax user & akan di set sebagai employee dan hak akses ke bagian produksi saja
{
    $_SESSION['LoginMode'] = base64_encode("Employee");
    $AccessLogin = base64_decode($_SESSION['LoginMode']);
    $BolProdAcc = true;
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

if(isset($_SESSION['ImportKWHTrackingPSM']))
{
    echo $_SESSION['ImportKWHTrackingPSM'];
    unset($_SESSION['ImportKWHTrackingPSM']);
}
?>
<?php //<!--<script src="lib/datetimepicker-master/jquery.js"></script>--> ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.full.min.js" integrity="sha512-AIOTidJAcHBH2G/oZv9viEGXRqDNmfdPVPYOYKGy3fti0xIplnlgMHUGfuNRzC6FkzIo0iIxgFnr9RikFxK+sw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<link rel="stylesheet" type="text/css" href="lib/datetimepicker-master/jquery.datetimepicker.css"/>
<script src="project/KWHTracking/lib/LibFormKWHTrackingPSM.js?no=<?php echo base64_encode(date("mdyHis")); ?>"></script>
<div class="row">
    <div class="col-md-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-detail ">
                <li class="breadcrumb-item"><a href="home.php">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Building Management : Manage Electricity Usage Semarang</li>
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