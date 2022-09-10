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
    $EncValGroup = htmlspecialchars(trim($_POST['ValGroup']), ENT_QUOTES, "UTF-8");
    $ValGroup = base64_decode(base64_decode($EncValGroup));
    # list employee FI & PSL
    $ArrayDataEmployee = array();
    switch ($ValGroup) {
        case "ALL DEPARTMENT":
            {
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
                $QListPSM = LIST_EMPLOYEE_SIMPLE_PSM($PSMConn);
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
                    $ArrayTemp = array(
                        "NIK" => $ValPSMNIK,
                        "FN" => $ValPSMFN,
                        "DivName" => $ValPSMDivName,
                        "ID" => $ValPSMID,
                        "Mobile" => $ValPSMMobile,
                        "Email" => $ValPSMEmail,
                        "GenderID" => $ValPSMGenderID,
                        "PicPath" => "https://sik.promanufacture.co.id/sites/FOTOKARYAWAN/".$ValPSMPicPath,
                        "Location" => "PSM"
                    );
                    array_push($ArrayDataEmployee,$ArrayTemp);
                }
                $ArrayDataEmployeeSelected = array();
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
                            array_push($ArrayDataEmployeeSelected,$ArrTemp);
                        }
                    }
                }
            }
            break;
        case "ADMINISTRATION":
            {
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
                $QListPSM = LIST_EMPLOYEE_SIMPLE_PSM();
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
                    $ArrayTemp = array(
                        "NIK" => $ValPSMNIK,
                        "FN" => $ValPSMFN,
                        "DivName" => $ValPSMDivName,
                        "ID" => $ValPSMID,
                        "Mobile" => $ValPSMMobile,
                        "Email" => $ValPSMEmail,
                        "GenderID" => $ValPSMGenderID,
                        "PicPath" => "https://sik.promanufacture.co.id/sites/FOTOKARYAWAN/".$ValPSMPicPath,
                        "Location" => "PSM"
                    );
                    array_push($ArrayDataEmployee,$ArrayTemp);
                }
                # list employee by department
                $ArrayDataEmployeeSelected = array();
                $QListDataEmpDepartment = GET_DATA_PTO_ALL($linkHRISWebTrax);
                while($RListDataEmpDepartment = sqlsrv_fetch_array($QListDataEmpDepartment))
                {
                    $ValEmpName = trim($RListDataEmpDepartment['EmployeeName']);
                    $ValLocation = trim($RListDataEmpDepartment['Location']);
                    foreach ($ArrayDataEmployee as $DataEmployee)
                    {
                        if($ValEmpName == trim($DataEmployee['FN']) && $ValLocation == trim($DataEmployee['Location']) && trim($RListDataEmpDepartment['Department']) == "Administration")
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
                            array_push($ArrayDataEmployeeSelected,$ArrTemp);
                        }
                    }
                }
            }
            break;    
        case "ENGINEERING":
            {
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
                $QListPSM = LIST_EMPLOYEE_SIMPLE_PSM($PSMConn);
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
                    $ArrayTemp = array(
                        "NIK" => $ValPSMNIK,
                        "FN" => $ValPSMFN,
                        "DivName" => $ValPSMDivName,
                        "ID" => $ValPSMID,
                        "Mobile" => $ValPSMMobile,
                        "Email" => $ValPSMEmail,
                        "GenderID" => $ValPSMGenderID,
                        "PicPath" => "https://sik.promanufacture.co.id/sites/FOTOKARYAWAN/".$ValPSMPicPath,
                        "Location" => "PSM"
                    );
                    array_push($ArrayDataEmployee,$ArrayTemp);
                }
                # list employee by department
                $ArrayDataEmployeeSelected = array();
                $QListDataEmpDepartment = GET_DATA_PTO_ALL($linkHRISWebTrax);
                while($RListDataEmpDepartment = sqlsrv_fetch_array($QListDataEmpDepartment))
                {
                    $ValEmpName = trim($RListDataEmpDepartment['EmployeeName']);
                    $ValLocation = trim($RListDataEmpDepartment['Location']);
                    foreach ($ArrayDataEmployee as $DataEmployee)
                    {
                        if($ValEmpName == trim($DataEmployee['FN']) && $ValLocation == trim($DataEmployee['Location']) && trim($RListDataEmpDepartment['Department']) == "Engineering")
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
                            array_push($ArrayDataEmployeeSelected,$ArrTemp);
                        }
                    }
                }
            }
            break;
        case "PRODUCTION":
            {
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
                $QListPSM = LIST_EMPLOYEE_SIMPLE_PSM($PSMConn);
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
                    $ArrayTemp = array(
                        "NIK" => $ValPSMNIK,
                        "FN" => $ValPSMFN,
                        "DivName" => $ValPSMDivName,
                        "ID" => $ValPSMID,
                        "Mobile" => $ValPSMMobile,
                        "Email" => $ValPSMEmail,
                        "GenderID" => $ValPSMGenderID,
                        "PicPath" => "https://sik.promanufacture.co.id/sites/FOTOKARYAWAN/".$ValPSMPicPath,
                        "Location" => "PSM"
                    );
                    array_push($ArrayDataEmployee,$ArrayTemp);
                }
                # list employee by department
                $ArrayDataEmployeeSelected = array();
                $QListDataEmpDepartment = GET_DATA_PTO_ALL($linkHRISWebTrax);
                while($RListDataEmpDepartment = sqlsrv_fetch_array($QListDataEmpDepartment))
                {
                    $ValEmpName = trim($RListDataEmpDepartment['EmployeeName']);
                    $ValLocation = trim($RListDataEmpDepartment['Location']);
                    foreach ($ArrayDataEmployee as $DataEmployee)
                    {
                        if($ValEmpName == trim($DataEmployee['FN']) && 
                        $ValLocation == trim($DataEmployee['Location']) && 
                        trim($RListDataEmpDepartment['Department']) == "Production")
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
    if(count($ArrayDataEmployee) > 0)
    {
        if($ValGroup == "ALL DEPARTMENT")
        {
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
            foreach($ArrayDataEmployeeSelected as $DataEmpDepart)
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
            ?>
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
            <?php
        }
        else
        {
            # get data division
            $ArrListDivision = array();
            $ArrListLocation = array();
            $ArrListGender = array();
            foreach ($ArrayDataEmployeeSelected as $DataEmployeeSelected)
            {
                $BolCheckDivision = TRUE;
                $BolCheckLocation = TRUE;
                $BolCheckGender = TRUE;
                foreach($ArrListDivision as $ListDivision)
                {
                    if($ListDivision['Division'] == trim($DataEmployeeSelected['DivName']))
                    {
                        $BolCheckDivision = FALSE;
                    }
                }
                if($BolCheckDivision == TRUE)
                {
                    $TempArray = array(
                        "Division" => trim($DataEmployeeSelected['DivName'])
                    );
                    array_push($ArrListDivision,$TempArray);
                }
                foreach ($ArrListLocation as $ListLocation)
                {
                    if($ListLocation['Location'] == trim($DataEmployeeSelected['Location']))
                    {
                        $BolCheckLocation = FALSE;
                    }
                }
                if($BolCheckLocation == TRUE)
                {
                    $TempArray = array(
                        "Location" => trim($DataEmployeeSelected['Location'])
                    );
                    array_push($ArrListLocation,$TempArray);
                }
                foreach ($ArrListGender as $ListGender)
                {
                    if($ListGender['GenderID'] == trim($DataEmployeeSelected['GenderID']))
                    {
                        $BolCheckGender = FALSE;
                    }
                }
                if($BolCheckGender == TRUE)
                {
                    $TempArray = array(
                        "GenderID" => trim($DataEmployeeSelected['GenderID'])
                    );
                    array_push($ArrListGender,$TempArray);
                }

            }
            asort($ArrListLocation);
            # get array by division
            $ArrListTotalEmployeePerDiv = array();
            foreach($ArrListDivision as $ListDivision)
            {
                $NoCount = 0;
                $TotalEmployee = 0;
                $TempNameDiv = "";
                foreach($ArrayDataEmployeeSelected as $DataEmployee)
                {
                    if(trim($DataEmployee['DivName']) == trim($ListDivision['Division']))
                    {
                        $NoCount = $NoCount + 1;
                        $TempNameDiv = trim($DataEmployee['DivName']);
                    }
                    $TotalEmployee = $TotalEmployee + 1;
                }
                $ArrayTemp = array(
                    "Division" => $TempNameDiv,
                    "TotalEmployee" => $NoCount,
                    "TotalAllEmployee" => $TotalEmployee
                );
                array_push($ArrListTotalEmployeePerDiv,$ArrayTemp);
            }
            $ArrChartDivision = array();
            foreach($ArrListTotalEmployeePerDiv as $DataListEmpPerDiv)
            {
                $PercentageListEmpPerDiv = 0;
                if(trim($DataListEmpPerDiv['TotalEmployee']) == "0"){$PercentageListEmpPerDiv = "0";}else{$PercentageListEmpPerDiv = (trim($DataListEmpPerDiv['TotalEmployee'])/trim($DataListEmpPerDiv['TotalAllEmployee']))*100;}
                $PercentageListEmpPerDiv = number_format((float)$PercentageListEmpPerDiv, 2, '.', ',');
                $TempArray = array(
                    "Info" => trim($DataListEmpPerDiv['Division']),
                    "TotalEmployee" => trim($DataListEmpPerDiv['TotalEmployee']),
                    "Percentage" => "".$PercentageListEmpPerDiv.""
                );
                array_push($ArrChartDivision,$TempArray);
            }
            # get array by company
            $ArrListTotalEmployeePerLocation = array();
            foreach($ArrListLocation as $ListLocation)
            {
                $NoCount = 0;
                $TotalEmployee = 0;
                $TempNameLoc = "";
                foreach ($ArrayDataEmployeeSelected as $DataEmployee)
                {
                    if(trim($DataEmployee['Location']) == trim($ListLocation['Location']))
                    {
                        $NoCount = $NoCount + 1;
                        $TempNameLoc = trim($DataEmployee['Location']);
                    }
                    $TotalEmployee = $TotalEmployee + 1;
                }
                $ArrayTemp = array(
                    "Location" => $TempNameLoc,
                    "TotalEmployee" => $NoCount,
                    "TotalAllEmployee" => $TotalEmployee
                );
                array_push($ArrListTotalEmployeePerLocation,$ArrayTemp);
            }
            $ArrChartTotalEmp = array();
            foreach($ArrListTotalEmployeePerLocation as $DataListEmpPerLocation)
            {
                $PercentageListEmpPerLocation = 0;
                if(trim($DataListEmpPerLocation['TotalEmployee']) == "0"){$PercentageListEmpPerLocation = "0";}else{$PercentageListEmpPerLocation = (trim($DataListEmpPerLocation['TotalEmployee'])/trim($DataListEmpPerLocation['TotalAllEmployee']))*100;}
                $PercentageListEmpPerLocation = number_format((float)$PercentageListEmpPerLocation, 2, '.', ',');
                if(trim($DataListEmpPerLocation['Location']) == "PSL"){$VarLoc = "Salatiga";}else{$VarLoc = "Semarang";}
                $TempArray = array(
                    "Info" => $VarLoc,
                    "TotalEmployee" => trim($DataListEmpPerLocation['TotalEmployee']),
                    "Percentage" => "".$PercentageListEmpPerLocation.""
                );
                array_push($ArrChartTotalEmp,$TempArray);
            }
            # get array by gender
            $ArrListTotalEmployeePerGender = array();
            foreach($ArrListGender as $ListGender)
            {
                $NoCount = 0;
                $TotalEmployee = 0;
                $TempGenderID = "";
                foreach ($ArrayDataEmployeeSelected as $DataEmployee)
                {
                    if(trim($DataEmployee['GenderID']) == trim($ListGender['GenderID']))
                    {
                        $NoCount = $NoCount + 1;
                        $TempGenderID = trim($DataEmployee['GenderID']);
                    }
                    $TotalEmployee = $TotalEmployee + 1;
                }
                $ArrayTemp = array(
                    "GenderID" => $TempGenderID,
                    "TotalEmployee" => $NoCount,
                    "TotalAllEmployee" => $TotalEmployee
                );
                array_push($ArrListTotalEmployeePerGender,$ArrayTemp);
            }
            $ArrChartGender = array();
            foreach($ArrListTotalEmployeePerGender as $DataListEmpPerGender)
            {
                $PercentageListEmpPerGender = 0;
                if(trim($DataListEmpPerGender['TotalEmployee']) == "0"){$PercentageListEmpPerGender = "0";}else{$PercentageListEmpPerGender = (trim($DataListEmpPerGender['TotalEmployee'])/trim($DataListEmpPerGender['TotalAllEmployee']))*100;}
                $PercentageListEmpPerGender = number_format((float)$PercentageListEmpPerGender, 2, '.', ',');                
                if(trim($DataListEmpPerGender['GenderID']) == "1"){$ValGender = "Male";}else{$ValGender = "Female";}
                $TempArray = array(
                    "Info" => $ValGender,
                    "TotalEmployee" => trim($DataListEmpPerGender['TotalEmployee']),
                    "Percentage" => "".$PercentageListEmpPerGender.""
                );
                array_push($ArrChartGender,$TempArray);
            }

            ?>
            <script type="text/javascript">
                google.charts.load('current', {'packages':['corechart']});
                google.charts.setOnLoadCallback(drawChart1);
                function drawChart1() {
                    var data1 = google.visualization.arrayToDataTable([
                        ['Info', 'TotalEmployee',{type: 'string', role: 'tooltip'}]
                        <?php 
                        foreach ($ArrChartDivision as $DataChart1)
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
            <div class="col-md-12"><h5>Group : <span id="GroupLabel"><strong><?php echo $ValGroup; ?></strong></span></h5></div>
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
                                <th class="text-center trowCustom">Division</th>
                                <th class="text-center trowCustom" width="100">Salatiga</th>
                                <th class="text-center trowCustom" width="100">Semarang</th>
                                <th class="text-center trowCustom" width="100">Total</th>
                            </tr>
                        </thead>
                        <tbody><?php 
                        asort($ArrListDivision);
                        $ArrValArrayT1 = array();
                        $ArrValResTable1 = array();
                        $TotalA1 = 0;$TotalA2 = 0;$TotalA3 = 0;
                        foreach($ArrListDivision as $ListDivision)
                        {
                            $ArrValArrayT2 = array();
                            $ValDivision = trim($ListDivision['Division']);
                            array($ArrValArrayT2['Division'] = $ValDivision);
                            foreach($ArrListLocation as $ListLocation)
                            {
                                $ValLocation = $ListLocation['Location'];
                                $ValTotalPerLocation = 0;
                                foreach ($ArrayDataEmployeeSelected as $DataEmp)
                                {
                                    if($DataEmp['Location'] == $ValLocation && $DataEmp['DivName'] == $ValDivision)
                                    {
                                        $ValTotalPerLocation = $ValTotalPerLocation + 1;
                                    }
                                }
                                array($ArrValArrayT2[$ValLocation] = $ValTotalPerLocation);
                            }
                            # total data
                            foreach ($ArrListTotalEmployeePerDiv as $ListTotalEmployeePerDiv)
                            {
                                if($ListTotalEmployeePerDiv['Division'] == $ValDivision)
                                {
                                    array($ArrValArrayT2['Total'] = $ListTotalEmployeePerDiv['TotalEmployee']);
                                }
                            }
                            array_push($ArrValArrayT1,$ArrValArrayT2);
                        }  
                        $IdxColumnNames = array_keys($ArrValArrayT1[0]);
                        foreach($ArrValArrayT1 as $ValArrayT1)
                        {
                            $TempArray = array(
                                $IdxColumnNames[3] => trim($ValArrayT1["".$IdxColumnNames[3].""]),
                                $IdxColumnNames[1] => trim($ValArrayT1["".$IdxColumnNames[1].""]),
                                $IdxColumnNames[2] => trim($ValArrayT1["".$IdxColumnNames[2].""]),
                                $IdxColumnNames[0] => trim($ValArrayT1["".$IdxColumnNames[0].""]),
                            );
                            array_push($ArrValResTable1,$TempArray);
                        }
                        rsort($ArrValResTable1);
                        $IdxColumnNames1 = array_keys($ArrValResTable1[0]);
                        foreach($ArrValResTable1 as $ValResTable1)
                        {
                            $TotalA1 = $TotalA1 + $ValResTable1[$IdxColumnNames1[1]];
                            $TotalA2 = $TotalA2 + $ValResTable1[$IdxColumnNames1[2]];
                            $TotalA3 = $TotalA3 + $ValResTable1[$IdxColumnNames1[0]];
                            echo '<tr class="DataRowAll">';
                            echo '<td class="text-left">'.$ValResTable1[$IdxColumnNames1[3]].'</td>';
                            echo '<td class="text-center">'.$ValResTable1[$IdxColumnNames1[1]].'</td>';
                            echo '<td class="text-center">'.$ValResTable1[$IdxColumnNames1[2]].'</td>';
                            echo '<td class="text-center">'.$ValResTable1[$IdxColumnNames1[0]].'</td>';
                            echo '</tr>';
                        }
                        ?>
                        </tbody>
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
                        $ArrResTable2 = array();
                        $TotalB1 = 0;$TotalB2 = 0;$TotalB3 = 0;
                        foreach($ArrListDivision as $ListDivision)
                        {
                            $ValDivision = trim($ListDivision['Division']);
                            $TotalGenderPSL = 0;
                            $TotalGenderMalePSL = 0;
                            $TotalGenderFemalePSL = 0;
                            foreach ($ArrayDataEmployeeSelected as $DataEmp)
                            {
                                if($DataEmp['Location'] == "PSL" && $DataEmp['DivName'] == $ValDivision)
                                {
                                    if($DataEmp['GenderID'] == "1")
                                    {
                                        $TotalGenderMalePSL = $TotalGenderMalePSL + 1;
                                    }
                                    if($DataEmp['GenderID'] == "2")
                                    {
                                        $TotalGenderFemalePSL = $TotalGenderFemalePSL + 1;
                                    }
                                }
                            }
                            $TotalGenderPSL = $TotalGenderMalePSL + $TotalGenderFemalePSL;
                            $TempArray = array(
                                "Total" => $TotalGenderPSL,
                                "Division" => $ValDivision,
                                "Male" => $TotalGenderMalePSL,
                                "Female" => $TotalGenderFemalePSL
                            );
                            array_push($ArrResTable2,$TempArray);
                        }
                        rsort($ArrResTable2);
                        foreach($ArrResTable2 as $ResTable2)
                        {
                            $TotalB1 = $TotalB1 + trim($ResTable2['Male']);
                            $TotalB2 = $TotalB2 + trim($ResTable2['Female']);
                            $TotalB3 = $TotalB3 + trim($ResTable2['Total']);
                            echo '<tr class="DataRowSalatiga">';
                            echo '<td class="text-left">'.trim($ResTable2['Division']).'</td>';
                            echo '<td class="text-center">'.trim($ResTable2['Male']).'</td>';
                            echo '<td class="text-center">'.trim($ResTable2['Female']).'</td>';
                            echo '<td class="text-center">'.trim($ResTable2['Total']).'</td>';
                            echo '</tr>';
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
                        $ArrResTable3 = array();
                        $TotalC1 = 0;$TotalC2 = 0;$TotalC3 = 0;
                        foreach($ArrListDivision as $ListDivision)
                        {
                            $ValDivision = trim($ListDivision['Division']);
                            $TotalGenderPSM = 0;
                            $TotalGenderMalePSM = 0;
                            $TotalGenderFemalePSM = 0;
                            foreach ($ArrayDataEmployeeSelected as $DataEmp)
                            {
                                if($DataEmp['Location'] == "PSM" && $DataEmp['DivName'] == $ValDivision)
                                {
                                    if($DataEmp['GenderID'] == "1")
                                    {
                                        $TotalGenderMalePSM = $TotalGenderMalePSM + 1;
                                    }
                                    if($DataEmp['GenderID'] == "2")
                                    {
                                        $TotalGenderFemalePSM = $TotalGenderFemalePSM + 1;
                                    }
                                }
                            }
                            $TotalGenderPSM = $TotalGenderMalePSM + $TotalGenderFemalePSM;
                            $TempArray = array(
                                "Total" => $TotalGenderPSM,
                                "Division" => $ValDivision,
                                "Male" => $TotalGenderMalePSM,
                                "Female" => $TotalGenderFemalePSM
                            );
                            array_push($ArrResTable3,$TempArray);
                        }
                        rsort($ArrResTable3);
                        foreach($ArrResTable3 as $ResTable3)
                        {
                            $TotalC1 = $TotalC1 + trim($ResTable3['Male']);
                            $TotalC2 = $TotalC2 + trim($ResTable3['Female']);
                            $TotalC3 = $TotalC3 + trim($ResTable3['Total']);
                            echo '<tr class="DataRowSemarang">';
                            echo '<td class="text-left">'.trim($ResTable3['Division']).'</td>';
                            echo '<td class="text-center">'.trim($ResTable3['Male']).'</td>';
                            echo '<td class="text-center">'.trim($ResTable3['Female']).'</td>';
                            echo '<td class="text-center">'.trim($ResTable3['Total']).'</td>';
                            echo '</tr>';
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
            <?php
        }
    }
    else
    {
        echo "";
    }
}
else
{
    echo "";    
}
?>