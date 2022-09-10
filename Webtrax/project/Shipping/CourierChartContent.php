<?php 
session_start();
require_once("../../src/Modules/ModuleLogin.php");
require_once("Modules/ModuleShippingChart.php");

if(!session_is_registered("UIDWebTrax"))
{
  ?>
    <script language="javascript">
        window.location.replace("https://webtrax.formulatrix.com/");
    </script>
    <?php
    exit();
}

if (! function_exists('array_column')) {
    function array_column(array $input, $columnKey, $indexKey = null) {
        $array = array();
        foreach ($input as $value) {
            if ( !array_key_exists($columnKey, $value)) {
                trigger_error("Key \"$columnKey\" does not exist in array");
                return false;
            }
            if (is_null($indexKey)) {
                $array[] = $value[$columnKey];
            }
            else {
                if ( !array_key_exists($indexKey, $value)) {
                    trigger_error("Key \"$indexKey\" does not exist in array");
                    return false;
                }
                if ( ! is_scalar($value[$indexKey])) {
                    trigger_error("Key \"$indexKey\" does not contain scalar value");
                    return false;
                }
                $array[$value[$indexKey]] = $value[$columnKey];
            }
        }
        return $array;
    }
}

if($_SERVER['REQUEST_METHOD'] == "POST")
{   
    $InputYear = htmlspecialchars(trim($_POST['InputYear']), ENT_QUOTES, "UTF-8");
    if($InputYear == "All") # semua tahun
    {
        $ArrListYear = array();
        $QListYear = GET_LIST_FILTER_SHIPPING($linkMACHWebTrax);
        while($RListYear = mssql_fetch_assoc($QListYear))
        {
            array_push($ArrListYear,array("YearShipment" => trim($RListYear['YearShipment'])));
        }
        $Year1Idx = end(array_keys($ArrListYear));
        $Year1 = $ArrListYear[$Year1Idx]['YearShipment'];
        $Year2 = $ArrListYear[0]['YearShipment'];
        krsort($ArrListYear);
        # list data
        $ArrDataPerYear = array();
        foreach($ArrListYear as $ListYear)
        {
            $ArrDataDefault = array();
            $QGetData = GET_DATA_SHIPMENT_COURIER_BY_YEAR($ListYear['YearShipment'],$linkMACHWebTrax);
            while($RGetData = mssql_fetch_assoc($QGetData))
            {
                switch (trim($RGetData['FromSubject'])) {
                    case 'PT. Promanufacture Indonesia':
                        $NewName = 'PSM';
                        break;
                    case 'PT. Promanufacture Indonesia (Salatiga)':
                        $NewName = 'PSL';
                        break;
                    default:
                        $NewName = 'FOR';
                        break;
                }
                $TempArray1 = array(
                    "MonthNo" => (int)trim($RGetData['MonthNo']),
                    "From" => trim($NewName),
                    "Courier" => trim($RGetData['Courier']),
                    "TotalFreight" => trim($RGetData['TotalFreight']),
                    "YearShipment" => $ListYear['YearShipment']
                );
                array_push($ArrDataDefault,$TempArray1);
            }            
            $QGetData2 = GET_DATA_SHIPMENT_COURIER_BY_YEAR_FOR($ListYear['YearShipment'],$linkMACHWebTrax);
            while($RGetData2 = mssql_fetch_assoc($QGetData2))
            {
                switch (trim($RGetData2['FromSubject'])) {
                    case 'PT. Promanufacture Indonesia':
                        $NewName = 'PSM';
                        break;
                    case 'PT. Promanufacture Indonesia (Salatiga)':
                        $NewName = 'PSL';
                        break;
                    default:
                        $NewName = 'FOR';
                        break;
                }
                $TempArray1 = array(
                    "MonthNo" => (int)trim($RGetData2['MonthNo']),
                    "From" => trim($NewName),
                    "Courier" => trim($RGetData2['Courier']),
                    "TotalFreight" => trim($RGetData2['TotalFreight']),
                    "YearShipment" => $ListYear['YearShipment']
                );
                array_push($ArrDataDefault,$TempArray1);
            }   
            foreach ($ArrDataDefault as $DataDefault1)
            {
                $ValCourierT1 = $DataDefault1['Courier'];
                $ValFromT1 = $DataDefault1['From'];
                $ValYearT1 = $DataDefault1['YearShipment'];
                $ValTotal1 = 0;
                $ValTotalCost1 = 0;
                # check data array
                $BolCheck1 = TRUE;
                foreach($ArrDataPerYear as $DataPerYear)
                {
                    if($DataPerYear['Courier'] == $ValCourierT1 && $DataPerYear['From'] == $ValFromT1 && $DataPerYear['YearShipment'] == $ValYearT1)
                    {
                        $BolCheck1 = FALSE;
                        break;
                    }
                }
                if($BolCheck1 == TRUE)
                {
                    $NoLoop1 = 1;
                    foreach ($ArrDataDefault as $DataDefault2)
                    {
                        if($DataDefault2['Courier'] == $ValCourierT1 && $DataDefault2['From'] == $ValFromT1 && $DataDefault2['YearShipment'] == $ValYearT1)
                        {
                            if($NoLoop1 == 1)
                            {
                                $ValTotal1 = 1;
                                $ValTotalCost1 = (float)$DataDefault2['TotalFreight'];
                                $NoLoop1++;
                            }
                            else
                            {
                                if($DataDefault2['Courier'] == $ValCourierT1 && $DataDefault2['From'] == $ValFromT1 && $DataDefault2['YearShipment'] == $ValYearT1)
                                {
                                    $ValTotal1 = $ValTotal1 + 1;
                                    $ValTotalCost1 = $ValTotalCost1 + (float)$DataDefault2['TotalFreight'];
                                }
                                $NoLoop1++;
                            }
                        }
                    }
                    # add data to array
                    array_push($ArrDataPerYear,array("Total" => $ValTotal1,"Courier" => $ValCourierT1,"From" => $ValFromT1,"YearShipment" => $ValYearT1,"TotalCost" => $ValTotalCost1));
                }
            }
        }        
        # Array location
        $ArrLocation = array();
        $TempLocation = "";
        array_push($ArrLocation,array("Location" => "PSM"));
        array_push($ArrLocation,array("Location" => "PSL"));
        array_push($ArrLocation,array("Location" => "FOR"));
        # total location
        $TotalLocation = count($ArrLocation);
        $ArrDataPerLocationHighest = array();
        $ArrDataPerLocationHighestCost = array();
        for($i = 1;$i <= $TotalLocation;$i++)
        {
            # set array 
            $ArrDataPerLocation[$i] = array();
        }
        for($i = 1;$i <= $TotalLocation;$i++)
        {
            $NewID = $i - 1;
            $Location = $ArrLocation[$NewID]['Location'];
            foreach($ArrDataPerYear as $DataPerYear)
            {
                if($DataPerYear['From'] == $Location)
                {
                    array_push($ArrDataPerLocation[$i],array("Total" => $DataPerYear['Total'],"Courier" => $DataPerYear['Courier'],"From" => $DataPerYear['From'],"YearShipment" => $DataPerYear['YearShipment'],"TotalCost" => $DataPerYear['TotalCost']));
                }
            }
            foreach($ArrListYear as $ListYear)
            {
                $YearShipment = $ListYear['YearShipment'];
                $TTotal = 0;
                $TCourier = "N/A";
                $NoLoop = 1;
                foreach($ArrDataPerLocation[$i] as $DataPerLocation)
                {
                    if($DataPerLocation['From'] == $Location && $DataPerLocation['YearShipment'] == $YearShipment)
                    {
                        if($NoLoop == 1)
                        {
                            $TTotal = $DataPerLocation['Total'];
                            $TCourier = $DataPerLocation['Courier'];
                            $NoLoop++;
                        }
                        else
                        {
                            if($DataPerLocation['Total'] > $TTotal)
                            {
                                $TTotal = $DataPerLocation['Total'];
                                $TCourier = $DataPerLocation['Courier'];
                            }
                            $NoLoop++;
                        }
                    }
                }
                array_push($ArrDataPerLocationHighest,array("Total" => $TTotal,"Courier" => $TCourier,"From" => $Location,"YearShipment" => $YearShipment));
            }
            foreach($ArrListYear as $ListYear)
            {
                $YearShipment = $ListYear['YearShipment'];
                $TTotal = 0;
                $TCourier = "N/A";
                $NoLoop = 1;
                foreach($ArrDataPerLocation[$i] as $DataPerLocation)
                {
                    if($DataPerLocation['From'] == $Location && $DataPerLocation['YearShipment'] == $YearShipment)
                    {
                        if($NoLoop == 1)
                        {
                            $TTotal = $DataPerLocation['TotalCost'];
                            $TCourier = $DataPerLocation['Courier'];
                            $NoLoop++;
                        }
                        else
                        {
                            if($DataPerLocation['TotalCost'] > $TTotal)
                            {
                                $TTotal = $DataPerLocation['TotalCost'];
                                $TCourier = $DataPerLocation['Courier'];
                            }
                            $NoLoop++;
                        }
                    }
                }
                array_push($ArrDataPerLocationHighestCost,array("TotalCost" => $TTotal,"Courier" => $TCourier,"From" => $Location,"YearShipment" => $YearShipment));
            }
        }
        # rank cost
        $ArrResultRankCostPerYear = array();
        for($i = 1;$i <= $TotalLocation;$i++)
        {
            $NewID = $i - 1;
            $Location = $ArrLocation[$NewID]['Location'];
            foreach($ArrListYear as $DataYear)
            {
                $TotalCountDt = 0;
                $TotalOther = 0;
                $TempArrayDt1 = array();
                $TempArrayDt2 = array();
                $TempArrayDt3 = array();
                $TempArrayNoOther = array();
                $TempArrayOther = array();
                foreach($ArrDataPerYear as $DataPerYear)
                {
                    if($DataPerYear['YearShipment'] == $DataYear['YearShipment'] && $DataPerYear['From'] == $Location)
                    {
                        array_push($TempArrayDt1,array("Total"=>$DataPerYear['Total'],"Courier"=>$DataPerYear['Courier'],"From"=>$DataPerYear['From'],"YearShipment"=>$DataPerYear['YearShipment'],"TotalCost"=>$DataPerYear['TotalCost']));
                    }
                }
                if(count($TempArrayDt1) > 0)
                {
                    foreach ($TempArrayDt1 as $Dt1)
                    {
                        $TotalCountDt++;
                        if($Dt1['Courier'] == "Other")
                        {
                            array_push($TempArrayOther,array("YearShipment" => $Dt1['YearShipment'],"Total" => $Dt1['Total'],"Courier" => $Dt1['Courier'],"From" => $Dt1['From'],"TotalCost" => $Dt1['TotalCost']));
                            $TotalOther++;
                        }
                        else
                        {
                            array_push($TempArrayNoOther,array("YearShipment" => $Dt1['YearShipment'],"Total" => $Dt1['Total'],"Courier" => $Dt1['Courier'],"From" => $Dt1['From'],"TotalCost" => $Dt1['TotalCost']));
                        }
                    }                    
                    # sort array 
                    if(count($TempArrayNoOther) != 0)
                    {
                        array_multisort(array_column($TempArrayNoOther,'TotalCost'), SORT_DESC, array_column($TempArrayNoOther,'Courier'), SORT_DESC, $TempArrayNoOther);
                    }
                    if(count($TempArrayOther) != 0)
                    {
                        array_multisort(array_column($TempArrayOther,'TotalCost'), SORT_DESC, array_column($TempArrayOther,'Courier'), SORT_DESC, $TempArrayOther);
                    }                    
                    if(count($TempArrayNoOther) != 0 && count($TempArrayOther) != 0)
                    {
                        if(count($TempArrayNoOther) == 1)    # jika total = 1
                        {
                            $ValRank = 1;
                            foreach($TempArrayNoOther as $DataArr) 
                            {
                                array_push($TempArrayDt2,array("Rank" => $ValRank,"Total" => $DataArr['Total'],"Courier" => $DataArr['Courier'],"YearShipment" => $DataYear['YearShipment'],"From" => $Location,"TotalCost" => $DataArr['TotalCost']));
                                $ValRank++;
                            }
                            # looping other
                            foreach($TempArrayOther as $DataArr) 
                            {
                                array_push($TempArrayDt2,array("Rank" => $ValRank,"Total" => $DataArr['Total'],"Courier" => "Other","YearShipment" => $DataYear['YearShipment'],"From" => $Location,"TotalCost" => $DataArr['TotalCost']));                            
                                $ValRank++;
                            }
                        }
                        elseif(count($TempArrayNoOther) == 2)    # jika total = 2
                        {
                            $ValRank = 1;
                            foreach($TempArrayNoOther as $DataArr) 
                            {
                                # input array
                                array_push($TempArrayDt2,array("Rank" => $ValRank,"Total" => $DataArr['Total'],"Courier" => $DataArr['Courier'],"YearShipment" => $DataYear['YearShipment'],"From" => $Location,"TotalCost" => $DataArr['TotalCost']));
                                $ValRank++;
                            }
                            # looping other
                            foreach($TempArrayOther as $DataArr) 
                            {
                                array_push($TempArrayDt2,array("Rank" => $ValRank,"Total" => $DataArr['Total'],"Courier" => "Other","YearShipment" => $DataYear['YearShipment'],"From" => $Location,"TotalCost" => $DataArr['TotalCost']));                                
                                $ValRank++;
                            }
                        }
                        else    # jika total >= 3
                        {
                            $ValTempTotal = 0;
                            $ValTempTotalCost = 0;
                            $ValRank = 1;
                            foreach($TempArrayNoOther as $DataArr) 
                            {
                                array_push($TempArrayDt2,array("Rank" => $ValRank,"Total" => $DataArr['Total'],"Courier" => $DataArr['Courier'],"YearShipment" => $DataYear['YearShipment'],"From" => $Location,"TotalCost" => $DataArr['TotalCost']));
                                $ValRank++;
                            }
                            # looping other
                            foreach($TempArrayOther as $DataArr) 
                            {
                                array_push($TempArrayDt2,array("Rank" => $ValRank,"Total" => $DataArr['Total'],"Courier" => "Other","YearShipment" => $DataYear['YearShipment'],"From" => $Location,"TotalCost" => $DataArr['TotalCost']));
                                $ValRank++;
                            }
                        }
                    }
                    elseif(count($TempArrayNoOther) != 0 && count($TempArrayOther) == 0)
                    {
                        if(count($TempArrayNoOther) == 1)    # jika total = 1
                        {
                            $ValRank = 1;
                            foreach($TempArrayNoOther as $DataArr) 
                            {
                                array_push($TempArrayDt2,array("Rank" => $ValRank,"Total" => $DataArr['Total'],"Courier" => $DataArr['Courier'],"YearShipment" => $DataYear['YearShipment'],"From" => $Location,"TotalCost" => $DataArr['TotalCost']));
                                $ValRank++;
                            }
                        }
                        elseif(count($TempArrayNoOther) == 2)   # jika total = 2
                        {
                            $ValRank = 1;
                            foreach($TempArrayNoOther as $DataArr) 
                            {
                                array_push($TempArrayDt2,array("Rank" => $ValRank,"Total" => $DataArr['Total'],"Courier" => $DataArr['Courier'],"YearShipment" => $DataYear['YearShipment'],"From" => $Location,"TotalCost" => $DataArr['TotalCost']));
                                $ValRank++;
                            }
                        }
                        else    # jika total >= 3
                        {
                            $ValRank = 1;
                            foreach($TempArrayNoOther as $DataArr) 
                            {
                                # input array
                                array_push($TempArrayDt2,array("Rank" => $ValRank,"Total" => $DataArr['Total'],"Courier" => $DataArr['Courier'],"YearShipment" => $DataYear['YearShipment'],"From" => $Location,"TotalCost" => $DataArr['TotalCost']));
                                $ValRank++;
                            }
                        }
                    }
                    else # data kosong
                    {
                        for($loopingRank = 1;$loopingRank <= 3;$loopingRank++)
                        {
                            array_push($TempArrayDt2,array("Rank" => $loopingRank,"Total" => "0","Courier" => "N/A","YearShipment" => $DataYear['YearShipment'],"From" => $Location,"TotalCost" => "0"));
                        }
                    }
                    if(count($TempArrayDt2) == 1)
                    {
                        foreach($TempArrayDt2 as $DataResult)
                        {
                            array_push($TempArrayDt3,array("Rank" => $DataResult['Rank'],"Total" => $DataResult['Total'],"Courier" => $DataResult['Courier'],"YearShipment" => $DataResult['YearShipment'],"From" => $DataResult['From'],"TotalCost" => $DataResult['TotalCost']));
                        }
                        array_push($TempArrayDt3,array("Rank" => "2","Total" => "0","Courier" => "N/A","YearShipment" => $DataYear['YearShipment'],"From" => $Location,"TotalCost" => "0"));
                        array_push($TempArrayDt3,array("Rank" => "3","Total" => "0","Courier" => "N/A","YearShipment" => $DataYear['YearShipment'],"From" => $Location,"TotalCost" => "0"));
                    }
                    elseif (count($TempArrayDt2) == 2)
                    {
                        foreach($TempArrayDt2 as $DataResult)
                        {
                            array_push($TempArrayDt3,array("Rank" => $DataResult['Rank'],"Total" => $DataResult['Total'],"Courier" => $DataResult['Courier'],"YearShipment" => $DataResult['YearShipment'],"From" => $DataResult['From'],"TotalCost" => $DataResult['TotalCost']));
                        }
                        array_push($TempArrayDt3,array("Rank" => "3","Total" => "0","Courier" => "N/A","YearShipment" =>  $DataYear['YearShipment'],"From" => $Location,"TotalCost" => "0"));
                    }
                    elseif (count($TempArrayDt2) == 3)
                    {
                        foreach($TempArrayDt2 as $DataResult)
                        {
                            array_push($TempArrayDt3,array("Rank" => $DataResult['Rank'],"Total" => $DataResult['Total'],"Courier" => $DataResult['Courier'],"YearShipment" => $DataResult['YearShipment'],"From" => $DataResult['From'],"TotalCost" => $DataResult['TotalCost']));
                        }
                    }
                    else
                    {
                        $NoLoopRank = 1;
                        $TTotalQty = 0;
                        $TTotalCost = 0;
                        foreach($TempArrayDt2 as $DataResult)
                        {
                            if($NoLoopRank < 3)
                            {
                                array_push($TempArrayDt3,array("Rank" => $DataResult['Rank'],"Total" => $DataResult['Total'],"Courier" => $DataResult['Courier'],"YearShipment" => $DataResult['YearShipment'],"From" => $DataResult['From'],"TotalCost" => $DataResult['TotalCost']));
                            }
                            else
                            {
                                $TTotalQty = (int)$TTotalQty + (int)$DataResult['Total'];
                                $TTotalCost = (float)$TTotalCost + (float)$DataResult['TotalCost'];
                            }
                            $NoLoopRank++;
                        }

                        if($NoLoopRank > 2)
                        {
                            array_push($TempArrayDt3,array("Rank" => "3","Total" => $TTotalQty,"Courier" => "Others","YearShipment" => $DataYear['YearShipment'],"From" => $Location,"TotalCost" => (float)$TTotalCost));
                        }
                    }
                    foreach($TempArrayDt3 as $DataResult)
                    {
                        array_push($ArrResultRankCostPerYear,array("Rank" => $DataResult['Rank'],"Total" => $DataResult['Total'],"Courier" => $DataResult['Courier'],"YearShipment" => $DataResult['YearShipment'],"From" => $DataResult['From'],"TotalCost" => $DataResult['TotalCost']));
                    }
                }
                else
                {
                    array_push($ArrResultRankCostPerYear,array("Rank" => "1","Total" => "0","Courier" => "N/A","YearShipment" => $DataYear['YearShipment'],"From" => $Location,"TotalCost" => "0"));
                    array_push($ArrResultRankCostPerYear,array("Rank" => "2","Total" => "0","Courier" => "N/A","YearShipment" => $DataYear['YearShipment'],"From" => $Location,"TotalCost" => "0"));
                    array_push($ArrResultRankCostPerYear,array("Rank" => "3","Total" => "0","Courier" => "N/A","YearShipment" => $DataYear['YearShipment'],"From" => $Location,"TotalCost" => "0"));
                }
            }
        }

        $MaxTotalCostTemp = 0;
        $MaxTotalCost = 0;
        foreach ($ArrResultRankCostPerYear as $Dt)
        {
            if($Dt['Rank'] != '3')
            {
                $MaxTotalCostTemp = (float)$MaxTotalCostTemp + (float)$Dt['TotalCost'];
            }
            else
            {
                $MaxTotalCostTemp = (float)$MaxTotalCostTemp + (float)$Dt['TotalCost'];
                if((float)$MaxTotalCostTemp > (float)$MaxTotalCost)
                {
                    $MaxTotalCost = (float)$MaxTotalCostTemp;
                }
                $MaxTotalCostTemp = 0;
            }
        }

        # rank qty
        $ArrResultRankQtyPerYear = array();
        for($i = 1;$i <= $TotalLocation;$i++)
        {
            $NewID = $i - 1;
            $Location = $ArrLocation[$NewID]['Location'];
            foreach($ArrListYear as $DataYear)
            {
                $TotalCountDt = 0;
                $TotalOther = 0;
                $TempArrayDt1 = array();
                $TempArrayDt2 = array();
                $TempArrayDt3 = array();
                $TempArrayNoOther = array();
                $TempArrayOther = array();
                foreach($ArrDataPerYear as $DataPerYear)
                {
                    if($DataPerYear['YearShipment'] == $DataYear['YearShipment'] && $DataPerYear['From'] == $Location)
                    {
                        array_push($TempArrayDt1,array("Total"=>$DataPerYear['Total'],"Courier"=>$DataPerYear['Courier'],"From"=>$DataPerYear['From'],"YearShipment"=>$DataPerYear['YearShipment'],"TotalCost"=>$DataPerYear['TotalCost']));
                    }
                }
                if(count($TempArrayDt1) > 0)
                {
                    foreach ($TempArrayDt1 as $Dt1)
                    {
                        $TotalCountDt++;
                        if($Dt1['Courier'] == "Other")
                        {
                            array_push($TempArrayOther,array("YearShipment" => $Dt1['YearShipment'],"Total" => $Dt1['Total'],"Courier" => $Dt1['Courier'],"From" => $Dt1['From'],"TotalCost" => $Dt1['TotalCost']));
                            $TotalOther++;
                        }
                        else
                        {
                            array_push($TempArrayNoOther,array("YearShipment" => $Dt1['YearShipment'],"Total" => $Dt1['Total'],"Courier" => $Dt1['Courier'],"From" => $Dt1['From'],"TotalCost" => $Dt1['TotalCost']));
                        }
                    }
                    
                    # sort array 
                    if(count($TempArrayNoOther) != 0)
                    {
                        array_multisort(array_column($TempArrayNoOther,'Total'), SORT_DESC, array_column($TempArrayNoOther,'Courier'), SORT_DESC, $TempArrayNoOther);
                    }
                    if(count($TempArrayOther) != 0)
                    {
                        array_multisort(array_column($TempArrayOther,'Total'), SORT_DESC, array_column($TempArrayOther,'Courier'), SORT_DESC, $TempArrayOther);
                    }
                                        
                    if(count($TempArrayNoOther) != 0 && count($TempArrayOther) != 0)
                    {
                        if(count($TempArrayNoOther) == 1)    # jika total = 1
                        {
                            $ValRank = 1;
                            foreach($TempArrayNoOther as $DataArr) 
                            {
                                array_push($TempArrayDt2,array("Rank" => $ValRank,"Total" => $DataArr['Total'],"Courier" => $DataArr['Courier'],"YearShipment" => $DataYear['YearShipment'],"From" => $Location,"TotalCost" => $DataArr['TotalCost']));
                                $ValRank++;
                            }
                            # looping other
                            foreach($TempArrayOther as $DataArr) 
                            {
                                array_push($TempArrayDt2,array("Rank" => $ValRank,"Total" => $DataArr['Total'],"Courier" => "Other","YearShipment" => $DataYear['YearShipment'],"From" => $Location,"TotalCost" => $DataArr['TotalCost']));                            
                                $ValRank++;
                            }
                        }
                        elseif(count($TempArrayNoOther) == 2)    # jika total = 2
                        {
                            $ValRank = 1;
                            foreach($TempArrayNoOther as $DataArr) 
                            {
                                # input array
                                array_push($TempArrayDt2,array("Rank" => $ValRank,"Total" => $DataArr['Total'],"Courier" => $DataArr['Courier'],"YearShipment" => $DataYear['YearShipment'],"From" => $Location,"TotalCost" => $DataArr['TotalCost']));
                                $ValRank++;
                            }
                            # looping other
                            foreach($TempArrayOther as $DataArr) 
                            {
                                array_push($TempArrayDt2,array("Rank" => $ValRank,"Total" => $DataArr['Total'],"Courier" => "Other","YearShipment" => $DataYear['YearShipment'],"From" => $Location,"TotalCost" => $DataArr['TotalCost']));                                
                                $ValRank++;
                            }
                        }
                        else    # jika total >= 3
                        {
                            $ValTempTotal = 0;
                            $ValTempTotalCost = 0;
                            $ValRank = 1;
                            foreach($TempArrayNoOther as $DataArr) 
                            {
                                array_push($TempArrayDt2,array("Rank" => $ValRank,"Total" => $DataArr['Total'],"Courier" => $DataArr['Courier'],"YearShipment" => $DataYear['YearShipment'],"From" => $Location,"TotalCost" => $DataArr['TotalCost']));
                                $ValRank++;
                            }
                            # looping other
                            foreach($TempArrayOther as $DataArr) 
                            {
                                array_push($TempArrayDt2,array("Rank" => $ValRank,"Total" => $DataArr['Total'],"Courier" => "Other","YearShipment" => $DataYear['YearShipment'],"From" => $Location,"TotalCost" => $DataArr['TotalCost']));
                                $ValRank++;
                            }
                        }
                    }
                    elseif(count($TempArrayNoOther) != 0 && count($TempArrayOther) == 0)
                    {
                        if(count($TempArrayNoOther) == 1)    # jika total = 1
                        {
                            $ValRank = 1;
                            foreach($TempArrayNoOther as $DataArr) 
                            {
                                array_push($TempArrayDt2,array("Rank" => $ValRank,"Total" => $DataArr['Total'],"Courier" => $DataArr['Courier'],"YearShipment" => $DataYear['YearShipment'],"From" => $Location,"TotalCost" => $DataArr['TotalCost']));
                                $ValRank++;
                            }
                        }
                        elseif(count($TempArrayNoOther) == 2)   # jika total = 2
                        {
                            $ValRank = 1;
                            foreach($TempArrayNoOther as $DataArr) 
                            {
                                array_push($TempArrayDt2,array("Rank" => $ValRank,"Total" => $DataArr['Total'],"Courier" => $DataArr['Courier'],"YearShipment" => $DataYear['YearShipment'],"From" => $Location,"TotalCost" => $DataArr['TotalCost']));
                                $ValRank++;
                            }
                        }
                        else    # jika total >= 3
                        {
                            $ValRank = 1;
                            foreach($TempArrayNoOther as $DataArr) 
                            {
                                # input array
                                array_push($TempArrayDt2,array("Rank" => $ValRank,"Total" => $DataArr['Total'],"Courier" => $DataArr['Courier'],"YearShipment" => $DataYear['YearShipment'],"From" => $Location,"TotalCost" => $DataArr['TotalCost']));
                                $ValRank++;
                            }
                        }
                    }
                    else # data kosong
                    {
                        for($loopingRank = 1;$loopingRank <= 3;$loopingRank++)
                        {
                            array_push($TempArrayDt2,array("Rank" => $loopingRank,"Total" => "0","Courier" => "N/A","YearShipment" => $DataYear['YearShipment'],"From" => $Location,"TotalCost" => "0"));
                        }
                    }
                    if(count($TempArrayDt2) == 1)
                    {
                        foreach($TempArrayDt2 as $DataResult)
                        {
                            array_push($TempArrayDt3,array("Rank" => $DataResult['Rank'],"Total" => $DataResult['Total'],"Courier" => $DataResult['Courier'],"YearShipment" => $DataResult['YearShipment'],"From" => $DataResult['From'],"TotalCost" => $DataResult['TotalCost']));
                        }
                        array_push($TempArrayDt3,array("Rank" => "2","Total" => "0","Courier" => "N/A","YearShipment" => $DataYear['YearShipment'],"From" => $Location,"TotalCost" => "0"));
                        array_push($TempArrayDt3,array("Rank" => "3","Total" => "0","Courier" => "N/A","YearShipment" => $DataYear['YearShipment'],"From" => $Location,"TotalCost" => "0"));
                    }
                    elseif (count($TempArrayDt2) == 2)
                    {
                        foreach($TempArrayDt2 as $DataResult)
                        {
                            array_push($TempArrayDt3,array("Rank" => $DataResult['Rank'],"Total" => $DataResult['Total'],"Courier" => $DataResult['Courier'],"YearShipment" => $DataResult['YearShipment'],"From" => $DataResult['From'],"TotalCost" => $DataResult['TotalCost']));
                        }
                        array_push($TempArrayDt3,array("Rank" => "3","Total" => "0","Courier" => "N/A","YearShipment" =>  $DataYear['YearShipment'],"From" => $Location,"TotalCost" => "0"));
                    }
                    elseif (count($TempArrayDt2) == 3)
                    {
                        foreach($TempArrayDt2 as $DataResult)
                        {
                            array_push($TempArrayDt3,array("Rank" => $DataResult['Rank'],"Total" => $DataResult['Total'],"Courier" => $DataResult['Courier'],"YearShipment" => $DataResult['YearShipment'],"From" => $DataResult['From'],"TotalCost" => $DataResult['TotalCost']));
                        }
                    }
                    else
                    {
                        $NoLoopRank = 1;
                        $TTotalQty = 0;
                        $TTotalCost = 0;
                        foreach($TempArrayDt2 as $DataResult)
                        {
                            if($NoLoopRank < 3)
                            {
                                array_push($TempArrayDt3,array("Rank" => $DataResult['Rank'],"Total" => $DataResult['Total'],"Courier" => $DataResult['Courier'],"YearShipment" => $DataResult['YearShipment'],"From" => $DataResult['From'],"TotalCost" => $DataResult['TotalCost']));
                            }
                            else
                            {
                                $TTotalQty = (int)$TTotalQty + (int)$DataResult['Total'];
                                $TTotalCost = (float)$TTotalCost + (float)$DataResult['TotalCost'];
                            }
                            $NoLoopRank++;
                        }

                        if($NoLoopRank > 2)
                        {
                            array_push($TempArrayDt3,array("Rank" => "3","Total" => $TTotalQty,"Courier" => "Others","YearShipment" => $DataYear['YearShipment'],"From" => $Location,"TotalCost" => (float)$TTotalCost));
                        }
                    }
                    foreach($TempArrayDt3 as $DataResult)
                    {
                        array_push($ArrResultRankQtyPerYear,array("Rank" => $DataResult['Rank'],"Total" => $DataResult['Total'],"Courier" => $DataResult['Courier'],"YearShipment" => $DataResult['YearShipment'],"From" => $DataResult['From'],"TotalCost" => $DataResult['TotalCost']));
                    }
                }
                else
                {
                    array_push($ArrResultRankQtyPerYear,array("Rank" => "1","Total" => "0","Courier" => "N/A","YearShipment" => $DataYear['YearShipment'],"From" => $Location,"TotalCost" => "0"));
                    array_push($ArrResultRankQtyPerYear,array("Rank" => "2","Total" => "0","Courier" => "N/A","YearShipment" => $DataYear['YearShipment'],"From" => $Location,"TotalCost" => "0"));
                    array_push($ArrResultRankQtyPerYear,array("Rank" => "3","Total" => "0","Courier" => "N/A","YearShipment" => $DataYear['YearShipment'],"From" => $Location,"TotalCost" => "0"));
                }
            }
        }
            
        $MaxTotalQtyPerYearTemp = 0;
        $MaxTotalQtyPerYear = 0;
        foreach ($ArrResultRankQtyPerYear as $Dt)
        {
            if($Dt['Rank'] != '3')
            {
                $MaxTotalQtyPerYearTemp = $MaxTotalQtyPerYearTemp + $Dt['Total'];
            }
            else
            {
                $MaxTotalQtyPerYearTemp = $MaxTotalQtyPerYearTemp + $Dt['Total'];
                if($MaxTotalQtyPerYearTemp > $MaxTotalQtyPerYear)
                {
                    $MaxTotalQtyPerYear = $MaxTotalQtyPerYearTemp;
                }
                $MaxTotalQtyPerYearTemp = 0;
            }
        }
        ?>
    
    <div class="col-md-6">
        <div class="ColumnContent" id="columnchart_material1"></div>
    </div>
    <div class="col-md-6">
        <div class="ColumnContent" id="columnchart_material2"></div>
    </div>
    <div class="col-md-12"><hr></div>
    <div class="col-md-12">
        <div class="table-responsive">
            <table id="TableDataChart3" class="table table-responsive table-bordered table-hover">
                <thead class="theadCustom">
                    <tr>
                        <th class="text-center">No</th>
                        <th class="text-center">Year</th>
                        <th class="text-center">Location</th>
                        <th class="text-center">Courier</th>
                        <th class="text-center">Qty Shipment</th>
                        <th class="text-center">Qty Shipment (%)</th>
                        <th class="text-center">Total of Freight Cost ($)</th>
                        <th class="text-center">Freight Cost<br>(%)</th>
                    </tr>
                </thead>
                <tbody><?php 
                $No = 1;
                $TotalQtyShipment = 0;
                $TotalFreightCost = 0;
                $ArrPercentage = array();
                foreach($ArrDataPerYear as $DataPerYear)
                {
                    $TotalQtyShipment = $TotalQtyShipment + (int)$DataPerYear['Total'];
                    $TotalFreightCost = $TotalFreightCost + (float)$DataPerYear['TotalCost'];
                }
                $TotalPercentageQty =  ($TotalQtyShipment / $TotalQtyShipment) * 100; 
                $TotalPercentageCost =  ($TotalFreightCost / $TotalFreightCost) * 100; 
                foreach($ArrDataPerYear as $DataPerYear)
                {
                    if((int)$TotalQtyShipment == 0){$PercentageQty = 0;}else{$PercentageQty = ((int)$DataPerYear['Total'] / (int)$TotalQtyShipment)*100;}                    
                    $PercentageQty = sprintf('%.2f',floatval($PercentageQty));                    
                    if((int)$TotalFreightCost == 0){$PercentageCost = 0;}else{$PercentageCost = ((int)$DataPerYear['TotalCost'] / (int)$TotalFreightCost)*100;}
                    $PercentageCost = sprintf('%.2f',floatval($PercentageCost));
                    ?>
                    <tr>
                        <td class="text-center"><?php echo $No; ?></td>
                        <td class="text-center"><?php echo $DataPerYear['YearShipment']; ?></td>
                        <td class="text-center"><?php echo $DataPerYear['From']; ?></td>
                        <td class="text-center"><?php echo $DataPerYear['Courier']; ?></td>
                        <td class="text-center"><?php echo (int)$DataPerYear['Total']; ?></td>
                        <td class="text-right"><?php echo $PercentageQty; ?></td>
                        <td class="text-right"><?php echo 
                        number_format(sprintf('%.2f',floatval($DataPerYear['TotalCost'])),2,'.',','); ?></td>
                        <td class="text-right"><?php echo $PercentageCost; ?></td>
                    </tr>
                    <?php
                    $No++;
                    array_push($ArrPercentage,array("YearShipment" => $DataPerYear['YearShipment'],"From" => $DataPerYear['From'],"Courier" => $DataPerYear['Courier'],"Total" => (int)$DataPerYear['Total'],"Percentage" => $PercentageQty,"TotalCost" => (int)$DataPerYear['TotalCost'],"PercentageCost" => $PercentageCost));
                }
                ?></tbody>
                <tfoot class="theadCustom">
                    <tr>
                        <td class="text-center" colspan="4"><strong>Grand Total</strong></td>
                        <td class="text-center"><?php echo "<strong>".$TotalQtyShipment."</strong>"; ?></td>
                        <td class="text-right"><?php echo "<strong>".round($TotalPercentageQty)."</strong>"; ?></td>
                        <td class="text-right"><?php echo "<strong>".number_format(sprintf('%.2f',floatval($TotalFreightCost)),2,'.',',')."</strong>"; ?></td>
                        <td class="text-right"><?php echo "<strong>".round($TotalPercentageCost)."</strong>"; ?></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <script type="text/javascript">
		google.charts.load('current', {
		  callback: function () {
			drawChart1();
			drawChart2();
			$(window).resize(drawChart1);
			$(window).resize(drawChart2);
		  },
		  packages:['corechart','bar']
		});
        google.charts.setOnLoadCallback(drawChart1);
        function drawChart1() {
            var data1 = new google.visualization.DataTable();
            data1.addColumn('string', 'Year');            
            <?php 
                foreach ($ArrLocation as $DataLocation) {
                    for($r2 = 1;$r2 <= count($ArrListYear);$r2++)
                    {
                        if($r2 < count($ArrListYear))
                        {
                    ?>
                data1.addColumn('number', '<?php echo $DataLocation['Location']." Top ".$r2; ?>');
                    <?php
                        }
                        else
                        {
                    ?>
                data1.addColumn('number', '<?php echo $DataLocation['Location']." Others "; ?>');
                    <?php
                        }
                    }
                }
            ?>            
            data1.addRows([<?php 
                foreach ($ArrListYear as $ListYear)
                {
                    echo "[";
                    echo "'".$ListYear['YearShipment']."'";
                    for($i2 = 1;$i2 <= $TotalLocation;$i2++)
                    {
                        $NewID = $i2 - 1;
                        $Location = $ArrLocation[$NewID]['Location'];
                        for($r2 = 1;$r2 <= 3;$r2++)
                        {
                            foreach($ArrResultRankCostPerYear as $DataRank)
                            {
                                if($DataRank['Rank'] == $r2 && $DataRank['From'] == $Location && $DataRank['YearShipment'] == $ListYear['YearShipment'])
                                {
                                    echo ",{v:".$DataRank['TotalCost'].",f:'".$DataRank['Courier']." : $".number_format(sprintf('%.2f',floatval($DataRank['TotalCost'])),2,'.',',')."'}";
                                    break;
                                }
                            }
                        }
                    }
                    echo "],";
                }
            ?>]);
            var options1 = {
                    title: 'Top 2 Freight Cost by Courier (All Season)'
                    ,titleTextStyle: {fontSize: 15, bold: true, color: '#000000'}
                    ,tooltip: { isHtml: true }
                    ,legend: { position: 'right', maxLines: 3 }
                    ,chartArea: {top:50, left:50, height:"80%", width:"58%"}  
                    ,isStacked: true
                    ,hAxis : {
                        titleTextStyle: {color: '#000000',bold: true}
                    }
                    ,vAxes: {
                            0: {textPosition: 'out', title: 'Total Cost ($)',titleTextStyle: {color: '#000000',bold: true}
                            },
                            1: {
                                gridlines: {
                                color: 'transparent'
                                },
                                textStyle: {
                                color: 'transparent'
                                }
                            },
                            2: {
                                gridlines: {
                                color: 'transparent'
                                },
                                textStyle: {
                                color: 'transparent'
                                }
                            },
                    },
                    series: {
                        0: {
                            targetAxisIndex: 0,
                            color : '#3366CC'
                        },
                        1: {
                            targetAxisIndex: 0,
                            color : '#527ED3'
                        },
                        2: {
                            targetAxisIndex: 0,
                            color : '#7196D9'
                        },
                        3: {
                            targetAxisIndex: 1,
                            color : '#DC3912'
                        },
                        4: {
                            targetAxisIndex: 1,
                            color : '#EC9A87'
                        },
                        5: {
                            targetAxisIndex: 1,
                            color : '#f2b8a9'
                        },
                        6: {
                            targetAxisIndex: 2,
                            color : '#FF9900'
                        },
                        7: {
                            targetAxisIndex: 2,
                            color : '#ffb23f'
                        },
                        8: {
                            targetAxisIndex: 2,
                            color : '#fcc779'
                        },                          
                    },
                    width: '100%',
                    vAxis: {
                        viewWindowMode: 'explicit',
                        viewWindow: {
                            min: 0,
                            max: <?php echo $MaxTotalCost+1; ?>
                        }
                    }
            };
            var chart1 = new google.charts.Bar(document.getElementById('columnchart_material1'));
            chart1.draw(data1, google.charts.Bar.convertOptions(options1));
        };  
        google.charts.setOnLoadCallback(drawChart2);
        function drawChart2() {
            var data2 = new google.visualization.DataTable();
            data2.addColumn('string', 'Year');            
            <?php 
                foreach ($ArrLocation as $DataLocation) {
                    for($r2 = 1;$r2 <= count($ArrListYear);$r2++)
                    {
                        if($r2 < count($ArrListYear))
                        {
                    ?>
                data2.addColumn('number', '<?php echo $DataLocation['Location']." Top ".$r2; ?>');
                    <?php
                        }
                        else
                        {
                    ?>
                data2.addColumn('number', '<?php echo $DataLocation['Location']." Others "; ?>');
                    <?php 
                        }
                    }
                }
            ?>
            data2.addRows([<?php 
                foreach ($ArrListYear as $ListYear)
                {
                    echo "[";
                    echo "'".$ListYear['YearShipment']."'";
                    for($i2 = 1;$i2 <= $TotalLocation;$i2++)
                    {
                        $NewID = $i2 - 1;
                        $Location = $ArrLocation[$NewID]['Location'];
                        for($r2 = 1;$r2 <= 3;$r2++)
                        {
                            foreach($ArrResultRankQtyPerYear as $DataRank)
                            {
                                if($DataRank['Rank'] == $r2 && $DataRank['From'] == $Location && $DataRank['YearShipment'] == $ListYear['YearShipment'])
                                {
                                    echo ",{v:".$DataRank['Total'].",f:'".$DataRank['Courier']." : ".(int)$DataRank['Total']."'}";
                                    break;
                                }
                            }
                        }
                    }
                    echo "],";
                }

            ?>]);
            var options2 = {
                    title: 'The Top 2 Total Qty Shipment by Courier (All Season)'
                    ,titleTextStyle: {fontSize: 15, bold: true, color: '#000000'}
                    ,tooltip: { isHtml: true }
                    ,legend: { position: 'right', maxLines: 3 }
                    ,chartArea: {top:50, left:50, height:"80%", width:"58%"}  
                    ,isStacked: true
                    ,hAxis : {
                        titleTextStyle: {color: '#000000',bold: true}
                    }
                    ,vAxes: {
                            0: { textPosition: 'out', title: 'Total Qty Shipment',titleTextStyle: {color: '#000000',bold: true}
                            },
                            1: {
                                gridlines: {
                                color: 'transparent'
                                },
                                textStyle: {
                                color: 'transparent'
                                }
                            },
                            2: {
                                gridlines: {
                                color: 'transparent'
                                },
                                textStyle: {
                                color: 'transparent'
                                }
                            },
                    },
                    series: {
                        0: {
                            targetAxisIndex: 0,
                            color : '#3366CC'
                        },
                        1: {
                            targetAxisIndex: 0,
                            color : '#527ED3'
                        },
                        2: {
                            targetAxisIndex: 0,
                            color : '#7196D9'
                        },
                        3: {
                            targetAxisIndex: 1,
                            color : '#DC3912'
                        },
                        4: {
                            targetAxisIndex: 1,
                            color : '#EC9A87'
                        },
                        5: {
                            targetAxisIndex: 1,
                            color : '#f2b8a9'
                        },
                        6: {
                            targetAxisIndex: 2,
                            color : '#FF9900'
                        },
                        7: {
                            targetAxisIndex: 2,
                            color : '#ffb23f'
                        },
                        8: {
                            targetAxisIndex: 2,
                            color : '#fcc779'
                        },
                    },
                    width: '100%',
                    vAxis: {
                        viewWindowMode: 'explicit',
                        viewWindow: {
                            min: 0,
                            max: <?php echo $MaxTotalQtyPerYear+1; ?>
                        }
                    }
            };
            var chart2 = new google.charts.Bar(document.getElementById('columnchart_material2'));
            chart2.draw(data2, google.charts.Bar.convertOptions(options2));
        };  
    </script>
    <style>
    .ColumnContent{width:100%; height: 400px;}
    </style>
        <?php
    }
    else # tahun terpilih
    {
        $ArrDataDefault = array();
        $QGetData = GET_DATA_SHIPMENT_COURIER_BY_YEAR($InputYear,$linkMACHWebTrax);
        while($RGetData = mssql_fetch_assoc($QGetData))
        {
            switch (trim($RGetData['FromSubject'])) {
                case 'PT. Promanufacture Indonesia':
                    $NewName = 'PSM';
                    break;
                case 'PT. Promanufacture Indonesia (Salatiga)':
                    $NewName = 'PSL';
                    break;
                default:
                    $NewName = 'FOR';
                    break;
            }
            $TempArray1 = array(
                "MonthNo" => (int)trim($RGetData['MonthNo']),
                "From" => trim($NewName),
                "Courier" => trim($RGetData['Courier']),
                "TotalFreight" => trim($RGetData['TotalFreight'])
            );
            array_push($ArrDataDefault,$TempArray1);
        }                   
        $QGetData2 = GET_DATA_SHIPMENT_COURIER_BY_YEAR_FOR($InputYear,$linkMACHWebTrax);
        while($RGetData2 = mssql_fetch_assoc($QGetData2))
        {
            switch (trim($RGetData2['FromSubject'])) {
                case 'PT. Promanufacture Indonesia':
                    $NewName = 'PSM';
                    break;
                case 'PT. Promanufacture Indonesia (Salatiga)':
                    $NewName = 'PSL';
                    break;
                default:
                    $NewName = 'FOR';
                    break;
            }
            $TempArray1 = array(
                "MonthNo" => (int)trim($RGetData2['MonthNo']),
                "From" => trim($NewName),
                "Courier" => trim($RGetData2['Courier']),
                "TotalFreight" => trim($RGetData2['TotalFreight'])
            );
            array_push($ArrDataDefault,$TempArray1);
        }
        # Array location
        $ArrLocation = array();
        $TempLocation = "";        
        array_push($ArrLocation,array("Location" => "PSM"));
        array_push($ArrLocation,array("Location" => "PSL"));
        array_push($ArrLocation,array("Location" => "FOR"));
        # total location
        $TotalLocation = count($ArrLocation);
        for($i = 1;$i <= $TotalLocation;$i++)
        {
            # set array 
            $ArrDataPerLocationBranch[$i] = array();
            $ArrDataBranch[$i] = array();
            $ArrDataListRankCost[$i] = array();
            $ArrDataListRankQty[$i] = array();
        }
        $ArrTotalCourier = array();
        for ($y=1; $y < 13; $y++)
        {
            # set array temporary per month
            $ArrDataPerMonthAll[$y] = array();
            $ArrDataCountPerMonth[$y] = array();
            $ArrDataCountPerMonthB[$y] = array();
            $ArrDataQtyPerMonthB[$y] = array();
        }
        $TotalLocation = count($ArrLocation);   
        for($i = 1;$i <= $TotalLocation;$i++)
        {
            $NewID = $i - 1;
            $LoopLocation = $ArrLocation[$NewID]['Location'];
            for ($y=1; $y < 13; $y++)
            {
                foreach ($ArrDataDefault as $DataDefault)
                {
                    if($DataDefault['MonthNo'] == $y && $DataDefault['From'] == $LoopLocation)
                    {
                        array_push($ArrDataPerMonthAll[$y],array("Courier"=>$DataDefault['Courier'],"MonthNo"=>$DataDefault['MonthNo'],"From"=>$DataDefault['From'],"TotalFreight"=>$DataDefault['TotalFreight']));
                    }
                }
            }
        }   
        for ($y=1; $y < 13; $y++)
        {
            ksort($ArrDataPerMonthAll[$y]);
            foreach($ArrDataPerMonthAll[$y] as $DataPerMonth)
            {
                $TempCourier = $DataPerMonth['Courier'];
                $TempMonthNo = $DataPerMonth['MonthNo'];
                $TempFrom = $DataPerMonth['From'];
                $BolCheck1 = TRUE;
                foreach ($ArrDataCountPerMonth[$y] as $DataCount)
                {
                    if($DataCount['Courier'] == $TempCourier && $DataCount['MonthNo'] == $y && $DataCount['From'] == $TempFrom)
                    {
                        $BolCheck1 = FALSE;
                        break;
                    }
                }
                if($BolCheck1 == TRUE)
                {
                    $CountCourier = 0;
                    $TotalCost = 0;
                    foreach($ArrDataPerMonthAll[$y] as $DataPerMonth2)
                    {
                        if($DataPerMonth2['Courier'] == $TempCourier && $DataPerMonth2['MonthNo'] == $y && $DataPerMonth2['From'] == $TempFrom)
                        {
                            $CountCourier = $CountCourier + 1;
                            $TotalCost = $TotalCost + (float)$DataPerMonth2['TotalFreight'];
                        }
                    }
                    $TempArray1 = array(
                        "Total" => (int)$CountCourier,
                        "Courier" => $TempCourier,
                        "MonthNo" => $TempMonthNo,
                        "From" => $TempFrom,
                        "TotalCost" => $TotalCost
                    );
                    array_push($ArrDataCountPerMonth[$y],$TempArray1);
                }
            }   
        }

        # rank cost
        $ArrResultRankCost = array();
        for($loopLocation = 1;$loopLocation <= $TotalLocation;$loopLocation++)
        {
            $NewID = $loopLocation - 1;
            $Location = $ArrLocation[$NewID]['Location'];
            for ($loopMonth=1; $loopMonth < 13; $loopMonth++)
            {
                $TotalCountDt = 0;
                $TotalOther = 0;
                $TempArrayNoOther = array();
                $TempArrayOther = array();
                
                foreach($ArrDataCountPerMonth[$loopMonth] as $ID => $DataPerMonth) # get total courier = Other && total row per month
                {
                    if($DataPerMonth['From'] == $Location)
                    {
                        $TotalCountDt++;
                        if($DataPerMonth['Courier'] == "Other")
                        {
                            array_push($TempArrayOther,array("Month" => $DataPerMonth['MonthNo'],"Total" => $DataPerMonth['Total'],"Courier" => $DataPerMonth['Courier'],"From" => $DataPerMonth['From'],"TotalCost" => $DataPerMonth['TotalCost']));
                            $TotalOther++;
                        }
                        else
                        {
                            array_push($TempArrayNoOther,array("Month" => $DataPerMonth['MonthNo'],"Total" => $DataPerMonth['Total'],"Courier" => $DataPerMonth['Courier'],"From" => $DataPerMonth['From'],"TotalCost" => $DataPerMonth['TotalCost']));
                        }
                    }                        
                }
                # sort array 
                if(count($TempArrayNoOther) != 0)
                {
                    array_multisort(array_column($TempArrayNoOther,'TotalCost'), SORT_DESC, array_column($TempArrayNoOther,'Courier'), SORT_DESC, $TempArrayNoOther);
                }
                if(count($TempArrayOther) != 0)
                {
                    array_multisort(array_column($TempArrayOther,'TotalCost'), SORT_DESC, array_column($TempArrayOther,'Courier'), SORT_DESC, $TempArrayOther);
                }
                array_multisort(array_column($ArrDataCountPerMonth[$loopMonth],'TotalCost'), SORT_DESC, array_column($ArrDataCountPerMonth[$loopMonth],'Courier'), SORT_DESC, $ArrDataCountPerMonth[$loopMonth]);
                $ArrTempCountPerMonth[$loopMonth] = array();
                if(count($TempArrayNoOther) != 0 && count($TempArrayOther) != 0)
                { 
                    if(count($TempArrayNoOther) == 1)    # jika total = 1
                    {
                        $ValRank = 1;
                        foreach($TempArrayNoOther as $DataArr) 
                        {
                            array_push($ArrTempCountPerMonth[$loopMonth],array("Rank" => $ValRank,"Total" => $DataArr['Total'],"Courier" => $DataArr['Courier'],"MonthNo" => $loopMonth,"From" => $Location,"TotalCost" => $DataArr['TotalCost']));
                            $ValRank++;
                        }
                        # looping other
                        foreach($TempArrayOther as $DataArr) 
                        {
                            array_push($ArrTempCountPerMonth[$loopMonth],array("Rank" => $ValRank,"Total" => $DataArr['Total'],"Courier" => "Other","MonthNo" => $loopMonth,"From" => $Location,"TotalCost" => $DataArr['TotalCost']));                            
                            $ValRank++;
                        }
                    }
                    elseif(count($TempArrayNoOther) == 2)    # jika total = 2
                    {
                        $ValRank = 1;
                        foreach($TempArrayNoOther as $DataArr) 
                        {
                            # input array
                            array_push($ArrTempCountPerMonth[$loopMonth],array("Rank" => $ValRank,"Total" => $DataArr['Total'],"Courier" => $DataArr['Courier'],"MonthNo" => $loopMonth,"From" => $Location,"TotalCost" => $DataArr['TotalCost']));
                            $ValRank++;
                        }
                        # looping other
                        foreach($TempArrayOther as $DataArr) 
                        {
                            array_push($ArrTempCountPerMonth[$loopMonth],array("Rank" => $ValRank,"Total" => $DataArr['Total'],"Courier" => "Other","MonthNo" => $loopMonth,"From" => $Location,"TotalCost" => $DataArr['TotalCost']));
                            
                            $ValRank++;
                        }
                    }
                    else    # jika total >= 3
                    {
                        $ValTempTotal = 0;
                        $ValTempTotalCost = 0;
                        $ValRank = 1;
                        foreach($TempArrayNoOther as $DataArr) 
                        {
                            array_push($ArrTempCountPerMonth[$loopMonth],array("Rank" => $ValRank,"Total" => $DataArr['Total'],"Courier" => $DataArr['Courier'],"MonthNo" => $loopMonth,"From" => $Location,"TotalCost" => $DataArr['TotalCost']));


                            $ValRank++;
                        }
                        # looping other
                        foreach($TempArrayOther as $DataArr) 
                        {
                            array_push($ArrTempCountPerMonth[$loopMonth],array("Rank" => $ValRank,"Total" => $DataArr['Total'],"Courier" => "Other","MonthNo" => $loopMonth,"From" => $Location,"TotalCost" => $DataArr['TotalCost']));
                            $ValRank++;
                        }                        
                    }
                }
                elseif(count($TempArrayNoOther) != 0 && count($TempArrayOther) == 0)
                {
                    if(count($TempArrayNoOther) == 1)    # jika total = 1
                    {
                        $ValRank = 1;
                        foreach($TempArrayNoOther as $DataArr) 
                        {
                            array_push($ArrTempCountPerMonth[$loopMonth],array("Rank" => $ValRank,"Total" => $DataArr['Total'],"Courier" => $DataArr['Courier'],"MonthNo" => $loopMonth,"From" => $Location,"TotalCost" => $DataArr['TotalCost']));
                            $ValRank++;
                        }
                    }
                    elseif(count($TempArrayNoOther) == 2)   # jika total = 2
                    {
                        $ValRank = 1;
                        foreach($TempArrayNoOther as $DataArr) 
                        {
                            array_push($ArrTempCountPerMonth[$loopMonth],array("Rank" => $ValRank,"Total" => $DataArr['Total'],"Courier" => $DataArr['Courier'],"MonthNo" => $loopMonth,"From" => $Location,"TotalCost" => $DataArr['TotalCost']));
                            $ValRank++;
                        }
                    }
                    else    # jika total >= 3
                    {
                        $ValRank = 1;
                        foreach($TempArrayNoOther as $DataArr) 
                        {
                            # input array
                            array_push($ArrTempCountPerMonth[$loopMonth],array("Rank" => $ValRank,"Total" => $DataArr['Total'],"Courier" => $DataArr['Courier'],"MonthNo" => $loopMonth,"From" => $Location,"TotalCost" => $DataArr['TotalCost']));
                            $ValRank++;
                        }
                    }
                }
                else # data kosong
                {
                    for($loopingRank = 1;$loopingRank <= 3;$loopingRank++)
                    {
                        array_push($ArrTempCountPerMonth[$loopMonth],array("Rank" => $loopingRank,"Total" => "0","Courier" => "N/A","MonthNo" => $loopMonth,"From" => $Location,"TotalCost" => "0"));
                    }
                }
                if(count($ArrTempCountPerMonth[$loopMonth]) == 1)
                {
                    foreach($ArrTempCountPerMonth[$loopMonth] as $DataResult)
                    {
                        array_push($ArrDataCountPerMonthB[$loopMonth],array("Rank" => $DataResult['Rank'],"Total" => $DataResult['Total'],"Courier" => $DataResult['Courier'],"MonthNo" => $DataResult['MonthNo'],"From" => $DataResult['From'],"TotalCost" => $DataResult['TotalCost']));
                    }
                    array_push($ArrDataCountPerMonthB[$loopMonth],array("Rank" => "2","Total" => "0","Courier" => "N/A","MonthNo" => $loopMonth,"From" => $Location,"TotalCost" => "0"));
                    array_push($ArrDataCountPerMonthB[$loopMonth],array("Rank" => "3","Total" => "0","Courier" => "N/A","MonthNo" => $loopMonth,"From" => $Location,"TotalCost" => "0"));
                }
                elseif (count($ArrTempCountPerMonth[$loopMonth]) == 2)
                {
                    foreach($ArrTempCountPerMonth[$loopMonth] as $DataResult)
                    {
                        array_push($ArrDataCountPerMonthB[$loopMonth],array("Rank" => $DataResult['Rank'],"Total" => $DataResult['Total'],"Courier" => $DataResult['Courier'],"MonthNo" => $DataResult['MonthNo'],"From" => $DataResult['From'],"TotalCost" => $DataResult['TotalCost']));
                    }
                    array_push($ArrDataCountPerMonthB[$loopMonth],array("Rank" => "3","Total" => "0","Courier" => "N/A","MonthNo" => $loopMonth,"From" => $Location,"TotalCost" => "0"));
                }
                elseif (count($ArrTempCountPerMonth[$loopMonth]) == 3)
                {
                    foreach($ArrTempCountPerMonth[$loopMonth] as $DataResult)
                    {
                        array_push($ArrDataCountPerMonthB[$loopMonth],array("Rank" => $DataResult['Rank'],"Total" => $DataResult['Total'],"Courier" => $DataResult['Courier'],"MonthNo" => $DataResult['MonthNo'],"From" => $DataResult['From'],"TotalCost" => $DataResult['TotalCost']));
                    }
                }
                else
                {
                    $NoLoopRank = 1;
                    $TTotalQty = 0;
                    $TTotalCost = 0;
                    foreach($ArrTempCountPerMonth[$loopMonth] as $DataResult)
                    {
                        if($NoLoopRank < 3)
                        {
                            array_push($ArrDataCountPerMonthB[$loopMonth],array("Rank" => $DataResult['Rank'],"Total" => $DataResult['Total'],"Courier" => $DataResult['Courier'],"MonthNo" => $DataResult['MonthNo'],"From" => $DataResult['From'],"TotalCost" => $DataResult['TotalCost']));
                        }
                        else
                        {
                            $TTotalQty = (int)$TTotalQty + (int)$DataResult['Total'];
                            $TTotalCost = (float)$TTotalCost + (float)$DataResult['TotalCost'];
                        }
                        $NoLoopRank++;
                    }

                    if($NoLoopRank > 2)
                    {
                        array_push($ArrDataCountPerMonthB[$loopMonth],array("Rank" => "3","Total" => $TTotalQty,"Courier" => "Others","MonthNo" => $loopMonth,"From" => $Location,"TotalCost" => (float)$TTotalCost));
                    }
                }
                foreach($ArrDataCountPerMonthB[$loopMonth] as $DataResult)
                {
                    array_push($ArrResultRankCost,array("Rank" => $DataResult['Rank'],"Total" => $DataResult['Total'],"Courier" => $DataResult['Courier'],"Month" => $DataResult['MonthNo'],"From" => $DataResult['From'],"TotalCost" => $DataResult['TotalCost']));
                }
                # reset array
                $ArrTempCountPerMonth[$loopMonth] = array();
            }
        }

        $MaxTotalCostPerHalfTemp = 0;
        $MaxTotalCostPerHalf = 0;
        foreach($ArrResultRankCost as $DataResult)
        {
            if($DataResult['Rank'] != '3')
            {
                $MaxTotalCostPerHalfTemp = (float)$MaxTotalCostPerHalfTemp + (float)$DataResult['TotalCost'];
            }
            else
            {
                $MaxTotalCostPerHalfTemp = (float)$MaxTotalCostPerHalfTemp + (float)$DataResult['TotalCost'];
                if($MaxTotalCostPerHalfTemp > $MaxTotalCostPerHalf)
                {
                    $MaxTotalCostPerHalf = (float)$MaxTotalCostPerHalfTemp;
                }
                $MaxTotalCostPerHalfTemp = 0;
            }
        }

        # rank qty
        $ArrRestRankQty = array();
        for($loopLocation = 1;$loopLocation <= $TotalLocation;$loopLocation++)
        {
            $NewID = $loopLocation - 1;
            $Location = $ArrLocation[$NewID]['Location'];
            for ($loopMonth=1; $loopMonth < 13; $loopMonth++)
            {
                $TotalCountDt = 0;
                $TotalOther = 0;
                $TempArrayNoOther = array();
                $TempArrayOther = array();
                
                foreach($ArrDataCountPerMonth[$loopMonth] as $ID => $DataPerMonth) # get total courier = Other && total row per month
                {
                    if($DataPerMonth['From'] == $Location)
                    {
                        $TotalCountDt++;
                        if($DataPerMonth['Courier'] == "Other")
                        {
                            array_push($TempArrayOther,array("Month" => $DataPerMonth['MonthNo'],"Total" => $DataPerMonth['Total'],"Courier" => $DataPerMonth['Courier'],"From" => $DataPerMonth['From'],"TotalCost" => $DataPerMonth['TotalCost']));
                            $TotalOther++;
                        }
                        else
                        {
                            array_push($TempArrayNoOther,array("Month" => $DataPerMonth['MonthNo'],"Total" => $DataPerMonth['Total'],"Courier" => $DataPerMonth['Courier'],"From" => $DataPerMonth['From'],"TotalCost" => $DataPerMonth['TotalCost']));
                        }
                    }                        
                }
                # sort array 
                if(count($TempArrayNoOther) != 0)
                {
                    array_multisort(array_column($TempArrayNoOther,'Total'), SORT_DESC, array_column($TempArrayNoOther,'Courier'), SORT_DESC, $TempArrayNoOther);
                }
                if(count($TempArrayOther) != 0)
                {
                    array_multisort(array_column($TempArrayOther,'Total'), SORT_DESC, array_column($TempArrayOther,'Courier'), SORT_DESC, $TempArrayOther);
                }
                array_multisort(array_column($ArrDataCountPerMonth[$loopMonth],'Total'), SORT_DESC, array_column($ArrDataCountPerMonth[$loopMonth],'Courier'), SORT_DESC, $ArrDataCountPerMonth[$loopMonth]);
		        $ArrTempQtyPerMonth[$loopMonth] = array();

                if(count($TempArrayNoOther) != 0 && count($TempArrayOther) != 0)
                { 
                    if(count($TempArrayNoOther) == 1)    # jika total = 1
                    {
                        $ValRank = 1;
                        foreach($TempArrayNoOther as $DataArr) 
                        {
                            array_push($ArrTempQtyPerMonth[$loopMonth],array("Rank" => $ValRank,"Total" => $DataArr['Total'],"Courier" => $DataArr['Courier'],"MonthNo" => $loopMonth,"From" => $Location,"TotalCost" => $DataArr['TotalCost']));
                            $ValRank++;
                        }
                        # looping other
                        foreach($TempArrayOther as $DataArr) 
                        {
                            array_push($ArrTempQtyPerMonth[$loopMonth],array("Rank" => $ValRank,"Total" => $DataArr['Total'],"Courier" => "Other","MonthNo" => $loopMonth,"From" => $Location,"TotalCost" => $DataArr['TotalCost']));                            
                            $ValRank++;
                        }
                    }
                    elseif(count($TempArrayNoOther) == 2)    # jika total = 2
                    {
                        $ValRank = 1;
                        foreach($TempArrayNoOther as $DataArr) 
                        {
                            # input array
                            array_push($ArrTempQtyPerMonth[$loopMonth],array("Rank" => $ValRank,"Total" => $DataArr['Total'],"Courier" => $DataArr['Courier'],"MonthNo" => $loopMonth,"From" => $Location,"TotalCost" => $DataArr['TotalCost']));
                            $ValRank++;
                        }
                        # looping other
                        foreach($TempArrayOther as $DataArr) 
                        {
                            array_push($ArrTempQtyPerMonth[$loopMonth],array("Rank" => $ValRank,"Total" => $DataArr['Total'],"Courier" => "Other","MonthNo" => $loopMonth,"From" => $Location,"TotalCost" => $DataArr['TotalCost']));
                            
                            $ValRank++;
                        }
                    }
                    else    # jika total >= 3
                    {
                        $ValTempTotal = 0;
                        $ValTempTotalCost = 0;
                        $ValRank = 1;
                        foreach($TempArrayNoOther as $DataArr) 
                        {
                            array_push($ArrTempQtyPerMonth[$loopMonth],array("Rank" => $ValRank,"Total" => $DataArr['Total'],"Courier" => $DataArr['Courier'],"MonthNo" => $loopMonth,"From" => $Location,"TotalCost" => $DataArr['TotalCost']));


                            $ValRank++;
                        }
                        # looping other
                        foreach($TempArrayOther as $DataArr) 
                        {
                            array_push($ArrTempQtyPerMonth[$loopMonth],array("Rank" => $ValRank,"Total" => $DataArr['Total'],"Courier" => "Other","MonthNo" => $loopMonth,"From" => $Location,"TotalCost" => $DataArr['TotalCost']));
                            $ValRank++;
                        }                        
                    }
                }
                elseif(count($TempArrayNoOther) != 0 && count($TempArrayOther) == 0)
                {
                    if(count($TempArrayNoOther) == 1)    # jika total = 1
                    {
                        $ValRank = 1;
                        foreach($TempArrayNoOther as $DataArr) 
                        {
                            array_push($ArrTempQtyPerMonth[$loopMonth],array("Rank" => $ValRank,"Total" => $DataArr['Total'],"Courier" => $DataArr['Courier'],"MonthNo" => $loopMonth,"From" => $Location,"TotalCost" => $DataArr['TotalCost']));
                            $ValRank++;
                        }
                    }
                    elseif(count($TempArrayNoOther) == 2)   # jika total = 2
                    {
                        $ValRank = 1;
                        foreach($TempArrayNoOther as $DataArr) 
                        {
                            array_push($ArrTempQtyPerMonth[$loopMonth],array("Rank" => $ValRank,"Total" => $DataArr['Total'],"Courier" => $DataArr['Courier'],"MonthNo" => $loopMonth,"From" => $Location,"TotalCost" => $DataArr['TotalCost']));
                            $ValRank++;
                        }
                    }
                    else    # jika total >= 3
                    {
                        $ValRank = 1;
                        foreach($TempArrayNoOther as $DataArr) 
                        {
                            # input array
                            array_push($ArrTempQtyPerMonth[$loopMonth],array("Rank" => $ValRank,"Total" => $DataArr['Total'],"Courier" => $DataArr['Courier'],"MonthNo" => $loopMonth,"From" => $Location,"TotalCost" => $DataArr['TotalCost']));
                            $ValRank++;
                        }
                    }
                }
                else # data kosong
                {
                    for($loopingRank = 1;$loopingRank <= 3;$loopingRank++)
                    {
                        array_push($ArrTempQtyPerMonth[$loopMonth],array("Rank" => $loopingRank,"Total" => "0","Courier" => "N/A","MonthNo" => $loopMonth,"From" => $Location,"TotalCost" => "0"));
                    }
                }
                if(count($ArrTempQtyPerMonth[$loopMonth]) == 1)
                {
                    foreach($ArrTempQtyPerMonth[$loopMonth] as $DataResult)
                    {
                        array_push($ArrDataQtyPerMonthB[$loopMonth],array("Rank" => $DataResult['Rank'],"Total" => $DataResult['Total'],"Courier" => $DataResult['Courier'],"MonthNo" => $DataResult['MonthNo'],"From" => $DataResult['From'],"TotalCost" => $DataResult['TotalCost']));
                    }
                    array_push($ArrDataQtyPerMonthB[$loopMonth],array("Rank" => "2","Total" => "0","Courier" => "N/A","MonthNo" => $loopMonth,"From" => $Location,"TotalCost" => "0"));
                    array_push($ArrDataQtyPerMonthB[$loopMonth],array("Rank" => "3","Total" => "0","Courier" => "N/A","MonthNo" => $loopMonth,"From" => $Location,"TotalCost" => "0"));
                }
                elseif (count($ArrTempQtyPerMonth[$loopMonth]) == 2)
                {
                    foreach($ArrTempQtyPerMonth[$loopMonth] as $DataResult)
                    {
                        array_push($ArrDataQtyPerMonthB[$loopMonth],array("Rank" => $DataResult['Rank'],"Total" => $DataResult['Total'],"Courier" => $DataResult['Courier'],"MonthNo" => $DataResult['MonthNo'],"From" => $DataResult['From'],"TotalCost" => $DataResult['TotalCost']));
                    }
                    array_push($ArrDataQtyPerMonthB[$loopMonth],array("Rank" => "3","Total" => "0","Courier" => "N/A","MonthNo" => $loopMonth,"From" => $Location,"TotalCost" => "0"));
                }
                elseif (count($ArrTempQtyPerMonth[$loopMonth]) == 3)
                {
                    foreach($ArrTempQtyPerMonth[$loopMonth] as $DataResult)
                    {
                        array_push($ArrDataQtyPerMonthB[$loopMonth],array("Rank" => $DataResult['Rank'],"Total" => $DataResult['Total'],"Courier" => $DataResult['Courier'],"MonthNo" => $DataResult['MonthNo'],"From" => $DataResult['From'],"TotalCost" => $DataResult['TotalCost']));
                    }
                }
                else
                {
                    $NoLoopRank = 1;
                    $TTotalQty = 0;
                    $TTotalCost = 0;
                    foreach($ArrTempQtyPerMonth[$loopMonth] as $DataResult)
                    {
                        if($NoLoopRank < 3)
                        {
                            array_push($ArrDataQtyPerMonthB[$loopMonth],array("Rank" => $DataResult['Rank'],"Total" => $DataResult['Total'],"Courier" => $DataResult['Courier'],"MonthNo" => $DataResult['MonthNo'],"From" => $DataResult['From'],"TotalCost" => $DataResult['TotalCost']));
                        }
                        else
                        {
                            $TTotalQty = (int)$TTotalQty + (int)$DataResult['Total'];
                            $TTotalCost = (float)$TTotalCost + (float)$DataResult['TotalCost'];
                        }
                        $NoLoopRank++;
                    }

                    if($NoLoopRank > 2)
                    {
                        array_push($ArrDataQtyPerMonthB[$loopMonth],array("Rank" => "3","Total" => $TTotalQty,"Courier" => "Others","MonthNo" => $loopMonth,"From" => $Location,"TotalCost" => (float)$TTotalCost));
                    }
                }
                foreach($ArrDataQtyPerMonthB[$loopMonth] as $DataResult)
                {
                    array_push($ArrRestRankQty,array("Rank" => $DataResult['Rank'],"Total" => $DataResult['Total'],"Courier" => $DataResult['Courier'],"Month" => $DataResult['MonthNo'],"From" => $DataResult['From'],"TotalCost" => $DataResult['TotalCost']));
                }
                # reset array
                $ArrDataQtyPerMonthB[$loopMonth] = array();
            }
        }

        $MaxRestRankQtyTemp = 0;
        $MaxRestRankQty = 0;
        foreach($ArrRestRankQty as $DTRestRankQty)
        {
            if($DTRestRankQty['Rank'] != '3')
            {
                $MaxRestRankQtyTemp = $MaxRestRankQtyTemp + $DTRestRankQty['Total'];
            }
            else
            {
                $MaxRestRankQtyTemp = $MaxRestRankQtyTemp + $DTRestRankQty['Total'];
                if($MaxRestRankQtyTemp > $MaxRestRankQty)
                {
                    $MaxRestRankQty = $MaxRestRankQtyTemp;
                }
                $MaxRestRankQtyTemp = 0;
            }
        }

        # data chart
        for($i = 1;$i <= $TotalLocation;$i++)
        {
            $NewID = $i - 1;
            $ValLocation = $ArrLocation[$NewID]['Location'];
            $ArrFinalChartTemp[$i] = array();
            $ArrFinalChart[$i] = array();
            $ArrFinalChart2[$i] = array();
            for ($y=1; $y < 13; $y++)
            {
                $BolCheck2 = FALSE;
                $TTotal = "";
                $TCourier = "";
                $TTotalCost = "";
                foreach($ArrDataCountPerMonth[$y] as $DataResPerMonth)
                {
                    if($DataResPerMonth['MonthNo'] == $y && $DataResPerMonth['From'] == $ValLocation)
                    {
                        $TTotal = $DataResPerMonth['Total'];
                        $TCourier = $DataResPerMonth['Courier'];
                        $TTotalCost = (float)$DataResPerMonth['TotalCost'];
                        $BolCheck2 = TRUE;
                        array_push($ArrFinalChartTemp[$i],array("Total" => $TTotal,"Courier" => $TCourier,"MonthNo" => $y,"From" => $ValLocation, "TotalCost" => $TTotalCost));
                    }
                }
                if($BolCheck2 == FALSE)
                {
                    array_push($ArrFinalChartTemp[$i],array("Total" => "0","Courier" => "-","MonthNo" => $y,"From" => $ValLocation, "TotalCost" => "0"));
                }
            }
        }
        for($i = 1;$i <= $TotalLocation;$i++)
        {            
            $NewID = $i - 1;
            $ValLocation = $ArrLocation[$NewID]['Location'];
            for ($y=1; $y < 13; $y++)
            {
                $TTotal = 0;
                $TCourier = "";
                $TTotalCost = 0;
                $NoLoop = 1;
                foreach($ArrFinalChartTemp[$i] as $DataFinalTemp)
                {
                    if($DataFinalTemp['From'] == $ValLocation && $DataFinalTemp['MonthNo'] == $y)
                    {
                        if($NoLoop == 1)
                        {
                            $TTotal = $DataFinalTemp['Total'];
                            $TCourier = $DataFinalTemp['Courier'];
                            $TTotalCost = (float)$DataFinalTemp['TotalCost'];
                            $NoLoop++;
                        }
                        else
                        {
                            if($DataFinalTemp['Total'] > $TTotal)
                            {
                                $TTotal = $DataFinalTemp['Total'];
                                $TCourier = $DataFinalTemp['Courier'];
                                $TTotalCost = (float)$DataFinalTemp['TotalCost'];
                            }
                            $NoLoop++;
                        }
                    }
                }
                array_push($ArrFinalChart[$i],array("Total"=> $TTotal,"Courier"=> $TCourier,"MonthNo" => $y,"From" => $ValLocation, "TotalCost" => $TTotalCost));
            }
            for ($y=1; $y < 13; $y++)
            {
                $TTotal = 0;
                $TCourier = "";
                $TTotalCost = 0;
                $NoLoop = 1;
                foreach($ArrFinalChartTemp[$i] as $DataFinalTemp)
                {
                    if($DataFinalTemp['From'] == $ValLocation && $DataFinalTemp['MonthNo'] == $y)
                    {
                        if($NoLoop == 1)
                        {
                            $TTotal = $DataFinalTemp['Total'];
                            $TCourier = $DataFinalTemp['Courier'];
                            $TTotalCost = (float)$DataFinalTemp['TotalCost'];
                            $NoLoop++;
                        }
                        else
                        {
                            if((float)$DataFinalTemp['TotalCost'] > $TTotalCost)
                            {
                                $TTotal = $DataFinalTemp['Total'];
                                $TCourier = $DataFinalTemp['Courier'];
                                $TTotalCost = (float)$DataFinalTemp['TotalCost'];
                            }
                            $NoLoop++;
                        }
                    }
                }
                array_push($ArrFinalChart2[$i],array("Total"=> $TTotal,"Courier"=> $TCourier,"MonthNo" => $y,"From" => $ValLocation, "TotalCost" => $TTotalCost));
            }
        }
?>
    <div class="col-md-6">
        <div class="ColumnContent" id="columnchart_material1"></div>
    </div>
    <div class="col-md-6">
        <div class="ColumnContent" id="columnchart_material2"></div>
    </div>
    <div class="col-md-12"><hr></div>
    <div class="col-md-12">
        <div class="table-responsive">
            <table id="TableDataChart3" class="table table-responsive table-bordered table-hover">
                <thead class="theadCustom">
                    <tr>
                        <th class="text-center">No</th>
                        <th class="text-center">Month</th>
                        <th class="text-center">Location</th>
                        <th class="text-center">Courier</th>
                        <th class="text-center">Qty Shipment</th>
                        <th class="text-center">Qty Shipment (%)</th>
                        <th class="text-center">Total of Freight Cost ($)</th>
                        <th class="text-center">Freight Cost<br>(%)</th>
                    </tr>
                </thead>
                <tbody><?php 
                $No = 1;
                $TotalQtyShipment = 0;
                $TotalFreightCost = 0;
                $GrandTotalFreightCost = 0;
                $ArrPercentage = array();
                for($i = 1;$i<13;$i++)
                {
                    foreach($ArrDataCountPerMonth[$i] as $DataResult1)
                    {
                        $TotalQtyShipment = $TotalQtyShipment + (int)$DataResult1['Total'];
                        $TotalFreightCost = $TotalFreightCost + (float)$DataResult1['TotalCost'];
                    }
                }
                $TotalPercentageQty = ($TotalQtyShipment / $TotalQtyShipment) * 100; 
                $TotalPercentageFreightCost = ($TotalFreightCost / $TotalFreightCost) * 100; 
                for($i = 1;$i<13;$i++)
                {
                    foreach($ArrDataCountPerMonth[$i] as $DataResult)
                    {
                        if((int)$TotalQtyShipment == 0){$PercentageQty = 0;}else{$PercentageQty = ((int)$DataResult['Total'] / (int)$TotalQtyShipment)*100;}                        
                        $PercentageQty = sprintf('%.2f',floatval($PercentageQty));
                        if((int)$TotalFreightCost == 0){$PercentageTotalCost = 0;}else{$PercentageTotalCost = ((int)$DataResult['TotalCost'] / (int)$TotalFreightCost)*100;}
                        $PercentageTotalCost = sprintf('%.2f',floatval($PercentageTotalCost));
                        $GrandTotalFreightCost = $GrandTotalFreightCost + (float)$DataResult['TotalCost'];
                    ?>
                    <tr>
                        <td class="text-center"><?php echo $No; ?></td>
                        <td class="text-center"><?php echo date('M',mktime(0, 0, 0, $DataResult['MonthNo'], 10)).' '.$InputYear; ?></td>
                        <td class="text-center"><?php echo $DataResult['From']; ?></td>
                        <td class="text-center"><?php echo $DataResult['Courier']; ?></td>
                        <td class="text-center"><?php echo (int)$DataResult['Total']; ?></td>
                        <td class="text-right"><?php echo $PercentageQty; ?></td>
                        <td class="text-right"><?php echo number_format(sprintf('%.2f',floatval($DataResult['TotalCost'])),2,'.',','); ?></td>
                        <td class="text-right"><?php echo $PercentageTotalCost; ?></td>
                    </tr>
                    <?php
                        $No++;
                        array_push($ArrPercentage,array("MonthNo" => $DataResult['MonthNo'],"From" => $DataResult['From'],"Courier" => $DataResult['Courier'],"Total" => (int)$DataResult['Total'],"Percentage" => $PercentageQty,"TotalCost" => (float)$DataResult['TotalCost'],"PercentageTotalCost" => $PercentageTotalCost));
                    }
                }               
                ?></tbody>
                <tfoot class="theadCustom">
                    <tr>
                        <td class="text-center" colspan="4"><strong>Grand Total</strong></td>
                        <td class="text-center"><?php echo "<strong>".$TotalQtyShipment."</strong>"; ?></td>
                        <td class="text-right"><?php echo "<strong>".round($TotalPercentageQty)."</strong>"; ?></td>
                        <td class="text-right"><?php echo "<strong>".number_format(sprintf('%.2f',floatval($GrandTotalFreightCost)),2,'.',',')."</strong>"; ?></td>
                        <td class="text-right"><?php echo "<strong>".round($TotalPercentageFreightCost)."</strong>"; ?></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <script type="text/javascript">
		google.charts.load('current', {
		  callback: function () {
			drawChart1();
			drawChart2();
			$(window).resize(drawChart1);
			$(window).resize(drawChart2);
		  },
		  packages:['corechart','bar']
		});
        google.charts.setOnLoadCallback(drawChart1);
        function drawChart1() {
            var data1 = new google.visualization.DataTable();
            data1.addColumn('string', 'Month');
            <?php 
                foreach ($ArrLocation as $DataLocation) {
                    for($r2 = 1;$r2 <= 3;$r2++)
                    {
                        if($r2 < 3)
                        {
                    ?>
                data1.addColumn('number', '<?php echo $DataLocation['Location']." Top ".$r2; ?>');
                    <?php
                        }
                        else
                        {
                    ?>
                data1.addColumn('number', '<?php echo $DataLocation['Location']." Others "; ?>');
                    <?php
                        }
                    }
                }
            ?>
            data1.addRows([<?php
            for($i = 1;$i<13;$i++)
            {
                echo "[";
                echo "'".date('M',mktime(0, 0, 0, $i, 1, $InputYear))."'";
                for($i2 = 1;$i2 <= $TotalLocation;$i2++)
                {
                    $NewID = $i2 - 1;
                    $Location = $ArrLocation[$NewID]['Location'];
                    for($r2 = 1;$r2 <= 3;$r2++)
                    {
                        foreach($ArrResultRankCost as $DataRank)
                        {
                            if($DataRank['Rank'] == $r2 && $DataRank['From'] == $Location && $DataRank['Month'] == $i)
                            {
                                echo ",{v:".$DataRank['TotalCost'].",f:'".$DataRank['Courier']." : $".number_format(sprintf('%.2f',floatval($DataRank['TotalCost'])),2,'.',',')."'}";
                                break;
                            }
                        }
                    }
                }
                echo "],";
            }
            ?>]);
            var options1 = {
                    title: 'The Top 2 Freight Cost by Courier (<?php echo $InputYear; ?>)'
                    ,titleTextStyle: {fontSize: 15, bold: true, color: '#000000'}
                    ,tooltip: { isHtml: true }
                    ,legend: { position: 'right', maxLines: 3 }
                    ,chartArea: {top:50, left:50, height:"80%", width:"58%"}  
                    ,isStacked: true
                    ,hAxis : {
                        titleTextStyle: {color: '#000000',bold: true}
                    }
                    ,vAxes: {
                            0: { textPosition: 'out', title: 'Total Cost ($)',titleTextStyle: {color: '#000000',bold: true}
                            },
                            1: {
                                gridlines: {
                                color: 'transparent'
                                },
                                textStyle: {
                                color: 'transparent'
                                }
                            },
                            2: {
                                gridlines: {
                                color: 'transparent'
                                },
                                textStyle: {
                                color: 'transparent'
                                }
                            },
                    },
                    series: {
                        0: {
                            targetAxisIndex: 0,
                            color : '#3366CC'
                        },
                        1: {
                            targetAxisIndex: 0,
                            color : '#527ED3'
                        },
                        2: {
                            targetAxisIndex: 0,
                            color : '#7196D9'
                        },
                        3: {
                            targetAxisIndex: 1,
                            color : '#DC3912'
                        },
                        4: {
                            targetAxisIndex: 1,
                            color : '#EC9A87'
                        },
                        5: {
                            targetAxisIndex: 1,
                            color : '#f2b8a9'
                        },
                        6: {
                            targetAxisIndex: 2,
                            color : '#FF9900'
                        },
                        7: {
                            targetAxisIndex: 2,
                            color : '#ffb23f'
                        },
                        8: {
                            targetAxisIndex: 2,
                            color : '#fcc779'
                        },
                    },
                    width: '100%',
                    vAxis: {
                        viewWindowMode: 'explicit',
                        viewWindow: {
                            min: 0,
                            max: <?php echo (int)($MaxTotalCostPerHalf)+1; ?>
                        }
                    }
            };            
            var chart1 = new google.charts.Bar(document.getElementById('columnchart_material1'));
            chart1.draw(data1, google.charts.Bar.convertOptions(options1));
            
        };  
        google.charts.setOnLoadCallback(drawChart2);
        function drawChart2() {
            var data2 = new google.visualization.DataTable();
            data2.addColumn('string', 'Month');
            <?php 
                foreach ($ArrLocation as $DataLocation) {
                    for($r2 = 1;$r2 <= 3;$r2++)
                    {
                        if($r2 < 3)
                        {
                    ?>
                data2.addColumn('number', '<?php echo $DataLocation['Location']." Top ".$r2; ?>');
                    <?php
                        }
                        else
                        {
                    ?>
                data2.addColumn('number', '<?php echo $DataLocation['Location']." Others "; ?>');
                    <?php
                        }
                    }
                }
            ?>
            data2.addRows([<?php
            for($i = 1;$i<13;$i++)
            {
                echo "[";
                echo "'".date('M',mktime(0, 0, 0, $i, 1, $InputYear))."'";
                for($i2 = 1;$i2 <= $TotalLocation;$i2++)
                {
                    $NewID = $i2 - 1;
                    $Location = $ArrLocation[$NewID]['Location'];
                    for($r2 = 1;$r2 <= 3;$r2++)
                    {
                        foreach($ArrRestRankQty as $DataRank)
                        {
                            if($DataRank['Rank'] == $r2 && $DataRank['From'] == $Location && $DataRank['Month'] == $i)
                            {
                                echo ",{v:".$DataRank['Total'].",f:'".$DataRank['Courier']." : ".(int)$DataRank['Total']."'}";
                                break;
                            }
                        }
                    }
                }
                echo "],";
            }
            ?>]);
            var options2 = {
                    title: 'The Top 2 Total Qty Shipment (<?php echo $InputYear; ?>)'
                    ,titleTextStyle: {fontSize: 15, bold: true, color: '#000000'}
                    ,tooltip: { isHtml: true }
                    ,legend: { position: 'right', maxLines: 3 }
                    ,isStacked: true
                    ,hAxis : {
                        titleTextStyle: {color: '#000000',bold: true}
                    }
                    ,vAxes: {
                            0: { textPosition: 'out', title: 'Total Qty Shipment',titleTextStyle: {color: '#000000',bold: true}
                            },
                            1: {
                                gridlines: {
                                color: 'transparent'
                                },
                                textStyle: {
                                color: 'transparent'
                                }
                            },
                            2: {
                                gridlines: {
                                color: 'transparent'
                                },
                                textStyle: {
                                color: 'transparent'
                                }
                            },
                    },
                    series: {
                        0: {
                            targetAxisIndex: 0,
                            color : '#3366CC'
                        },
                        1: {
                            targetAxisIndex: 0,
                            color : '#527ED3'
                        },
                        2: {
                            targetAxisIndex: 0,
                            color : '#7196D9'
                        },
                        3: {
                            targetAxisIndex: 1,
                            color : '#DC3912'
                        },
                        4: {
                            targetAxisIndex: 1,
                            color : '#EC9A87'
                        },
                        5: {
                            targetAxisIndex: 1,
                            color : '#f2b8a9'
                        },
                        6: {
                            targetAxisIndex: 2,
                            color : '#FF9900'
                        },
                        7: {
                            targetAxisIndex: 2,
                            color : '#ffb23f'
                        },
                        8: {
                            targetAxisIndex: 2,
                            color : '#fcc779'
                        },
                    },
                    width: '100%',
                    vAxis: {
                        viewWindowMode: 'explicit',
                        viewWindow: {
                            min: 0,
                            max: <?php echo $MaxRestRankQty+1; ?>
                        }
                    }
            };            
            var chart2 = new google.charts.Bar(document.getElementById('columnchart_material2'));
            chart2.draw(data2, google.charts.Bar.convertOptions(options2));            
        };   
    </script>
    <style>
    .ColumnContent{width:100%; height: 400px;}
    </style>

        <?php
    }
}
else
{
    echo "";     
}
?>