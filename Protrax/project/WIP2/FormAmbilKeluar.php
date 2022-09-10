<?php
require_once("project/WIP/Modules/ModuleWIP.php"); 
require_once("project/WIP2/Modules/ModuleInOut.php"); 
date_default_timezone_set("Asia/Jakarta");
$YearNow = date("Y");
$DateNow = date("m/d/Y");
/*
if(!session_is_registered("UIDProTrax"))
{
    ?>
    <script language="javascript">
        window.location.replace("https://protrax.formulatrix.com/");
    </script>
    <?php
    exit();
}

# data session
$FullName = strtoupper(base64_decode($_SESSION['FullNameUserProTrax']));
$UserNameSession = base64_decode(base64_decode($_SESSION['UIDProTrax']));
# data protrax user
$BolProdAcc = false;
$QDataUserWebtrax = GET_DATA_LOGIN_BY_USERNAME_ONLY($UserNameSession,$linkHRISWebTrax);
if(mssql_num_rows($QDataUserWebtrax) > 0)
{
    $RDataUserWebtrax = mssql_fetch_assoc($QDataUserWebtrax);
    $TypeUser = trim($RDataUserWebtrax['TypeUser']);
    $_SESSION['LoginMode'] = base64_encode($TypeUser);
    $AccessLogin = base64_decode($_SESSION['LoginMode']);   
}
else # kondisi tidak terdaftar di protrax user & akan di set sebagai employee dan hak akses ke bagian produksi saja
{
    $_SESSION['LoginMode'] = base64_encode("Employee");
    $AccessLogin = base64_decode($_SESSION['LoginMode']);
    $BolProdAcc = true;
}

if((trim($AccessLogin) != "Manager"))
{
    if($RDataUserWebtrax['MnAdmin'] != "1")  
    {
        ?>
        <script language="javascript">
            window.location.replace("https://protrax.formulatrix.com/");
        </script>
        <?php
        exit();
    }
}
else
{
    if($RDataUserWebtrax['MnProduction'] != "1")
    {
        ?>
        <script language="javascript">
            window.location.replace("https://protrax.formulatrix.com/");
        </script>
        <?php
        exit();
    }
}
*/
?>

<?php /*<script src="project/WIP/lib/LibInventoryBinV3.js?no=<?php echo base64_encode(date("mdyHis")); ?>"></script>*/ ?>
<script src="lib/datetimepicker-master/jquery.datetimepicker.full.min.js"></script>
<script src="lib/DataTables-v1.11.3/DataTables/Buttons-2.1.1/js/dataTables.buttons.min.js"></script>
<script src="lib/DataTables-v1.11.3/DataTables/Buttons-2.1.1/js/buttons.html5.min.js"></script>
<script src="lib/DataTables-v1.11.3/DataTables/Buttons-2.1.1/js/buttons.print.min.js"></script>
<link rel="stylesheet" type="text/css" href="lib/datetimepicker-master/jquery.datetimepicker.css"/>
<div class="row">
    <div class="col-md-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-detail ">
                <li class="breadcrumb-item"><a href="home.php">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Production : Form Pengambilan & Keluar Part</li>
            </ol>
        </nav>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-12 pb-2">
                <div class="card">
                    <h6 class="card-header text-white bg-secondary">Form Scan Pengambilan<span id="BtnViewReport" style="float:right; cursor:pointer;" aria-hidden="true" data-bs-toggle="modal" data-bs-target="#ReportModal">[View Report]</span></h6>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="input-group">
                                    <label for="CompanyIN" class="input-group-text fw-bold">Lokasi</label>
                                    <select class="form-select form-select-sm" id="CompanyIN">
                                        <option>-- Pilih Lokasi --</option>
                                        <?php
                                        $QListLocation = GET_LIST_COMPANY($linkMACHWebTrax);
                                        while($res=sqlsrv_fetch_array($QListLocation))
                                        {
                                            $ValLoc = trim($res['CompanyCode']);
                                        ?>
                                        <option><?php echo $ValLoc; ?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="input-group">
                                    <label for="ProsesOUT" class="input-group-text fw-bold">Out From</label>
                                    <select class="form-select form-select-sm" id="ProsesOUT">
                                        <option>-- Pilih Proses --</option>
                                        <option>NEW</option>
                                        <option>A</option>
                                        <option>B</option>
                                        <option>C</option>
                                        <option>D</option>
                                        <option>E</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="input-group">
                                    <label for="ProsesIN" class="input-group-text fw-bold">Into</label>
                                    <select class="form-select form-select-sm" id="ProsesIN">
                                        <option>-- Pilih Proses --</option>
                                        <option>A</option>
                                        <option>B</option>
                                        <option>C</option>
                                        <option>D</option>
                                        <option>E</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="input-group">
                                    <label for="ScanIN" class="input-group-text fw-bold">Scan</label>
                                    <input type="text" class="form-control form-control-sm text-center" id="ScanIN">
                                </div>
                            </div>
                            <div class="col-md-12" style="margin-top:30px;"><hr></div>
                            <div class="col-md-12">
                                <div id="">
                                    <table class="table table-responsive table-hover" id="ScanInInfo">
                                        <thead>
                                            <tr>
                                                <th class="text-center">Barcode</th>
                                                <th class="text-center">PartNo</th>
                                                <th class="text-center" width="250">Qty</th>
                                                <th class="text-center">#</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="col-md-10">
                                <button id="BtnProceedIn"class="btn btn-md btn-success" style="float:right; width:30%;">Proceed</button>
                            </div>
                            <div class="col-md-2" id="SubmitContent"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="DeleteTemp" tabindex="-1" role="dialog" aria-labelledby="Modal-View" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Remove from list</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
				<div id="RecFormContent">
                <div class="row">
                        <div class="col-md-6"><button class="btn btn-md btn-danger" id="DeleteBtn2" style="width:100%">YES</button></div>
                        <div class="col-md-6"><button class="btn btn-md btn-warning" data-bs-dismiss="modal" style="width:100%">NO</button></div>
                    </div>
                </div>
                <div id="DeleteContent" style="margin-top:20px;">
                    
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">&nbsp;Close&nbsp;</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="ReportModal" tabindex="-1" role="dialog" aria-labelledby="Modal-View" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Report</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row" id="ReportModalContent">

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">&nbsp;Close&nbsp;</button>
            </div>
        </div>
    </div>
</div>
<script>
$(document).ready(function () { 
    $("#CompanyIN").focus();
    $("#BtnProceedIn").prop('disabled', true);
    $("#CompanyIN").change(function(){
        $("#ProsesOUT").focus();
    });
    $("#ProsesOUT").change(function(){
        if($("#CompanyIN").val() == "-- Pilih Lokasi --"){
            $("#CompanyIN").focus();
            return false;
        }
        $("#ProsesIN").focus();
    });
    $("#ProsesIN").change(function(){
        if($("#ProsesOUT").val() == "-- Pilih Proses --"){
            $("#ProsesOUT").focus();
            return false;
        }
        $("#ScanIN").focus();
    });
    $("#ScanIN").change(function(){ 
        if($("#CompanyIN").val() == "-- Pilih Lokasi --"){
            $("#CompanyIN").focus();
            $("#ScanIN").val('');
            return false;
        }
        if($("#ProsesIN").val() == "-- Pilih Proses --"){
            $("#ProsesIN").focus();
            $("#ScanIN").val('');
            return false;
        }
        if($("#ProsesOUT").val() == "-- Pilih Proses --"){
            $("#ProsesOUT").focus();
            $("#ScanIN").val('');
            return false;
        }
        var CompanyIN = $("#CompanyIN").val().trim();
        var ProsesOUT = $("#ProsesOUT").val().trim();
        var ProsesIN = $("#ProsesIN").val().trim();
        var BarcodeIN = $("#ScanIN").val().trim();
        if(ProsesOUT == ProsesIN){
            $("#ProsesIN").focus();
            $("#ScanIN").val('');
            return false;
        }
        $("#ScanIN").val('');
        $("#ScanIN").focus();
        var formdata = new FormData();
        formdata.append('Company', CompanyIN);
        formdata.append('ProsesOUT', ProsesOUT);
        formdata.append('ProsesIN', ProsesIN);
        formdata.append('Barcode', BarcodeIN);
        $.ajax({
            url: 'project/WIP2/src/srcBCPengambilan.php',
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: formdata,
            type: 'post',
            beforeSend: function () {
            },
            success: function (xaxa) {
                var result = xaxa;
                const ArrRes = result.split(":");
                if(ArrRes[0] == "TRUE"){
                    const arr = ArrRes[1].split("*");
                    var ValBC = arr[2];
                    var ValPartNo = arr[0];
                    var ValQty = arr[1];
                    var markup = '<tr data-idrows="isx" data-erows="'+ValBC+'"><td class="text-center">'+ValBC+'</td><td class="text-center">'+ValPartNo+'</td><td class="text-center">'+ValQty+'</td><td class="text-center"><i class="bi bi-trash-fill PointerList" aria-hidden="true" data-bs-toggle="modal" data-ecode="'+ValBC+'" data-bs-target="#DeleteTemp" title="Delete"></td></tr>';
                    $("#ScanInInfo tbody").append(markup);
                    $("#BtnProceedIn").prop('disabled', false);
                    $("#CompanyIN").prop('disabled', true);
                    $("#ProsesOUT").prop('disabled', true);
                    $("#ProsesIN").prop('disabled', true);
                }
                else if(ArrRes[0] == "FALSE1"){
                    alert('Barcode Tidak Ditemukan');
                }
                else{
                    alert('Barcode Tidak Dapat Digunakan');
                }
                $("#DeleteTemp").on('show.bs.modal', function (event) {
                    $("#DeleteBtn2").click(function(){
                        var act = $(event.relatedTarget);
                        var DataCode = act.data('ecode');
                        var formdata = new FormData();
                        formdata.append("ValCode", DataCode);
                        $.ajax({
                            url: 'project/WIP2/src/srcDeleteTempPengambilan.php',
                            dataType: 'text',
                            cache: false,
                            contentType: false,
                            processData: false,
                            data: formdata,
                            type: 'post',
                            beforeSend: function () {
                                $('#DeleteContent').html("");
                                $('#DeleteContent').before('<div class="col-sm-12 d-flex justify-content-center" id="LoadingImg"><img src="../images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
                            },
                            success: function (xaxa) {
                                $('#DeleteContent').hide();
                                $('#DeleteContent').html(xaxa);
                                $('#DeleteContent').fadeIn('fast');
                                $('#LoadingImg').remove();
                            },
                            error: function () {
                                alert('Request cannot proceed!');
                                $('#LoadingImg').remove();
                            }
                        });
                    });
                });
            },
            error: function () {
                alert('Request cannot proceed!');
            }
        });
    });
    $("#BtnProceedIn").click(function(){
        var arr = [];
        $("#ScanInInfo tr[data-idrows='isx']").each(function(){
            var Id = $(this).find("td:first").text();
            arr.push(Id);
        }); 
        var CompanyIN = $("#CompanyIN").val().trim();
        var ProsesOUT = $("#ProsesOUT").val().trim();
        var ProsesIN = $("#ProsesIN").val().trim();
        var formdata = new FormData();
        formdata.append('Company', CompanyIN);
        formdata.append('ProsesOUT', ProsesOUT);
        formdata.append('ProsesIN', ProsesIN);
        formdata.append('arr', arr);
        if (confirm("Apakah anda yakin untuk memproses ?") == true) {
            $.ajax({
                url: 'project/WIP2/src/srcProceedPengambilan.php',
                dataType: 'text',
                cache: false,
                contentType: false,
                processData: false,
                data: formdata,
                type: 'post',
                beforeSend: function () {
                    $('#SubmitContent').html("");
                    $('#SubmitContent').before('<div class="col-sm-2 d-flex justify-content-center" id="LoadingImg"><img src="../images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
                },
                success: function (xaxa) {
                    var Res = xaxa;
                    $('#LoadingImg').remove();
                    if(Res == "TRUE"){
                        alert('Success to save');
                        window.location.replace("http://localhost/protrax/home.php?link=44");
                    }
                    else{
                        alert('Failed to save');
                        return false;
                    }
                },
                error: function () {
                    alert('Request cannot proceed!');
                    $('#LoadingImg').remove();
                }
            });
        }
    });
    $("#ReportModal").on('show.bs.modal', function (event) { 
        $.ajax({
            url: 'project/WIP2/ReportPengambilan.php',
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            beforeSend: function () {
                $('#ReportModalContent').html("");
                $('#ReportModalContent').before('<div class="col-sm-12 d-flex justify-content-center" id="LoadingImg"><img src="../images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
            },
            success: function (xaxa) {
                $('#ReportModalContent').hide();
                $('#ReportModalContent').html(xaxa);
                $('#ReportModalContent').fadeIn('fast');
                $('#LoadingImg').remove();
                $('#ReportTable').DataTable({
                    "iDisplayLength": 10,
                    "lengthChange": false,
                    searching: false,
                });
            },
            error: function () {
                alert('Request cannot proceed!');
                $('#LoadingImg').remove();
            }
        });
    });
});

</script>