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
    $InputHalf = htmlspecialchars(trim($_POST['Half']), ENT_QUOTES, "UTF-8");
    $InputCategory = htmlspecialchars(trim($_POST['Category']), ENT_QUOTES, "UTF-8");
    $InputQuote = htmlspecialchars(trim($_POST['Quote']), ENT_QUOTES, "UTF-8");
    $ValTokenAdd = base64_encode(base64_encode($InputHalf."#".$InputCategory."#".$InputQuote));

?>
<div class="col-md-12">Hasil Pencarian :</div>
<div class="col-md-12"><h6 id="TitleResult">Half : <?php echo $InputHalf; ?>. Quote : <?php echo $InputQuote; ?>. Category : <?php echo $InputCategory; ?>.</h6></div>
<div class="col-md-12 text-end pb-2">
    <button class="btn btn-sm btn-dark" id="BtnAddData" data-datatoken="<?php echo $ValTokenAdd; ?>">Tambah Data</button>
</div>
<div class="col-md-12">
    <div class="table-responsive">
        <table class="table table-border table-hover" id="TableViewData">
            <thead class="theadCustom">
                <tr>
                    <th class="text-center">No</th>
                    <th class="text-center">#</th>
                    <th class="text-center">Expense</th>
                    <th class="text-center">Half</th>
                    <th class="text-center">Category</th>
                    <th class="text-center">Quote</th>
                    <th class="text-center">PM</th>
                    <th class="text-center">DM</th>
                    <th class="text-center">QtyQuote</th>
                    <th class="text-center">QtyTarget</th>
                    <th class="text-center">TargetPeopleCost</th>
                    <th class="text-center">PeopleCost</th>
                    <th class="text-center">TargetMachineCost</th>
                    <th class="text-center">MachineCost</th>
                    <th class="text-center">TargetMaterialCost</th>
                    <th class="text-center">MaterialCost</th>
                    <th class="text-center">QtyQCIn</th>
                    <th class="text-center">QtyQCOut</th>
                    <th class="text-center">TotalTargetCost</th>
                    <th class="text-center">TotalActualCost</th>
                    <th class="text-center">TotalTargetCostAndTargetQty</th>
                    <th class="text-center">TotalTargetCostAndActualQty</th>
                    <th class="text-center">TotalActualCostAndActualQty</th>
                </tr>
            </thead>
            <tbody><?php 
            $No = 1;
            $QData = GET_DATA_PERIODIC_QUOTE_COST($InputHalf,$InputQuote,$InputCategory,$linkMACHWebTrax);
            while($RData = mssql_fetch_assoc($QData))
            {
                $ValIdx = trim($RData['Idx']);
                $ValExpense = trim($RData['ExpenseAllocation']);
                $ValHalfClosed = trim($RData['HalfClosed']);
                $ValQuoteCategory = trim($RData['QuoteCategory']);
                $ValQuote = trim($RData['Quote']);
                $ValPM = trim($RData['PM']);
                $ValDM = trim($RData['DM']);
                $ValQtyQuote = trim($RData['QtyQuote']);
                $ValQtyQuote = sprintf('%.0f',floatval(trim($ValQtyQuote)));
                $ValQtyTarget = trim($RData['QtyTarget']);
                $ValQtyTarget = sprintf('%.0f',floatval(trim($ValQtyTarget)));
                $ValTargetPeopleCost = trim($RData['TargetPeopleCost']);
                $ValTargetPeopleCost = sprintf('%.2f',floatval(trim($ValTargetPeopleCost)));
                $ValPeopleCost = trim($RData['PeopleCost']);
                $ValPeopleCost = sprintf('%.2f',floatval(trim($ValPeopleCost)));
                $ValTargetMachineCost = trim($RData['TargetMachineCost']);
                $ValTargetMachineCost = sprintf('%.2f',floatval(trim($ValTargetMachineCost)));
                $ValMachineCost = trim($RData['MachineCost']);
                $ValMachineCost = sprintf('%.2f',floatval(trim($ValMachineCost)));
                $ValTargetMaterialCost = trim($RData['TargetMaterialCost']);
                $ValTargetMaterialCost = sprintf('%.2f',floatval(trim($ValTargetMaterialCost)));
                $ValMaterialCost = trim($RData['MaterialCost']);
                $ValMaterialCost = sprintf('%.2f',floatval(trim($ValMaterialCost)));                
                $ValQtyQCIn = trim($RData['QtyQCIn']);
                $ValQtyQCIn = sprintf('%.0f',floatval(trim($ValQtyQCIn)));
                $ValQtyQCOut = trim($RData['QtyQCOut']);
                $ValQtyQCOut = sprintf('%.0f',floatval(trim($ValQtyQCOut)));                
                $ValTotalTargetCost = trim($RData['TotalTargetCost']);
                $ValTotalTargetCost = sprintf('%.2f',floatval(trim($ValTotalTargetCost)));
                $ValTotalActualCost = trim($RData['TotalActualCost']);
                $ValTotalActualCost = sprintf('%.2f',floatval(trim($ValTotalActualCost)));
                $ValTotalTargetCostAndTargetQty = trim($RData['TotTargetCostNTargetQty']);
                $ValTotalTargetCostAndTargetQty = sprintf('%.2f',floatval(trim($ValTotalTargetCostAndTargetQty)));
                $ValTotalTargetCostAndActualQty = trim($RData['TotTargetCostNActualQty']);
                $ValTotalTargetCostAndActualQty = sprintf('%.2f',floatval(trim($ValTotalTargetCostAndActualQty)));
                $ValTotalActualCostAndActualQty = trim($RData['TotActualCostNActualQty']);
                $ValTotalActualCostAndActualQty = sprintf('%.2f',floatval(trim($ValTotalActualCostAndActualQty)));
                $ValToken = base64_encode(base64_encode($InputHalf."#".$InputCategory."#".$InputQuote."#".$ValIdx));
                ?>
                <tr>
                    <td class="text-center"><?php echo $No; ?></td>
                    <td class="text-center">
                        <span class="PointerList UpdatePeriodic" data-datatoken="<?php echo $ValToken; ?>" title="Update Periodic">Update</span>&nbsp;
                        <span class="PointerList DeletePeriodic" data-datatoken="<?php echo $ValToken; ?>" title="Delete Periodic">Delete</span>
                    </td>
                    <td class="text-start"><?php echo $ValExpense; ?></td>
                    <td class="text-center"><?php echo $ValHalfClosed; ?></td>
                    <td class="text-center"><?php echo $ValQuoteCategory; ?></td>
                    <td class="text-start"><?php echo $ValQuote; ?></td>
                    <td class="text-start"><?php echo $ValPM; ?></td>
                    <td class="text-start"><?php echo $ValDM; ?></td>
                    <td class="text-center"><?php echo $ValQtyQuote; ?></td>
                    <td class="text-center"><?php echo $ValQtyTarget; ?></td>
                    <td class="text-end"><?php echo $ValTargetPeopleCost; ?></td>
                    <td class="text-end"><?php echo $ValPeopleCost; ?></td>
                    <td class="text-end"><?php echo $ValTargetMachineCost; ?></td>
                    <td class="text-end"><?php echo $ValMachineCost; ?></td>
                    <td class="text-end"><?php echo $ValTargetMaterialCost; ?></td>
                    <td class="text-end"><?php echo $ValMaterialCost; ?></td>
                    <td class="text-center"><?php echo $ValQtyQCIn; ?></td>
                    <td class="text-center"><?php echo $ValQtyQCOut; ?></td>
                    <td class="text-end"><?php echo $ValTotalTargetCost; ?></td>
                    <td class="text-end"><?php echo $ValTotalActualCost; ?></td>
                    <td class="text-end"><?php echo $ValTotalTargetCostAndTargetQty; ?></td>
                    <td class="text-end"><?php echo $ValTotalTargetCostAndActualQty; ?></td>
                    <td class="text-end"><?php echo $ValTotalActualCostAndActualQty; ?></td>
                </tr>
                <?php
                $No++;
            }
            
            ?></tbody>
        </table>
    </div>
</div>
<div class="col-md-12">&nbsp;</div>
<div class="col-md-12">&nbsp;</div>
<div id="TemporarySpace"></div>
    <?php
}
else
{
    echo "";    
}
?>