$(document).ready(function () { 
    $("#BtnViewData").click(function () {
        $('#ListQuote').html("");
        $('#ContentPageManage').html("");
        var Season = $("#TextSeason").val().trim();
        var Category = $("#TextCategory").val().trim();
        var formdata = new FormData();
        formdata.append("Season", Season);
        formdata.append("Category", Category);
        $.ajax({
            url: 'project/CostTracking/ListQuoteQuantityBuildPoint.php',
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: formdata,
            type: 'post',
            beforeSend: function () {
                $("html, body").animate({ scrollTop: $("#ListQuote").offset().top - 80 }, "fast");
                $('#ListQuote').html("");
                $("#ListQuote").before('<div class="col-sm-12 d-flex justify-content-center" id="LoadingLoad"><img src="images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
            },
            success: function (xaxa) {
                $('#ListQuote').html("");
                $('#ListQuote').html(xaxa);
                $('#ListQuote').fadeIn('fast');
                $("#LoadingLoad").remove();
                LOAD_DATA();
            },
            error: function () {
                $("#ListQuote").html("");
            }
        });        
    });
});
function LOAD_DATA()
{
    $("#TableListQuote tbody .PointerListProject").on("click", function () {
        $("#TableListQuote tr").removeClass('PointerListQuoteSelected');
        $(this).closest('.PointerListProject').addClass("PointerListQuoteSelected");
        var QuoteName = $(this).text();
        var DataID = $(this).data("class");
        var formdata = new FormData();
        formdata.append("QuoteName", QuoteName);
        formdata.append("DataID", DataID);
        $.ajax({
            url: 'project/CostTracking/ContentQuantityBuildPoint.php',
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: formdata,
            type: 'post',
            beforeSend: function () {
                $("html, body").animate({ scrollTop: $("#ContentPageManage").offset().top - 80 }, "fast");
                $('#ContentPageManage').html("");
                $("#ContentPageManage").before('<div class="col-sm-12 d-flex justify-content-center" id="LoadingLoad2"><img src="images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
            },
            success: function (xaxa) {
                $('#ContentPageManage').html("");
                $('#ContentPageManage').html(xaxa);
                $('#ContentPageManage').fadeIn('fast');
                $("#LoadingLoad2").remove();
                $('#InputPoint').keypress(function (e) {
                    if ((e.which != 46 || $(this).val().indexOf('.') != -1) && (e.which < 48 || e.which > 57)) {
                        e.preventDefault();
                    }
                });
                $('#InputTargetQty').keypress(function (e) {
                    if ((e.which != 46 || $(this).val().indexOf('.') != -1) && (e.which < 48 || e.which > 57)) {
                        e.preventDefault();
                    }
                });
                $('#InputActualQty').keypress(function (e) {
                    if ((e.which != 46 || $(this).val().indexOf('.') != -1) && (e.which < 48 || e.which > 57)) {
                        e.preventDefault();
                    }
                });
                $("#TableViewData").dataTable({
                    "paging": false,
                    "bInfo": false
                });
                $(".dataTables_wrapper > .dataTables_filter input").css("margin-bottom","10px");
                ADD_DATA();
                UPDATE_MODAL();
                DELETE_DATA();
            },
            error: function () {
                $("#ContentPageManage").html("");
            }
        });
    });
}
function ADD_DATA()
{
    $("#BtnNewData").click(function(){
        var TitleResult = $("#TitleResult").html();        
        var Division = $("#InputDivision").val().trim();
        var Month = $("#InputMonth").val().trim();
        var TargetQty = $("#InputTargetQty").val().trim();
        var ActualQty = $("#InputActualQty").val().trim();
        if(TargetQty == "")
        {
            $("#InputTargetQty").focus();
            return false;
        }
        if(ActualQty == "")
        {
            $("#InputActualQty").focus();
            return false;
        }
        var formdata = new FormData();
        formdata.append("TitleResult", TitleResult);
        formdata.append("Division", Division);
        formdata.append("Month", Month);
        formdata.append("TargetQty", TargetQty);
        formdata.append("ActualQty", ActualQty);
        $.ajax({
            url: 'project/CostTracking/src/srcAddNewDataQuantityBuild.php',
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: formdata,
            type: 'post',
            beforeSend: function () {
                $("#BtnNewData").attr('disabled', true);
                $('#TemporarySpace').html("");
            },
            success: function (xaxa) {
                $('#TemporarySpace').html("");
                $('#TemporarySpace').hide();
                $('#TemporarySpace').html(xaxa);
                $('#TemporarySpace').fadeIn('fast');
                $('#TemporarySpace').html("");
                $("#BtnNewData").attr('disabled', false);
            },
            error: function () {
                alert("Request cannot proceed!");
                $("#TemporarySpace").html("");
                $("#BtnNewData").attr('disabled', false);
            }
        });
    });
}
function DELETE_DATA()
{
    $("#TableViewData tbody .DeleteQtyBuild").on("click", function () {
        if (confirm("Are you sure to delete this record?") == true) {
            var DataID = $(this).closest("td").find(".PointerList").data("datatoken");
            var formdata = new FormData();
            formdata.append("DataID", DataID);
            $.ajax({
                url: 'project/CostTracking/src/srcDeleteQuantityBuild.php',
                dataType: 'text',
                cache: false,
                contentType: false,
                processData: false,
                data: formdata,
                type: 'post',
                beforeSend: function () {
                    $('#TemporarySpace').html("");
                },
                success: function (xaxa) {
                    $('#TemporarySpace').html("");
                    $('#TemporarySpace').hide();
                    $('#TemporarySpace').html(xaxa);
                    $('#TemporarySpace').fadeIn('fast');
                    $('#TemporarySpace').html("");
                },
                error: function () {
                    alert("Request cannot proceed!");
                    $("#TemporarySpace").html("");
                }
            });
        }
    });
}
function UPDATE_MODAL()
{
    $("#TableViewData tbody .UpdateQtyBuild").on("click", function () {
        var DataID = $(this).closest("td").find(".PointerList").data("datatoken");
        $('#TemporarySpace').html("");
        $("#TemporarySpace").html('<div class="modal fade" id="ModalUpdateQtyBuild" tabindex="-1" role="dialog" aria-labelledby="Modal-View" aria-hidden="true"><div class="modal-dialog modal-md" role="document"><div class="modal-content"><div class="modal-header"><h5 class="modal-title" style="color:#000;">Update Quantity Build</h5><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div><div class="modal-body"><span id="ContentModal" style="color:#000;"></span></div><div class="modal-footer"><button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">&nbsp;Close&nbsp;</button></div></div></div></div>');
        var formdata = new FormData();
        formdata.append("DataID", DataID);
        $.ajax({
            url: 'project/CostTracking/ContentViewModalQuantityBuild.php',
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: formdata,
            type: 'post',
            beforeSend: function () {
                $("#ModalUpdateQtyBuild").modal("show");
                $('#ContentModal').html("");
                $("#ContentModal").before('<div class="col-sm-12 d-flex justify-content-center" id="LoadingLoad3"><img src="images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
            },
            success: function (xaxa) {
                $('#ContentModal').html("");
                $('#ContentModal').hide();
                $('#ContentModal').html(xaxa);
                $('#ContentModal').fadeIn('fast');
                $("#LoadingLoad3").remove();
                $('#TextPoint').keypress(function (e) {
                    if ((e.which != 46 || $(this).val().indexOf('.') != -1) && (e.which < 48 || e.which > 57)) {
                        e.preventDefault();
                    }
                });
                $('#TextTargetQty').keypress(function (e) {
                    if ((e.which != 46 || $(this).val().indexOf('.') != -1) && (e.which < 48 || e.which > 57)) {
                        e.preventDefault();
                    }
                });
                $('#TextActualQty').keypress(function (e) {
                    if ((e.which != 46 || $(this).val().indexOf('.') != -1) && (e.which < 48 || e.which > 57)) {
                        e.preventDefault();
                    }
                });
                UPDATE_QTY_BUILD();
            },
            error: function () {
                $('#ModalUpdateQtyBuild').on('hide.bs.modal', function () {
                    $("#Temporary").html("");
                });
            }
        });
    });
}
function UPDATE_QTY_BUILD()
{
    $("#BtnEditQtyBuild").click(function () {
        var InputTargetQty = $("#TextTargetQty").val();
        var InputActualQty = $("#TextActualQty").val();
        var DataID = $(this).data("datatoken");
        if (InputTargetQty.trim() == "") {
            $("#TextTargetQty").focus();
            return false;
        }
        if (InputActualQty.trim() == "") {
            $("#TextActualQty").focus();
            return false;
        }
        var formdata = new FormData();
        formdata.append("DataID", DataID);
        formdata.append("InputTargetQty", InputTargetQty);
        formdata.append("InputActualQty", InputActualQty);
        $.ajax({
            url: 'project/CostTracking/src/srcUpdateQuantityBuild.php',
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: formdata,
            type: 'post',
            beforeSend: function () {
                $("#BtnEditQtyBuild").attr('disabled', true);
                $('#TempProcess').html("");
                $("#TempProcess").before('Please wait...');
            },
            success: function (xaxa) {
                $('#TempProcess').html("");
                $('#TempProcess').hide();
                $('#TempProcess').html(xaxa);
                $('#TempProcess').fadeIn('fast');
            },
            error: function () {
                alert("Request cannot proceed!");
                $("#TempProcess").html("");
                $("#BtnEditQtyBuild").attr('disabled', false);
            }
        });
    });
}