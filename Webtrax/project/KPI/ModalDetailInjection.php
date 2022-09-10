<?php
require_once("../../src/Modules/ModuleLogin.php");
require_once("Module/ModuleKPI.php"); 
date_default_timezone_set("Asia/Jakarta");

if($_SERVER['REQUEST_METHOD'] == "POST")
{   
    $ValCodeDec = base64_decode(htmlspecialchars(trim($_POST['ValCode']), ENT_QUOTES, "UTF-8"));
    $Codec = explode("*",$ValCodeDec);
    $ValMonth = $Codec[0];
    $ValMonth2 = $Codec[1];
    // echo "$ValMonth >> $ValMonth2";
?>
<div class="col-md-12"><strong><h5><?php echo $ValMonth2; ?></strong></h5></div>
    <div class="col-md-12">
        <div class="table-responsive">
            <table class="table table-bordered table-hover" id="TableInjection">
                <thead class="theadCustom">
                    <tr>
                        <th class="text-center trowCustom">Barcode ID</th>
                        <th class="text-center trowCustom">WO</th>
                        <th class="text-center trowCustom">Start Injection</th>
                        <th class="text-center trowCustom">End Injection</th>
                        <th class="text-center trowCustom">Diff Day</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $Data = GET_DATA_DETAIL_KPI_INJECTION2($ValMonth,$linkMACHWebTrax);
                    while($DataRes = mssql_fetch_assoc($Data))
                    {
                        $Barcode_ID = trim($DataRes['Barcode_ID']);
                        $WO = trim($DataRes['WO']);
                        $InjectIn = trim($DataRes['InjectIn']);
                        $InjectOut = trim($DataRes['InjectOut']);
                        $Diff = trim($DataRes['Diff']);
                    ?>
                    <tr>
                        <td><?php echo $Barcode_ID; ?></td>
                        <td><?php echo $WO; ?></td>
                        <td><?php echo $InjectIn; ?></td>
                        <td><?php echo $InjectOut; ?></td>
                        <td><?php echo $Diff; ?></td>
                    </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
<?php
}
?>