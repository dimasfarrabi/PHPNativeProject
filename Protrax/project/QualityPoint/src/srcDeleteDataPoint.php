<?php 
session_start();
require_once("../../../ConfigDB.php");
require_once("../../../src/Modules/ModuleLogin.php");
require_once("../Modules/ModuleQualityPoint.php");

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

if($_SERVER['REQUEST_METHOD'] == "POST")
{
    $ValQuote = htmlspecialchars(trim($_POST['ValQuote']), ENT_QUOTES, "UTF-8");
    $ValClosedTime = htmlspecialchars(trim($_POST['ValClosedTime']), ENT_QUOTES, "UTF-8");
    $ValDivision = htmlspecialchars(trim($_POST['ValDivision']), ENT_QUOTES, "UTF-8");
    $ValDataCookies = htmlspecialchars(trim($_POST['ValDataCookies']), ENT_QUOTES, "UTF-8");
    $ValDataCookies = base64_decode($ValDataCookies);
    $ArrDataCookies = explode("*",$ValDataCookies);
    $ValProjectID = $ArrDataCookies[1];
    $ValLocation = $ArrDataCookies[4];
    # get data selected
    $QData = GET_DETAIL_MODAL_QP_SELECTED($ValClosedTime,$ValDivision,$ValQuote,$linkMACHWebTrax);
    $RData = mssql_fetch_assoc($QData);
    $IdxData = trim($RData['Idx']);
    # delete data
    $ResDelete = DELETE_DATA_SELECTED($IdxData,$linkMACHWebTrax);
    if($ResDelete == "True")
    {        
        # hitung QA data selain qa
        $QTotalQA = GET_TOTAL_COUNT_AVG_QA($ValClosedTime,$ValProjectID,$ValLocation,$linkMACHWebTrax);
        if(mssql_num_rows($QTotalQA) != "0")
        {
            # hitung QA data
            $QTotalQA = GET_TOTAL_COUNT_AVG_QA($ValClosedTime,$ValProjectID,$ValLocation,$linkMACHWebTrax);
            $RTotalQA = mssql_fetch_assoc($QTotalQA);
            //$ResultQA = number_format((float)trim($RTotalQA['Result']), 2, '.', ',');
            $ResultQA = number_format((float)trim($RTotalQA['Result2']), 2, '.', ',');            
            UPDATE_DATA_QA_TO_QUALITY_POINT($ValLocation,$ValClosedTime,$ValProjectID,"11",$ResultQA,$linkMACHWebTrax);
            $DTCookies = base64_encode($ValClosedTime."*".$ValProjectID."*11*QUALITY ASSURANCE*".$ValLocation);
            echo "True#$ResultQA#$DTCookies";     
        }
        else
        {
            # hapus qa
            DELETE_DATA_QA($ValLocation,$ValClosedTime,$ValProjectID,'11',$linkMACHWebTrax);
            $ResultQA = number_format("0", 2, '.', ',');
            $DTCookies = base64_encode($ValClosedTime."*".$ValProjectID."*11*QUALITY ASSURANCE*".$ValLocation);
            echo "True#$ResultQA#$DTCookies";
        }
    }
    else
    {
        echo "False#Error delete data!";
    }
}
else
{
    echo "";    
}
?>