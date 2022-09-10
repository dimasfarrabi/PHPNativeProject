<?php
require_once("project/WIPSims/Modules/ModuleWIPSims.php"); 
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
?>
<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb breadcrumb-detail">
            <li><a href="home.php">Home</a></li>
            <li class="active"><a href="home.php?link=41">Production : WIP</a></li>
        </ol>
    </div>
</div>

<div class="row"><?php /*
    <div class="col-sm-12"><h5><strong>Filter</strong></h5></div>
    <div class="col-sm-12">
        <div class="form-inline">
            <div class="form-group">
                <label for="InputQuoteCategory">Quote Category</label>
                <select class="form-control" id="InputQuoteCategory"><?php 
                $QListQuoteCategory = GET_LIST_QUOTE_CATEGORY($linkMACHWebTrax);
                while($RListQuoteCategory = mssql_fetch_assoc($QListQuoteCategory))
                {
                    $QuoteCategory = $RListQuoteCategory['QuoteCategory'];
                    ?>
                    <option><?php echo $QuoteCategory; ?></option>
                    <?php
                }                
                ?></select>
            </div>
            <div class="form-group">
                <button class="btn btn-dark btn-labeled" id="BtnViewQuote">View Data</button> 
            </div>           
        </div>
    </div>
    <div class="col-sm-12"><hr></div>*/ ?>
    <div class="col-md-3">
        <div class="row" id="ListQuote"></div>
        <span id="TempQuote" class="InvisibleText"></span>
    </div>
    <div class="col-md-9">
        <div class="row" id="ResultCategory"></div><div class="row" id="ListDetailPart"></div>
        <span id="TempFilter" class="InvisibleText"></span>
    </div>
</div>
<script>
$(document).ready(function () {
    var ValQuoteCategory = $("#InputQuoteCategory").children("option:selected").val();
    var formdata = new FormData();
    $.ajax({
        url: 'project/wipsims/wiplistquoteV2.php',
        dataType: 'text',
        cache: false,
        contentType: false,
        processData: false,
        data: formdata,
        type: 'post',
        beforeSend: function () {
            $("#BtnViewQuote").attr('disabled', true);
            $('#ListQuote').html("");
            $("#ListQuote").before('<div class="col-sm-12" id="ContentLoading"><img src="../images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
            $('#ListQuote').html("");
            $('#ResultCategory').html("");
            $('#ListDetailPart').html("");
        },
        success: function (xaxa) {
            $('#ListQuote').html("");
            $('#ListQuote').hide();
            $('#ListQuote').html(xaxa);
            $('#ListQuote').fadeIn('fast');
            $("#ContentLoading").remove();
            $("#BtnViewQuote").blur();
            $("#BtnViewQuote").attr('disabled', false);
            TEMPLATE_CHECK();
        },
        error: function () {
            alert("Request cannot proceed!");
            $("#BtnViewQuote").blur();
            $('#ListQuote').html("");
            $("#ContentLoading").remove();
            $("#BtnViewQuote").attr('disabled', false);
        }
    });
})
function TEMPLATE_CHECK()
{
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
                    url: 'project/wipsims/wipsimscontentV2.php',
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
                        $('#ListDetailPart').html("");
                        $("#ContentLoading").remove();
                        $("#ResultCategory").before('<div class="col-sm-12" id="ContentLoading"><img src="../images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
                        $('#ResultCategory').html("");
                    },
                    success: function (xaxa) {
                        $('#ResultCategory').html("");
                        $('#ResultCategory').hide();
                        $('#ResultCategory').html(xaxa);
                        $('#ResultCategory').fadeIn('fast');
                        $("#ContentLoading").remove();
                        BolClickListCategory = "TRUE";
                        PART_CHECK();
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
}
function PART_CHECK()
{
    var BolClickPartNo = "TRUE";
    if (BolClickPartNo == "TRUE")
    {
        $("#TableParentPart .PointerList").click(function () {
            $("#TableParentPart tr").removeClass('PointerListSelected');
            $(this).closest('.PointerList').addClass("PointerListSelected");
            var PartNo = $(this).data('id');
            $("#TempFilter").text(PartNo);
            var QuoteName = $("#TempQuote").text();
            var Location = $(this).data('log');
            var formdata = new FormData();
            formdata.append('ValQuoteName', QuoteName);
            formdata.append('ValPartNo', PartNo);
            formdata.append('ValLocation', Location);
            $.ajax({
                url: 'project/wipsims/wipsimscontentpartV2.php',
                dataType: 'text',
                cache: false,
                contentType: false,
                processData: false,
                data: formdata,
                type: 'post',
                beforeSend: function () {
                    BolClickPartNo = "FALSE";
                    $("html, body").animate({ scrollTop: $("#ListDetailPart").offset().top - 20 }, "fast");
                    $('#ListDetailPart').html("");
                    $("#ContentLoading").remove();
                    $("#ListDetailPart").before('<div class="col-sm-12" id="ContentLoading"><img src="../images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
                    $('#ListDetailPart').html("");
                },
                success: function (xaxa) {
                    $('#ListDetailPart').html("");
                    $('#ListDetailPart').hide();
                    $('#ListDetailPart').html(xaxa);
                    $('#ListDetailPart').fadeIn('fast');
                    $("#ContentLoading").remove();
                    BolClickPartNo = "TRUE";
                    // DOWNLOAD_CSV(PartNo);
                },
                error: function () {
                    alert("Request cannot proceed!");
                    $('#ListDetailPart').html("");
                    $("#ContentLoading").remove();
                    BolClickPartNo = "TRUE";
                }
            });
        });
    }
}
// function DOWNLOAD_CSV(InputTemplate)
// {
//     $("#DownloadCSV").click(function(){
//         window.location.href = 'project/WIPSims/_DownloadDetailTemplate.php?template='+InputTemplate;
//     });
// }
</script>