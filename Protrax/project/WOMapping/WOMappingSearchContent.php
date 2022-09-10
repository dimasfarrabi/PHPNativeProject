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
    $FilType = htmlspecialchars(trim($_POST['FilType']), ENT_QUOTES, "UTF-8");
    $FilterKeywords = htmlspecialchars(trim($_POST['FilterKeywords']), ENT_QUOTES, "UTF-8");
    // echo "$FilType >> $FilterKeywords";
?>
<div class="col-md-6"><strong><h6>Data WO Mapping [<?php echo $FilterKeywords;?>]</h6></strong></div>
<div class="col-md-12 mt-2">
    <div class="table-responsive">
        <table class="table table-responsive table-hover display" id="TableSearchWO">
            <thead>
                <tr>
                    <th class="text-center">WOChild</th>
                    <th class="text-center">WOParent</th>
                    <th class="text-center">Quote</th>
                    <th class="text-center">TargetCost</th>
                    <th class="text-center">ClosedTime</th>
                    <th class="text-center">QtyParent</th>
                    <th class="text-center">QtyQuote</th>
                    <th class="text-center">Division</th>
                    <th class="text-center">ExpenseAllocation</th>
                    <th class="text-center">Product</th>
                    <th class="text-center">OrderType</th>
                    <th class="text-center">MappingCode</th>
                    <th class="text-center">Idx</th>
                    <th class="text-center">WOType</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $data = GET_DATA_WO($FilType,$FilterKeywords,$linkMACHWebTrax);
                while($res=sqlsrv_fetch_array($data))
                {
                    $ValWOChild	= trim($res['WOChild']);
                    $ValWOParent= trim($res['WOParent']);
                    $ValQuote= trim($res['Quote']);
                    $ValTargetCost= trim($res['TargetCost']);
                    $ValClosedTime= trim($res['ClosedTime']);
                    $ValQtyParent= trim($res['QtyParent']);
                    $ValQtyQuote= trim($res['QtyQuote']);
                    $ValDivision= trim($res['Division']);
                    $ValExpenseAllocation= trim($res['ExpenseAllocation']);
                    $ValProduct= trim($res['Product']);
                    $ValOrderType= trim($res['OrderType']);
                    $ValMappingCode= trim($res['MappingCode']);
                    $ValIdx= trim($res['Idx']);
                    $ValWOType= trim($res['WOType']);
                    $ValTargetCost = number_format((float)$ValTargetCost,2,'.',',');
                    $ValQtyParent = number_format((float)$ValQtyParent,2,'.',',');
                    $ValQtyQuote = number_format((float)$ValQtyQuote,2,'.',',');
                ?>
                <tr>
                    <td class="text-left"><?php echo $ValWOChild; ?></td>
                    <td class="text-left"><?php echo $ValWOParent; ?></td>
                    <td class="text-right"><?php echo $ValQuote; ?></td>
                    <td class="text-right"><?php echo $ValTargetCost; ?></td>
                    <td class="text-center"><?php echo $ValClosedTime; ?></td>
                    <td class="text-right"><?php echo $ValQtyParent; ?></td>
                    <td class="text-right"><?php echo $ValQtyQuote; ?></td>
                    <td class="text-left"><?php echo $ValDivision; ?></td>
                    <td class="text-left"><?php echo $ValExpenseAllocation; ?></td>
                    <td class="text-left"><?php echo $ValProduct; ?></td>
                    <td class="text-left"><?php echo $ValOrderType; ?></td>
                    <td class="text-left"><?php echo $ValMappingCode; ?></td>
                    <td class="text-left"><?php echo $ValIdx; ?></td>
                    <td class="text-left"><?php echo $ValWOType; ?></td>
                </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
<?php
}
?>
