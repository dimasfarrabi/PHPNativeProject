<?php 
session_start();
require_once("../../ConfigDB.php");
// require_once("../../ConfigDB2.php");
require_once("../../src/Modules/ModuleLogin.php");
require_once("Modules/ModuleInOutPartTBZ.php");
date_default_timezone_set("Asia/Jakarta");
$DateNow = date("m/d/Y");
$YearNow = date("Y");
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
    $InputWOID = htmlspecialchars(trim($_POST['InputWOID']), ENT_QUOTES, "UTF-8");
    # check WO
    $QDataWO = GET_WO_DETAIL_INFO($InputWOID,$LinkPSL);
    if(sqlsrv_num_rows($QDataWO) > 0)
    {
        $RDataWO = sqlsrv_fetch_array($QDataWO);
        $ResClosedTime = trim($RDataWO['ClosedTime']);
        if($ResClosedTime == "OPEN")
        {
            $ValWOChild = trim($RDataWO['WOChild']);
            $ValExpenseAllocation = trim($RDataWO['ExpenseAllocation']); 
            $ValProduct = trim($RDataWO['Product']); 
            $ValWOMappingID = trim($RDataWO['Idx']); 
            $ValString = "2#".$ValWOChild."&&".$ValProduct."&&".$ValExpenseAllocation."&&".$ValWOMappingID;
            echo $ValString;
            exit();
        }
        else
        {
            echo "1#-";
            exit();
        }
    }
    else
    {
        echo "0#-";
        exit();
    }    
}
else
{
    echo "";    
}
?>