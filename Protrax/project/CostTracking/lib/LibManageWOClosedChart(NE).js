$(document).ready(function () { 
    $("#TableListProject tbody .PointerListProject").on("click", function () {
        $("#TableListProject tr").removeClass('PointerListQuoteSelected');
        $(this).closest('.PointerListProject').addClass("PointerListQuoteSelected");
        var DataID = $(this).data("split");
        var formdata = new FormData();
        formdata.append("ValDataID", DataID);
        $.ajax({
            url: 'project/CostTracking/ManageWOClosedChartContent.php',
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: formdata,
            type: 'post',
            beforeSend: function () {
                $("html, body").animate({ scrollTop: $("#ContentPageManage").offset().top - 80 }, "fast");
                $('#ContentPageManage').html("");
                $("#ContentPageManage").before('<div class="col-sm-12 d-flex justify-content-center" id="LoadingLoad"><img src="images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
            },
            success: function (xaxa) {
                $('#ContentPageManage').html("");
                $('#ContentPageManage').hide();
                $('#ContentPageManage').html(xaxa);
                $('#ContentPageManage').fadeIn('fast');
                $("#LoadingLoad").remove();
                LOAD_DATA();
            },
            error: function () {
                $("#ContentPage").html("");
            }
        });        
    });
});
function LOAD_DATA()
{        
    $("#TableViewData tbody .DataRow").on("click", function () {
        var DataToken = $(this).data("token");        
        $("#TemporarySpace").html("");   
        $("#TemporarySpace").html('<div class="modal fade" id="ModalUpdateDataChart" tabindex="-1" role="dialog" aria-labelledby="Modal-View" aria-hidden="true"><div class="modal-dialog modal-md" role="document"><div class="modal-content"><div class="modal-header"><h5 class="modal-title" style="color:#000;">Update Data Chart</h5><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div><div class="modal-body"><span id="ContentModal" style="color:#000;"></span></div><div class="modal-footer"><button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">&nbsp;Close&nbsp;</button></div></div></div></div>');
        var formdata = new FormData();
        formdata.append("ValDataToken", DataToken);
        $.ajax({
            url: 'project/CostTracking/ModalManageWOCloseChart.php',
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: formdata,
            type: 'post',
            beforeSend: function () {
                $("#ModalUpdateDataChart").modal("show");
                $('#ContentModal').html("");
                $("#ContentModal").before('<div class="col-sm-12 d-flex justify-content-center" id="LoadingLoad"><img src="images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
            },
            success: function (xaxa) {
                $('#ContentModal').html("");
                $('#ContentModal').hide();
                $('#ContentModal').html(xaxa);
                $('#ContentModal').fadeIn('fast');
                $("#LoadingLoad").remove();
                $('#TextTotalTargetCost').keypress(function (e) {
                    if ((e.which != 46 || $(this).val().indexOf('.') != -1) && (e.which < 48 || e.which > 57)) {
                        e.preventDefault();
                    }
                });
                $('#TextTotalActualCost').keypress(function (e) {
                    if ((e.which != 46 || $(this).val().indexOf('.') != -1) && (e.which < 48 || e.which > 57)) {
                        e.preventDefault();
                    }
                });
                $('#TextTotalQtyBuilt').keypress(function (e) {
                    if ((e.which != 46 || $(this).val().indexOf('.') != -1) && (e.which < 48 || e.which > 57)) {
                        e.preventDefault();
                    }
                });
                $('#TextTotalQtyTarget').keypress(function (e) {
                    if ((e.which != 46 || $(this).val().indexOf('.') != -1) && (e.which < 48 || e.which > 57)) {
                        e.preventDefault();
                    }
                });
                $('#TextTotalOTS').keypress(function (e) {
                    if ((e.which != 46 || $(this).val().indexOf('.') != -1) && (e.which < 48 || e.which > 57)) {
                        e.preventDefault();
                    }
                });
                UPDATE_DATA();
            },
            error: function () {
                $('#ModalUpdateDataChart').on('hide.bs.modal', function () {
                    $("#Temporary").html("");
                });
            }
        }); 
    });    
    $('#InputTargetCost').keypress(function (e) {
        if ((e.which != 46 || $(this).val().indexOf('.') != -1) && (e.which < 48 || e.which > 57)) {
            e.preventDefault();
        }
    });
    $('#InputActualCost').keypress(function (e) {
        if ((e.which != 46 || $(this).val().indexOf('.') != -1) && (e.which < 48 || e.which > 57)) {
            e.preventDefault();
        }
    });
    $('#InputQtyBuilt').keypress(function (e) {
        if ((e.which != 46 || $(this).val().indexOf('.') != -1) && (e.which < 48 || e.which > 57)) {
            e.preventDefault();
        }
    });
    $('#InputQtyTarget').keypress(function (e) {
        if ((e.which != 46 || $(this).val().indexOf('.') != -1) && (e.which < 48 || e.which > 57)) {
            e.preventDefault();
        }
    });
    $('#InputOTS').keypress(function (e) {
        if ((e.which != 46 || $(this).val().indexOf('.') != -1) && (e.which < 48 || e.which > 57)) {
            e.preventDefault();
        }
    });
    $("#BtnNewData").click(function(){
        var InputQuoteID = $("#TitleProject").text().trim();
        var InputQuote = $("#InputQuote").val().trim();
        var InputHalf = $("#InputHalf").val().trim();
        var InputTotalTargetCost = $("#InputTargetCost").val().trim();
        var InputTotalActualCost = $("#InputActualCost").val().trim();
        var InputTotalQtyBuilt = $("#InputQtyBuilt").val().trim();
        var InputTotalQtyTarget = $("#InputQtyTarget").val().trim();
        var InputTotalOTS = $("#InputOTS").val().trim();
        
        if(InputTotalTargetCost == "")
        {
            $("#InputTargetCost").focus();
            return false;
        }
        if(InputTotalActualCost == "")
        {
            $("#InputActualCost").focus();
            return false;
        }
        if(InputTotalQtyBuilt == "")
        {
            $("#InputQtyBuilt").focus();
            return false;
        }
        if(InputTotalQtyTarget == "")
        {
            $("#InputQtyTarget").focus();
            return false;
        }
        if(InputTotalOTS == "")
        {
            $("#InputOTS").focus();
            return false;
        }
        
        var formdata = new FormData();
        formdata.append("InputQuoteID", InputQuoteID);
        formdata.append("InputQuote", InputQuote);
        formdata.append("InputHalf", InputHalf);
        formdata.append("InputTotalTargetCost", InputTotalTargetCost);
        formdata.append("InputTotalActualCost", InputTotalActualCost);
        formdata.append("InputTotalQtyBuilt", InputTotalQtyBuilt);
        formdata.append("InputTotalQtyTarget", InputTotalQtyTarget);
        formdata.append("InputTotalOTS", InputTotalOTS);
        $.ajax({
            url: 'project/CostTracking/src/srcAddNewDataWOClosedChart.php',
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
    $("#TableViewData tbody .DataDelete").on("click", function () {
        if(!confirm("Are you sure you want to delete this?"))
        {				
            return false;
        }
        var x = $(this).closest("tr");
        var Res = "";
        var DataToken = $(this).data("token");
        var formdata = new FormData();
        formdata.append("ValDataToken", DataToken);
        $.ajax({
            url: 'project/CostTracking/src/srcDeleteDataWOClosedChart.php',
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
                Res = $('#TemporarySpace').html();
                if(Res == "TRUE")
                {
                    x.remove();
                }
                if(Res == "FALSE")
                {
                    alert("Delete error!");
                }
                $('#TemporarySpace').html("");
            },
            error: function () {
                $("#TemporarySpace").html("");
            }
        });
    });
}
function UPDATE_DATA()
{
    $("#BtnEditQtyTarget").click(function(){
        if(!confirm("Are you sure you want to update this data?"))
        {				
            return false;
        }
        var DataToken = $(this).data("token");
        var DataTotalTargetCost = $("#TextTotalTargetCost").val().trim();
        var DataTotalActualCost = $("#TextTotalActualCost").val().trim();
        var DataTotalQtyBuilt = $("#TextTotalQtyBuilt").val().trim();
        var DataTotalQtyTarget = $("#TextTotalQtyTarget").val().trim();
        var DataTotalOTS = $("#TextTotalOTS").val().trim();
        
        if(DataTotalTargetCost == "")
        {
            $("#TextTotalTargetCost").focus();
            return false;
        }
        if(DataTotalActualCost == "")
        {
            $("#TextTotalActualCost").focus();
            return false;
        }
        if(DataTotalQtyBuilt == "")
        {
            $("#TextTotalQtyBuilt").focus();
            return false;
        }
        if(DataTotalQtyTarget == "")
        {
            $("#TextTotalQtyTarget").focus();
            return false;
        }
        if(DataTotalOTS == "")
        {
            $("#TextTotalOTS").focus();
            return false;
        }        
        var formdata = new FormData();
        formdata.append("ValDataToken", DataToken);
        formdata.append("ValDataTotalTargetCost", DataTotalTargetCost);
        formdata.append("ValDataTotalActualCost", DataTotalActualCost);
        formdata.append("ValDataTotalQtyBuilt", DataTotalQtyBuilt);
        formdata.append("ValDataTotalQtyTarget", DataTotalQtyTarget);
        formdata.append("ValDataTotalOTS", DataTotalOTS);
        $.ajax({
            url: 'project/CostTracking/src/srcUpdateDataWOClosedChart.php',
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: formdata,
            type: 'post',
            beforeSend: function () {
                $("#BtnEditQtyTarget").attr('disabled', true);
                $('#TempProcess').html("");
                $("#TempProcess").before('<div class="col-sm-12 d-flex justify-content-center" id="LoadingLoadU"><img src="images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
            },
            success: function (xaxa) {
                $('#TempProcess').html("");
                $('#TempProcess').hide();
                $('#TempProcess').html(xaxa);
                $('#TempProcess').fadeIn('fast');
                $("#LoadingLoadU").remove();
                $('#TempProcess').html("");
                $("#BtnEditQtyTarget").attr('disabled', false);
            },
            error: function () {
                $("#TempProcess").html("");
                $("#BtnEditQtyTarget").attr('disabled', false);
            }
        });         
    });
}