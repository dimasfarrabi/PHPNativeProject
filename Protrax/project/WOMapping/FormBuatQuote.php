<?php
require_once("../../ConfigDB.php");
require_once("../../src/Modules/ModuleLogin.php");
require_once("Modules/ModuleWOMapping.php");
date_default_timezone_set("Asia/Jakarta");
$YearNow = date("Y");
$DateNow = date("m/d/Y");
?>
</div class="col-md-12">
    <div class="form-group">
        <label for="NewQuoteName" class="form-label fw-bold">Daftarkan Quote</label>
        <input type="text" style="text-transform: uppercase" class="form-control form-control-sm" id="NewQuoteName">
    </div>
</div>
</div class="col-md-12">
    <div class="form-group">
        <label for="QuoteCat" class="form-label fw-bold">Quote Category</label>
        <select class="form-select form-select-sm" id="QuoteCat">
            <option>Quote</option>
            <option>Unquote</option>
        </select>
    </div>
</div>
</div class="col-md-12">
    <div class="form-group">
        <label for="NamaPM" class="form-label fw-bold">PM</label>
        <select class="form-select form-select-sm" id="NamaPM">
            <option></option>
            <?php
            $data = GET_PM_NAME($linkMACHWebTrax);
            while($res=sqlsrv_fetch_array($data))
            {
                $ValPM = trim($res['Name']);
            ?>
            <option><?php echo $ValPM; ?></option>
            <?php
            }
            ?>
        </select>
    </div>
</div>
<br>
</div class="col-md-12">
    <button class="btn btn-sm btn-success" id="BtnSaveQuote" style="width:100%">Simpan</button>
</div>
<div class="col-md-12" id="SimpanQuote"></div>