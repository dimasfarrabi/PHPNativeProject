<?php 
session_start();
require_once("../../ConfigDB.php");
require_once("../../src/Modules/ModuleLogin.php");
require_once("Modules/ModulePeriodicQuoteCost.php");
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

if($_SERVER['REQUEST_METHOD'] == "POST")
{   
    $DataIDEnc = htmlspecialchars(trim($_POST['DataID']), ENT_QUOTES, "UTF-8");
    $DataID = base64_decode(base64_decode($DataIDEnc));
    $ArrDataID = explode("#",$DataID);
    $ValHalf = trim($ArrDataID [0]);
    $ValCategory = trim($ArrDataID [1]);
    $ValQuote = trim($ArrDataID [2]);

?>
<div class="row">
    <div class="col-md-3">
        <div class="row">
            <div class="col-md-12 mb-2">
                <label for="TextAQuote" class="form-label fw-bold">Quote</label>
                <input type="text" class="form-control form-control-sm" id="TextAQuote" value="<?php echo $ValQuote; ?>" readonly>
            </div>
            <div class="col-md-12 mb-2">
                <label for="TextACategory" class="form-label fw-bold">Category</label>
                <input type="text" class="form-control form-control-sm" id="TextACategory" value="<?php echo $ValCategory; ?>" readonly>
            </div>
            <div class="col-md-12 mb-2">
                <label for="TextAHalf" class="form-label fw-bold">Half</label>
                <input type="text" class="form-control form-control-sm" id="TextAHalf" value="<?php echo $ValHalf; ?>" readonly>
            </div>
            <div class="col-md-12 mb-2">
                <label for="TextAExpense" class="form-label fw-bold">Expense</label>
                <select class="form-select form-select-sm" id="TextAExpense"><?php 
                $QListExpense = LIST_DIVISION_PERIODIC($linkMACHWebTrax);
                while($RListExpense = mssql_fetch_assoc($QListExpense))
                {
                    ?>
                    <option value="<?php echo trim($RListExpense['SortNumber']); ?>"><?php echo trim($RListExpense['ExpenseOption']); ?></option>
                    <?php
                }                
                ?></select>
            </div>
            <div class="col-md-12 mb-2">
                <label for="TextAPM" class="form-label fw-bold">PM</label>
                <select class="form-select form-select-sm" id="TextAPM"><option>-- Pilih PM --</option><?php 
                $QListPM = GET_LIST_PM_PERIODIC_QUOTE_COST($linkMACHWebTrax);
                while($RListPM = mssql_fetch_assoc($QListPM))
                {
                    ?>
                    <option value="<?php echo trim($RListPM['FullName']); ?>"><?php echo trim($RListPM['FullName']); ?></option>
                    <?php
                }                
                ?></select>
            </div>
            <div class="col-md-12 mb-2">
                <label for="TextADM" class="form-label fw-bold">DM</label>
                <select class="form-select form-select-sm" id="TextADM"><option>-- Pilih DM --</option><?php 
                $QLisDM = GET_LIST_DM_PERIODIC_QUOTE_COST($linkMACHWebTrax);
                while($RLisDM = mssql_fetch_assoc($QLisDM))
                {
                    ?>
                    <option value="<?php echo trim($RLisDM['FullName']); ?>"><?php echo trim($RLisDM['FullName']); ?></option>
                    <?php
                }                
                ?></select>
            </div>
        </div>
    </div>
    <div class="col-md-9">
        <div class="row">
            <div class="col-md-3 mb-2">
                <label for="TextAQtyQuote" class="form-label fw-bold">Qty Quote</label>
                <input type="text" class="form-control form-control-sm" id="TextAQtyQuote" value="">
            </div>
            <div class="col-md-3 mb-2">
                <label for="TextAQtyTarget" class="form-label fw-bold">Qty Target</label>
                <input type="text" class="form-control form-control-sm" id="TextAQtyTarget" value="">
            </div>
            <div class="col-md-3 mb-2">
                <label for="TextATargetPeopleCost" class="form-label fw-bold">Target People Cost</label>
                <input type="text" class="form-control form-control-sm" id="TextATargetPeopleCost" value="">
            </div>
            <div class="col-md-3 mb-2">
                <label for="TextAPeopleCost" class="form-label fw-bold">People Cost</label>
                <input type="text" class="form-control form-control-sm" id="TextAPeopleCost" value="">
            </div>
            <div class="col-md-3 mb-2">
                <label for="TextATargetMachineCost" class="form-label fw-bold">Target Machine Cost</label>
                <input type="text" class="form-control form-control-sm" id="TextATargetMachineCost" value="">
            </div>
            <div class="col-md-3 mb-2">
                <label for="TextAMachineCost" class="form-label fw-bold">Machine Cost</label>
                <input type="text" class="form-control form-control-sm" id="TextAMachineCost" value="">
            </div>
            <div class="col-md-3 mb-2">
                <label for="TextATargetMaterialCost" class="form-label fw-bold">Target Material Cost</label>
                <input type="text" class="form-control form-control-sm" id="TextATargetMaterialCost" value="">
            </div>
            <div class="col-md-3 mb-2">
                <label for="TextAMaterialCost" class="form-label fw-bold">Material Cost</label>
                <input type="text" class="form-control form-control-sm" id="TextAMaterialCost" value="">
            </div>
            <div class="col-md-3 mb-2">
                <label for="TextAQtyQCIn" class="form-label fw-bold">Qty QC In</label>
                <input type="text" class="form-control form-control-sm" id="TextAQtyQCIn" value="">
            </div>
            <div class="col-md-3 mb-2">
                <label for="TextAQtyQCOut" class="form-label fw-bold">Qty QC Out</label>
                <input type="text" class="form-control form-control-sm" id="TextAQtyQCOut" value="">
            </div>
            <div class="col-md-3 mb-2">
                <label for="TextATotalTargetCost" class="form-label fw-bold">Total Target Cost</label>
                <input type="text" class="form-control form-control-sm" id="TextATotalTargetCost" value="">
            </div>
            <div class="col-md-3 mb-2">
                <label for="TextATotalActualCost" class="form-label fw-bold">Total Actual Cost</label>
                <input type="text" class="form-control form-control-sm" id="TextATotalActualCost" value="">
            </div>
            <div class="col-md-3 mb-2">
                <label for="TextATotalTargetCostAndTargetQty" class="form-label fw-bold">Total Target Cost & Target Qty</label>
                <input type="text" class="form-control form-control-sm" id="TextATotalTargetCostAndTargetQty" value="">
            </div>
            <div class="col-md-3 mb-2">
                <label for="TextATotalTargetCostAndActualQty" class="form-label fw-bold">Total Target Cost & Actual Qty</label>
                <input type="text" class="form-control form-control-sm" id="TextATotalTargetCostAndActualQty" value="">
            </div>
            <div class="col-md-3 mb-2">
                <label for="TextATotalActualCostAndActualQty" class="form-label fw-bold">Total Actual Cost & Actual Qty</label>
                <input type="text" class="form-control form-control-sm" id="TextATotalActualCostAndActualQty" value="">
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">&nbsp;</div>
            <div class="col-md-12">
                <button class="btn btn-sm btn-dark btn-labeled" id="BtnAddPeriodic">Simpan Data</button>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12 pt-2" id="ModalContentInfoAdd"></div>
</div>
<?php
}
else
{
    echo "";    
}
?>