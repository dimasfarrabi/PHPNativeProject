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
    $ValGroupNo = htmlspecialchars(trim($_POST['ValGroupNo']), ENT_QUOTES, "UTF-8");    # tidak digunakan
    $ValActivity = htmlspecialchars(trim($_POST['ValActivity']), ENT_QUOTES, "UTF-8");    # tidak digunakan
    $ValSubActivity = htmlspecialchars(trim($_POST['ValSubActivity']), ENT_QUOTES, "UTF-8");    # tidak digunakan
    # get 
    $QGroupNo = GET_GROUPNO_BY_DEVICE_AND_EMPLOYEE($PCName,$FullName,$linkMACHWebTrax); # cari group no sebelumnya
    if(mssql_num_rows($QGroupNo) != "0")
    {
        $BolStopTimetrackGroup = FALSE;
        $BolUpdateDurationGroup = FALSE;
        $BolUpdateLineItem = FALSE;
        $BolUpdateRunningTime = FALSE;
        $BolInsertTimetrack = FALSE;
        $BolAcc = FALSE;
        $BolCheck = FALSE;
        $ErrorNote = "";
        # masih ada proses sebelumnya
        $RGroupNo = mssql_fetch_assoc($QGroupNo);
        $GroupNo = $RGroupNo['GroupNo'];
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
            # set refresh group no
            $GroupNoDefault = SET_GROUP_NO();
            # show error
            ?>
            <script>
            $(document).ready(function () {
                $('#TableIdentitasKaryawan').find('tr:eq(1)').find('td:eq(2)').html("<?php echo $GroupNoDefault; ?>");
                $("#TableDataListBarcodeProses").DataTable().clear().draw();
            });
            </script>
            <?php
            exit();
        }
    }
    else
    {
        # set refresh group no
        $GroupNoDefault = SET_GROUP_NO();
        # show error
        ?>
        <script>
        $(document).ready(function () {
            $('#TableIdentitasKaryawan').find('tr:eq(1)').find('td:eq(2)').html("<?php echo $GroupNoDefault; ?>");
        });
        </script>
        <?php
        exit();
    }
}
else
{
    echo "Anda tidak mempunyai hak akses!";
}
?>
