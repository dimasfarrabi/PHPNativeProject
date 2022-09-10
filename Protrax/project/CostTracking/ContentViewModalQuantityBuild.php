<?php 
session_start();
require_once("../../ConfigDB.php");
require_once("../../src/Modules/ModuleLogin.php");
require_once("Modules/ModuleQuantityBuild.php");
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
    $DataID = htmlspecialchars(trim($_POST['DataID']), ENT_QUOTES, "UTF-8");
    $ValID = $DataID;
    $DataID = str_replace("IDXData","",base64_decode(base64_decode($DataID)));
    # data detail
    $QData = GET_DETAIL_QUANTITY_BUILD_BY_ID($DataID,$linkMACHWebTrax);
    $RData = mssql_fetch_assoc($QData);
    $Half = trim($RData['HalfClosed']);
    $Division = trim($RData['Division']);
    $Month = date("F",mktime(0,0,0,trim($RData['Month']),1,date("Y")));
    $Point = sprintf('%.0f',floatval(trim($RData['Points'])));
    $TargetQty = sprintf('%.0f',floatval(trim($RData['TargetQty'])));
    $ActualQty = sprintf('%.0f',floatval(trim($RData['ActualQty'])));
?>

<div class="row">
    <div class="col-md-12 mb-2">
        <label for="TextHalf" class="form-label fw-bold">Half</label>
        <input type="text" class="form-control form-control-sm" id="TextHalf" value="<?php echo $Half; ?>" disabled>
    </div>
    <div class="col-md-12 mb-2">
        <label for="TextDivision" class="form-label fw-bold">Division</label>
        <input type="text" class="form-control form-control-sm" id="TextDivision" value="<?php echo $Division; ?>" disabled>
    </div>
    <div class="col-md-12 mb-2">
        <label for="TextMonth" class="form-label fw-bold">Month</label>
        <input type="text" class="form-control form-control-sm" id="TextMonth" value="<?php echo $Month; ?>" disabled>
    </div><?php /*
    <div class="col-md-12 mb-2">
        <label for="TextPoint" class="form-label fw-bold">Point</label>
        <input type="text" class="form-control form-control-sm" id="TextPoint" value="<?php echo $Point; ?>">
    </div>*/?>
    <div class="col-md-12 mb-2">
        <label for="TextTargetQty" class="form-label fw-bold">Target Qty</label>
        <input type="text" class="form-control form-control-sm" id="TextTargetQty" value="<?php echo $TargetQty; ?>">
    </div>
    <div class="col-md-12 mb-2">
        <label for="TextActualQty" class="form-label fw-bold">Actual Qty</label>
        <input type="text" class="form-control form-control-sm" id="TextActualQty" value="<?php echo $ActualQty; ?>">
    </div>
    <div class="col-md-12 mb-2">
        <button class="btn btn-dark btn-labeled" id="BtnEditQtyBuild" data-datatoken="<?php echo $ValID; ?>">Edit Qty Target</button>
    </div>
    <div class="col-md-12 mb-2">
        <div id="TempProcess"></div>
    </div>  
</div>
<?php    
}
else
{
    echo "";    
}
?>