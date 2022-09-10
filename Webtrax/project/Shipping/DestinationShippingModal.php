<?php
session_start();
require_once("../../src/Modules/ModuleLogin.php");
require_once("Modules/ModuleShippingChart.php");


if($_SERVER['REQUEST_METHOD'] == "POST")
{   
    $ValCode = htmlspecialchars(trim($_POST['ValCode']), ENT_QUOTES, "UTF-8");
    $Code = base64_decode($ValCode);
    $arr = explode(":",$Code);
    $MonthYear = $arr[0];
    $Company = $arr[1];
    $Country = $arr[2];
    $MonthTitle = $arr[3];
    $CountryExp = $arr[4];
//  echo "$MonthYear >> $Company >> $Country >> $CountryExp";
?>
<h6>Period : <strong><?php echo $MonthTitle; ?></strong></h6>
<h6>From Company : <strong><?php echo $Company; ?></strong></h6>
<h6>To Subject : <strong><?php echo $Country; ?></strong></h6>
<?php
    if($Country != 'Others')
    {
    ?>
    <div class="table-responsive">
        <table id="ModalShippingTable" class="table table-responsive table-bordered table-hover">
            <thead class="theadCustom">
                <tr>
                    <th class="text-center">No</th>
                    <th class="text-center">Tracking ID</th>
                    <th class="text-center">Date Shipped</th>
                    <th class="text-center">Total Weight</th>
                    <th class="text-center">Final Freight</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no=1;
                $TotalWeight = $TotalFreight = 0; 
                $data = GET_DETAIL_SHIPPING_PER_MONTH($MonthYear,$Company,$Country,$CountryExp,$linkMACHWebTrax);
                while($res=sqlsrv_fetch_array($data))
                {
                    $TrackingID = trim($res['TrackingID']);
                    $DateShipped = trim($res['ShipDate']);
                    $Weight = trim($res['TotalWeight']);
                    $Freight = trim($res['FinalShippingCost']);
                    $TotalWeight = $TotalWeight + $Weight;
                    $TotalFreight = $TotalFreight + $Freight;
                    $Weight = number_format((float)$Weight, 2, '.', ',');
                    $Freight = number_format((float)$Freight, 2, '.', ',');
                ?>
                <tr>
                    <td class="text-center"><?php echo $no; ?></td>
                    <td class="text-left"><?php echo $TrackingID; ?></td>
                    <td class="text-center"><?php echo $DateShipped; ?></td>
                    <td class="text-right"><?php echo $Weight; ?></td>
                    <td class="text-right"><?php echo $Freight; ?></td>
                </tr>
                <?php
                $no++;
                }
                $TotalWeight = number_format((float)$TotalWeight, 2, '.', ',');
                $TotalFreight = number_format((float)$TotalFreight, 2, '.', ',');
                ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" class="text-center"><strong>Total</strong></td>
                    <td class="text-right"><strong><?php echo $TotalWeight; ?></strong></td>
                    <td class="text-right"><strong><?php echo $TotalFreight; ?></strong></td>
                </tr>
            </tfoot>
        </table>
    </div>
    <?php
    }
    else
    {
    ?>
    <div class="table-responsive">
        <table id="ModalShippingTable" class="table table-responsive table-bordered table-hover">
            <thead class="theadCustom">
                <tr>
                    <th class="text-center">No</th>
                    <th class="text-center">Tracking ID</th>
                    <th class="text-center">Date Shipped</th>
                    <th class="text-center">Total Weight</th>
                    <th class="text-center">Final Freight</th>
                    <th class="text-center">Destination</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no=1; 
                $TotalWeight = $TotalFreight = 0; 
                $data = GET_DETAIL_SHIPPING_PER_MONTH($MonthYear,$Company,$Country,$CountryExp,$linkMACHWebTrax);
                while($res=sqlsrv_fetch_array($data))
                {
                    $TrackingID = trim($res['TrackingID']);
                    $DateShipped = trim($res['ShipDate']);
                    $Weight = trim($res['TotalWeight']);
                    $Freight = trim($res['FinalShippingCost']);
                    $Subject = trim($res['Subject']);
                    $TotalWeight = $TotalWeight + $Weight;
                    $TotalFreight = $TotalFreight + $Freight;
                    $Weight = number_format((float)$Weight, 2, '.', ',');
                    $Freight = number_format((float)$Freight, 2, '.', ',');
                ?>
                <tr>
                    <td class="text-center"><?php echo $no; ?></td>
                    <td class="text-left"><?php echo $TrackingID; ?></td>
                    <td class="text-center"><?php echo $DateShipped; ?></td>
                    <td class="text-right"><?php echo $Weight; ?></td>
                    <td class="text-right"><?php echo $Freight; ?></td>
                    <td class="text-left"><?php echo $Subject; ?></td>
                </tr>
                <?php
                $no++;
                }
                $TotalWeight = number_format((float)$TotalWeight, 2, '.', ',');
                $TotalFreight = number_format((float)$TotalFreight, 2, '.', ',');
                ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" class="text-center"><strong>Total</strong></td>
                    <td class="text-right"><strong><?php echo $TotalWeight; ?></strong></td>
                    <td class="text-right"><strong><?php echo $TotalFreight; ?></strong></td>
                    <td class="text-right"></td>
                </tr>
            </tfoot>
        </table>
    </div>
    <?php
    }
}
?>