$(document).ready(function () {
    $("#PostProduct").change(function () {
        var Res = $("#PostProduct").val();
        if (Res != "0") {
            $("#PostSubstation")
                .find("option")
                .show()
                .not("option[class ='" + this.value + "']").hide();
            $("#PostSubstation").val($("#PostSubstation").find("option:visible:first").val());
            $("#PostSubstation")[0].selectedIndex = 0;
        }
        else {
            $("#PostSubstation")
                .find("option")
                .show()
                .not("option[class ='" + this.value + "']").show();
        }
    }).change();
    
    $("#BtnSimpanMappingSubstation").click(function () {
        var ActionName = $("#BtnSimpanMappingSubstation").text();
        if (ActionName == "Simpan")
        {
            $("#BtnSimpanMappingSubstation").blur();
            var ProductVal = $("#PostProduct option:selected").val();
            var SubstationVal = $("#PostSubstation option:selected").val();
            AddMapping(ProductVal, SubstationVal, ActionName);
        }
        if (ActionName == "Update")
        {
            $("#BtnSimpanMappingSubstation").blur();
            var ProductVal = $("#PostProduct option:selected").val();
            var SubstationVal = $("#PostSubstation option:selected").val();
            AddMapping(ProductVal, SubstationVal, ActionName);
        }
        
    });

    function AddMapping(Product, Substation, ActionName)
    {
        if (Product == "0")
        {
            alert("Mohon dipilih dengan benar!");
            $("#PostProduct").focus();
            return false;
        }
        if (Substation == "0")
        {
            alert("Mohon dipilih dengan benar!");
            $("#PostSubstation").focus();
            return false;
        }
        if (ActionName != "Simpan" && ActionName != "Update")
        {
            alert("Proses tidak dikenal!");
            return false;
        }        
        var FormDataMapping = new FormData();
        FormDataMapping.append('ValProduct', Product);
        FormDataMapping.append('ValSubstation', Substation);
        FormDataMapping.append('ValAction', ActionName);
        $.ajax({
            url: 'src/addnewmappingsubstationactivity.php',
            data: FormDataMapping,
            dataType: 'html',
            cache: false,
            contentType: false,
            processData: false,
            type: "POST",
            beforeSend: function () {
                $("#LoadingAdd").show();
                $('#BtnSimpanMappingSubstation').blur();
                $('#BtnSimpanMappingSubstation').attr('disabled', true);
                $('#DivContentChecking').html("");
            },
            success: function (xaxa) {
                $("#LoadingAdd").hide();
                $('#DivContentChecking').hide();
                $('#DivContentChecking').html("");
                $('#DivContentChecking').html(xaxa);
                $('#DivContentChecking').fadeIn('fast');
                $('#DivContentChecking').html("");
                $('#BtnSimpanMappingSubstation').attr('disabled', false);
            },
            error: function () {
                $("#LoadingAdd").hide();
                $("#DivContentChecking").html('<div class="form-group"><div class="alert alert-danger alert-dismissible text-center" role="alert" ><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Request cannot proceed! Try Again!</div></div>');
                $('#BtnSimpanMappingSubstation').attr('disabled', false);
            }
        });
    }
});