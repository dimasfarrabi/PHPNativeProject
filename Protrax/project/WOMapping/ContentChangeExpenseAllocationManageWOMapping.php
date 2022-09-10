<?php 
session_start();
require_once("../../ConfigDB.php");
require_once("../../src/Modules/ModuleLogin.php");
require_once("Modules/ModuleWOMapping.php");
require_once("../../Project/Report/Modules/ModuleReport.php");
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

if($_SERVER['REQUEST_METHOD'] == "POST")
{   
    $ValWOMapping_ID = htmlspecialchars(trim($_POST['ValWOMapping_ID']), ENT_QUOTES, "UTF-8");
    $ValLocation = htmlspecialchars(trim($_POST['ValLocation']), ENT_QUOTES, "UTF-8");
    $ValClosedTime = htmlspecialchars(trim($_POST['ValClosedTime']), ENT_QUOTES, "UTF-8");
    $ValDataTemp = base64_encode(base64_encode(trim($ValWOMapping_ID)."#".$ValLocation));
    # data detail wo
    if($ValLocation == "PSM")
    {
        $QDataDetailWO = GET_DETAIL_WO_SELECTED_BY_ID_PSM($ValWOMapping_ID);
        $RDataDetailWO = mssql_fetch_assoc($QDataDetailWO);
        $QListExpense = GET_LIST_EXPENSE_ALLOCATION_ALL_NO_ADM_PSM();
    }
    if($ValLocation == "PSL")
    {
        $QDataDetailWO = GET_DETAIL_WO_SELECTED_BY_ID($ValWOMapping_ID,$linkMACHWebTrax);
        $RDataDetailWO = mssql_fetch_assoc($QDataDetailWO);
        $QListExpense = GET_LIST_EXPENSE_ALLOCATION_ALL_NO_ADM($linkMACHWebTrax);
    }
   

    ?>
<div class="row">
    <div class="col-md-2">
        <div class="form-group">
            <label for="FieldLocation2" class="form-label fw-bold">Location</label>
            <input type="text" class="form-control form-control-sm" id="FieldLocation2" value="<?php echo $ValLocation; ?>" readonly>
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <label for="FieldWOID2" class="form-label fw-bold">WOMapping_ID</label>
            <input type="text" class="form-control form-control-sm" id="FieldWOID2" value="<?php echo $ValWOMapping_ID; ?>" readonly>
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <label for="FieldClosedTime2" class="form-label fw-bold">Closed Time</label>
            <input type="text" class="form-control form-control-sm" id="FieldClosedTime2" value="<?php echo $ValClosedTime; ?>" readonly>
        </div>
    </div>
    <div class="col-md-6">&nbsp;</div>
    <div class="col-md-12"><hr></div>
    <div class="col-md-2">
        <div class="form-group">
            <label for="FieldOldExpenseAllocation" class="form-label fw-bold">Old Expense Allocation</label>
            <input type="text" class="form-control form-control-sm" id="FieldOldExpenseAllocation" value="<?php echo trim($RDataDetailWO['ExpenseAllocation']); ?>" readonly>
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <label for="FieldNewExpenseAllocation" class="form-label fw-bold">New Expense Allocation</label>
            <select class="form-select form-select-sm" id="FieldNewExpenseAllocation"><?php 
            while($RListExpense = mssql_fetch_assoc($QListExpense))
            {
                if(trim($RListExpense['DivisionName']) == trim($RDataDetailWO['ExpenseAllocation']))
                {
                    echo '<option selected>'.trim($RListExpense['DivisionName']).'</option>';
                }
                else
                {
                    echo '<option>'.trim($RListExpense['DivisionName']).'</option>';
                }
            }
            ?></select>
        </div>
    </div>
    <div class="col-md-12"><hr></div>
    <div class="col-md-12">
        <button class="btn btn-sm btn-success" id="BtnChangeExpenseAllocation" data-temp="<?php echo $ValDataTemp; ?>">Change Expense Allocation</button>
    </div>
    <div class="col-md-12" id="InfoChangeExpenseAllocation"></div>
</div>
    
</div>
    <?php
    
}
else
{
    echo "";    
}
?>