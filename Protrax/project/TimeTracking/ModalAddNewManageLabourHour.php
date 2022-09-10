<?php 
session_start();
require_once("../../ConfigDB.php");
require_once("../../src/Modules/ModuleLogin.php");
require_once("Modules/ModuleLabourHour.php");
require_once("../../project/CostTracking/Modules/ModuleCostTracking.php");
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
    $Employee = str_replace("ID:","",$DataIDEnc);
    
?>
<div class="row">
    <div class="col-md-3 mb-2">
        <label for="TextEmployee" class="form-label fw-bold">Employee</label>
        <input type="text" class="form-control form-control-sm" id="TextEmployee" value="<?php echo $Employee; ?>" disabled>
    </div>
    <div class="col-md-3 mb-2">
        <label for="TextCategory" class="form-label fw-bold">Category</label>
        <select class="form-select form-select-sm" aria-label="Select Category" id="TextCategory">
            <?php
                $QListQuoteCategory = GET_LIST_QUOTE_CATEGORY("",$linkMACHWebTrax);
                while($RListQuoteCategory = mssql_fetch_assoc($QListQuoteCategory))
                {
                    $QuoteCategory = $RListQuoteCategory['QuoteCategory'];
                    ?>
                    <option><?php echo $QuoteCategory; ?></option>
                    <?php
                }       
            ?>
        </select>
    </div>
    <div class="col-md-3 mb-2">
        <label for="TextSeason" class="form-label fw-bold">Season</label>
        <select class="form-select form-select-sm" aria-label="Select Season" id="TextSeason"><option>OPEN</option>
            <?php
            $QListClosedTime = GET_ALL_TYPE_CLOSED_TIME_LABOUR_HOUR_DESC($linkMACHWebTrax);
            while($RListClosedTime = mssql_fetch_assoc($QListClosedTime))
            {
                echo '<option>'.trim($RListClosedTime['ClosedTime']).'</option>';
            }
            ?>
        </select>
    </div>
</div>
<div class="row">
    <div class="col-md-3 mb-2">
        <label for="TextWOID" class="form-label fw-bold">WO ID</label>
        <input type="text" class="form-control form-control-sm" id="TextWOID" disabled>
    </div>
    <div class="col-md-3 mb-2">
        <label for="TextWOC" class="form-label fw-bold">WO Child</label>
        <input type="text" class="form-control form-control-sm" id="TextWOC" disabled>
    </div>
    <div class="col-md-3 mb-2">
        <label for="TextExpense" class="form-label fw-bold">Expense</label>
        <input type="text" class="form-control form-control-sm" id="TextExpense" disabled>
    </div>
    <div class="col-md-3 pt-4 mt-1">
        <button class="btn btn-dark btn-sm btn-labeled" id="BtnLoadListWO" data-bs-target="#ModalWOList" data-bs-toggle="modal" data-bs-dismiss="modal">List WO</button>
    </div>
</div>
<div class="row">
    <div class="col-md-2 mb-2">
        <label for="TextTotal" class="form-label fw-bold">Total</label>
        <input type="text" class="form-control form-control-sm" id="TextTotal">
    </div>
</div>
<div class="row">
    <div class="col-md-12 mb-2">
        <button class="btn btn-dark btn-sm btn-labeled" id="BtnAddLabourHour" data-datatoken="<?php echo $Token; ?>">Add Labour Hour</button>
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