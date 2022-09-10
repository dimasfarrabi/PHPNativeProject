<?php 
session_start();
require_once("../../ConfigDB.php");
require_once("../../src/Modules/ModuleLogin.php");
require_once("Modules/ModuleQuantityBuild.php");
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
*/
if($_SERVER['REQUEST_METHOD'] == "POST")
{   
    $QuoteName = htmlspecialchars(trim($_POST['QuoteName']), ENT_QUOTES, "UTF-8");
    $DataIDEnc = htmlspecialchars(trim($_POST['DataID']), ENT_QUOTES, "UTF-8");
    $DataID = base64_decode(base64_decode($DataIDEnc));
    $ArrDataID = explode("#",$DataID);
    $Season = trim($ArrDataID['0']);
    $Category = trim($ArrDataID['1']);
    
    ?>
<div class="row">
    <div class="col-md-12"><h6 id="TitleResult">Season : <?php echo "<strong>".$Season."</strong>"; ?> Category : <?php echo "<strong>".$Category."</strong>"; ?> Quote : <?php echo "<strong>".$QuoteName."</strong>"; ?></h6></div>
    <div class="col-md-12"><hr></div>
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-2">
                <label for="InputDivision" class="form-label fw-bold">Division</label>
                <select class="form-select form-select-sm" aria-label="Select Division" id="InputDivision">
                    <?php
                    $QListExpense = GET_LIST_EXPENSE_SORTED($linkMACHWebTrax);
                    while($RListExpense = sqlsrv_fetch_array($QListExpense))
                    {
                        $ValIDEnc = base64_encode(base64_encode(trim($Season)."#".trim($RListExpense['ExpenseOption'])."#".trim($RListExpense['SortNumber'])));
                        echo '<option>'.trim($RListExpense['ExpenseOption']).'</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="col-md-2">
                <label for="InputMonth" class="form-label fw-bold">Month</label>
                <select class="form-select form-select-sm" aria-label="Select Month" id="InputMonth">
                    <?php
                    if(substr($Season,-1) == "1")
                    {
                        echo '<option value="12">December</option>';
                        for($a = 1;$a<=5;$a++)
                        {
                            $MonthName = date("F",mktime(0,0,0,$a,1,date("Y")));
                            echo '<option value="'.$a.'">'.$MonthName.'</option>';
                        }
                    }
                    else
                    {
                        for($a = 6;$a<=11;$a++)
                        {
                            $MonthName = date("F",mktime(0,0,0,$a,1,date("Y")));
                            echo '<option value="'.$a.'">'.$MonthName.'</option>';
                        }
                    }
                    ?>
                </select>
            </div><?php /*
            <div class="col-md-2">
                <label for="InputPoint" class="form-label fw-bold">Point</label>
                <input type="text" class="form-control form-control-sm" id="InputPoint">
            </div>*/ ?>
            <div class="col-md-2">
                <label for="InputTargetQty" class="form-label fw-bold">Target Qty</label>
                <input type="text" class="form-control form-control-sm" id="InputTargetQty">
            </div>
            <div class="col-md-2">
                <label for="InputActualQty" class="form-label fw-bold">Actual Qty</label>
                <input type="text" class="form-control form-control-sm" id="InputActualQty">
            </div>
            <div class="col-md-2 mt-4 pt-1">
                <button type="button" class="btn btn-dark btn-sm btn-labeled" id="BtnNewData">New Data</button>
            </div>
        </div>
    </div>
    <div class="col-md-12"><hr></div>
    <div class="col-md-12">
        <div class="table-responsive">
            <table class="table table-hover" id="TableViewData">
                <thead class="theadCustom">
                    <tr>
                        <th class="text-center">No</th>
                        <th class="text-center">Half</th>
                        <th class="text-center">Division</th>
                        <th class="text-center">Month</th>
                        <th class="text-center">Point</th>
                        <th class="text-center">Target Qty</th>
                        <th class="text-center">Actual Qty</th>
                        <th class="text-center">#</th>
                    </tr>
                </thead>
                <tbody><?php 
                $No = 1;
                $QListQty = LOAD_LIST_QUANTITY_BUILD_POINTS_DEFAULT($Season,$QuoteName,$linkMACHWebTrax);
                while($RListQty = sqlsrv_fetch_array($QListQty))
                {
                    $ValMonth =  $MonthName = date("F",mktime(0,0,0,trim($RListQty['Month']),1,date("Y")));
                    $ValPoints = sprintf('%.0f',floatval(trim($RListQty['Points'])));
                    $ValTargetQty = sprintf('%.0f',floatval(trim($RListQty['TargetQty'])));
                    $ValActualQty = sprintf('%.0f',floatval(trim($RListQty['ActualQty'])));
                    $ValId = trim($RListQty['Idx']);
                    $ValToken = base64_encode(base64_encode("IDXData".$ValId));
                    ?>
                    <tr>
                        <td class="text-center"><?php echo $No; ?></td>
                        <td class="text-center"><?php echo trim($RListQty['HalfClosed']); ?></td>
                        <td class="text-start"><?php echo trim($RListQty['Division']); ?></td>
                        <td class="text-start"><?php echo $ValMonth; ?></td>
                        <td class="text-center"><?php echo $ValPoints; ?></td>
                        <td class="text-center"><?php echo $ValTargetQty; ?></td>
                        <td class="text-center"><?php echo $ValActualQty; ?></td>
                        <td class="text-center"><span class="PointerList UpdateQtyBuild" data-datatoken="<?php echo $ValToken; ?>" title="Update Quantity Build"><i class="bi bi-pencil-square" aria-hidden="true"></i></span>&nbsp;<span class="PointerList DeleteQtyBuild" data-datatoken="<?php echo $ValToken; ?>" title="Delete Quantity Build"><i class="bi bi-trash-fill" aria-hidden="true"></i></span></td>
                    </tr>
                    <?php
                    $No++;
                }
                ?></tbody>
            </table>
        </div>
    </div>
    <div id="TemporarySpace"></div>  
</div>
    <?php
    
}
else
{
    echo "";    
}
?>