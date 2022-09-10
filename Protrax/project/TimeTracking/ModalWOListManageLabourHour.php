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
    $Category = htmlspecialchars(trim($_POST['Category']), ENT_QUOTES, "UTF-8");
    $Season = htmlspecialchars(trim($_POST['Season']), ENT_QUOTES, "UTF-8");    
?>
<div class="row">
    <div class="col-md-12">
        <div class="table-responsive">
            <table class="table table-border table-hover" id="TableListWO">
                <thead class="theadCustom">
                    <tr>
                        <th class="text-center">No</th>
                        <th class="text-center">WO Child</th>
                        <th class="text-center">WO ID</th>
                        <th class="text-center">Expense</th>
                        <th class="text-center">#</th>
                    </tr>
                </thead>
                <tbody><?php
                $No = 1;
                $QListWO = GET_LIST_WO_MAPPING($Category,$Season,$linkMACHWebTrax);
                while($RListWO = mssql_fetch_assoc($QListWO))
                {
                    ?>
                    <tr>
                        <td class="text-center"><?php echo $No; ?></td>
                        <td class="text-start"><?php echo trim($RListWO['WOChild']); ?></td>
                        <td class="text-center"><?php echo trim($RListWO['PSL_Idx']); ?></td>
                        <td class="text-center"><?php echo trim($RListWO['ExpenseAllocation']); ?></td>
                        <td class="text-center"><button class="btn btn-sm btn-dark BtnSelect" data-bs-target="#ModalAddLabourHour" data-bs-toggle="modal" data-bs-dismiss="modal">Use</button></td>
                    </tr>
                    <?php 
                    $No++;
                }
                ?></tbody>
            </table>
        </div>
    </div>
</div>
    <?php
}
else
{
    echo "";    
}
?>