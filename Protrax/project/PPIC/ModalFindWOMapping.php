<?php 
session_start();
require_once("../../ConfigDB.php");
require_once("../../src/Modules/ModuleLogin.php");
require_once("Modules/ModuleNewBCPartJob.php");
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
    $TypeSearchData = htmlspecialchars(trim($_POST['TypeSearchData']), ENT_QUOTES, "UTF-8");
    $Division = htmlspecialchars(trim($_POST['Division']), ENT_QUOTES, "UTF-8");
    $InputKeywords = htmlspecialchars(trim($_POST['InputKeywords']), ENT_QUOTES, "UTF-8");

?>   
<div class="col-md-12">
    <div class="table-responsive">
        <table class="table table-border table-hover" id="TableWOMapping">
            <thead class="theadCustom">
                <tr>
                    <th class="text-center">#</th>
                    <th class="text-center">WOChild</th>
                    <th class="text-center">WOParent</th>
                    <th class="text-center">Quote</th>
                    <th class="text-center">Division</th>
                    <th class="text-center">ExpenseAllocation</th>
                    <th class="text-center">Product</th>
                    <th class="text-center">OrderType</th>
                    <th class="text-center">Idx</th>
                </tr>
            </thead>
            <tbody><?php 
            $QData = SEARCH_WO_MAPPING_MODAL($TypeSearchData,$Division,$InputKeywords,$linkMACHWebTrax);
            while($RData = mssql_fetch_assoc($QData))
            {
                ?>
                <tr>
                    <td class="text-start"><button class="btn btn-sm btn-dark BtnSelect">Gunakan</button></td>
                    <td class="text-start"><?php echo utf8_encode(trim($RData['WOChild'])); ?></td>
                    <td class="text-start"><?php echo utf8_encode(trim($RData['WOParent'])); ?></td>
                    <td class="text-start"><?php echo utf8_encode(trim($RData['Quote'])); ?></td>
                    <td class="text-start"><?php echo utf8_encode(trim($RData['Division'])); ?></td>
                    <td class="text-start"><?php echo utf8_encode(trim($RData['ExpenseAllocation'])); ?></td>
                    <td class="text-start"><?php echo utf8_encode(trim($RData['Product'])); ?></td>
                    <td class="text-start"><?php echo utf8_encode(trim($RData['OrderType'])); ?></td>
                    <td class="text-start"><?php echo utf8_encode(trim($RData['Idx'])); ?></td>
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