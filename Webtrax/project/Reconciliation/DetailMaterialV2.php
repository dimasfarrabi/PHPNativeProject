<?php
require_once("../../src/Modules/ModuleLogin.php");
require_once("Module/ModuleRecon.php"); 
date_default_timezone_set("Asia/Jakarta");


if($_SERVER['REQUEST_METHOD'] == "POST")
{   
    $ValCodeDec = base64_decode(htmlspecialchars(trim($_POST['ValCode']), ENT_QUOTES, "UTF-8"));
    $NewValCodeEnc = base64_encode($ValCodeDec);
    $ArrCodeDec = explode("*",$ValCodeDec);
    $PartNo = $ArrCodeDec[0];
    $Val2 = $ArrCodeDec[1];
    $Location = $ArrCodeDec[2];
    $PartDesc = $ArrCodeDec[3];
    // echo "$PartNo >> $Val2 >> $Val3";
    ?>
    <div><h5><strong>Warehouse IN<br>Part : <?php echo $PartNo; ?></strong></h5></div>
    <div class="table-responsive tableFixHead2">
        <table class="table table-bordered table-hover" id="">
            <thead class="theadCustom">
                <tr>
                    <th>Transact ID</th>
                    <th>TLI ID</th>
                    <th>Category</th>
                    <th>Qty Received</th>
                    <th>UOM</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $TotalQtyIn = 0;
                $UOM = "";
                $DataIn = GET_QTY("IN",$Val2,$Location,$PartNo,$linkMACHWebTrax);
                while($Resin=sqlsrv_fetch_array($DataIn))
                {
                    $TransactID = trim($Resin['TransactID']);
                    $TLI = trim($Resin['TBZ_ID']);
                    $Category = trim($Resin['CategoryUsage']);
                    $QtyReceived = trim($Resin['QtyReceived']);
                    $UOM = trim($Resin['TransactUOM']);
                    $TotalQtyIn = $TotalQtyIn + $QtyReceived;
                    $QtyReceived = number_format((float)$QtyReceived,2,'.',',');
                    ?>
                    <tr>
                        <td class="text-left"><?php echo $TransactID; ?></td>
                        <td class="text-left"><?php echo $TLI; ?></td>
                        <td class="text-left"><?php echo $Category; ?></td>
                        <td class="text-right"><?php echo $QtyReceived; ?></td>
                        <td class="text-left"><?php echo $UOM; ?></td>
                    </tr>
                    <?php
                }
                $TotalQtyIn = number_format((float)$TotalQtyIn,2,'.',',');
                ?>
            </tbody>
            <tfoot>
                <tr>
                    <td class="text-center" colspan="3"><strong>TOTAL</strong></td>
                    <td class="text-right"><strong><?php echo $TotalQtyIn; ?></strong></td>
                    <td class="text-left"><strong><?php echo $UOM; ?></strong></td>
                </tr>
            </tfoot>
        </table>
    </div>
    <div><h5><strong>Warehouse OUT<br>Part : <?php echo $PartNo; ?></strong></h5></div>
    <div class="table-responsive tableFixHead2">
        <table class="table table-bordered table-hover" id="">
            <thead class="theadCustom">
                <tr>
                    <th>Transact ID</th>
                    <th>Category</th>
                    <th>Qty Issued Out</th>
                    <th>UOM</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $TotalQtyOut = 0;
                $UOM = "";
                $DataIn = GET_QTY("OUT",$Val2,$Location,$PartNo,$linkMACHWebTrax);
                while($Resin=sqlsrv_fetch_array($DataIn))
                {
                    $TransactID = trim($Resin['TransactID']);
                    $Category = trim($Resin['CategoryUsage']);
                    $QtyUsage = trim($Resin['QtyUsage']);
                    $UOM = trim($Resin['TransactUOM']);
                    $TotalQtyOut = $TotalQtyOut + $QtyUsage;
                    $QtyUsage = number_format((float)$QtyUsage,2,'.',',');
                    ?>
                    <tr>
                        <td class="text-left"><?php echo $TransactID; ?></td>
                        <td class="text-left"><?php echo $Category; ?></td>
                        <td class="text-right"><?php echo $QtyUsage; ?></td>
                        <td class="text-left"><?php echo $UOM; ?></td>
                    </tr>
                    <?php
                }
                $TotalQtyOut = number_format((float)$TotalQtyOut,2,'.',',');
                ?>
            </tbody>
            <tfoot>
                <tr>
                    <td class="text-center" colspan="2"><strong>TOTAL</strong></td>
                    <td class="text-right"><strong><?php echo $TotalQtyOut; ?></strong></td>
                    <td class="text-left"><strong><?php echo $UOM; ?></strong></td>
                </tr>
            </tfoot>
        </table>
    </div>
    <?php
}

/*
if($_SERVER['REQUEST_METHOD'] == "POST")
{   
    $ValFloat = htmlspecialchars(trim($_POST['ValFloat']), ENT_QUOTES, "UTF-8");
    $ValFloat = base64_decode(base64_decode($ValFloat));
    $ArrValFloat = explode("#",$ValFloat);
    $PartNo = $ArrValFloat[0];
    $Location = $ArrValFloat[1];
    $DateAwal = $ArrValFloat[3];
    echo "$DateAwal >> $Location";
    $arrTipe = array("IN","OUT","Daily Stock");
    ?>
    <div><h5><strong>Part : <?php echo $PartNo; ?></strong></h5></div>
    <div class="table-responsive tableFixHead2">
        <table class="table table-bordered table-hover" id="">
            <thead class="theadCustom">
                <tr>
                    <th width = "20">No</th>
                    <th>Date Transact</th>
                    <th>Transact</th>
                    <th>Qty</th>
                    <th>UOM</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no=1;
                foreach($arrTipe as $tipe)
                {
                    $QtyAndUom = GET_QTY($tipe,$DateAwal,$Location,$PartNo,$linkMACHWebTrax);
                    $arr = explode("*",$QtyAndUom);
                    $ValQty = $arr[0];
                    $ValUOM = $arr[1];
                    $ValQty = number_format((float)$ValQty,2,'.',',');
                ?>
                <tr>
                    <td class="text-center"><?php echo $no; ?></td>
                    <td class="text-center"><?php echo $DateAwal; ?></td>
                    <td class="text-left"><?php echo $tipe; ?></td>
                    <td class="text-right"><?php echo $ValQty; ?></td>
                    <td class="text-left"><?php echo $ValUOM; ?></td>
                </tr>
                <?php
                $no++;
                }
                ?>
            </tbody>
        </table>
    </div>
    <?php
/*
?>
<style>
    .tableFixHead2{
        overflow-y: auto;
        max-height: 350px;
      }
      .tableFixHead2 thead th {
        position: sticky;
        top: 0;
      }
      table {
        border-collapse: collapse;
        width: 100%;
      }
      th,
      td {
        padding: 8px 16px;
        border: 1px solid #ccc;
      }
      th {
        background: #eee;
      }
</style>
<div><h5><strong>Part : <?php echo $PartNo; ?>.  Location : <?php echo $Location; ?><br></br>Warehouse In</strong></h5></div>
<div class="table-responsive tableFixHead2">
    <table class="table table-bordered table-hover" id="">
        <thead class="theadCustom">
            <tr>
                <th width = "20">No</th>
                <th>Date In</th>
                <th>Transact ID</th>
                <th>TLI</th>
                <th>Qty Received</th>
                <th width = "20">UOM</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $num = 1;
        $TotalQtyReceived = 0;
        $Data = GET_WAREHOUSE_IN($PartNo,$Location,$linkMACHWebTrax);
        while($Datares=sqlsrv_fetch_array($Data))
        {
            $TransactID = trim($Datares['TransactID']);
            $DateQA = trim($Datares['DateQA']);
            $QtyReceived = trim($Datares['QtyReceived']);
            $UOM = trim($Datares['TransactUOM']);
            $TLI = trim($Datares['TBZ_ID']);
            $CekData = sqlsrv_num_rows(CEK_DATA_WH_IN($TLI,$linkMACHWebTrax));
            if($CekData == 0)
            {
                $TotalQtyReceived = @($TotalQtyReceived + $QtyReceived);
                $QtyReceived = number_format((float)$QtyReceived,2,'.',',');
        ?>
        <tr>
            <td class="text-center"><?php echo $num; ?></td>
            <td class="text-center"><?php echo $DateQA; ?></td>
            <td class="text-left"><?php echo $TransactID; ?></td>
            <td class="text-left"><?php echo $TLI; ?></td>
            <td class="text-right"><?php echo $QtyReceived; ?></td>
            <td class="text-center"><?php echo $UOM; ?></td>
        </tr>
        <?php
            $num++;
            }
            else {}
            
        }
        $TotalQtyReceived = number_format((float)$TotalQtyReceived,2,'.',',');
        ?>
        </tbody>
        <tfoot>
            <tr>
                <td class="text-center" colspan="4">TOTAL</td>
                <td class="text-right"><?php echo $TotalQtyReceived; ?></td>
                <td class="text-center"><?php echo $UOM; ?></td>
            </tr>
        </tfoot>
    </table>
</div>
<div><h5><strong>Warehouse Out</strong></h5></div>
<div class="table-responsive tableFixHead2">
    <table class="table table-bordered table-hover" id="">
        <thead class="theadCustom">
            <tr>
                <th width = "20">No</th>
                <th>Date Out</th>
                <th>Transact ID</th>
                <th>Qty Received</th>
                <th width = "20">UOM</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $num = 1;
        $TotalQtyUsage = 0;
        $Data = GET_WAREHOUSE_OUT($DateIn,$PartNo,$Location,$linkMACHWebTrax);
        while($Datares=sqlsrv_fetch_array($Data))
        {
            $TransactID = trim($Datares['TransactID']);
            $DateQA = trim($Datares['DateQA']);
            $QtyUsage = trim($Datares['QtyUsage']);
            $UOM = trim($Datares['TransactUOM']);
            $TotalQtyUsage = @($TotalQtyUsage + $QtyUsage);
            $QtyUsage = number_format((float)$QtyUsage,2,'.',',');
        ?>
        <tr>
            <td class="text-center"><?php echo $num; ?></td>
            <td class="text-center"><?php echo $DateQA; ?></td>
            <td class="text-left"><?php echo $TransactID; ?></td>
            <td class="text-right"><?php echo $QtyUsage; ?></td>
            <td class="text-center"><?php echo $UOM; ?></td>
        </tr>
        <?php
        $num++;
        }
        $TotalQtyUsage = number_format((float)$TotalQtyUsage,2,'.',',');
        ?>
        </tbody>
        <tfoot>
            <tr>
                <td class="text-center" colspan="3">TOTAL</td>
                <td class="text-right"><?php echo $TotalQtyUsage; ?></td>
                <td class="text-center"><?php echo $UOM; ?></td>
            </tr>
        </tfoot>
    </table>
</div>
<div><h5><strong>Daily Stock Report</strong></h5></div>
<div class="table-responsive tableFixHead2">
    <table class="table table-bordered table-hover" id="">
        <thead class="theadCustom">
            <tr>
                <th width = "20">No</th>
                <th>Date Report</th>
                <th>PartNo</th>
                <th>Qty Reported</th>
                <th width = "20">UOM</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $nom=1;
        $Data3 = GET_DAILY_STOCK_REPORT($PartNo,$DateAwal,$DateAkhir,$Location,$linkMACHWebTrax);
        while($Data3res=sqlsrv_fetch_array($Data3))
        {
            $DateReport = trim($Data3res['DateCreate2']);
            $PartNum = trim($Data3res['PartNo']);
            $QtyReported = trim($Data3res['Qty']);
            $UOM = trim($Data3res['UOM']);
        ?>
        <tr>
            <td class="text-center"><?php echo $nom; ?></td>
            <td class="text-center"><?php echo $DateReport; ?></td>
            <td class="text-left"><?php echo $PartNum; ?></td>
            <td class="text-right"><?php echo $QtyReported; ?></td>
            <td class="text-center"><?php echo $UOM; ?></td>
        </tr>
        <?php
        $nom++;
        }
        ?>
        </tbody>
    </table>
</div>
<?php

}*/
?>