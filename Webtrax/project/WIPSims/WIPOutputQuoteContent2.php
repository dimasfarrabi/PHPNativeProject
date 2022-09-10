<?php 
session_start();
require_once("../../src/Modules/ModuleLogin.php");
require_once("Modules/ModuleWIPSims.php");
date_default_timezone_set("Asia/Jakarta");
$DateNow = date("m/d/Y");

function GetStartAndEndDateByWeeks($week, $year) {
    $dto = new DateTime();
    $dto->setISODate($year, $week);
    $Start = $dto->format('m/d/Y');
    $dto->modify('+6 days');
    $End = $dto->format('m/d/Y');
    return $Start."#".$End;
}



if($_SERVER['REQUEST_METHOD'] == "POST")
{   
    $ValQuoteCategorySelected = htmlspecialchars(trim($_POST['ValQuoteCategorySelected']), ENT_QUOTES, "UTF-8");
    $ValDataFilter = htmlspecialchars(trim($_POST['ValDataFilter']), ENT_QUOTES, "UTF-8");
    $ValQuoteName = htmlspecialchars(trim($_POST['ValQuoteName']), ENT_QUOTES, "UTF-8");
    # data project
    $QDataProject = GET_DATA_PROJECT_BY_NAME($ValQuoteCategorySelected,$ValQuoteName,$linkMACHWebTrax);
    $RDataProject = sqlsrv_fetch_array($QDataProject);
    $ValProjectID = trim($RDataProject['Idx']);
    switch ($ValDataFilter) {
        case 'Weekly':
            {
                # get data date
                $Yesterday = date("m/d/Y",strtotime("-1 day"));
                $WeekName1 = date('W',strtotime($Yesterday));
                $YearWeekName1 = date("Y",strtotime($Yesterday));
                $RangeWeek1 = GetStartAndEndDateByWeeks($WeekName1,$YearWeekName1);
                $ArrRangeWeek1 = explode("#",$RangeWeek1);
                $WeeklyStart1 = $ArrRangeWeek1[0];
                // $WeeklyEnd1 = $ArrRangeWeek1[1];
                $WeeklyEnd1 = $Yesterday;
                $YearWeekNameBefore = date("Y",strtotime("-1 year",strtotime($YearWeekName1)));
                if($WeekName1 < 4)
                {                    
                    $WeekName2 = date("W",strtotime("-1 day",strtotime($WeeklyStart1)));
                    if((int)$WeekName2 > (int)$WeekName1)
                    { 
                        $YearWeekName2 = $YearWeekNameBefore;
                    }
                    else
                    { 
                        $YearWeekName2 = $YearWeekName1;
                    }
                    $RangeWeek2 = GetStartAndEndDateByWeeks($WeekName2,$YearWeekName2);
                    $ArrRangeWeek2 = explode("#",$RangeWeek2);
                    $WeeklyStart2 = $ArrRangeWeek2[0];
                    $WeeklyEnd2 = $ArrRangeWeek2[1];                    

                    $WeekName3 = date("W",strtotime("-1 day",strtotime($WeeklyStart2)));
                    if((int)$WeekName3 > (int)$WeekName1)
                    { 
                        $YearWeekName3 = $YearWeekNameBefore;
                    }
                    else
                    { 
                        $YearWeekName3 = $YearWeekName1;
                    }
                    $RangeWeek3 = GetStartAndEndDateByWeeks($WeekName3,$YearWeekName3);
                    $ArrRangeWeek3 = explode("#",$RangeWeek3);
                    $WeeklyStart3 = $ArrRangeWeek3[0];
                    $WeeklyEnd3 = $ArrRangeWeek3[1];
                    
                    $WeekName4 = date("W",strtotime("-1 day",strtotime($WeeklyStart3)));
                    if((int)$WeekName4 > (int)$WeekName1)
                    { 
                        $YearWeekName4 = $YearWeekNameBefore;
                    }
                    else
                    { 
                        $YearWeekName4 = $YearWeekName1;
                    }
                    $RangeWeek4 = GetStartAndEndDateByWeeks($WeekName4,$YearWeekName4);
                    $ArrRangeWeek4 = explode("#",$RangeWeek4);
                    $WeeklyStart4 = $ArrRangeWeek4[0];
                    $WeeklyEnd4 = $ArrRangeWeek4[1];
                }
                else
                {
                    $WeekName2 = date("W",strtotime("-1 day",strtotime($WeeklyStart1)));
                    $YearWeekName2 = $YearWeekName1;
                    $RangeWeek2 = GetStartAndEndDateByWeeks($WeekName2,$YearWeekName2);
                    $ArrRangeWeek2 = explode("#",$RangeWeek2);
                    $WeeklyStart2 = $ArrRangeWeek2[0];
                    $WeeklyEnd2 = $ArrRangeWeek2[1];

                    $WeekName3 = date("W",strtotime("-1 day",strtotime($WeeklyStart2)));
                    $YearWeekName3 = $YearWeekName1;
                    $RangeWeek3 = GetStartAndEndDateByWeeks($WeekName3,$YearWeekName3);
                    $ArrRangeWeek3 = explode("#",$RangeWeek3);
                    $WeeklyStart3 = $ArrRangeWeek3[0];
                    $WeeklyEnd3 = $ArrRangeWeek3[1];
                    
                    $WeekName4 = date("W",strtotime("-1 day",strtotime($WeeklyStart3)));
                    $YearWeekName4 = $YearWeekName1;
                    $RangeWeek4 = GetStartAndEndDateByWeeks($WeekName4,$YearWeekName4);
                    $ArrRangeWeek4 = explode("#",$RangeWeek4);
                    $WeeklyStart4 = $ArrRangeWeek4[0];
                    $WeeklyEnd4 = $ArrRangeWeek4[1];
               
                }
                $Date1ID = $YearWeekName1."-"."Wk.".$WeekName1;
                $Date2ID = $YearWeekName2."-"."Wk.".$WeekName2;
                $Date3ID = $YearWeekName3."-"."Wk.".$WeekName3;
                $Date4ID = $YearWeekName4."-"."Wk.".$WeekName4;
                
                $QData = GET_DATA_OUTPUT_2DATE($ValProjectID,$WeeklyStart4,$WeeklyEnd4,$WeeklyStart3,$WeeklyEnd3,$WeeklyStart2,$WeeklyEnd2,$WeeklyStart1,$WeeklyEnd1,$linkMACHWebTrax);
                

            }
            break;
        case 'Monthly':
            {                
                # get data date
                $Yesterday = date("m/d/Y",strtotime("-1 day"));
                // $Yesterday = "01/10/2021";
                $MonthlyName1 = date('M',strtotime($Yesterday));
                $YearMonthlyName1 = date("Y",strtotime($Yesterday));
                $YearWeekNameBefore = date("Y",strtotime("-1 year",strtotime($Yesterday)));
                $MonthlyStart1 = date("m/01/Y",strtotime($Yesterday));
                $MonthlyEnd1 = $Yesterday;
                $MonthlyStart2 = date("m/01/Y",strtotime("-1 month",strtotime($Yesterday)));
                $MonthlyEnd2 = date("m/t/Y",strtotime($MonthlyStart2));
                $YearMonthlyName2 = date("Y",strtotime($MonthlyStart2));
                $MonthlyName2 = date('M',strtotime($MonthlyStart2));
                $MonthlyStart3 = date("m/01/Y",strtotime("-1 month",strtotime($MonthlyStart2)));
                $MonthlyEnd3 = date("m/t/Y",strtotime($MonthlyStart3));
                $YearMonthlyName3 = date("Y",strtotime($MonthlyStart3));
                $MonthlyName3 = date('M',strtotime($MonthlyStart3));
                $MonthlyStart4 = date("m/01/Y",strtotime("-1 month",strtotime($MonthlyStart3)));
                $MonthlyEnd4 = date("m/t/Y",strtotime($MonthlyStart4));
                $YearMonthlyName4 = date("Y",strtotime($MonthlyStart4));
                $MonthlyName4 = date('M',strtotime($MonthlyStart4));
                
                $Date1ID = $YearMonthlyName1."-".$MonthlyName1;
                $Date2ID = $YearMonthlyName2."-".$MonthlyName2;
                $Date3ID = $YearMonthlyName3."-".$MonthlyName3;
                $Date4ID = $YearMonthlyName4."-".$MonthlyName4;

                $QData = GET_DATA_OUTPUT_2DATE($ValProjectID,$MonthlyStart4,$MonthlyEnd4,$MonthlyStart3,$MonthlyEnd3,$MonthlyStart2,$MonthlyEnd2,$MonthlyStart1,$MonthlyEnd1,$linkMACHWebTrax);

            }
            break;
        case 'Half':
            {
                # get data date
                $Yesterday = date("m/d/Y",strtotime("-1 day"));
                // $Yesterday = "11/10/2020";
                $MonthHalfName1 = date('n',strtotime($Yesterday));
                $YearHalfName1 = date("Y",strtotime($Yesterday));
                if($MonthHalfName1 < 7)
                {
                    $HalfName1 = "H1";
                    $HalfStart1 = date("01/01/Y");
                    $HalfEnd1 = $Yesterday;
                }
                else
                {
                    $HalfName1 = "H2";
                    $HalfStart1 = date("07/01/Y");
                    $HalfEnd1 = $Yesterday;
                }
                $HalfStart2 = date("m/d/Y",strtotime("-1 month",strtotime($HalfStart1)));
                $YearHalfName2 = date("Y",strtotime($HalfStart2));
                $MonthHalfName2 = date('n',strtotime($HalfStart2));
                if($MonthHalfName2 < 7)
                {
                    $HalfName2 = "H1";
                    $HalfStart2 = "01/01/".$YearHalfName2;
                    $HalfEnd2 = "06/30/".$YearHalfName2;
                }
                else
                {
                    $HalfName2 = "H2";
                    $HalfStart2 = "07/01/".$YearHalfName2;
                    $HalfEnd2 = "12/31/".$YearHalfName2;
                }
                if($HalfName2 == "H1")
                {
                    $HalfStart3 = date("m/d/Y",strtotime("-1 month",strtotime($HalfStart2)));
                    $YearHalfName3 = date("Y",strtotime($HalfStart3));
                    $HalfStart3 = "07/01/".$YearHalfName3;
                    $HalfEnd3 = "12/31/".$YearHalfName3;
                    $MonthHalfName3 = date('n',strtotime($HalfStart3));
                    $HalfName3 = "H2";
                }
                else
                {
                    $HalfName3 = "H1";
                    $YearHalfName3 = $YearHalfName2;
                    $HalfStart3 = "01/01/".$YearHalfName3;
                    $HalfEnd3 = "06/30/".$YearHalfName3;
                    $MonthHalfName3 = date('n',strtotime($HalfStart3));
                }
                if($HalfName3 == "H1")
                {
                    $HalfStart4 = date("m/d/Y",strtotime("-1 month",strtotime($HalfStart3)));
                    $YearHalfName4 = date("Y",strtotime($HalfStart4));
                    $HalfStart4 = "07/01/".$YearHalfName4;
                    $HalfEnd4 = "12/31/".$YearHalfName4;
                    $MonthHalfName4 = date('n',strtotime($HalfStart4));
                    $HalfName4 = "H2";
                }
                else
                {
                    $HalfName4 = "H1";
                    $YearHalfName4 = $YearHalfName3;
                    $HalfStart4 = "01/01/".$YearHalfName4;
                    $HalfEnd4 = "06/30/".$YearHalfName4;
                    $MonthHalfName4 = date('n',strtotime($HalfStart4));
                }               

                $Date1ID = $YearHalfName1."-".$HalfName1;
                $Date2ID = $YearHalfName2."-".$HalfName2;
                $Date3ID = $YearHalfName3."-".$HalfName3;
                $Date4ID = $YearHalfName4."-".$HalfName4;

                $QData = GET_DATA_OUTPUT_2DATE($ValProjectID,$HalfStart4,$HalfEnd4,$HalfStart3,$HalfEnd3,$HalfStart2,$HalfEnd2,$HalfStart1,$HalfEnd1,$linkMACHWebTrax);

            }
            break;
        case 'Year':
            {                
                $YearStart4 = date("01/01/Y",strtotime("-3 years"));
                $YearEnd4 = date("12/31/Y",strtotime("-3 years"));
                $Year4 = date('Y',strtotime($YearStart4));
                $YearStart3 = date("01/01/Y",strtotime("-2 years"));
                $YearEnd3 = date("12/31/Y",strtotime("-2 years"));
                $Year3 = date('Y',strtotime($YearStart3));
                $YearStart2 = date("01/01/Y",strtotime("-1 year"));
                $YearEnd2 = date("12/31/Y",strtotime("-1 year"));
                $Year2 = date('Y',strtotime($YearStart2));
                $YearStart1 = date("01/01/Y");
                $YearEnd1 = date("m/d/Y",strtotime("-1 day"));
                $Year1 = date('Y',strtotime($YearStart1));
                $QData = GET_DATA_OUTPUT_2DATE($ValProjectID,$YearStart4,$YearEnd4,$YearStart3,$YearEnd3,$YearStart2,$YearEnd2,$YearStart1,$YearEnd1,$linkMACHWebTrax);
                
                $Date1ID = $Year1;
                $Date2ID = $Year2;
                $Date3ID = $Year3;
                $Date4ID = $Year4;

            }
            break;
        default:
                $Date1 = date("m/d/Y",strtotime("-1 day"));
                $Date1ID = "".date("m/d/",strtotime($Date1))."".substr(date("Y",strtotime($Date1)),-2);
                $Date2 = date("m/d/Y",strtotime("-2 day"));  
                $Date2ID = "".date("m/d/",strtotime($Date2))."".substr(date("Y",strtotime($Date2)),-2);    
                $Date3 = date("m/d/Y",strtotime("-3 day"));
                $Date3ID = "".date("m/d/",strtotime($Date3))."".substr(date("Y",strtotime($Date3)),-2);
                $Date4 = date("m/d/Y",strtotime("-4 day"));
                $Date4ID = "".date("m/d/",strtotime($Date4))."".substr(date("Y",strtotime($Date4)),-2);

                $QData = GET_DATA_OUTPUT_DAILY($ValProjectID,$Date4,$Date3,$Date2,$Date1,$linkMACHWebTrax);
            break;
    }

?>
<style>.TimeLog{cursor: pointer;color: #337AB7;}</style>
<div class="col-md-12">
    <div class="table-responsive">
        <table class="table table-bordered table-hover" id="TableQuoteSelected">
            <thead class="theadCustom">
                <tr>
                    <td class="text-center trowCustom" width="30"><strong>No</strong></td>
                    <td class="text-center trowCustom" width="100"><strong>PartNo</strong></td>
                    <td class="text-center trowCustom"><strong>Part Description</strong></td>
                    <td class="text-center trowCustom" width="110"><strong>Qty Output<br>(<?php echo $Date4ID; ?>)</strong></td>
                    <td class="text-center trowCustom" width="110"><strong>Qty Output<br>(<?php echo $Date3ID; ?>)</strong></td>
                    <td class="text-center trowCustom" width="110"><strong>Qty Output<br>(<?php echo $Date2ID; ?>)</strong></td>
                    <td class="text-center trowCustom" width="110"><strong>Qty Output<br>(<?php echo $Date1ID; ?>)</strong></td>
                </tr>
            </thead>
            <tbody><?php
            $No = 1;
            while($RData = sqlsrv_fetch_array($QData))
            {
                $ValPartNo = trim($RData['PartNo']);
                $ValPartDesc = htmlspecialchars_decode(trim($RData['PartDesc']), ENT_QUOTES);
                $ValQtyOutput1 = trim($RData['QtyOutput1']);
                $ValQtyOutput1 = number_format((float)$ValQtyOutput1, 0, '.', ',');
                $ValQtyOutput2 = trim($RData['QtyOutput2']);
                $ValQtyOutput2 = number_format((float)$ValQtyOutput2, 0, '.', ',');
                $ValQtyOutput3 = trim($RData['QtyOutput3']);
                $ValQtyOutput3 = number_format((float)$ValQtyOutput3, 0, '.', ',');
                $ValQtyOutput4 = trim($RData['QtyOutput4']);
                $ValQtyOutput4 = number_format((float)$ValQtyOutput4, 0, '.', ',');
                ?>
                <tr>
                    <td class="text-center"><?php echo $No; ?></td>
                    <td class="text-center"><?php echo $ValPartNo; ?></td>
                    <td class="text-left"><?php echo $ValPartDesc; ?></td>
                    <td class="text-center TimeLog"><?php echo $ValQtyOutput1; ?></td>
                    <td class="text-center TimeLog"><?php echo $ValQtyOutput2; ?></td>
                    <td class="text-center TimeLog"><?php echo $ValQtyOutput3; ?></td>
                    <td class="text-center TimeLog"><?php echo $ValQtyOutput4; ?></td>
                </tr>
                <?php
                $No++;
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