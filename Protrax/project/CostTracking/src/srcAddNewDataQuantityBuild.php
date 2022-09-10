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
    $TitleResult = trim($_POST['TitleResult']);
    $TitleResult = str_replace("Season ","",$TitleResult);
    $TitleResult = str_replace(" <strong>","",$TitleResult);
    $TitleResult = str_replace("</strong>","",$TitleResult);
    $TitleResult = str_replace(" Category ","",$TitleResult);
    $TitleResult = str_replace(" Quote ","",$TitleResult);
    // $TitleResult = str_replace(".","",$TitleResult);
    $ArrTitleResult = explode(":",$TitleResult);
    $Season = trim($ArrTitleResult[1]);
    $Category = trim($ArrTitleResult[2]);
    $Quote = trim($ArrTitleResult[3]);
    $Division = htmlspecialchars(trim($_POST['Division']), ENT_QUOTES, "UTF-8");
    $Month = htmlspecialchars(trim($_POST['Month']), ENT_QUOTES, "UTF-8");
    // $Point = htmlspecialchars(trim($_POST['Point']), ENT_QUOTES, "UTF-8");
    $TargetQty = htmlspecialchars(trim($_POST['TargetQty']), ENT_QUOTES, "UTF-8");
    $ActualQty = htmlspecialchars(trim($_POST['ActualQty']), ENT_QUOTES, "UTF-8");
    $MonthName = date("F",mktime(0,0,0,$Month,1,date("Y")));
    # check data
    $Check = CHECK_QUANTITY_BUILD_POINTS($Division,$Season,$Quote,$Month,$linkMACHWebTrax);
    if(sqlsrv_num_rows($Check) == 0)
    {
        # check half
        if(substr($Season,-1) == "1")   # H1
        {
            if($Month == "1")   # bulan pertama
            {
                if((int)$TargetQty <= (int)$ActualQty)
                {
                    $Point = $TargetQty;
                }
                else
                {
                    $Point = "0";
                }
                # insert data
                $Result = INSERT_NEW_QUANTITY_BUILD_POINTS($Division,$Season,$Quote,$Month,$Point,$TargetQty,$ActualQty,$linkMACHWebTrax);
                if($Result == "TRUE")
                {
                    $Point2 = sprintf('%.0f',floatval(trim($Point)));
                    $TargetQty2 = sprintf('%.0f',floatval(trim($TargetQty)));
                    $ActualQty2 = sprintf('%.0f',floatval(trim($ActualQty)));

                    ?>
                    <script>
                        $(document).ready(function () {
                            var dt = $("#TableViewData").DataTable();
                            var newrow = $('<tr><td class="text-center">-</td><td class="text-center"><?php echo $Season; ?></td><td class="text-start"><?php echo $Division; ?></td><td class="text-start"><?php echo $MonthName; ?></td><td class="text-center"><?php echo $Point2; ?></td><td class="text-center"><?php echo $TargetQty2; ?></td><td class="text-center"><?php echo $ActualQty2; ?></td><td class="text-center">-</td></tr>');
                            dt.row.add(newrow).draw(false);                
                            $("#InputPoint").val("");
                            $("#InputTargetQty").val("");
                            $("#InputActualQty").val("");
                            $("#TempProcess").html("");
                        });
                    </script>
                    <?php 
                }
                else
                {
                    ?>
                    <script>
                        $(document).ready(function () {
                            alert("Error! Please try again later!");
                        });
                    </script>
                    <?php
                }
            }
            else
            {
                $BolCheckMonth = TRUE;
                for ($i=1; $i < $Month ; $i++) # check bulan sebelumnya
                { 
                    $Total = CHECK_ROWS_PER_MONTH($Division,$Season,$Quote,$i,$linkMACHWebTrax);
                    if($Total == 0)
                    {
                        $BolCheckMonth = FALSE;
                        break;
                    }
                }
                if($BolCheckMonth == FALSE)
                {
                    ?>
                    <script>
                        $(document).ready(function () {
                            alert("Bulan sebelumnya masih kosong!");
                            $("#TempProcess").html("");
                            $("#InputMonth").focus();
                        });
                    </script>
                    <?php
                }
                else
                {
                    $QTempTotal = GET_COUNT_TOTAL_TARGET_AND_ACTUAL_QTY($Division,$Season,$Quote,"1",$Month,$linkMACHWebTrax);
                    $RTempTotal = sqlsrv_fetch_array($QTempTotal);
                    $TotalTargetQty = trim($RTempTotal['TotalTargetQty']);
                    $TotalActualQty = trim($RTempTotal['TotalActualQty']);
                    $NewTotalTargetQty = (int)$TotalTargetQty + (int)$TargetQty;
                    $NewTotalActualQty = (int)$TotalActualQty + (int)$ActualQty;
                    if((int)$NewTotalTargetQty <= (int)$NewTotalActualQty)
                    {
                        $Point = $TargetQty;
                    }
                    else
                    {
                        $Point = "0";
                    }
                    # check data
                    $Check = CHECK_QUANTITY_BUILD_POINTS($Division,$Season,$Quote,$Month,$linkMACHWebTrax);
                    if(sqlsrv_num_rows($Check) == 0)
                    {
                        # insert data
                        $Result = INSERT_NEW_QUANTITY_BUILD_POINTS($Division,$Season,$Quote,$Month,$Point,$TargetQty,$ActualQty,$linkMACHWebTrax);
                        if($Result == "TRUE")
                        {
                            $Point2 = sprintf('%.0f',floatval(trim($Point)));
                            $TargetQty2 = sprintf('%.0f',floatval(trim($TargetQty)));
                            $ActualQty2 = sprintf('%.0f',floatval(trim($ActualQty)));

                            ?>
                            <script>
                                $(document).ready(function () {
                                    var dt = $("#TableViewData").DataTable();
                                    var newrow = $('<tr><td class="text-center">-</td><td class="text-center"><?php echo $Season; ?></td><td class="text-start"><?php echo $Division; ?></td><td class="text-start"><?php echo $MonthName; ?></td><td class="text-center"><?php echo $Point2; ?></td><td class="text-center"><?php echo $TargetQty2; ?></td><td class="text-center"><?php echo $ActualQty2; ?></td><td class="text-center">-</td></tr>');
                                    dt.row.add(newrow).draw(false);                
                                    $("#InputPoint").val("");
                                    $("#InputTargetQty").val("");
                                    $("#InputActualQty").val("");
                                    $("#TempProcess").html("");
                                });
                            </script>
                            <?php 
                        }
                        else
                        {
                            ?>
                            <script>
                                $(document).ready(function () {
                                    alert("Error! Please try again later!");
                                });
                            </script>
                            <?php
                        }
                    }
                    else
                    {
                        # info data sdh ada seblmnya
                        ?>
                            <script>
                                $(document).ready(function () {
                                    alert("Data saved before!");
                                    $("#InputPoint").val("");
                                    $("#InputTargetQty").val("");
                                    $("#InputActualQty").val("");
                                    $("#TempProcess").html("");
                                    $("#InputDivision").focus();
                                });
                            </script>
                        <?php
                    }
                }
            }
        }
        else 
        {
            if($Month == "7")   # bulan pertama
            {
                if((int)$TargetQty <= (int)$ActualQty)
                {
                    $Point = $TargetQty;
                }
                else
                {
                    $Point = "0";
                }
                # insert data
                $Result = INSERT_NEW_QUANTITY_BUILD_POINTS($Division,$Season,$Quote,$Month,$Point,$TargetQty,$ActualQty,$linkMACHWebTrax);
                if($Result == "TRUE")
                {
                    $Point2 = sprintf('%.0f',floatval(trim($Point)));
                    $TargetQty2 = sprintf('%.0f',floatval(trim($TargetQty)));
                    $ActualQty2 = sprintf('%.0f',floatval(trim($ActualQty)));              

                    ?>
                    <script>
                        $(document).ready(function () {
                            var dt = $("#TableViewData").DataTable();
                            var newrow = $('<tr><td class="text-center">-</td><td class="text-center"><?php echo $Season; ?></td><td class="text-start"><?php echo $Division; ?></td><td class="text-start"><?php echo $MonthName; ?></td><td class="text-center"><?php echo $Point2; ?></td><td class="text-center"><?php echo $TargetQty2; ?></td><td class="text-center"><?php echo $ActualQty2; ?></td><td class="text-center">-</td></tr>');
                            dt.row.add(newrow).draw(false);                
                            $("#InputPoint").val("");
                            $("#InputTargetQty").val("");
                            $("#InputActualQty").val("");
                        });
                    </script>
                    <?php 
                }
                else
                {
                    ?>
                    <script>
                        $(document).ready(function () {
                            alert("Error! Please try again later!");
                        });
                    </script>
                    <?php
                }
            }
            else
            {
                $BolCheckMonth = TRUE;
                for ($i=7; $i < $Month; $i++) # check bulan sebelumnya
                { 
                    $Total = CHECK_ROWS_PER_MONTH($Division,$Season,$Quote,$i,$linkMACHWebTrax);
                    if($Total == 0)
                    {
                        $BolCheckMonth = FALSE;
                        break;
                    }
                }
                if($BolCheckMonth == FALSE)
                {
                    ?>
                    <script>
                        $(document).ready(function () {
                            alert("Bulan sebelumnya masih kosong!");
                            $("#TempProcess").html("");
                            $("#InputMonth").focus();
                        });
                    </script>
                    <?php
                }
                else
                {
                    $QTempTotal = GET_COUNT_TOTAL_TARGET_AND_ACTUAL_QTY($Division,$Season,$Quote,"1",$Month,$linkMACHWebTrax);
                    $RTempTotal = sqlsrv_fetch_array($QTempTotal);
                    $TotalTargetQty = trim($RTempTotal['TotalTargetQty']);
                    $TotalActualQty = trim($RTempTotal['TotalActualQty']);
                    $NewTotalTargetQty = (int)$TotalTargetQty + (int)$TargetQty;
                    $NewTotalActualQty = (int)$TotalActualQty + (int)$ActualQty;
                    if((int)$NewTotalTargetQty <= (int)$NewTotalActualQty)
                    {
                        $Point = $TargetQty;
                    }
                    else
                    {
                        $Point = "0";
                    }
                    # check data
                    $Check = CHECK_QUANTITY_BUILD_POINTS($Division,$Season,$Quote,$Month,$linkMACHWebTrax);
                    if(sqlsrv_num_rows($Check) == 0)
                    {
                        # insert data
                        $Result = INSERT_NEW_QUANTITY_BUILD_POINTS($Division,$Season,$Quote,$Month,$Point,$TargetQty,$ActualQty,$linkMACHWebTrax);
                        if($Result == "TRUE")
                        {
                            $Point2 = sprintf('%.0f',floatval(trim($Point)));
                            $TargetQty2 = sprintf('%.0f',floatval(trim($TargetQty)));
                            $ActualQty2 = sprintf('%.0f',floatval(trim($ActualQty)));
                            ?>
                            <script>
                                $(document).ready(function () {
                                    var dt = $("#TableViewData").DataTable();
                                    var newrow = $('<tr><td class="text-center">-</td><td class="text-center"><?php echo $Season; ?></td><td class="text-start"><?php echo $Division; ?></td><td class="text-start"><?php echo $MonthName; ?></td><td class="text-center"><?php echo $Point2; ?></td><td class="text-center"><?php echo $TargetQty2; ?></td><td class="text-center"><?php echo $ActualQty2; ?></td><td class="text-center">-</td></tr>');
                                    dt.row.add(newrow).draw(false);                
                                    $("#InputPoint").val("");
                                    $("#InputTargetQty").val("");
                                    $("#InputActualQty").val("");
                                    $("#TempProcess").html("");
                                });
                            </script>
                            <?php
                        }
                        else
                        {
                            ?>
                            <script>
                                $(document).ready(function () {
                                    alert("Error! Please try again later!");
                                });
                            </script>
                            <?php
                        }
                    }
                    else
                    {
                        # info data sdh ada seblmnya
                        ?>
                            <script>
                                $(document).ready(function () {
                                    alert("Data saved before!");
                                    $("#InputPoint").val("");
                                    $("#InputTargetQty").val("");
                                    $("#InputActualQty").val("");
                                    $("#TempProcess").html("");
                                    $("#InputDivision").focus();
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
        # info data sdh ada seblmnya
        ?>
            <script>
                $(document).ready(function () {
                    alert("Data saved before!");
                    $("#InputPoint").val("");
                    $("#InputTargetQty").val("");
                    $("#InputActualQty").val("");
                    $("#TempProcess").html("");
                    $("#InputDivision").focus();
                });
            </script>
        <?php
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
