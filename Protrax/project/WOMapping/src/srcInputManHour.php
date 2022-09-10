<?php
session_start();
require_once("../../../ConfigDB.php");
require_once("../../../src/Modules/ModuleLogin.php");
require_once("../Modules/ModuleWOMapping.php");
$YearNow = date("Y");
$DateNow = date("m/d/Y");
/*
date_default_timezone_set("Asia/Jakarta");
$Time = date("Y-m-d H:i:s");
# data session
$FullName = strtoupper(base64_decode($_SESSION['FullNameUserProTrax']));
$UserNameSession = base64_decode(base64_decode($_SESSION['UIDProTrax']));
 
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
date_default_timezone_set("Asia/Jakarta");
$YearNow = date("Y");
$DateNow = date("m/d/Y");
if($_SERVER['REQUEST_METHOD'] == "POST")
{
    $ValCode = base64_decode(htmlspecialchars(trim($_POST['ValCode']), ENT_QUOTES, "UTF-8"));
    // echo "$ValCode";
    $arr = explode("*",$ValCode);
    $Quote = $arr[0];
    $Expense = $arr[1];
    $Category = $arr[2];
    $MachineCost = array();
    $MaterialCost = array();
    $Target = GET_TARGET_COST_EXPENSE($Quote,$Expense,$Category,$linkMACHWebTrax);
    while($resc=sqlsrv_fetch_array($Target))
    {
        $CostType = trim($resc['CostType']);
        $TargetCost = trim($resc['TargetCost']);
        if($CostType == 'MACHINE')
        {
            array_push($MachineCost,$TargetCost);
        }
        else
        {
            array_push($MaterialCost,$TargetCost);
        }
    }
    $MachineTargetCost = $MaterialTargetCost = 0;
    foreach($MachineCost as $ValMachineCost)
    {
        $MachineTargetCost = $ValMachineCost;
    }
    foreach($MaterialCost as $ValMaterialCost)
    {
        $MaterialTargetCost = $ValMaterialCost;
    }
?>

</div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="Expense" class="form-label fw-bold">Expense</label>
                <input type="text" style="text-transform: uppercase" class="form-control form-control-sm" id="Expense" value="<?php echo $Expense; ?>" disabled>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="FilLokasi" class="form-label fw-bold">Lokasi</label>
                <select class="form-select form-select-sm" id="FilLokasi">
                    <option>--Pilih Lokasi--</option>
                    <option>PSL</option>
                    <option>PSM</option>
                </select>
            </div>
        </div>
    </div>
</div>

<div class="row"><br>
</div class="col-md-12"><strong>TARGET MAN HOUR</strong></div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="SumDay" class="form-label fw-bold">Input Jumlah Hari</label>
            <input type="text" class="form-control form-control-sm" id="SumDay" placeholder="0.00">
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="SumMan" class="form-label fw-bold">Input Jumlah Alokasi Orang</label>
            <input type="text" class="form-control form-control-sm" id="SumMan" placeholder="0.00">
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="LimMax" class="form-label fw-bold">Limit Max Pengerjaan (Hour)</label>
            <input type="text" class="form-control form-control-sm" id="LimMax" placeholder="0.00" disabled>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="KonsManHour" class="form-label fw-bold">Konstanta Man Hour Cost ($)</label>
            <input type="text" class="form-control form-control-sm" id="KonsManHour" value="3.00" disabled>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="EstManHour" class="form-label fw-bold">Est. Target Man Hour Cost ($)</label>
            <input type="text" class="form-control form-control-sm" id="EstManHour" placeholder="0.00" disabled>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12"><br></br></div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="MachHour" class="form-label fw-bold">Input Target Mach Hour</label>
            <input type="text" class="form-control form-control-sm" id="MachHour" value="<?php echo $MachineTargetCost; ?>">
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="MatCost" class="form-label fw-bold">Input Target Cost Material</label>
            <input type="text" class="form-control form-control-sm" id="MatCost" value="<?php echo $MaterialTargetCost; ?>">
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12" style="color: transparent;">Input Target Cost</div>
    <div class="col-md-4">
        <div class="form-group">
            <div class="controls">
                <label for="txtFilterTanggal1" class="form-label fw-bold">Est. Finish Date</label>
                <div class="input-group input-group-sm">
                    <input id="txtFilterTanggal1" name="txtFilterTanggal1" type="text" class="date-picker form-control" aria-describedby="txtFilterTanggal1Val" value="<?php echo $DateNow; ?>" readonly />
                    <label for="txtFilterTanggal1" class="input-group-text" id="txtFilterTanggal1Val"><span class="bi bi-calendar-date text-dark"></span></label>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="EstHalf" class="form-label fw-bold">Est. Half Closed</label>
            <select class="form-select form-select-sm" id="EstHalf">
                <?php
                $CL = GET_EST_CLOSEDTIME($YearNow,$linkMACHWebTrax);
                while($resCL=sqlsrv_fetch_array($CL))
                {
                    $EstCL = trim($resCL['EstHalf']);
                ?>
                <option><?php echo $EstCL; ?></option>
                <?php
                }
                ?>
            </select>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="NamaDM" class="form-label fw-bold">DM</label>
            <select class="form-select form-select-sm" id="NamaDM">
                <?php
                $Data = GET_NAMA_DM($Expense,$linkMACHWebTrax);
                while($res=sqlsrv_fetch_array($Data))
                {
                    $NamaDM = trim($res['FullName']);
                ?>
                <option><?php echo $NamaDM; ?></option>
                <?php
                }
                ?>
            </select>
        </div>
    </div>
    <div class="col-md-6">
        <label for="" class="form-label fw-bold" style="color: transparent;">input</label>
        <button class="btn btn-sm btn-success" id="BtnInputManHour" style="width:100%">INPUT</button>
    </div>
</div>

<?php
}
else
{
    echo "Error!";
}
?>
