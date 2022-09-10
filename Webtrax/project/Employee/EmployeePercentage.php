<?php  
require_once("project/Employee/Modules/ModuleEmployee.php");
date_default_timezone_set("Asia/Jakarta");
$YearNow = date("Y");
$DateNow = date("m/d/Y");
/*
if(!session_is_registered("UIDWebTrax"))
{
    ?>
    <script language="javascript">
        window.location.replace("https://webtrax.formulatrix.com/");
    </script>
    <?php
    exit();
}
if($AccessLogin == "Reguler")
{
    ?>
    <script language="javascript">
        window.location.href = "home.php";
    </script>
    <?php
    exit();
}
*/
$ValGroup = "ALL DEPARTMENT";
# list employee FI & PSL
$ArrayDataEmployee = array();
$QListPSL = LIST_EMPLOYEE_SIMPLE_PSL($linkHRISWebTrax);
while($RListPSL = sqlsrv_fetch_array($QListPSL))
{
    $ValPSLNIK = trim($RListPSL['NIK']);
    $ValPSLFN = trim($RListPSL['FullName']);
    $ValPSLDivName = trim($RListPSL['DivisionName']);
    $ValPSLID = trim($RListPSL['IDCard']);
    $ValPSLMobile = trim($RListPSL['Mobile']);
    $ValPSLEmail = trim($RListPSL['Email']);
    $ValPSLGenderID = trim($RListPSL['Gender_ID']);
    $ValPSLPicPath = trim($RListPSL['PicPath']);
    $ArrayTemp = array(
        "NIK" => $ValPSLNIK,
        "FN" => $ValPSLFN,
        "DivName" => $ValPSLDivName,
        "ID" => $ValPSLID,
        "Mobile" => $ValPSLMobile,
        "Email" => $ValPSLEmail,
        "GenderID" => $ValPSLGenderID,
        "PicPath" => "https://sik.formulatrix.com/FOTOKARYAWAN/".$ValPSLPicPath,
        "Location" => "PSL"
    );
    array_push($ArrayDataEmployee,$ArrayTemp);
}
# list employee PSM
// $QListPSM = LIST_EMPLOYEE_SIMPLE_PSM($PSMConn);
// while($RListPSM = sqlsrv_fetch_array($QListPSM))
// {
//     $ValPSMNIK = trim($RListPSM['NIK']);
//     $ValPSMFN = trim($RListPSM['FullName']);
//     $ValPSMDivName = trim($RListPSM['DivisionName']);
//     $ValPSMID = trim($RListPSM['IDCard']);
//     $ValPSMMobile = trim($RListPSM['Mobile']);
//     $ValPSMEmail = trim($RListPSM['Email']);
//     $ValPSMGenderID = trim($RListPSM['Gender_ID']);
//     $ValPSMPicPath = trim($RListPSM['PicPath']);
//     $ArrayTemp = array(
//         "NIK" => $ValPSMNIK,
//         "FN" => $ValPSMFN,
//         "DivName" => $ValPSMDivName,
//         "ID" => $ValPSMID,
//         "Mobile" => $ValPSMMobile,
//         "Email" => $ValPSMEmail,
//         "GenderID" => $ValPSMGenderID,
//         "PicPath" => "https://sik.promanufacture.co.id/sites/FOTOKARYAWAN/".$ValPSMPicPath,
//         "Location" => "PSM"
//     );
//     array_push($ArrayDataEmployee,$ArrayTemp);
// }
# list employee by department
$ArrayDataEmployeeDepartment = array();
$QListDataEmpDepartment = GET_DATA_PTO_ALL($linkHRISWebTrax);
while($RListDataEmpDepartment = sqlsrv_fetch_array($QListDataEmpDepartment))
{
    $ValEmpName = trim($RListDataEmpDepartment['EmployeeName']);
    $ValLocation = trim($RListDataEmpDepartment['Location']);
    foreach ($ArrayDataEmployee as $DataEmployee)
    {
        if($ValEmpName == trim($DataEmployee['FN']) && $ValLocation == trim($DataEmployee['Location']))
        {
            $ArrTemp = array(
                "NIK" => trim($DataEmployee['NIK']),
                "FN" => trim($DataEmployee['FN']),
                "DivName" => trim($DataEmployee['DivName']),
                "ID" => trim($DataEmployee['ID']),
                "Mobile" => trim($DataEmployee['Mobile']),
                "Email" => trim($DataEmployee['Email']),
                "GenderID" => trim($DataEmployee['GenderID']),
                "PicPath" => trim($DataEmployee['PicPath']),
                "Location" => trim($DataEmployee['Location']),
                "Department" => trim($RListDataEmpDepartment['Department'])
            );
            array_push($ArrayDataEmployeeDepartment,$ArrTemp);
        }
    }
}
# count data table
$TotalAdmPSL = 0;$TotalAdmPSM = 0;$TotalAdm = 0;
$TotalEngPSL = 0;$TotalEngPSM = 0;$TotalEng = 0;
$TotalProdPSL = 0;$TotalProdPSM = 0;$TotalProd = 0;
$TotalAdmMalePSL = 0;$TotalAdmFemalePSL = 0;$TotalAdmGenderPSL = 0;
$TotalEngMalePSL = 0;$TotalEngFemalePSL = 0;$TotalEngGenderPSL = 0;
$TotalProdMalePSL = 0;$TotalProdFemalePSL = 0;$TotalProdGenderPSL = 0;
$TotalAdmMalePSM = 0;$TotalAdmFemalePSM = 0;$TotalAdmGenderPSM = 0;
$TotalEngMalePSM = 0;$TotalEngFemalePSM = 0;$TotalEngGenderPSM = 0;
$TotalProdMalePSM = 0;$TotalProdFemalePSM = 0;$TotalProdGenderPSM = 0;
foreach($ArrayDataEmployeeDepartment as $DataEmpDepart)
{
    if(trim($DataEmpDepart['Department']) == "Administration" && trim($DataEmpDepart['Location']) == "PSL")
    {
        $TotalAdmPSL = $TotalAdmPSL + 1;
        $TotalAdm = $TotalAdm + 1;
    }
    if(trim($DataEmpDepart['Department']) == "Administration" && trim($DataEmpDepart['Location']) == "PSM")
    {
        $TotalAdmPSM = $TotalAdmPSM + 1;
        $TotalAdm = $TotalAdm + 1;
    }
    if(trim($DataEmpDepart['Department']) == "Engineering" && trim($DataEmpDepart['Location']) == "PSL")
    {
        $TotalEngPSL = $TotalEngPSL + 1;
        $TotalEng = $TotalEng + 1;
    }
    if(trim($DataEmpDepart['Department']) == "Engineering" && trim($DataEmpDepart['Location']) == "PSM")
    {
        $TotalEngPSM = $TotalEngPSM + 1;
        $TotalEng = $TotalEng + 1;
    }
    if(trim($DataEmpDepart['Department']) == "Production" && trim($DataEmpDepart['Location']) == "PSL")
    {
        $TotalProdPSL = $TotalProdPSL + 1;
        $TotalProd = $TotalProd + 1;
    }
    if(trim($DataEmpDepart['Department']) == "Production" && trim($DataEmpDepart['Location']) == "PSM")
    {
        $TotalProdPSM = $TotalProdPSM + 1;
        $TotalProd = $TotalProd + 1;
    }
    /////////////////////////////////////////////////////
    if(trim($DataEmpDepart['Department']) == "Administration" && trim($DataEmpDepart['GenderID']) == "1" && trim($DataEmpDepart['Location']) == "PSL")
    {
        $TotalAdmMalePSL = $TotalAdmMalePSL + 1;
        $TotalAdmGenderPSL = $TotalAdmGenderPSL + 1;
    }
    if(trim($DataEmpDepart['Department']) == "Administration" && trim($DataEmpDepart['GenderID']) == "2" && trim($DataEmpDepart['Location']) == "PSL")
    {
        $TotalAdmFemalePSL = $TotalAdmFemalePSL + 1;
        $TotalAdmGenderPSL = $TotalAdmGenderPSL + 1;
    }
    if(trim($DataEmpDepart['Department']) == "Engineering" && trim($DataEmpDepart['GenderID']) == "1" && trim($DataEmpDepart['Location']) == "PSL")
    {
        $TotalEngMalePSL = $TotalEngMalePSL + 1;
        $TotalEngGenderPSL = $TotalEngGenderPSL + 1;
    }
    if(trim($DataEmpDepart['Department']) == "Engineering" && trim($DataEmpDepart['GenderID']) == "2" && trim($DataEmpDepart['Location']) == "PSL")
    {
        $TotalEngFemalePSL = $TotalEngFemalePSL + 1;
        $TotalEngGenderPSL = $TotalEngGenderPSL + 1;
    }
    if(trim($DataEmpDepart['Department']) == "Production" && trim($DataEmpDepart['GenderID']) == "1" && trim($DataEmpDepart['Location']) == "PSL")
    {
        $TotalProdMalePSL = $TotalProdMalePSL + 1;
        $TotalProdGenderPSL = $TotalProdGenderPSL + 1;
    }
    if(trim($DataEmpDepart['Department']) == "Production" && trim($DataEmpDepart['GenderID']) == "2" && trim($DataEmpDepart['Location']) == "PSL")
    {
        $TotalProdFemalePSL = $TotalProdFemalePSL + 1;
        $TotalProdGenderPSL = $TotalProdGenderPSL + 1;
    }
    /////////////////////////////////////////////////////
    if(trim($DataEmpDepart['Department']) == "Administration" && trim($DataEmpDepart['GenderID']) == "1" && trim($DataEmpDepart['Location']) == "PSM")
    {
        $TotalAdmMalePSM = $TotalAdmMalePSM + 1;
        $TotalAdmGenderPSM = $TotalAdmGenderPSM + 1;
    }
    if(trim($DataEmpDepart['Department']) == "Administration" && trim($DataEmpDepart['GenderID']) == "2" && trim($DataEmpDepart['Location']) == "PSM")
    {
        $TotalAdmFemalePSM = $TotalAdmFemalePSM + 1;
        $TotalAdmGenderPSM = $TotalAdmGenderPSM + 1;
    }
    if(trim($DataEmpDepart['Department']) == "Engineering" && trim($DataEmpDepart['GenderID']) == "1" && trim($DataEmpDepart['Location']) == "PSM")
    {
        $TotalEngMalePSM = $TotalEngMalePSM + 1;
        $TotalEngGenderPSM = $TotalEngGenderPSM + 1;
    }
    if(trim($DataEmpDepart['Department']) == "Engineering" && trim($DataEmpDepart['GenderID']) == "2" && trim($DataEmpDepart['Location']) == "PSM")
    {
        $TotalEngFemalePSM = $TotalEngFemalePSM + 1;
        $TotalEngGenderPSM = $TotalEngGenderPSM + 1;
    }
    if(trim($DataEmpDepart['Department']) == "Production" && trim($DataEmpDepart['GenderID']) == "1" && trim($DataEmpDepart['Location']) == "PSM")
    {
        $TotalProdMalePSM = $TotalProdMalePSM + 1;
        $TotalProdGenderPSM = $TotalProdGenderPSM + 1;
    }
    if(trim($DataEmpDepart['Department']) == "Production" && trim($DataEmpDepart['GenderID']) == "2" && trim($DataEmpDepart['Location']) == "PSM")
    {
        $TotalProdFemalePSM = $TotalProdFemalePSM + 1;
        $TotalProdGenderPSM = $TotalProdGenderPSM + 1;
    }
}
# count data chart 1
$TotalDepartment = $TotalAdm + $TotalEng + $TotalProd;
$PercentageDepartmentAdm = 0;
$PercentageDepartmentEng = 0;
$PercentageDepartmentProd = 0;
if($TotalAdm == "0"){$PercentageDepartmentAdm = "0";}else{$PercentageDepartmentAdm = ($TotalAdm/$TotalDepartment)*100;}
if($TotalEng == "0"){$PercentageDepartmentEng = "0";}else{$PercentageDepartmentEng = ($TotalEng/$TotalDepartment)*100;}
if($TotalProd == "0"){$PercentageDepartmentProd = "0";}else{$PercentageDepartmentProd = ($TotalProd/$TotalDepartment)*100;}
$TotalPercentageDepartment = $PercentageDepartmentAdm + $PercentageDepartmentEng + $PercentageDepartmentProd;
$TotalPercentageDepartment = number_format((float)$TotalPercentageDepartment, 0, '.', ',');
$PercentageDepartmentAdm = number_format((float)$PercentageDepartmentAdm, 2, '.', ',');
$PercentageDepartmentEng = number_format((float)$PercentageDepartmentEng, 2, '.', ',');
$PercentageDepartmentProd = number_format((float)$PercentageDepartmentProd, 2, '.', ',');
$ArrChartDepartment = array();
$TempArrayAdmDepart = array(
    "Info" => "Administration",
    "TotalEmployee" => "".$TotalAdm."",
    "Percentage" => "".$PercentageDepartmentAdm.""
);
array_push($ArrChartDepartment,$TempArrayAdmDepart);
$TempArrayEngDepart = array(
    "Info" => "Engineering",
    "TotalEmployee" => "".$TotalEng."",
    "Percentage" => "".$PercentageDepartmentEng.""
);
array_push($ArrChartDepartment,$TempArrayEngDepart);
$TempArrayProdDepart = array(
    "Info" => "Production",
    "TotalEmployee" => "".$TotalProd."",
    "Percentage" => "".$PercentageDepartmentProd.""
);
array_push($ArrChartDepartment,$TempArrayProdDepart);
# count data chart 2
$TotalEmpPSL = $TotalAdmMalePSL + $TotalEngMalePSL + $TotalProdMalePSL + $TotalAdmFemalePSL + $TotalEngFemalePSL + $TotalProdFemalePSL;
$TotalEmpPSM = $TotalAdmMalePSM + $TotalEngMalePSM + $TotalProdMalePSM +  $TotalAdmFemalePSM + $TotalEngFemalePSM + $TotalProdFemalePSM;
$TotalAllEmp = $TotalEmpPSL + $TotalEmpPSM;
$PercentageTotalEmpPSL = 0;
$PercentageTotalEmpPSM = 0;
if($TotalEmpPSL == "0"){$PercentageTotalEmpPSL = "0";}else{$PercentageTotalEmpPSL = ($TotalEmpPSL/$TotalAllEmp)*100;}
if($TotalEmpPSM == "0"){$PercentageTotalEmpPSM = "0";}else{$PercentageTotalEmpPSM = ($TotalEmpPSM/$TotalAllEmp)*100;}
$PercentageTotalEmpPSL = number_format((float)$PercentageTotalEmpPSL, 2, '.', ',');
$PercentageTotalEmpPSM = number_format((float)$PercentageTotalEmpPSM, 2, '.', ',');
$ArrChartTotalEmp = array();
$TempArrayTotalEmpPSL = array(
    "Info" => "Salatiga",
    "TotalEmployee" => "".$TotalEmpPSL."",
    "Percentage" => "".$PercentageTotalEmpPSL.""
);
array_push($ArrChartTotalEmp,$TempArrayTotalEmpPSL);
$TempArrayTotalEmpPSM = array(
    "Info" => "Semarang",
    "TotalEmployee" => "".$TotalEmpPSM."",
    "Percentage" => "".$PercentageTotalEmpPSM.""
);
array_push($ArrChartTotalEmp,$TempArrayTotalEmpPSM);
# count data chart 3
$TotalGenderMale = $TotalAdmMalePSL + $TotalEngMalePSL + $TotalProdMalePSL + $TotalAdmMalePSM + $TotalEngMalePSM + $TotalProdMalePSM;
$TotalGenderFemale = $TotalAdmFemalePSL + $TotalEngFemalePSL + $TotalProdFemalePSL + $TotalAdmFemalePSM + $TotalEngFemalePSM + $TotalProdFemalePSM;
$TotalGender = $TotalGenderMale + $TotalGenderFemale;
$PercentageGenderMale = 0;
$PercentageGenderFemale = 0;
if($TotalGenderMale == "0"){$PercentageGenderMale = "0";}else{$PercentageGenderMale = ($TotalGenderMale/$TotalGender)*100;}
if($TotalGenderFemale == "0"){$PercentageGenderFemale = "0";}else{$PercentageGenderFemale = ($TotalGenderFemale/$TotalGender)*100;}
$TotalPercentageGender = $PercentageGenderMale + $PercentageGenderFemale;
$TotalPercentageGender = number_format((float)$TotalPercentageGender, 0, '.', ',');
$PercentageGenderMale = number_format((float)$PercentageGenderMale, 2, '.', ',');
$PercentageGenderFemale = number_format((float)$PercentageGenderFemale, 2, '.', ',');
$ArrChartGender = array();
$TempArrayGenderMale = array(
    "Info" => "Male",
    "TotalEmployee" => "".$TotalGenderMale."",
    "Percentage" => "".$PercentageGenderMale.""
);
array_push($ArrChartGender,$TempArrayGenderMale);
$TempArrayGenderFemale = array(
    "Info" => "Female",
    "TotalEmployee" => "".$TotalGenderFemale."",
    "Percentage" => "".$PercentageGenderFemale.""
);
array_push($ArrChartGender,$TempArrayGenderFemale);
$ArrGroupAll = array();
$ArrGroupPSL = array();
$ArrGroupPSM = array();
array_push($ArrGroupAll,array(
    "Total" => $TotalAdm,
    "Group" => "ADMINISTRATION",
    "Salatiga" => $TotalAdmPSL,
    "Semarang" => $TotalAdmPSM
));
array_push($ArrGroupAll,array(
    "Total" => $TotalEng,
    "Group" => "ENGINEERING",
    "Salatiga" => $TotalEngPSL,
    "Semarang" => $TotalEngPSM
));
array_push($ArrGroupAll,array(
    "Total" => $TotalProd,
    "Group" => "PRODUCTION",
    "Salatiga" => $TotalProdPSL,
    "Semarang" => $TotalProdPSM
));
krsort($ArrGroupAll);
array_push($ArrGroupPSL,array(
    "Total" => $TotalAdmGenderPSL,
    "Group" => "ADMINISTRATION",
    "Male" => $TotalAdmMalePSL,
    "Female" => $TotalAdmFemalePSL
));
array_push($ArrGroupPSL,array(
    "Total" => $TotalEngGenderPSL,
    "Group" => "ENGINEERING",
    "Male" => $TotalEngMalePSL,
    "Female" => $TotalEngFemalePSL
));
array_push($ArrGroupPSL,array(
    "Total" => $TotalProdGenderPSL,
    "Group" => "PRODUCTION",
    "Male" => $TotalProdMalePSL,
    "Female" => $TotalProdFemalePSL
));
krsort($ArrGroupPSL);
array_push($ArrGroupPSM,array(
    "Total" => $TotalAdmGenderPSM,
    "Group" => "ADMINISTRATION",
    "Male" => $TotalAdmMalePSM,
    "Female" => $TotalAdmFemalePSM
));
array_push($ArrGroupPSM,array(
    "Total" => $TotalEngGenderPSM,
    "Group" => "ENGINEERING",
    "Male" => $TotalEngMalePSM,
    "Female" => $TotalEngFemalePSM
));
array_push($ArrGroupPSM,array(
    "Total" => $TotalProdGenderPSM,
    "Group" => "PRODUCTION",
    "Male" => $TotalProdMalePSM,
    "Female" => $TotalProdFemalePSM
));
krsort($ArrGroupPSM);
?><script src="project/employee/lib/LibPercentage.js?no=<?php echo base64_encode(date("mdyHis")); ?>"></script>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb breadcrumb-detail">
            <li><a href="home.php">Home</a></li>
            <li class="active"><a href="home.php?link=27">Employee : Employee Statistic</a></li>
        </ol>
    </div>
</div>

<div class="row">
    <div class="col-md-3">
        <div class="table-responsive">
            <table class="table table-bordered table-hover" id="ListTableGroup">
                <thead class="theadCustom">
                    <tr>
                        <th class="text-center">Group</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="PointerListGroup PointerListSelected" data-roles="<?php echo base64_encode(base64_encode("ALL DEPARTMENT")); ?>">
                        <td class="text-left">ALL DEPARTMENT</td>
                    </tr>
                    <tr class="PointerListGroup" data-roles="<?php echo base64_encode(base64_encode("ADMINISTRATION")); ?>">
                        <td class="text-left">ADMINISTRATION</td>
                    </tr>
                    <tr class="PointerListGroup" data-roles="<?php echo base64_encode(base64_encode("ENGINEERING")); ?>">
                        <td class="text-left">ENGINEERING</td>
                    </tr>
                    <tr class="PointerListGroup" data-roles="<?php echo base64_encode(base64_encode("PRODUCTION")); ?>">
                        <td class="text-left">PRODUCTION</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="col-md-9">
        <div class="row" id="ResultData">
            <script type="text/javascript">
                google.charts.load('current', {'packages':['corechart']});
                google.charts.setOnLoadCallback(drawChart1);
                function drawChart1() {
                    var data1 = google.visualization.arrayToDataTable([
                        ['Info', 'TotalEmployee',{type: 'string', role: 'tooltip'}]
                        <?php 
                        foreach ($ArrChartDepartment as $DataChart1)
                        {
                            $ValInfo = $DataChart1['Info'];
                            $ValTotalEmployee = $DataChart1['TotalEmployee'];
                            $ValPercentage = $DataChart1['Percentage'];
                            $ValPercentage = number_format((float)$ValPercentage, 2, '.', ',');
                            echo ",['$ValInfo',".$ValTotalEmployee.",'Total (Employee) : $ValTotalEmployee  ($ValPercentage%)']";
                        }
                        ?>
                    ]);
                    var options = {
                        is3D: true,            
                        chartArea: {top:0,height:"80%",width:"100%",bottom:0},
                        isStacked:true,
                        focusTarget: 'category'
                    };
                    var chart1 = new google.visualization.PieChart(document.getElementById('DataCharts1'));
                    chart1.draw(data1, options);
                }
                google.charts.load('current', {'packages':['corechart']});
                google.charts.setOnLoadCallback(drawChart2);
                function drawChart2() {
                    var data2 = google.visualization.arrayToDataTable([
                        ['Info', 'TotalEmployee',{type: 'string', role: 'tooltip'}]
                        <?php 
                        foreach ($ArrChartTotalEmp as $DataChart2)
                        {
                            $ValInfo = $DataChart2['Info'];
                            $ValTotalEmployee = $DataChart2['TotalEmployee'];
                            $ValPercentage = $DataChart2['Percentage'];
                            $ValPercentage = number_format((float)$ValPercentage, 2, '.', ',');
                            echo ",['$ValInfo',".$ValTotalEmployee.",'Total (Employee) : $ValTotalEmployee  ($ValPercentage%)']";
                        }
                        ?>
                    ]);
                    var options2 = {
                        is3D: true,            
                        chartArea: {top:0,height:"80%",width:"100%",bottom:0},
                        isStacked:true,
                        focusTarget: 'category'
                    };
                    var chart2 = new google.visualization.PieChart(document.getElementById('DataCharts2'));
                    chart2.draw(data2, options2);
                }

                google.charts.load('current', {'packages':['corechart']});
                google.charts.setOnLoadCallback(drawChart3);
                function drawChart3() {
                    var data3 = google.visualization.arrayToDataTable([
                        ['Info', 'TotalEmployee',{type: 'string', role: 'tooltip'}]
                        <?php 
                        foreach ($ArrChartGender as $DataChart3)
                        {
                            $ValInfo = $DataChart3['Info'];
                            $ValTotalEmployee = $DataChart3['TotalEmployee'];
                            $ValPercentage = $DataChart3['Percentage'];
                            $ValPercentage = number_format((float)$ValPercentage, 2, '.', ',');
                            echo ",['$ValInfo',".$ValTotalEmployee.",'Total (Employee) : $ValTotalEmployee  ($ValPercentage%)']";
                        }
                        ?>
                    ]);
                    var options3 = {
                        is3D: true,            
                        chartArea: {top:0,height:"80%",width:"100%",bottom:0},
                        isStacked:true,
                        focusTarget: 'category'
                    };
                    var chart3 = new google.visualization.PieChart(document.getElementById('DataCharts3'));
                    chart3.draw(data3, options3);
                }
            </script>
            <div class="col-md-12"><h5>Group : <strong><?php echo $ValGroup; ?></strong></h5></div>
            <div class="col-md-4"><div id="DataCharts1" style="width: 330px; height: 185px;"></div></div>
            <div class="col-md-4"><div id="DataCharts2" style="width: 330px; height: 185px;"></div></div>
            <div class="col-md-4"><div id="DataCharts3" style="width: 330px; height: 185px;"></div></div>
            <div class="col-md-12">&nbsp;</div>
            <div class="col-md-12">&nbsp;</div>
            <div class="col-md-12"><strong>Formulatrix Indonesia</strong></div>
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="ListTableResultA">
                        <thead class="theadCustom">
                            <tr>
                                <th class="text-center trowCustom">Group</th>
                                <th class="text-center trowCustom" width="100">Salatiga</th>
                                <th class="text-center trowCustom" width="100">Semarang</th>
                                <th class="text-center trowCustom" width="100">Total</th>
                            </tr>
                        </thead>
                        <tbody><?php 
                            $TotalA1 = 0;$TotalA2 = 0;$TotalA3 = 0;
                            foreach($ArrGroupAll as $GroupAll)
                            {
                                $TotalA1 = $TotalA1 + $GroupAll['Salatiga'];
                                $TotalA2 = $TotalA2 + $GroupAll['Semarang'];
                                $TotalA3 = $TotalA3 + $GroupAll['Total'];
                                ?>
                            <tr>
                                <td class="text-left"><?php echo $GroupAll['Group']; ?></td>
                                <td class="text-center"><?php echo $GroupAll['Salatiga']; ?></td>
                                <td class="text-center"><?php echo $GroupAll['Semarang']; ?></td>
                                <td class="text-center"><?php echo $GroupAll['Total']; ?></td>
                            </tr>
                                <?php
                            }
						?></tbody>
                        <tfoot class="theadCustom">
                            <tr>
                                <td class="text-right"><strong>Total</strong></td>
                                <td class="text-center"><strong><?php echo $TotalA1; ?></strong></td>
                                <td class="text-center"><strong><?php echo $TotalA2; ?></strong></td>
                                <td class="text-center"><strong><?php echo $TotalA3; ?></strong></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <div class="col-md-12"><strong>Salatiga Site</strong></div>
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="ListTableResultB">
                        <thead class="theadCustom">
                            <tr>
                                <th class="text-center trowCustom">Salatiga</th>
                                <th class="text-center trowCustom" width="100">Male</th>
                                <th class="text-center trowCustom" width="100">Female</th>
                                <th class="text-center trowCustom" width="100">Total</th>
                            </tr>
                        </thead>
                        <tbody><?php 
                            $TotalB1 = 0;$TotalB2 = 0;$TotalB3 = 0;
                            foreach($ArrGroupPSL as $GroupPSL)
                            {
                                $TotalB1 = $TotalB1 + $GroupPSL['Male'];
                                $TotalB2 = $TotalB2 + $GroupPSL['Female'];
                                $TotalB3 = $TotalB3 + $GroupPSL['Total'];
                                ?>
                            <tr>
                                <td class="text-left"><?php echo $GroupPSL['Group']; ?></td>
                                <td class="text-center"><?php echo $GroupPSL['Male']; ?></td>
                                <td class="text-center"><?php echo $GroupPSL['Female']; ?></td>
                                <td class="text-center"><?php echo $GroupPSL['Total']; ?></td>
                            </tr>
                                <?php
                            }
						?></tbody>
                        <tfoot class="theadCustom">
                            <tr>
                                <td class="text-right"><strong>Total</strong></td>
                                <td class="text-center"><strong><?php echo $TotalB1; ?></strong></td>
                                <td class="text-center"><strong><?php echo $TotalB2; ?></strong></td>
                                <td class="text-center"><strong><?php echo $TotalB3; ?></strong></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <div class="col-md-12"><strong>Semarang Site</strong></div>
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="ListTableResultC">
                        <thead class="theadCustom">
                            <tr>
                                <th class="text-center trowCustom">Semarang</th>
                                <th class="text-center trowCustom" width="100">Male</th>
                                <th class="text-center trowCustom" width="100">Female</th>
                                <th class="text-center trowCustom" width="100">Total</th>
                            </tr>
                        </thead>
                        <tbody><?php 
                            $TotalC1 = 0;$TotalC2 = 0;$TotalC3 = 0;
                            foreach($ArrGroupPSM as $GroupPSM)
                            {
                                $TotalC1 = $TotalC1 + $GroupPSM['Male'];
                                $TotalC2 = $TotalC2 + $GroupPSM['Female'];
                                $TotalC3 = $TotalC3 + $GroupPSM['Total'];
                                ?>
                            <tr>
                                <td class="text-left"><?php echo $GroupPSM['Group']; ?></td>
                                <td class="text-center"><?php echo $GroupPSM['Male']; ?></td>
                                <td class="text-center"><?php echo $GroupPSM['Female']; ?></td>
                                <td class="text-center"><?php echo $GroupPSM['Total']; ?></td>
                            </tr>
                                <?php
                            }
						?></tbody>
                        <tfoot class="theadCustom">
                            <tr>
                                <td class="text-right"><strong>Total</strong></td>
                                <td class="text-center"><strong><?php echo $TotalC1; ?></strong></td>
                                <td class="text-center"><strong><?php echo $TotalC2; ?></strong></td>
                                <td class="text-center"><strong><?php echo $TotalC3; ?></strong></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
        <div class="row" id="DetailData"></div>
    </div>
</div>