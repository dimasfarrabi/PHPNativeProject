$(document).ready(function(){
    VIEW_ALL();
    $("#BtnView").click(function(){
        var InputYear = $("#InputSeason option:selected").val().trim();
        var formdata = new FormData();
        if(InputYear == 'ALL')
        {
            VIEW_ALL();
        }
        else
        {
            formdata.append("InputYear", InputYear);
            $.ajax({
                url: 'project/Shipping/InboundFreightContent.php',
                dataType: 'text',
                cache: false,
                contentType: false,
                processData: false,
                data: formdata,
                type: 'POST',
                beforeSend: function () {
                    $('#BtnView').attr('disabled', true);
                    $('#ContentChartAll').hide();
                    $('#ContentChart').html("");
                    $("#ContentChart").before('<div class="col-sm-12" id="ContentLoading"><img src="../images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
                },
                success: function (xaxa) {
                    $("#ContentLoading").remove();
                    $('#ContentChartAll').hide();
                    $('#ContentChart').html(xaxa);
                    $('#ContentChart').fadeIn('fast');
                    $('#BtnView').attr('disabled', false);
                    $("#TabelDataInbound").DataTable({
                        "pagingType": "full"
                    });
                    MODAL_DETAILS();
                },
                error: function () {
                    alert('Request cannot proceed!');
                    $('#BtnView').attr('disabled', false);
                    $('#ContentChartAll').hide();
                }
            });
        }
    });
        
});
function VIEW_ALL()
{
    $.ajax({
        url: 'project/Shipping/InboundFreightContentAll.php',
        dataType: 'text',
        cache: false,
        contentType: false,
        processData: false,
        beforeSend: function () {
            $('#ContentChartAll').html("");
            $('#ContentChart').hide();
            $("#ContentChartAll").append('<div class="col-sm-12" id="ContentLoading2"><img src="../images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
        },
        success: function (xaxa) {
            $("#ContentLoading2").remove();
            $('#ContentChart').hide();
            $('#ContentChartAll').html(xaxa);
            $('#ContentChartAll').fadeIn('fast');
            $("#AllTableInbound").DataTable({
                "pagingType": "full"
            });
            MODAL_ALL_DETAILS();
        },
        error: function () {
            alert('Request cannot proceed!');
            $("#ContentLoading2").remove();
            $('#ContentChart').hide();
        }
    });
}
function MODAL_DETAILS()
{
    $("#InboundDetail").on('show.bs.modal', function (event) {
    var act = $(event.relatedTarget);
    var DataCode = act.data('ecode');
    var formdata = new FormData();
    formdata.append("ValCode", DataCode);
        $.ajax({
            url: 'project/Shipping/IboundFreightDetail.php',
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: formdata,
            type: 'post',
            beforeSend: function () {
                $('#LoadingImg').show();
                $('#ContentDetails').html("");
            },
            success: function (xaxa) {
                $('#LoadingImg').hide();
                $('#ContentDetails').hide();
                $('#ContentDetails').html(xaxa);
                $('#ContentDetails').fadeIn('fast');
                
            },
            error: function () {
                $('#LoadingImg').hide();
                alert('Request cannot proceed!');
            }
        });
    });
}
function MODAL_ALL_DETAILS()
{
    $("#AllDetail").on('show.bs.modal', function (event) {
    var act = $(event.relatedTarget);
    var DataCode = act.data('ecode');
    var formdata = new FormData();
    formdata.append("ValCode", DataCode);
        $.ajax({
            url: 'project/Shipping/IboundFreightDetailALL.php',
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: formdata,
            type: 'post',
            beforeSend: function () {
                $('#LoadingImg').show();
                $('#AllContentDetails').html("");
            },
            success: function (xaxa) {
                $('#LoadingImg').hide();
                $('#AllContentDetails').hide();
                $('#AllContentDetails').html(xaxa);
                $('#AllContentDetails').fadeIn('fast');
                $("#InboundTableDetail").DataTable({
                    "pagingType": "full"
                });
            },
            error: function () {
                $('#LoadingImg').hide();
                alert('Request cannot proceed!');
            }
        });
    });
}