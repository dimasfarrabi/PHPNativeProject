<?php 
require_once("project/QualityPoint/Modules/ModuleQualityPoint.php"); 
require_once("project/CostTracking/Modules/ModuleCostTracking.php");
date_default_timezone_set("Asia/Jakarta");
$YearNow = date("Y");
$DateNow = date("m/d/Y");
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
    if($RDataUserWebtrax['MnAdmin'] != "1" && $RDataUserWebtrax['MnCostTracking'] != "1")  
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
    if($RDataUserWebtrax['MnCostTracking'] != "1")
    {
        ?>
        <script language="javascript">
            window.location.replace("https://protrax.formulatrix.com/");
        </script>
        <?php
        exit();
    }
}
?><script src="project/QualityPoint/lib/LibQualityPoint.js?no=<?php echo base64_encode(date("mdyHis")); ?>"></script>
<div class="row">
    <div class="col-md-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-detail ">
                <li class="breadcrumb-item"><a href="home.php">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Cost Tracking : Manage Quality Point</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <label for="InputClosedTime" class="form-label fw-bold">Season</label>
    </div>
    <div class="col-sm-2">        
        <div class="mb-1">
            <select class="form-select" id="InputClosedTime"><?php 
                $QListClosedTime = GET_LIST_CLOSEDTIME_NOT_OPEN("",$linkMACHWebTrax);
                while($RListClosedTime = mssql_fetch_assoc($QListClosedTime))
                {
                    $ClosedTime = $RListClosedTime['ClosedTime'];
                    ?>
                    <option><?php echo $ClosedTime; ?></option>
                    <?php
                }                
            ?></select>
        </div>
    </div>
    <div class="col-sm-10"><button class="btn btn-dark btn-labeled" id="BtnViewProject">View Data</button> <button class="btn btn-dark btn-labeled" id="BtnDownload">Download Quality Point per Season</button></div>
    <div class="col-sm-12"><hr></div>
    <div class="col-md-3">
        <div class="row" id="ListQuote"></div>
        <span id="AccQ" class="InvisibleText"></span>
    </div>
    <div class="col-md-9">
        <div class="row" id="ResultCategory"></div>
        <div class="row" id="ActualDetails"></div>
        <span id="TempDataTime" class="InvisibleText"></span>
        <span id="TempQuote" class="InvisibleText"></span>
        <span id="TempSelect" class="InvisibleText"></span>
    </div>
</div>