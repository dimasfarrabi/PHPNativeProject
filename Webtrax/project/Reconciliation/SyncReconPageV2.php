<?php
// require_once("../src/Modules/ModuleLogin.php");
require_once("project/Reconciliation/Module/ModuleRecon.php"); 
date_default_timezone_set("Asia/Jakarta");

$lastWeek = date('m/d/Y',strtotime("-7 days"));
$yesterday = date('m/d/Y',strtotime("-1 days"));
$thisMonth = date('m/Y',strtotime("-1 days"));

?>
<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb breadcrumb-detail">
            <li><a href="home.php">Home</a></li>
            <li class="active"><a href="home.php?link=34">Reconciliation : Synchronize Reconciliation</a></li>
        </ol>
    </div>
</div>

<div class="row">
    <div class="col-md-3">
        <div class="row" id="ListQuote">
            <div class="col-sm-12">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="ListSync">
                        <thead class="theadCustom">
                            <tr>
                                <th class="text-center">Data Report</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="PointerList">
                                <td class="text-left">Time Tracking</td>
                            </tr>
                            <tr class="PointerList">
                                <td class="text-left">Machine Tracking</td>
                            </tr>
                            <tr class="PointerList">
                                <td class="text-left">Material Tracking</td>
                            </tr>
                            <tr class="PointerList">
                                <td class="text-left">WO Mapping</td>
                            </tr>
                            <tr  class="PointerList">
                                <td class="text-left">Barcode Status</td>
                            </tr>
                            <tr  class="PointerList">
                                <td class="text-left">Spindle Hour</td>
                            </tr>
                            <tr  class="PointerList">
                                <td class="text-left">Raw Material</td>
                            </tr>
                            <tr  class="PointerList">
                                <td class="text-left">Inventory Out label</td>
                            </tr>
                            <tr  class="PointerList">
                                <td class="text-left">Attendance</td>
                            </tr>
                            <!-- <tr  class="PointerList">
                                <td class="text-left">Employee Leave Job</td>
                            </tr>
                            <tr  class="PointerList">
                                <td class="text-left">Raw Material</td>
                            </tr>
                            <tr  class="PointerList">
                                <td class="text-left">Tools Usage Tracking</td>
                            </tr> -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-9">
        <div class="row" id="PageFilter">
            
        </div>
        <div class="row" id="Content">

        </div>
    </div>
</div>


<script type="text/javascript">

var BolClick = "TRUE";
    if (BolClick == "TRUE") {
        $(".PointerList").click(function () {
            if (BolClick == "TRUE") {
                $("#ListSync tr").removeClass('PointerListSelected');
                $(this).closest('.PointerList').addClass("PointerListSelected");
                var ReportName = $(this).text();
                var formdata = new FormData();
                formdata.append('DataName', ReportName);
                $.ajax({
                    url: 'project/reconciliation/SyncFilterV2.php',
                    dataType: 'text',
                    cache: false,
                    contentType: false,
                    processData: false,
                    data: formdata,
                    type: 'post',
                    beforeSend: function () {
                        BolClick = "FALSE";
                        $('#PageFilter').html("");
                        $("#ContentLoading").remove();
                        $("#PageFilter").before('<div class="col-sm-12" id="ContentLoading"><img src="../images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
                        $('#PageFilter').html("");
                    },
                    success: function (xaxa) {
                        $('#PageFilter').html("");
                        $('#PageFilter').hide();
                        $('#Content').hide();
                        $('#PageFilter').html(xaxa);
                        $('#PageFilter').fadeIn('fast');
                        $("#ContentLoading").remove();
                        BolClick = "TRUE";
                        // alert(ReportName);
                    },
                    error: function () {
                        alert("Request cannot proceed!");
                        $('#PageFilter').html("");
                        $("#ContentLoading").remove();
                        BolClick = "TRUE";
                    }
                });
            }
            else {
                return false;
            }
        });
    }
</script>