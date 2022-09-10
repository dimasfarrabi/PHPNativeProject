<?php
session_start();
require_once("../../src/Modules/ModuleLogin.php");
require_once("Modules/ModuleShippingChart.php");
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
    $EncData = htmlspecialchars(trim($_POST['EncData']), ENT_QUOTES, "UTF-8");
    $Data = base64_decode($EncData);
    $arr = explode("+",$Data);
    $Country = $arr[0];
    $Year = $arr[1];
    $CountryExp = $arr[2];
?>
<div class="col-md-12"><h4>Destination: <strong><?php echo $Country; ?></strong>.  Year: <strong><?php echo $Year; ?></strong>.</h4></div>
<div class="col-md-4">
    <h5>Company: <strong>PSL</strong>.</h5>
    <div class="table-responsive">
        <table class="table table-responsive table-bordered table-hover">
            <thead class="theadCustom">
                <tr>
                    <th class="text-left">Year</th>
                    <th class="text-right">Freight ($)</th>
                    <th class="text-right">Qty Shipment</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $totalFreightX = $TotalQtyX = 0;
            $datax = GET_DETAIL_DESTINATION($Year,$Country,$CountryExp,"PSL",$linkMACHWebTrax);
            while($resx=sqlsrv_fetch_array($datax))
            {
                $ValYear = trim($resx['DateYear']);
                $ValFreight = trim($resx['Freight']);
                $ValQty = trim($resx['Qty']);
                $totalFreightX = @($totalFreightX + $ValFreight);
                $TotalQtyX = @($TotalQtyX + $ValQty);
                if(trim($ValFreight) == ""){$ValFreight = "";} else {$ValFreight = number_format((float)$ValFreight, 2, '.', ',');}
                if(trim($ValQty) == ""){$ValQty = "";} else {$ValQty = number_format((float)$ValQty, 2, '.', ',');}
            ?>
            <tr>
                <td class="text-center"><?php echo $ValYear; ?></td>
                <td class="text-right"><?php echo $ValFreight; ?></td>
                <td class="text-right"><?php echo $ValQty; ?></td>
            </tr>
            <?php
            }
            $totalFreightX = number_format((float)$totalFreightX, 2, '.', ',');
            $TotalQtyX = number_format((float)$TotalQtyX, 2, '.', ',');
            ?>
            </tbody>
            <tfoot>
                <tr>
                    <td class="text-center"><strong>TOTAL</strong></td>
                    <td class="text-right"><strong><?php echo $totalFreightX; ?></strong></td>
                    <td class="text-right"><strong><?php echo $TotalQtyX; ?></strong></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
<div class="col-md-4">
    <h5>Company: <strong>FOR</strong>.</h5>
    <div class="table-responsive">
        <table class="table table-responsive table-bordered table-hover">
            <thead class="theadCustom">
                <tr>
                    <th class="text-left">Year</th>
                    <th class="text-right">Freight ($)</th>
                    <th class="text-right">Qty Shipment</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $totalFreightY = $TotalQtyY = 0;
            $dataY = GET_DETAIL_DESTINATION($Year,$Country,$CountryExp,"FOR",$linkMACHWebTrax);
            while($resY=sqlsrv_fetch_array($dataY))
            {
                $ValYear = trim($resY['DateYear']);
                $ValFreight = trim($resY['Freight']);
                $ValQty = trim($resY['Qty']);
                $totalFreightY = @($totalFreightY + $ValFreight);
                $TotalQtyY = @($TotalQtyY + $ValQty);
                if(trim($ValFreight) == ""){$ValFreight = "";} else {$ValFreight = number_format((float)$ValFreight, 2, '.', ',');}
                if(trim($ValQty) == ""){$ValQty = "";} else {$ValQty = number_format((float)$ValQty, 2, '.', ',');}
            ?>
            <tr>
                <td class="text-center"><?php echo $ValYear; ?></td>
                <td class="text-right"><?php echo $ValFreight; ?></td>
                <td class="text-right"><?php echo $ValQty; ?></td>
            </tr>
            <?php
            }
            $totalFreightY = number_format((float)$totalFreightY, 2, '.', ',');
            $TotalQtyY = number_format((float)$TotalQtyY, 2, '.', ',');
            ?>
            </tbody>
            <tfoot>
                <tr>
                    <td class="text-center"><strong>TOTAL</strong></td>
                    <td class="text-right"><strong><?php echo $totalFreightY; ?></strong></td>
                    <td class="text-right"><strong><?php echo $TotalQtyY; ?></strong></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
<div class="col-md-4">
    <h5>Company: <strong>PSM</strong>.</h5>
    <div class="table-responsive">
        <table class="table table-responsive table-bordered table-hover">
            <thead class="theadCustom">
                <tr>
                    <th class="text-left">Year</th>
                    <th class="text-right">Freight ($)</th>
                    <th class="text-right">Qty Shipment</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $totalFreightz = $TotalQtyz = 0;
            $dataz = GET_DETAIL_DESTINATION($Year,$Country,$CountryExp,"PSM",$linkMACHWebTrax);
            while($resz=sqlsrv_fetch_array($dataz))
            {
                $ValYear = trim($resz['DateYear']);
                $ValFreight = trim($resz['Freight']);
                $ValQty = trim($resz['Qty']);
                $totalFreightz = @($totalFreightz + $ValFreight);
                $TotalQtyz = @($TotalQtyz + $ValQty);
                if(trim($ValFreight) == ""){$ValFreight = "";} else {$ValFreight = number_format((float)$ValFreight, 2, '.', ',');}
                if(trim($ValQty) == ""){$ValQty = "";} else {$ValQty = number_format((float)$ValQty, 2, '.', ',');}
            ?>
            <tr>
                <td class="text-center"><?php echo $ValYear; ?></td>
                <td class="text-right"><?php echo $ValFreight; ?></td>
                <td class="text-right"><?php echo $ValQty; ?></td>
            </tr>
            <?php
            }
            $totalFreightz = number_format((float)$totalFreightz, 2, '.', ',');
            $TotalQtyz = number_format((float)$TotalQtyz, 2, '.', ',');
            ?>
            </tbody>
            <tfoot>
                <tr>
                    <td class="text-center"><strong>TOTAL</strong></td>
                    <td class="text-right"><strong><?php echo $totalFreightz; ?></strong></td>
                    <td class="text-right"><strong><?php echo $TotalQtyz; ?></strong></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
<?php
}
?>