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

if($_SERVER['REQUEST_METHOD'] == "POST")
{   
    $ValProjectName = htmlspecialchars(trim($_POST['ValProjectName']), ENT_QUOTES, "UTF-8");
    $ValProjectIDEnc = htmlspecialchars(trim($_POST['ValProjectID']), ENT_QUOTES, "UTF-8");
    $ValClosedTime = htmlspecialchars(trim($_POST['ValClosedTime']), ENT_QUOTES, "UTF-8");
    $ValProjectID = str_replace("ID","",base64_decode($ValProjectIDEnc));   
    # set array data
    $ArrListDivision = array();
    $ArrListConst = array();
    $ArrListProjectRate = array();
    $ArrListProjectDivision = array();
    $ArrListQualityPoint = array();
    $ArrSortProjectDivision = array();
    $ArrDataResultQuality = array();
    $ArrDataResultQualityPSL = array();
    $ArrDataResultQualityPSM = array();
    $ArrListDivisionPSL = array();
    $ArrListDivisionPSM = array();
    # data list sort order division
    $QListDivision = GET_LIST_QUALITY_POINT_DIVISION($linkMACHWebTrax);
    while($RListDivision = mssql_fetch_assoc($QListDivision))
    {
        $ValSortOrder = trim($RListDivision['SortOrder']);
        $ValDivision = trim($RListDivision['Division']);
        $TempArray = array(
            "SortOrder" => $ValSortOrder,
            "Division" => $ValDivision,
        );
        array_push($ArrListDivision,$TempArray);
    }
    # data list division psl
    $QListDivisionPSL = GET_LIST_DIVISION($linkMACHWebTrax);
    while($RListDivisionPSL = mssql_fetch_assoc($QListDivisionPSL))
    {
        $TempArray = array(
            "DivisionID" => trim($RListDivisionPSL['Division_ID']),
            "DivisionName" => trim($RListDivisionPSL['DivisionName'])
        );
        array_push($ArrListDivisionPSL,$TempArray);    
    }    
    # data list const
    $QListConst = GET_LIST_CONST_QUALITY_VALUE($ValClosedTime,$linkMACHWebTrax);
    while($RListConst = mssql_fetch_assoc($QListConst))
    {
        $ValLocation = trim($RListConst['Location']);
        $ValClosingHalf = trim($RListConst['ClosingHalf']);
        $ValProjectID = trim($RListConst['Project_ID']);
        $ValCostAllocation = trim($RListConst['CostAllocation']);
        $ValConstValue = trim($RListConst['ConstValue']);   
        $TempArray = array(
            "Location" => $ValLocation,
            "ClosingHalf" => $ValClosingHalf,
            "ProjectID" => $ValProjectID,
            "CostAllocation" => $ValCostAllocation,
            "ConstValue" => $ValConstValue,
        );
        array_push($ArrListConst,$TempArray);
    }
    # data list project rate
    $QListProjectRate = GET_LIST_PROJECT_REJECT_RATE($ValClosedTime,$linkMACHWebTrax);
    while($RListProjectRate = mssql_fetch_assoc($QListProjectRate))
    {
        $ValLocation = trim($RListProjectRate['Location']);
        $ValProjectID = trim($RListProjectRate['ProjectID']);
        $ValDivisionID = trim($RListProjectRate['Division_ID']);
        $ValClosingHalf = trim($RListProjectRate['ClosingHalf']);
        $ValRejectRate = trim($RListProjectRate['RejectRate']);
        $ValTotalQtyIn = trim($RListProjectRate['TotalQtyIn']);
        $ValTotalQtyOut = trim($RListProjectRate['TotalQtyOut']);
        $ValLastUpdated = trim($RListProjectRate['LastUpdated2']);

        $TempArray = array(
            "Location" => $ValLocation,
            "ProjectID" => $ValProjectID,
            "DivisionID" => $ValDivisionID,
            "ClosingHalf" => $ValClosingHalf,
            "RejectRate" => $ValRejectRate,
            "TotalQtyIn" => $ValTotalQtyIn,
            "TotalQtyOut" => $ValTotalQtyOut,
            "LastUpdated2" => $ValLastUpdated
        );
        array_push($ArrListProjectRate,$TempArray);
    }
    # data list quality point
    $QListQualityPoint = GET_LIST_QUALITY_POINT($ValClosedTime,$linkMACHWebTrax);
    while($RListQualityPoint = mssql_fetch_assoc($QListQualityPoint))
    {
        $ValLocation = trim($RListQualityPoint['Location']);
        $ValClosingHalf = trim($RListQualityPoint['ClosingHalf']);
        $ValProjectID = trim($RListQualityPoint['Project_ID']);
        $ValDivisionID = trim($RListQualityPoint['QPDivision_ID']);
        $ValActual = trim($RListQualityPoint['Actual']);
        $ValTargetMin = trim($RListQualityPoint['TargetMin']);
        $ValTargetMax = trim($RListQualityPoint['TargetMax']);
        $ValGoalAchievement = trim($RListQualityPoint['GoalAchievement']);
        $TempArray = array(
            "Location" => $ValLocation,
            "ClosingHalf" => $ValClosingHalf,
            "ProjectID" => $ValProjectID,
            "DivisionID" => $ValDivisionID,
            "Actual" => $ValActual,
            "TargetMin" => $ValTargetMin,
            "TargetMax" => $ValTargetMax,
            "GoalAchievement" => $ValGoalAchievement
        );
        array_push($ArrListQualityPoint,$TempArray);
    }
    # data header PSL
    $QDataHeader1 = GET_LIST_DIVISION_BY_PROJECT($ValProjectName,$ValClosedTime,$linkMACHWebTrax);
    while ($RDataHeader1 = mssql_fetch_assoc($QDataHeader1))
    {
        $ValClosedTime1 = trim($RDataHeader1['ClosedTime']);
        $ValDivision1 = trim($RDataHeader1['Division']);
        $ValProjectID1 = trim($RDataHeader1['Idx']);
        $ValProjectName1 = trim($RDataHeader1['ProjectName']);
        $ValDivisionID1 = trim($RDataHeader1['Division_ID']);
        $TempArray = array(
            "ClosedTime" => $ValClosedTime1,
            "DivisionName" => $ValDivision1,
            "ProjectID" => $ValProjectID1,
            "ProjectName" => $ValProjectName1,
            "DivisionID" => $ValDivisionID1,
            "Location" => "PSL",
        );
        array_push($ArrListProjectDivision,$TempArray);
    }
    # data header PSM  
    $QDataHeader2 = GET_LIST_DIVISION_BY_PROJECT_PSM($ValProjectName,$ValClosedTime);
    while ($RDataHeader2 = mssql_fetch_assoc($QDataHeader2))
    {
        $ValClosedTime2 = trim($RDataHeader2['ClosedTime']);
        $ValDivision2 = trim($RDataHeader2['Division']);
        $ValProjectID2 = trim($RDataHeader2['Idx']);
        $ValProjectName2 = trim($RDataHeader2['ProjectName']);
        $ValDivisionID2 = trim($RDataHeader2['Division_ID']);
        $TempArray = array(
            "ClosedTime" => $ValClosedTime2,
            "DivisionName" => $ValDivision2,
            "ProjectID" => $ValProjectID2,
            "ProjectName" => $ValProjectName2,
            "DivisionID" => $ValDivisionID2,
            "Location" => "PSM",
        );
        array_push($ArrListProjectDivision,$TempArray);
    }    
    # data list division psm
    $QListDivisionPSM = GET_LIST_DIVISION_PSM();
    while($RListDivisionPSM = mssql_fetch_assoc($QListDivisionPSM))
    {
        $TempArray = array(
            "DivisionID" => trim($RListDivisionPSM['Division_ID']),
            "DivisionName" => trim($RListDivisionPSM['DivisionName'])
        );
        array_push($ArrListDivisionPSM,$TempArray);    
    }   
    
    foreach ($ArrListDivision as $ListDivision)
    {
        $ListDivisionSortOrder = trim($ListDivision['SortOrder']);
        $ListDivisionDivisionName = trim($ListDivision['Division']);
        $BolCheckListPSL = FALSE;
        $BolCheckListPSM = FALSE;
        $BolCheckListAll = TRUE;
        foreach($ArrListProjectDivision as $ListProjectDivision)
        {
            $ValClosedTime = $ListProjectDivision['ClosedTime'];
            $ValDivisionName = $ListProjectDivision['DivisionName'];
            $ValProjectID = $ListProjectDivision['ProjectID'];
            $ValProjectName = $ListProjectDivision['ProjectName'];
            $ValDivisionID = $ListProjectDivision['DivisionID'];
            $ValLocation = $ListProjectDivision['Location'];

            if($ListDivisionDivisionName == $ValDivisionName)
            {
                if($ValLocation == "PSL")
                {
                    foreach($ArrListDivisionPSL as $ListDivisionPSL)
                    {
                        if($ListDivisionDivisionName == $ListDivisionPSL['DivisionName'])
                        {
                            $TempArray = array(
                                "SortOrder" => $ListDivisionSortOrder,
                                "ClosedTime" => $ValClosedTime,
                                "DivisionName" =>  $ValDivisionName,
                                "ProjectID" => $ValProjectID,
                                "ProjectName" => $ValProjectName,
                                "DivisionID" => $ValDivisionID,
                                "Location" =>  $ValLocation
                            );
                            array_push($ArrSortProjectDivision,$TempArray);
                        }
                    }
                    $BolCheckListPSL = TRUE;
                }
                if($ValLocation == "PSM")
                {
                    foreach($ArrListDivisionPSM as $ListDivisionPSM)
                    {
                        if($ListDivisionDivisionName == $ListDivisionPSM['DivisionName'])
                        {
                            $TempArray = array(
                                "SortOrder" => $ListDivisionSortOrder,
                                "ClosedTime" => $ValClosedTime,
                                "DivisionName" =>  $ValDivisionName,
                                "ProjectID" => $ValProjectID,
                                "ProjectName" => $ValProjectName,
                                "DivisionID" => $ValDivisionID,
                                "Location" =>  $ValLocation
                            );
                            array_push($ArrSortProjectDivision,$TempArray);
                        }
                    }
                    $BolCheckListPSM = TRUE;
                }
                $BolCheckListAll = FALSE;
            }
        }
        if($BolCheckListAll == TRUE)
        {
            $ValDivIDPSL = "";
            $ValDivIDPSM = "";
            foreach($ArrListDivisionPSL as $ListDivisionPSL)
            {
                if($ListDivisionPSL['DivisionName'] == $ListDivisionDivisionName)
                {
                    $ValDivIDPSL = $ListDivisionPSL['DivisionID'];
                }
            }
            foreach($ArrListDivisionPSM as $ListDivisionPSM)
            {
                if($ListDivisionPSM['DivisionName'] == $ListDivisionDivisionName)
                {
                    $ValDivIDPSM = $ListDivisionPSM['DivisionID'];
                }
            }
            if($ValDivIDPSL != "")
            {
                $TempArray = array(
                    "SortOrder" => $ListDivisionSortOrder,
                    "ClosedTime" => $ValClosedTime,
                    "DivisionName" => $ListDivisionDivisionName,
                    "ProjectID" => $ValProjectID,
                    "ProjectName" => $ValProjectName,
                    "DivisionID" => $ValDivIDPSL,
                    "Location" =>  "PSL"
                );
                array_push($ArrSortProjectDivision,$TempArray);
            }
            if($ValDivIDPSM != "")
            {
                $TempArray2 = array(
                    "SortOrder" => $ListDivisionSortOrder,
                    "ClosedTime" => $ValClosedTime,
                    "DivisionName" => $ListDivisionDivisionName,
                    "ProjectID" => $ValProjectID,
                    "ProjectName" => $ValProjectName,
                    "DivisionID" => $ValDivIDPSM,
                    "Location" =>  "PSM"
                );
                array_push($ArrSortProjectDivision,$TempArray2);
            }
        }
        else
        {
            if($BolCheckListPSL == FALSE)
            {
                $ValDivIDPSL = "";
                foreach($ArrListDivisionPSL as $ListDivisionPSL)
                {
                    if($ListDivisionPSL['DivisionName'] == $ListDivisionDivisionName)
                    {
                        $ValDivIDPSL = $ListDivisionPSL['DivisionID'];
                    }
                }
                if($ValDivIDPSL != "")
                {
                    $TempArray = array(
                        "SortOrder" => $ListDivisionSortOrder,
                        "ClosedTime" => $ValClosedTime,
                        "DivisionName" => $ListDivisionDivisionName,
                        "ProjectID" => $ValProjectID,
                        "ProjectName" => $ValProjectName,
                        "DivisionID" => $ValDivIDPSL,
                        "Location" =>  "PSL"
                    );
                    array_push($ArrSortProjectDivision,$TempArray);
                    $BolCheckListPSL = TRUE;
                }
            }
            if($BolCheckListPSM == FALSE)
            {
                $ValDivIDPSM = "";
                foreach($ArrListDivisionPSM as $ListDivisionPSM)
                {
                    if($ListDivisionPSM['DivisionName'] == $ListDivisionDivisionName)
                    {
                        $ValDivIDPSM = $ListDivisionPSM['DivisionID'];
                    }
                }
                if($ValDivIDPSM != "")
                {
                    $TempArray2 = array(
                        "SortOrder" => $ListDivisionSortOrder,
                        "ClosedTime" => $ValClosedTime,
                        "DivisionName" => $ListDivisionDivisionName,
                        "ProjectID" => $ValProjectID,
                        "ProjectName" => $ValProjectName,
                        "DivisionID" => $ValDivIDPSM,
                        "Location" =>  "PSM"
                    );
                    array_push($ArrSortProjectDivision,$TempArray2);
                    $BolCheckListPSM = TRUE;
                }
            }            
        }
    }       
    # data result
    foreach($ArrSortProjectDivision as $DataSorting)
    {
        $ValSortOrder = trim($DataSorting['SortOrder']);
        $ValClosedTime = trim($DataSorting['ClosedTime']);
        $ValDivisionName = trim($DataSorting['DivisionName']);
        $ValProjectID = trim($DataSorting['ProjectID']);
        $ValProjectName = trim($DataSorting['ProjectName']);
        $ValDivisionID = trim($DataSorting['DivisionID']);
        $ValLocation = trim($DataSorting['Location']);
        # set const
        $TempConstValue = "0.00";
        foreach ($ArrListConst as $ListConst)
        {
            $ArrDataLocation = trim($ListConst['Location']);
            $ArrDataHalf = trim($ListConst['ClosingHalf']);
            $ArrDataProjectID = trim($ListConst['ProjectID']);
            $ArrDataAllocation = trim($ListConst['CostAllocation']);
            $ArrDataConst = trim($ListConst['ConstValue']);

            if(($ArrDataLocation == $ValLocation) && ($ValProjectID == $ArrDataProjectID) && 
            ($ValClosedTime == $ArrDataHalf) && ($ValDivisionName == $ArrDataAllocation))
            {
                $TempConstValue = $ArrDataConst;
            }
        }
        # set reject rate
        $TempRejectRate = "0.00";
        $TempTotalQtyIn = "0.00";
        $TempTotalQtyOut = "0.00";
        $TempLastUpdatedProjectRate = date("Y-m-d H:i:s");
        foreach ($ArrListProjectRate as $ListProjectRate)
        {
            $ArrDataLocation = trim($ListProjectRate['Location']);
            $ArrDataProjectID = trim($ListProjectRate['ProjectID']);
            $ArrDataDivisionID = trim($ListProjectRate['DivisionID']);
            $ArrDataClosingHalf = trim($ListProjectRate['ClosingHalf']);
            $ArrDataRejectRate = trim($ListProjectRate['RejectRate']);
            $ArrDataTotalQtyIn = trim($ListProjectRate['TotalQtyIn']);
            $ArrDataTotalQtyOut = trim($ListProjectRate['TotalQtyOut']);
            $ArrDataLastUpdated2 = trim($ListProjectRate['LastUpdated2']);

            if(($ArrDataLocation == $ValLocation) && ($ValProjectID == $ArrDataProjectID) && 
            ($ValClosedTime == $ArrDataClosingHalf) && ($ValDivisionID == $ArrDataDivisionID))
            {
                $TempRejectRate = $ArrDataRejectRate;
                $TempTotalQtyIn = $ArrDataTotalQtyIn;
                $TempTotalQtyOut = $ArrDataTotalQtyOut;
                $TempLastUpdatedProjectRate = $ArrDataLastUpdated2;
            }
        }
        # set actual 
        $TempActual = "0.00";
        $TempTargetMin = "0.00";
        $TempTargetMax = "0.00";
        $TempGoal = "0.00";
        foreach($ArrListQualityPoint as $ListQualityPoint)
        {
            $ArrDataLocation = trim($ListQualityPoint['Location']);
            $ArrDataClosingHalf = trim($ListQualityPoint['ClosingHalf']);
            $ArrDataProjectID = trim($ListQualityPoint['ProjectID']);
            $ArrDataDivisionID = trim($ListQualityPoint['DivisionID']);
            $ArrDataActual = trim($ListQualityPoint['Actual']);
            $ArrDataTargetMin = trim($ListQualityPoint['TargetMin']);
            $ArrDataTargetMax = trim($ListQualityPoint['TargetMax']);
            $ArrDataGoalAchievement = trim($ListQualityPoint['GoalAchievement']);
            
            if(($ArrDataLocation == $ValLocation) && ($ValProjectID == $ArrDataProjectID) && 
            ($ValClosedTime == $ArrDataClosingHalf) && ($ValDivisionID == $ArrDataDivisionID))
            {
                $TempActual = $ArrDataActual;
                $TempTargetMin = $ArrDataTargetMin;
                $TempTargetMax = $ArrDataTargetMax;
                $TempGoal = $ArrDataGoalAchievement;
            }
        }

        $TempArray = array(
            "SortOrder" => $ValSortOrder,
            "ClosedTime" => $ValClosedTime,
            "DivisionName" => $ValDivisionName,
            "ProjectID" => $ValProjectID,
            "ProjectName" => $ValProjectName,
            "DivisionID" => $ValDivisionID,
            "ConstValue" => $TempConstValue,
            "Location" => $ValLocation,
            "TotalQtyIn" => $TempTotalQtyIn,
            "TotalQtyOut" => $TempTotalQtyOut,
            "RejectRate" => $TempRejectRate,
            "LastUpdated" => $TempLastUpdatedProjectRate,
            "Actual" => $TempActual,
            "TargetMin" => $TempTargetMin,
            "TargetMax" => $TempTargetMax,
            "Goal" => $TempGoal
        );
        array_push($ArrDataResultQuality,$TempArray);
    }    
    
    $ArrDataResultQualityPSL = array();
    $ArrDataResultQualityPSM = array();
    foreach($ArrDataResultQuality as $DataResultQuality)
    {
        $ValSortOrder = trim($DataResultQuality['SortOrder']);
        $ValClosedTime = trim($DataResultQuality['ClosedTime']);
        $ValDivisionName = trim($DataResultQuality['DivisionName']);
        $ValProjectID = trim($DataResultQuality['ProjectID']);
        $ValProjectName = trim($DataResultQuality['ProjectName']);
        $ValDivisionID = trim($DataResultQuality['DivisionID']);
        $ValConstValue = trim($DataResultQuality['ConstValue']);
        $ValLocation = trim($DataResultQuality['Location']);
        $ValTotalQtyIn = trim($DataResultQuality['TotalQtyIn']);
        $ValTotalQtyOut = trim($DataResultQuality['TotalQtyOut']);
        $ValRejectRate = trim($DataResultQuality['RejectRate']);
        $ValLastUpdated = trim($DataResultQuality['LastUpdated']);
        $ValActual = trim($DataResultQuality['Actual']);
        $ValTargetMin = trim($DataResultQuality['TargetMin']);
        $ValTargetMax = trim($DataResultQuality['TargetMax']);
        $ValGoal = trim($DataResultQuality['Goal']);
        if($ValLocation == "PSL")
        {
            $TempArrayPSL = array(
                "SortOrder" => $ValSortOrder,
                "ClosedTime" => $ValClosedTime,
                "DivisionName" => $ValDivisionName,
                "ProjectID" => $ValProjectID,
                "ProjectName" => $ValProjectName,
                "DivisionID" => $ValDivisionID,
                "ConstValue" => $ValConstValue,
                "Location" => $ValLocation,
                "TotalQtyIn" => $ValTotalQtyIn,
                "TotalQtyOut" => $ValTotalQtyOut,
                "RejectRate" => $ValRejectRate,
                "LastUpdated" => $ValLastUpdated,
                "Actual" => $ValActual,
                "TargetMin" => $ValTargetMin,
                "TargetMax" => $ValTargetMax,
                "Goal" => $ValGoal
            );
            array_push($ArrDataResultQualityPSL,$TempArrayPSL);
        }
        else
        {
            $TempArrayPSM = array(
                "SortOrder" => $ValSortOrder,
                "ClosedTime" => $ValClosedTime,
                "DivisionName" => $ValDivisionName,
                "ProjectID" => $ValProjectID,
                "ProjectName" => $ValProjectName,
                "DivisionID" => $ValDivisionID,
                "ConstValue" => $ValConstValue,
                "Location" => $ValLocation,
                "TotalQtyIn" => $ValTotalQtyIn,
                "TotalQtyOut" => $ValTotalQtyOut,
                "RejectRate" => $ValRejectRate,
                "LastUpdated" => $ValLastUpdated,
                "Actual" => $ValActual,
                "TargetMin" => $ValTargetMin,
                "TargetMax" => $ValTargetMax,
                "Goal" => $ValGoal
            );
            array_push($ArrDataResultQualityPSM,$TempArrayPSM);
        }
    }

if(count($ArrDataResultQualityPSL) !="0")
{?>
<div class="col-md-12">Quote : <strong><?php echo $ValProjectName; ?></strong>. Closed Time : <strong><?php echo $ValClosedTime; ?></strong>.<?php // Location : <strong>Salatiga</strong>.?></div>
<div class="col-md-12">
    <div class="table-responsive">
        <table class="table table-bordered table-hover" id="TableProjectSelectedA">
            <thead class="theadCustom">
                <tr>
                    <th class="text-center trowCustom" width="30">No</th>
                    <th class="text-center trowCustom">Division</th>
                    <th class="text-center trowCustom" width="100">Actual (%)</th>
                    <th class="text-center trowCustom" width="100">TargetMin (%)</th>
                    <th class="text-center trowCustom" width="100">TargetMax (%)</th>
                    <th class="text-center trowCustom" width="100">Goal<br>Achievement<br>(%)</th>
                    <th class="text-center trowCustom" width="10">#</th>
                </tr>
            </thead>
            <tbody><?php 
            $No = 1; 
            foreach ($ArrDataResultQualityPSL as $DataResultQualityPSL)
            {
                $ValSortOrder = trim($DataResultQualityPSL['SortOrder']);
                $ValClosedTime = trim($DataResultQualityPSL['ClosedTime']);
                $ValDivisionName = trim($DataResultQualityPSL['DivisionName']);
                $ValProjectID = trim($DataResultQualityPSL['ProjectID']);
                $ValProjectName = trim($DataResultQualityPSL['ProjectName']);
                $ValDivisionID = trim($DataResultQualityPSL['DivisionID']);
                $ValConstValue = trim($DataResultQualityPSL['ConstValue']);
                $ValLocation = trim($DataResultQualityPSL['Location']);
                $ValTotalQtyIn = trim($DataResultQualityPSL['TotalQtyIn']);
                $ValTotalQtyOut = trim($DataResultQualityPSL['TotalQtyOut']);
                $ValRejectRate = trim($DataResultQualityPSL['RejectRate']);
                $ValLastUpdated = trim($DataResultQualityPSL['LastUpdated']);
                $ValActual = trim($DataResultQualityPSL['Actual']);
                $ValTargetMin = trim($DataResultQualityPSL['TargetMin']);
                $ValTargetMax = trim($DataResultQualityPSL['TargetMax']);
                $ValGoal = trim($DataResultQualityPSL['Goal']);

                $RowEnc = base64_encode($ValClosedTime."*".$ValProjectID."*".$ValDivisionID."*".$ValDivisionName."*".$ValLocation);
                $RowEnc2 = base64_encode($ValProjectName."*".$ValClosedTime."*A");
                $ValActual = number_format((float)$ValActual, 2, '.', ',');
                $ValTargetMin = number_format((float)$ValTargetMin, 2, '.', ',');
                $ValTargetMax = number_format((float)$ValTargetMax, 2, '.', ',');
                $ValGoal = number_format((float)$ValGoal, 2, '.', ',');
                # btn form update
                $ValOptForm = '<span class="PointerList UpdateRow" aria-hidden="true" data-bs-toggle="modal" data-ecode="'.$RowEnc.'" data-dcode="'.$RowEnc2.'" data-bs-target="#ModalUpdatePSL" title="Update"><i class="bi bi-pencil-square"></i></span>';
                // if($ValDivisionID == "9" || $ValDivisionID == "21")
                // {
                    // $ValOptForm = "";
                    // $ValOptDetails = ' data-details="'.$RowEnc.'"';
                // }
                // else
                // {
                    $ValOptDetails = "";    
                // }
				
                if($ValDivisionName == "QUALITY ASSURANCE")
                {
                    if($ValGoal == "0.00")
                    {
                        $QValGoal = GET_TOTAL_COUNT_AVG_QA($ValClosedTime,$ValProjectID,$ValLocation,$linkMACHWebTrax);
                        if(mssql_num_rows($QValGoal) != 0)
                        {   
                            $RValGoal = mssql_fetch_assoc($QValGoal);
                            // $ValGoal = number_format((float)trim($RValGoal['Result']), 2, '.', ',');
                            $ValGoal = number_format((float)trim($RValGoal['Result2']), 2, '.', ',');
                        }
                    }
                ?>
                 <tr class="ListRow" data-cookies="<?php echo $RowEnc; ?>"<?php echo $ValOptDetails; ?>>
                     <td class="text-center"><?php echo $No; ?></td>
                     <td class="text-start" colspan="4"><?php echo $ValDivisionName; ?></td>
                     <td class="text-center"><?php echo $ValGoal; ?></td>
                     <td class="text-center"></td>
                 </tr>
                <?php
                }
                else
                {
                    if($ValActual == "0.00"){$ValActual = "";}
                    if($ValTargetMin == "0.00"){$ValTargetMin = "";}
                    if($ValTargetMax == "0.00"){$ValTargetMax = "";}
					if($ValActual == "" && $ValTargetMin == "" && $ValTargetMax == "")
					{
						if($ValGoal == "0.00"){$ValGoal = "";}
					}
                ?>
                 <tr class="ListRow" data-cookies="<?php echo $RowEnc; ?>"<?php echo $ValOptDetails; ?>>
                     <td class="text-center"><?php echo $No; ?></td>
                     <td class="text-start"><?php echo $ValDivisionName; ?></td>
                     <td class="text-center"><?php echo $ValActual; ?></td>
                     <td class="text-center"><?php echo $ValTargetMin; ?></td>
                     <td class="text-center"><?php echo $ValTargetMax; ?></td>
                     <td class="text-center"><?php echo $ValGoal; ?></td>
                     <td class="text-center"><?php echo $ValOptForm; ?></td>
                 </tr>
                <?php
                }
                $No++;
            }
            ?></tbody>
        </table>
    </div>
</div>
<div class="modal fade" id="ModalUpdatePSL" tabindex="-1" role="dialog" aria-labelledby="Modal-View" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Form</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
				<div id="ContentSelected"></div>
                <div class="text-center"><img src="images/ajax-loader1.gif" id="LoadingImg" class="load_img" style="height:10px;"/></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">&nbsp;Close&nbsp;</button>
            </div>
        </div>
    </div>
</div>
<?php
}
/*
if(count($ArrDataResultQualityPSM) !="0")
{
?>
<div class="col-md-12">Quote : <strong><?php echo $ValProjectName; ?></strong>. Closed Time : <strong><?php echo $ValClosedTime; ?></strong>. Location : <strong>Semarang</strong>.</div>
<div class="col-md-12">
    <div class="table-responsive">
        <table class="table table-bordered table-hover" id="TableProjectSelectedB">
            <thead class="theadCustom">
                <tr>
                    <th class="text-center trowCustom" width="30">No</th>
                    <th class="text-center trowCustom">Division</th>
                    <th class="text-center trowCustom" width="100">Actual (%)</th>
                    <th class="text-center trowCustom" width="100">TargetMin (%)</th>
                    <th class="text-center trowCustom" width="100">TargetMax (%)</th>
                    <th class="text-center trowCustom" width="100">Goal<br>Achievement<br>(%)</th>
                    <th class="text-center trowCustom" width="10">#</th>
                </tr>
            </thead>
            <tbody><?php 
            $No = 1; 
            foreach ($ArrDataResultQualityPSM as $DataResultQualityPSM)
            {
                $ValSortOrder = trim($DataResultQualityPSM['SortOrder']);
                $ValClosedTime = trim($DataResultQualityPSM['ClosedTime']);
                $ValDivisionName = trim($DataResultQualityPSM['DivisionName']);
                $ValProjectID = trim($DataResultQualityPSM['ProjectID']);
                $ValProjectName = trim($DataResultQualityPSM['ProjectName']);
                $ValDivisionID = trim($DataResultQualityPSM['DivisionID']);
                $ValConstValue = trim($DataResultQualityPSM['ConstValue']);
                $ValLocation = trim($DataResultQualityPSM['Location']);
                $ValTotalQtyIn = trim($DataResultQualityPSM['TotalQtyIn']);
                $ValTotalQtyOut = trim($DataResultQualityPSM['TotalQtyOut']);
                $ValRejectRate = trim($DataResultQualityPSM['RejectRate']);
                $ValLastUpdated = trim($DataResultQualityPSM['LastUpdated']);
                $ValActual = trim($DataResultQualityPSM['Actual']);
                $ValTargetMin = trim($DataResultQualityPSM['TargetMin']);
                $ValTargetMax = trim($DataResultQualityPSM['TargetMax']);
                $ValGoal = trim($DataResultQualityPSM['Goal']);

                $RowEnc = base64_encode($ValClosedTime."*".$ValProjectID."*".$ValDivisionID."*".$ValDivisionName."*".$ValLocation);
                $RowEnc2 = base64_encode($ValProjectName."*".$ValClosedTime."*B");
                $ValActual = number_format((float)$ValActual, 2, '.', ',');
                $ValTargetMin = number_format((float)$ValTargetMin, 2, '.', ',');
                $ValTargetMax = number_format((float)$ValTargetMax, 2, '.', ',');
                $ValGoal = number_format((float)$ValGoal, 2, '.', ',');
                # btn form update
                $ValOptForm = '<span class="PointerList UpdateRow" aria-hidden="true" data-bs-toggle="modal" data-ecode="'.$RowEnc.'" data-dcode="'.$RowEnc2.'" data-bs-target="#ModalUpdatePSM" title="Update"><i class="bi bi-pencil-square"></i></span>';
                if($ValDivisionID == "9" || $ValDivisionID == "20")
                {
                    $ValOptForm = "";
                    $ValOptDetails = ' data-details="'.$RowEnc.'"';
                }
                else
                {
                    $ValOptDetails = "";    
                }
                ?>
                 <tr class="ListRow" data-cookies="<?php echo $RowEnc; ?>"<?php echo $ValOptDetails; ?>>
                     <td class="text-center"><?php echo $No; ?></td>
                     <td class="text-start"><?php echo $ValDivisionName; ?></td>
                     <td class="text-center"><?php echo $ValActual; ?></td>
                     <td class="text-center"><?php echo $ValTargetMin; ?></td>
                     <td class="text-center"><?php echo $ValTargetMax; ?></td>
                     <td class="text-center"><?php echo $ValGoal; ?></td>
                     <td class="text-center"><?php echo $ValOptForm; ?></td>
                 </tr>
                <?php
                $No++;
            }
            ?></tbody>
        </table>
    </div>
</div>
<div class="modal fade" id="ModalUpdatePSM" tabindex="-1" role="dialog" aria-labelledby="Modal-View" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Form</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
				<div id="ContentSelectedPSM"></div>
                <div class="text-center"><img src="images/ajax-loader1.gif" id="LoadingImgPSM" class="load_img" style="height:10px;"/></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">&nbsp;Close&nbsp;</button>
            </div>
        </div>
    </div>
</div>
<?php
}*/
if(count($ArrDataResultQualityPSL) == "0" && count($ArrDataResultQualityPSM) =="0")
{
    ?>
<div class="col-md-12">Quote : <strong><?php echo $ValProjectName; ?></strong>. Closed Time : <strong><?php echo $ValClosedTime; ?></strong>.</div>  
<div class="col-md-12"><strong>No Data Found !!</strong></div>   
    <?php
}


}
else
{
    echo "";    
}
?>