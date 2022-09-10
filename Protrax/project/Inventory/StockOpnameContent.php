<?php
session_start();
require_once("../../ConfigDB.php");
require_once("../../src/Modules/ModuleLogin.php");
require_once("Modules/ModuleStockOpname.php");
date_default_timezone_set("Asia/Jakarta");
$DateNow = date("m/d/Y");
$YearNow = date("Y");
$TimeNow = date("Y-m-d H:i:s");
$FullName = "DIMAS RIZKY FARRABI";
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

$LinkPSL = $linkMACHWebTrax;
if($_SERVER['REQUEST_METHOD'] == "POST")
{
    $ValQuoteName = htmlspecialchars(trim($_POST['ValQuoteName']), ENT_QUOTES, "UTF-8");
    $ValProjectID = htmlspecialchars(trim($_POST['ValProjectID']), ENT_QUOTES, "UTF-8");
    $ValLocation = base64_decode(htmlspecialchars(trim($_POST['ValLocation']), ENT_QUOTES, "UTF-8"));
?>
<div class="col-md-12"><h6><strong>Quote:</strong> <?php echo $ValQuoteName; ?></h6></div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group" style="width:100%;">
            <label for="InputTemplate" class="form-label fw-bold"></label>
            <select class="form-select form-select-sm" id="InputTemplate">
                <option>-- Pilih Template --</option>
                <?php
                $data = GET_TEMPLATE_NAME_BY_QUOTE($ValProjectID,$LinkPSL);
                while($res=sqlsrv_fetch_array($data))
                {
                    $TemplateName = trim($res['TemplateName']);
                ?>
                <option><?php echo $TemplateName; ?></option>
                <?php
                }
                ?>
            </select>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group" style="width:100%;">
            <label for="InputLocation" class="form-label fw-bold"></label>
            <select class="form-select form-select-sm" id="InputLocation">
                <option>-- Pilih Lokasi --</option>
                <?php
                $data2 = GET_COMPANY($linkMACHWebTrax);
                while($res2=sqlsrv_fetch_array($data2))
                {
                    $ValComp = trim($res2['CompanyCode']);
                    ?>
                    <option><?php echo $ValComp; ?></option>
                    <?php
                }
                ?>
            </select>
        </div>
    </div>
    <div class="col-md-3" style="margin-top:20px;">
        <button class="btn btn-sm btn-dark" id="BtnOpen">Open Template</button>
    </div>
</div>

<?php
}
else { }
?>