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
    $ValIdx = trim($ArrDataID [3]);
    # data detail
    $QData = DETAIL_DATA_PERIODIC_BY_ID($ValIdx,$linkMACHWebTrax);
    $RData = mssql_fetch_assoc($QData);
    $DPM = trim($RData['PM']);
    $DDM = trim($RData['DM']);
    $DExpense = trim($RData['ExpenseAllocation']);
    $DQtyQuote = trim($RData['QtyQuote']);
    $DQtyQuote = sprintf('%.0f',floatval(trim($DQtyQuote)));
    $DQtyTarget = trim($RData['QtyTarget']);
    $DQtyTarget = sprintf('%.0f',floatval(trim($DQtyTarget)));
    $DTargetPeopleCost = trim($RData['TargetPeopleCost']);
    $DTargetPeopleCost = sprintf('%.2f',floatval(trim($DTargetPeopleCost)));
    $DPeopleCost = trim($RData['PeopleCost']);
    $DPeopleCost = sprintf('%.2f',floatval(trim($DPeopleCost)));
    $DTargetMachineCost = trim($RData['TargetMachineCost']);
    $DTargetMachineCost = sprintf('%.2f',floatval(trim($DTargetMachineCost)));
    $DMachineCost = trim($RData['MachineCost']);
    $DMachineCost = sprintf('%.2f',floatval(trim($DMachineCost)));
    $DTargetMaterialCost = trim($RData['TargetMaterialCost']);
    $DTargetMaterialCost = sprintf('%.2f',floatval(trim($DTargetMaterialCost)));
    $DMaterialCost = trim($RData['MaterialCost']);
    $DMaterialCost = sprintf('%.2f',floatval(trim($DMaterialCost)));
    $DQtyQCIn = trim($RData['QtyQCIn']);
    $DQtyQCIn = sprintf('%.0f',floatval(trim($DQtyQCIn)));
    $DQtyQCOut = trim($RData['QtyQCOut']);
    $DQtyQCOut = sprintf('%.0f',floatval(trim($DQtyQCOut)));
    $DTotalTargetCost = trim($RData['TotalTargetCost']);
    $DTotalTargetCost = sprintf('%.2f',floatval(trim($DTotalTargetCost)));
    $DTotalActualCost = trim($RData['TotalActualCost']);
    $DTotalActualCost = sprintf('%.2f',floatval(trim($DTotalActualCost)));
    $DTotTargetCostNTargetQty = trim($RData['TotTargetCostNTargetQty']);
    $DTotTargetCostNTargetQty = sprintf('%.2f',floatval(trim($DTotTargetCostNTargetQty)));
    $DTotTargetCostNActualQty = trim($RData['TotTargetCostNActualQty']);
    $DTotTargetCostNActualQty = sprintf('%.2f',floatval(trim($DTotTargetCostNActualQty)));
    $DTotActualCostNActualQty = trim($RData['TotActualCostNActualQty']);
    $DTotActualCostNActualQty = sprintf('%.2f',floatval(trim($DTotActualCostNActualQty)));
    $ValToken = base64_encode(base64_encode($ValHalf."#".$ValCategory."#".$ValQuote."#".$ValIdx));
?>
<div class="row">
    <div class="col-md-3">
        <div class="row">
            <div class="col-md-12 mb-2">
                <label for="TextVQuote" class="form-label fw-bold">Quote</label>
                <input type="text" class="form-control form-control-sm" id="TextVQuote" value="<?php echo $ValQuote; ?>" readonly>
            </div>
            <div class="col-md-12 mb-2">
                <label for="TextVCategory" class="form-label fw-bold">Category</label>
                <input type="text" class="form-control form-control-sm" id="TextVCategory" value="<?php echo $ValCategory; ?>" readonly>
            </div>
            <div class="col-md-12 mb-2">
                <label for="TextVHalf" class="form-label fw-bold">Half</label>
                <input type="text" class="form-control form-control-sm" id="TextVHalf" value="<?php echo $ValHalf; ?>" readonly>
            </div>
            <div class="col-md-12 mb-2">
                <label for="TextVExpense" class="form-label fw-bold">Expense</label>
                <input type="text" class="form-control form-control-sm" id="TextVExpense" value="<?php echo $DExpense; ?>" readonly>
            </div>
            <div class="col-md-12 mb-2">
                <label for="TextVPM" class="form-label fw-bold">PM</label>
                <select class="form-select form-select-sm" id="TextVPM"><?php 
                $QListPM = GET_LIST_PM_PERIODIC_QUOTE_COST($linkMACHWebTrax);
                while($RListPM = mssql_fetch_assoc($QListPM))
                {
                    if(trim($RListPM['FullName']) == $DPM)
                    {
                        ?>
                        <option value="<?php echo trim($RListPM['FullName']); ?>" selected><?php echo trim($RListPM['FullName']); ?></option>
                        <?php
                    }
                    else
                    {
                        ?>
                        <option value="<?php echo trim($RListPM['FullName']); ?>"><?php echo trim($RListPM['FullName']); ?></option>
                        <?php
                    }
                }                
                ?></select>
            </div>
            <div class="col-md-12 mb-2">
                <label for="TextVDM" class="form-label fw-bold">DM</label>
                <select class="form-select form-select-sm" id="TextVDM"><?php 
                $QLisDM = GET_LIST_DM_PERIODIC_QUOTE_COST($linkMACHWebTrax);
                while($RLisDM = mssql_fetch_assoc($QLisDM))
                {
                    if(trim($RLisDM['FullName']) == $DDM)
                    {
                        ?>
                        <option value="<?php echo trim($RLisDM['FullName']); ?>" selected><?php echo trim($RLisDM['FullName']); ?></option>
                        <?php
                    }
                    else
                    {
                        ?>
                        <option value="<?php echo trim($RLisDM['FullName']); ?>"><?php echo trim($RLisDM['FullName']); ?></option>
                        <?php
                    }

                }                
                ?></select>
            </div>
        </div>
    </div>
    <div class="col-md-9">
        <div class="row">
            <div class="col-md-3 mb-2">
                <label for="TextVQtyQuote" class="form-label fw-bold">Qty Quote</label>
                <input type="text" class="form-control form-control-sm" id="TextVQtyQuote" value="<?php echo $DQtyQuote; ?>">
            </div>
            <div class="col-md-3 mb-2">
                <label for="TextVQtyTarget" class="form-label fw-bold">Qty Target</label>
                <input type="text" class="form-control form-control-sm" id="TextVQtyTarget" value="<?php echo $DQtyTarget; ?>">
            </div>
            <div class="col-md-3 mb-2">
                <label for="TextVTargetPeopleCost" class="form-label fw-bold">Target People Cost</label>
                <input type="text" class="form-control form-control-sm" id="TextVTargetPeopleCost" value="<?php echo $DTargetPeopleCost; ?>">
            </div>
            <div class="col-md-3 mb-2">
                <label for="TextVPeopleCost" class="form-label fw-bold">People Cost</label>
                <input type="text" class="form-control form-control-sm" id="TextVPeopleCost" value="<?php echo $DPeopleCost; ?>">
            </div>
            <div class="col-md-3 mb-2">
                <label for="TextVTargetMachineCost" class="form-label fw-bold">Target Machine Cost</label>
                <input type="text" class="form-control form-control-sm" id="TextVTargetMachineCost" value="<?php echo $DTargetMachineCost; ?>">
            </div>
            <div class="col-md-3 mb-2">
                <label for="TextVMachineCost" class="form-label fw-bold">Machine Cost</label>
                <input type="text" class="form-control form-control-sm" id="TextVMachineCost" value="<?php echo $DMachineCost; ?>">
            </div>
            <div class="col-md-3 mb-2">
                <label for="TextVTargetMaterialCost" class="form-label fw-bold">Target Material Cost</label>
                <input type="text" class="form-control form-control-sm" id="TextVTargetMaterialCost" value="<?php echo $DTargetMaterialCost; ?>">
            </div>
            <div class="col-md-3 mb-2">
                <label for="TextVMaterialCost" class="form-label fw-bold">Material Cost</label>
                <input type="text" class="form-control form-control-sm" id="TextVMaterialCost" value="<?php echo $DMaterialCost; ?>">
            </div>
            <div class="col-md-3 mb-2">
                <label for="TextVQtyQCIn" class="form-label fw-bold">Qty QC In</label>
                <input type="text" class="form-control form-control-sm" id="TextVQtyQCIn" value="<?php echo $DQtyQCIn; ?>">
            </div>
            <div class="col-md-3 mb-2">
                <label for="TextVQtyQCOut" class="form-label fw-bold">Qty QC Out</label>
                <input type="text" class="form-control form-control-sm" id="TextVQtyQCOut" value="<?php echo $DQtyQCOut; ?>">
            </div>
            <div class="col-md-3 mb-2">
                <label for="TextVTotalTargetCost" class="form-label fw-bold">Total Target Cost</label>
                <input type="text" class="form-control form-control-sm" id="TextVTotalTargetCost" value="<?php echo $DTotalTargetCost; ?>">
            </div>
            <div class="col-md-3 mb-2">
                <label for="TextVTotalActualCost" class="form-label fw-bold">Total Actual Cost</label>
                <input type="text" class="form-control form-control-sm" id="TextVTotalActualCost" value="<?php echo $DTotalActualCost; ?>">
            </div>
            <div class="col-md-3 mb-2">
                <label for="TextVTotalTargetCostAndTargetQty" class="form-label fw-bold">Total Target Cost & Target Qty</label>
                <input type="text" class="form-control form-control-sm" id="TextVTotalTargetCostAndTargetQty" value="<?php echo $DTotTargetCostNTargetQty; ?>">
            </div>
            <div class="col-md-3 mb-2">
                <label for="TextVTotalTargetCostAndActualQty" class="form-label fw-bold">Total Target Cost & Actual Qty</label>
                <input type="text" class="form-control form-control-sm" id="TextVTotalTargetCostAndActualQty" value="<?php echo $DTotTargetCostNActualQty; ?>">
            </div>
            <div class="col-md-3 mb-2">
                <label for="TextVTotalActualCostAndActualQty" class="form-label fw-bold">Total Actual Cost & Actual Qty</label>
                <input type="text" class="form-control form-control-sm" id="TextVTotalActualCostAndActualQty" value="<?php echo $DTotActualCostNActualQty; ?>">
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">&nbsp;</div>
            <div class="col-md-12">
                <button class="btn btn-sm btn-dark btn-labeled" id="BtnEditPeriodic" data-token="<?php echo $ValToken; ?>">Update Data</button>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12" id="ModalContentInfo"></div>
</div>
<?php
}
else
{
    echo "";    
}
?>