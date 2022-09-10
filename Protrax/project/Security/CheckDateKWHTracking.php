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
    $ValDate = htmlspecialchars(trim($_POST['ValDate']), ENT_QUOTES, "UTF-8");
    $Location = htmlspecialchars(trim($_POST['ValLocation']), ENT_QUOTES, "UTF-8");
    $Location = base64_decode(base64_decode($Location));
    $ArrLocation = explode("#",$Location);
    # check data before
    $QCheckData1 = GET_DATA_KWH_TRACKING_BEFORE($ValDate,$ArrLocation[1],$linkHRISWebTrax);
    $QCheckData2 = GET_DATA_KWH_TRACKING_BEFORE_SECURITY($ValDate,$ArrLocation[1],$linkHRISWebTrax);
    $RowCheck1 = mssql_num_rows($QCheckData1);
    $RowCheck2 = mssql_num_rows($QCheckData2);
    $TotalCheckRow = $RowCheck1 + $RowCheck2;
    if($TotalCheckRow == 0)
    {
        $FormInput = '';
        $BtnAdd = '';
    }
    else
    {
        if($RowCheck1 != "0")
        {
            $RCheckData1 = mssql_fetch_assoc($QCheckData1);
            $ValKWH = trim($RCheckData1['KWH']);
            $FormInput = ' value="'.$ValKWH.'" disabled';
            $BtnAdd = ' disabled';
        }
        if($RowCheck2 != "0")
        {
            $RCheckData2 = mssql_fetch_assoc($QCheckData2);
            $ValKWH = trim($RCheckData2['Usage']);
            $FormInput = ' value="'.$ValKWH.'" disabled';
            $BtnAdd = ' disabled';
        }
    }
?>
<label class="form-label fw-bold">Date Input : <?php echo $ValDate; ?></label>
<div class="form-group">
    <label for="InputUsage" class="form-label fw-bold">Usage</label>
    <input type="text" class="form-control form-control-custom" id="InputUsage" name="InputUsage"<?php echo $FormInput; ?> required>
</div><hr>                        
<button id="BtnAdd" class="btn btn-md btn-dark"<?php echo $BtnAdd; ?>>Add Data</button>
<?php
}
else
{
    echo "";    
}
?>