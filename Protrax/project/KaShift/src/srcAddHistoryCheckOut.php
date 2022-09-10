<?php
session_start();
require_once("../../../ConfigDB2.php");
require_once("../../../src/Modules/ModuleLogin.php");
require_once("../Modules/ModuleCheckInOutStatus.php");

date_default_timezone_set("Asia/Jakarta");
$Time = date("Y-m-d H:i:s");
 
if(!session_is_registered("UIDProTrax"))
{
    ?>
    <script language="javascript">
        window.location.replace("https://protrax.formulatrix.com/");
    </script>
    <?php
    exit();
}

# data session
$FullName = strtoupper(base64_decode($_SESSION['FullNameUserProTrax']));
$UserNameSession = base64_decode(base64_decode($_SESSION['UIDProTrax']));
# data karyawan
$QData = GET_DETAIL_EMPLOYEE_BY_NAME_CHECKIN($FullName,$LinkPSL);
$RData = mssql_fetch_assoc($QData);
$CompanyCode = trim($RData['CompanyCode']);
$IdxEmployee = trim($RData['Idx']);
$NIKEmployee = trim($RData['NIK']);
$FNEmployee = trim($RData['FullName']);

if($_SERVER['REQUEST_METHOD'] == "POST")
{
    $LocationInput = htmlspecialchars(trim($_POST['LocationInput']), ENT_QUOTES, "UTF-8");
    $AreaInput = htmlspecialchars(trim($_POST['AreaInput']), ENT_QUOTES, "UTF-8");
    $TypeBarcodeInput = htmlspecialchars(trim($_POST['TypeBarcodeInput']), ENT_QUOTES, "UTF-8");
    $BarcodeInput = htmlspecialchars(trim($_POST['BarcodeInput']), ENT_QUOTES, "UTF-8");

    if(strtoupper($TypeBarcodeInput) == "MATERIAL")
    {
        if($AreaInput == "MACHINING")
        {
            # check barcode valid
            $BolCheckValid = FALSE;
            // if($LocationInput != "PSL") # lokasi selain PSL
            // {
            //     $QDetailMaterialID = GET_DETAIL_BARCODE_MATERIAL_BY_ID($BarcodeInput,$LinkPSM);
            // }
            // else
            // {
                $QDetailMaterialID = GET_DETAIL_BARCODE_MATERIAL_BY_ID($BarcodeInput,$LinkPSL);
            // }
            if(mssql_num_rows($QDetailMaterialID) != "0")
            {
                $BolCheckValid = TRUE;
            }
            if($BolCheckValid == FALSE)
            {
                echo "1";
                exit();
            }
            # check date check by part
            // if($LocationInput != "PSL") # lokasi selain PSL
            // {
            //     $QListPart = GET_LIST_PART_BY_MATERIAL_ID($BarcodeInput,$LinkPSM);
            //     $TotalUpdatePart = 0;
            //     $ArrPartList = array();
            //     $ArrPartList2 = array();
            //     while($RListPart = mssql_fetch_assoc($QListPart))
            //     {
            //         $ValBCMaterial = trim($RListPart['BCMaterial']);
            //         $ValBCPart = trim($RListPart['PartBarcode']);
            //         $ValCheckInDate = trim($RListPart['CheckInDate']);
            //         $ValCheckOutDate = trim($RListPart['CheckOutDate']);
            //         $TempArray = array(
            //             "BCMaterial" => $ValBCMaterial,
            //             "BCPart" => $ValBCPart,
            //             "CheckInDate" => $ValCheckInDate,
            //             "CheckOutDate" => $ValCheckOutDate,
            //         );
            //         array_push($ArrPartList,$TempArray);
            //     }
            //     $BolCheckSync = TRUE;
            //     # check sinkron part
            //     foreach ($ArrPartList as $PartList)
            //     {
            //         $ValBCMaterial = trim($PartList['BCMaterial']);
            //         $ValBCPart = trim($PartList['BCPart']);
            //         $ValCheckInDate = trim($PartList['CheckInDate']);
            //         $ValCheckOutDate = trim($PartList['CheckOutDate']);
            //         # check sync
            //         $NewBCPart = GET_NEW_BARCODE_PART_PSL($ValBCPart,$LinkPSL);
            //         if(trim($NewBCPart) == "")
            //         {
            //             $BolCheckSync = FALSE;
            //             break;
            //         }
            //         else
            //         {
            //             $QDataMainBC = GET_CHECKIN_DATE_MAIN_BY_BARCODE($NewBCPart,$LinkPSL);
            //             $RDataMainBC = mssql_fetch_assoc($QDataMainBC);
            //             $ValCheckInDate2 = trim($RDataMainBC['CheckInDate']);
            //             $ValCheckOutDate2 = trim($RDataMainBC['CheckOutDate']);
            //             $TempArray = array(
            //                 "BCMaterial" => $ValBCMaterial,
            //                 "BCPart" => $ValBCPart,
            //                 "CheckInDate" => $ValCheckInDate,
            //                 "CheckOutDate" => $ValCheckOutDate,
            //                 "CheckInDate2" => $ValCheckInDate2,
            //                 "CheckOutDate2" => $ValCheckOutDate2,
            //                 "NewBCPart" => $NewBCPart
            //             );
            //             array_push($ArrPartList2,$TempArray);
            //         }
            //     }
            //     if($BolCheckSync == FALSE)  
            //     {
            //         echo "4";
            //         exit();
            //     }
            //     $BolCheckSync2 = TRUE;
            //     foreach ($ArrPartList2 as $PartList)
            //     {
            //         $ValBCMaterial = trim($PartList['BCMaterial']);
            //         $ValBCPart = trim($PartList['BCPart']);
            //         $ValCheckInDate = trim($PartList['CheckInDate']);
            //         $ValCheckOutDate = trim($PartList['CheckOutDate']);
            //         $ValNewBCPart = trim($PartList['NewBCPart']);
            //         $ValCheckInDate2 = trim($PartList['CheckInDate2']);
            //         $ValCheckOutDate2 = trim($PartList['CheckOutDate2']);
            //         if($ValCheckInDate == "")
            //         {
            //             $BolCheckSync2 = FALSE;
            //             break;
            //         }
            //     }
            //     if($BolCheckSync2 == FALSE)
            //     {
            //         # kondisi data part ada yg blm dicheck in
            //         echo "6";
            //         exit();
            //     }
            //     foreach ($ArrPartList2 as $PartList)
            //     {
            //         $ValBCMaterial = trim($PartList['BCMaterial']);
            //         $ValBCPart = trim($PartList['BCPart']);
            //         $ValCheckInDate = trim($PartList['CheckInDate']);
            //         $ValCheckOutDate = trim($PartList['CheckOutDate']);
            //         $ValNewBCPart = trim($PartList['NewBCPart']);
            //         $ValCheckInDate2 = trim($PartList['CheckInDate2']);
            //         $ValCheckOutDate2 = trim($PartList['CheckOutDate2']);

            //         if($ValCheckOutDate == "")   # check date check in
            //         {
            //             # update machining check in date
            //             UPDATE_DATE_CHECKOUT_PART($ValBCPart,$Time,$LinkPSM);
            //             # insert history checkinout
            //             INSERT_START_LOG_HISTORY_CHECKINOUT_2($NIKEmployee,$FNEmployee,$Time,"OUT",$ValNewBCPart,$AreaInput,"PSM",$ValBCPart,$LinkPSL);
            //             # insert history checkinout local
            //             INSERT_LOG_HISTORY_CHECKINOUT_PSM($NIKEmployee,$FNEmployee,$Time,"OUT",$ValBCPart,$AreaInput,$LinkPSM);
            //             $TotalUpdatePart++;
            //         }
            //         # check date in psl
            //         if($ValCheckOutDate2 == "")
            //         {
            //             # update machining check in date
            //             UPDATE_DATE_CHECKOUT_PART($ValNewBCPart,$Time,$LinkPSL);
            //         }
            //     }
            //     $ArrPartList = array();
            //     $ArrPartList2 = array();
            //     if($TotalUpdatePart == 0 && $TotalPartCheckedBefore == 0)
            //     {
            //         # kondisi data part kosong
            //         echo "2";
            //         exit();
            //     }
            //     else
            //     {
            //         if($TotalUpdatePart == 0 && $TotalPartCheckedBefore != 0)
            //         {
            //             # kondisi part sudah pernah di check sebelumnya
            //             echo "3";
            //             exit();
            //         }
            //         else
            //         {
            //             # kondisi berhasil
            //             echo "OK";
            //             exit();
            //         }
            //     }
            // }
            // else
            // {
                $QListPart = GET_LIST_PART_BY_MATERIAL_ID($BarcodeInput,$LinkPSL);
                $ArrPartList = array();
                $TotalUpdatePart = 0;
                $TotalPartCheckedBefore = 0;
                while($RListPart = mssql_fetch_assoc($QListPart))
                {
                    $ValBCMaterial = trim($RListPart['BCMaterial']);
                    $ValBCPart = trim($RListPart['PartBarcode']);
                    $ValCheckInDate = trim($RListPart['CheckInDate']);
                    $ValCheckOutDate = trim($RListPart['CheckOutDate']);
                    $TempArray = array(
                        "BCMaterial" => $ValBCMaterial,
                        "BCPart" => $ValBCPart,
                        "CheckInDate" => $ValCheckInDate,
                        "CheckOutDate" => $ValCheckOutDate,
                    );
                    array_push($ArrPartList,$TempArray);
                }

                $BolCheckSync = TRUE;
                # check sinkron part
                foreach ($ArrPartList as $PartList)
                {
                    $ValBCMaterial = trim($PartList['BCMaterial']);
                    $ValBCPart = trim($PartList['BCPart']);
                    $ValCheckInDate = trim($PartList['CheckInDate']);
                    $ValCheckOutDate = trim($PartList['CheckOutDate']);
                    if($ValCheckInDate == "")
                    {
                        $BolCheckSync = FALSE;
                        break;
                    }
                }
                if($BolCheckSync == FALSE)
                {
                    # kondisi data part ada yg blm dicheck in
                    echo "6";
                    exit();
                }
                foreach ($ArrPartList as $PartList)
                {
                    $ValBCMaterial = trim($PartList['BCMaterial']);
                    $ValBCPart = trim($PartList['BCPart']);
                    $ValCheckInDate = trim($PartList['CheckInDate']);
                    $ValCheckOutDate = trim($PartList['CheckOutDate']);
                    if($ValCheckOutDate == "")   # check date check in
                    {
                        # update machining check in date
                        UPDATE_DATE_CHECKOUT_PART($ValBCPart,$Time,$LinkPSL);
                        # insert history checkinout
                        INSERT_START_LOG_HISTORY_CHECKINOUT($NIKEmployee,$FNEmployee,$Time,"OUT",$ValBCPart,$AreaInput,$LocationInput,$UserNameSession,$LinkPSL);
                        $TotalUpdatePart++;
                    }
                    else
                    {
                        $TotalPartCheckedBefore++;
                    }
                }
                if($TotalUpdatePart == 0 && $TotalPartCheckedBefore == 0)
                {
                    # kondisi data part kosong
                    echo "2";
                    exit();
                }
                else
                {
                    if($TotalUpdatePart == 0 && $TotalPartCheckedBefore != 0)
                    {
                        # kondisi part sudah pernah di check sebelumnya
                        // echo "3";
                        // exit();
                        foreach ($ArrPartList as $PartList)
                        {
                            $ValBCMaterial = trim($PartList['BCMaterial']);
                            $ValBCPart = trim($PartList['BCPart']);
                            $ValCheckInDate = trim($PartList['CheckInDate']);
                            $ValCheckOutDate = trim($PartList['CheckOutDate']);
                            # insert history checkinout
                            INSERT_START_LOG_HISTORY_CHECKINOUT($NIKEmployee,$FNEmployee,$Time,"OUT",$ValBCPart,$AreaInput,$LocationInput,$UserNameSession,$LinkPSL);
                        }
                        # kondisi berhasil
                        echo "OK";
                        exit();
                    }
                    else
                    {
                        # kondisi berhasil
                        echo "OK";
                        exit();
                    }
                }
            // }
        }
        if($AreaInput == "FABRICATION")
        {
            # check barcode valid
            $BolCheckValid = FALSE;
            // if($LocationInput != "PSL") # lokasi selain PSL
            // {
            //     $QDetailMaterialID = GET_DETAIL_BARCODE_MATERIAL_BY_ID($BarcodeInput,$LinkPSM);
            // }
            // else
            // {
                $QDetailMaterialID = GET_DETAIL_BARCODE_MATERIAL_BY_ID($BarcodeInput,$LinkPSL);
            // }
            if(mssql_num_rows($QDetailMaterialID) != "0")
            {
                $BolCheckValid = TRUE;
            }
            if($BolCheckValid == FALSE)
            {
                echo "1";
                exit();
            }
            # check date check by part
            // if($LocationInput != "PSL") # lokasi selain PSL
            // {
            //     $QListPart = GET_LIST_PART_BY_MATERIAL_ID($BarcodeInput,$LinkPSM);
            //     $TotalUpdatePart = 0;
            //     $ArrPartList = array();
            //     $ArrPartList2 = array();
            //     while($RListPart = mssql_fetch_assoc($QListPart))
            //     {
            //         $ValBCMaterial = trim($RListPart['BCMaterial']);
            //         $ValBCPart = trim($RListPart['PartBarcode']);
            //         $ValCheckInDate = trim($RListPart['FabCheckInDate']);
            //         $ValCheckOutDate = trim($RListPart['FabCheckOutDate']);
            //         $TempArray = array(
            //             "BCMaterial" => $ValBCMaterial,
            //             "BCPart" => $ValBCPart,
            //             "CheckInDate" => $ValCheckInDate,
            //             "CheckOutDate" => $ValCheckOutDate,
            //         );
            //         array_push($ArrPartList,$TempArray);
            //     }
            //     $BolCheckSync = TRUE;
            //     # check sinkron part
            //     foreach ($ArrPartList as $PartList)
            //     {
            //         $ValBCMaterial = trim($PartList['BCMaterial']);
            //         $ValBCPart = trim($PartList['BCPart']);
            //         $ValCheckInDate = trim($PartList['CheckInDate']);
            //         $ValCheckOutDate = trim($PartList['CheckOutDate']);
            //         # check sync
            //         $NewBCPart = GET_NEW_BARCODE_PART_PSL($ValBCPart,$LinkPSL);
            //         if(trim($NewBCPart) == "")
            //         {
            //             $BolCheckSync = FALSE;
            //             break;
            //         }
            //         else
            //         {
            //             $QDataMainBC = GET_CHECKIN_DATE_FABRICATION_MAIN_BY_BARCODE($NewBCPart,$LinkPSL);
            //             $RDataMainBC = mssql_fetch_assoc($QDataMainBC);
            //             $ValCheckInDate2 = trim($RDataMainBC['CheckInDate']);
            //             $ValCheckOutDate2 = trim($RDataMainBC['CheckOutDate']);
            //             $TempArray = array(
            //                 "BCMaterial" => $ValBCMaterial,
            //                 "BCPart" => $ValBCPart,
            //                 "CheckInDate" => $ValCheckInDate,
            //                 "CheckOutDate" => $ValCheckOutDate,
            //                 "CheckInDate2" => $ValCheckInDate2,
            //                 "CheckOutDate2" => $ValCheckOutDate2,
            //                 "NewBCPart" => $NewBCPart
            //             );
            //             array_push($ArrPartList2,$TempArray);
            //         }
            //     }
            //     if($BolCheckSync == FALSE)  
            //     {
            //         echo "4";
            //         exit();
            //     }
            //     $BolCheckSync2 = TRUE;
            //     foreach ($ArrPartList2 as $PartList)
            //     {
            //         $ValBCMaterial = trim($PartList['BCMaterial']);
            //         $ValBCPart = trim($PartList['BCPart']);
            //         $ValCheckInDate = trim($PartList['CheckInDate']);
            //         $ValCheckOutDate = trim($PartList['CheckOutDate']);
            //         $ValNewBCPart = trim($PartList['NewBCPart']);
            //         $ValCheckInDate2 = trim($PartList['CheckInDate2']);
            //         $ValCheckOutDate2 = trim($PartList['CheckOutDate2']);
            //         if($ValCheckInDate == "")
            //         {
            //             $BolCheckSync2 = FALSE;
            //             break;
            //         }
            //     }
            //     if($BolCheckSync2 == FALSE)
            //     {
            //         # kondisi data part ada yg blm dicheck in
            //         echo "6";
            //         exit();
            //     }
            //     foreach ($ArrPartList2 as $PartList)
            //     {
            //         $ValBCMaterial = trim($PartList['BCMaterial']);
            //         $ValBCPart = trim($PartList['BCPart']);
            //         $ValCheckInDate = trim($PartList['CheckInDate']);
            //         $ValCheckOutDate = trim($PartList['CheckOutDate']);
            //         $ValNewBCPart = trim($PartList['NewBCPart']);
            //         $ValCheckInDate2 = trim($PartList['CheckInDate2']);
            //         $ValCheckOutDate2 = trim($PartList['CheckOutDate2']);

            //         if($ValCheckOutDate == "")   # check date check in
            //         {
            //             # update check in date
            //             UPDATE_DATE_FABRICATION_CHECKOUT_PART($ValBCPart,$Time,$LinkPSM);
            //             # insert history checkinout
            //             INSERT_START_LOG_HISTORY_CHECKINOUT_FABRICATION_2($NIKEmployee,$FNEmployee,$Time,"OUT",$ValNewBCPart,$AreaInput,"PSM",$ValBCPart,$LinkPSL);
            //             # insert history checkinout local
            //             INSERT_LOG_HISTORY_CHECKINOUT_PSM($NIKEmployee,$FNEmployee,$Time,"OUT",$ValBCPart,$AreaInput,$LinkPSM);
            //             $TotalUpdatePart++;
            //         }
            //         # check date in psl
            //         if($ValCheckOutDate2 == "")
            //         {
            //             # update check in date
            //             UPDATE_DATE_FABRICATION_CHECKOUT_PART($ValNewBCPart,$Time,$LinkPSL);
            //         }
            //     }
            //     $ArrPartList = array();
            //     $ArrPartList2 = array();
            //     if($TotalUpdatePart == 0 && $TotalPartCheckedBefore == 0)
            //     {
            //         # kondisi data part kosong
            //         echo "2";
            //         exit();
            //     }
            //     else
            //     {
            //         if($TotalUpdatePart == 0 && $TotalPartCheckedBefore != 0)
            //         {
            //             # kondisi part sudah pernah di check sebelumnya
            //             echo "3";
            //             exit();
            //         }
            //         else
            //         {
            //             # kondisi berhasil
            //             echo "OK";
            //             exit();
            //         }
            //     }
            // }
            // else
            // {
                $QListPart = GET_LIST_PART_BY_MATERIAL_ID($BarcodeInput,$LinkPSL);
                $ArrPartList = array();
                $TotalUpdatePart = 0;
                $TotalPartCheckedBefore = 0;
                while($RListPart = mssql_fetch_assoc($QListPart))
                {
                    $ValBCMaterial = trim($RListPart['BCMaterial']);
                    $ValBCPart = trim($RListPart['PartBarcode']);
                    $ValCheckInDate = trim($RListPart['FabCheckInDate']);
                    $ValCheckOutDate = trim($RListPart['FabCheckOutDate']);
                    $TempArray = array(
                        "BCMaterial" => $ValBCMaterial,
                        "BCPart" => $ValBCPart,
                        "CheckInDate" => $ValCheckInDate,
                        "CheckOutDate" => $ValCheckOutDate,
                    );
                    array_push($ArrPartList,$TempArray);
                }

                $BolCheckSync = TRUE;
                # check sinkron part
                foreach ($ArrPartList as $PartList)
                {
                    $ValBCMaterial = trim($PartList['BCMaterial']);
                    $ValBCPart = trim($PartList['BCPart']);
                    $ValCheckInDate = trim($PartList['CheckInDate']);
                    $ValCheckOutDate = trim($PartList['CheckOutDate']);
                    if($ValCheckInDate == "")
                    {
                        $BolCheckSync = FALSE;
                        break;
                    }
                }
                if($BolCheckSync == FALSE)
                {
                    # kondisi data part ada yg blm dicheck in
                    echo "6";
                    exit();
                }
                foreach ($ArrPartList as $PartList)
                {
                    $ValBCMaterial = trim($PartList['BCMaterial']);
                    $ValBCPart = trim($PartList['BCPart']);
                    $ValCheckInDate = trim($PartList['CheckInDate']);
                    $ValCheckOutDate = trim($PartList['CheckOutDate']);
                    if($ValCheckOutDate == "")   # check date check out
                    {
                        # update check out date
                        UPDATE_DATE_FABRICATION_CHECKOUT_PART($ValBCPart,$Time,$LinkPSL);
                        # insert history checkinout
                        INSERT_START_LOG_HISTORY_CHECKINOUT_FABRICATION($NIKEmployee,$FNEmployee,$Time,"OUT",$ValBCPart,$AreaInput,$LocationInput,$UserNameSession,$LinkPSL);
                        $TotalUpdatePart++;
                    }
                    else
                    {
                        $TotalPartCheckedBefore++;
                    }
                }
                if($TotalUpdatePart == 0 && $TotalPartCheckedBefore == 0)
                {
                    # kondisi data part kosong
                    echo "2";
                    exit();
                }
                else
                {
                    if($TotalUpdatePart == 0 && $TotalPartCheckedBefore != 0)
                    {
                        # kondisi part sudah pernah di check sebelumnya
                        // echo "3";
                        // exit();
                        foreach ($ArrPartList as $PartList)
                        {
                            $ValBCMaterial = trim($PartList['BCMaterial']);
                            $ValBCPart = trim($PartList['BCPart']);
                            $ValCheckInDate = trim($PartList['CheckInDate']);
                            $ValCheckOutDate = trim($PartList['CheckOutDate']);
                            # insert history checkinout
                            INSERT_START_LOG_HISTORY_CHECKINOUT_FABRICATION($NIKEmployee,$FNEmployee,$Time,"OUT",$ValBCPart,$AreaInput,$LocationInput,$UserNameSession,$LinkPSL);
                        }
                        # kondisi berhasil
                        echo "OK";
                        exit();
                    }
                    else
                    {
                        # kondisi berhasil
                        echo "OK";
                        exit();
                    }
                }
            // }
        }
        if($AreaInput == "FINISHING")
        {
            # check barcode valid
            // $BolCheckValid = FALSE;
            // if($LocationInput != "PSL") # lokasi selain PSL
            // {
            //     $QDetailMaterialID = GET_DETAIL_BARCODE_MATERIAL_BY_ID($BarcodeInput,$LinkPSM);
            // }
            // else
            // {
                $QDetailMaterialID = GET_DETAIL_BARCODE_MATERIAL_BY_ID($BarcodeInput,$LinkPSL);
            // }
            if(mssql_num_rows($QDetailMaterialID) != "0")
            {
                $BolCheckValid = TRUE;
            }
            if($BolCheckValid == FALSE)
            {
                echo "1";
                exit();
            }
            # check date check by part
            // if($LocationInput != "PSL") # lokasi selain PSL
            // {
            //     $QListPart = GET_LIST_PART_BY_MATERIAL_ID($BarcodeInput,$LinkPSM);
            //     $TotalUpdatePart = 0;
            //     $ArrPartList = array();
            //     $ArrPartList2 = array();
            //     while($RListPart = mssql_fetch_assoc($QListPart))
            //     {
            //         $ValBCMaterial = trim($RListPart['BCMaterial']);
            //         $ValBCPart = trim($RListPart['PartBarcode']);
            //         $ValCheckInDate = trim($RListPart['FinCheckInDate']);
            //         $ValCheckOutDate = trim($RListPart['FinCheckOutDate']);
            //         $TempArray = array(
            //             "BCMaterial" => $ValBCMaterial,
            //             "BCPart" => $ValBCPart,
            //             "CheckInDate" => $ValCheckInDate,
            //             "CheckOutDate" => $ValCheckOutDate,
            //         );
            //         array_push($ArrPartList,$TempArray);
            //     }
            //     $BolCheckSync = TRUE;
            //     # check sinkron part
            //     foreach ($ArrPartList as $PartList)
            //     {
            //         $ValBCMaterial = trim($PartList['BCMaterial']);
            //         $ValBCPart = trim($PartList['BCPart']);
            //         $ValCheckInDate = trim($PartList['CheckInDate']);
            //         $ValCheckOutDate = trim($PartList['CheckOutDate']);
            //         # check sync
            //         $NewBCPart = GET_NEW_BARCODE_PART_PSL($ValBCPart,$LinkPSL);
            //         if(trim($NewBCPart) == "")
            //         {
            //             $BolCheckSync = FALSE;
            //             break;
            //         }
            //         else
            //         {
            //             $QDataMainBC = GET_CHECKIN_DATE_FINISHING_MAIN_BY_BARCODE($NewBCPart,$LinkPSL);
            //             $RDataMainBC = mssql_fetch_assoc($QDataMainBC);
            //             $ValCheckInDate2 = trim($RDataMainBC['CheckInDate']);
            //             $ValCheckOutDate2 = trim($RDataMainBC['CheckOutDate']);
            //             $TempArray = array(
            //                 "BCMaterial" => $ValBCMaterial,
            //                 "BCPart" => $ValBCPart,
            //                 "CheckInDate" => $ValCheckInDate,
            //                 "CheckOutDate" => $ValCheckOutDate,
            //                 "CheckInDate2" => $ValCheckInDate2,
            //                 "CheckOutDate2" => $ValCheckOutDate2,
            //                 "NewBCPart" => $NewBCPart
            //             );
            //             array_push($ArrPartList2,$TempArray);
            //         }
            //     }
            //     if($BolCheckSync == FALSE)  
            //     {
            //         echo "4";
            //         exit();
            //     }
            //     $BolCheckSync2 = TRUE;
            //     foreach ($ArrPartList2 as $PartList)
            //     {
            //         $ValBCMaterial = trim($PartList['BCMaterial']);
            //         $ValBCPart = trim($PartList['BCPart']);
            //         $ValCheckInDate = trim($PartList['CheckInDate']);
            //         $ValCheckOutDate = trim($PartList['CheckOutDate']);
            //         $ValNewBCPart = trim($PartList['NewBCPart']);
            //         $ValCheckInDate2 = trim($PartList['CheckInDate2']);
            //         $ValCheckOutDate2 = trim($PartList['CheckOutDate2']);
            //         if($ValCheckInDate == "")
            //         {
            //             $BolCheckSync2 = FALSE;
            //             break;
            //         }
            //     }
            //     if($BolCheckSync2 == FALSE)
            //     {
            //         # kondisi data part ada yg blm dicheck in
            //         echo "6";
            //         exit();
            //     }
            //     foreach ($ArrPartList2 as $PartList)
            //     {
            //         $ValBCMaterial = trim($PartList['BCMaterial']);
            //         $ValBCPart = trim($PartList['BCPart']);
            //         $ValCheckInDate = trim($PartList['CheckInDate']);
            //         $ValCheckOutDate = trim($PartList['CheckOutDate']);
            //         $ValNewBCPart = trim($PartList['NewBCPart']);
            //         $ValCheckInDate2 = trim($PartList['CheckInDate2']);
            //         $ValCheckOutDate2 = trim($PartList['CheckOutDate2']);

            //         if($ValCheckOutDate == "")   # check date check in
            //         {
            //             # update check in date
            //             UPDATE_DATE_FINISHING_CHECKOUT_PART($ValBCPart,$Time,$LinkPSM);
            //             # insert history checkinout
            //             INSERT_START_LOG_HISTORY_CHECKINOUT_FINISHING_2($NIKEmployee,$FNEmployee,$Time,"OUT",$ValNewBCPart,$AreaInput,"PSM",$ValBCPart,$LinkPSL);
            //             # insert history checkinout local
            //             INSERT_LOG_HISTORY_CHECKINOUT_PSM($NIKEmployee,$FNEmployee,$Time,"OUT",$ValBCPart,$AreaInput,$LinkPSM);
            //             $TotalUpdatePart++;
            //         }
            //         # check date in psl
            //         if($ValCheckOutDate2 == "")
            //         {
            //             # update check in date
            //             UPDATE_DATE_FINISHING_CHECKOUT_PART($ValNewBCPart,$Time,$LinkPSL);
            //         }
            //     }
            //     $ArrPartList = array();
            //     $ArrPartList2 = array();
            //     if($TotalUpdatePart == 0 && $TotalPartCheckedBefore == 0)
            //     {
            //         # kondisi data part kosong
            //         echo "2";
            //         exit();
            //     }
            //     else
            //     {
            //         if($TotalUpdatePart == 0 && $TotalPartCheckedBefore != 0)
            //         {
            //             # kondisi part sudah pernah di check sebelumnya
            //             echo "3";
            //             exit();
            //         }
            //         else
            //         {
            //             # kondisi berhasil
            //             echo "OK";
            //             exit();
            //         }
            //     }
            // }
            // else
            // {
                $QListPart = GET_LIST_PART_BY_MATERIAL_ID($BarcodeInput,$LinkPSL);
                $ArrPartList = array();
                $TotalUpdatePart = 0;
                $TotalPartCheckedBefore = 0;
                while($RListPart = mssql_fetch_assoc($QListPart))
                {
                    $ValBCMaterial = trim($RListPart['BCMaterial']);
                    $ValBCPart = trim($RListPart['PartBarcode']);
                    $ValCheckInDate = trim($RListPart['FinCheckInDate']);
                    $ValCheckOutDate = trim($RListPart['FinCheckOutDate']);
                    $TempArray = array(
                        "BCMaterial" => $ValBCMaterial,
                        "BCPart" => $ValBCPart,
                        "CheckInDate" => $ValCheckInDate,
                        "CheckOutDate" => $ValCheckOutDate,
                    );
                    array_push($ArrPartList,$TempArray);
                }

                $BolCheckSync = TRUE;
                # check sinkron part
                foreach ($ArrPartList as $PartList)
                {
                    $ValBCMaterial = trim($PartList['BCMaterial']);
                    $ValBCPart = trim($PartList['BCPart']);
                    $ValCheckInDate = trim($PartList['CheckInDate']);
                    $ValCheckOutDate = trim($PartList['CheckOutDate']);
                    if($ValCheckInDate == "")
                    {
                        $BolCheckSync = FALSE;
                        break;
                    }
                }
                if($BolCheckSync == FALSE)
                {
                    # kondisi data part ada yg blm dicheck in
                    echo "6";
                    exit();
                }
                foreach ($ArrPartList as $PartList)
                {
                    $ValBCMaterial = trim($PartList['BCMaterial']);
                    $ValBCPart = trim($PartList['BCPart']);
                    $ValCheckInDate = trim($PartList['CheckInDate']);
                    $ValCheckOutDate = trim($PartList['CheckOutDate']);
                    if($ValCheckOutDate == "")   # check date check out
                    {
                        # update check out date
                        UPDATE_DATE_FINISHING_CHECKOUT_PART($ValBCPart,$Time,$LinkPSL);
                        # insert history checkinout
                        INSERT_START_LOG_HISTORY_CHECKINOUT_FINISHING($NIKEmployee,$FNEmployee,$Time,"OUT",$ValBCPart,$AreaInput,$LocationInput,$UserNameSession,$LinkPSL);
                        $TotalUpdatePart++;
                    }
                    else
                    {
                        $TotalPartCheckedBefore++;
                    }
                }
                if($TotalUpdatePart == 0 && $TotalPartCheckedBefore == 0)
                {
                    # kondisi data part kosong
                    echo "2";
                    exit();
                }
                else
                {
                    if($TotalUpdatePart == 0 && $TotalPartCheckedBefore != 0)
                    {
                        # kondisi part sudah pernah di check sebelumnya
                        // echo "3";
                        // exit();
                        foreach ($ArrPartList as $PartList)
                        {
                            $ValBCMaterial = trim($PartList['BCMaterial']);
                            $ValBCPart = trim($PartList['BCPart']);
                            $ValCheckInDate = trim($PartList['CheckInDate']);
                            $ValCheckOutDate = trim($PartList['CheckOutDate']);
                            # insert history checkinout
                            INSERT_START_LOG_HISTORY_CHECKINOUT_FINISHING($NIKEmployee,$FNEmployee,$Time,"OUT",$ValBCPart,$AreaInput,$LocationInput,$UserNameSession,$LinkPSL);
                        }
                        # kondisi berhasil
                        echo "OK";
                        exit();
                    }
                    else
                    {
                        # kondisi berhasil
                        echo "OK";
                        exit();
                    }
                }
            // }
        }
    }
    else    # jenis input berupa part
    {
        if($AreaInput == "MACHINING")
        {
            # check barcode valid
            $BolCheckValid = FALSE;
            // if($LocationInput != "PSL") # lokasi selain PSL
            // {
            //     $QDetailPartID = GET_DETAIL_BARCODE_PART_BY_ID($BarcodeInput,$LinkPSM);
            // }
            // else
            // {
                $QDetailPartID = GET_DETAIL_BARCODE_PART_BY_ID($BarcodeInput,$LinkPSL);
            // }
            if(mssql_num_rows($QDetailPartID) != "0")
            {
                $BolCheckValid = TRUE;
            }
            if($BolCheckValid == FALSE)
            {
                echo "1";
                exit();
            }
            # check date check by part
            // if($LocationInput != "PSL") # lokasi selain PSL
            // {
            //     $RDetailPartID = mssql_fetch_assoc($QDetailPartID);
            //     $DateCheckInMachining = trim($RDetailPartID['CheckInDate']);
            //     $DateCheckOutMachining = trim($RDetailPartID['CheckOutDate']);
            //     if($DateCheckInMachining == "")
            //     {
            //         # kondisi data part ada yg blm dicheck in
            //         echo "7";
            //         exit();
            //     }
            //     else
            //     {
            //         if($DateCheckOutMachining != "")
            //         {
            //             echo "5";
            //             exit();
            //         }
            //         else
            //         {
            //             # check sync
            //             $NewBCPart = GET_NEW_BARCODE_PART_PSL($BarcodeInput,$LinkPSL);
            //             if(trim($NewBCPart) == "")
            //             {
            //                 echo "4";
            //                 exit();
            //             }
            //             else
            //             {
            //                 # update machining check in date
            //                 UPDATE_DATE_CHECKOUT_PART($BarcodeInput,$Time,$LinkPSM);
            //                 # data part di psl
            //                 $QDataPart = GET_CHECKIN_DATE_MAIN_BY_BARCODE($NewBCPart,$LinkPSL);
            //                 $RDataPart = mssql_fetch_assoc($QDataPart);
            //                 if(trim($RDataPart['CheckOutDate']) == "")
            //                 {
            //                     UPDATE_DATE_CHECKOUT_PART($NewBCPart,$Time,$LinkPSL);
            //                     # insert history checkinout
            //                     INSERT_START_LOG_HISTORY_CHECKINOUT_2($NIKEmployee,$FNEmployee,$Time,"OUT",$NewBCPart,$AreaInput,"PSM",$BarcodeInput,$LinkPSL);
            //                     # insert history checkinout local
            //                     INSERT_LOG_HISTORY_CHECKINOUT_PSM($NIKEmployee,$FNEmployee,$Time,"OUT",$BarcodeInput,$AreaInput,$LinkPSM);
            //                     # kondisi berhasil
            //                     echo "OK";
            //                     exit();
            //                 }
            //                 else
            //                 {
            //                     echo "5";
            //                     exit();
            //                 }
            //             }
            //         }
            //     }
            // }
            // else
            // {
                $RDetailPartID = mssql_fetch_assoc($QDetailPartID);
                $DateCheckInMachining = trim($RDetailPartID['CheckInDate']);
                $DateCheckOutMachining = trim($RDetailPartID['CheckOutDate']);
                if($DateCheckInMachining == "")
                {
                    # kondisi data part ada yg blm dicheck in
                    echo "7";
                    exit();
                }
                else
                {
                    if($DateCheckOutMachining != "")
                    {
                        // echo "5";
                        // exit();
                        # insert history checkinout
                        INSERT_START_LOG_HISTORY_CHECKINOUT($NIKEmployee,$FNEmployee,$Time,"OUT",$BarcodeInput,$AreaInput,$LocationInput,$UserNameSession,$LinkPSL);
                        # kondisi berhasil
                        echo "OK";
                        exit();
                    }
                    else
                    {
                        # update machining check in date
                        UPDATE_DATE_CHECKOUT_PART($BarcodeInput,$Time,$LinkPSL);
                        # insert history checkinout
                        INSERT_START_LOG_HISTORY_CHECKINOUT($NIKEmployee,$FNEmployee,$Time,"OUT",$BarcodeInput,$AreaInput,$LocationInput,$UserNameSession,$LinkPSL);
                        # kondisi berhasil
                        echo "OK";
                        exit();
                    }
                }
            // }
        }
        if($AreaInput == "FABRICATION")
        {
            # check barcode valid
            $BolCheckValid = FALSE;
            // if($LocationInput != "PSL") # lokasi selain PSL
            // {
            //     $QDetailPartID = GET_DETAIL_BARCODE_PART_BY_ID($BarcodeInput,$LinkPSM);
            // }
            // else
            // {
                $QDetailPartID = GET_DETAIL_BARCODE_PART_BY_ID($BarcodeInput,$LinkPSL);
            // }
            if(mssql_num_rows($QDetailPartID) != "0")
            {
                $BolCheckValid = TRUE;
            }
            if($BolCheckValid == FALSE)
            {
                echo "1";
                exit();
            }
            # check date check by part
            // if($LocationInput != "PSL") # lokasi selain PSL
            // {
            //     $RDetailPartID = mssql_fetch_assoc($QDetailPartID);
            //     $DateCheckIn = trim($RDetailPartID['FabCheckInDate']);
            //     $DateCheckOut = trim($RDetailPartID['FabCheckOutDate']);
            //     if($DateCheckIn == "")
            //     {
            //         # kondisi data part ada yg blm dicheck in
            //         echo "7";
            //         exit();
            //     }
            //     else
            //     {
            //         if($DateCheckOut != "")
            //         {
            //             echo "5";
            //             exit();
            //         }
            //         else
            //         {
            //             # check sync
            //             $NewBCPart = GET_NEW_BARCODE_PART_PSL($BarcodeInput,$LinkPSL);
            //             if(trim($NewBCPart) == "")
            //             {
            //                 echo "4";
            //                 exit();
            //             }
            //             else
            //             {
            //                 # update check in date
            //                 UPDATE_DATE_FABRICATION_CHECKOUT_PART($BarcodeInput,$Time,$LinkPSM);
            //                 # data part di psl
            //                 $QDataPart = GET_CHECKIN_DATE_FABRICATION_MAIN_BY_BARCODE($NewBCPart,$LinkPSL);
            //                 $RDataPart = mssql_fetch_assoc($QDataPart);
            //                 if(trim($RDataPart['CheckOutDate']) == "")
            //                 {
            //                     UPDATE_DATE_FABRICATION_CHECKOUT_PART($NewBCPart,$Time,$LinkPSL);
            //                     # insert history checkinout
            //                     INSERT_START_LOG_HISTORY_CHECKINOUT_FABRICATION_2($NIKEmployee,$FNEmployee,$Time,"OUT",$NewBCPart,$AreaInput,"PSM",$BarcodeInput,$LinkPSL);
            //                     # insert history checkinout local
            //                     INSERT_LOG_HISTORY_CHECKINOUT_PSM($NIKEmployee,$FNEmployee,$Time,"OUT",$BarcodeInput,$AreaInput,$LinkPSM);
            //                     # kondisi berhasil
            //                     echo "OK";
            //                     exit();
            //                 }
            //                 else
            //                 {
            //                     echo "5";
            //                     exit();
            //                 }
            //             }
            //         }
            //     }
            // }
            // else
            // {
                $RDetailPartID = mssql_fetch_assoc($QDetailPartID);
                $DateCheckIn = trim($RDetailPartID['FabCheckInDate']);
                $DateCheckOut = trim($RDetailPartID['FabCheckOutDate']);
                if($DateCheckIn == "")
                {
                    # kondisi data part ada yg blm dicheck in
                    echo "7";
                    exit();
                }
                else
                {
                    if($DateCheckOut != "")
                    {
                        // echo "5";
                        // exit();
                        # insert history checkinout
                        INSERT_START_LOG_HISTORY_CHECKINOUT_FABRICATION($NIKEmployee,$FNEmployee,$Time,"OUT",$BarcodeInput,$AreaInput,$LocationInput,$UserNameSession,$LinkPSL);
                        # kondisi berhasil
                        echo "OK";
                        exit();
                    }
                    else
                    {
                        # update check in date
                        UPDATE_DATE_FABRICATION_CHECKOUT_PART($BarcodeInput,$Time,$LinkPSL);
                        # insert history checkinout
                        INSERT_START_LOG_HISTORY_CHECKINOUT_FABRICATION($NIKEmployee,$FNEmployee,$Time,"OUT",$BarcodeInput,$AreaInput,$LocationInput,$UserNameSession.$LinkPSL);
                        # kondisi berhasil
                        echo "OK";
                        exit();
                    }
                // }
            }
        }
        if($AreaInput == "FINISHING")
        {
            # check barcode valid
            $BolCheckValid = FALSE;
            // if($LocationInput != "PSL") # lokasi selain PSL
            // {
            //     $QDetailPartID = GET_DETAIL_BARCODE_PART_BY_ID($BarcodeInput,$LinkPSM);
            // }
            // else
            // {
                $QDetailPartID = GET_DETAIL_BARCODE_PART_BY_ID($BarcodeInput,$LinkPSL);
            // }
            if(mssql_num_rows($QDetailPartID) != "0")
            {
                $BolCheckValid = TRUE;
            }
            if($BolCheckValid == FALSE)
            {
                echo "1";
                exit();
            }
            # check date check by part
            // if($LocationInput != "PSL") # lokasi selain PSL
            // {
            //     $RDetailPartID = mssql_fetch_assoc($QDetailPartID);
            //     $DateCheckIn = trim($RDetailPartID['FinCheckInDate']);
            //     $DateCheckOut = trim($RDetailPartID['FinCheckOutDate']);
            //     if($DateCheckIn == "")
            //     {
            //         # kondisi data part ada yg blm dicheck in
            //         echo "7";
            //         exit();
            //     }
            //     else
            //     {
            //         if($DateCheckOut != "")
            //         {
            //             echo "5";
            //             exit();
            //         }
            //         else
            //         {
            //             # check sync
            //             $NewBCPart = GET_NEW_BARCODE_PART_PSL($BarcodeInput,$LinkPSL);
            //             if(trim($NewBCPart) == "")
            //             {
            //                 echo "4";
            //                 exit();
            //             }
            //             else
            //             {
            //                 # update check in date
            //                 UPDATE_DATE_FINISHING_CHECKOUT_PART($BarcodeInput,$Time,$LinkPSM);
            //                 # data part di psl
            //                 $QDataPart = GET_CHECKIN_DATE_FINISHING_MAIN_BY_BARCODE($NewBCPart,$LinkPSL);
            //                 $RDataPart = mssql_fetch_assoc($QDataPart);
            //                 if(trim($RDataPart['CheckOutDate']) == "")
            //                 {
            //                     UPDATE_DATE_FINISHING_CHECKOUT_PART($NewBCPart,$Time,$LinkPSL);
            //                     # insert history checkinout
            //                     INSERT_START_LOG_HISTORY_CHECKINOUT_FINISHING_2($NIKEmployee,$FNEmployee,$Time,"OUT",$NewBCPart,$AreaInput,"PSM",$BarcodeInput,$LinkPSL);
            //                     # insert history checkinout local
            //                     INSERT_LOG_HISTORY_CHECKINOUT_PSM($NIKEmployee,$FNEmployee,$Time,"OUT",$BarcodeInput,$AreaInput,$LinkPSM);
            //                     # kondisi berhasil
            //                     echo "OK";
            //                     exit();
            //                 }
            //                 else
            //                 {
            //                     echo "5";
            //                     exit();
            //                 }
            //             }
            //         }
            //     }
            // }
            // else
            // {
                $RDetailPartID = mssql_fetch_assoc($QDetailPartID);
                $DateCheckIn = trim($RDetailPartID['FinCheckInDate']);
                $DateCheckOut = trim($RDetailPartID['FinCheckOutDate']);
                if($DateCheckIn == "")
                {
                    # kondisi data part ada yg blm dicheck in
                    echo "7";
                    exit();
                }
                else
                {
                    if($DateCheckOut != "")
                    {
                        // echo "5";
                        // exit();
                        # insert history checkinout
                        INSERT_START_LOG_HISTORY_CHECKINOUT_FINISHING($NIKEmployee,$FNEmployee,$Time,"OUT",$BarcodeInput,$AreaInput,$LocationInput,$UserNameSession,$LinkPSL);
                        # kondisi berhasil
                        echo "OK";
                        exit();
                    }
                    else
                    {
                        # update check in date
                        UPDATE_DATE_FINISHING_CHECKOUT_PART($BarcodeInput,$Time,$LinkPSL);
                        # insert history checkinout
                        INSERT_START_LOG_HISTORY_CHECKINOUT_FINISHING($NIKEmployee,$FNEmployee,$Time,"OUT",$BarcodeInput,$AreaInput,$LocationInput,$UserNameSession,$LinkPSL);
                        # kondisi berhasil
                        echo "OK";
                        exit();
                    }
                }
            // }
        }
    }
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