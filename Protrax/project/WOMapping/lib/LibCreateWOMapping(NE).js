$(document).ready(function () {
    $("#ModalNewQuote").on('show.bs.modal', function (event) {
        $.ajax({
            url: 'project/WOMapping/FormBuatQuote.php',
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            beforeSend: function () {
                $('#NewQuoteForm').html("");
            },
            success: function (xaxa) {
                $('#NewQuoteForm').hide();
                $('#NewQuoteForm').html(xaxa);
                $('#NewQuoteForm').fadeIn('fast');
                SaveQuote();
            },
            error: function () {
                alert('Request cannot proceed!');
            }
        });
    });
    
    $("#BtnSelect").click(function(){
        var ValQuote = $("#FilQuote").val();
        var formdata = new FormData();
        formdata.append('ValQuote', ValQuote);
        $.ajax({
            url: 'project/WOMapping/CreateWOMappingContent.php',
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: formdata,
            type: 'post',
            beforeSend: function () {
                $("#BtnSelect").attr('disabled', true);
                $('#FormCreateWO').html("");
                $("#FormCreateWO").before('<div class="col-sm-12 d-flex justify-content-center" id="ContentLoading"><img src="../images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
                $('#FormCreateWO').html("");
            },
            success: function (xaxa) {
                $('#FormCreateWO').html("");
                $('#FormCreateWO').hide();
                $('#FormCreateWO').html(xaxa);
                $('#FormCreateWO').fadeIn('fast');
                $("#ContentLoading").remove();
                $("#BtnSelect").blur();
                $("#BtnSelect").attr('disabled', false);
                $('#FILWOP').change(function() {
                    var val = $("#FILWOP option:selected").val();
                    const myArray = val.split(":");
                    $("#QtyWOP").val(myArray[1]);
                    $("#FilProduct").val(myArray[2]);
                    $("#FilWOC").val(myArray[0]);
                });
                NEW_WOP_MODAL(ValQuote);
                INPUT_MANHOUR();
                BTN_SUBMIT(ValQuote);
            },
            error: function () {
                alert("Request cannot proceed!");
                $("#BtnSelect").blur();
                $('#FormCreateWO').html("");
                $("#ContentLoading").remove();
                $("#BtnSelect").attr('disabled', false);
            }
        });
    });
    $("#BtnSearch").click(function(){
        var FilType = $("#FilType").val();
        var FilterKeywords = $("#FilterKeywords").val();
        var formdata = new FormData();
        formdata.append('FilType', FilType);
        formdata.append('FilterKeywords', FilterKeywords);
        $.ajax({
            url: 'project/WOMapping/WOMappingSearchContent.php',
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: formdata,
            type: 'post',
            beforeSend: function () {
                $("#BtnSearch").attr('disabled', true);
                $('#DataSearch').html("");
                $("#DataSearch").before('<div class="col-sm-12 d-flex justify-content-center" id="ContentLoading"><img src="../images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
                $('#DataSearch').html("");
            },
            success: function (xaxa) {
                $('#DataSearch').html("");
                $('#DataSearch').hide();
                $('#DataSearch').html(xaxa);
                $('#DataSearch').fadeIn('fast');
                $("#ContentLoading").remove();
                $("#BtnSearch").blur();
                $("#BtnSearch").attr('disabled', false);
                $('#TableSearchWO').DataTable( {
                    "iDisplayLength": 5,
                    "aLengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
                    scrollY:        "430px",
                    scrollX:        true,
                    scrollCollapse: true,
                    autoWidth: true
                });
            },
            error: function () {
                alert("Request cannot proceed!");
                $("#BtnSearch").blur();
                $('#DataSearch').html("");
                $("#ContentLoading").remove();
                $("#BtnSearch").attr('disabled', false);
            }
        });
    });
});
function SaveQuote()
{
    $("#BtnSaveQuote").click(function(){
        var QuoteName = $("#QuoteName").val();
        var QuoteCat = $("#QuoteCat").val();
        var NamaPM = $("#NamaPM").val();
        var formdata = new FormData();
        formdata.append("QuoteName", QuoteName);
        formdata.append("QuoteCat", QuoteCat);
        formdata.append("NamaPM", NamaPM);
        $.ajax({
            url: 'project/WOMapping/src/srcBuatQuoteBaru.php',
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: formdata,
            type: 'post',
            beforeSend: function () {
                $('#SimpanQuote').html("");
            },
            success: function (xaxa) {
                $('#SimpanQuote').hide();
                $('#SimpanQuote').html(xaxa);
                $('#SimpanQuote').fadeIn('fast');
            },
            error: function () {
                alert('Request cannot proceed!');
            }
        });
    });
}
function NEW_WOP_MODAL(Data)
{
    $("#ModalNewWOP").on('show.bs.modal', function (event) {
        var NamaPM = $("#FilPM").val();
        var formdata = new FormData();
        formdata.append('Quote', Data);
        formdata.append('NamaPM', NamaPM);
        $.ajax({
            url: 'project/WOMapping/FormBuatWOP.php',
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: formdata,
            type: 'post',
            beforeSend: function () {
                $('#NewWOPForm').html("");
            },
            success: function (xaxa) {
                $('#NewWOPForm').hide();
                $('#NewWOPForm').html(xaxa);
                $('#NewWOPForm').fadeIn('fast');
                SaveWOP();
            },
            error: function () {
                alert('Request cannot proceed!');
            }
        });
    });
}
function SaveWOP()
{
    $("#BtnSaveWOP").click(function(){
        var QuoteName = $("#QuoteName").val();
        var Category = $("#Category").val();
        var NamaPM = $("#NamaPM").val();
        var WOPBaru = $("#WOPBaru").val();
        var ProductName = $("#ProductName").val();
        var QtyWOPBaru = $("#QtyWOPBaru").val();
        var formdata = new FormData();
        formdata.append("QuoteName", QuoteName);
        formdata.append("Category", Category);
        formdata.append("NamaPM", NamaPM);
        formdata.append("WOPBaru", WOPBaru);
        formdata.append("ProductName", ProductName);
        formdata.append("QtyWOPBaru", QtyWOPBaru);
        $.ajax({
            url: 'project/WOMapping/src/srcBuatWOPBaru.php',
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: formdata,
            type: 'post',
            beforeSend: function () {
                $('#SimpanWOP').html("");
            },
            success: function (xaxa) {
                $('#SimpanWOP').hide();
                $('#SimpanWOP').html(xaxa);
                $('#SimpanWOP').fadeIn('fast');
            },
            error: function () {
                alert('Request cannot proceed!');
            }
        });
    });
}
function INPUT_MANHOUR()
{
    $("#ManHourModal").on('show.bs.modal', function (event) {
        var act = $(event.relatedTarget);
        var DataCode = act.data('ecode');
        // var DataTemp = act.data('dcode');
        var formdata = new FormData();
        formdata.append("ValCode", DataCode);
        $.ajax({
            url: 'project/WOMapping/src/srcInputManHour.php',
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: formdata,
            type: 'post',
            beforeSend: function () {
                $('#InputManHour').html("");
            },
            success: function (xaxa) {
                $('#InputManHour').hide();
                $('#InputManHour').html(xaxa);
                $('#InputManHour').fadeIn('fast');
                $('#txtFilterTanggal1').datetimepicker({
                    lang:'en',
                    timepicker:false,
                    format:'m/d/Y',
                    formatDate:'m/d/Y',
                    theme:'dark'
                });
                
                $('#SumDay').change(function() {
                    var a=$("#SumDay").val();
                    var b=$("#SumMan").val();
                    var d=$("#KonsManHour").val();
                    var c=a*b*8;
                    var e=c*d;
                    $("#LimMax").val(c);
                    $("#EstManHour").val(e);
                    // PASSING_MANHOUR(e);
                });
                $('#SumMan').change(function() {
                    var a=$("#SumDay").val();
                    var b=$("#SumMan").val();
                    var d=$("#KonsManHour").val();
                    var c=a*b*8;
                    var e=c*d;
                    $("#LimMax").val(c);
                    $("#EstManHour").val(e);
                    PASSING_MANHOUR(e,c);
                });
            },
            error: function () {
                alert('Request cannot proceed!');
            }
        });
    });
}
function PASSING_MANHOUR(ValE,ValC)
{
    $("#BtnInputManHour").click(function(){
        var Expense = $("#Expense").val();
        var MachHour = $("#MachHour").val();
        var MatCost = $("#MatCost").val();
        var EstDate = $("#txtFilterTanggal1").val();
        var NamaDM = $("#NamaDM").val();
        var EstHalf = $("#EstHalf").val();
        // var id = '#ManHour*'+Expense;
        // alert(id);
        $("#ManHourModal").modal('hide');
        // $("#ManHour").val(ValE);
        // alert(DataRows);
        $("#FormExpense tr[data-idrows='" + Expense + "']").find("td:eq(2)").text(ValE);
        $("#FormExpense tr[data-idrows='" + Expense + "']").find("td:eq(3)").text(MachHour);
        $("#FormExpense tr[data-idrows='" + Expense + "']").find("td:eq(4)").text(MatCost);
        $("#FormExpense tr[data-idrows='" + Expense + "']").find("td:eq(5)").text(ValC);
        $("#FormExpense tr[data-idrows='" + Expense + "']").find("td:eq(6)").text(EstDate);
        $("#FormExpense tr[data-idrows='" + Expense + "']").find("td:eq(7)").text(EstHalf);
        $("#FormExpense tr[data-idrows='" + Expense + "']").find("td:eq(8)").text(NamaDM);
    });
}
function BTN_SUBMIT(Quote)
{
    $("#BtnSubmitWO").click(function(){
        var FilPM = $("#FilPM").val();
        var FilCOPM = $("#FilCOPM").val();
        var FilCategory = $("#FilCategory").val();
        var FilWOP = $("#FILWOP").val();
        var QtyWOP = $("#QtyWOP").val();
        var FilProduct = $("#FilProduct").val();
        var FilWOC = $("#FilWOC").val();
        var JenisWO = $("#JenisWO").val();
        var TipeOrder = $("#TipeOrder").val();
        if($("#FormExpense tr[data-idrows='MACHINING']").find("td:eq(2)").text() === ''){ var Exp1 = '';}
        else
        { 
            var Expense1 = $("#FormExpense tr[data-idrows='MACHINING']").find("td:eq(1)").text();
            var ManHour1 = $("#FormExpense tr[data-idrows='MACHINING']").find("td:eq(2)").text();
            var MachHour1 = $("#FormExpense tr[data-idrows='MACHINING']").find("td:eq(3)").text();
            var MatCost1 = $("#FormExpense tr[data-idrows='MACHINING']").find("td:eq(4)").text();
            var LimMax1 = $("#FormExpense tr[data-idrows='MACHINING']").find("td:eq(5)").text();
            var EstDate1 = $("#FormExpense tr[data-idrows='MACHINING']").find("td:eq(6)").text();
            var EstHalf1 = $("#FormExpense tr[data-idrows='MACHINING']").find("td:eq(7)").text();
            var dm1 = $("#FormExpense tr[data-idrows='MACHINING']").find("td:eq(8)").text();
            Exp1 = Expense1+'+'+ManHour1+'+'+MachHour1+'+'+MatCost1+'+'+LimMax1+'+'+EstDate1+'+'+EstHalf1+'+'+dm1;
        }
        if($("#FormExpense tr[data-idrows='FABRICATION']").find("td:eq(2)").text() === ''){ var Exp2 = '';}
        else
        { 
            var Expense1 = $("#FormExpense tr[data-idrows='FABRICATION']").find("td:eq(1)").text();
            var ManHour1 = $("#FormExpense tr[data-idrows='FABRICATION']").find("td:eq(2)").text();
            var MachHour1 = $("#FormExpense tr[data-idrows='FABRICATION']").find("td:eq(3)").text();
            var MatCost1 = $("#FormExpense tr[data-idrows='FABRICATION']").find("td:eq(4)").text();
            var LimMax1 = $("#FormExpense tr[data-idrows='FABRICATION']").find("td:eq(5)").text();
            var EstDate1 = $("#FormExpense tr[data-idrows='FABRICATION']").find("td:eq(6)").text();
            var EstHalf1 = $("#FormExpense tr[data-idrows='FABRICATION']").find("td:eq(7)").text();
            var dm1 = $("#FormExpense tr[data-idrows='FABRICATION']").find("td:eq(8)").text();
            Exp2 = Expense1+'+'+ManHour1+'+'+MachHour1+'+'+MatCost1+'+'+LimMax1+'+'+EstDate1+'+'+EstHalf1+'+'+dm1;
        }
        if($("#FormExpense tr[data-idrows='INJECTION']").find("td:eq(2)").text() === ''){ var Exp3 = '';}
        else
        { 
            var Expense1 = $("#FormExpense tr[data-idrows='INJECTION']").find("td:eq(1)").text();
            var ManHour1 = $("#FormExpense tr[data-idrows='INJECTION']").find("td:eq(2)").text();
            var MachHour1 = $("#FormExpense tr[data-idrows='INJECTION']").find("td:eq(3)").text();
            var MatCost1 = $("#FormExpense tr[data-idrows='INJECTION']").find("td:eq(4)").text();
            var LimMax1 = $("#FormExpense tr[data-idrows='INJECTION']").find("td:eq(5)").text();
            var EstDate1 = $("#FormExpense tr[data-idrows='INJECTION']").find("td:eq(6)").text();
            var EstHalf1 = $("#FormExpense tr[data-idrows='INJECTION']").find("td:eq(7)").text();
            var dm1 = $("#FormExpense tr[data-idrows='INJECTION']").find("td:eq(8)").text();
            Exp3 = Expense1+'+'+ManHour1+'+'+MachHour1+'+'+MatCost1+'+'+LimMax1+'+'+EstDate1+'+'+EstHalf1+'+'+dm1;
        }
        if($("#FormExpense tr[data-idrows='ELECTRONICS']").find("td:eq(2)").text() === ''){ var Exp4 = '';}
        else
        { 
            var Expense1 = $("#FormExpense tr[data-idrows='ELECTRONICS']").find("td:eq(1)").text();
            var ManHour1 = $("#FormExpense tr[data-idrows='ELECTRONICS']").find("td:eq(2)").text();
            var MachHour1 = $("#FormExpense tr[data-idrows='ELECTRONICS']").find("td:eq(3)").text();
            var MatCost1 = $("#FormExpense tr[data-idrows='ELECTRONICS']").find("td:eq(4)").text();
            var LimMax1 = $("#FormExpense tr[data-idrows='ELECTRONICS']").find("td:eq(5)").text();
            var EstDate1 = $("#FormExpense tr[data-idrows='ELECTRONICS']").find("td:eq(6)").text();
            var EstHalf1 = $("#FormExpense tr[data-idrows='ELECTRONICS']").find("td:eq(7)").text();
            var dm1 = $("#FormExpense tr[data-idrows='ELECTRONICS']").find("td:eq(8)").text();
            Exp4 = Expense1+'+'+ManHour1+'+'+MachHour1+'+'+MatCost1+'+'+LimMax1+'+'+EstDate1+'+'+EstHalf1+'+'+dm1;
        }
        if($("#FormExpense tr[data-idrows='ASSEMBLY']").find("td:eq(2)").text() === ''){ var Exp5 = '';}
        else
        { 
            var Expense1 = $("#FormExpense tr[data-idrows='ASSEMBLY']").find("td:eq(1)").text();
            var ManHour1 = $("#FormExpense tr[data-idrows='ASSEMBLY']").find("td:eq(2)").text();
            var MachHour1 = $("#FormExpense tr[data-idrows='ASSEMBLY']").find("td:eq(3)").text();
            var MatCost1 = $("#FormExpense tr[data-idrows='ASSEMBLY']").find("td:eq(4)").text();
            var LimMax1 = $("#FormExpense tr[data-idrows='ASSEMBLY']").find("td:eq(5)").text();
            var EstDate1 = $("#FormExpense tr[data-idrows='ASSEMBLY']").find("td:eq(6)").text();
            var EstHalf1 = $("#FormExpense tr[data-idrows='ASSEMBLY']").find("td:eq(7)").text();
            var dm1 = $("#FormExpense tr[data-idrows='ASSEMBLY']").find("td:eq(8)").text();
            Exp5 = Expense1+'+'+ManHour1+'+'+MachHour1+'+'+MatCost1+'+'+LimMax1+'+'+EstDate1+'+'+EstHalf1+'+'+dm1;
        }
        if($("#FormExpense tr[data-idrows='QUALITY ASSURANCE']").find("td:eq(2)").text() === ''){ var Exp6 = '';}
        else
        { 
            var Expense1 = $("#FormExpense tr[data-idrows='QUALITY ASSURANCE']").find("td:eq(1)").text();
            var ManHour1 = $("#FormExpense tr[data-idrows='QUALITY ASSURANCE']").find("td:eq(2)").text();
            var MachHour1 = $("#FormExpense tr[data-idrows='QUALITY ASSURANCE']").find("td:eq(3)").text();
            var MatCost1 = $("#FormExpense tr[data-idrows='QUALITY ASSURANCE']").find("td:eq(4)").text();
            var LimMax1 = $("#FormExpense tr[data-idrows='QUALITY ASSURANCE']").find("td:eq(5)").text();
            var EstDate1 = $("#FormExpense tr[data-idrows='QUALITY ASSURANCE']").find("td:eq(6)").text();
            var EstHalf1 = $("#FormExpense tr[data-idrows='QUALITY ASSURANCE']").find("td:eq(7)").text();
            var dm1 = $("#FormExpense tr[data-idrows='QUALITY ASSURANCE']").find("td:eq(8)").text();
            Exp6 = Expense1+'+'+ManHour1+'+'+MachHour1+'+'+MatCost1+'+'+LimMax1+'+'+EstDate1+'+'+EstHalf1+'+'+dm1;
        }
        if($("#FormExpense tr[data-idrows='SHIPPING']").find("td:eq(2)").text() === ''){ var Exp7 = '';}
        else
        { 
            var Expense1 = $("#FormExpense tr[data-idrows='SHIPPING']").find("td:eq(1)").text();
            var ManHour1 = $("#FormExpense tr[data-idrows='SHIPPING']").find("td:eq(2)").text();
            var MachHour1 = $("#FormExpense tr[data-idrows='SHIPPING']").find("td:eq(3)").text();
            var MatCost1 = $("#FormExpense tr[data-idrows='SHIPPING']").find("td:eq(4)").text();
            var LimMax1 = $("#FormExpense tr[data-idrows='SHIPPING']").find("td:eq(5)").text();
            var EstDate1 = $("#FormExpense tr[data-idrows='SHIPPING']").find("td:eq(6)").text();
            var EstHalf1 = $("#FormExpense tr[data-idrows='SHIPPING']").find("td:eq(7)").text();
            var dm1 = $("#FormExpense tr[data-idrows='SHIPPING']").find("td:eq(8)").text();
            Exp7 = Expense1+'+'+ManHour1+'+'+MachHour1+'+'+MatCost1+'+'+LimMax1+'+'+EstDate1+'+'+EstHalf1+'+'+dm1;
        }
        var All = Quote+'*'+FilPM+'*'+FilCOPM+'*'+FilCategory+'*'+FilWOP+'*'+QtyWOP+'*'+FilProduct+'*'+FilWOC+'*'+JenisWO+'*'+TipeOrder;
        var All2 = Exp1+'*'+Exp2+'*'+Exp3+'*'+Exp4+'*'+Exp5+'*'+Exp6+'*'+Exp7;
        var formdata = new FormData();
        formdata.append('DataAtas', All);
        formdata.append('DataBawah', All2);
        $.ajax({
            url: 'project/WOMapping/src/srcInputWOMappingBaru.php',
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: formdata,
            type: 'post',
            beforeSend: function () {
                $("#BtnSubmitWO").attr('disabled', true);
                $('#SubmitWO').html("");
                $('#SubmitWO').html("");
            },
            success: function (xaxa) {
                $('#SubmitWO').html("");
                $('#SubmitWO').hide();
                $('#SubmitWO').html(xaxa);
                $('#SubmitWO').fadeIn('fast');
                $("#BtnSubmitWO").blur();
                $("#BtnSubmitWO").attr('disabled', false);
            },
            error: function () {
                alert("Request cannot proceed!");
                $("#BtnSubmitWO").blur();
                $('#SubmitWO').html("");
                $("#BtnSubmitWO").attr('disabled', false);
            }
        });
    });
}