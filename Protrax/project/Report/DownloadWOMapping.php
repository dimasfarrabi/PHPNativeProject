<?php
require_once("../webtrax/project/CostTracking/Modules/ModuleCostTracking.php");
require_once("project/Report/Modules/ModuleReport.php");
date_default_timezone_set("Asia/Jakarta");
$YearNow = date("Y");
$DateNow = date("m/d/Y");
?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.full.min.js" integrity="sha512-AIOTidJAcHBH2G/oZv9viEGXRqDNmfdPVPYOYKGy3fti0xIplnlgMHUGfuNRzC6FkzIo0iIxgFnr9RikFxK+sw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<link rel="stylesheet" type="text/css" href="lib/datetimepicker-master/jquery.datetimepicker.css"/>
<!-- <script src="project/Report/lib/LibWOMapping.js"></script> -->
<div class="row">
    <div class="col-md-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-detail ">
                <li class="breadcrumb-item"><a href="home.php">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Report : WO Mapping</li>
            </ol>
        </nav>
    </div>
</div>
<div class="row">
    <div class="col-md-3">
        <div class="row">
            <div class="col-md-12 pt-3">
                <div class="card">
                    <div class="card-body pt-2">
                        <div class="row">
                            <div class="col-md-12"><h6>Filter ClosedTime</h6></div>
                            <div class="col-md-12">
                                
                            </div><?php /*
                            <div class="col-12"><div class="form-check">
                                <input class="form-check-input" type="checkbox" id="PartNoDefault2">
                                <label class="form-check-label" for="PartNoDefault2">
                                Termasuk Part No
                                </label></div>
                            </div>*/ ?>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="FilterSeason" class="form-label fw-bold">Closed Time</label>
                                    <select class="form-select form-select-sm" id="FilterSeason">
                                        <option>OPEN</option>
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
                            <div class="col-md-12"><div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="UsedOpen">
                                    <label class="form-check-label" for="UsedOpen">Termasuk Open</label>
                                </div>
                            </div>
                            <!-- <div class="col-md-6 d-grid mt-2">
                                <button class="btn btn-sm btn-dark" id="">View Data</button>
                            </div>
                            <div class="col-md-6 d-grid mt-2">
                                <button class="btn btn-sm btn-dark" id="">Download</button>
                            </div>  -->
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12 pt-3">
                <div class="card">
                    <div class="card-body pt-2">
                        <div class="row">
                            <div class="col-md-12"><h6>Custom Search</h6></div>
                            <div class="col-md-12"><div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="UsedClosedTime" checked>
                                    <label class="form-check-label" for="UsedClosedTime">Dengan ClosedTime</label>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="FilterCustom" class="form-label fw-bold">Category</label>
                                    <select class="form-select form-select-sm" id="FilterCustom">
                                        <option>WO Mapping ID</option>
                                        <option>Quote</option>
                                        <option>Expense Allocation</option>
                                        <option>Product</option>
                                        <option>WO Child</option>
                                        <option>PM</option>
                                        <option>Location</option>
                                        <option>WO Type</option>
                                        <option>PSM Idx</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="FilterKeywords" class="form-label fw-bold">Keywords</label>
                                    <input type="text" class="form-control form-control-sm" id="FilterKeywords" placeholder="Keywords" value="">
                                </div>
                            </div>                                
                            <div class="col-md-6 d-grid mt-2">
                                <button class="btn btn-sm btn-dark" id="BtnViewWO">View Data</button>
                            </div>
                            <div class="col-md-6 d-grid mt-2">
                                <button class="btn btn-sm btn-dark" id="BtnDownloadWO">Download</button>
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
    $('#TableWOMapping').removeAttr('width').DataTable({
        "iDisplayLength": 5,
        "aLengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
        scrollY:        "430px",
        scrollX:        true,
        scrollCollapse: true,
        "autoWidth": true
    });
    // $("#BtnDownloadWO").click(function(){
    //     var Start = $("#txtFilterTanggal1").val();
    //     var End = $("#txtFilterTanggal2").val();
    //     var Type = $("#FilterCustom").val();
    //     var Keywords = $("#FilterKeywords").val().trim();
    //     // alert("Download Is On Process");
    //     window.location.href = 'project/Report/src/DownLoadCSVMaterial.php?ds='+Start+'&&de='+End+'&&typ='+Type+'&&key='+Keywords;
    // });
    $("#BtnDownloadWO").click(function(){
        var UsedCL = "";
        if ($('#UsedClosedTime').is(":checked")) {
            UsedCL = $('#UsedClosedTime').val();
        }
        else{ UsedCL = "off";}
        var Open = "";
        if ($('#UsedOpen').is(":checked")) {
            Open = $('#UsedOpen').val();
        }
        else{ Open = "off";}
        var Season = $("#FilterSeason").val();
        var Type = $("#FilterCustom").val();
        var Key = $("#FilterKeywords").val().trim();
        window.location.href = 'project/Report/src/DownLoadCSVWO.php?sea='+Season+'&&ucl='+UsedCL+'&&typ='+Type+'&&key='+Key+'&&op='+Open;
    });
      
    $("#TableWOMapping tbody").css("font-size", "11px");
    $("#BtnViewWO").click(function(){
        var UsedCL = "";
        if ($('#UsedClosedTime').is(":checked")) {
            UsedCL = $('#UsedClosedTime').val();
        }
        else{ UsedCL = "off";}
        var Open = "";
        if ($('#UsedOpen').is(":checked")) {
            Open = $('#UsedOpen').val();
        }
        else{ Open = "off";}
        var Season = $("#FilterSeason").val();
        var Type = $("#FilterCustom").val();
        var Key = $("#FilterKeywords").val().trim();
        var formdata = new FormData();
        formdata.append('ClosedTime', Season);
        formdata.append('FilterType', Type);
        formdata.append('Keywords', Key);
        formdata.append('UsedCL', UsedCL);
        formdata.append('Open', Open);
        $.ajax({
            url: 'project/Report/WOMappingContent.php',
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: formdata,
            type: 'post',
            beforeSend: function () {
                $("#BtnViewWO").attr('disabled', true);
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
                $("#BtnViewWO").blur();
                $("#BtnViewWO").attr('disabled', false);
                $('#TableWOMapping').DataTable( {
                    "iDisplayLength": 5,
                    "aLengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
                    scrollY:        "430px",
                    scrollX:        true,
                    scrollCollapse: true,
                    autoWidth: true
                });
            },
            error: function () {
                alert("Request cannot proceed!");
                $("#BtnViewWO").blur();
                $('#ContentResult').html("");
                $("#ContentLoading").remove();
                $("#BtnViewWO").attr('disabled', false);
            }
        });
    });
});
</script>