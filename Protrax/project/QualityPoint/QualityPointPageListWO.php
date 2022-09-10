<?php 
session_start();
require_once("../../ConfigDB.php");
require_once("../../src/Modules/ModuleLogin.php");
require_once("Modules/ModuleQualityPoint.php");
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
    if($RDataUserWebtrax['MnAdmin'] != "1")  
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
    $ValClosedTime = htmlspecialchars(trim($_POST['ValClosedTime']), ENT_QUOTES, "UTF-8");
?>
<div class="table-responsive">
            <table class="table table-bordered table-hover" id="ListProject">
                <thead class="theadCustom">
                    <tr>
                        <th class="text-center">Quote</th>
                    </tr>
                </thead>
                <tbody><?php
                $QListProject = LIST_QUOTE_QUALITY_POINT($ValClosedTime,$linkMACHWebTrax);
                while($RListProject = mssql_fetch_assoc($QListProject))
                {
                    $ValName = $RListProject['Quote'];
                    $ValIdx = $RListProject['Idx'];
                    $ValIdxEnc = base64_encode("ID".$ValIdx);
                    echo '<tr data-row="'.$ValIdxEnc.'" class="PointerListProject">';
                    echo '<td>'.$ValName.'</td>';
                    echo '</tr>';
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