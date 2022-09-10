<?php
require_once("src/Modules/ModuleLogin.php");
require_once("Modules/ModuleBarcodePart.php");
date_default_timezone_set("Asia/Jakarta");
$YearNow = date("Y");
$DateNow = date("m/d/Y");
/*
if(!session_is_registered("UIDWebTrax"))
{
    ?>
    <script language="javascript">
        window.location.replace("https://webtrax.formulatrix.com/");
    </script>
    <?php
    exit();
}
if($AccessLogin == "Reguler")
{
    ?>
    <script language="javascript">
        window.location.href = "home.php";
    </script>
    <?php
    exit();
}
*/
$ArrListQuote = array();
$QListCategory = QUOTE_LIST($linkMACHWebTrax);
    while($RListCategory = sqlsrv_fetch_array($QListCategory))
    {
        $TempArray = array(
            "Quote" => trim($RListCategory['Quote']),
            "Location" => "PSL",
            "ProjectID" => trim($RListCategory['ProjectID'])
        );
        array_push($ArrListQuote,$TempArray);
    }
    asort($ArrListQuote);
    
?>
<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb breadcrumb-detail">
            <li><a href="home.php">Home</a></li>
            <li class="active"><a href="home.php?link=36">Production : PPIC Barcode Part</a></li>
        </ol>
    </div>
</div>

<div class="row">
    <div class="col-md-3">
        <div class="row" id="">
            <div class="col-sm-12">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="ListCategory">
                        <thead class="theadCustom">
                            <tr>
                                <th class="text-center">Quote</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php 
                        foreach($ArrListQuote as $ListQuote)
                        {
                            $Quote = $ListQuote['Quote'];
                            if ($Quote == 'NT8 V4'){ $Quote = "NT8 SYSTEMS";}
                            $EncLocation = base64_encode(base64_encode($ListQuote['Location']));
                            echo '<tr data-id="'.$ListQuote['ProjectID'].'" data-log="'.$EncLocation.'" class="PointerList">';
                            echo '<td>'.$Quote.'</td>';
                            echo '</tr>';
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <span id="TempQuote" class="InvisibleText"></span>
    </div>
    <div class="col-md-9">
        <div class="row" id="ResultCategory">

        </div>
        <span id="TempWOP" class="InvisibleText"></span>
        <div class="row" id="StageDetail">

        </div>
        <div class="row" id="ListBarcode">

        </div>
    </div>
</div>
<script>
$(document).ready(function () {
    var BolClickListCategory = "TRUE";
    if (BolClickListCategory == "TRUE") {
        $(".PointerList").click(function () {
            if (BolClickListCategory == "TRUE") {
                $("#ListCategory tr").removeClass('PointerListSelected');
                $(this).closest('.PointerList').addClass("PointerListSelected");
                var QuoteName = $(this).text();
                var ProjectID = $(this).data('id');
                var Location = $(this).data('log');
                $("#TempQuote").text(QuoteName);
                var formdata = new FormData();
                formdata.append('ValQuoteName', QuoteName);
                formdata.append('ValProjectID', ProjectID);
                formdata.append('ValLocation', Location);
                $.ajax({
                    url: 'project/wipsims/BarcodePartContent.php',
                    dataType: 'text',
                    cache: false,
                    contentType: false,
                    processData: false,
                    data: formdata,
                    type: 'post',
                    beforeSend: function () {
                        BolClickListCategory = "FALSE";
                        $("html, body").animate({ scrollTop: $("#ResultCategory").offset().top - 20 }, "fast");
                        $('#ResultCategory').html("");
                        $('#StageDetail').html("");
                        $("#ContentLoading").remove();
                        $("#ResultCategory").before('<div class="col-sm-12" id="ContentLoading"><img src="../images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
                        $('#ResultCategory').html("");
                        $('#ListBarcode').hide();
                    },
                    success: function (xaxa) {
                        $('#ResultCategory').html("");
                        $('#ResultCategory').hide();
                        $('#ResultCategory').html(xaxa);
                        $('#ResultCategory').fadeIn('fast');
                        $("#ContentLoading").remove();
                        BolClickListCategory = "TRUE";
                        STAGE_DETAIL();
                    },
                    error: function () {
                        alert("Request cannot proceed!");
                        $('#ResultCategory').html("");
                        $("#ContentLoading").remove();
                        BolClickListCategory = "TRUE";
                    }
                });
            }
            else {
                return false;
            }
        });
    }
});
function STAGE_DETAIL()
{
    var BoolClick = "TRUE";
    $(".DataParent").click(function () {
        if (BoolClick == "TRUE") {
            $("#TableWOP tr").removeClass('PointerListSelected');
            $(this).closest('.DataParent').addClass("PointerListSelected");
            var FloatData = $(this).data('float');
            var formdata = new FormData();
            formdata.append("ValFloat", FloatData);          
            $.ajax({
                url: 'project/WIPSims/PartDetailStage.php',
                dataType: 'text',
                cache: false,
                contentType: false,
                processData: false,
                data: formdata,
                type: 'post',
                beforeSend: function () {
                    BoolClick = "FALSE";
                    $("html, body").animate({ scrollTop: $("#StageDetail").offset().top }, "fast");
                    $('#StageDetail').html("");
                    $("#ContentLoading").remove();
                    $("#StageDetail").before('<div class="col-sm-12" id="ContentLoading"><img src="../images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
                    $('#StageDetail').html("");
                    $('#ListBarcode').hide();
                },
                success: function (xaxa) {
                    $('#StageDetail').html("");
                    $('#StageDetail').hide();
                    $('#StageDetail').html(xaxa);
                    $('#StageDetail').fadeIn('fast');
                    $("#ContentLoading").remove();
                    BoolClick = "TRUE";
                    LIST_BARCODE();
                },
                error: function () {
                    alert("Request cannot proceed!");
                    $('#ListBarcode').html("");
                    $("#ContentLoading").remove();
                    BoolClick = "TRUE";
                }
            });
        }
        else {
            return false;
        }
    });
}
function LIST_BARCODE()
{
    var BoolClick = "TRUE";
    $(".DataChild").click(function () {
        if (BoolClick == "TRUE") {
            $("#TableStage tr").removeClass('PointerListSelected');
            $(this).closest('.DataChild').addClass("PointerListSelected");
            var FloatData = $(this).data('float');
            var formdata = new FormData();
            formdata.append("ValFloat", FloatData);          
            $.ajax({
                url: 'project/WIPSims/BarcodeListDetail.php',
                dataType: 'text',
                cache: false,
                contentType: false,
                processData: false,
                data: formdata,
                type: 'post',
                beforeSend: function () {
                    BoolClick = "FALSE";
                    $("html, body").animate({ scrollTop: $("#ListBarcode").offset().top }, "fast");
                    $('#ListBarcode').html("");
                    $("#ContentLoading").remove();
                    $("#ListBarcode").before('<div class="col-sm-12" id="ContentLoading"><img src="../images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
                    $('#ListBarcode').html("");
                },
                success: function (xaxa) {
                    $('#ListBarcode').html("");
                    $('#ListBarcode').hide();
                    $('#ListBarcode').html(xaxa);
                    $('#ListBarcode').fadeIn('fast');
                    $("#ContentLoading").remove();
                    BoolClick = "TRUE";
                },
                error: function () {
                    alert("Request cannot proceed!");
                    $('#StageDetail').html("");
                    $("#ContentLoading").remove();
                    BoolClick = "TRUE";
                }
            });
        }
        else {
            return false;
        }
    });
}
</script>
