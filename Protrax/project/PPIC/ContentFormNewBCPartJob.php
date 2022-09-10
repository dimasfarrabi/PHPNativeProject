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
    $PPIC = htmlspecialchars(trim($_POST['PPIC']), ENT_QUOTES, "UTF-8");
    $TypeData = htmlspecialchars(trim($_POST['TypeData']), ENT_QUOTES, "UTF-8");
    $Keywords = htmlspecialchars(trim($_POST['Keywords']), ENT_QUOTES, "UTF-8");
    $Year = htmlspecialchars(trim($_POST['Year']), ENT_QUOTES, "UTF-8");
?>   
<div class="col-md-12">
    <div class="table-responsive">
        <table class="table table-border table-hover" id="TableBarcodeRegistered">
            <thead class="theadCustom">
                <tr>
                    <th class="text-center">Barcode_ID</th>
                    <th class="text-center">PartNo</th>
                    <th class="text-center">QtyInitial</th>
                    <th class="text-center">FinishingCode</th>
                    <th class="text-center">WOChild</th>
                    <th class="text-center">ExpenseAllocation</th>
                    <th class="text-center">Product</th>
                    <th class="text-center">Quote</th>
                    <th class="text-center">PM</th>
                    <th class="text-center">CreatedBy</th>
                    <th class="text-center">DateCreate</th>
                </tr>
            </thead>
            <tbody><?php 
            $QData = LOAD_BARCODE_REGISTERED($PPIC,$TypeData,$Keywords,$Year,$linkMACHWebTrax);
            while($RData = mssql_fetch_assoc($QData))
            {
                ?>
                <tr>
                    <td class="text-center"><?php echo utf8_encode(trim($RData['Barcode_ID'])); ?></td>
                    <td class="text-center"><?php echo utf8_encode(trim($RData['PartNo'])); ?></td>
                    <td class="text-center"><?php echo utf8_encode(trim($RData['QtyInitial'])); ?></td>
                    <td class="text-center"><?php echo utf8_encode(trim($RData['FinishingCode'])); ?></td>
                    <td class="text-start"><?php echo utf8_encode(trim($RData['WOChild'])); ?></td>
                    <td class="text-start"><?php echo utf8_encode(trim($RData['ExpenseAllocation'])); ?></td>
                    <td class="text-start"><?php echo utf8_encode(trim($RData['Product'])); ?></td>
                    <td class="text-start"><?php echo utf8_encode(trim($RData['Quote'])); ?></td>
                    <td class="text-start"><?php echo utf8_encode(trim($RData['PM'])); ?></td>
                    <td class="text-start"><?php echo utf8_encode(trim($RData['CreatedBy'])); ?></td>
                    <td class="text-center"><?php echo utf8_encode(trim($RData['DateCreate'])); ?></td>
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