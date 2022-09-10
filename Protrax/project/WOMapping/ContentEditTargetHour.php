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
*/
if($_SERVER['REQUEST_METHOD'] == "POST")
{   
    $ValWOMapping_ID = htmlspecialchars(trim($_POST['ValWOMapping_ID']), ENT_QUOTES, "UTF-8");
    $ValLocation = htmlspecialchars(trim($_POST['ValLocation']), ENT_QUOTES, "UTF-8");
    $ValClosedTime = htmlspecialchars(trim($_POST['ValClosedTime']), ENT_QUOTES, "UTF-8");
    $ValDataTemp = base64_encode(base64_encode(trim($ValWOMapping_ID)."#".$ValLocation));
    if($ValLocation == "PSM")
    {
        $QDataDetailWO = GET_DETAIL_WO_SELECTED_BY_ID_PSM($ValWOMapping_ID);
        while($RDataDetailWO = sqlsrv_fetch_array($QDataDetailWO))
        {
            $TargetHour = trim($RDataDetailWO['TargetCost']);
        }
    }
    if($ValLocation == "PSL")
    {
        $QDataDetailWO = GET_DETAIL_WO_SELECTED_BY_ID($ValWOMapping_ID,$linkMACHWebTrax);
        while($RDataDetailWO = sqlsrv_fetch_array($QDataDetailWO))
        {
            $TargetHour = trim($RDataDetailWO['TargetCost']);
        }
    }
?>
<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label for="InputTargetHour" class="form-label fw-bold">Input Target Hour</label>
            <input type="text" class="form-control form-control-sm" id="InputTargetHour" value="<?php echo $TargetHour; ?>">
        </div>
    </div>
    <div class="col-md-4" style="margin-top:28px;">
        <button class="btn btn-sm btn-dark" data-temp="<?php echo $ValDataTemp; ?>" id="BtnUpdateTargetHour">UPDATE</button>
    </div>
    <div class="col-md-4" style="margin-top:28px;">
        <div id="InfoUpdateHour"></div>
    </div>
</div>

<?php
}
?>