<?php
session_start();
require_once("../../../ConfigDB.php");
require_once("../../../src/Modules/ModuleLogin.php");
require_once("../Modules/ModuleQuantityBuild.php");

date_default_timezone_set("Asia/Jakarta");
$Time = date("Y-m-d H:i:s");
 /*
if(!session_is_registered("UIDProTrax"))
{
    ?>
    <script language="javascript">
        window.location.replace("https://protrax.formulatrix.com/");
    </script>
    <?php
    exit();
}
*/
if($_SERVER['REQUEST_METHOD'] == "POST")
{
    $DataID = htmlspecialchars(trim($_POST['DataID']), ENT_QUOTES, "UTF-8");
    $DataID = str_replace("IDXData","",base64_decode(base64_decode($DataID)));
    $DataID2  = htmlspecialchars(trim($_POST['DataID']), ENT_QUOTES, "UTF-8");        
    # data id yg diupdate
    $QData = GET_DETAIL_QUANTITY_BUILD_BY_ID($DataID,$linkMACHWebTrax);
    $RData = sqlsrv_fetch_array($QData);
    $DtDivision = trim($RData['Division']);
    $DtHalfClosed = trim($RData['HalfClosed']);
    $DtQuote = trim($RData['Quote']);
    $DtMonth = trim($RData['Month']);
    $NoHalf = substr($DtHalfClosed,-1);
    $BolAccess = FALSE;

    if(($DtMonth == 6) && ($DtMonth = 12))
    {
        $BolAccess = TRUE;
    }
    else
    {
        $TotalNextRow = COUNT_NEXT_MONTH($DtDivision,$DtHalfClosed,$DtQuote,$DtMonth,$linkMACHWebTrax);
        if($TotalNextRow > 0)
        {
            $BolAccess = FALSE;
        }
        else
        {
            $BolAccess = TRUE;
        }
    }
    
    if($BolAccess == FALSE)
    {
        ?>
            <script>
                $(document).ready(function () {
                    alert("Masih ada data di bulan berikutnya!");
                    $('#TemporarySpace').html("");
                });
            </script>
        <?php
    }
    else
    {   
        # delete data
        DELETE_QUANTITY_BUILD_BY_ID($DataID,$linkMACHWebTrax);
        ?>
            <script>
                $(document).ready(function () {
                    var XX = $("#TableViewData tr .PointerList[data-datatoken='<?php echo $DataID2; ?>']").closest('tr');
                    $("#TableViewData").DataTable().row(XX).remove().draw();
                    $('#TemporarySpace').html("");
                });
            </script>
        <?php        
        # penentuan termasuk H1 / H2
        if($NoHalf == "1") # H1
        {
            # list qty build
            $QList = LIST_QTY_BUILD_POINT_PER_HALF($DtDivision,$DtHalfClosed,$DtQuote,$linkMACHWebTrax);
            while($RList = sqlsrv_fetch_array($QList))
            {
                for ($i=1; $i < 7 ; $i++)
                {
                    if($i == $RList['Month'])
                    {
                        if($i == 1)
                        {
                            $QDataTemp = GET_DETAIL_QUANTITY_BUILD_BY_ID(trim($RList['Idx']),$linkMACHWebTrax);
                            $RDataTemp = sqlsrv_fetch_array($QDataTemp);
                            $PointsTemp = sprintf('%.0f',floatval(trim($RDataTemp['Points'])));
                            $TargetQtyTemp = sprintf('%.0f',floatval(trim($RDataTemp['TargetQty'])));
                            $ActualQtyTemp = sprintf('%.0f',floatval(trim($RDataTemp['ActualQty'])));
                            $ValTokenTemp = base64_encode(base64_encode("IDXData".trim($RDataTemp['Idx'])));
                            ?>
                                <script>
                                    $(document).ready(function () {
                                        var $row = $("#TableViewData tr .PointerList[data-datatoken='<?php echo $ValTokenTemp; ?>']").closest('tr');
                                        $row.find("td:eq(4)").html('<?php echo $PointsTemp; ?>');   
                                        $row.find("td:eq(5)").html('<?php echo $TargetQtyTemp; ?>');   
                                        $row.find("td:eq(6)").html('<?php echo $ActualQtyTemp; ?>');
                                    });
                                </script>
                            <?php
                        }
                        else
                        {
                            # data detail id
                            $QDataTemp1 = GET_DETAIL_QUANTITY_BUILD_BY_ID(trim($RList['Idx']),$linkMACHWebTrax);
                            $RDataTemp1 = sqlsrv_fetch_array($QDataTemp1);
                            $IdxTemp1 = trim($RDataTemp1['Idx']);
                            $TargetQtyTemp1 = trim($RDataTemp1['TargetQty']);
                            $ActualQtyTemp1 = trim($RDataTemp1['ActualQty']);
                            # data total 
                            $QDataTemp2 = GET_COUNT_TOTAL_TARGET_AND_ACTUAL_QTY($DtDivision,$DtHalfClosed,$DtQuote,"7",$i,$linkMACHWebTrax);
                            $RDataTemp2 = sqlsrv_fetch_array($QDataTemp2);
                            $TotalTargetQtyTemp2 = trim($RDataTemp2['TotalTargetQty']);
                            $TotalActualQtyTemp2 = trim($RDataTemp2['TotalActualQty']);
                            if((int)$TotalTargetQtyTemp2 <= (int)$TotalActualQtyTemp2)
                            {
                                $InputPointTemp2 = $TargetQtyTemp1;
                            }
                            else
                            {
                                $InputPointTemp2 = "0";
                            }
                            # update data
                            $ResBolTemp = UPDATE_QUANTITY_BUILD_BY_ID($IdxTemp1,$InputPointTemp2,$TargetQtyTemp1,$ActualQtyTemp1,$linkMACHWebTrax);
                            $PointsTemp = sprintf('%.0f',floatval(trim($InputPointTemp2)));
                            $TargetQtyTemp = sprintf('%.0f',floatval(trim($TargetQtyTemp1)));
                            $ActualQtyTemp = sprintf('%.0f',floatval(trim($ActualQtyTemp1)));
                            $ValTokenTemp = base64_encode(base64_encode("IDXData".trim($IdxTemp1)));
                            ?>
                                <script>
                                    $(document).ready(function () {
                                        var $row = $("#TableViewData tr .PointerList[data-datatoken='<?php echo $ValTokenTemp; ?>']").closest('tr');
                                        $row.find("td:eq(4)").html('<?php echo $PointsTemp; ?>');   
                                        $row.find("td:eq(5)").html('<?php echo $TargetQtyTemp; ?>');   
                                        $row.find("td:eq(6)").html('<?php echo $ActualQtyTemp; ?>');
                                    });
                                </script>
                            <?php
                        }
                    }
                } 
            }
        }
        else
        {
            # list qty build
            $QList = LIST_QTY_BUILD_POINT_PER_HALF($DtDivision,$DtHalfClosed,$DtQuote,$linkMACHWebTrax);
            while($RList = sqlsrv_fetch_array($QList))
            {
                for ($i=7; $i < 13 ; $i++)
                {
                    if($i == $RList['Month'])
                    {
                        if($i == 7)
                        {
                            $QDataTemp = GET_DETAIL_QUANTITY_BUILD_BY_ID(trim($RList['Idx']),$linkMACHWebTrax);
                            $RDataTemp = sqlsrv_fetch_array($QDataTemp);
                            $PointsTemp = sprintf('%.0f',floatval(trim($RDataTemp['Points'])));
                            $TargetQtyTemp = sprintf('%.0f',floatval(trim($RDataTemp['TargetQty'])));
                            $ActualQtyTemp = sprintf('%.0f',floatval(trim($RDataTemp['ActualQty'])));
                            $ValTokenTemp = base64_encode(base64_encode("IDXData".trim($RDataTemp['Idx'])));
                            ?>
                                <script>
                                    $(document).ready(function () {
                                        var $row = $("#TableViewData tr .PointerList[data-datatoken='<?php echo $ValTokenTemp; ?>']").closest('tr');
                                        $row.find("td:eq(4)").html('<?php echo $PointsTemp; ?>');   
                                        $row.find("td:eq(5)").html('<?php echo $TargetQtyTemp; ?>');   
                                        $row.find("td:eq(6)").html('<?php echo $ActualQtyTemp; ?>');
                                    });
                                </script>
                            <?php
                        }
                        else
                        {
                            # data detail id
                            $QDataTemp1 = GET_DETAIL_QUANTITY_BUILD_BY_ID(trim($RList['Idx']),$linkMACHWebTrax);
                            $RDataTemp1 = sqlsrv_fetch_array($QDataTemp1);
                            $IdxTemp1 = trim($RDataTemp1['Idx']);
                            $TargetQtyTemp1 = trim($RDataTemp1['TargetQty']);
                            $ActualQtyTemp1 = trim($RDataTemp1['ActualQty']);
                            # data total 
                            $QDataTemp2 = GET_COUNT_TOTAL_TARGET_AND_ACTUAL_QTY($DtDivision,$DtHalfClosed,$DtQuote,"7",$i,$linkMACHWebTrax);
                            $RDataTemp2 = sqlsrv_fetch_array($QDataTemp2);
                            $TotalTargetQtyTemp2 = trim($RDataTemp2['TotalTargetQty']);
                            $TotalActualQtyTemp2 = trim($RDataTemp2['TotalActualQty']);
                            if((int)$TotalTargetQtyTemp2 <= (int)$TotalActualQtyTemp2)
                            {
                                $InputPointTemp2 = $TargetQtyTemp1;
                            }
                            else
                            {
                                $InputPointTemp2 = "0";
                            }
                            # update data
                            $ResBolTemp = UPDATE_QUANTITY_BUILD_BY_ID($IdxTemp1,$InputPointTemp2,$TargetQtyTemp1,$ActualQtyTemp1,$linkMACHWebTrax);
                            $PointsTemp = sprintf('%.0f',floatval(trim($InputPointTemp2)));
                            $TargetQtyTemp = sprintf('%.0f',floatval(trim($TargetQtyTemp1)));
                            $ActualQtyTemp = sprintf('%.0f',floatval(trim($ActualQtyTemp1)));
                            $ValTokenTemp = base64_encode(base64_encode("IDXData".trim($IdxTemp1)));
                            ?>
                                <script>
                                    $(document).ready(function () {
                                        var $row = $("#TableViewData tr .PointerList[data-datatoken='<?php echo $ValTokenTemp; ?>']").closest('tr');
                                        $row.find("td:eq(4)").html('<?php echo $PointsTemp; ?>');   
                                        $row.find("td:eq(5)").html('<?php echo $TargetQtyTemp; ?>');   
                                        $row.find("td:eq(6)").html('<?php echo $ActualQtyTemp; ?>');
                                    });
                                </script>
                            <?php
                        }
                    }
                } 
            }
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

