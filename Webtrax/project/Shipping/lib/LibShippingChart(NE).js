$(document).ready(function(){
    $("#BtnViewWeightChart").click(function(){
        var InputYear = $("#InputSeason option:selected").val().trim();
        var formdata = new FormData();
        formdata.append("InputYear", InputYear);
        $.ajax({
            url: 'project/Shipping/FreightQtyWeightChartContent.php',
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: formdata,
            type: 'POST',
            beforeSend: function () {
                $('#BtnViewWeightChart').attr('disabled', true);
                $('#ResultChart').html("");
                $("#ResultChart").append('<div class="col-sm-12" id="ContentLoading"><img src="../images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
            },
            success: function (xaxa) {
                $("#ContentLoading").remove();
                $('#ResultChart').html(xaxa);
                $('#ResultChart').fadeIn('fast');
                $('#BtnViewWeightChart').attr('disabled', false);
                $("#TableDataChart1").DataTable({
                    "pagingType": "full",
                    order: [[0, 'desc']]
                });
            },
            error: function () {
                alert('Request cannot proceed!');
                $('#BtnViewWeightChart').attr('disabled', false);
            }
        });
    });
    SHOW_ALL_DESTINATION();
    $("#BtnDestinationChart").click(function () {
        var InputYear = $("#InputSeason option:selected").val().trim();
        var formdata = new FormData();
        formdata.append("InputYear", InputYear);
        $.ajax({
            url: 'project/Shipping/DestinationCountryChartContentV2.php',
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: formdata,
            type: 'POST',
            beforeSend: function () {
                $('#AllDestinationChart').hide();
                $('#DetailDestination').hide();
                $('#BtnDestinationChart').attr('disabled', true);
                $('#ResultChart').html("");
                $("#ResultChart").append('<div class="col-sm-12" id="ContentLoading"><img src="../images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
            },
            success: function (xaxa) {
                $('#AllDestinationChart').hide();
                $('#ResultChart').html(xaxa);
                $('#ResultChart').fadeIn('fast');
                $('#BtnDestinationChart').attr('disabled', false);
                $("#TableDataChart2").DataTable({
                    "pagingType": "full"
                });
                SHOW_DETAIL();
            },
            error: function () {
                alert('Request cannot proceed!');
                $('#BtnDestinationChart').attr('disabled', false);
            }
        });
    });
    $("#BtnCourierChart").click(function () {
        var InputYear = $("#InputSeason option:selected").val().trim();
        var formdata = new FormData();
        formdata.append("InputYear", InputYear);
        $.ajax({
            url: 'project/Shipping/CourierChartContent.php',
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: formdata,
            type: 'POST',
            beforeSend: function () {
                $('#BtnCourierChart').attr('disabled', true);
                $('#ResultChart').html("");
                $("#ResultChart").append('<div class="col-sm-12" id="ContentLoading"><img src="../images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
            },
            success: function (xaxa) {
                $('#ResultChart').html(xaxa);
                $('#ResultChart').fadeIn('fast');
                $('#BtnCourierChart').attr('disabled', false);
                $("#TableDataChart3").DataTable({
                    "pagingType": "full"
                });
            },
            error: function () {
                alert('Request cannot proceed!');
                $('#BtnCourierChart').attr('disabled', false);
            }
        });
    });

});
function SHOW_DETAIL()
{
    var Bol = "TRUE";
    if (Bol == "TRUE") {
        $(".PointerList").click(function () {
            if (Bol == "TRUE") {
                $("#TableSumDestination tr").removeClass('PointerListSelected');
                $(this).closest('.PointerList').addClass("PointerListSelected");
                var Data = $(this).data('id');
                var formdata = new FormData();
                formdata.append('EncData', Data);
                $.ajax({
                    url: 'project/Shipping/DestinationCountryDetail.php',
                    dataType: 'text',
                    cache: false,
                    contentType: false,
                    processData: false,
                    data: formdata,
                    type: 'post',
                    beforeSend: function () {
                        Bol = "FALSE";
                        $('#AllDestinationChart').hide();
                        $('#DetailDestination').html("");
                        $("#ContentLoading").remove();
                        $("#DetailDestination").before('<div class="col-sm-12" id="ContentLoading"><img src="../images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
                        $('#DetailDestination').html("");
                    },
                    success: function (xaxa) {
                        $('#AllDestinationChart').hide();
                        $('#DetailDestination').html("");
                        $('#DetailDestination').hide();
                        $('#DetailDestination').html(xaxa);
                        $('#DetailDestination').fadeIn('fast');
                        $("#ContentLoading").remove();
                        Bol = "TRUE";
                        SHOW_MODAL();
                    },
                    error: function () {
                        alert("Request cannot proceed!");
                        $('#DetailDestination').html("");
                        $("#ContentLoading").remove();
                        Bol = "TRUE";
                    }
                });
            }
            else {
                return false;
            }
        });
    }
}
function SHOW_MODAL()
{
    $("#ModalShipment").on('show.bs.modal', function (event) {
        var act = $(event.relatedTarget);
        var DataCode = act.data('ecode');
        var formdata = new FormData();
        formdata.append("ValCode", DataCode);
        $.ajax({
            url: 'project/Shipping/DestinationShippingModal.php',
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: formdata,
            type: 'post',
            beforeSend: function () {
                $('#LoadingImg').show();
                $('#ContentModal').html("");
            },
            success: function (xaxa) {
                $('#LoadingImg').hide();
                $('#ContentModal').hide();
                $('#ContentModal').html(xaxa);
                $('#ContentModal').fadeIn('fast');
                $("#ModalShippingTable").DataTable({
                });
            },
            error: function () {
                $('#LoadingImg').hide();
                alert('Request cannot proceed!');
            }
        });
    });
}
function SHOW_ALL_DESTINATION()
{
    $.ajax({
        url: 'project/Shipping/DestinationCountryChartContentAll.php',
        dataType: 'text',
        cache: false,
        contentType: false,
        processData: false,
        beforeSend: function () {
            $('#ResultChart').hide();
            $('#DetailDestination').hide();
            $('#AllDestinationChart').html("");
            $("#AllDestinationChart").append('<div class="col-sm-12" id="ContentLoading"><img src="../images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
        },
        success: function (xaxa) {
            $('#ResultChart').hide();
            $('#DetailDestination').hide();
            $('#AllDestinationChart').hide();
            $('#AllDestinationChart').html(xaxa);
            $('#AllDestinationChart').fadeIn('fast');
            $("#ContentLoading").remove();
        },
        error: function () {
            alert('Request cannot proceed!');
            $("#ContentLoading").remove();
        }
    });
}