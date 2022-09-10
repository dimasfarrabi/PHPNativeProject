<?php 
session_start();
require_once("../../ConfigDB.php");
require_once("../../src/Modules/ModuleLogin.php");
require_once("Modules/ModuleInOutPartTBZ.php");
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
    $Category = htmlspecialchars(trim($_POST['Category']), ENT_QUOTES, "UTF-8");
    $Keywords = htmlspecialchars(trim($_POST['Keywords']), ENT_QUOTES, "UTF-8");

?>   
<div class="col-md-12">
    <div class="table-responsive">
        <table class="table table-border table-hover" id="TableWO">
            <thead class="theadCustom">
                <tr>
                    <th class="text-center">#</th>
                    <th class="text-center">WO ID</th>
                    <th class="text-center">WO Child</th>
                    <th class="text-center">WO Parent</th>
                    <th class="text-center">Quote</th>
                    <th class="text-center">Category</th>
                    <th class="text-center">Expense</th>
                    <th class="text-center">Division</th>
                    <th class="text-center">ClosedTime</th>
                    <th class="text-center">Product</th>
                </tr>
            </thead>
            <tbody><?php 
            $QData = GET_WO_BY_FILTER($Category,$Keywords,$linkMACHWebTrax);
            while($RData = sqlsrv_fetch_array($QData))
            {
                ?>
                <tr>
                    <td class="text-start"><button class="btn btn-sm btn-dark BtnSelect">Gunakan</button></td>
                    <td class="text-center"><?php echo trim($RData['Idx']); ?></td>
                    <td class="text-center"><?php echo trim($RData['WOChild']); ?></td>
                    <td class="text-center"><?php echo trim($RData['WOParent']); ?></td>
                    <td class="text-center"><?php echo trim($RData['Quote']); ?></td>
                    <td class="text-center"><?php echo trim($RData['QuoteCategory']); ?></td>
                    <td class="text-center"><?php echo trim($RData['ExpenseAllocation']); ?></td>
                    <td class="text-center"><?php echo trim($RData['Division']); ?></td>
                    <td class="text-center"><?php echo trim($RData['ClosedTime']); ?></td>
                    <td class="text-center"><?php echo trim($RData['Product']); ?></td>
                </tr>
                <?php
            }
            ?></tbody>
        </table>
    </div>
</div>

<?php
}
else
{
    echo "";    
}
?>