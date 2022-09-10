<?php
session_start();
require_once("../../ConfigDB.php");
require_once("../../src/Modules/ModuleLogin.php");
require_once("Modules/ModuleInOutPartTBZ.php");

if($_SERVER['REQUEST_METHOD'] == "POST")
{   
    $ValLoc = base64_decode(base64_decode(htmlspecialchars(trim($_POST['ValLoc']), ENT_QUOTES, "UTF-8")));
    $arr = explode(":",$ValLoc);
    $LocationCode = $arr[1];
?>
<div class="input-group input-group-sm mb-3">
    <label for="InputGudang" class="input-group-text fw-bold">Gudang Kecil</label>
    <select class="form-select form-select-sm" id="InputGudang">
        <option>-- Pilih Gudang Kecil --</option>
        <?php
        $data=GET_GUDANG_KECIL_BY_LOC($LocationCode,$linkMACHWebTrax);
        while($res=sqlsrv_fetch_array($data))
        {
            $WarehouseName = trim($res['Gudang']);
        ?>
        <option><?php echo $WarehouseName; ?></option>
        <?php
        }
        ?>
    </select>
</div>
<?php
}
?>