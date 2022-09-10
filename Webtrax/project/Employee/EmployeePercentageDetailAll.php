<?php 
session_start();
require_once("../../src/Modules/ModuleLogin.php");
require_once("Modules/ModuleEmployee.php");
date_default_timezone_set("Asia/Jakarta");
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
*/
if($_SERVER['REQUEST_METHOD'] == "POST")
{   
    $ValDivision = htmlspecialchars(trim($_POST['ValDivision']), ENT_QUOTES, "UTF-8");
    $ValLocation = htmlspecialchars(trim($_POST['ValLocation']), ENT_QUOTES, "UTF-8");
    $ValLabel = htmlspecialchars(trim($_POST['ValLabel']), ENT_QUOTES, "UTF-8");
    $ArrayDataEmployee = array();
    $ArrayDataEmployeeSelected = array();
    switch ($ValLabel) {
        case "ADMINISTRATION":
            {
                # list employee
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
                    $ValPSLDetailPosition = trim($RListPSL['DetailPosition']);
                    $ArrayTemp = array(
                        "NIK" => $ValPSLNIK,
                        "FN" => $ValPSLFN,
                        "DivName" => $ValPSLDivName,
                        "ID" => $ValPSLID,
                        "Mobile" => $ValPSLMobile,
                        "Email" => $ValPSLEmail,
                        "GenderID" => $ValPSLGenderID,
                        "DetailPosition" => $ValPSLDetailPosition,
                        "PicPath" => "https://sik.formulatrix.com/FOTOKARYAWAN/".$ValPSLPicPath,
                        "Location" => "PSL"
                    );
                    array_push($ArrayDataEmployee,$ArrayTemp);
                }
                $QListPSM = LIST_EMPLOYEE_SIMPLE_PSM($linkHRISWebTrax);
                while($RListPSM = sqlsrv_fetch_array($QListPSM))
                {
                    $ValPSMNIK = trim($RListPSM['NIK']);
                    $ValPSMFN = trim($RListPSM['FullName']);
                    $ValPSMDivName = trim($RListPSM['DivisionName']);
                    $ValPSMID = trim($RListPSM['IDCard']);
                    $ValPSMMobile = trim($RListPSM['Mobile']);
                    $ValPSMEmail = trim($RListPSM['Email']);
                    $ValPSMGenderID = trim($RListPSM['Gender_ID']);
                    $ValPSMPicPath = trim($RListPSM['PicPath']);
                    $ValPSMDetailPosition = trim($RListPSM['DetailPosition']);
                    $ArrayTemp = array(
                        "NIK" => $ValPSMNIK,
                        "FN" => $ValPSMFN,
                        "DivName" => $ValPSMDivName,
                        "ID" => $ValPSMID,
                        "Mobile" => $ValPSMMobile,
                        "Email" => $ValPSMEmail,
                        "GenderID" => $ValPSMGenderID,
                        "DetailPosition" => $ValPSMDetailPosition,
                        "PicPath" => "https://sik.promanufacture.co.id/sites/FOTOKARYAWAN/".$ValPSMPicPath,
                        "Location" => "PSM"
                    );
                    array_push($ArrayDataEmployee,$ArrayTemp);
                }
                $QListDataEmpDepartment = GET_DATA_PTO_ALL($linkHRISWebTrax);
                while($RListDataEmpDepartment = sqlsrv_fetch_array($QListDataEmpDepartment))
                {
                    $ValEmpName = trim($RListDataEmpDepartment['EmployeeName']);
                    $ValLocation = trim($RListDataEmpDepartment['Location']);
                    foreach ($ArrayDataEmployee as $DataEmployee)
                    {
                        if($ValEmpName == trim($DataEmployee['FN']) && $ValLocation == trim($DataEmployee['Location']) && 
                        trim($RListDataEmpDepartment['Department']) == "Administration" && trim($DataEmployee['DivName']) == $ValDivision)
                        {
                            $ArrTemp = array(
                                "FN" => trim($DataEmployee['FN']),
                                "NIK" => trim($DataEmployee['NIK']),
                                "DivName" => trim($DataEmployee['DivName']),
                                "ID" => trim($DataEmployee['ID']),
                                "Mobile" => trim($DataEmployee['Mobile']),
                                "Email" => trim($DataEmployee['Email']),
                                "GenderID" => trim($DataEmployee['GenderID']),
                                "PicPath" => trim($DataEmployee['PicPath']),
                                "Location" => trim($DataEmployee['Location']),
                                "Department" => trim($RListDataEmpDepartment['Department']),
                                "DetailPosition" => trim($DataEmployee['DetailPosition'])
                            );
                            array_push($ArrayDataEmployeeSelected,$ArrTemp);
                        }
                    }
                }
            }
            break;
        case "ENGINEERING":
            {
                # list employee
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
                    $ValPSLDetailPosition = trim($RListPSL['DetailPosition']);
                    $ArrayTemp = array(
                        "NIK" => $ValPSLNIK,
                        "FN" => $ValPSLFN,
                        "DivName" => $ValPSLDivName,
                        "ID" => $ValPSLID,
                        "Mobile" => $ValPSLMobile,
                        "Email" => $ValPSLEmail,
                        "GenderID" => $ValPSLGenderID,
                        "DetailPosition" => $ValPSLDetailPosition,
                        "PicPath" => "https://sik.formulatrix.com/FOTOKARYAWAN/".$ValPSLPicPath,
                        "Location" => "PSL"
                    );
                    array_push($ArrayDataEmployee,$ArrayTemp);
                }
                $QListPSM = LIST_EMPLOYEE_SIMPLE_PSM($linkHRISWebTrax);
                while($RListPSM = sqlsrv_fetch_array($QListPSM))
                {
                    $ValPSMNIK = trim($RListPSM['NIK']);
                    $ValPSMFN = trim($RListPSM['FullName']);
                    $ValPSMDivName = trim($RListPSM['DivisionName']);
                    $ValPSMID = trim($RListPSM['IDCard']);
                    $ValPSMMobile = trim($RListPSM['Mobile']);
                    $ValPSMEmail = trim($RListPSM['Email']);
                    $ValPSMGenderID = trim($RListPSM['Gender_ID']);
                    $ValPSMPicPath = trim($RListPSM['PicPath']);
                    $ValPSMDetailPosition = trim($RListPSM['DetailPosition']);
                    $ArrayTemp = array(
                        "NIK" => $ValPSMNIK,
                        "FN" => $ValPSMFN,
                        "DivName" => $ValPSMDivName,
                        "ID" => $ValPSMID,
                        "Mobile" => $ValPSMMobile,
                        "Email" => $ValPSMEmail,
                        "GenderID" => $ValPSMGenderID,
                        "DetailPosition" => $ValPSMDetailPosition,
                        "PicPath" => "https://sik.promanufacture.co.id/sites/FOTOKARYAWAN/".$ValPSMPicPath,
                        "Location" => "PSM"
                    );
                    array_push($ArrayDataEmployee,$ArrayTemp);
                }
                $QListDataEmpDepartment = GET_DATA_PTO_ALL($linkHRISWebTrax);
                while($RListDataEmpDepartment = sqlsrv_fetch_array($QListDataEmpDepartment))
                {
                    $ValEmpName = trim($RListDataEmpDepartment['EmployeeName']);
                    $ValLocation = trim($RListDataEmpDepartment['Location']);
                    foreach ($ArrayDataEmployee as $DataEmployee)
                    {
                        if($ValEmpName == trim($DataEmployee['FN']) && $ValLocation == trim($DataEmployee['Location']) && 
                        trim($RListDataEmpDepartment['Department']) == "Engineering" && trim($DataEmployee['DivName']) == $ValDivision)
                        {
                            $ArrTemp = array(
                                "FN" => trim($DataEmployee['FN']),
                                "NIK" => trim($DataEmployee['NIK']),
                                "DivName" => trim($DataEmployee['DivName']),
                                "ID" => trim($DataEmployee['ID']),
                                "Mobile" => trim($DataEmployee['Mobile']),
                                "Email" => trim($DataEmployee['Email']),
                                "GenderID" => trim($DataEmployee['GenderID']),
                                "PicPath" => trim($DataEmployee['PicPath']),
                                "Location" => trim($DataEmployee['Location']),
                                "Department" => trim($RListDataEmpDepartment['Department']),
                                "DetailPosition" => trim($DataEmployee['DetailPosition'])
                            );
                            array_push($ArrayDataEmployeeSelected,$ArrTemp);
                        }
                    }
                }
            }
            break;
        case "PRODUCTION":
            {
                # list employee
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
                    $ValPSLDetailPosition = trim($RListPSL['DetailPosition']);
                    $ArrayTemp = array(
                        "NIK" => $ValPSLNIK,
                        "FN" => $ValPSLFN,
                        "DivName" => $ValPSLDivName,
                        "ID" => $ValPSLID,
                        "Mobile" => $ValPSLMobile,
                        "Email" => $ValPSLEmail,
                        "GenderID" => $ValPSLGenderID,
                        "DetailPosition" => $ValPSLDetailPosition,
                        "PicPath" => "https://sik.formulatrix.com/FOTOKARYAWAN/".$ValPSLPicPath,
                        "Location" => "PSL"
                    );
                    array_push($ArrayDataEmployee,$ArrayTemp);
                }
                $QListPSM = LIST_EMPLOYEE_SIMPLE_PSM($linkHRISWebTrax);
                while($RListPSM = sqlsrv_fetch_array($QListPSM))
                {
                    $ValPSMNIK = trim($RListPSM['NIK']);
                    $ValPSMFN = trim($RListPSM['FullName']);
                    $ValPSMDivName = trim($RListPSM['DivisionName']);
                    $ValPSMID = trim($RListPSM['IDCard']);
                    $ValPSMMobile = trim($RListPSM['Mobile']);
                    $ValPSMEmail = trim($RListPSM['Email']);
                    $ValPSMGenderID = trim($RListPSM['Gender_ID']);
                    $ValPSMPicPath = trim($RListPSM['PicPath']);
                    $ValPSMDetailPosition = trim($RListPSM['DetailPosition']);
                    $ArrayTemp = array(
                        "NIK" => $ValPSMNIK,
                        "FN" => $ValPSMFN,
                        "DivName" => $ValPSMDivName,
                        "ID" => $ValPSMID,
                        "Mobile" => $ValPSMMobile,
                        "Email" => $ValPSMEmail,
                        "GenderID" => $ValPSMGenderID,
                        "DetailPosition" => $ValPSMDetailPosition,
                        "PicPath" => "https://sik.promanufacture.co.id/sites/FOTOKARYAWAN/".$ValPSMPicPath,
                        "Location" => "PSM"
                    );
                    array_push($ArrayDataEmployee,$ArrayTemp);
                }
                $QListDataEmpDepartment = GET_DATA_PTO_ALL($linkHRISWebTrax);
                while($RListDataEmpDepartment = sqlsrv_fetch_array($QListDataEmpDepartment))
                {
                    $ValEmpName = trim($RListDataEmpDepartment['EmployeeName']);
                    $ValLocation = trim($RListDataEmpDepartment['Location']);
                    foreach ($ArrayDataEmployee as $DataEmployee)
                    {
                        if($ValEmpName == trim($DataEmployee['FN']) && $ValLocation == trim($DataEmployee['Location']) && 
                        trim($RListDataEmpDepartment['Department']) == "Production" && trim($DataEmployee['DivName']) == $ValDivision)
                        {
                            $ArrTemp = array(
                                "FN" => trim($DataEmployee['FN']),
                                "NIK" => trim($DataEmployee['NIK']),
                                "DivName" => trim($DataEmployee['DivName']),
                                "ID" => trim($DataEmployee['ID']),
                                "Mobile" => trim($DataEmployee['Mobile']),
                                "Email" => trim($DataEmployee['Email']),
                                "GenderID" => trim($DataEmployee['GenderID']),
                                "PicPath" => trim($DataEmployee['PicPath']),
                                "Location" => trim($DataEmployee['Location']),
                                "Department" => trim($RListDataEmpDepartment['Department']),
                                "DetailPosition" => trim($DataEmployee['DetailPosition'])
                            );
                            array_push($ArrayDataEmployeeSelected,$ArrTemp);
                        }
                    }
                }
            }
            break;
        default:
            echo "";
            break;
    }
    if(count($ArrayDataEmployeeSelected) > 0)
    {
        asort($ArrayDataEmployeeSelected);        
        ?>
        <div class="col-md-12"><strong>Formulatrix Indonesia <?php echo '- '.$ValDivision; ?></strong></div>
        <div class="col-md-12">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="TableDetail">
                    <thead class="theadCustom">
                        <tr>
                            <th class="text-center trowCustom" width="50">No</th>
                            <th class="text-center trowCustom">Name</th>
                            <th class="text-center trowCustom" width="300">Detail Position</th>
                            <th class="text-center trowCustom" width="150">Location</th>
                            <th class="text-center trowCustom" width="150">Phone</th>
                        </tr>
                    </thead>
                    <tbody><?php
                    $NoList = 1;
                    foreach($ArrayDataEmployeeSelected as $DataEmployeeSelected)
                    {
                        $EncPicEmployee = urlencode(trim($DataEmployeeSelected['PicPath']));
                        if(trim($DataEmployeeSelected['Location']) == "PSL"){$ResLocation = "Salatiga";}else{$ResLocation = "Semarang";}
                        ?>
                        <tr class="tablerow" data-dataref="<?php echo $EncPicEmployee; ?>">
                            <td class="text-center"><?php echo $NoList; ?></td>
                            <td class="text-left"><?php echo trim($DataEmployeeSelected['FN']); ?></td>
                            <td class="text-center"><?php echo trim($DataEmployeeSelected['DetailPosition']); ?></td>
                            <td class="text-center"><?php echo $ResLocation; ?></td>
                            <td class="text-center"><?php echo trim($DataEmployeeSelected['Mobile']); ?></td>
                        </tr>
                        <?php
                        $NoList++;
                    }
                    ?></tbody>
                </table>
            </div>
        </div>
        
        <div class="modal fade" id="ModalViewResult2" tabindex="-1" role="dialog" aria-labelledby="Modal-View" aria-hidden="true">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="row">
                        <div class="col-xs-6 text-left"><h5 class="modal-title"><strong>Detail</strong></h5><span></span></div>
                        <div class="col-xs-6 text-right">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="row" id="ContentResultPic2"></div>
                </div>
            </div>
        </div>
        <?php
    }
}
else
{
    echo "";    
}
?>