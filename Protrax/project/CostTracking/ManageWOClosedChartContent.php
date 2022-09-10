<?php 
session_start();
require_once("../../ConfigDB.php");
require_once("../../src/Modules/ModuleLogin.php");
require_once("Modules/ModuleCostTrackingChart.php");
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
    $EncValDataID = htmlspecialchars(trim($_POST['ValDataID']), ENT_QUOTES, "UTF-8");
    $ArrDataID = base64_decode(base64_decode($EncValDataID));
    $ArrDataID = explode("#",$ArrDataID);
    $ValProjectName = $ArrDataID[0];
    $ValQuoteID = $ArrDataID[1];
    $ValQuoteCategory = $ArrDataID[2];
    ?>
    <div class="col-md-12"><h5 id="TitleProject">Project : <?php echo  '<strong>'.strtoupper($ValQuoteID).'</strong>'; ?></h5></div>
    <div class="col-md-12"><hr></div>
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-2">
                <label for="InputQuote" class="form-label fw-bold">Quote</label>
                <select class="form-select form-select-sm" aria-label="Select Quote" id="InputQuote">
                    <?php
                    $QListQuoteSelected = GET_LIST_WEBTRAX_QUOTE_BY_QUOTEID($ValQuoteID,$linkMACHWebTrax);
                    while($RListQuoteSelected = mssql_fetch_assoc($QListQuoteSelected))
                    {
                        echo '<option>'.trim($RListQuoteSelected['Quote']).'</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="col-md-2">
                <label for="InputHalf" class="form-label fw-bold">Half</label>
                <select class="form-select form-select-sm" aria-label="Select Half" id="InputHalf">
                    <?php
                    $QListClosedTime = GET_ALL_TYPE_CLOSED_TIME_DESC($linkMACHWebTrax);
                    while($RListClosedTime = mssql_fetch_assoc($QListClosedTime))
                    {
                        echo '<option>'.trim($RListClosedTime['ClosedTime']).'</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="col-md-2">
                <label for="InputTargetCost" class="form-label fw-bold">Total Target Cost</label>
                <input type="text" class="form-control form-control-sm" id="InputTargetCost">
            </div>
            <div class="col-md-2">
                <label for="InputActualCost" class="form-label fw-bold">Total Actual Cost</label>
                <input type="text" class="form-control form-control-sm" id="InputActualCost">
            </div>
            <div class="col-md-2">
                <label for="InputQtyBuilt" class="form-label fw-bold">Total Qty Built</label>
                <input type="text" class="form-control form-control-sm" id="InputQtyBuilt">
            </div>
            <div class="col-md-2">
                <label for="InputQtyTarget" class="form-label fw-bold">Total Qty Target</label>
                <input type="text" class="form-control form-control-sm" id="InputQtyTarget">
            </div>
            <div class="col-md-2">
                <label for="InputOTS" class="form-label fw-bold">Total OTS</label>
                <input type="text" class="form-control form-control-sm" id="InputOTS">
            </div>
            <div class="col-md-2 mt-4 pt-1">
                <button type="button" class="btn btn-dark btn-sm btn-labeled" id="BtnNewData">New Data</button>
            </div>
        </div>
    </div>
    <div class="col-md-12"><hr></div>
    <div class="col-md-12">
        <div class="table-responsive">
            <table class="table table-bordered table-hover" id="TableViewData">
                <thead class="theadCustom">
                    <tr>
                        <th class="text-center">No</th>
                        <th class="text-center">Half</th>
                        <th class="text-center">Quote</th>
                        <th class="text-center">TotalTargetCost</th>
                        <th class="text-center">TotalActualCost</th>
                        <th class="text-center">TotalQtyBuilt</th>
                        <th class="text-center">TotalQtyTarget</th>
                        <th class="text-center">TotalOTS</th>
                        <th class="text-center">#</th>
                    </tr>
                </thead>
                <tbody><?php
                    $No = 1;
                    $QData = GET_LIST_DATA_WO_CLOSED_CHART($ValQuoteID,$linkMACHWebTrax);
                    while($RData = mssql_fetch_assoc($QData))
                    {
                        $ValHalf = trim($RData['TargetHalfClosed']);
                        $ValNumTotalTargetCost = sprintf('%.2f',floatval(trim($RData['TotalTargetCost'])));
                        $ValNumTotalActualCost = sprintf('%.2f',floatval(trim($RData['TotalActualCost'])));
                        $ValNumTotalQtyBuilt = sprintf('%.2f',floatval(trim($RData['TotalQtyBuilt'])));
                        $ValNumTotalOTS = sprintf('%.2f',floatval(trim($RData['TotalOTS'])));
                        $ValToken = base64_encode(base64_encode(trim($RData['Idx'])."#".trim($RData['QuoteID'])."#".trim($RData['TargetHalfClosed'])));
                        $ValRow = base64_encode(base64_encode(trim($RData['Idx'])."#".trim($RData['QuoteID'])));
                        $ValQuote = trim($RData['Quote']);
                        $ValNumTotalQtyTarget = sprintf('%.2f',floatval(trim($RData['TotalQtyTarget'])));
                    ?>
                    <tr data-cookies="<?php echo $ValRow; ?>">
                        <td class="text-center"><?php echo $No; ?></td>
                        <td class="text-center"><?php echo $ValHalf; ?></td>
                        <td class="text-start"><?php echo $ValQuote; ?></td>
                        <td class="text-end"><?php echo $ValNumTotalTargetCost; ?></td>
                        <td class="text-end"><?php echo $ValNumTotalActualCost; ?></td>
                        <td class="text-end"><?php echo $ValNumTotalQtyBuilt ?></td>
                        <td class="text-end"><?php echo $ValNumTotalQtyTarget ?></td>
                        <td class="text-end"><?php echo $ValNumTotalOTS ?></td>
                        <td class="text-center"><span class="PointerList DataRow" title="Update Data" data-token="<?php echo $ValToken; ?>"><i class="bi bi-pencil-square"></i></span>&nbsp;<span class="PointerList DataDelete" title="Delete Data" data-token="<?php echo $ValToken; ?>"><i class="bi bi-trash"></i></span></td>
                    </tr>
                    <?php
                    $No++;
                    }
                    
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    <div id="TemporarySpace"></div>    
    <?php    
}
else
{
    echo "";    
}
?>