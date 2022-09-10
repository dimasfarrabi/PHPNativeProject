<?php 
require_once("Modules/ModuleCostTrackingChart.php"); 
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
<?php /*<script src="project/costtracking/lib/libwochart.js?no=<?php echo base64_encode(date("mdyHis")); ?>"></script> */?>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<div class="row">
    <div class="col-sm-12">
        <ol class="breadcrumb breadcrumb-detail">
            <li><a href="home.php">Home</a></li>
            <li class="active"><a href="home.php?link=20">Cost Tracking : WO Closed (Periodical) Chart</a></li>
        </ol>
    </div>
    <div class="col-sm-12"><h5><strong>Filter</strong></h5></div>
    <div class="col-sm-12">
        <div class="form-inline">
            <div class="form-group">
                <label for="InputQuoteCategory">QuoteCategory</label>
                <select class="form-control" id="InputQuoteCategory">
                    <option value="<?php echo base64_encode(base64_encode("Category#Quote#CLOSE")); ?>">Quote</option>
                    <option value="<?php echo base64_encode(base64_encode("Category#Unquote#CLOSE")); ?>">Unquote</option>
                    <?php /*<option value="<?php echo base64_encode(base64_encode("Category#All#CLOSE")); ?>">All</option>*/ ?>
                </select>
            </div>
            <div class="form-group">
                <button class="btn btn-dark btn-labeled" id="BtnViewWOClosed">View Data</button> 
            </div>           
        </div>
    </div>
    <div class="col-sm-12"><hr></div>
    <div class="col-md-3">
        <div class="row" id="ListQuote">

        </div>
        <span id="TempQuote" class="InvisibleText"></span>
        <span id="TempContent" class="InvisibleText"></span>
    </div>
    <div class="col-md-9">
        <div class="row" id="ResultChart">

        </div>
        <div class="row" id="SumChart">
            
        </div>
        <div class="row">
            <div class="col-md-9" id="ChartContentDetail" style="margin-top:20px">

            </div>
        </div>
    </div>
</div>
<script>
$(document).ready(function () {
    $("#BtnViewWOClosed").click(function(){
        var ValQuoteCategory = $("#InputQuoteCategory").children("option:selected").val();
        $("#TempQuote").text(ValQuoteCategory);
        var formdata = new FormData();
        formdata.append('ValQuoteCategory', ValQuoteCategory);
        $.ajax({
            url: 'project/CostTracking/WOChartListProject.php',
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: formdata,
            type: 'post',
            beforeSend: function () {
                $("#BtnViewWOClosed").attr('disabled', true);
                $('#ListQuote').html("");
                $("#ListQuote").before('<div class="col-sm-3" id="ContentLoading"><img src="../images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
                $('#ListQuote').html("");
                $('#TempContent').html("");
                $('#ResultChart').html("");
                $('#ChartContentDetail').hide();
            },
            success: function (xaxa) {
                $('#ListQuote').html("");
                $('#ListQuote').hide();
                $('#ListQuote').html(xaxa);
                $('#ListQuote').fadeIn('fast');
                LISTDATAPROJECT();
                $("#ContentLoading").remove();
                $("#BtnViewWOClosed").blur();
                $("#BtnViewWOClosed").attr('disabled', false);
            },
            error: function () {
                alert("Request cannot proceed!");
                $("#BtnViewWOClosed").blur();
                $('#ListQuote').html("");
                $('#TempContent').html("");
                $('#ResultChart').html("");
                $("#ContentLoading").remove();
                $("#BtnViewWOClosed").attr('disabled', false);
            }
        });
        $.ajax({
            url: 'project/CostTracking/WOClosedSumChart.php',
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: formdata,
            type: 'post',
            beforeSend: function () {
                $("#BtnViewWOClosed").attr('disabled', true);
                $('#SumChart').html("");
                $("#SumChart").before('<div class="col-sm-9" id="ContentLoading2"><img src="../images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
                $('#SumChart').html("");
                $('#TempContent').html("");
                $('#ResultChart').html("");
                $('#ChartContentDetail').hide();
            },
            success: function (xaxa) {
                $('#SumChart').html("");
                $('#SumChart').hide();
                $('#SumChart').html(xaxa);
                $('#SumChart').fadeIn('fast');
                $("#ContentLoading2").remove();
                $("#BtnViewWOClosed").blur();
                $("#BtnViewWOClosed").attr('disabled', false);
            },
            error: function () {
                alert("Request cannot proceed!");
                $("#BtnViewWOClosed").blur();
                $('#SumChart').html("");
                $('#TempContent').html("");
                $('#ResultChart').html("");
                $("#ContentLoading2").remove();
                $("#BtnViewWOClosed").attr('disabled', false);
            }
        });
    });
    $("#BtnViewWOClosed").trigger("click");
    $("#BtnViewWOOpen").click(function () {
        var ValQuoteCategory = $("#InputQuoteCategory").children("option:selected").val();
        $("#TempQuote").text(ValQuoteCategory);
        var formdata = new FormData();
        formdata.append('ValQuoteCategory', ValQuoteCategory);
        $.ajax({
            url: 'project/CostTracking/WOChartListProject.php',
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: formdata,
            type: 'post',
            beforeSend: function () {
                $("#BtnViewWOOpen").attr('disabled', true);
                $('#ListQuote').html("");
                $("#ListQuote").before('<div class="col-sm-12" id="ContentLoading"><img src="../images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
                $('#ListQuote').html("");
                $('#TempContent').html("");
                $('#ResultChart').html("");
                $('#ChartContentDetail').hide();
            },
            success: function (xaxa) {
                $('#ListQuote').html("");
                $('#ListQuote').hide();
                $('#ListQuote').html(xaxa);
                $('#ListQuote').fadeIn('fast');
                LISTDATAPROJECT();
                $("#ContentLoading").remove();
                $("#BtnViewWOOpen").blur();
                $("#BtnViewWOOpen").attr('disabled', false);
            },
            error: function () {
                alert("Request cannot proceed!");
                $("#BtnViewWOOpen").blur();
                $('#ListQuote').html("");
                $('#TempContent').html("");
                $('#ResultChart').html("");
                $("#ContentLoading").remove();
                $("#BtnViewWOOpen").attr('disabled', false);
            }
        });
    });
});
function LISTDATAPROJECT()
{
    var BolPointerListProject = "TRUE";
    $(".PointerListProject").click(function(){
        if (BolPointerListProject == "TRUE")
        {
            $("#TableListProject tr").removeClass("PointerListSelected");
            $(this).closest(".PointerListProject").addClass("PointerListSelected");
            var ProjectName = $(this).data('split');
            var QuoteCategory = $("#TempQuote").text();
            $("#TempContent").html(ProjectName);
            var formdata = new FormData();
            formdata.append('ValCategory', QuoteCategory);
            formdata.append('ValProject', ProjectName);
            $.ajax({
                url: 'project/CostTracking/WOChartListContent.php',
                dataType: 'text',
                cache: false,
                contentType: false,
                processData: false,
                data: formdata,
                type: 'post',
                beforeSend: function () {
                    BolPointerListProject = "FALSE";
                    $("html, body").animate({ scrollTop: $("#ResultChart").offset().top - 20 }, "fast");
                    $('#ResultChart').html("");
                    $("#ResultChart").before('<div class="col-sm-12" id="ContentLoading2"><img src="../images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
                    $('#ResultChart').html("");
                    $('#SumChart').hide();
                    $('#ChartContentDetail').hide();
                },
                success: function (xaxa) {
                    $('#ResultChart').html("");
                    $('#ResultChart').hide();
                    $('#ResultChart').html(xaxa);
                    $('#ResultChart').fadeIn('fast');
                    $("#ContentLoading2").remove();
                    $("#btn-labeled").blur();
                    $('#SumChart').hide();
                    BolPointerListProject = "TRUE";
                },
                error: function () {
                    alert("Request cannot proceed!");
                    $("#btn-labeled").blur();
                    $('#ResultChart').html("");
                    $("#ContentLoading2").remove();
                    BolPointerListProject = "TRUE";
                }
            });
        }
        else
        {
            return false;
        }
    });
}
function DETAIL_CHART(Data1,Data2)
{
    var formdata = new FormData();
    formdata.append('ValCategory', Data2);
    formdata.append('ValYear', Data1);
    $.ajax({
        url: 'project/CostTracking/WOChartSumDetail.php',
        dataType: 'text',
        cache: false,
        contentType: false,
        processData: false,
        data: formdata,
        type: 'post',
        beforeSend: function () {
            $("html, body").animate({ scrollTop: $("#ChartSumDetail").offset().top - 150 }, "fast");
            $('#ChartSumDetail').html("");
            $("#ChartSumDetail").before('<div class="col-sm-12" id="ContentLoading2"><img src="../images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
            $('#ChartSumDetail').html("");
            $('#ChartContentDetail').hide();
        },
        success: function (xaxa) {
            $('#ChartSumDetail').html("");
            $('#ChartSumDetail').hide();
            $('#ChartSumDetail').html(xaxa);
            $('#ChartSumDetail').fadeIn('fast');
            $("#ContentLoading2").remove();
            MODAL_DETAIL();
        },
        error: function () {
            alert("Request cannot proceed!");
            $('#ChartSumDetail').html("");
            $("#ContentLoading2").remove();
        }
    });
}
function MODAL_DETAIL()
{
    var BoolClick = "TRUE";
    $(".DataParent").click(function () {
        if (BoolClick == "TRUE") {
            $("#TableProject tr").removeClass('PointerListSelected');
            $(this).closest('.DataParent').addClass("PointerListSelected");
            var FloatData = $(this).data('float');
            const myArray = FloatData.split("*");
            var Project = myArray[0];
            var Year = myArray[1];
            var Category = myArray[2];
            DETAIL_PROJECT_CHART(Year,Category,Project);
        }
        else {
            return false;
        }
    });
}
function DETAIL_PROJECT_CHART(a,b,c)
{
    var formdata = new FormData();
    formdata.append('ValCategory', b);
    formdata.append('ValYear', a);
    formdata.append('Quote', c);
    $.ajax({
        url: 'project/CostTracking/WOChartContentDetail.php',
        dataType: 'text',
        cache: false,
        contentType: false,
        processData: false,
        data: formdata,
        type: 'post',
        beforeSend: function () {
            $("html, body").animate({ scrollTop: $("#ChartContentDetail").offset().top - 10 }, "fast");
            $('#ChartContentDetail').html("");
            $("#ChartContentDetail").before('<div class="col-sm-12" id="ContentLoading2"><img src="../images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
            $('#ChartContentDetail').html("");
        },
        success: function (xaxa) {
            $('#ChartContentDetail').html("");
            $('#ChartContentDetail').hide();
            $('#ChartContentDetail').html(xaxa);
            $('#ChartContentDetail').fadeIn('fast');
            $("#ContentLoading2").remove();
        },
        error: function () {
            alert("Request cannot proceed!");
            $('#ChartContentDetail').html("");
            $("#ContentLoading2").remove();
        }
    });
}
</script>