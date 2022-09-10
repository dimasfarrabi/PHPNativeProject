<?php 
session_start();
require_once("../../ConfigDB.php");
require_once("../../src/Modules/ModuleLogin.php");
require_once("Modules/ModuleLabourHour.php");
date_default_timezone_set("Asia/Jakarta");
$DateNow = date("m/d/Y");
$YearNow = date("Y");

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
if($RDataUserWebtrax['MnAdmin'] != "1")  
{
    ?>
    <script language="javascript">
        window.location.replace("https://protrax.formulatrix.com/");
    </script>
    <?php
    exit();
}

if($_SERVER['REQUEST_METHOD'] == "POST")
{   
    $DataIDEnc = htmlspecialchars(trim($_POST['DataID']), ENT_QUOTES, "UTF-8");
    $Token = $DataIDEnc;
    $DataIDEnc = base64_decode(base64_decode($DataIDEnc));
    $DataID = str_replace("TokenID:","",$DataIDEnc);
    $ArrDataID = explode("#",$DataID);
    $Employee = trim($ArrDataID[0]);
    $ID = trim($ArrDataID[1]);
    # detail data
    $QData = DETAIL_DATA_LABOUR_HOUR_BY_ID($ID,$linkMACHWebTrax);
    $RData = mssql_fetch_assoc($QData);
    $ValEmployee = trim($RData['EmployeeName']);
    $ValWOID = trim($RData['WOMapping_ID']);
    $ValWOC = trim($RData['WOChild']);
    $ValExpense = trim($RData['ExpenseAllocation']);
    $ValClosedTime = trim($RData['ClosedTime']);
    $ValTotal = trim($RData['SumEstimateTime']);
    $ValTotal = sprintf('%.3f',floatval(trim($ValTotal)));
    
?>
<div class="row">
    <div class="col-md-5 mb-2">
        <label for="TextEmployee" class="form-label fw-bold">Employee</label>
        <input type="text" class="form-control form-control-sm" id="TextEmployee" value="<?php echo $ValEmployee; ?>" disabled>
    </div>
</div>
<div class="row">
    <div class="col-md-3 mb-2">
        <label for="TextWOID" class="form-label fw-bold">WO ID</label>
        <input type="text" class="form-control form-control-sm" id="TextWOID" value="<?php echo $ValWOID; ?>" disabled>
    </div>
    <div class="col-md-3 mb-2">
        <label for="TextWOC" class="form-label fw-bold">WO Child</label>
        <input type="text" class="form-control form-control-sm" id="TextWOC" value="<?php echo $ValWOC; ?>" disabled>
    </div>
    <div class="col-md-3 mb-2">
        <label for="TextExpense" class="form-label fw-bold">Expense</label>
        <input type="text" class="form-control form-control-sm" id="TextExpense" value="<?php echo $ValExpense; ?>" disabled>
    </div>
    <div class="col-md-3 mb-2">
        <label for="TextClosedTime" class="form-label fw-bold">Closed Time</label>
        <input type="text" class="form-control form-control-sm" id="TextClosedTime" value="<?php echo $ValClosedTime; ?>" disabled>
    </div>
</div>
<div class="row">
    <div class="col-md-2 mb-2">
        <label for="TextTotal" class="form-label fw-bold">Total</label>
        <input type="text" class="form-control form-control-sm" id="TextTotal" value="<?php echo $ValTotal; ?>">
    </div>
</div>
<div class="row">
    <div class="col-md-12 mb-2">
        <button class="btn btn-dark btn-sm btn-labeled" id="BtnEditLabourHour" data-datatoken="<?php echo $Token; ?>">Edit Labour Hour</button>
    </div>
    <div class="col-md-12 mb-2">
        <div id="TempProcess"></div>
    </div>  
</div>
    <?php
}
else
{
    echo "";    
}
?>