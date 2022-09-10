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
    $ValActual = htmlspecialchars(trim($_POST['ValActual']), ENT_QUOTES, "UTF-8");
    $ValTargetMin = htmlspecialchars(trim($_POST['ValTargetMin']), ENT_QUOTES, "UTF-8");
    $ValTargetMax = htmlspecialchars(trim($_POST['ValTargetMax']), ENT_QUOTES, "UTF-8");
    $ValDataCookies = htmlspecialchars(trim($_POST['ValDataCookies']), ENT_QUOTES, "UTF-8");
    $ValDataCookies = base64_decode($ValDataCookies);
    $ArrDataCookies = explode("*",$ValDataCookies);
    $ValProjectID = $ArrDataCookies[1];
    $ValLocation = $ArrDataCookies[4];
    
    if(!is_numeric($ValActual))
    {
        echo "False#Invalid actual!";
        exit();
    }
    if(!is_numeric($ValTargetMin))
    {
        echo "False#Invalid target min!";
        exit();
    }
    if($ValTargetMax == ""){$ValTargetMax = "100";}
    if(!is_numeric($ValTargetMax))
    {
        echo "False#Invalid target max!";
        exit();
    }
    if($ValTargetMin > $ValTargetMax)
    {
        echo "False#Invalid input target min > target max!";
        exit();
    }
    # check input 
    if($ValActual[0] == "."){$ValActual = "0".$ValActual;}
    if($ValTargetMin[0] == "."){$ValTargetMin = "0".$ValTargetMin;}
    if($ValTargetMax[0] == "."){$ValTargetMax = "0".$ValTargetMax;}
    # data div
    // $QDataDivision = GET_DATA_DIVISION_IN_QUALITY_POINT($ValDivision,$linkMACHWebTrax);
    // $RDataDivision = mssql_fetch_assoc($QDataDivision);
    // $ValDivisionID = $RDataDivision['Division_ID'];
    $ValDivisionID = $ArrDataCookies[2];
    # count goal achievement
    $FirstVal = $ValActual - $ValTargetMin;
    $SecondVal = $ValTargetMax - $ValTargetMin;
    $ValGoalAchievement =  ($FirstVal / $SecondVal) * 100;
    if($ValGoalAchievement < 0){$ValGoalAchievement = "0";}
    if($ValGoalAchievement > 100){$ValGoalAchievement = "100";}
    $ValGoalAchievement = number_format((float)$ValGoalAchievement, 2, '.', ',');
    # check row 
    $QCheck = CHECK_ROW_QUALITY_POINT($ValClosedTime,$ValProjectID,$ValDivisionID,$ValLocation,$linkMACHWebTrax);
    if(mssql_num_rows($QCheck) == 0)
    {
        # input data
        $ValInsert = INSERT_NEW_QUALITY_POINT($ValClosedTime,$ValProjectID,$ValDivisionID,$ValActual,$ValTargetMin,$ValTargetMax,$ValGoalAchievement,$ValLocation,$linkMACHWebTrax);
        if($ValInsert == "True")
        {
            # hitung QA data
            // $QTotalQA = CHECK_ROW_QUALITY_POINT_QA($ValClosedTime,$ValProjectID,$ValLocation,$linkMACHWebTrax);
            $QTotalQA = GET_TOTAL_COUNT_AVG_QA($ValClosedTime,$ValProjectID,$ValLocation,$linkMACHWebTrax);
            $RTotalQA = mssql_fetch_assoc($QTotalQA);
            // $ResultQA = number_format((float)trim($RTotalQA['Result']), 2, '.', ',');
            $ResultQA = number_format((float)trim($RTotalQA['Result2']), 2, '.', ',');
            # check data QA
            $TotalRowQA = CHECK_DATA_QA($ValLocation,$ValClosedTime,$ValProjectID,'11',$linkMACHWebTrax);
            if($TotalRowQA == "0")
            {
                ADD_DATA_QA_TO_QUALITY_POINT($ValLocation,$ValClosedTime,$ValProjectID,"11",$ResultQA,$linkMACHWebTrax);
                $DTCookies = base64_encode($ValClosedTime."*".$ValProjectID."*11*QUALITY ASSURANCE*".$ValLocation);
                echo "True#$ValGoalAchievement#$ResultQA#$DTCookies";
            }
            else
            {
                UPDATE_DATA_QA_TO_QUALITY_POINT($ValLocation,$ValClosedTime,$ValProjectID,"11",$ResultQA,$linkMACHWebTrax);
                $DTCookies = base64_encode($ValClosedTime."*".$ValProjectID."*11*QUALITY ASSURANCE*".$ValLocation);
                echo "True#$ValGoalAchievement#$ResultQA#$DTCookies";
            }           
        }
        else
        {
            echo "False#Error add new data!";
        }
    }
    else
    {
        # get data
        $RCheck = mssql_fetch_assoc($QCheck);
        $IDCheck = $RCheck['Idx'];
        # update
        $ValUpdate = UPDATE_DATA_QUALITY_POINT($IDCheck,$ValActual,$ValTargetMin,$ValTargetMax,$ValGoalAchievement,$ValLocation,$linkMACHWebTrax);
        if($ValUpdate == "True")
        {
            # hitung QA data
            // $QTotalQA = CHECK_ROW_QUALITY_POINT_QA($ValClosedTime,$ValProjectID,$ValLocation,$linkMACHWebTrax);
            $QTotalQA = GET_TOTAL_COUNT_AVG_QA($ValClosedTime,$ValProjectID,$ValLocation,$linkMACHWebTrax);
            $RTotalQA = mssql_fetch_assoc($QTotalQA);
            // $ResultQA = number_format((float)trim($RTotalQA['Result']), 2, '.', ',');
            $ResultQA = number_format((float)trim($RTotalQA['Result2']), 2, '.', ',');
            # check data QA
            $TotalRowQA = CHECK_DATA_QA($ValLocation,$ValClosedTime,$ValProjectID,'11',$linkMACHWebTrax);
            if($TotalRowQA == "0")
            {
                ADD_DATA_QA_TO_QUALITY_POINT($ValLocation,$ValClosedTime,$ValProjectID,"11",$ResultQA,$linkMACHWebTrax);
                $DTCookies = base64_encode($ValClosedTime."*".$ValProjectID."*11*QUALITY ASSURANCE*".$ValLocation);
                echo "True#$ValGoalAchievement#$ResultQA#$DTCookies";
            }
            else
            {
                UPDATE_DATA_QA_TO_QUALITY_POINT($ValLocation,$ValClosedTime,$ValProjectID,"11",$ResultQA,$linkMACHWebTrax);
                $DTCookies = base64_encode($ValClosedTime."*".$ValProjectID."*11*QUALITY ASSURANCE*".$ValLocation);
                echo "True#$ValGoalAchievement#$ResultQA#$DTCookies";
            }
        }
        else
        {
            echo "False#Error update new data!";
        }
    }
}
else
{
    echo "";    
}
?>