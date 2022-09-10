$(document).ready(function () {
    $("#FilterQty").keypress(function (e) {
        if ((e.which != 46 || $(this).val().indexOf('.') != -1) && (e.which < 48 || e.which > 57)) {
            e.preventDefault();
        }
    });
    $("#BtnMappingWO").click(function () {
        $("#ContentResultModal").html("");
        $("#FilterTypeSearchData").prop("selectedIndex", 0);
        $("#FilterDivision").prop("selectedIndex", 0);
        $("#FilterInputKeywords").val("");
        $("#ModalLoadMappingWO").modal("show");
        $("#BtnSearchWOMapping").click(function () {
            var TypeSearchData = $("#FilterTypeSearchData").val().trim();
            var Division = $("#FilterDivision").val().trim();
            var InputKeywords = $("#FilterInputKeywords").val().trim();
            var formdata = new FormData();
            formdata.append("TypeSearchData", TypeSearchData);
            formdata.append("Division", Division);
            formdata.append("InputKeywords", InputKeywords);
            $.ajax({
                url: 'project/PPIC/ModalFindWOMapping.php',
                dataType: 'text',
                cache: false,
                contentType: false,
                processData: false,
                data: formdata,
                type: 'post',
                beforeSend: function () {
                    $("#BtnSearchWOMapping").attr('disabled', true);
                    $("#ContentResultModal").html("");
                    $("#ContentResultModal").append('<div class="col-sm-12 d-flex justify-content-center" id="LoadingLoad1"><img src="images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
                },
                success: function (xaxa) {
                    $("#ContentResultModal").html("");
                    $("#ContentResultModal").html(xaxa);
                    $("#ContentResultModal").fadeIn('fast');
                    $("#LoadingLoad1").remove();
                    $("#BtnSearchWOMapping").blur();
                    $("#TableWOMapping").dataTable();
                    $(".dataTables_wrapper > .dataTables_filter input").css("margin-bottom", "10px");
                    $("#TableWOMapping").on("click","tbody .BtnSelect", function () {
                        var ValWOC = $(this).closest("tr").find("td:eq(1)").html();
                        var ValWOP = $(this).closest("tr").find("td:eq(2)").html();
                        var ValQuote = $(this).closest("tr").find("td:eq(3)").html();
                        var ValDivision = $(this).closest("tr").find("td:eq(4)").html();
                        var ValExpense = $(this).closest("tr").find("td:eq(5)").html();
                        var ValProduct = $(this).closest("tr").find("td:eq(6)").html();
                        var ValOrderType = $(this).closest("tr").find("td:eq(7)").html();
                        var ValIdx = $(this).closest("tr").find("td:eq(8)").html();
                        $("#FilterWOMapID").val(ValIdx);
                        $("#FilterChild").val(ValWOC);
                        $("#FilterParent").val(ValWOP);
                        $("#FilterQuote").val(ValQuote);
                        $("#FilterExpense").val(ValExpense);
                        $("#FilterProduct").val(ValProduct);
                        $("#FilterOrderType").val(ValOrderType);
                        $("#ModalLoadMappingWO").modal("hide");
                        $("#FilterQty").prop('readonly', false);
                        $("#OptCheckFinishing").attr('disabled', false);
                        $("#BtnNewData").attr('disabled', false);
                        $("#BtnFindPartNo").attr('disabled', false);
                    });
                    $("#BtnSearchWOMapping").attr('disabled', false);
                },
                error: function () {
                    alert("Error! Load hasil pencarian tidak berhasil!")
                    $("#BtnSearchWOMapping").attr('disabled', false);
                }
            });
        });
    });
    $("#OptCheckFinishing").on("click",function(){
        if ($("#OptCheckFinishing").is(":checked"))
        {
            $("#FilterTypeFinishing").attr('disabled', false);
        }
        else
        {
            $("#FilterTypeFinishing").attr('disabled', true);
            $("#FilterTypeFinishing").prop("selectedIndex", 0);
        }
    });
    NEW_BARCODE();
    FILTER_VIEW_DATA();
    $("#BtnFindPartNo").click(function () {
        $("#ModalLoadPartNo").modal("show");
    });
    LOAD_PARTNO();
});
function NEW_BARCODE()
{
    $("#BtnNewData").click(function () {
        var WOMappingID = $("#FilterWOMapID").val().trim();
        var Company = $("#FilterCompany").val().trim();
        var WOC = $("#FilterChild").val().trim();
        var WOP = $("#FilterParent").val().trim();
        var Quote = $("#FilterQuote").val().trim();
        var Product = $("#FilterProduct").val().trim();
        var OrderType = $("#FilterOrderType").val().trim();
        var Expense = $("#FilterExpense").val().trim();
        var PartNo = $("#FilterPartNo").val().trim();
        var InputQty = $("#FilterQty").val().trim();
        var PartStatus = $("#FilterPartStatus").val().trim();
        if ($("#OptCheckFinishing").is(":checked")) {
            var CheckFinishing = $("#FilterTypeFinishing").val().trim();
            if (CheckFinishing == "#") {
                $("#FilterTypeFinishing").focus();
                return false;
            }
        }
        else {
            var CheckFinishing = "N/A";
        }
        if (PartNo == "") {
            $("#FilterPartNo").focus();
            return false;
        }
        if (InputQty == "") {
            $("#FilterQty").focus();
            return false;
        }
        var formdata = new FormData();
        formdata.append("WOMappingID", WOMappingID);
        formdata.append("Company", Company);
        formdata.append("WOC", WOC);
        formdata.append("WOP", WOP);
        formdata.append("Quote", Quote);
        formdata.append("Product", Product);
        formdata.append("OrderType", OrderType);
        formdata.append("Expense", Expense);
        formdata.append("PartNo", PartNo);
        formdata.append("InputQty", InputQty);
        formdata.append("CheckFinishing", CheckFinishing);
        formdata.append("PartStatus", PartStatus);

        $.ajax({
            url: 'project/PPIC/src/srcAddNewBCPartJob.php',
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: formdata,
            type: 'post',
            beforeSend: function () {
                $("#BtnNewData").attr('disabled', true);
                $("#InfoError").html("");
            },
            success: function (xaxa) {
                $("#InfoError").html("");
                $("#InfoError").hide();
                $("#InfoError").html(xaxa);
                $("#InfoError").fadeIn('fast');
                $("#BtnNewData").attr('disabled', false);
                if ($("#InfoError").text() == "TRUE")
                {
                    $("#InfoError").html('<p class="text-success fw-bold">Data telah tersimpan!</p>');
                    RESET_FORM();
                }
                else
                {
                    $("#InfoError").html('<p class="text-danger fw-bold">Data gagal tersimpan!</p>');
                }
                
            },
            error: function () {
                $("#InfoError").html("Request cannot proceed!");
                $("#BtnNewData").attr('disabled', false);
            }
        });
    });
}
function RESET_FORM()
{
    $("#FilterWOMapID").val("");
    $("#FilterChild").val("");
    $("#FilterParent").val("");
    $("#FilterQuote").val("");
    $("#FilterProduct").val("");
    $("#FilterOrderType").val("");
    $("#FilterExpense").val("");
    $("#FilterPartNo").val("");
    $("#FilterQty").val("");
    $("#FilterQty").prop('readonly', true);
    $("#OptCheckFinishing").prop('checked', false);
    $("#OptCheckFinishing").attr('disabled', true);
    $("#FilterTypeFinishing").prop("selectedIndex", 0);
    $("#FilterTypeFinishing").attr('disabled', true);
    $("#BtnFindPartNo").attr('disabled', true);
    $("#BtnNewData").attr('disabled', true);
    $("#BtnNewData").blur();
}
function FILTER_VIEW_DATA()
{
    $("#BtnSearch").click(function(){
        var PPIC = $("#FilterPPIC").val().trim();
        var TypeData = $("#FilterTypeData").val().trim();
        var Keywords = $("#FilterKeywords").val().trim();
        var Year = $("#FilterYear").val().trim();
        var formdata = new FormData();
        formdata.append("PPIC", PPIC);
        formdata.append("TypeData", TypeData);
        formdata.append("Keywords", Keywords);
        formdata.append("Year", Year);
        $.ajax({
            url: 'project/PPIC/ContentFormNewBCPartJob.php',
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: formdata,
            type: 'post',
            beforeSend: function () {
                $("#BtnSearch").attr('disabled', true);
                $('#ContentResult2').html("");
                $("#ContentResult2").append('<div class="col-sm-12 d-flex justify-content-center" id="LoadingLoad2"><img src="images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
            },
            success: function (xaxa) {
                $('#ContentResult2').html("");
                $('#ContentResult2').hide();
                $('#ContentResult2').html(xaxa);
                $('#ContentResult2').fadeIn('fast');
                $("#LoadingLoad2").remove();
                $("#BtnSearch").attr('disabled', false);
                $("#TableBarcodeRegistered").dataTable(
                    {
                        scrollX: true,
                        scrollCollapse: true
                    }
                );
                $(".dataTables_wrapper > .dataTables_filter input").css("margin-bottom", "10px");
                $("#TableBarcodeRegistered > tbody").css("font-size", "12px");
            },
            error: function () {
                alert("Request cannot proceed!");
                $("#ContentResult2").html("");
                $("#BtnSearch").attr('disabled', false);
            }
        });

    });
}
function LOAD_PARTNO()
{
    $("#BtnSearchPartNo").click(function () {
        var Category = $("#FilterTypeCategoryFind").val().trim();
        var Keywords = $("#FilterInputKeywordPartno").val().trim();
        if (Keywords == "") {
            $("#FilterInputKeywordPartno").focus();
            return false;
        }
        var formdata = new FormData();
        formdata.append("Category", Category);
        formdata.append("Keywords", Keywords);
        $.ajax({
            url: 'project/PPIC/ModalFindPartNo.php',
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: formdata,
            type: 'post',
            beforeSend: function () {
                $("#BtnSearchPartNo").attr('disabled', true);
                $("#ContentModalPartNo").html("");
                $("#ContentModalPartNo").append('<div class="col-sm-12 d-flex justify-content-center" id="LoadingLoadPartNo"><img src="images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
            },
            success: function (xaxa) {
                $("#ContentModalPartNo").html("");
                $("#ContentModalPartNo").hide();
                $("#ContentModalPartNo").html(xaxa);
                $("#ContentModalPartNo").fadeIn('fast');
                $("#LoadingLoadPartNo").remove();                
                $("#TablePartNo").dataTable(
                    {
                        scrollX: true,
                        scrollCollapse: true
                    }
                );
                $(".dataTables_wrapper > .dataTables_filter input").css("margin-bottom", "10px");
                $("#TablePartNo > tbody").css("font-size", "12px");
                $("#BtnSearchPartNo").attr('disabled', false);
                $("#TablePartNo").on("click","tbody .BtnSelect", function () {
                    var ValPartNo = $(this).closest("tr").find("td:eq(1)").html();
                    $("#FilterPartNo").val(ValPartNo);
                    $("#FilterTypeCategoryFind").prop("selectedIndex", 0);
                    $("#FilterInputKeywordPartno").val("");
                    $("#ContentModalPartNo").html("");
                    $("#ModalLoadPartNo").modal("hide");
                });
            },
            error: function () {
                alert("Request cannot proceed!");
                $("#ContentModalPartNo").html("");
                $("#BtnSearchPartNo").attr('disabled', false);
            }
        });
    });
    $('#ModalLoadPartNo').on('hide.bs.modal', function () {
        $("#FilterTypeCategoryFind").prop("selectedIndex", 0);
        $("#FilterInputKeywordPartno").val("");
        $("#ContentModalPartNo").html("");
    });
}