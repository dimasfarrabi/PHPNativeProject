<?php 
session_start();
require_once("../../ConfigDB.php");
require_once("../../ConfigDB2.php");
require_once("../../src/Modules/ModuleLogin.php");
require_once("Modules/ModuleInOutPartTBZ.php");
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
    $LocationWH = htmlspecialchars(trim($_POST['LocationWH']), ENT_QUOTES, "UTF-8");
    $QListSmallWH = GET_SMALL_WAREHOUSE_BY_LOCATION($LocationWH,$LinkPSL);
?>
<div class="row">
    <div class="col-md-12">
        <div class="table-responsive">
            <table id="TableSmallWH" class="table table-bordered table-hover display">
                <thead class="theadCustom">
                    <tr>
                        <th class="text-center">#</th>
                        <th class="text-center">Warehouse</th>
                        <th class="text-center">Company</th>
                    </tr>
                </thead>
                <tbody><?php 
                while($RListSmallWH = mssql_fetch_assoc($QListSmallWH))
                {
                    ?>
                    <tr>
                        <td class="text-center"><button class="btn btn-sm btn-dark BtnSelect">Gunakan</button></td>
                        <td class="text-center"><?php echo trim($RListSmallWH['Warehouse']); ?></td>
                        <td class="text-center"><?php echo trim($RListSmallWH['Company']); ?></td>
                    </tr>
                    <?php
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