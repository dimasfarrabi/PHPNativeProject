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
            <li class="active"><a href="home.php?link=35">Reconciliation : Synchronize Reconciliation</a></li>
        </ol>
    </div>
</div>

<div class="row">
    <div class="col-md-3">
        <div class="row" id="ListQuote">
            <div class="col-sm-12">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="ListData">
                        <thead class="theadCustom">
                            <tr>
                                <th class="text-center">Data Report</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="PointerList">
                                <td class="text-left">WO Mapping</td>
                            </tr>
                            <tr class="PointerList">
                                <td class="text-left">Time Tracking</td>
                            </tr>
                            <tr class="PointerList">
                                <td class="text-left">Material Tracking</td>
                            </tr>
                            <tr class="PointerList">
                                <td class="text-left">Machine Tracking</td>
                            </tr>
                            <tr  class="PointerList">
                                <td class="text-left">Barcode Status</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-9">
        <div class="row" id="FilPage">
            
        </div>
        <div class="row" id="SyncTable">

        </div>
    </div>
</div>


<script type="text/javascript">

var BolClick = "TRUE";
    if (BolClick == "TRUE") {
        $(".PointerList").click(function () {
            if (BolClick == "TRUE") {
                $("#ListData tr").removeClass('PointerListSelected');
                $(this).closest('.PointerList').addClass("PointerListSelected");
                var ReportName = $(this).text();
                // $("#TempQuote").text(QuoteName);
                var formdata = new FormData();
                formdata.append('DataName', ReportName);
                // formdata.append('ValProjectID', ProjectID);
                // formdata.append('ValLocation', Location);
                $.ajax({
                    url: 'project/reconciliation/SyncFilter.php',
                    dataType: 'text',
                    cache: false,
                    contentType: false,
                    processData: false,
                    data: formdata,
                    type: 'post',
                    beforeSend: function () {
                        BolClick = "FALSE";
                        $("html, body").animate({ scrollTop: $("#FilPage").offset().top - 20 }, "fast");
                        $('#FilPage').html("");
                        $("#ContentLoading").remove();
                        $("#FilPage").before('<div class="col-sm-12" id="ContentLoading"><img src="../images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
                        $('#FilPage').html("");
                    },
                    success: function (xaxa) {
                        $('#FilPage').html("");
                        $('#FilPage').hide();
                        $('#SyncTable').hide();
                        $('#FilPage').html(xaxa);
                        $('#FilPage').fadeIn('fast');
                        $("#ContentLoading").remove();
                        BolClick = "TRUE";
                        // alert(ReportName);
                    },
                    error: function () {
                        alert("Request cannot proceed!");
                        $('#FilPage').html("");
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