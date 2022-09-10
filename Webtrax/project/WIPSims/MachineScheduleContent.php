<?php 
session_start();
require_once("../../src/Modules/ModuleLogin.php");
require_once("Modules/ModuleMachinePlan.php");



if($_SERVER['REQUEST_METHOD'] == "POST")
{   
    $ValMachineName = htmlspecialchars(trim($_POST['ValMachine']), ENT_QUOTES, "UTF-8");
    $ValFloat = htmlspecialchars(trim($_POST['ValFloat']), ENT_QUOTES, "UTF-8");
    $ValFloat = base64_decode(base64_decode($ValFloat));
    $ArrFloat = explode("#",$ValFloat);
    $ValQuoteName = $ArrFloat[0];
    $ValLoc = $ArrFloat[1];
    if($ValLoc == 'PSL'){$Kota = "SALATIGA";}
    else {$Kota = "SEMARANG";}
?>
<style>
    .cards {
    padding: 20px;
    background:#FFFFFf;
    width: 100%; 
    margin-bottom: 30px; 
    box-shadow: 0px 1px 3px #808080
    }
    .sticky {position: sticky; top: 0; width: 100%;z-index:100;}
    .header {padding: 5px 10px;background:#FFFFFF;color: #555;box-shadow: 0px 3px 5px #808080;}
</style>
<script>
window.onscroll = function() {myFunction()};

var header = document.getElementById("myHeader");
var sticky = header.offsetTop;

function myFunction() {
  if (window.pageYOffset > sticky) {
    header.classList.add("sticky");
  } else {
    header.classList.remove("sticky");
  }
}
</script>
<br></br>
<div class="col-md-12" id="myHeader">
    <div class = "cards">
        Machine Usage : <strong><?php echo $ValMachineName; ?></strong>  .Location : <strong><?php echo $Kota; ?></strong>  . Quote : <strong><?php echo $ValQuoteName; ?></strong>
    </div>
</div>
<div class="col-md-12">
    <div class = "cards">
        <div><h5><strong>Ready To Process</strong></h5></div>
        <div class="table-responsive">
            <table class="table table-bordered table-hover" id="TableDiRak">
                <thead class="theadCustom">
                    <tr>
                        <th>Barcode Material</th>
                        <th>Work Order</th>
                        <th>PPIC</th>
                        <th>Weight (KG)</th>
                        <th>Transact Date</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    $Data1 = GET_MATERIAL_BY_MACHINE("Di Rak",$ValQuoteName,$ValMachineName,$ValLoc,$linkMACHWebTrax);
                    while($Data1Res = sqlsrv_fetch_array($Data1))
                    {
                        $BCMaterial = trim($Data1Res['Idx']);
                        $WO = trim($Data1Res['WO']);
                        $Creator = trim($Data1Res['Creator']);
                        $OutFromWarehouse = trim($Data1Res['OutFromWarehouse']);
                        $DateBeforeCNC = trim($Data1Res['DateBeforeCNC']);
                        $cekData = CHECK_ON_BARCODE_PART($BCMaterial,$linkMACHWebTrax);
                        $Row = sqlsrv_num_rows($cekData);
                        if($Row > 0){}
                        else
                        {
                            $OutFromWarehouse = number_format((float)$OutFromWarehouse,2,'.',',');
                            ?>
                                <tr>
                                    <td class="text-center"><?php echo $BCMaterial; ?></td>
                                    <td><?php echo $WO; ?></td>
                                    <td><?php echo $Creator; ?></td>
                                    <td class="text-right"><?php echo $OutFromWarehouse; ?></td>
                                    <td class="text-center"><?php echo $DateBeforeCNC; ?></td>
                                </tr>

                            <?php
                        }
                    }
                    
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div> 
<?php
?>
<div class="col-md-12">
    <div class = "cards">
        <div><h5><strong>Material Cut by Warehouse</strong></h5></div>
        <div class="table-responsive">
            <table class="table table-bordered table-hover" id="TableDiPotong">
                <thead class="theadCustom">
                    <tr>
                        <th>Barcode Material</th>
                        <th>Work Order</th>
                        <th>PPIC</th>
                        <th>Date Print</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    $Data1 = GET_MATERIAL_BY_MACHINE("Di Cetak",$ValQuoteName,$ValMachineName,$ValLoc,$linkMACHWebTrax);
                    while($Data1Res = sqlsrv_fetch_array($Data1))
                    {
                        $BCMaterial = trim($Data1Res['Idx']);
                        $WO = trim($Data1Res['WO']);
                        $Creator = trim($Data1Res['Creator']);
                        $DatePrint = trim($Data1Res['DatePrint']);
                        $cekData = CHECK_ON_BARCODE_PART($BCMaterial,$linkMACHWebTrax);
                        $Row = sqlsrv_num_rows($cekData);
                        if($Row > 0){}
                        else
                        {
                            ?>
                                <tr>
                                    <td class="text-center"><?php echo $BCMaterial; ?></td>
                                    <td><?php echo $WO; ?></td>
                                    <td><?php echo $Creator; ?></td>
                                    <td class="text-center"><?php echo $DatePrint; ?></td>
                                </tr>

                            <?php
                        }
                    }
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div> 
<div class="col-md-12">
    <div class = "cards">   
        <div><h5><strong>Wait to Print</strong></h5></div>
        <div class="table-responsive">
            <table class="table table-bordered table-hover" id="TableNotIssued">
                <thead class="theadCustom">
                    <tr>
                        <th>Barcode Material</th>
                        <th>Work Order</th>
                        <th>PPIC</th>
                        <th>Date Create</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                $Data1 = GET_MATERIAL_BY_MACHINE("",$ValQuoteName,$ValMachineName,$ValLoc,$linkMACHWebTrax);
                while($Data1Res = sqlsrv_fetch_array($Data1))
                {
                    $BCMaterial = trim($Data1Res['Idx']);
                    $WO = trim($Data1Res['WO']);
                    $Creator = trim($Data1Res['Creator']);
                    $DateCreate = trim($Data1Res['DateCreate']);
                    $cekData = CHECK_ON_BARCODE_PART($BCMaterial,$linkMACHWebTrax);
                    $Row = sqlsrv_num_rows($cekData);
                    if($Row > 0){}
                    else
                    {
                        ?>
                            <tr>
                                <td class="text-center"><?php echo $BCMaterial; ?></td>
                                <td><?php echo $WO; ?></td>
                                <td><?php echo $Creator; ?></td>
                                <td class="text-center"><?php echo $DateCreate; ?></td>
                            </tr>

                        <?php
                    }
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php
}
?>
<script>
$('#TableDiRak').DataTable( {
    "iDisplayLength": 10,
    "aLengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
    scrollCollapse: true,
    autoWidth: true
});
$('#TableDiPotong').DataTable( {
    "iDisplayLength": 10,
    "aLengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
    scrollCollapse: true,
    autoWidth: true
});
$('#TableNotIssued').DataTable( {
    "iDisplayLength": 10,
    "aLengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
    scrollCollapse: true,
    autoWidth: true
});
</script>