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
# set default 
$GroupNoDefault = SET_GROUP_NO();
$PCName = GET_COMPUTER_NAME();
# set activity
$DivisionMapping = GET_DIVISION_MAPPING($NIK,$NIKSorting);
$Activity = GET_ACTIVITY_BY_EXPENSE_ALLOCATION($DivisionMapping);
$SubActivity = "-";
# load data timetrack aktif
$QProgressEntry = GET_LOAD_PROGRESS_ENTRY_DEVICES($PCName,$FullName,$linkMACHWebTrax);



?><script src="lib/libtimetrackactivity.js?no=<?php echo base64_encode(date("mdyHis")); ?>"></script>
<div class="row">
    <div class="col-sm-12">
        <ol class="breadcrumb breadcrumb-detail">
            <li><a href="home.php">Home</a></li>
            <li class="active">Timetrack</li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-sm-12 div-content-top">
        <div class="row">
            <div class="col-md-4">
                <div class="row">
                    <div class="col-sm-12"><h4 class="TitleGroup" id="LabelInput">Scan Barcode</h4></div>
                    <div class="col-sm-12">
                        <div class="form-group input-group-lg">
                            <input type="text" class="form-control text-center text-input-black" id="InputID">
                        </div>
                    </div>
                    <div class="col-sm-12"><h4 class="TitleGroup" id="LabelNotes">System Notes</h4></div>
                    <div class="col-sm-12">
                        <textarea class="form-control text-center text-input-black" id="InfoNotes" rows="2" readonly></textarea>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="row">
                    <div class="col-sm-12"><h4 class="TitleGroup">Identitas Karyawan</h4></div>
                    <div class="col-sm-12">
                        <div class="table-responsive">
                            <table class="table text-left" id="TableIdentitasKaryawan">
                                <tr>
                                    <td>PC Name</td>
                                    <td>:</td>
                                    <td><?php echo $PCName; ?></td>
                                </tr>
                                <tr>
                                    <td>Group No</td>
                                    <td>:</td>
                                    <td><?php echo $GroupNoDefault; ?></td>
                                </tr>
                                <tr>
                                    <td>NIK</td>
                                    <td>:</td>
                                    <td><?php echo $NIK; ?></td>
                                </tr>
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
                                    <td>Activity</td>
                                    <td>:</td>
                                    <td><?php echo $Activity; ?></td>
                                </tr>
                                <tr>
                                    <td>SubActivity</td>
                                    <td>:</td>
                                    <td><?php echo $SubActivity; ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="row">
                    <div class="col-sm-12"><h4 class="TitleGroup">Identitas Produk</h4></div>
                    <div class="col-sm-12">
                        <div class="table-responsive">
                            <table class="table text-left" id="TableIdentitasProduk">
                                <tr>
                                    <td>Barcode</td>
                                    <td>:</td>
                                    <td>-</td>
                                </tr>
                                <tr>
                                    <td>WOMapping ID</td>
                                    <td>:</td>
                                    <td>-</td>
                                </tr>
                                <tr>
                                    <td>Work Order</td>
                                    <td>:</td>
                                    <td>-</td>
                                </tr>
                                <tr>
                                    <td>Product</td>
                                    <td>:</td>
                                    <td>-</td>
                                </tr>
                                <tr>
                                    <td>Order Type</td>
                                    <td>:</td>
                                    <td>-</td>
                                </tr>
                                <tr>
                                    <td>Expense</td>
                                    <td>:</td>
                                    <td>-</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-12">
        <div class="row">
            <div class="col-md-6">
                <div class="row">
                    <div class="col-sm-12"><h5 class="TitleGroup"><strong>List sementara barcode yang akan diproses</strong></h5></div>
                    <div class="col-sm-12">&nbsp;</div>
                    <div class="col-sm-12">
                        <div class="table-responsive">
                            <table class="table table-hover display" id="TableDataListBarcodeSementara">
                                <thead>
                                    <tr>
                                        <th class="text-center">Employee</th>
                                        <th class="text-center">WorkOrder</th>
                                        <th class="text-center">Product</th>
                                        <th class="text-center">OrderType</th>
                                        <th class="text-center">ExpAllocation</th>
                                        <th class="text-center">Activity</th>
                                        <th class="text-center">SubActivity</th>
                                        <th class="text-center">Barcode</th>
                                        <th class="text-center">GroupNo</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="row">
                    <div class="col-sm-12"><h5 class="TitleGroup"><strong>Daftar proses barcode yang sedang berjalan</strong></h5></div>
                    <div class="col-sm-12">&nbsp;</div>
                    <div class="col-sm-12">
                        <div class="table-responsive">
                            <table class="table table-hover display" id="TableDataListBarcodeProses">
                                <thead>
                                    <tr>
                                        <th class="text-center">Employee</th>
                                        <th class="text-center">WorkOrder</th>
                                        <th class="text-center">Activity</th>
                                        <th class="text-center">SubActivity</th>
                                        <th class="text-center">StartTime</th>
                                        <th class="text-center">EndTime</th>
                                        <th class="text-center">ShiftCode</th>
                                        <th class="text-center">GroupNo</th>
                                        <th class="text-center">Idx</th>
                                    </tr>
                                </thead>
                                <tbody><?php 
                                while($RProgressEntry = mssql_fetch_assoc($QProgressEntry))
                                {
                                    $Employee = $RProgressEntry['Employee'];
                                    $WorkOrder = $RProgressEntry['WorkOrder'];
                                    $Activity = $RProgressEntry['Activity'];
                                    $SubActivity = $RProgressEntry['SubActivity'];
                                    $StartTime = $RProgressEntry['StartTime'];
                                    $EndTime = $RProgressEntry['EndTime'];
                                    $ShiftCode = $RProgressEntry['ShiftCode'];
                                    $GroupNo = $RProgressEntry['GroupNo'];
                                    $Idx = $RProgressEntry['Idx'];
                                    ?>
                                    <tr>
                                        <td class="text-left"><?php echo $Employee; ?></td>
                                        <td class="text-left"><?php echo $WorkOrder; ?></td>
                                        <td class="text-left"><?php echo $Activity; ?></td>
                                        <td class="text-left"><?php echo $SubActivity; ?></td>
                                        <td class="text-center"><?php echo $StartTime; ?></td>
                                        <td class="text-center"><?php echo $EndTime; ?></td>
                                        <td class="text-center"><?php echo $ShiftCode; ?></td>
                                        <td class="text-left"><?php echo $GroupNo; ?></td>
                                        <td class="text-center"><?php echo $Idx; ?></td>
                                    </tr>
                                    <?php
                                }
                                ?></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-12" id="ResultCheck"></div>
</div>
