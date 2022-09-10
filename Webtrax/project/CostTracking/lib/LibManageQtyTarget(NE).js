var BolFilter = "False";
$(document).ready(function () {
    FILTER_VIEW_DATA();
    GET_TOKEN();
    REMOVE_DATA();
    $("#InputSeasonF").change(function () {
        FILTER_NEW_TARGET_COST_QUOTE();
    });
    $("#InputQuoteCategoryF").change(function () {
        FILTER_NEW_TARGET_COST_QUOTE();
    });
});
function FILTER_VIEW_DATA() {
    if (BolFilter == "False") {
        $("#BtnViewData").click(function () {
            var ValSeason = $("#InputClosedTime").val();
            var ValQuoteCategory = $("#InputQuoteCategory").val();
            var formdata = new FormData();
            formdata.append("ValSeason", ValSeason);
            formdata.append("ValQuoteCategory", ValQuoteCategory);
            $.ajax({
                url: 'project/costtracking/ContentViewQtyTarget.php',
                dataType: 'text',
                cache: false,
                contentType: false,
                processData: false,
                data: formdata,
                type: 'post',
                beforeSend: function () {
                    $('#ContentView').html("");
                    $("#ContentView").before('<div class="col-sm-12" id="LoadingView"><img src="../images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
                    $('#ContentView').html("");
                    $("#BtnViewData").attr('disabled', true);
                    BolFilter = "True";
                },
                success: function (xaxa) {
                    $('#ContentView').html("");
                    $('#ContentView').hide();
                    $('#ContentView').html(xaxa);
                    $('#ContentView').fadeIn('fast');
                    $("#LoadingView").remove();
                    $("#BtnViewData").attr('disabled', false);
                    GET_TOKEN();
                    REMOVE_DATA();
                    BolFilter = "False";
                },
                error: function () {
                    alert("Request cannot proceed!");
                    $('#ContentView').html("");
                    $("#LoadingView").remove();
                    $("#BtnViewData").attr('disabled', false);
                    GET_TOKEN();
                    REMOVE_DATA();
                    BolFilter = "False";
                }
            });
        });
    }
    else {
        return false;
    }
}
function GET_TOKEN() 
{
    $("#TableData tbody .EditTarget").on("click", function () {
        var DataID = $(this).closest("td").find(".PointerList").data("datatoken");
        $("#Temporary").html("");
        $("#Temporary").html('<div class="modal fade" id="ModalUpdateTarget" tabindex="-1" role="dialog" aria-labelledby="Modal-View" aria-hidden="true"><div class="modal-dialog modal-md" role="document"><div class="modal-content"><div class="modal-header"><div class="row"><div class="col-xs-6 text-left"><h5 class="modal-title"><strong>Update Qty Target</strong></h5></div><div class="col-xs-6 text-right"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button></div></div></div><div class="modal-body"><span id="ContentModal"></span></div><div class="modal-footer"><button type="button" class="btn btn-sm btn-default" data-dismiss="modal">&nbsp;Close&nbsp;</button></div></div></div></div>');
        var formdata = new FormData();
        formdata.append("ValDataID", DataID);
        $.ajax({
            url: 'project/costtracking/ContentViewModalQtyTarget.php',
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: formdata,
            type: 'post',
            beforeSend: function () {
                $("#ModalUpdateTarget").modal("show");
                $('#ContentModal').html("");
                $("#ContentModal").before('<div class="col-sm-12" id="LoadingLoad"><img src="../images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
            },
            success: function (xaxa) {
                $('#ContentModal').html("");
                $('#ContentModal').hide();
                $('#ContentModal').html(xaxa);
                $('#ContentModal').fadeIn('fast');
                $("#LoadingLoad").remove();
                $('#TextQtyTarget').keypress(function (e) {
                    if ((e.which != 46 || $(this).val().indexOf('.') != -1) && (e.which < 48 || e.which > 57)) {
                        e.preventDefault();
                    }
                });
                UPDATE_TARGET_QTY();
            },
            error: function () {
                $('#ModalUpdateTarget').on('hide.bs.modal', function () {
                    $("#Temporary").html("");
                })
            }
        });
    });
}
function UPDATE_TARGET_QTY() {
    $("#BtnEditQtyTarget").click(function () {
        var InputNewQtyTarget = $("#TextQtyTarget").val();
        var DataID = $(this).data("datatoken");
        if (InputNewQtyTarget.trim() == "") {
            $("#TextQtyTarget").focus();
            return false;
        }
        else {
            var formdata = new FormData();
            formdata.append("ValNewQtyTarget", InputNewQtyTarget);
            formdata.append("ValDataID", DataID);
            $.ajax({
                url: 'project/costtracking/src/srcUpdateQtyTarget.php',
                dataType: 'text',
                cache: false,
                contentType: false,
                processData: false,
                data: formdata,
                type: 'post',
                beforeSend: function () {
                    $("#BtnEditQtyTarget").attr('disabled', true);
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
                    $("#BtnEditQtyTarget").attr('disabled', false);
                }
            });
        }
    });
}
function FILTER_NEW_TARGET_COST_QUOTE() {
    var ValSeason = $("#InputSeasonF option:selected").val();
    var ValQuoteCategory = $("#InputQuoteCategoryF option:selected").val();
    var formdata = new FormData();
    formdata.append("ValSeason", ValSeason);
    formdata.append("ValQuoteCategory", ValQuoteCategory);
    $.ajax({
        url: 'project/costtracking/ContentListInputTargetCostQuote.php',
        dataType: 'text',
        cache: false,
        contentType: false,
        processData: false,
        data: formdata,
        type: 'post',
        beforeSend: function () {
            $('#ResFormQuote').html("");
            $('#ResFormExpense').html("");
            $('#InfoNewTarget').html("");
            $("#ResFormQuote").before('<div class="col-sm-12" id="LoadListQuote"><img src="../images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
        },
        success: function (xaxa) {
            $('#ResFormQuote').html("");
            $('#ResFormQuote').hide();
            $('#ResFormQuote').html(xaxa);
            $('#ResFormQuote').fadeIn('fast');
            $("#LoadListQuote").remove();
            FILTER_NEW_QTY_TARGET_EXPENSE();
            $('#InputQtyTargetF').keypress(function (e) {
                if ((e.which != 46 || $(this).val().indexOf('.') != -1) && (e.which < 48 || e.which > 57)) {
                    e.preventDefault();
                }
            });
        },
        error: function () {
            $("#ResFormQuote").html("");
            $('#ResFormExpense').html("");
        }
    });
}
function FILTER_NEW_QTY_TARGET_EXPENSE() {
    var ValSeason = $("#InputSeasonF option:selected").val();
    var ValQuoteCategory = $("#InputQuoteCategoryF option:selected").val();
    var ValQuote = $("#InputQuoteF option:selected").val();
    var formdata = new FormData();
    formdata.append("ValSeason", ValSeason);
    formdata.append("ValQuoteCategory", ValQuoteCategory);
    formdata.append("ValQuote", ValQuote);
    $.ajax({
        url: 'project/costtracking/ContentListInputQtyTargetExpense.php',
        dataType: 'text',
        cache: false,
        contentType: false,
        processData: false,
        data: formdata,
        type: 'post',
        beforeSend: function () {
            $('#ResFormExpense').html("");
            $("#ResFormExpense").before('<div class="col-sm-12" id="LoadListExpense"><img src="../images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
            $('#InfoNewTarget').html("");
        },
        success: function (xaxa) {
            $('#ResFormExpense').html("");
            $('#ResFormExpense').hide();
            $('#ResFormExpense').html(xaxa);
            $('#ResFormExpense').fadeIn('fast');
            $("#LoadListExpense").remove();
            $("#InputQuoteF").change(function () {
                FILTER_NEW_TARGET_COST_QOUTE_2();
            });
            $('#InputQtyTargetF').keypress(function (e) {
                if ((e.which != 46 || $(this).val().indexOf('.') != -1) && (e.which < 48 || e.which > 57)) {
                    e.preventDefault();
                }
            });
            ADD_NEW_TARGET();
        },
        error: function () {
            $('#ResFormExpense').html("");
        }
    });
}
function FILTER_NEW_TARGET_COST_QOUTE_2() {
    var ValSeason = $("#InputSeasonF option:selected").val();
    var ValQuoteCategory = $("#InputQuoteCategoryF option:selected").val();
    var ValQuote = $("#InputQuoteF option:selected").val();
    var formdata = new FormData();
    formdata.append("ValSeason", ValSeason);
    formdata.append("ValQuoteCategory", ValQuoteCategory);
    formdata.append("ValQuote", ValQuote);
    $.ajax({
        url: 'project/costtracking/ContentListInputQtyTargetExpense.php',
        dataType: 'text',
        cache: false,
        contentType: false,
        processData: false,
        data: formdata,
        type: 'post',
        beforeSend: function () {
            $('#ResFormExpense').html("");
            $('#InfoNewTarget').html("");
            $("#ResFormExpense").before('<div class="col-sm-12" id="LoadListExpense"><img src="../images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
            $('#InfoNewTarget').html("");
        },
        success: function (xaxa) {
            $('#ResFormExpense').html("");
            $('#ResFormExpense').hide();
            $('#ResFormExpense').html(xaxa);
            $('#ResFormExpense').fadeIn('fast');
            $("#LoadListExpense").remove();
            $('#InputQtyTargetF').keypress(function (e) {
                if ((e.which != 46 || $(this).val().indexOf('.') != -1) && (e.which < 48 || e.which > 57)) {
                    e.preventDefault();
                }
            });
            ADD_NEW_TARGET();
        },
        error: function () {
            $('#ResFormExpense').html("");
        }
    });
}
function ADD_NEW_TARGET() {
    $("#BtnNewTarget").click(function () {
        var ValSeason = $("#InputSeasonTF option:selected").val();
        var ValQuoteCategory = $("#InputQuoteCategoryF option:selected").val();
        var ValQuote = $("#InputQuoteF option:selected").val();
        var ValExpense = $("#InputExpenseF option:selected").val();
        var ValQtyTarget = $("#InputQtyTargetF").val();
        var ValLocation = $("#InputLocationF option:selected").val();
        var formdata = new FormData();
        formdata.append("ValSeason", ValSeason);
        formdata.append("ValQuoteCategory", ValQuoteCategory);
        formdata.append("ValQuote", ValQuote);
        formdata.append("ValExpense", ValExpense);
        formdata.append("ValQtyTarget", ValQtyTarget);
        formdata.append("ValLocation", ValLocation);
        $.ajax({
            url: 'project/costtracking/src/srcAddNewQtyTarget.php',
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: formdata,
            type: 'post',
            beforeSend: function () {
                $('#InfoNewTarget').html("");
                $("#InfoNewTarget").before('<div class="col-sm-12" id="LoadAddNewTarget"><img src="../images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
                $("#BtnNewTarget").attr('disabled', true);
            },
            success: function (xaxa) {
                $('#InfoNewTarget').html("");
                $('#InfoNewTarget').hide();
                $('#InfoNewTarget').html(xaxa);
                $('#InfoNewTarget').fadeIn('fast');
                $("#LoadAddNewTarget").remove();
                $("#BtnNewTarget").attr('disabled', false);
                $("#InputQtyTargetF").val("");
            },
            error: function () {
                alert("Request cannot proceed!");
                $('#InfoNewTarget').html("");
                $("#BtnNewTarget").attr('disabled', false);
            }
        });
    });
}
function REMOVE_DATA() {
    $("#TableData tbody .DeleteTarget").on("click", function () {
        if (confirm("Are you sure to delete this record?") == true) {
            $(this).closest("tr").remove();
            var DataID = $(this).closest("td").find(".PointerList").data("datatoken");
            var formdata = new FormData();
            formdata.append("DataID", DataID);
            $.ajax({
                url: 'project/costtracking/src/srcDeleteQtyTarget.php',
                dataType: 'text',
                cache: false,
                contentType: false,
                processData: false,
                data: formdata,
                type: 'post',
                beforeSend: function () {
                    $('#Temporary').html("");
                },
                success: function (xaxa) {
                    $('#Temporary').html("");
                    $('#Temporary').hide();
                    $('#Temporary').html(xaxa);
                    $('#Temporary').fadeIn('fast');
                },
                error: function () {
                    alert("Request cannot proceed!");
                    $("#Temporary").html("");
                }
            });
        }
    });
}

