<?php
require_once("../webtrax/project/CostTracking/Modules/ModuleCostTracking.php");
require_once("project/Report/Modules/ModuleReport.php");
date_default_timezone_set("Asia/Jakarta");
$YearNow = date("Y");
$DateNow = date("m/d/Y");
?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.full.min.js" integrity="sha512-AIOTidJAcHBH2G/oZv9viEGXRqDNmfdPVPYOYKGy3fti0xIplnlgMHUGfuNRzC6FkzIo0iIxgFnr9RikFxK+sw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<link rel="stylesheet" type="text/css" href="lib/datetimepicker-master/jquery.datetimepicker.css"/>
<div class="row">
    <div class="col-md-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-detail ">
                <li class="breadcrumb-item"><a href="home.php">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Report : Barcode Status</li>
            </ol>
        </nav>
    </div>
</div>
<div class="row">
    <div class="col-md-3">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body pt-2">
                        <div class="row">
                           <div class="col-md-12"><br><h6>Data Type</h6></div>
                                <div class="form-group" style="text-align: center; width: 100%;">
                                    <form id="RadioFilter">
                                        <label class="radio-inline">
                                        <input type="radio" name="RadioFilter" class="radio-inline-custom RadioFilter" value="Not" Checked><strong>Not Forced Closed</strong></label>
                                        <label class="radio-inline">&nbsp;&nbsp;&nbsp;
                                        <input type="radio" name="RadioFilter" class="radio-inline-custom RadioFilter" value="Forced"><strong>Forced Closed</strong></label>
                                        <label class="radio-inline">&nbsp;&nbsp;&nbsp;
                                        <input type="radio" name="RadioFilter" class="radio-inline-custom RadioFilter" value="All"><strong>All</strong></label>
                                    </form>
                                </div> 
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12 pt-3">
                <div class="card">
                    <div class="card-body pt-2">
                        <div class="row">
                            
                            <div class="col-md-12"><br><h6>Filter Date By</h6></div>
                            <div class="form-group">
                                <select class="form-select form-select-sm" id="DateType">
                                    <option>Date Created</option>
                                    <option>Mach Check In</option>
                                    <option>QC Check In</option>
                                    <option>QC2 Check In</option>
                                    <option>Finishing Check In</option>
                                    <option>Closed Date</option>
                                </select>
                                <br>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="controls">
                                        <label for="txtFilterTanggal1" class="form-label fw-bold">Start Date</label>
                                        <div class="input-group input-group-sm">
                                            <input id="txtFilterTanggal1" name="txtFilterTanggal1" type="text" class="date-picker form-control" aria-describedby="txtFilterTanggal1Val" value="<?php echo $DateNow; ?>" readonly />
                                            <label for="txtFilterTanggal1" class="input-group-text" id="txtFilterTanggal1Val"><span class="bi bi-calendar-date text-dark"></span></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="controls">
                                        <label for="txtFilterTanggal2" class="form-label fw-bold">End Date</label>
                                        <div class="input-group input-group-sm">
                                            <input id="txtFilterTanggal2" name="txtFilterTanggal2" type="text" class="date-picker form-control" aria-describedby="txtFilterTanggal2Val" value="<?php echo $DateNow; ?>" readonly /><label for="txtFilterTanggal2" class="input-group-text" id="txtFilterTanggal2Val"><span class="bi bi-calendar-date text-dark"></span></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12"><br><h6>Custom Search</h6></div>
                            <div class="col-md-12"><div class="form-check">
                                <input class="form-check-input" type="checkbox" id="DateCheckDefault" checked>
                                <label class="form-check-label" for="DateCheckDefault">
                                Termasuk Filter Date
                                </label></div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="FilterCustom" class="form-label fw-bold">Category</label>
                                    <select class="form-select form-select-sm" id="FilterCustom">
                                        <option>Barcode ID</option>
                                        <option>PSM Barcode ID</option>
                                        <option>PPIC</option>
                                        <option>WOChild</option>
                                        <option>Part No</option>
                                        <option>Location</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="FilterKeywords" class="form-label fw-bold">Keywords</label>
                                    <input type="text" class="form-control form-control-sm" id="FilterKeywords" placeholder="Keywords" value="">
                                </div>
                            </div>
                                <div class="col-md-6 d-grid mt-2"><br>
                                    <button class="btn btn-sm btn-dark" id="ViewButton">View Data</button>
                                </div>
                                <div class="col-md-6 d-grid mt-2"><br>
                                    <button class="btn btn-sm btn-dark" id="BtnDownloadBarcode">Download</button>
                                </div> 
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-12 pt-3">
                <div class="card">
                    <div class="card-body pt-2">
                        <div class="row">
                            <div class="col-md-12"><h6>Filter Closed Time</h6></div>
                            <div class="col-md-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="UsedOpen">
                                    <label class="form-check-label" for="UsedOpen">Termasuk "OPEN"</label>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="FilterSeason" class="form-label fw-bold">Closed Time</label>
                                    <select class="form-select form-select-sm" id="FilterSeason">
                                        <?php 
                                        $QListClosedTimeF = GET_LIST_CLOSEDTIME_NOT_OPEN("",$linkMACHWebTrax);
                                        while($RListClosedTimeF = sqlsrv_fetch_array($QListClosedTimeF))
                                        {
                                            $ClosedTime = $RListClosedTimeF['ClosedTime'];
                                            ?>
                                            <option><?php echo $ClosedTime; ?></option>
                                            <?php
                                        }                
                                        ?>
                                    </select>
                                </div>
                            </div>                               
                            <div class="col-md-6 d-grid mt-2">
                                <button class="btn btn-sm btn-dark" id="ViewButton2">View Data</button>
                            </div>
                            <div class="col-md-6 d-grid mt-2">
                                <button class="btn btn-sm btn-dark" id="BtnDownloadBarcode2">Download</button>
                            </div> 
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
    <div class="col-md-9 pt-2"><div class="row" id="ContentResult"></div></div>
    <div class="col-md-12 mt-4"></div>
</div>
<script>
$(document).ready(function () {
    $('#txtFilterTanggal1').datetimepicker({
        lang:'en',
        timepicker:false,
        format:'m/d/Y',
        formatDate:'m/d/Y',
        theme:'dark'
    });
    $('#txtFilterTanggal2').datetimepicker({
        lang:'en',
        timepicker:false,
        format:'m/d/Y',
        formatDate:'m/d/Y',
        theme:'dark'
    });
    $('#TableBarcodeStatus').removeAttr('width').DataTable({
        "iDisplayLength": 5,
        "aLengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
        scrollY:        "430px",
        scrollX:        true,
        scrollCollapse: true,
        "autoWidth": true,
        "searching": false
    });
    $("#BtnDownloadBarcode").click(function(){
        var StartDate = $("#txtFilterTanggal1").val();
        var EndDate = $("#txtFilterTanggal2").val();
        var TipeDate = $("#DateType").val();
        var DataType = $('input[name=RadioFilter]:checked', '#RadioFilter').val();
        var Category = $("#FilterCustom").val();
        var Keywords = $("#FilterKeywords").val();
        var Used = "";
        var UsedOpen = "";
        var ClosedTime = "";
        if ($('#DateCheckDefault').is(":checked")) {
            Used = $('#DateCheckDefault').val();
        }
        else{ Used = "off";}
        window.location.href = 'project/Report/src/DownloadCSVBarcode.php?ds='+StartDate+'&&de='+EndDate+'&&typ='+TipeDate+'&&fil='+DataType+'&&used='+Used+'&&cat='+Category+'&&key='+Keywords+'&&op='+UsedOpen+'&&clo='+ClosedTime;
    });
    $("#BtnDownloadBarcode2").click(function(){
        var StartDate = "";
        var EndDate = "";
        var TipeDate = "";
        var DataType = $('input[name=RadioFilter]:checked', '#RadioFilter').val();
        var Category = "";
        var Keywords = "";
        var Used = "";
        var UsedOpen = "";
        var ClosedTime = $("#FilterSeason").val();
        if ($('#UsedOpen').is(":checked")) {
            UsedOpen = $('#UsedOpen').val();
        }
        else{ UsedOpen = "off";}
        window.location.href = 'project/Report/src/DownloadCSVBarcode.php?ds='+StartDate+'&&de='+EndDate+'&&typ='+TipeDate+'&&fil='+DataType+'&&used='+Used+'&&cat='+Category+'&&key='+Keywords+'&&op='+UsedOpen+'&&clo='+ClosedTime;
    });
      
    $("#TableBarcodeStatus tbody").css("font-size", "11px");
    $("#ViewButton").click(function(){
        var StartDate = $("#txtFilterTanggal1").val();
        var EndDate = $("#txtFilterTanggal2").val();
        var TipeDate = $("#DateType").val();
        var DataType = $('input[name=RadioFilter]:checked', '#RadioFilter').val();
        var Category = $("#FilterCustom").val();
        var Keywords = $("#FilterKeywords").val();
        var Used = "";
        var UsedOpen = "";
        var ClosedTime = "";
        if ($('#DateCheckDefault').is(":checked")) {
            Used = $('#DateCheckDefault').val();
        }
        else{ Used = "off";}
        var formdata = new FormData();
        formdata.append('ValStartDate', StartDate);
        formdata.append('ValEndDate', EndDate);
        formdata.append('ValTipeDate', TipeDate);
        formdata.append('ValDataType', DataType);
        formdata.append('ValUsedDate', Used);
        formdata.append('ValCategory', Category);
        formdata.append('ValKeywords', Keywords);
        formdata.append('UsedOpen', UsedOpen);
        formdata.append('ClosedTime', ClosedTime);
        $.ajax({
            url: 'project/Report/BarcodeStatusContent.php',
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: formdata,
            type: 'post',
            beforeSend: function () {
                $("#ViewButton").attr('disabled', true);
                $('#ContentResult').html("");
                $("#ContentResult").before('<div class="col-sm-12 d-flex justify-content-center" id="ContentLoading"><img src="../images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
                $('#ContentResult').html("");
            },
            success: function (xaxa) {
                $('#ContentResult').html("");
                $('#ContentResult').hide();
                $('#ContentResult').html(xaxa);
                $('#ContentResult').fadeIn('fast');
                $("#ContentLoading").remove();
                $("#ViewButton").blur();
                $("#ViewButton").attr('disabled', false);
                $('#TableBarcodeStatus').DataTable( {
                    "iDisplayLength": 5,
                    "aLengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
                    scrollY:        "430px",
                    scrollX:        true,
                    scrollCollapse: true,
                    autoWidth: true,
                    "searching": false
                });
                MODAL_DETAIL();
            },
            error: function () {
                alert("Request cannot proceed!");
                $("#ViewButton").blur();
                $('#ContentResult').html("");
                $("#ContentLoading").remove();
                $("#ViewButton").attr('disabled', false);
            }
        });
    });
    $("#ViewButton2").click(function(){
        var StartDate = "";
        var EndDate = "";
        var TipeDate = "";
        var DataType = $('input[name=RadioFilter]:checked', '#RadioFilter').val();
        var Category = "";
        var Keywords = "";
        var Used = "";
        var UsedOpen = "";
        var ClosedTime = $("#FilterSeason").val();
        if ($('#UsedOpen').is(":checked")) {
            UsedOpen = $('#UsedOpen').val();
        }
        else{ UsedOpen = "off";}
            var formdata = new FormData();
            formdata.append('ValStartDate', StartDate);
            formdata.append('ValEndDate', EndDate);
            formdata.append('ValTipeDate', TipeDate);
            formdata.append('ValDataType', DataType);
            formdata.append('ValUsedDate', Used);
            formdata.append('ValCategory', Category);
            formdata.append('ValKeywords', Keywords);
            formdata.append('UsedOpen', UsedOpen);
            formdata.append('ClosedTime', ClosedTime);
        $.ajax({
            url: 'project/Report/BarcodeStatusContent.php',
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: formdata,
            type: 'post',
            beforeSend: function () {
                $("#ViewButton2").attr('disabled', true);
                $('#ContentResult').html("");
                $("#ContentResult").before('<div class="col-sm-12 d-flex justify-content-center" id="ContentLoading"><img src="../images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
                $('#ContentResult').html("");
            },
            success: function (xaxa) {
                $('#ContentResult').html("");
                $('#ContentResult').hide();
                $('#ContentResult').html(xaxa);
                $('#ContentResult').fadeIn('fast');
                $("#ContentLoading").remove();
                $("#ViewButton2").blur();
                $("#ViewButton2").attr('disabled', false);
                $('#TableBarcodeStatus').DataTable( {
                    "iDisplayLength": 5,
                    "aLengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
                    scrollY:        "430px",
                    scrollX:        true,
                    scrollCollapse: true,
                    autoWidth: true
                });
                MODAL_DETAIL();
            },
            error: function () {
                alert("Request cannot proceed!");
                $("#ViewButton").blur();
                $('#ContentResult').html("");
                $("#ContentLoading").remove();
                $("#ViewButton").attr('disabled', false);
            }
        });
    });
    
});
function MODAL_DETAIL()
{
    $("#BCDetail").on('show.bs.modal', function (event) {
        var act = $(event.relatedTarget);
        var DataCode = act.data('ecode');
        var formdata = new FormData();
        formdata.append("ValCode", DataCode);
        $.ajax({
            url: 'project/Report/BarcodeStatusDetail.php',
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: formdata,
            type: 'post',
            beforeSend: function () {
                $('#LoadingImg').show();
                $('#DetailContent').html("");
            },
            success: function (xaxa) {
                $('#LoadingImg').hide();
                $('#DetailContent').hide();
                $('#DetailContent').html(xaxa);
                $('#DetailContent').fadeIn('fast');
                SRC_MOVING();
            },
            error: function () {
                $('#LoadingImg').hide();
                alert('Request cannot proceed!');
            }
        });
    });
}
</script>