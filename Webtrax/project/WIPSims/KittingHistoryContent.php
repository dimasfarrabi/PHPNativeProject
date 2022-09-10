<?php
session_start();
require_once("../../src/Modules/ModuleLogin.php");
require_once("Modules/ModuleWIPSims.php");
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
$today = date("m/d/Y");
if($_SERVER['REQUEST_METHOD'] == "POST")
{   
    $Param = htmlspecialchars(trim($_POST['Param']), ENT_QUOTES, "UTF-8");
    switch ($Param) {
        case 'Week':
            {
                $Param2 = "1 Minggu Terakhir";
            }
        break;
        case 'Month':
            {
                $Param2 = "1 Bulan Terakhir";
            }
        break;
        case 'ThreeMonth':
            {
                $Param2 = "3 Bulan Terakhir";
            }
        break;
        case 'SixMonth':
            {
                $Param2 = "6 Bulan Terakhir";
            }
        break;
    }
?>
<div class="col-md-6"><h4><strong>Kitting Report: [ <?php echo $Param2; ?> ]</strong></h4></div>
<div class="col-md-6">
    <button type="button" class="btn btn-md btn-info" id="BtnDownload" style="float: right;">Download CSV</button>
</div>
<div class="col-md-12">
    <div class="table-responsive">
        <table class="table table-bordered table-hover" id="ContentTable">
            <thead class="theadCustom">
                <tr>
                    <th>BarcodeKit</th>
                    <th>WO Child</th>
                    <th>TemplateName</th>
                    <th>TrayKitStatus</th>
                    <th>TglCreated</th>
                    <th>TglTransaksiAwal</th>
                    <th>TglTransaksiAkhir</th>
                    <th>TglClosing</th>
                    <th>TglCheckOutQC</th>
                    <th>TglCheckInWH</th>
                    <th>TglCheckOutWH</th>
                    <th>TglCheckInAssy</th>
                    <th>InstrumentSN</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $data = GET_KITTING_HISTORY_DATA($Param,$linkMACHWebTrax);
            while($res=sqlsrv_fetch_array($data))
            {
                $BarcodeKit = trim($res['BarcodeKit']);
                $WOChild = trim($res['WOChild']);
                $TemplateName = trim($res['TemplateName']);
                $TrayKitStatus = trim($res['TrayKitStatus']);
                $TglCreate = trim($res['TglCreate2']);
                $TglTransaksiAwal = trim($res['TglTransaksiAwal2']);
                $TglTransaksiAkhir = trim($res['TglTransaksiAkhir2']);
                $TglClosing = trim($res['TglClosing2']);
                $TglCheckOutQC = trim($res['TglCheckOutQC2']);
                $TglCheckInWH = trim($res['TglCheckInWH2']);
                $TglCheckOutWH = trim($res['TglCheckOutWH2']);
                $TglCheckInAssembly = trim($res['TglCheckInAssembly2']);
                $InstrumentSN = trim($res['InstrumentSN']);
            ?>
            <tr>
                <td class="text-left"><?php echo $BarcodeKit; ?></td>
                <td class="text-left"><?php echo $WOChild; ?></td>
                <td class="text-left"><?php echo $TemplateName; ?></td>
                <td class="text-left"><?php echo $TrayKitStatus; ?></td>
                <td class="text-center"><?php echo $TglCreate; ?></td>
                <td class="text-center"><?php echo $TglTransaksiAwal; ?></td>
                <td class="text-center"><?php echo $TglTransaksiAkhir; ?></td>
                <td class="text-center"><?php echo $TglClosing; ?></td>
                <td class="text-center"><?php echo $TglCheckOutQC; ?></td>
                <td class="text-center"><?php echo $TglCheckInWH; ?></td>
                <td class="text-center"><?php echo $TglCheckOutWH; ?></td>
                <td class="text-center"><?php echo $TglCheckInAssembly; ?></td>
                <td class="text-left"><?php echo $InstrumentSN; ?></td>
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