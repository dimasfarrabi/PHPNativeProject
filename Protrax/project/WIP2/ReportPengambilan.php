<?php
session_start();
require_once("../../ConfigDB.php");
require_once("../../src/Modules/ModuleLogin.php");
require_once("Modules/ModuleInOut.php");
date_default_timezone_set("Asia/Jakarta");
$DateNow = date("m/d/Y");
$YearNow = date("Y");
$FullName = "Local-Dimas Farrabi";
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
*/
?>
<div class="col-md-12">
    <div class="table-responsive">
        <table class="table table-responsive table-bordered" id="ReportTable">
            <thead class="theadCustom">
                <tr>
                    <th class="text-center">PartNo</div>
                    <th class="text-center">Proses</div>
                    <th class="text-center">Qty</div>
                </tr>
            </thead>
            <tbody>
                <?php
                $data = GET_REPORT($linkMACHWebTrax);
                while($res=sqlsrv_fetch_array($data))
                {
                    $ValPartNo = trim($res['PartNo']);
                    $ValProses = trim($res['Proses']);
                    $ValQty = trim($res['TotalQty']);
                ?>
                <tr>
                    <td class="text-center"><?php echo $ValPartNo; ?></td>
                    <td class="text-center"><?php echo $ValProses; ?></td>
                    <td class="text-center"><?php echo $ValQty; ?></td>
                </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
    </div>
</div>