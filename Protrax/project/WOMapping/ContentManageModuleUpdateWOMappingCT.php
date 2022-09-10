<?php 
session_start();
require_once("../../ConfigDB.php");
require_once("../../src/Modules/ModuleLogin.php");
require_once("Modules/ModuleWOMapping.php");
require_once("../../Project/Report/Modules/ModuleReport.php");
date_default_timezone_set("Asia/Jakarta");
$DateNow = date("m/d/Y");
$YearNow = date("Y");
/*
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
    $RDataUserWebtrax = sqlsrv_fetch_array($QDataUserWebtrax);
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
*/
if($_SERVER['REQUEST_METHOD'] == "POST")
{   
    $ValWOMapping_ID = htmlspecialchars(trim($_POST['ValWOMapping_ID']), ENT_QUOTES, "UTF-8");
    $ValLocation = htmlspecialchars(trim($_POST['ValLocation']), ENT_QUOTES, "UTF-8");
    $ValClosedTime = htmlspecialchars(trim($_POST['ValClosedTime']), ENT_QUOTES, "UTF-8");
    $ValDataTemp = base64_encode(base64_encode(trim($ValWOMapping_ID)."#".$ValLocation));

    if($ValClosedTime == "OPEN")
    {
        $ValActionStatus = '<button class="btn btn-sm btn-dark" data-temp="'.$ValDataTemp.'" id="BtnCloseWO">CLOSE WO</button>';
    }
    else
    {
        $ValActionStatus = '<button class="btn btn-sm btn-dark" data-temp="'.$ValDataTemp.'" id="BtnReOpenWO">REOPEN WO</button>';
    }

    # data detail wo
    if($ValLocation == "PSM")
    {
        $QDataDetailWO = GET_DETAIL_WO_SELECTED_BY_ID_PSM($ValWOMapping_ID);
        $RDataDetailWO = sqlsrv_fetch_array($QDataDetailWO);
        $TotalTTWO = TOTAL_TIMETRACK_BY_WO_ID_PSM($ValWOMapping_ID);
        $TotalMachTWO = TOTAL_MACHTRACK_BY_WO_ID_PSM($ValWOMapping_ID);
        $TotalMaterialTWO = TOTAL_MATERIALTRACK_BY_WO_ID_PSM($ValWOMapping_ID);
        $TotalRawMaterialTWO = TOTAL_RAW_MATERIALTRACK_BY_WO_ID_PSM($ValWOMapping_ID);
        $TotalToolsUsageTWO = TOTAL_TOOLS_USAGE_TRACK_BY_WO_ID_PSM($ValWOMapping_ID);
    }
    if($ValLocation == "PSL")
    {
        $QDataDetailWO = GET_DETAIL_WO_SELECTED_BY_ID($ValWOMapping_ID,$linkMACHWebTrax);
        while($RDataDetailWO = sqlsrv_fetch_array($QDataDetailWO))
        {
            $QtyParent = trim($RDataDetailWO['QtyParent']);
        }
        $TotalTTWO = TOTAL_TIMETRACK_BY_WO_ID($ValWOMapping_ID,$linkMACHWebTrax);
        $TotalMachTWO = TOTAL_MACHTRACK_BY_WO_ID($ValWOMapping_ID,$linkMACHWebTrax);
        $TotalMaterialTWO = TOTAL_MATERIALTRACK_BY_WO_ID($ValWOMapping_ID,$linkMACHWebTrax);
        $TotalRawMaterialTWO = TOTAL_RAW_MATERIALTRACK_BY_WO_ID($ValWOMapping_ID,$linkMACHWebTrax);
        $TotalToolsUsageTWO = TOTAL_TOOLS_USAGE_TRACK_BY_WO_ID_PSM($ValWOMapping_ID,$linkMACHWebTrax);
    }

    ?>
<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label for="FieldLocation2" class="form-label fw-bold">Location</label>
            <input type="text" class="form-control form-control-sm" id="FieldLocation2" value="<?php echo $ValLocation; ?>" readonly>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="FieldWOID2" class="form-label fw-bold">WOMapping_ID</label>
            <input type="text" class="form-control form-control-sm" id="FieldWOID2" value="<?php echo $ValWOMapping_ID; ?>" readonly>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="FieldClosedTime2" class="form-label fw-bold">Closed Time</label>
            <input type="text" class="form-control form-control-sm" id="FieldClosedTime2" value="<?php echo $ValClosedTime; ?>" readonly>
        </div>
    </div>
    <div class="col-md-12"><hr></div>
    <div class="col-md-2">
        <div class="form-group">
            <label for="FieldTotalTT" class="form-label fw-bold">Total Time Tracking</label>
            <input type="text" class="form-control form-control-sm" id="FieldTotalTT" value="<?php echo trim($TotalTTWO); ?>" readonly>
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <label for="FieldTotalMachTracking" class="form-label fw-bold">Total Machine Tracking</label>
            <input type="text" class="form-control form-control-sm" id="FieldTotalMachTracking" value="<?php echo trim($TotalMachTWO); ?>" readonly>
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <label for="FieldTotalMaterialTracking" class="form-label fw-bold">Total Material Tracking</label>
            <input type="text" class="form-control form-control-sm" id="FieldTotalMaterialTracking" value="<?php echo trim($TotalMaterialTWO); ?>" readonly>
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <label for="FieldTotalRawMaterial" class="form-label fw-bold">Total Raw Material</label>
            <input type="text" class="form-control form-control-sm" id="FieldTotalRawMaterial" value="<?php echo trim($TotalRawMaterialTWO); ?>" readonly>
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <label for="FieldTotalToolsUsage" class="form-label fw-bold">Total Tools Usage</label>
            <input type="text" class="form-control form-control-sm" id="FieldTotalToolsUsage" value="<?php echo trim($TotalToolsUsageTWO); ?>" readonly>
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <label for="FieldQtyClosed" class="form-label fw-bold">Qty Closed</label>
            <input type="text" class="form-control form-control-sm" id="FieldQtyClosed" value="<?php echo $QtyParent; ?>">
        </div>
    </div>
    <div class="col-md-12"><hr></div>
    <div class="col-md-12 d-grid mt-2">
        <?php echo $ValActionStatus; ?>
    </div>
    <div class="col-md-12 pt-2"><div id="InfoUpdateCT"></div></div>
</div>
    <?php
    
}
else
{
    echo "";    
}
?>