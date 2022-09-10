<?php  
require_once("project/Employee/Modules/ModuleEmployee.php");
date_default_timezone_set("Asia/Jakarta");
$YearNow = date("Y");
$DateNow = date("m/d/Y");
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

$ArrayDataEmployee = array();
# list employee FI & PSL
$QListPSL = LIST_EMPLOYEE_SIMPLE_PSL($linkHRISWebTrax);
while($RListPSL = mssql_fetch_assoc($QListPSL))
{
    $ValPSLNIK = trim($RListPSL['NIK']);
    $ValPSLFN = trim($RListPSL['FullName']);
    $ValPSLDivName = trim($RListPSL['DivisionName']);
    $ValPSLID = trim($RListPSL['IDCard']);
    $ValPSLMobile = trim($RListPSL['Mobile']);
    $ValPSLAddress = trim($RListPSL['Address']);
    $ArrayTemp = array(
        "NIK" => $ValPSLNIK,
        "FN" => $ValPSLFN,
        "DivName" => $ValPSLDivName,
        "ID" => $ValPSLID,
        "Mobile" => $ValPSLMobile,
        "Address" => $ValPSLAddress,
        "Location" => "PSL"
    );
    array_push($ArrayDataEmployee,$ArrayTemp);
}
# list employee PSM
$QListPSM = LIST_EMPLOYEE_SIMPLE_PSM();
while($RListPSM = mssql_fetch_assoc($QListPSM))
{
    $ValPSMNIK = trim($RListPSM['NIK']);
    $ValPSMFN = trim($RListPSM['FullName']);
    $ValPSMDivName = trim($RListPSM['DivisionName']);
    $ValPSMID = trim($RListPSM['IDCard']);
    $ValPSMMobile = trim($RListPSM['Mobile']);
    $ValPSMAddress = trim($RListPSM['Address']);
    $ArrayTemp = array(
        "NIK" => $ValPSMNIK,
        "FN" => $ValPSMFN,
        "DivName" => $ValPSMDivName,
        "ID" => $ValPSMID,
        "Mobile" => $ValPSMMobile,
        "Address" => $ValPSMAddress,
        "Location" => "PSM"
    );
    array_push($ArrayDataEmployee,$ArrayTemp);
}

?><script src="project/employee/lib/libemployeelist.js?no=<?php echo base64_encode(date("mdyHis")); ?>"></script>
<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb breadcrumb-detail">
            <li><a href="home.php">Home</a></li>
            <li class="active"><a href="home.php?link=24">Employee : Employee List</a></li>
        </ol>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="table-responsive">
            <table id="DataEmployee" class="table table-hover">
                <thead>
                    <tr>
                        <th class="text-center">NO</th>
                        <th class="text-center">NIK</th>
                        <th class="text-center">FullName</th>
                        <th class="text-center">Division</th>
                        <th class="text-center">Location</th>
                        <th class="text-center">No IDCard</th>
                        <th class="text-center">Phone</th>
                        <th class="text-center">Address</th>
                    </tr>
                </thead>
                <tbody><?php
                $No = 1;
                foreach ($ArrayDataEmployee as $DataEmployee)
                {
                    $ValNIK = trim($DataEmployee['NIK']);
                    $ValFN = trim($DataEmployee['FN']);
                    $ValDivision = trim($DataEmployee['DivName']);
                    $ValIDCard = trim($DataEmployee['ID']);
                    $ValMobile = trim($DataEmployee['Mobile']);
                    $ValAddress = trim($DataEmployee['Address']);
                    $ValLocation = trim($DataEmployee['Location']);
                    ?>
                    <tr>
                        <td class="text-center"><?php echo $No; ?></td>
                        <td class="text-left"><?php echo $ValNIK ;?></td>
                        <td class="text-left"><?php echo $ValFN ;?></td>
                        <td class="text-left"><?php echo $ValDivision ;?></td>
                        <td class="text-center"><?php echo $ValLocation ;?></td>
                        <td class="text-left"><?php echo $ValIDCard ;?></td>
                        <td class="text-center"><?php echo $ValMobile ;?></td>
                        <td class="text-left"><?php echo $ValAddress ;?></td>
                    </tr>
                    <?php
                    $No++;
                }
                ?></tbody>
            </table>
        </div>   
    </div>
</div>