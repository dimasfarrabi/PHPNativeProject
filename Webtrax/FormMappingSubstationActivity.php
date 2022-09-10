<?php 
if(!session_is_registered("UIDWebTrax"))
{
    ?>
    <script language="javascript">
        window.location.replace("https://webtrax.formulatrix.com/");
    </script>
    <?php
    exit();
}
if($AccessLogin != "Reguler")
{
    ?>
    <script language="javascript">
        window.location.href = "home.php";
    </script>
    <?php
    exit();
}
require("src/Modules/ModuleMappingSubstation.php");

# default value
$ValProductName = "-";
$ValSubstation = "-";
$ValActivity = "-";
$ValDisabled = "";
# data mapping substation
$QDataMappingSubstation = LOAD_USER_MAPPING_SUBSTATION_BY_ID($EmployeeID,$linkMACHWebTrax);
if(mssql_num_rows($QDataMappingSubstation) != "0")
{
    $RDataMappingSubstation = mssql_fetch_assoc($QDataMappingSubstation);
    $ValProductName = $RDataMappingSubstation['Product'];
    $ValSubstation = $RDataMappingSubstation['SubStation'];
    $ValActivity = $RDataMappingSubstation['SubStationActivity'];
    // $ValDisabled = " disabled";
    $BtnName = "Update";
}
else
{
    $RDataMappingSubstation = mssql_fetch_assoc($QDataMappingSubstation);
    $ValProductName = $RDataMappingSubstation['Product'];
    $ValSubstation = $RDataMappingSubstation['SubStation'];
    $ValActivity = $RDataMappingSubstation['SubStationActivity'];
    $BtnName = "Simpan";
}

?>
<script src="lib/libmappingsubstationactivity.js?no=<?php echo base64_encode(date("mdyHis")); ?>"></script>
<div class="row">
    <div class="col-sm-12">
        <ol class="breadcrumb breadcrumb-detail">
            <li><a href="home.php">Home</a></li>
            <li class="active">Settings</li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-sm-4">
        <div class="row">
            <div class="col-sm-12"><h4 class="TitleGroup">Pendataan Mapping Substation</h4></div>
            <div class="col-sm-12">
                <div class="form-group">
                    <label for="ProductCategory">Product</label>
                    <select class="form-control" id="PostProduct"<?php echo $ValDisabled; ?>><option class="NoProduct" value="0" disabled selected>-- Pilih Produk --</option><?php
                        $QListProduct = GET_LIST_PRODUCT_IN_SUBSTATION($linkMACHWebTrax);
                        while($RListProduct = mssql_fetch_assoc($QListProduct))
                        {
                            $Product = trim($RListProduct['Product']);
                        ?><option class="<?php echo $Product; ?>" value="<?php echo $Product; ?>"><?php echo $Product; ?></option><?php
                        }
                    ?></select>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="form-group">
                    <label for="ProductSubStation">Substation</label>
                    <select class="form-control" id="PostSubstation"<?php echo $ValDisabled; ?>><option class="NoProduct" value="0" disabled selected>-- Pilih Substation --</option><?php
                        $QListSubstation = GET_LIST_ALL_DATA_SUBSTATION_ACTIVITY($linkMACHWebTrax);
                        while($RListSubstation = mssql_fetch_assoc($QListSubstation))
                        {
                            $ProductGroup = trim($RListSubstation['Product']);
                            $Substation = trim($RListSubstation['SubStation']);
                            $SubStationActivity = trim($RListSubstation['SubStationActivity']);
                            $CombineSubstation = $Substation." - ".$SubStationActivity;
                            $IDMapping = base64_encode(base64_encode(trim($RListSubstation['Idx'])."*".$ProductGroup."*".$Substation."*".$SubStationActivity));
                            ?><option class="<?php echo $ProductGroup; ?>" value="<?php echo $IDMapping; ?>"><?php echo $CombineSubstation; ?></option><?php
                        }
                    ?></select>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="form-group">
                    <button class="btn btn-small btn-dark" id="BtnSimpanMappingSubstation"<?php echo $ValDisabled; ?>><?php echo $BtnName; ?></button> <img src="../images/ajax-loader1.gif" id="LoadingAdd" class="load_img"/>
                </div>
            </div>
            <div class="col-sm-12"><div id="DivContentChecking"></div></div>
        </div>
    </div>
    <div class="col-sm-8">
        <div class="row">
            <div class="col-sm-12"><h4 class="TitleGroup">Data Mapping Substation</h4></div>
            <div class="col-sm-12">&nbsp;</div>
            <div class="col-sm-12">
                <div class="table-responsive">
                    <table class="table text-left" id="TableDataMappingSubstation">
                        <tr>
                            <td>Nama</td>
                            <td>:</td>
                            <td><?php echo $FullName; ?></td>
                        </tr>
                        <tr>
                            <td>Divisi</td>
                            <td>:</td>
                            <td><?php echo $DivisionName; ?></td>
                        </tr>
                        <tr>
                            <td>Product</td>
                            <td>:</td>
                            <td><?php echo $ValProductName; ?></td>
                        </tr>
                        <tr>
                            <td>Substation</td>
                            <td>:</td>
                            <td><?php echo $ValSubstation; ?></td>
                        </tr>
                        <tr>
                            <td>Aktivitas</td>
                            <td>:</td>
                            <td><?php echo $ValActivity; ?></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
