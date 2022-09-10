<?php
session_start();
require_once("../../../ConfigDB.php");
require_once("../../../src/Modules/ModuleLogin.php");
require_once("../Modules/ModuleWOMapping.php");

date_default_timezone_set("Asia/Jakarta");
$Time = date("Y-m-d H:i:s");
# data session
/*
$FullName = strtoupper(base64_decode($_SESSION['FullNameUserProTrax']));
$UserNameSession = base64_decode(base64_decode($_SESSION['UIDProTrax']));

if(!session_is_registered("UIDProTrax"))
{
    ?>
    <script language="javascript">
        window.location.replace("https://protrax.formulatrix.com/");
    </script>
    <?php
    exit();
}
*/
if($_SERVER['REQUEST_METHOD'] == "POST")
{
    $ValLocation = htmlspecialchars(trim($_POST['ValLocation']), ENT_QUOTES, "UTF-8");
    $ValWOMappingID = htmlspecialchars(trim($_POST['ValWOMappingID']), ENT_QUOTES, "UTF-8");
    $ValClosedTime = htmlspecialchars(trim($_POST['ValClosedTime']), ENT_QUOTES, "UTF-8");
    $ValQtyClosed = htmlspecialchars(trim($_POST['ValQtyClosed']), ENT_QUOTES, "UTF-8");
    /*
    $DateNowSQL = date("Y-m-d");
    $YearPeriod = date("Y",strtotime($DateNowSQL));
    $MonthPeriod = date("m",strtotime($DateNowSQL));

    if($ValLocation == "PSM")
    {
        if($ValClosedTime == "OPEN")    # proses close wo
        {
            # data detail wo
            $QDataDetailWO = GET_DETAIL_WO_SELECTED_BY_ID_PSM($ValWOMappingID);
            $RDataDetailWO = mssql_fetch_assoc($QDataDetailWO);
            $ValQuoteCategory = trim($RDataDetailWO['QuoteCategory']);
            # list closed time by year
            $ArrListClosedTime = array();
            $ValNewClosedTime = '';
            $QListClosedTime = LIST_CLOSED_TIME_BY_PERIOD_CLOSED_PSM($YearPeriod,$ValQuoteCategory);
            while($RListClosedTime = mssql_fetch_assoc($QListClosedTime))
            {
                $TempQuoteCategory = trim($RListClosedTime['QuoteCategory']);
                $TempPeriod = trim($RListClosedTime['Period']);
                $TempHalf = trim($RListClosedTime['Half']);
                $TempMaxClosingDate = date("m/d/Y",strtotime(trim($RListClosedTime['MaxClosingDate'])));
                $TempClosedTimeLate = trim($RListClosedTime['ClosedTimeSetWhenLate']);
                $TempArray = array(
                    "QuoteCategory" => $TempQuoteCategory,
                    "Period" => $TempPeriod,
                    "Half" => $TempHalf,
                    "MaxClosingDate" => $TempMaxClosingDate,
                    "ClosedTimeLate" => $TempClosedTimeLate
                );
                array_push($ArrListClosedTime,$TempArray);
            }
            $BolCheck = "FALSE";
            $TempClosedTime = "";
            foreach($ArrListClosedTime as $ListClosedTime)
            {
                $ValQuoteCategory = $ListClosedTime['QuoteCategory'];
                $ValPeriod = $ListClosedTime['Period'];
                $ValHalf = $ListClosedTime['Half'];
                $ValMaxClosingDate = $ListClosedTime['MaxClosingDate'];
                $ValClosedTimeLate = $ListClosedTime['ClosedTimeLate'];
                $TempClosedTime = $ValClosedTimeLate;
                // if(strtotime($DateNowSQL) <= strtotime($TempMaxClosingDate))
                // {
                //     $BolCheck = "TRUE";
                //     $TempClosedTime = $ValPeriod.'-'.$ValHalf;
                // }
                // if($BolCheck == "TRUE")
                // {
                //     break;
                // }
            }

            $BolError = FALSE;
            $ResCloseWO = UPDATE_SET_CLOSEDTIME_WO_MAPPING_BY_ID_PSM($ValQtyClosed,$ValWOMappingID,$TempClosedTime,$Time,$FullName);
            if($ResCloseWO == "FALSE")
            {
                $BolError = TRUE;
            }
            $ResUpdateCloseWOWebtrax = UPDATE_WEBTRAX_SET_CLOSEDTIME_WO_MAPPING_BY_ID_PSM($ValQtyClosed,$ValWOMappingID,$TempClosedTime,$Time,$FullName,$linkMACHWebTrax);
            # set info error
            if($BolError == TRUE)
            {
                echo "0";
            }
            else
            {
                $TempTime = date('m/d/Y',strtotime($Time));
                echo "1#".$TempClosedTime."#".$TempTime."#".$ValQtyClosed;
            }
        }
        else    # proses reopen wo
        {
            $BolError = FALSE;
            $ResReopen = UPDATE_MACH_REOPEN_WO_MAPPING_BY_ID_PSM($ValWOMappingID);
            if($ResReopen == "FALSE")
            {
                $BolError = TRUE;
            }
            $ResReopenWebtrax = UPDATE_WEBTRAX_REOPEN_WO_MAPPING_BY_ID_PSM($ValWOMappingID,$linkMACHWebTrax);
            if($BolError == TRUE)
            {
                echo "0";
            }
            else
            {
                echo "1";
            }
        }
    }
    if($ValLocation == "PSL")
    {
        if($ValClosedTime == "OPEN")    # proses close wo
        {
            # data detail wo
            $QDataDetailWO = GET_DETAIL_WO_SELECTED_BY_ID($ValWOMappingID,$linkMACHWebTrax);
            $RDataDetailWO = mssql_fetch_assoc($QDataDetailWO);
            $ValQuoteCategory = trim($RDataDetailWO['QuoteCategory']);
            # list closed time by year
            $ArrListClosedTime = array();
            $ValNewClosedTime = '';
            $QListClosedTime = LIST_CLOSED_TIME_BY_PERIOD_CLOSED($YearPeriod,$ValQuoteCategory,$linkMACHWebTrax);
            while($RListClosedTime = mssql_fetch_assoc($QListClosedTime))
            {
                $TempQuoteCategory = trim($RListClosedTime['QuoteCategory']);
                $TempPeriod = trim($RListClosedTime['Period']);
                $TempHalf = trim($RListClosedTime['Half']);
                $TempMaxClosingDate = date("m/d/Y",strtotime(trim($RListClosedTime['MaxClosingDate'])));
                $TempClosedTimeLate = trim($RListClosedTime['ClosedTimeSetWhenLate']);
                $TempArray = array(
                    "QuoteCategory" => $TempQuoteCategory,
                    "Period" => $TempPeriod,
                    "Half" => $TempHalf,
                    "MaxClosingDate" => $TempMaxClosingDate,
                    "ClosedTimeLate" => $TempClosedTimeLate
                );
                array_push($ArrListClosedTime,$TempArray);
            }
            $BolCheck = "FALSE";
            $TempClosedTime = "";
            foreach($ArrListClosedTime as $ListClosedTime)
            {
                $ValQuoteCategory = $ListClosedTime['QuoteCategory'];
                $ValPeriod = $ListClosedTime['Period'];
                $ValHalf = $ListClosedTime['Half'];
                $ValMaxClosingDate = $ListClosedTime['MaxClosingDate'];
                $ValClosedTimeLate = $ListClosedTime['ClosedTimeLate'];
                $TempClosedTime = $ValClosedTimeLate;
                // if(strtotime($DateNowSQL) <= strtotime($TempMaxClosingDate))
                // {
                //     $BolCheck = "TRUE";
                //     $TempClosedTime = $ValPeriod.'-'.$ValHalf;
                // }
                // if($BolCheck == "TRUE")
                // {
                //     break;
                // }
            }

            $BolError = FALSE;
            $ResCloseWO = UPDATE_SET_CLOSEDTIME_WO_MAPPING_BY_ID($ValQtyClosed,$ValWOMappingID,$TempClosedTime,$Time,$FullName,$linkMACHWebTrax);
            if($ResCloseWO == "FALSE")
            {
                $BolError = TRUE;
            }
            $ResUpdateCloseWOWebtrax = UPDATE_WEBTRAX_SET_CLOSEDTIME_WO_MAPPING_BY_ID_PSL($ValQtyClosed,$ValWOMappingID,$TempClosedTime,$Time,$FullName,$linkMACHWebTrax);
            # set info error
            if($BolError == TRUE)
            {
                echo "0";
            }
            else
            {
                $TempTime = date('m/d/Y',strtotime($Time));
                echo "1#".$TempClosedTime."#".$TempTime."#".$ValQtyClosed;
            }
        }
        else    # proses reopen wo
        {
            $BolError = FALSE;
            $ResReopen = UPDATE_MACH_REOPEN_WO_MAPPING_BY_ID($ValWOMappingID,$linkMACHWebTrax);
            if($ResReopen == "FALSE")
            {
                $BolError = TRUE;
            }
            $ResReopenWebtrax = UPDATE_WEBTRAX_REOPEN_WO_MAPPING_BY_ID_PSL($ValWOMappingID,$linkMACHWebTrax);
            if($BolError == TRUE)
            {
                echo "0";
            }
            else
            {
                echo "1";
            }
        }
    }
    */
}
else
{
    ?>
    <script language="javascript">
        window.location.replace("https://protrax.formulatrix.com/");
    </script>
    <?php
    exit();  
}
?>