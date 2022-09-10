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
    $Category = htmlspecialchars(trim($_POST['Category']), ENT_QUOTES, "UTF-8");
    $Keywords = htmlspecialchars(trim($_POST['Keywords']), ENT_QUOTES, "UTF-8");
    
?>   
<div class="col-md-12">
    <div class="table-responsive">
        <table class="table table-border table-hover" id="TablePartNo">
            <thead class="theadCustom">
                <tr>
                    <th class="text-center">#</th>
                    <th class="text-center">PartNo</th>
                    <th class="text-center">PartDescription</th>
                    <th class="text-center">UOM</th>
                    <th class="text-center">CurrencySymbol</th>
                    <th class="text-center">UnitCost</th>
                    <th class="text-center">UnitCost<br>BasedCurrency</th>
                    <th class="text-center">Category</th>
                    <th class="text-center">BondedZone<br>Category</th>
                </tr>
            </thead>
            <tbody><?php 
            $QData = SEARCH_PART_NO($Category,$Keywords,$linkMACHWebTrax);
            while($RData = mssql_fetch_assoc($QData))
            {
                ?>
                <tr>
                    <td class="text-start"><button class="btn btn-sm btn-dark BtnSelect">Gunakan</button></td>
                    <td class="text-start"><?php echo utf8_encode(trim($RData['PartNo'])); ?></td>
                    <td class="text-start"><?php echo utf8_encode(trim($RData['PartDescription'])); ?></td>
                    <td class="text-center"><?php echo utf8_encode(trim($RData['UOM'])); ?></td>
                    <td class="text-center"><?php echo utf8_encode(trim($RData['CurrencySymbol'])); ?></td>
                    <td class="text-end"><?php echo utf8_encode(trim($RData['UnitCost'])); ?></td>
                    <td class="text-end"><?php echo utf8_encode(trim($RData['UnitCostBasedCurrency'])); ?></td>
                    <td class="text-center"><?php echo utf8_encode(trim($RData['Category'])); ?></td>
                    <td class="text-center"><?php echo utf8_encode(trim($RData['BondedZoneCategory'])); ?></td>
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