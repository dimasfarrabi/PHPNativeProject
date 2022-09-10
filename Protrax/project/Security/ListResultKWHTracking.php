<?php 
session_start();
require_once("../../ConfigDB.php");
require_once("../../src/Modules/ModuleLogin.php");
require_once("Modules/ModuleSecurity.php");
date_default_timezone_set("Asia/Jakarta");

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

if((trim($AccessLogin) != "Employee") && ($RDataUserWebtrax['MnSecurity'] != "1") && ($RDataUserWebtrax['MnAdmin'] != "0"))
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
    $Location = htmlspecialchars(trim($_POST['ValLocation']), ENT_QUOTES, "UTF-8");
    $Location = base64_decode(base64_decode($Location));
    $ArrLocation = explode("#",$Location);
    $ValLocation = $ArrLocation[1];
?>
<strong>Table Top 10 Result</strong>
<div class="table-responsive">
    <table class="table table-bordered table-hover" id="TableData">
        <thead class="theadCustom">    
            <tr>
                <th class="text-center" width="10">No</th>
                <th class="text-center" width="100">Date</th>
                <th class="text-center">Usage</th>
                <th class="text-center">Location</th>
                <th class="text-center">Name</th>
            </tr>
        </thead>
        <tbody><?php 
        $QData = GET_HISTORY_TOP10_SECURITY_KWH_TRACKING_LOG($ValLocation,$linkHRISWebTrax);
        $No = 1;
        while($RData = mssql_fetch_assoc($QData))
        {
            $ValDate = date("m/d/Y",strtotime($RData['DateTracking']));
            $ValUsage = trim($RData['Usage']);
            $ValLocation = trim($RData['Location']);
            $ValUser = trim($RData['FullName']);
            switch ($ValLocation) {
                case 'FI':
                    $ResLocation = "Formulatrix Indonesia - Salatiga";
                    break;
                case 'PSL':
                    $ResLocation = "Promanufacture Indonesia - Salatiga";
                    break;
                case 'PSM':
                    $ResLocation = "Promanufacture Indonesia - Semarang";
                    break;
                default:
                    $ResLocation = "-";
                    break;
            }
            ?>
            <tr>
                <td class="text-center"><?php echo $No; ?></td>
                <td class="text-center"><?php echo $ValDate; ?></td>
                <td class="text-center"><?php echo $ValUsage; ?></td>
                <td class="text-center"><?php echo $ResLocation; ?></td>
                <td class="text-center"><?php echo $ValUser; ?></td>
            </tr>
            <?php
            $No++;
        }
        ?></tbody>
    </table>
</div>
<?php
}
else
{
    echo "";    
}
?>