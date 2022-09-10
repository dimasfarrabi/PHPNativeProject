<?php
require_once("../../ConfigDB.php");
require_once("../../src/Modules/ModuleLogin.php");
require_once("Modules/ModuleWOMapping.php");
date_default_timezone_set("Asia/Jakarta");
$YearNow = date("Y");
$DateNow = date("m/d/Y");

if($_SERVER['REQUEST_METHOD'] == "POST")
{   
    $ValQuoteCode = htmlspecialchars(trim($_POST['Quote']), ENT_QUOTES, "UTF-8");
    $NamaPM = htmlspecialchars(trim($_POST['NamaPM']), ENT_QUOTES, "UTF-8");
    $ValQuote = base64_decode($ValQuoteCode);
    $arr = explode("*",$ValQuote);
    $Quote = $arr[0];
    $Category = $arr[1];
    // echo "$Quote >> $NamaPM";
?>
<div class="col-md-12">
    <div class="form-group">
        <label for="QuoteName" class="form-label fw-bold">Quote</label>
        <input type="text" class="form-control form-control-sm" id="QuoteName" value="<?php echo $Quote ; ?>" disabled>
    </div>
</div> 
<div class="col-md-12">
    <div class="form-group">
        <label for="Category" class="form-label fw-bold">Quote Category</label>
        <input type="text" class="form-control form-control-sm" id="Category" value="<?php echo $Category ; ?>" disabled>
    </div>
</div>
<div class="col-md-12">
    <div class="form-group">
        <label for="NamaPM" class="form-label fw-bold">PM</label>
        <input type="text" class="form-control form-control-sm" id="NamaPM" value="<?php echo $NamaPM ; ?>" disabled>
    </div>
</div>
<div class="col-md-12">
    <div class="form-group">
        <label for="WOPBaru" class="form-label fw-bold">Daftarkan WO Parent</label>
        <input type="text" style="text-transform: uppercase" class="form-control form-control-sm" id="WOPBaru">
    </div>
</div>
</div class="col-md-12">
    <div class="form-group">
        <label for="ProductName" class="form-label fw-bold">Product</label>
        <select class="form-select form-select-sm" id="ProductName">
            <option></option>
            <?php
            $data = GET_PRODUCT_NAME($linkMACHWebTrax);
            while($res=sqlsrv_fetch_array($data))
            {
                $ValProduct = trim($res['ProductName']);
            ?>
            <option><?php echo $ValProduct; ?></option>
            <?php
            }
            ?>
            
        </select>
    </div>
</div>
<div class="col-md-12">
    <div class="form-group">
        <label for="QtyWOPBaru" class="form-label fw-bold">Qty Parent</label>
        <input type="text" class="form-control form-control-sm" id="QtyWOPBaru" placeholder="0.00" required>
    </div>
</div>
<br>
</div class="col-md-12">
    <button class="btn btn-sm btn-success" id="BtnSaveWOP" style="width:100%">Simpan</button>
</div>
<div class="col-md-12" id="SimpanWOP"></div>
<?php
}
?>