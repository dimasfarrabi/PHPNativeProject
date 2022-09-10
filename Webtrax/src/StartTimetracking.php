<?php
session_start();
if(!session_is_registered("UIDWebTrax"))
{
	?>
    <script language="javascript">
        window.location.replace("https://webtrax.formulatrix.com/");
    </script>
    <?php
    exit();
}
require("../../src/srcConnect.php");
require("../../src/srcProcessFunction.php");
require("../../src/srcFunction.php");
require("Modules/ModuleLogin.php");
require("Modules/ModuleMappingSubstation.php");
date_default_timezone_set("Asia/Jakarta");
# data session
$EmployeeID = base64_decode(base64_decode($_SESSION['UIDWebTrax']));
$AccessLogin = base64_decode(base64_decode($_SESSION['LoginMode']));
# data employee
$QDataEmployee = GET_DATA_EMPLOYEE($EmployeeID,$linkHRISWebTrax);
$RDataEmployee = mssql_fetch_assoc($QDataEmployee);
$FullName = $RDataEmployee['FullName'];
$NIK = $RDataEmployee['NIK'];
$DivisionID = $RDataEmployee['Division_ID'];
$NIKSorting = $RDataEmployee['NIKSorting'];
# data divisi
$DivisionName = GET_EMPLOYEE_DIVISION($DivisionID);
# data pc
$PCName = GET_COMPUTER_NAME();
# set division mapping
$DivisionMapping = GET_DIVISION_MAPPING($NIK,$NIKSorting);
# data default
$BolAcc = TRUE;
$BolStep = TRUE;

if($_SERVER['REQUEST_METHOD'] == "POST")
{
    $ValGroupNo = htmlspecialchars(trim($_POST['ValGroupNo']), ENT_QUOTES, "UTF-8");
    $ValActivity = htmlspecialchars(trim($_POST['ValActivity']), ENT_QUOTES, "UTF-8");
    $ValSubActivity = htmlspecialchars(trim($_POST['ValSubActivity']), ENT_QUOTES, "UTF-8");
    $ArrScan = json_decode($_POST['ValScan']);
    $CountArr = count($ArrScan);
    # cek proses sebelumnya
    $QGroupNo = GET_GROUPNO_BY_DEVICE_AND_EMPLOYEE($PCName,$FullName,$linkMACHWebTrax); # cari group no sebelumnya
    if(mssql_num_rows($QGroupNo) != "0")
    {
        # masih ada proses sebelumnya
        $RGroupNo = mssql_fetch_assoc($QGroupNo);
        $GroupNo = $RGroupNo['GroupNo'];
        $BolStopTimetrackGroup = FALSE;
        $BolUpdateDurationGroup = FALSE;
        $BolUpdateLineItem = FALSE;
        $BolUpdateRunningTime = FALSE;
        $BolInsertTimetrack = FALSE;
        $BolAcc = FALSE;
        $BolCheck = FALSE;
        $ErrorNote = "";
        # stop proses
        $BolStopTimetrackGroup = STOP_TIMETRACK_GROUP_BY_ID($GroupNo,$linkMACHWebTrax);
        if($BolStopTimetrackGroup == TRUE)
        {
            # update duration group
            $BolUpdateDurationGroup = UPDATE_EACH_PART_DURATION_GROUP_BY_ID($GroupNo,$linkMACHWebTrax);
            if($BolUpdateDurationGroup == TRUE)
            {
                # update line item
                $BolUpdateLineItem = UPDATE_EACH_PART_DURATION_TIMETRACKING_LINE($GroupNo,$linkMACHWebTrax);
                if($BolUpdateLineItem == TRUE)
                {
                    # update running time
                    $BolUpdateRunningTime = PROCESS_TIMETRACKING_LINE_ITEM_RUNNING_TIME($GroupNo,$linkMACHWebTrax);
                    if($BolUpdateRunningTime == TRUE)
                    {
                        # insert timetrack
                        $BolInsertTimetrack = JOIN_LINE_ITEM_WITH_GROUP_TIMETRACK($GroupNo,$linkMACHWebTrax);
                        if($BolInsertTimetrack == TRUE)
                        {
                            $ErrorNote = "";
                            $BolCheck = TRUE;
                            # reset tabel proses
                            ?>
                            <script>
                            $(document).ready(function () {
                                $("#TableDataListBarcodeProses").DataTable().clear().draw();
                            });
                            </script>
                            <?php
                        }
                        else
                        {
                            $ErrorNote = '$("#InfoNotes").val("Timetrack tidak dapat dihentikan![4]");';
                            $BolCheck = FALSE;
                        }
                    }
                    else
                    {
                        $ErrorNote = '$("#InfoNotes").val("Running gagal di update!");';
                        $BolCheck = FALSE;
                    }
                }
                else
                {
                    $ErrorNote = '$("#InfoNotes").val("Timetrack tidak dapat dihentikan![3]");';
                    $BolCheck = FALSE;
                }
            }
            else
            {
                $ErrorNote = '$("#InfoNotes").val("Timetrack tidak dapat dihentikan![2]");';
                $BolCheck = FALSE;
            }
        }
        else
        {
            $ErrorNote = '$("#InfoNotes").val("Timetrack tidak dapat dihentikan![1]");';
            $BolCheck = FALSE;
        }
        if($BolCheck == FALSE)
        {
            # set refresh group no
            $GroupNoDefault = SET_GROUP_NO();
            # show error
            ?>
            <script>
            $(document).ready(function () {
                $('#TableIdentitasKaryawan').find('tr:eq(1)').find('td:eq(2)').html("<?php echo $GroupNoDefault; ?>");
                <?php echo $ErrorNote;?>
            });
            </script>
            <?php
            exit();
        }
        else # kondisi proses sudah berhasil diberhentikan
        {
            $BolAcc = TRUE;
        }
    }
    else
    {
        # tidak ada proses sebelumnya
        $BolAcc = TRUE;
    }

    if($BolAcc == TRUE)
    {
        # set shiftcode
        $ValShiftCode = SET_SHIFTCODE();
        # get total parts
        $ValTotalParts = $CountArr;
        # set timetracking group
        $BolSavingTimetrack = FALSE;
        $BolSavingTimetrack = SAVE_TIMETRACKING_GROUP($ValGroupNo,$PCName,$FullName,$NIK,$DivisionName,$ValActivity,"-","0",$ValShiftCode,$ValTotalParts,"",$linkMACHWebTrax);
        if($BolSavingTimetrack == FALSE)
        {
            # set refresh group no
            $GroupNoDefault = SET_GROUP_NO();
            # show error
            ?>
            <script>
            $(document).ready(function () {
                $('#TableIdentitasKaryawan').find('tr:eq(1)').find('td:eq(2)').html("<?php echo $GroupNoDefault; ?>");
                $("#InfoNotes").val("Timetrack tidak dapat dijalankan!");
            });
            </script>
            <?php
            exit();
        }
        else
        {
            # insert part manualy
            $BolInsertPartManual = FALSE;
            $ValBarcode = "";
            $BolShowRes = FALSE;
            foreach($ArrScan as $DataScan)
            {
                $ValBarcode = $DataScan->Barcode;
                $ValGroupNoArr = $DataScan->GroupNo;
                $BolInsertPartManual = INSERT_PART_MANUALY($ValBarcode,$ValGroupNoArr,$DivisionMapping,$linkMACHWebTrax);
                if($BolInsertPartManual == FALSE)
                {
                    # set refresh group no
                    $GroupNoDefault = SET_GROUP_NO();
                    # show error
                    ?>
                    <script>
                    $(document).ready(function () {
                        $('#TableIdentitasKaryawan').find('tr:eq(1)').find('td:eq(2)').html("<?php echo $GroupNoDefault; ?>");
                        $("#InfoNotes").val("Barcode tidak dapat di proses!");
                    });
                    </script>
                    <?php
                    exit();
                }
                else
                {
                    # set valid true
                    $BolShowRes = TRUE;
                }
            }
            # tampilkan hasil
            if($BolShowRes == TRUE)
            {
                # load proses
                $QProgressEntry = GET_LOAD_PROGRESS_ENTRY_DEVICES($PCName,$FullName,$linkMACHWebTrax);
                # set data list
                $ArrListEntry = array();
                $NoRow = 1;
                $TotRow = mssql_num_rows($QProgressEntry);
                if($TotRow != "0")
                {
                    while($RProgressEntry = mssql_fetch_assoc($QProgressEntry))
                    {
                        $Employee = $RProgressEntry['Employee'];
                        $WorkOrder = $RProgressEntry['WorkOrder'];
                        $Activity = $RProgressEntry['Activity'];
                        $SubActivity = $RProgressEntry['SubActivity'];
                        $StartTime = $RProgressEntry['StartTime'];
                        $EndTime = $RProgressEntry['EndTime'];
                        $ShiftCode = $RProgressEntry['ShiftCode'];
                        $BarcodePart = $RProgressEntry['BarcodePart'];
                        $GroupNo = $RProgressEntry['GroupNo'];
                        $Idx = $RProgressEntry['Idx'];
                        $ArrayTemp = array(
                            "ArrEmployee" => $Employee,
                            "ArrWorkOrder" => $WorkOrder,
                            "ArrActivity" => $Activity,
                            "ArrSubActivity" => $SubActivity,
                            "ArrStartTime" => $StartTime,
                            "ArrEndTime" => $EndTime,
                            "ArrShiftCode" => $ShiftCode,
                            "ArrBarcodePart" => $BarcodePart,
                            "ArrGroupNo" => $GroupNo,
                            "ArrIdx" => $Idx
                        );
                        array_push($ArrListEntry,$ArrayTemp);
                        $NoRow++;
                    }
                }
                # set refresh group no
                $GroupNoDefault = SET_GROUP_NO();
                # reset form & tabel temp
                ?>
                <script>
                $(document).ready(function () {
                    $("#TableDataListBarcodeSementara").DataTable().clear().draw();
                    $('#TableIdentitasKaryawan').find('tr:eq(1)').find('td:eq(2)').html("<?php echo $GroupNoDefault; ?>");
                    $('#TableIdentitasProduk').find('tr:eq(0)').find('td:eq(2)').html("-");
                    $('#TableIdentitasProduk').find('tr:eq(1)').find('td:eq(2)').html("-");
                    $('#TableIdentitasProduk').find('tr:eq(2)').find('td:eq(2)').html("-");
                    $('#TableIdentitasProduk').find('tr:eq(3)').find('td:eq(2)').html("-");
                    $('#TableIdentitasProduk').find('tr:eq(4)').find('td:eq(2)').html("-");
                    $('#TableIdentitasProduk').find('tr:eq(5)').find('td:eq(2)').html("-");
                    <?php
                        if($NoRow == 1)
                        {
                            foreach ($ArrListEntry as $ListEntry)
                            {
                                $REmployee = $ListEntry['ArrEmployee'];
                                $RWorkOrder = $ListEntry['ArrWorkOrder'];
                                $RActivity = $ListEntry['ArrActivity'];
                                $RSubActivity = $ListEntry['ArrSubActivity'];
                                $RStartTime = $ListEntry['ArrStartTime'];
                                $REndTime = $ListEntry['ArrEndTime'];
                                $RShiftCode = $ListEntry['ArrShiftCode'];
                                $RBarcodePart = $ListEntry['ArrBarcodePart'];
                                $RGroupNo = $ListEntry['ArrGroupNo'];
                                $RIdx = $ListEntry['ArrIdx'];
                                echo '$("#TableDataListBarcodeProses").DataTable().row.add(';
                                echo '[';
                                echo '"'.$REmployee.'"';
                                echo ',"'.$RWorkOrder.'"';
                                echo ',"'.$RActivity.'"';
                                echo ',"'.$RSubActivity.'"';
                                echo ',"'.$RStartTime.'"';
                                echo ',"'.$REndTime.'"';
                                echo ',"'.$RShiftCode.'"';
                                echo ',"'.$RBarcodePart.'"';
                                echo ',"'.$RGroupNo.'"';
                                echo ',"'.$RIdx.'"';
                                echo ']';
                                echo ').draw(false);';   
                            }
                        }
                        else
                        {
                            $ArrNo = 1;
                            foreach ($ArrListEntry as $ListEntry)
                            {
                                $REmployee = $ListEntry['ArrEmployee'];
                                $RWorkOrder = $ListEntry['ArrWorkOrder'];
                                $RActivity = $ListEntry['ArrActivity'];
                                $RSubActivity = $ListEntry['ArrSubActivity'];
                                $RStartTime = $ListEntry['ArrStartTime'];
                                $REndTime = $ListEntry['ArrEndTime'];
                                $RShiftCode = $ListEntry['ArrShiftCode'];
                                $RBarcodePart = $ListEntry['ArrBarcodePart'];
                                $RGroupNo = $ListEntry['ArrGroupNo'];
                                $RIdx = $ListEntry['ArrIdx'];
                                if($ArrNo == 1)
                                {
                                    echo '$("#TableDataListBarcodeProses").DataTable().row.add(';
                                    echo '[';
                                    echo '"'.$REmployee.'"';
                                    echo ',"'.$RWorkOrder.'"';
                                    echo ',"'.$RActivity.'"';
                                    echo ',"'.$RSubActivity.'"';
                                    echo ',"'.$RStartTime.'"';
                                    echo ',"'.$REndTime.'"';
                                    echo ',"'.$RShiftCode.'"';
                                    echo ',"'.$RBarcodePart.'"';
                                    echo ',"'.$RGroupNo.'"';
                                    echo ',"'.$RIdx.'"';
                                    echo ']';
                                    echo ').draw(false);';   
                                    $ArrNo++;
                                }
                                else
                                {
                                    echo '$("#TableDataListBarcodeProses").DataTable().row.add(';
                                    echo '[';
                                    echo '"'.$REmployee.'"';
                                    echo ',"'.$RWorkOrder.'"';
                                    echo ',"'.$RActivity.'"';
                                    echo ',"'.$RSubActivity.'"';
                                    echo ',"'.$RStartTime.'"';
                                    echo ',"'.$REndTime.'"';
                                    echo ',"'.$RShiftCode.'"';
                                    echo ',"'.$RBarcodePart.'"';
                                    echo ',"'.$RGroupNo.'"';
                                    echo ',"'.$RIdx.'"';
                                    echo ']';
                                    echo ').draw(false);';   
                                    $ArrNo++;
                                }
                            }
                        }                     
                    ?>
                });
                </script>
                <?php
            }
        }
    }
    else
    {
        # proses error
        ?>
        <script>
        $(document).ready(function () {
            $("#InfoNotes").val("Timetrack tidak dapat dijalankan. Silahkan mencoba beberapa saat lagi!");
        });
        </script>
        <?php
    }
}
else
{
    echo "Anda tidak mempunyai hak akses!";
}
?>