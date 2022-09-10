<?php 
require_once("Modules/ModuleCostTracking.php"); 
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

if(isset($_SESSION['WOOpenPSL']))
{
    echo $_SESSION['WOOpenPSL'];
    unset($_SESSION['WOOpenPSL']);
}*/
/* <script src="project/costtracking/lib/libcosttracking.js?no=<?php echo base64_encode(date("mdyHis")); ?>"></script>*/ ?>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<div class="row">
    <div class="col-sm-12">
        <ol class="breadcrumb breadcrumb-detail">
            <li><a href="home.php">Home</a></li>
            <li class="active"><a href="home.php?link=5">Cost Tracking : WO Open</a></li>
        </ol>
    </div>
    <div class="col-sm-12"><h5><strong>Filter</strong></h5></div>
    <div class="col-sm-12">
        <div class="form-inline">
            <div class="form-group">
                <label for="InputQuoteCategory">QuoteCategory</label>
                <select class="form-control" id="InputQuoteCategory"><?php 
                $QListQuoteCategory = GET_LIST_QUOTE_CATEGORY("PSL",$linkMACHWebTrax);
                while($RListQuoteCategory = sqlsrv_fetch_array($QListQuoteCategory))
                {
                    $QuoteCategory = $RListQuoteCategory['QuoteCategory'];
                    ?>
                    <option><?php echo $QuoteCategory; ?></option>
                    <?php
                }                
                ?></select>
            </div>
            <div class="form-group">
                <button class="btn btn-dark btn-labeled" id="BtnViewWOOpenPSL">View Data</button> 
            </div>            
        </div>
    </div>
    <div class="col-sm-12"><hr></div>
    <div class="col-md-3">
        <div class="row" id="ListQuoteOpen">

        </div>
        <span id="TempQuote" class="InvisibleText"></span>
        <span id="TempLocation" class="InvisibleText"></span>
    </div>
    <div class="col-md-9">
        <div class="row" id="ListReportOpen">

        </div>
        <span id="TempFilter" class="InvisibleText"></span>
        <div class="row" id="ListOTSTop">

        </div>
        <div class="row" id="RunningWOP">

        </div>
        <div class="row" id="WOPCostDetail">

        </div>
    </div>
</div>
<script>
$(document).ready(function () {
    $("#BtnViewWOClosedPSL").click(function(){
        var ValTime = $("#InputClosedTime").children("option:selected").val();
        var ValQuoteCategory = $("#InputQuoteCategory").children("option:selected").val();
        $("#TempLocation").text("");                                                // *) sementara utk ganti lokasi yg disembunyikan
        var ValTitle = "";                                                          // *) sementara utk ganti lokasi yg disembunyikan
        $("#TempFilter").text(ValTime + "*" + ValQuoteCategory);
        var formdata = new FormData();
        formdata.append('ValTime', ValTime);
        formdata.append('ValQuoteCategory', ValQuoteCategory);
        formdata.append('ValType', ValTitle);
        if(ValQuoteCategory != "All"){
        $.ajax({
            url: 'project/costtracking/listquoteclosed.php',
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: formdata,
            type: 'post',
            beforeSend: function () {
                $("#BtnViewWOClosedPSL").attr('disabled', true);
                $('#ListQuote').html("");
                $("#ListQuote").before('<div class="col-sm-12" id="ContentLoading"><img src="../images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
                $('#ListQuote').html("");
                $('#ListReport').html("");
                $('#ListOTSTop').html("");
            },
            success: function (xaxa) {
                $('#ListQuote').html("");
                $('#ListQuote').hide();
                $('#ListQuote').html(xaxa);
                $('#ListQuote').fadeIn('fast');
                $("#ContentLoading").remove();
                $("#BtnViewWOClosedPSL").blur();
                $("#BtnViewWOClosedPSL").attr('disabled', false);
                SHOW_PIE_CHART_CLOSED()
            },
            error: function () {
                alert("Request cannot proceed!");
                $("#BtnViewWOClosedPSL").blur();
                $('#ListQuote').html("");
                $("#ContentLoading").remove();
                $("#BtnViewWOClosedPSL").attr('disabled', false);
            }
        
        });
    }
    else{}
    });


    $("#BtnViewWOOpenPSL").click(function () {
        var ValQuoteCategory = $("#InputQuoteCategory").children("option:selected").val();
        $("#TempLocation").text("");                                                // *) sementara utk ganti lokasi yg disembunyikan
        var ValTitle = "";                                                          // *) sementara utk ganti lokasi yg disembunyikan
        $("#TempFilter").text(ValQuoteCategory);
        var formdata = new FormData();
        formdata.append('ValQuoteCategory', ValQuoteCategory);
        formdata.append('ValType', ValTitle);
        $.ajax({
            url: 'project/costtracking/listquoteopen.php',
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: formdata,
            type: 'post',
            beforeSend: function () {
                $("#BtnViewWOOpenPSL").attr('disabled', true);
                $('#ListQuoteOpen').html("");
                $('#RunningWOP').hide();
                $("#ListQuoteOpen").before('<div class="col-sm-12" id="ContentLoading"><img src="../images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
                $('#ListQuoteOpen').html("");
                $('#ListReportOpen').html("");
                $('#ListOTSTop').html("");
            },
            success: function (xaxa) {
                $('#ListQuoteOpen').html("");
                $('#ListQuoteOpen').hide();
                $('#ListQuoteOpen').html(xaxa);
                $('#ListQuoteOpen').fadeIn('fast');
                $("#ContentLoading").remove();
                $("#BtnViewWOOpenPSL").blur();
                $("#BtnViewWOOpenPSL").attr('disabled', false);
                SHOW_PIE_CHART_OPEN(ValQuoteCategory);
            },
            error: function () {
                alert("Request cannot proceed!");
                $("#BtnViewWOOpenPSL").blur();
                $('#ListQuoteOpen').html("");
                $("#ContentLoading").remove();
                $("#BtnViewWOOpenPSL").attr('disabled', false);
            }
        });
    });
});


function SHOW_PIE_CHART_CLOSED()
{
    var DataFilterSelected = $("#TempFilter").text();
    var formdata = new FormData();
    formdata.append('ValFilter', DataFilterSelected);
    $.ajax({
        url: 'project/costtracking/chartwoclosed.php',
        dataType: 'text',
        cache: false,
        contentType: false,
        processData: false,
        data: formdata,
        type: 'post',
        beforeSend: function () {
            $('#ListReport').html("");
            $("#ListReport").before('<div class="col-sm-12" id="ContentLoadingChart"><img src="../images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
            $('#ListReport').html("");
        },
        success: function (xaxa) {
            $('#ListReport').html("");
            $('#ListReport').hide();
            $('#ListReport').html(xaxa);
            $('#ListReport').fadeIn('fast');
            $("#ContentLoadingChart").remove();
        },
        error: function () {
            alert("Request cannot proceed!");
            $('#ListReport').html("");
            $("#ContentLoadingChart").remove();
        }
    });
}
function SHOW_PIE_CHART_OPEN(Data1)
{
    var Category = Data1;
    var DataFilterSelected = $("#TempFilter").text();
    if(Category == "Quote")
    {
        var formdata = new FormData();
        formdata.append('ValFilter', Category);
        $.ajax({
            url: 'project/costtracking/chartwoopen.php',
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: formdata,
            type: 'post',
            beforeSend: function () {
                $('#ListReportOpen').html("");
                $('#RunningWOP').hide();
                $("#ListReportOpen").before('<div class="col-sm-12" id="ContentLoadingChart"><img src="../images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
                $('#ListReportOpen').html("");
            },
            success: function (xaxa) {
                $('#ListReportOpen').html("");
                $('#ListReportOpen').hide();
                $('#ListReportOpen').html(xaxa);
                $('#ListReportOpen').fadeIn('fast');
                $("#ContentLoadingChart").remove();
                var BoolClick = "TRUE";
                $(".DataParentQuote").click(function () {
                    if (BoolClick == "TRUE") {
                        $("#TableTotalActual tr").removeClass('PointerListSelected');
                        $(this).closest('.DataParentQuote').addClass("PointerListSelected");
                        var FloatData = $(this).data('float');
                        var ValTitle = '';
                        const myArray = FloatData.split("*");
                        var formdata = new FormData();
                        formdata.append('ValQuoteSelected', myArray[0]);
                        formdata.append('ValType', ValTitle);
                        formdata.append('ValQuoteCategory', myArray[2]);
                        $.ajax({
                            url: 'project/costtracking/listreportopen.php',
                            dataType: 'text',
                            cache: false,
                            contentType: false,
                            processData: false,
                            data: formdata,
                            type: 'post',
                            beforeSend: function () {
                                BolClickListQuoteOpen = "FALSE";
                                $("html, body").animate({ scrollTop: $("#ListReportOpen").offset().top }, "slow");
                                $("#BtnViewWOOpenPSM").attr('disabled', true);
                                $('#ListReportOpen').html("");
                                $("#ContentLoading").remove();
                                $("#ListReportOpen").before('<div class="col-sm-12" id="ContentLoading"><img src="../images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
                                $('#ListReportOpen').html("");
                                $('#ListOTSTop').html("");
                                $('#RunningWOP').hide();
                                $('#WOPCostDetail').hide();
                            },
                            success: function (xaxa) {
                                $('#ListReportOpen').html("");
                                $('#ListReportOpen').hide();
                                $('#ListReportOpen').html(xaxa);
                                $('#ListReportOpen').fadeIn('fast');
                                $("#ContentLoading").remove();
                                $("#BtnViewWOOpenPSM").blur();
                                $("#BtnViewWOOpenPSM").attr('disabled', false);
                                BACK_TO_CHART_OPEN(myArray[2]);
                                BolClickListQuoteOpen = "TRUE";
                                SHOW_RUNNING_WOP(myArray[0]);
                            },
                            error: function () {
                                alert("Request cannot proceed!");
                                $("#BtnViewWOOpenPSM").blur();
                                $('#ListReportOpen').html("");
                                $("#ContentLoading").remove();
                                $("#BtnViewWOOpenPSM").attr('disabled', false);
                                BolClickListQuoteOpen = "TRUE";
                            }
                        });
                    }
                    else {
                        return false;
                    }
                });
            },
            error: function () {
                alert("Request cannot proceed!");
                $('#ListReportOpen').html("");
                $("#ContentLoadingChart").remove();
            }
        });
    }
    else
    {
        var formdata = new FormData();
        formdata.append('ValFilter', Category);
        $.ajax({
            url: 'project/costtracking/ChartWOOpenUnquote.php',
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: formdata,
            type: 'post',
            beforeSend: function () {
                $('#ListReportOpen').html("");
                $('#RunningWOP').hide();
                $("#ListReportOpen").before('<div class="col-sm-12" id="ContentLoadingChart"><img src="../images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
                $('#ListReportOpen').html("");
            },
            success: function (xaxa) {
                $('#ListReportOpen').html("");
                $('#ListReportOpen').hide();
                $('#ListReportOpen').html(xaxa);
                $('#ListReportOpen').fadeIn('fast');
                $("#ContentLoadingChart").remove();
                var BoolClick = "TRUE";
                $(".DataParent").click(function () {
                    if (BoolClick == "TRUE") {
                        $("#TableTotalActual tr").removeClass('PointerListSelected');
                        $(this).closest('.DataParent').addClass("PointerListSelected");
                        var FloatData = $(this).data('float');
                        SHOW_INSIDE_QUOTE(FloatData);
                    }
                    else {
                        return false;
                    }
                });
            },
            error: function () {
                alert("Request cannot proceed!");
                $('#ListReportOpen').html("");
                $("#ContentLoadingChart").remove();
            }
        });
    }
}
function SHOW_INSIDE_QUOTE(Datax)
{
    var formdata = new FormData();
    formdata.append('DataFloat', Datax);
    $.ajax({
        url: 'project/costtracking/UnquoteReportOpen.php',
        dataType: 'text',
        cache: false,
        contentType: false,
        processData: false,
        data: formdata,
        type: 'post',
        beforeSend: function () {
            $("html, body").animate({ scrollTop: $("#ListReportOpen").offset().top -10 }, "fast");
            $('#ListReportOpen').html("");
            $('#RunningWOP').hide();
            $("#ListReportOpen").before('<div class="col-sm-12" id="ContentLoadingChart"><img src="../images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
            $('#ListReportOpen').html("");
        },
        success: function (xaxa) {
            $('#ListReportOpen').html("");
            $('#ListReportOpen').hide();
            $('#ListReportOpen').html(xaxa);
            $('#ListReportOpen').fadeIn('fast');
            $("#ContentLoadingChart").remove();
            $(".InfoChart").click(function () {
                var DataFilterSelected = $("#TempFilter").text();
                $("#TableListQuote tr").removeClass('PointerListQuoteOpenSelected');
                SHOW_PIE_CHART_OPEN(DataFilterSelected);
            });
            $(".DataChild").click(function () {
                var BoolClick = "TRUE";
                if (BoolClick == "TRUE") {
                    $("#QuoteCost tr").removeClass('PointerListSelected');
                    $(this).closest('.DataChild').addClass("PointerListSelected");
                    var EncData = $(this).data('float');
                    DETAIL_WOP(EncData);
                }
                else {
                    return false;
                }
            });
        },
        error: function () {
            alert("Request cannot proceed!");
            $('#ListReportOpen').html("");
            $("#ContentLoadingChart").remove();
        }
    });
}
function DETAIL_WOP(a)
{
    var formdata = new FormData();
    formdata.append('aFloat', a);
    $.ajax({
        url: 'project/costtracking/UnquoteMaterialDetail.php',
        dataType: 'text',
        cache: false,
        contentType: false,
        processData: false,
        data: formdata,
        type: 'post',
        beforeSend: function () {
            $("html, body").animate({ scrollTop: $("#MaterialDetail").offset().top -10 }, "fast");
            $('#MaterialDetail').html("");
            $("#MaterialDetail").before('<div class="col-sm-12" id="ContentLoadingChart"><img src="../images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
            $('#MaterialDetail').html("");
        },
        success: function (xaxa) {
            $('#MaterialDetail').html("");
            $('#MaterialDetail').hide();
            $('#MaterialDetail').html(xaxa);
            $('#MaterialDetail').fadeIn('fast');
            $("#ContentLoadingChart").remove();
        },
        error: function () {
            alert("Request cannot proceed!");
            $('#MaterialDetail').html("");
            $("#ContentLoadingChart").remove();
        }
    });
}
</script>