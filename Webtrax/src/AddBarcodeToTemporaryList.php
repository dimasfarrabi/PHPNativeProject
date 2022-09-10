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

if($_SERVER['REQUEST_METHOD'] == "POST")
{
    $ValBarcode = htmlspecialchars(trim($_POST['ValBarcode']), ENT_QUOTES, "UTF-8");
    $ValGroupNo = htmlspecialchars(trim($_POST['ValGroupNo']), ENT_QUOTES, "UTF-8");
    $ValActivity = htmlspecialchars(trim($_POST['ValActivity']), ENT_QUOTES, "UTF-8");
    $ValSubActivity = htmlspecialchars(trim($_POST['ValSubActivity']), ENT_QUOTES, "UTF-8");
    # check status wo berdasarkan barcode
    $CountData = COUNT_DATA_WO_BY_ID($ValBarcode,$linkMACHWebTrax);
    if(trim($CountData) != "" && trim($CountData) != "0")
    {
        $QDataWO = GET_WO_MAPPING_DETAIL_FOR_TIMETRACKING($ValBarcode,$linkMACHWebTrax);
        if(mssql_num_rows($QDataWO) != "0")
        {
            $RDataWO = mssql_fetch_assoc($QDataWO);            
            $DataWOMappingIdx = trim($RDataWO['Idx']);
            $DataWO = trim($RDataWO['WOChild']);
            $DataProductName = trim($RDataWO['Product']);
            $DataOrderType = trim($RDataWO['OrderType']);
            $DataExpenseAllocationWO = trim($RDataWO['ExpenseAllocation']);
            ?>
            <script>
            $(document).ready(function () {
                $('#TableIdentitasProduk').find('tr:eq(0)').find('td:eq(2)').html("<?php echo $ValBarcode; ?>");
                $('#TableIdentitasProduk').find('tr:eq(1)').find('td:eq(2)').html("<?php echo $DataWOMappingIdx; ?>");
                $('#TableIdentitasProduk').find('tr:eq(2)').find('td:eq(2)').html("<?php echo $DataWO; ?>");
                $('#TableIdentitasProduk').find('tr:eq(3)').find('td:eq(2)').html("<?php echo $DataProductName; ?>");
                $('#TableIdentitasProduk').find('tr:eq(4)').find('td:eq(2)').html("<?php echo $DataOrderType; ?>");
                $('#TableIdentitasProduk').find('tr:eq(5)').find('td:eq(2)').html("<?php echo $DataExpenseAllocationWO; ?>");
                $("#TableDataListBarcodeSementara").DataTable().row.add([
                    "<?php echo $FullName; ?>",
                    "<?php echo $DataWO; ?>",
                    "<?php echo $DataProductName; ?>",
                    "<?php echo $DataOrderType; ?>",
                    "<?php echo $DataExpenseAllocationWO; ?>",
                    "<?php echo $ValActivity; ?>",
                    "<?php echo $ValSubActivity; ?>",
                    "<?php echo $ValBarcode; ?>",
                    "<?php echo $ValGroupNo; ?>"
                ]).draw( false );
            });
            </script>
            <?php
        }
        else
        {
            ?>
            <script>
            $(document).ready(function () {
                $("#InfoNotes").val("Gagal menemukan WO !");  
            });
            </script>
            <?php
        }
    }
    else
    {
        ?>
        <script>
        $(document).ready(function () {
            $("#InfoNotes").val("Gagal menemukan WO !");  
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
