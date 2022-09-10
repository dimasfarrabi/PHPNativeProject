$(document).ready(function () {
    var LabelScanText = $("#LabelScan").html();
    if (LabelScanText == "Scan BC Label Item") {
        $("#BtnScan").html("Scan");
    }
    $("#txtFilterDateIN1").datetimepicker({
        lang: "en",
        timepicker: false,
        format: "m/d/Y",
        formatDate: "m/d/Y",
        theme: "dark"
    });
    $("#txtFilterDateOUT1").datetimepicker({
        lang: "en",
        timepicker: false,
        format: "m/d/Y",
        formatDate: "m/d/Y",
        theme: "dark"
    });
    $("#txtFilterDateIN2").datetimepicker({
        lang: "en",
        timepicker: false,
        format: "m/d/Y",
        formatDate: "m/d/Y",
        theme: "dark"
    });
    $("#txtFilterDateOUT2").datetimepicker({
        lang: "en",
        timepicker: false,
        format: "m/d/Y",
        formatDate: "m/d/Y",
        theme: "dark"
    });
    $("#TextInfoLog").css({ "resize": "none", "background-color": "#191970", "color": "#FFFF00", "height": "180px", "font-size": "14px" });
    $("#InputScan").css({ "font-size": "20px" });
    $("#BtnHistory").click(function () {
        VIEW_HISTORY();
    });
    
    $("#InputCompany").on("change", function () {
        if ($("#InputCompany").val().trim() != "U1dRNk1BPT0=") {
            $("#InputCompany").attr('disabled', true);
            $("#StockType").focus();
            return false;
        }
        else
        {
            $("#InputCompany").attr('disabled', false);
            return false;
        }
    });
    $("#InputScan").on("keypress", function (e) {
        if (e.which == 13) {
            SCAN_LABEL();
        }
    });
    $("#BtnScan").click(function () {
        var LabelScanText = $("#LabelScan").html();
        if (LabelScanText == "Scan BC Label Item") {
            SCAN_LABEL();
        }
        if (LabelScanText == "Scan Jenis Stock") {
            if ($("#InfoStockType").val().trim() == "") {
                $("#ModalTypeStocks").modal("show");
            }
        }
        if (LabelScanText == "Scan Barcode WO ID") {
            $("#ModalWO").modal("show");
            $("#ModalWO").on('shown.bs.modal', function () {
                $("#FilterInputKeywordWO").val("");
                $("#FilterInputKeywordWO").focus();
            });
        }
        if (LabelScanText == "Pilih Gudang Kecil") {
            SHOW_SMALL_WAREHOUSE();
        }
        if ($("#BtnScan").text() == "Submit")
        {
            CHECK_SUBMIT();
        }
    });
    $("#TableTypeStock").on("click", "tbody .BtnSelect", function () {
        var ValIDTypeStock = $(this).closest("tr").find("td:eq(2)").html();
        $("#InfoStockType").val(ValIDTypeStock);
        $("#ModalTypeStocks").modal("hide");
        if ($("#InfoStockType").val().trim() != "") {
            if ($("#InfoStockType").val().trim() == "Masuk Stock")
            {
                $("#LabelScan").text("Pilih Gudang Kecil");
                $("#InputScan").attr('disabled', true);
                $("#InputScan").val("");
                $("#BtnScan").html("Cari");
                $("#BtnScan").get(0).focus();
            }
            else if ($("#InfoStockType").val().trim() == "Tanpa Stock RM/Tools")
            {
                $("#LabelScan").text("Pilih Gudang Kecil");
                $("#InputScan").attr('disabled', true);
                $("#InputScan").val("");
                $("#BtnScan").html("Cari");
                $("#BtnScan").get(0).focus();
            }
            else
            {
                $("#LabelScan").text("Scan Barcode WO ID");
                $("#InputScan").val("");
                $("#InputScan").get(0).focus();
            }
        }
        else {
            $("#LabelScan").text("Scan Jenis Stock");
            $("#BtnScan").html("Cari");
        }
        $("#InputScan").focus();
    });
    $("#BtnSearchWO").click(function () {
        FIND_WO();
    });
    $("#ModalWO").on('hide.bs.modal', function () {
        $("#FilterTypeCategoryFindWO").prop("selectedIndex", 0);
        $("#FilterInputKeywordWO").val("");
        $("#ContentModalWO").html("");
    });
    $("#FilterInputKeywordWO").on("keypress", function (e) {
        if (e.which == 13) {
            FIND_WO();
        }
    });

    $("#ModalConfirmation").on('hide.bs.modal', function () {
        $("#ConfirmCompany").val("");
        $("#ConfirmLocation").val("");
        $("#ConfirmGudang").val("");
        $("#ConfirmStockType").val("");
        $("#ConfirmLabelID").val("");
        $("#ConfirmIDTBZ").val("");
        $("#ConfirmWOTBZ").val("");
        $("#ConfirmProduct").val("");
        $("#ConfirmJobID").val("");
        $("#ConfirmPartNo").val("");
        $("#ConfirmCategory").val("");
        $("#ConfirmPartDesc").val("");
        $("#ConfirmWOChild").val("");
        $("#ConfirmExpense").val("");
        $("#ConfirmQty").val("");
        $("#ConfirmUOM").val("");
        $("#ConfirmUnitCost").val("");
    });
    $("#ModalConfirmation").on('shown.bs.modal', function () {
        $("#BtnSubmit").focus();
    });

    
    $("#BtnSubmit").click(function () {
        $("#BtnSubmit").attr('disabled', true);
        var InputNIK = $("#ConfirmNIK").val();
        var InputName = $("#ConfirmName").val();
        var InputDivision = $("#ConfirmDivision").val();
        var InputCompany = $("#ConfirmCompany").val();
        var InputLocation = $("#ConfirmLocation").val();
        var InputGudang = $("#ConfirmGudang").val();
        var InputStockType = $("#ConfirmStockType").val();
        var InputLabelID = $("#ConfirmLabelID").val();
        var InputIDTBZ = $("#ConfirmIDTBZ").val();
        var InputWOTBZ = $("#ConfirmWOTBZ").val();
        var InputProduct = $("#ConfirmProduct").val();
        var InputJobID = $("#ConfirmJobID").val();
        var InputPartNo = $("#ConfirmPartNo").val();
        var InputCategory = $("#ConfirmCategory").val();
        var InputPartDesc = $("#ConfirmPartDesc").val();
        var InputWOMappingID = $("#ConfirmWOID").val();
        var InputWOChild = $("#ConfirmWOChild").val();
        var InputExpense = $("#ConfirmExpense").val();
        var InputQty = $("#ConfirmQty").val();
        var InputUOM = $("#ConfirmUOM").val();
        var InputUnitCost = $("#ConfirmUnitCost").val();
        

        setTimeout(function () {
            var _qtyInput = parseFloat(InputQty);
            var _qtyReceived = "0";
            var _stockLama = "0";
            var _totalCost = "0";
            var _materialDivision = "";
            var _stockDivision = "";
            var _materialCodeInitial = "";
            var _materialStockOwner = "";
            var _transactionDate = "";
            var timeAct = GET_TIME_NOW();
            var TextLog = $("#TextInfoLog").val();
            var NewTextLog = TextLog + "[" + timeAct + "] =====================\n";
            $("#TextInfoLog").val(NewTextLog);
            $("#TextInfoLog").focus();
            // proses awal
            var timeAct2 = GET_TIME_NOW();
            var TextLog2 = $("#TextInfoLog").val();
            var NewTextLog2 = TextLog2 + "[" + timeAct2 + "] BC Label Issue : " + InputLabelID + " >> Start Processing\n";
            $("#TextInfoLog").val(NewTextLog2);
            $("#TextInfoLog").focus();
            // proses pencarian tgl transaksi tbz
            var formdata = new FormData();
            formdata.append("ProcessType", "Get Transact Date From TBZ");
            formdata.append("InputSequence", InputIDTBZ);
            $.ajax({
                url: 'project/Inventory/src/srcAddDataOutPartTBZ.php',
                dataType: 'text',
                cache: false,
                contentType: false,
                processData: false,
                async: false,
                data: formdata,
                type: 'post',
                beforeSend: function () {
                },
                success: function (xaxa) {
                    var Res = xaxa;
                    var ArrRes = Res.split(" >> ");
                    switch (ArrRes[0]) {
                        case "TRUE":
                            _transactionDate = ArrRes[1];
                            break;
                        case "FALSE":
                            _transactionDate = ArrRes[1];
                            break;
                        default:
                            var timeAct3 = GET_TIME_NOW();
                            var TextLog3 = $("#TextInfoLog").val();
                            var NewTextLog3 = TextLog3 + "[" + timeAct3 + "] [ERROR] : Proses pemgambilan tanggal transaksi gagal!\n";
                            $("#TextInfoLog").val(NewTextLog3);
                            $("#TextInfoLog").focus();
                            break;
                    }
                },
                error: function () {
                    alert("Request cannot proceed!");
                }
            });
            // set stock division
            var DivisionWH = InputGudang;
            // var CompanyWH = InputCompany;
            var CompanyWH = InputLocation;
            _stockDivision = DivisionWH;
            switch (_stockDivision) {
                case "ROCK IMAGER ASSEMBLY":
                    _materialDivision = "ROC";
                    _materialStockOwner = "AssyMaterial";
                    break;
                case "ASSEMBLY":
                    _materialDivision = "ASM";
                    _materialStockOwner = "AssyMaterial";
                    break;
                case "ELECTRONICS":
                    _materialDivision = "ELC";
                    _materialStockOwner = "ElectMaterial";
                    break;
                case "MACHINING":
                    _materialDivision = "MCH";
                    _materialStockOwner = "MachMaterial";
                    break;
                case "QUALITY ASSURANCE":
                    _materialDivision = "QOR";
                    _materialStockOwner = "QAMaterial";
                    break;
                case "MECHANICAL ENGINEERING":
                    _materialDivision = "R&D";
                    _materialStockOwner = "R&DMaterial";
                    break;
                case "MECHANICAL ENGINEER":
                    _materialDivision = "R&D";
                    _materialStockOwner = "R&DMaterial";
                    break;
                case "INJECTION MOLD ENGINEERING":
                    _materialDivision = "IME";
                    _materialStockOwner = "INJECTION MOLD ENGINEERING";
                    break;
                case "INJECTION MOLD ENGINEER":
                    _materialDivision = "IME";
                    _materialStockOwner = "INJECTION MOLD ENGINEERING";
                    break;
                default:
                    _materialDivision = _stockDivision.substring(0, 3);
                    _materialStockOwner = "XXMaterial";
                    break;
            }
            // set categori material
            switch (InputCategory) {
                case "OTS":
                    _materialCodeInitial = "OTS";
                    break;
                case "MTS":
                    _materialCodeInitial = "MTS";
                    break;
                case "Support Materials":
                    _materialCodeInitial = "SP";
                    break;
                case "Raw Materials":
                    _materialCodeInitial = "RM";
                    break;
                case "Packaging Materials":
                    _materialCodeInitial = "PM";
                    break;
                case "Customer Kit":
                    _materialCodeInitial = "CK";
                    break;
                case "Tools":
                    _materialCodeInitial = "TL";
                    break;
                case "R&D":
                    _materialCodeInitial = "R&D";
                    break;
                default:
                    _materialCodeInitial = "XX";
                    break;
            }
            // get transact id
            var _transactID = _materialCodeInitial + "-" + _materialDivision + "-" + GET_GROUP_NO();
            // get total cost
            if (InputCategory == "MTS") {
                _unitCost = "0";
            }
            else {
                _unitCost = InputUnitCost;
            }
            _totalCost = (_unitCost * _qtyInput);
            // get uom conv
            var _UOMConv = InputUOM;
            var formdata = new FormData();
            formdata.append("ProcessType", "Get UOM Conv");
            formdata.append("PartNo", InputPartNo);
            $.ajax({
                url: 'project/Inventory/src/srcAddDataOutPartTBZ.php',
                dataType: 'text',
                cache: false,
                contentType: false,
                processData: false,
                data: formdata,
                type: 'post',
                async: false,
                beforeSend: function () {
                },
                success: function (xaxa) {
                    var Res = xaxa;
                    var ArrRes = Res.split(" >> ");
                    switch (ArrRes[0]) {
                        case "TRUE":
                            _UOMConv = ArrRes[1];
                            break;
                        case "FALSE":
                            _UOMConv = InputUOM;
                            break;
                        default:
                            var timeAct4 = GET_TIME_NOW();
                            var TextLog4 = $("#TextInfoLog").val();
                            var NewTextLog4 = TextLog4 + "[" + timeAct4 + "] [ERROR] : Proses konversi UOM gagal!\n";
                            $("#TextInfoLog").val(NewTextLog4);
                            $("#TextInfoLog").focus();
                            break;
                    }
                },
                error: function () {
                    alert("Request cannot proceed!");
                }
            });

            if (InputStockType == "Tanpa Stock") {
                // bila transaksi tidak mempengaruhi jumlah stock (maka langsung proses IN / OUT)
                var timeAct5 = GET_TIME_NOW();
                var TextLog5 = $("#TextInfoLog").val();
                var NewTextLog5 = TextLog5 + "[" + timeAct5 + "] Status Stock : Tanpa Stock\n";
                $("#TextInfoLog").val(NewTextLog5);
                var _getTotalDuplicateSequenceID = 0;
                var formdata = new FormData();
                formdata.append("ProcessType", "CHECK EXISTING TLI");
                formdata.append("SequenceID", InputIDTBZ);
                $.ajax({
                    url: 'project/Inventory/src/srcAddDataOutPartTBZ.php',
                    dataType: 'text',
                    cache: false,
                    contentType: false,
                    processData: false,
                    data: formdata,
                    type: 'post',
                    async: false,
                    beforeSend: function () {
                    },
                    success: function (xaxa) {
                        _getTotalDuplicateSequenceID = xaxa;
                    },
                    error: function () {
                        alert("Request cannot proceed!");
                    }
                });
                if (parseInt(_getTotalDuplicateSequenceID) > 0) 
                {
                    var timeAct6 = GET_TIME_NOW();
                    var TextLog6 = $("#TextInfoLog").val();
                    var NewTextLog6 = TextLog6 + "[" + timeAct6 + "] BC Label Issue : " + InputLabelID + " >> already exist. Process canceled.\n";
                    $("#TextInfoLog").val(NewTextLog6);
                    $("#TextInfoLog").focus();
                    RESET_FORM();
                    $("#LabelScan").text("Scan BC Label Item");
                    $("#InputScan").focus();
                }
                else {
                    if (_qtyInput > 0) {
                        // transaksi in
                        var BolTransactIn = "FALSE";
                        var BolTransactOut = "FALSE";
                        var formdata = new FormData();
                        formdata.append("ProcessType", "CHECK EXISTING TLI TANPA STOCK");
                        formdata.append("PartNo", InputPartNo);
                        formdata.append("PartDesc", InputPartDesc);
                        formdata.append("TransactionDate", _transactionDate);
                        formdata.append("QtyInput", _qtyInput);
                        formdata.append("UOMConv", _UOMConv);
                        formdata.append("UnitCost", _unitCost);
                        formdata.append("EmployeeName", InputName);
                        formdata.append("CategoryMaterial", InputCategory);
                        formdata.append("MaterialStockOwner", _materialStockOwner);
                        formdata.append("TotalCost", _totalCost);
                        formdata.append("TransactID", _transactID);
                        formdata.append("WOChildExpense", InputExpense);
                        formdata.append("StockDivision", _stockDivision);
                        formdata.append("LabelIDIssuedOutTBZ", InputLabelID);
                        formdata.append("SequenceID", InputIDTBZ);
                        formdata.append("WOMappingID", InputWOMappingID);
                        formdata.append("CompanyWH", CompanyWH);
                        formdata.append("InputStockType", InputStockType);
                        $.ajax({
                            url: 'project/Inventory/src/srcAddDataOutPartTBZ.php',
                            dataType: 'text',
                            cache: false,
                            contentType: false,
                            processData: false,
                            data: formdata,
                            type: 'post',
                            async: false,
                            beforeSend: function () {
                            },
                            success: function (xaxa) {
                                BolTransactIn = xaxa;
                            },
                            error: function () {
                                alert("Request cannot proceed!");
                            }
                        });
                        if (BolTransactIn == "TRUE") {
                            var timeAct8 = GET_TIME_NOW();
                            var TextLog8 = $("#TextInfoLog").val();
                            var NewTextLog8 = TextLog8 + "[" + timeAct8 + "] BC Label Issue : " + InputLabelID + " >> POS Adj Success.\n";
                            $("#TextInfoLog").val(NewTextLog8);
                            $("#TextInfoLog").focus();
                            //transaksi out
                            var formdata = new FormData();
                            formdata.append("ProcessType", "CHECK EXISTING TLI TANPA STOCK OUT");
                            formdata.append("PartNo", InputPartNo);
                            formdata.append("PartDesc", InputPartDesc);
                            formdata.append("TransactionDate", _transactionDate);
                            formdata.append("QtyInput", _qtyInput);
                            formdata.append("UOMConv", _UOMConv);
                            formdata.append("UnitCost", _unitCost);
                            formdata.append("EmployeeNIK", InputNIK);
                            formdata.append("EmployeeName", InputName);
                            formdata.append("CategoryMaterial", InputCategory);
                            formdata.append("MaterialStockOwner", _materialStockOwner);
                            formdata.append("UnitCost", _unitCost);
                            formdata.append("TotalCost", _totalCost);
                            formdata.append("TransactID", _transactID);
                            formdata.append("WOChildExpense", InputExpense);
                            formdata.append("StockDivision", _stockDivision);
                            formdata.append("LabelIDIssuedOutTBZ", InputLabelID);
                            formdata.append("SequenceID", InputIDTBZ);
                            formdata.append("WOMappingID", InputWOMappingID);
                            formdata.append("CompanyWH", CompanyWH);
                            formdata.append("InputJobID", InputJobID);
                            formdata.append("InputStockType", InputStockType);
							formdata.append("Product", InputProduct);
                            $.ajax({
                                url: 'project/Inventory/src/srcAddDataOutPartTBZ.php',
                                dataType: 'text',
                                cache: false,
                                contentType: false,
                                processData: false,
                                data: formdata,
                                type: 'post',
                                async: false,
                                beforeSend: function () {
                                },
                                success: function (xaxa) {
                                    BolTransactOut = xaxa;
                                },
                                error: function () {
                                    alert("Request cannot proceed!");
                                }
                            });
                            if (BolTransactIn == "TRUE") {
                                var timeAct9 = GET_TIME_NOW();
                                var TextLog9 = $("#TextInfoLog").val();
                                var NewTextLog9 = TextLog9 + "[" + timeAct9 + "] BC Label Issue : " + InputLabelID + " >> NEG Adj Success.\n";
                                $("#TextInfoLog").val(NewTextLog9);
                                $("#TextInfoLog").focus();
                                RESET_FORM();
                                $("#LabelScan").text("Scan BC Label Item");
                                $("#InputScan").focus();

                            }
                            else {
                                var timeAct8 = GET_TIME_NOW();
                                var TextLog8 = $("#TextInfoLog").val();
                                var NewTextLog8 = TextLog8 + "[" + timeAct8 + "] BC Label Issue : " + InputLabelID + " >> NEG Adj Failed.\n";
                                $("#TextInfoLog").val(NewTextLog8);
                                $("#TextInfoLog").focus();
                                RESET_FORM();
                                $("#LabelScan").text("Scan BC Label Item");
                                $("#InputScan").focus();
                            }
                        }
                        else {
                            var timeAct7 = GET_TIME_NOW();
                            var TextLog7 = $("#TextInfoLog").val();
                            var NewTextLog7 = TextLog7 + "[" + timeAct7 + "] BC Label Issue : " + InputLabelID + " >> POS Adj Failed.\n";
                            $("#TextInfoLog").val(NewTextLog7);
                            $("#TextInfoLog").focus();
                            RESET_FORM();
                            $("#LabelScan").text("Scan BC Label Item");
                            $("#InputScan").focus();
                        }
                    }
                    else {
                        var timeAct7 = GET_TIME_NOW();
                        var TextLog7 = $("#TextInfoLog").val();
                        var NewTextLog7 = TextLog7 + "[" + timeAct7 + "] [ERROR] : Jumlah qty masih kosong.\n";
                        $("#TextInfoLog").val(NewTextLog7);
                        $("#TextInfoLog").focus();
                        RESET_FORM();
                        $("#LabelScan").text("Scan BC Label Item");
                        $("#InputScan").focus();
                        return false;
                    }
                }
            }
            if (InputStockType == "Tanpa Stock RM/Tools") {
                // bila transaksi tidak mempengaruhi jumlah stock(maka langsung proses IN untuk raw material dan tools)
                var timeAct5 = GET_TIME_NOW();
                var TextLog5 = $("#TextInfoLog").val();
                var NewTextLog5 = TextLog5 + "[" + timeAct5 + "] Status Stock : Tanpa Stock\n";
                $("#TextInfoLog").val(NewTextLog5);
                $("#TextInfoLog").focus();

                var _getTotalDuplicateSequenceID = 0;
                var formdata = new FormData();
                formdata.append("ProcessType", "CHECK EXISTING TLI");
                formdata.append("SequenceID", InputIDTBZ);
                $.ajax({
                    url: 'project/Inventory/src/srcAddDataOutPartTBZ.php',
                    dataType: 'text',
                    cache: false,
                    contentType: false,
                    processData: false,
                    data: formdata,
                    type: 'post',
                    async: false,
                    beforeSend: function () {
                    },
                    success: function (xaxa) {
                        _getTotalDuplicateSequenceID = xaxa;
                    },
                    error: function () {
                        alert("Request cannot proceed!");
                    }
                });
                if (parseInt(_getTotalDuplicateSequenceID) > 0)
                {
                    var timeAct6 = GET_TIME_NOW();
                    var TextLog6 = $("#TextInfoLog").val();
                    var NewTextLog6 = TextLog6 + "[" + timeAct6 + "] BC Label Issue : " + InputLabelID + " >> already exist. Process canceled.\n";
                    $("#TextInfoLog").val(NewTextLog6);
                    $("#TextInfoLog").focus();
                    RESET_FORM();
                    $("#LabelScan").text("Scan BC Label Item");
                    $("#InputScan").focus();
                }
                else {
                    if (_qtyInput > 0) {
                        // transaksi in
                        var BolTransactIn = "FALSE";
                        var formdata = new FormData();
                        formdata.append("ProcessType", "CHECK EXISTING TLI TANPA STOCK");
                        formdata.append("PartNo", InputPartNo);
                        formdata.append("PartDesc", InputPartDesc);
                        formdata.append("TransactionDate", _transactionDate);
                        formdata.append("QtyInput", _qtyInput);
                        formdata.append("UOMConv", _UOMConv);
                        formdata.append("UnitCost", _unitCost);
                        formdata.append("EmployeeName", InputName);
                        formdata.append("CategoryMaterial", InputCategory);
                        formdata.append("MaterialStockOwner", _materialStockOwner);
                        formdata.append("TotalCost", _totalCost);
                        formdata.append("TransactID", _transactID);
                        formdata.append("WOChildExpense", InputExpense);
                        formdata.append("StockDivision", _stockDivision);
                        formdata.append("LabelIDIssuedOutTBZ", InputLabelID);
                        formdata.append("SequenceID", InputIDTBZ);
                        formdata.append("WOMappingID", InputWOMappingID);
                        formdata.append("CompanyWH", CompanyWH);
                        formdata.append("InputStockType", InputStockType);
                        $.ajax({
                            url: 'project/Inventory/src/srcAddDataOutPartTBZ.php',
                            dataType: 'text',
                            cache: false,
                            contentType: false,
                            processData: false,
                            data: formdata,
                            type: 'post',
                            async: false,
                            beforeSend: function () {
                            },
                            success: function (xaxa) {
                                BolTransactIn = xaxa;
                            },
                            error: function () {
                                alert("Request cannot proceed!");
                            }
                        });
                        if (BolTransactIn == "TRUE") {
                            var timeAct8 = GET_TIME_NOW();
                            var TextLog8 = $("#TextInfoLog").val();
                            var NewTextLog8 = TextLog8 + "[" + timeAct8 + "] BC Label Issue : " + InputLabelID + " >> POS Adj Success.\n";
                            $("#TextInfoLog").val(NewTextLog8);
                            $("#TextInfoLog").focus();
                        }
                        else {
                            var timeAct7 = GET_TIME_NOW();
                            var TextLog7 = $("#TextInfoLog").val();
                            var NewTextLog7 = TextLog7 + "[" + timeAct7 + "] BC Label Issue : " + InputLabelID + " >> POS Adj Failed.\n";
                            $("#TextInfoLog").val(NewTextLog7);
                            $("#TextInfoLog").focus();
                            RESET_FORM();
                            $("#LabelScan").text("Scan BC Label Item");
                            $("#InputScan").focus();
                        }
                    }
                    else {
                        var timeAct7 = GET_TIME_NOW();
                        var TextLog7 = $("#TextInfoLog").val();
                        var NewTextLog7 = TextLog7 + "[" + timeAct7 + "] [ERROR] : Jumlah qty masih kosong.\n";
                        $("#TextInfoLog").val(NewTextLog7);
                        $("#TextInfoLog").focus();
                        RESET_FORM();
                        $("#LabelScan").text("Scan BC Label Item");
                        $("#InputScan").focus();
                        return false;
                    }
                }
            }
            if (InputStockType == "Masuk Stock") {
                var timeAct5 = GET_TIME_NOW();
                var TextLog5 = $("#TextInfoLog").val();
                var NewTextLog5 = TextLog5 + "[" + timeAct5 + "] Status Stock : Masuk Stock\n";
                $("#TextInfoLog").val(NewTextLog5);
                $("#TextInfoLog").focus();

                var _getTotalDuplicateSequenceID = 0;
                var formdata = new FormData();
                formdata.append("ProcessType", "CHECK EXISTING TLI");
                formdata.append("SequenceID", InputIDTBZ);
                $.ajax({
                    url: 'project/Inventory/src/srcAddDataOutPartTBZ.php',
                    dataType: 'text',
                    cache: false,
                    contentType: false,
                    processData: false,
                    data: formdata,
                    type: 'post',
                    async: false,
                    beforeSend: function () {
                    },
                    success: function (xaxa) {
                        _getTotalDuplicateSequenceID = xaxa;
                    },
                    error: function () {
                        alert("Request cannot proceed!");
                    }
                });

                // bila transaksi mempengaruhi jumlah stock gudang kecil
                var BolCheckPartStockList = "FALSE";
                var formdata = new FormData();
                formdata.append("ProcessType", "CHECK STOCK LIST PN");
                formdata.append("PartNo", InputPartNo);
                formdata.append("DivisionWH", InputGudang);
                formdata.append("Company", CompanyWH);
                $.ajax({
                    url: 'project/Inventory/src/srcAddDataOutPartTBZ.php',
                    dataType: 'text',
                    cache: false,
                    contentType: false,
                    processData: false,
                    data: formdata,
                    type: 'post',
                    async: false,
                    beforeSend: function () {
                    },
                    success: function (xaxa) {
                        BolCheckPartStockList = xaxa;
                    },
                    error: function () {
                        alert("Request cannot proceed!");
                    }
                });
                if (BolCheckPartStockList == "TRUE") {
                    if ((_qtyInput) != "0") {
                        var formdata = new FormData();
                        formdata.append("ProcessType", "CHECK STOCK QTY");
                        formdata.append("PartNo", InputPartNo);
                        formdata.append("StockDivision", _stockDivision);
                        formdata.append("CompanyWH", CompanyWH);
                        $.ajax({
                            url: 'project/Inventory/src/srcAddDataOutPartTBZ.php',
                            dataType: 'text',
                            cache: false,
                            contentType: false,
                            processData: false,
                            data: formdata,
                            type: 'post',
                            async: false,
                            beforeSend: function () {
                            },
                            success: function (xaxa) {
                                _stockLama = xaxa;
                            },
                            error: function () {
                                alert("Request cannot proceed!");
                            }
                        });
                        _qtyReceived = parseFloat(_stockLama) + parseFloat(_qtyInput);
                        // update stock
                        var BolUpdateStock = "FALSE";
                        var formdata = new FormData();
                        formdata.append("ProcessType", "EDIT STOCK QTY");
                        formdata.append("PartNo", InputPartNo);
                        formdata.append("QtyInput", _qtyReceived);
                        formdata.append("StockDivision", _stockDivision);
                        formdata.append("CompanyWH", CompanyWH);
                        formdata.append("OldQty", _stockLama);
                        $.ajax({
                            url: 'project/Inventory/src/srcAddDataOutPartTBZ.php',
                            dataType: 'text',
                            cache: false,
                            contentType: false,
                            processData: false,
                            data: formdata,
                            type: 'post',
                            async: false,
                            beforeSend: function () {
                            },
                            success: function (xaxa) {
                                BolUpdateStock = xaxa;
                            },
                            error: function () {
                                alert("Request cannot proceed!");
                            }
                        });
                        if (BolUpdateStock == "TRUE") {
                            // input transaksi in
                            var BolAddStock = "FALSE";
                            var formdata = new FormData();
                            formdata.append("ProcessType", "TRANSACTION IN");
                            formdata.append("PartNo", InputPartNo);
                            formdata.append("PartDesc", InputPartDesc);
                            formdata.append("TransactionDate", _transactionDate);
                            formdata.append("QtyInput", _qtyInput);
                            formdata.append("UOMConv", _UOMConv);
                            formdata.append("UnitCost", _unitCost);
                            formdata.append("EmployeeName", InputName);
                            formdata.append("CategoryMaterial", InputCategory);
                            formdata.append("MaterialStockOwner", _materialStockOwner);
                            formdata.append("TotalCost", _totalCost);
                            formdata.append("TransactID", _transactID);
                            formdata.append("WOChildExpense", InputExpense);
                            formdata.append("StockDivision", _stockDivision);
                            formdata.append("LabelIDIssuedOutTBZ", InputLabelID);
                            formdata.append("SequenceID", InputIDTBZ);
                            formdata.append("WOMappingID", InputWOMappingID);
                            formdata.append("CompanyWH", CompanyWH);
                            formdata.append("InputStockType", InputStockType);
                            $.ajax({
                                url: 'project/Inventory/src/srcAddDataOutPartTBZ.php',
                                dataType: 'text',
                                cache: false,
                                contentType: false,
                                processData: false,
                                data: formdata,
                                type: 'post',
                                async: false,
                                beforeSend: function () {
                                },
                                success: function (xaxa) {
                                    BolAddStock = xaxa;
                                },
                                error: function () {
                                    alert("Request cannot proceed!");
                                }
                            });
                            if (BolAddStock == "TRUE") {
                                var timeAct9 = GET_TIME_NOW();
                                var TextLog9 = $("#TextInfoLog").val();
                                var NewTextLog9 = TextLog9 + "[" + timeAct9 + "] BC Label Issue : " + InputLabelID + " >> POS Adj Success.\n";
                                $("#TextInfoLog").val(NewTextLog9);
                                $("#TextInfoLog").focus();
                                // simpan history stock
                                var BolHistoryUpdateStock = "FALSE";
                                var formdata = new FormData();
                                formdata.append("ProcessType", "ADD HISTORY UPDATE STOCK");
                                formdata.append("PartNo", InputPartNo);
                                formdata.append("QtyInput", _qtyInput);
                                formdata.append("StockDivision", _stockDivision);
                                formdata.append("TransactID", _transactID);
                                formdata.append("CompanyWH", CompanyWH);
                                $.ajax({
                                    url: 'project/Inventory/src/srcAddDataOutPartTBZ.php',
                                    dataType: 'text',
                                    cache: false,
                                    contentType: false,
                                    processData: false,
                                    data: formdata,
                                    type: 'post',
                                    async: false,
                                    beforeSend: function () {
                                    },
                                    success: function (xaxa) {
                                        BolHistoryUpdateStock = xaxa;
                                    },
                                    error: function () {
                                        alert("Request cannot proceed!");
                                    }
                                });
                                if (BolHistoryUpdateStock == "FALSE") {
                                    var timeAct10 = GET_TIME_NOW();
                                    var TextLog10 = $("#TextInfoLog").val();
                                    var NewTextLog10 = TextLog10 + "[" + timeAct10 + "] [ERROR] : History adjustment gagal direkam, stock gagal di update.\n";
                                    $("#TextInfoLog").val(NewTextLog10);
                                    $("#TextInfoLog").focus();
                                    RESET_FORM();
                                    $("#LabelScan").text("Scan BC Label Item");
                                    $("#InputScan").focus();
                                }
                                else {
                                    RESET_FORM();
                                    $("#LabelScan").text("Scan BC Label Item");
                                    $("#InputScan").focus();
                                }
                            }
                            else {
                                var timeAct8 = GET_TIME_NOW();
                                var TextLog8 = $("#TextInfoLog").val();
                                var NewTextLog8 = TextLog8 + "[" + timeAct8 + "] BC Label Issue : " + InputLabelID + " >> POS Adj Failed.\n";
                                $("#TextInfoLog").val(NewTextLog8);
                                $("#TextInfoLog").focus();
                                RESET_FORM();
                                $("#LabelScan").text("Scan BC Label Item");
                                $("#InputScan").focus();
                            }
                        }
                        else {
                            var timeAct7 = GET_TIME_NOW();
                            var TextLog7 = $("#TextInfoLog").val();
                            var NewTextLog7 = TextLog7 + "[" + timeAct7 + "] [ERROR] : Stock gagal di update.\n";
                            $("#TextInfoLog").val(NewTextLog7);
                            $("#TextInfoLog").focus();
                            RESET_FORM();
                            $("#LabelScan").text("Scan BC Label Item");
                            $("#InputScan").focus();
                        }
                    }
                    else {
                        var timeAct6 = GET_TIME_NOW();
                        var TextLog6 = $("#TextInfoLog").val();
                        var NewTextLog6 = TextLog6 + "[" + timeAct6 + "] [ERROR] : Qty stock kosong.\n";
                        $("#TextInfoLog").val(NewTextLog6);
                        $("#TextInfoLog").focus();
                        RESET_FORM();
                        $("#LabelScan").text("Scan BC Label Item");
                        $("#InputScan").focus();
                    }
                }
                else {  //bila partno tidak terdata dalam list stock
                    //proses simpan stock baru
                    var BolNewStockData = "FALSE";
                    var formdata = new FormData();
                    formdata.append("ProcessType", "NEW STOCK");
                    formdata.append("PartNo", InputPartNo);
                    formdata.append("PartDesc", InputPartDesc);
                    formdata.append("TransactionDate", _transactionDate);
                    formdata.append("QtyInput", _qtyInput);
                    formdata.append("UOMConv", _UOMConv);
                    formdata.append("UnitCost", _unitCost);
                    formdata.append("EmployeeName", InputName);
                    formdata.append("CategoryMaterial", InputCategory);
                    formdata.append("MaterialStockOwner", _materialStockOwner);
                    formdata.append("TotalCost", _totalCost);
                    formdata.append("TransactID", _transactID);
                    formdata.append("WOChildExpense", InputExpense);
                    formdata.append("StockDivision", _stockDivision);
                    formdata.append("LabelIDIssuedOutTBZ", InputLabelID);
                    formdata.append("SequenceID", InputIDTBZ);
                    formdata.append("WOMappingID", InputWOMappingID);
                    formdata.append("CompanyWH", CompanyWH);
                    formdata.append("InputStockType", InputStockType);
                    $.ajax({
                        url: 'project/Inventory/src/srcAddDataOutPartTBZ.php',
                        dataType: 'text',
                        cache: false,
                        contentType: false,
                        processData: false,
                        data: formdata,
                        type: 'post',
                        async: false,
                        beforeSend: function () {
                        },
                        success: function (xaxa) {
                            BolNewStockData = xaxa;
                        },
                        error: function () {
                            alert("Request cannot proceed!");
                        }
                    });
                    if (BolNewStockData == "TRUE") {
                        var timeAct6 = GET_TIME_NOW();
                        var TextLog6 = $("#TextInfoLog").val();
                        var NewTextLog6 = TextLog6 + "[" + timeAct6 + "] BC Label Issue : " + InputLabelID + " >> Update Stock Success.\n";
                        $("#TextInfoLog").val(NewTextLog6);
                        $("#TextInfoLog").focus();

                        if (_qtyInput > 0) {
                            _stockLama = 0;
                            _qtyReceived = 0;
                            var formdata = new FormData();
                            formdata.append("ProcessType", "CHECK STOCK QTY");
                            formdata.append("PartNo", InputPartNo);
                            formdata.append("StockDivision", _stockDivision);
                            formdata.append("CompanyWH", CompanyWH);
                            $.ajax({
                                url: 'project/Inventory/src/srcAddDataOutPartTBZ.php',
                                dataType: 'text',
                                cache: false,
                                contentType: false,
                                processData: false,
                                data: formdata,
                                type: 'post',
                                async: false,
                                beforeSend: function () {
                                },
                                success: function (xaxa) {
                                    _stockLama = xaxa;
                                },
                                error: function () {
                                    alert("Request cannot proceed!");
                                }
                            });
                            _qtyReceived = _stockLama + _qtyInput;
                            // update stok 
                            var BolUpdateStock = "FALSE";
                            var formdata = new FormData();
                            formdata.append("ProcessType", "EDIT STOCK QTY");
                            formdata.append("PartNo", InputPartNo);
                            formdata.append("QtyInput", _qtyInput);
                            formdata.append("StockDivision", _stockDivision);
                            formdata.append("CompanyWH", CompanyWH);
							formdata.append("OldQty", _stockLama);
                            $.ajax({
                                url: 'project/Inventory/src/srcAddDataOutPartTBZ.php',
                                dataType: 'text',
                                cache: false,
                                contentType: false,
                                processData: false,
                                data: formdata,
                                type: 'post',
                                async: false,
                                beforeSend: function () {
                                },
                                success: function (xaxa) {
                                    BolUpdateStock = xaxa;
                                },
                                error: function () {
                                    alert("Request cannot proceed!");
                                }
                            });
                            if (BolUpdateStock == "TRUE") {
                                // input transaksi in
                                var BolAddStock = "FALSE";
                                var formdata = new FormData();
                                formdata.append("ProcessType", "TRANSACTION IN");
                                formdata.append("PartNo", InputPartNo);
                                formdata.append("PartDesc", InputPartDesc);
                                formdata.append("TransactionDate", _transactionDate);
                                formdata.append("QtyInput", _qtyInput);
                                formdata.append("UOMConv", _UOMConv);
                                formdata.append("UnitCost", _unitCost);
                                formdata.append("EmployeeName", InputName);
                                formdata.append("CategoryMaterial", InputCategory);
                                formdata.append("MaterialStockOwner", _materialStockOwner);
                                formdata.append("TotalCost", _totalCost);
                                formdata.append("TransactID", _transactID);
                                formdata.append("WOChildExpense", InputExpense);
                                formdata.append("StockDivision", _stockDivision);
                                formdata.append("LabelIDIssuedOutTBZ", InputLabelID);
                                formdata.append("SequenceID", InputIDTBZ);
                                formdata.append("WOMappingID", InputWOMappingID);
                                formdata.append("CompanyWH", CompanyWH);
                                formdata.append("InputStockType", InputStockType);
                                $.ajax({
                                    url: 'project/Inventory/src/srcAddDataOutPartTBZ.php',
                                    dataType: 'text',
                                    cache: false,
                                    contentType: false,
                                    processData: false,
                                    data: formdata,
                                    type: 'post',
                                    async: false,
                                    beforeSend: function () {
                                    },
                                    success: function (xaxa) {
                                        BolAddStock = xaxa;
                                    },
                                    error: function () {
                                        alert("Request cannot proceed!");
                                    }
                                });
                                if (BolAddStock == "TRUE") {
                                    var timeAct11 = GET_TIME_NOW();
                                    var TextLog11 = $("#TextInfoLog").val();
                                    var NewTextLog11 = TextLog11 + "[" + timeAct11 + "] BC Label Issue : " + InputLabelID + " >> POS Adj Success.\n";
                                    $("#TextInfoLog").val(NewTextLog11);
                                    $("#TextInfoLog").focus();
                                    // simpan history stock
                                    var BolHistoryUpdateStock = "FALSE";
                                    var formdata = new FormData();
                                    formdata.append("ProcessType", "ADD HISTORY UPDATE STOCK");
                                    formdata.append("PartNo", InputPartNo);
                                    formdata.append("QtyInput", _qtyInput);
                                    formdata.append("StockDivision", _stockDivision);
                                    formdata.append("TransactID", _transactID);
                                    formdata.append("CompanyWH", CompanyWH);
                                    $.ajax({
                                        url: 'project/Inventory/src/srcAddDataOutPartTBZ.php',
                                        dataType: 'text',
                                        cache: false,
                                        contentType: false,
                                        processData: false,
                                        data: formdata,
                                        type: 'post',
                                        async: false,
                                        beforeSend: function () {
                                        },
                                        success: function (xaxa) {
                                            BolHistoryUpdateStock = xaxa;
                                        },
                                        error: function () {
                                            alert("Request cannot proceed!");
                                        }
                                    });
                                    if (BolHistoryUpdateStock == "FALSE") {
                                        var timeAct11 = GET_TIME_NOW();
                                        var TextLog11 = $("#TextInfoLog").val();
                                        var NewTextLog11 = TextLog11 + "[" + timeAct11 + "] [ERROR] : History adjustment gagal direkam, stock gagal di update.\n";
                                        $("#TextInfoLog").val(NewTextLog11);
                                        $("#TextInfoLog").focus();
                                        RESET_FORM();
                                        $("#LabelScan").text("Scan BC Label Item");
                                        $("#InputScan").focus();
                                    }
                                    else {
                                        RESET_FORM();
                                        $("#LabelScan").text("Scan BC Label Item");
                                        $("#InputScan").focus();
                                    }
                                }
                                else {
                                    var timeAct10 = GET_TIME_NOW();
                                    var TextLog10 = $("#TextInfoLog").val();
                                    var NewTextLog10 = TextLog10 + "[" + timeAct10 + "] BC Label Issue : " + InputLabelID + " >> POS Adj Failed.\n";
                                    $("#TextInfoLog").val(NewTextLog10);
                                    $("#TextInfoLog").focus();
                                    RESET_FORM();
                                    $("#LabelScan").text("Scan BC Label Item");
                                    $("#InputScan").focus();
                                }
                            }
                            else {
                                var timeAct9 = GET_TIME_NOW();
                                var TextLog9 = $("#TextInfoLog").val();
                                var NewTextLog9 = TextLog9 + "[" + timeAct9 + "] [ERROR] : Stock gagal di update.\n";
                                $("#TextInfoLog").val(NewTextLog9);
                                $("#TextInfoLog").focus();
                                RESET_FORM();
                                $("#LabelScan").text("Scan BC Label Item");
                                $("#InputScan").focus();
                            }
                        }
                        else {
                            var timeAct8 = GET_TIME_NOW();
                            var TextLog8 = $("#TextInfoLog").val();
                            var NewTextLog8 = TextLog8 + "[" + timeAct8 + "] [ERROR] : Jumlah qty masih kosong.\n";
                            $("#TextInfoLog").val(NewTextLog8);
                            $("#TextInfoLog").focus();
                            RESET_FORM();
                            $("#LabelScan").text("Scan BC Label Item");
                            $("#InputScan").focus();
                            return false;
                        }
                    }
                    else {
                        var timeAct7 = GET_TIME_NOW();
                        var TextLog7 = $("#TextInfoLog").val();
                        var NewTextLog7 = TextLog7 + "[" + timeAct7 + "] BC Label Issue : " + InputLabelID + " >> Update Stock Failed.\n";
                        $("#TextInfoLog").val(NewTextLog7);
                        $("#TextInfoLog").focus();
                        RESET_FORM();
                        $("#LabelScan").text("Scan BC Label Item");
                        $("#InputScan").focus();
                    }
                }
            }
        }, 500);

        setTimeout(function () {
            $("#ModalConfirmation").modal("hide");
        }, 1000);
        setTimeout(function () {
            $("#BtnSubmit").attr('disabled', false);
        }, 1000);

        $("#InputScan").focus();
    });

    $("#InputCompany").focus();

})
function VIEW_HISTORY() {
    var Location = $("#FilterLocation option:selected").val();
    var DateStart = $("#txtFilterDateIN1").val().trim();
    var DateEnd = $("#txtFilterDateOUT1").val().trim();
    var Category = $("#FilterOpt option:selected").val();
    var formdata = new FormData();
    formdata.append("ValLocation", Location);
    formdata.append("ValDateStart", DateStart);
    formdata.append("ValDateEnd", DateEnd);
    formdata.append("ValCategory", Category);
    $.ajax({
        url: "project/Inventory/ContentViewDataInOutPartTBZPerLabel.php",
        dataType: "text",
        cache: false,
        contentType: false,
        processData: false,
        data: formdata,
        type: "post",
        beforeSend: function () {
            $("#BtnHistory").attr("disabled", true);
            $("#ContentTableInOut").html("");
            $("#ContentTableInOut").append('<div class="col-sm-12 pt-2 pb-2 d-flex justify-content-center" id="ContentLoading1"><div class="spinner-border text-secondary" role="status"><span class="visually-hidden" id="LoadingProcess1">Loading...</span ></div></div>');
        },
        success: function (xaxa) {
            $("#ContentLoading1").remove();
            $("#ContentTableInOut").html(xaxa);
            $("#BtnHistory").attr("disabled", false);
            var HeaderRes = $(".header-result").text();
            var HeaderResSplit = HeaderRes.split("[");
            var Location = HeaderResSplit[1].replace("Lokasi : ", "");
            Location = Location.replace("]", "");
            var TimeLog = HeaderResSplit[2].replace("]", "");
            TimeLog = TimeLog.replace(" - ", "_");
            var Category = HeaderResSplit[3].replace("]", "");
            Category = Category.replace("Kategori : ", "");
            var NewFileName = "DataProsesInOutPartTBZ_" + Location + "_" + Category + "_" + TimeLog;
            $("#TableTBZ").DataTable({
                "pagingType": "full",
                "scrollX": true,
                destroy: true,
                buttons: [
                    {
                        extend: 'csv',
                        filename: NewFileName
                    }
                ]
            });
            $(".dataTables_wrapper > .dataTables_filter input").css("margin-bottom", "10px");
            $("#TableTBZ").css("font-size", "13px");
            $("#TableTBZ th, td").css({
                "word-wrap": "break-word"
            });
            $("#BtnDownloadCSV").click(function () {
                $("#BtnDownloadCSV").attr("disabled", true);
                setTimeout(function () {
                    $("#TableTBZ").DataTable().button('.buttons-csv').trigger();
                }, 1000);
                setTimeout(function () {
                    $("#BtnDownloadCSV").attr("disabled", false);
                }, 3000);
            });
        },
        error: function () {
            alert("Request cannot proceed!");
            $("#ContentLoading1").remove();
            $("#ContentTableInOut").html("");
            $("#BtnHistory").blur();
            $("#BtnHistory").attr("disabled", false);
        }
    });
}
function RESET_FORM() {
    $("#InputScan").val("");
    $("#InfoStockType").attr("disabled", true);
    $("#BtnStockType").attr("disabled", true);
    $("#InputScan").attr("disabled", false);
    $("#InfoScan").html("");
    $("#InfoLocation").val("");
    $("#InfoLabelID").val("");
    $("#InfoIDLineItemTBZ").val("");
    $("#InfoPartNo").val("");
    $("#InfoCategory").val("");
    $("#InfoPartDesc").val("");
    $("#InfoWOTBZ").val("");
    $("#InfoJobID").val("");
    $("#InfoWOC").val("");
    $("#InfoWOID").val("");
    $("#InfoProduct").val("");
    $("#InfoExpense").val("");
    $("#InfoQty").val("");
    $("#InfoUOM").val("");
    $("#InfoUnitCost").val("");
    $("#InfoStockType").val("");
    $("#InfoStockTypeDefault").val("");
    $("#InfoDivisionWH").val("");
    $("#BtnScan").html("Scan");
    $("#LabelScan").html("Scan BC Label Item");
    $("#InputScan").get(0).focus();
}
function GET_TIME_NOW() {
    var dt = new Date();
    var time = (("0" + (dt.getMonth() + 1)).slice(-2)) + "/" + (("0" + (dt.getDate())).slice(-2)) + "/" + dt.getFullYear() + " " + (("0" + (dt.getHours())).slice(-2)) + ":" + (("0" + (dt.getMinutes())).slice(-2)) + ":" + (("0" + (dt.getSeconds())).slice(-2));
    return time;
}
function GET_GROUP_NO() {
    var dt = new Date();
    var time = dt.getFullYear() + "" + (("0" + (dt.getMonth() + 1)).slice(-2)) + "" + (("0" + (dt.getDate())).slice(-2)) + "-" + (("0" + (dt.getHours())).slice(-2)) + "" + (("0" + (dt.getMinutes())).slice(-2)) + "" + (("0" + (dt.getSeconds())).slice(-2)) + "." + (("0" + (dt.getMilliseconds())).slice(-3));
    return time;
}
function SCAN_LABEL() {
    // check lokasi
    if ($("#InputCompany").val().trim() == "U1dRNk1BPT0=") {
        $("#InputCompany").focus();
        return false;
    }
    // check content input
    if ($("#InputScan").val().trim() == "") {
        $("#InputScan").focus();
        return false;
    }

    var ValScan = $("#InputScan").val().trim().toUpperCase();
    var ValCompanyID = $("#InputCompany option:selected").text();
    var LabelScanText = $("#LabelScan").html();

    if (LabelScanText == "Scan BC Label Item") {
        if (ValScan == "RESET") {
            RESET_FORM();
            $("#InputScan").focus();
        }
        else {
            $("#InputLocation").attr("disabled", true);
            $("#InfoLocation").val(ValCompanyID);
            const timeAct = GET_TIME_NOW();
            var TextLog = $("#TextInfoLog").val();
            var NewTextLog = TextLog + "[" + timeAct + "] Input Scan : " + $("#InputScan").val().trim() + "\n";
            $("#TextInfoLog").val(NewTextLog);
            $("#TextInfoLog").blur();
            $("#InfoScan").html("");
            $("#InputScan").val("");
            $("#InputScan").attr("disabled", true);
            //pengecekan kolom label id
            if ($("#InfoLabelID").val() == "") {
                var ResCheckInput = CHECK_ID_LABEL_STOCK(ValScan, ValCompanyID);
                if (ResCheckInput == "TRUE") {
                    if ($("#InfoStockTypeDefault").val().trim() == "")
                    {
                        $("#LabelScan").text("Scan Jenis Stock");
                        $("#BtnScan").html("Cari");
                        $("#InputScan").val("");
                        $("#InputScan").attr("disabled", false);
                        $("#InputScan").get(0).focus();
                    }
                    else
                    {
                        $("#InfoStockType").val($("#InfoStockTypeDefault").val().trim());
                        var StockDef = $("#InfoStockTypeDefault").val().trim();
                        switch (StockDef) {
                            case "Masuk Stock":
                            case "Tanpa Stock RM/Tools":
                                $("#LabelScan").text("Pilih Gudang Kecil");
                                $("#InputScan").attr('disabled', true);
                                $("#InputScan").val("");
                                $("#BtnScan").html("Cari");
                                $("#BtnScan").get(0).focus();
                                break;
                            default:
                                $("#InputScan").attr("disabled", false);
                                $("#InputScan").val("");
                                $("#LabelScan").text("Scan Barcode WO ID");
                                $("#InputScan").get(0).focus();
                                break;
                        }
                    }
                }
                else
                {
                    $("#InputScan").attr("disabled", false);
                    $("#InputScan").get(0).focus();
                }
            }
        }
    }

    if (LabelScanText == "Scan Jenis Stock") {
        if (ValScan == "RESET") {
            RESET_FORM();
            $("#LabelScan").text("Scan BC Label Item");
            $("#InputScan").focus();
        }
        else {
            if ($("#BtnScan").html() == "Cari") {
                ValScan = ValScan.toUpperCase();
                switch (ValScan) {
                    case "MS":
                        $("#InfoStockType").val("Masuk Stock");
                        $("#LabelScan").text("Pilih Gudang Kecil");
                        $("#InputScan").attr('disabled', true);
                        $("#InputScan").val("");
                        $("#BtnScan").html("Cari");
                        $("#BtnScan").get(0).focus();
                        break;
                    case "MASUK STOCK":
                        $("#InfoStockType").val("Masuk Stock");
                        $("#LabelScan").text("Pilih Gudang Kecil");
                        $("#InputScan").attr('disabled', true);
                        $("#InputScan").val("");
                        $("#BtnScan").html("Cari");
                        $("#BtnScan").get(0).focus();
                        break;
                    case "TS":
                        $("#InfoStockType").val("Tanpa Stock");
                        $("#LabelScan").text("Scan Barcode WO ID");
                        $("#InputScan").val("");
                        $("#InputScan").get(0).focus();
                        break;
                    case "TANPA STOCK":
                        $("#InfoStockType").val("Tanpa Stock");
                        $("#LabelScan").text("Scan Barcode WO ID");
                        $("#InputScan").val("");
                        $("#InputScan").get(0).focus();
                        break;
                    case "TS-IN":
                        $("#InfoStockType").val("Tanpa Stock RM/Tools");
                        $("#LabelScan").text("Pilih Gudang Kecil");
                        $("#InputScan").attr('disabled', true);
                        $("#InputScan").val("");
                        $("#BtnScan").html("Cari");
                        $("#BtnScan").get(0).focus();
                        break;
                    default:
                        var timeAct = GET_TIME_NOW();
                        var TextLog = $("#TextInfoLog").val();
                        var NewTextLog = TextLog + "[" + timeAct + "] [ERROR] : Label tidak ditemukan! Harap mencoba lagi\n";
                        $("#TextInfoLog").val(NewTextLog);
                        $("#TextInfoLog").blur();
                        $("#InputScan").val("");
                        $("#InputScan").focus();
                        break;
                }
            }
        }
    }
    if (LabelScanText == "Scan Barcode WO ID") {
        if (ValScan == "RESET") {
            RESET_FORM();
            $("#LabelScan").text("Scan BC Label Item");
            $("#InputScan").focus();
        }
        else {
            var InputWOID = $("#InputScan").val().trim();
            var formdata = new FormData();
            formdata.append("InputWOID", InputWOID);
            $.ajax({
                url: 'project/Inventory/ContentFindWOOpen.php',
                dataType: 'text',
                cache: false,
                contentType: false,
                processData: false,
                async: false,
                data: formdata,
                type: 'post',
                beforeSend: function () {
                    $("#InputScan").attr('disabled', true);
                },
                success: function (xaxa) {
                    var Res = xaxa;
                    var IDRes = Res.split("#");
                    switch (IDRes[0]) {
                        case "0":
                            $("#InputScan").attr('disabled', false);
                            $("#InputScan").val("");
                            $("#InputScan").focus();
                            var timeAct = GET_TIME_NOW();
                            var TextLog = $("#TextInfoLog").val();
                            var NewTextLog = TextLog + "[" + timeAct + "] [ERROR] : WO tidak ditemukan!\n";
                            $("#TextInfoLog").val(NewTextLog);
                            $("#TextInfoLog").blur();
                            break;
                        case "1":
                            $("#InputScan").attr('disabled', false);
                            $("#InputScan").val("");
                            $("#InputScan").focus();
                            var timeAct = GET_TIME_NOW();
                            var TextLog = $("#TextInfoLog").val();
                            var NewTextLog = TextLog + "[" + timeAct + "] [ERROR] : Status WO sudah di closed!\n";
                            $("#TextInfoLog").val(NewTextLog);
                            $("#TextInfoLog").blur();
                            break;
                        case "2":
                            var ResultWO = IDRes[1];
                            var ArrResult = ResultWO.split("&&");
                            $("#InfoWOC").val(ArrResult[0]);
                            $("#InfoProduct").val(ArrResult[1]);
                            $("#InfoExpense").val(ArrResult[2]);
                            $("#InfoWOID").val(ArrResult[3]);
                            $("#InputScan").attr('disabled', false);
                            $("#InputScan").val("");
                            if ($("#InfoStockType").val().trim() == "Masuk Stock")
                            {
                                $("#LabelScan").text("Pilih Gudang Kecil");
                                $("#InputScan").attr('disabled', true);
                                $("#InputScan").val("");
                                $("#BtnScan").html("Cari");
                                $("#BtnScan").get(0).focus();
                            }
                            else
                            {
                                $("#InfoDivisionWH").val(ArrResult[2]);
                                $("#BtnScan").html("Submit");
                                $("#InputScan").attr('disabled', true);
                                $("#BtnScan").get(0).focus();
                                CHECK_SUBMIT();
                            }
                            break;
                        default:
                            $("#InputScan").attr('disabled', false);
                            $("#InputScan").val("");
                            $("#InputScan").focus();
                            var timeAct = GET_TIME_NOW();
                            var TextLog = $("#TextInfoLog").val();
                            var NewTextLog = TextLog + "[" + timeAct + "] [ERROR] : WO tidak ditemukan!\n";
                            $("#TextInfoLog").val(NewTextLog);
                            $("#TextInfoLog").blur();
                            break;
                    }
                },
                error: function () {
                    alert("Request cannot proceed!");
                    $("#InputScan").attr('disabled', false);
                }
            });
        }
    }
}
function CHECK_ID_LABEL_STOCK(InputID, InputLocation) {
    var BolRes = "FALSE";
    var ValID = InputID.trim();
    var ValLocation = InputLocation.trim();
    var formdata = new FormData();
    formdata.append("ValID", ValID);
    formdata.append("ValLocation", ValLocation);
    alert(ValID+'_'+ValLocation);
    $.ajax({
        url: "project/Inventory/src/srcCheckRegisteredInventoryIssuedOutLabel.php",
        dataType: "text",
        cache: false,
        contentType: false,
        processData: false,
        async: false,
        data: formdata,
        type: "post",
        beforeSend: function () {
            $("#InfoScan").html("");
            $("#InfoScan").append('<div class="col-sm-12 pt-2 pb-2 d-flex justify-content-center" id="ContentLoading2"><div class="spinner-border text-secondary" role="status"><span class="visually-hidden" id="LoadingProcess2">Loading...</span></div></div>');
        },
        success: function (xaxa) {
            var results = xaxa;
            $("#ContentLoading2").remove();
            $("#test").show(xaxa);
            // results = results.split(" >> ");
            // switch (results[0]) {
            //     case "FALSE":
            //         if (results[1] == "0")
            //         {
            //             var timeFalse = GET_TIME_NOW();
            //             var TextLog = $("#TextInfoLog").val();
            //             var NewTextLog = TextLog + "[" + timeFalse + "] [ERROR] - Data [" + ValID + "] tidak ditemukan!\n";
            //             $("#TextInfoLog").val(NewTextLog);
            //             $("#InputScan").focus();
            //             BolRes = "FALSE";
            //         }
            //         if (results[1] == "1")
            //         {
            //             var timeFalse = GET_TIME_NOW();
            //             var TextLog = $("#TextInfoLog").val();
            //             var NewTextLog = TextLog + "[" + timeFalse + "] [ERROR] - Label ID [" + ValID + "] sudah pernah digunakan sebelumnya!\n";
            //             $("#TextInfoLog").val(NewTextLog);
            //             $("#InputScan").focus();
            //             BolRes = "FALSE";
            //             RESET_FORM();
            //         }
            //         break;
            //     case "TRUE":
            //         var obj = jQuery.parseJSON(results[1]);
            //         var ValIdx = obj['Idx'];
            //         var ValExpenseWH = obj['ExpenseWH'];
            //         var ValCompanyID = obj['CompanyID'];
            //         var ValPartNo = obj['PartNo'];
            //         var ValQty = obj['Qty'];
            //         var ValUOM = obj['UOM'];
            //         var ValWOID = obj['WOID'];
            //         var ValJobID = obj['JobID'];
            //         var ValSequenceID = obj['SequenceID'];
            //         var ValStockType = obj['StockType'];
            //         var CategoryPN = obj['CategoryPN'];
            //         var PNDesc = obj['PNDesc'];
            //         var UnitCost = obj['UnitCost'];
            //         var WOChild = obj['WOChild'];
            //         var Product = obj['Product'];
            //         var Expense = obj['Expense'];
            //         var ClosedTime = obj['ClosedTime'];
            //         var JenisStock = obj['JenisStock'];
            //         var WOID = obj['WOIdx'];
            //         $("#InfoLabelID").val(ValIdx);
            //         $("#InfoIDLineItemTBZ").val(ValSequenceID);
            //         $("#InfoPartNo").val(ValPartNo);
            //         $("#InfoCategory").val(CategoryPN);
            //         $("#InfoPartDesc").val(PNDesc);
            //         $("#InfoWOTBZ").val(ValWOID);
            //         $("#InfoJobID").val(ValJobID);
            //         $("#InfoQty").val(ValQty);
            //         $("#InfoUOM").val(ValUOM);
            //         $("#InfoUnitCost").val(UnitCost);
            //         $("#InfoStockTypeDefault").val(JenisStock);
            //         BolRes = "TRUE";
            //         break;
            //     default:
            //         $("#InputScan").attr("disabled", false);
            //         var timeErr = GET_TIME_NOW();
            //         var TextLog = $("#TextInfoLog").val();
            //         var NewTextLog = TextLog + "[" + timeErr + "] [ERROR] - Sistem error, mohon diulangi lagi!\n";
            //         $("#TextInfoLog").val(NewTextLog);
            //         RESET_FORM();
            //         $("#InputScan").focus();
            //         BolRes = "FALSE";
            //         break;
            // }
        },
        error: function () {
            alert("Request cannot proceed!");
            $("#InfoScan").html("");
            $("#InputScan").val("");
            BolRes = "FALSE";
        }
    });
    return BolRes;
}
function FIND_WO() {
    var Category = $("#FilterTypeCategoryFindWO option:selected").val().trim();
    var Keywords = $("#FilterInputKeywordWO").val().trim();
    if (Keywords == "") {
        $("#FilterInputKeywordWO").focus();
        return false;
    }
    var formdata = new FormData();
    formdata.append("Category", Category);
    formdata.append("Keywords", Keywords);
    $.ajax({
        url: 'project/Inventory/ModalFindWO.php',
        dataType: 'text',
        cache: false,
        contentType: false,
        processData: false,
        async: false,
        data: formdata,
        type: 'post',
        beforeSend: function () {
            $("#BtnSearchWO").attr('disabled', true);
            $("#ContentModalWO").html("");
            $("#ContentModalWO").append('<div class="col-sm-12 pt-2 pb-2 d-flex justify-content-center" id="ContentLoading3"><div class="spinner-border text-secondary" role="status"><span class="visually-hidden" id="LoadingProcess3">Loading...</span ></div></div>');
            $("#FilterInputKeywordWO").val("");
        },
        success: function (xaxa) {
            $("#ContentLoading3").remove();
            $("#ContentModalWO").html("");
            $("#ContentModalWO").hide();
            $("#ContentModalWO").html(xaxa);
            $("#ContentModalWO").fadeIn('fast');
            $("#LoadingLoadWO").remove();
            $("#TableWO").dataTable(
                {
                    "pagingType": "full"
                }
            );
            $(".dataTables_wrapper > .dataTables_filter input").css("margin-bottom", "10px");
            $("#TableWO > tbody").css("font-size", "12px");
            $("#BtnSearchWO").attr('disabled', false);
            $("#TableWO").on("click", "tbody .BtnSelect", function () {
                var ValID = $(this).closest("tr").find("td:eq(1)").html();
                var ValProduct = $(this).closest("tr").find("td:eq(9)").html();
                var ValWOC = $(this).closest("tr").find("td:eq(2)").html();
                var ValExpense = $(this).closest("tr").find("td:eq(6)").html();
                $("#InfoWOC").val(ValWOC);
                $("#InfoWOID").val(ValID);
                $("#InfoProduct").val(ValProduct);
                $("#InfoExpense").val(ValExpense);
                $("#FilterInputKeywordWO").val("");
                $("#ContentModalWO").html("");
                $("#ModalWO").modal("hide");
                if ($("#InfoStockType").val().trim() == "Masuk Stock") {
                    $("#LabelScan").text("Pilih Gudang Kecil");
                    $("#InputScan").attr('disabled', true);
                    $("#InputScan").val("");
                    $("#BtnScan").html("Cari");
                    $("#BtnScan").get(0).focus();
                }
                else {
                    $("#InfoDivisionWH").val(ValExpense);
                    $("#BtnScan").html("Submit");
                    $("#InputScan").attr('disabled', true);
                    $("#BtnScan").get(0).focus();
                    CHECK_SUBMIT();
                }
            });
        },
        error: function () {
            alert("Request cannot proceed!");
            $("#ContentModalWO").html("");
            $("#BtnSearchWO").attr('disabled', false);
        }
    });
}
function SHOW_SMALL_WAREHOUSE()
{
    var LocationWH = $("#InfoLocation").val().trim();
    var formdata = new FormData();
    formdata.append("LocationWH", LocationWH);
    $.ajax({
        url: 'project/Inventory/ModalFindSmallWH.php',
        dataType: 'text',
        cache: false,
        contentType: false,
        processData: false,
        async: false,
        data: formdata,
        type: 'post',
        beforeSend: function () {
            $("#ModalDivisionWH").modal("show");
            $("#ContentModalDivisionWH").html("");
            $("#ContentModalDivisionWH").append('<div class="col-sm-12 pt-2 pb-2 d-flex justify-content-center" id="ContentLoading4"><div class="spinner-border text-secondary" role="status"><span class="visually-hidden" id="LoadingProcess4">Loading...</span ></div></div>');
        },
        success: function (xaxa) {
            $("#ContentLoading4").remove();
            $("#ContentModalDivisionWH").html("");
            $("#ContentModalDivisionWH").hide();
            $("#ContentModalDivisionWH").html(xaxa);
            $("#ContentModalDivisionWH").fadeIn('fast');
            $("#TableSmallWH").dataTable(
                {
                    "pagingType": "full"
                }
            );
            $(".dataTables_wrapper > .dataTables_filter input").css("margin-bottom", "10px");
            $("#TableSmallWH > tbody").css("font-size", "12px");
            $("#TableSmallWH").on("click", "tbody .BtnSelect", function () {
                var ValDivWH = $(this).closest("tr").find("td:eq(1)").html();
                $("#InfoDivisionWH").val(ValDivWH);
                $("#ContentModalDivisionWH").html("");
                $("#ModalDivisionWH").modal("hide");
                $("#BtnScan").html("Submit");
                $("#InputScan").attr('disabled', true);
                $("#BtnScan").get(0).focus();
                CHECK_SUBMIT();
            });
        },
        error: function () {
            alert("Request cannot proceed!");
            $("#ContentModalDivisionWH").html("");
        }
    });
}

function CHECK_SUBMIT() {
    $("#ModalConfirmation").modal("show");
    $("#ConfirmNIK").val($("#InfoNIK").val().trim());
    $("#ConfirmName").val($("#InfoFN").val().trim());
    $("#ConfirmDivision").val($("#InfoDivision").val().trim());
    var ArrLocation = $("#InfoLocation").val().trim();
    $("#ConfirmGudang").val($("#InfoDivisionWH").val().trim());
    switch (ArrLocation[1]) {
        case "PSL":
            $("#ConfirmCompany").val("PT Promanufacture Indonesia - Salatiga");
            break;
        case "PSM":
            $("#ConfirmCompany").val("PT Promanufacture Indonesia - Semarang");
            break;
        case "FOR":
            $("#ConfirmCompany").val("PT Formulatrix Indonesia");
            break;
        default:
            $("#ConfirmCompany").val("PT Promanufacture Indonesia - Salatiga");
            break;
    }
    $("#ConfirmLocation").val($("#InfoLocation").val().trim());
    $("#ConfirmLabelID").val($("#InfoLabelID").val().trim());
    $("#ConfirmStockType").val($("#InfoStockType").val().trim());
    $("#ConfirmIDTBZ").val($("#InfoIDLineItemTBZ").val().trim());
    $("#ConfirmPartNo").val($("#InfoPartNo").val().trim());
    $("#ConfirmCategory").val($("#InfoCategory").val().trim());
    $("#ConfirmPartDesc").val($("#InfoPartDesc").val().trim());
    $("#ConfirmWOTBZ").val($("#InfoWOTBZ").val().trim());
    $("#ConfirmJobID").val($("#InfoJobID").val().trim());
    $("#ConfirmWOChild").val($("#InfoWOC").val().trim());
    $("#ConfirmWOID").val($("#InfoWOID").val().trim());
    $("#ConfirmProduct").val($("#InfoProduct").val().trim());
    $("#ConfirmExpense").val($("#InfoExpense").val().trim());
    $("#ConfirmQty").val($("#InfoQty").val().trim());
    $("#ConfirmUOM").val($("#InfoUOM").val().trim());
    $("#ConfirmUnitCost").val($("#InfoUnitCost").val().trim());

    switch ($("#InfoStockType").val().trim()) {
        case "Masuk Stock":
        case "Tanpa Stock RM/Tools":
            //
            break;
        default:
            if ($("#ConfirmNIK").val().trim() == "") {
                $("#InfoSubmit").append('<div class="alert alert-danger alert-dismissible" role="alert"><strong>Kolom NIK masih kosong. Harap ulangi lagi proses inputnya!</strong><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
                $("#BtnSubmit").attr('disabled', true);
                return true;
            }
            if ($("#ConfirmName").val().trim() == "") {
                $("#InfoSubmit").append('<div class="alert alert-danger alert-dismissible" role="alert"><strong>Kolom Nama masih kosong. Harap ulangi lagi proses inputnya!</strong><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
                $("#BtnSubmit").attr('disabled', true);
                return true;
            }
            if ($("#ConfirmDivision").val().trim() == "") {
                $("#InfoSubmit").append('<div class="alert alert-danger alert-dismissible" role="alert"><strong>Kolom Divisi masih kosong. Harap ulangi lagi proses inputnya!</strong><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
                $("#BtnSubmit").attr('disabled', true);
                return true;
            }

            if ($("#ConfirmCompany").val().trim() == "") {
                $("#InfoSubmit").append('<div class="alert alert-danger alert-dismissible" role="alert"><strong>Kolom Company masih kosong. Harap ulangi lagi proses inputnya!</strong><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
                $("#BtnSubmit").attr('disabled', true);
                return true;
            }
            if ($("#ConfirmLocation").val().trim() == "") {
                $("#InfoSubmit").append('<div class="alert alert-danger alert-dismissible" role="alert"><strong>Kolom Location masih kosong. Harap ulangi lagi proses inputnya!</strong><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
                $("#BtnSubmit").attr('disabled', true);
                return true;
            }
            if ($("#ConfirmGudang").val().trim() == "") {
                $("#InfoSubmit").append('<div class="alert alert-danger alert-dismissible" role="alert"><strong>Kolom Gudang masih kosong. Harap ulangi lagi proses inputnya!</strong><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
                $("#BtnSubmit").attr('disabled', true);
                return true;
            }

            if ($("#ConfirmStockType").val().trim() == "") {
                $("#InfoSubmit").append('<div class="alert alert-danger alert-dismissible" role="alert"><strong>Kolom Jenis Stok masih kosong. Harap ulangi lagi proses inputnya!</strong><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
                $("#BtnSubmit").attr('disabled', true);
                return true;
            }
            if ($("#ConfirmLabelID").val().trim() == "") {
                $("#InfoSubmit").append('<div class="alert alert-danger alert-dismissible" role="alert"><strong>Kolom Label ID masih kosong. Harap ulangi lagi proses inputnya!</strong><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
                $("#BtnSubmit").attr('disabled', true);
                return true;
            }
            if ($("#ConfirmIDTBZ").val().trim() == "") {
                $("#InfoSubmit").append('<div class="alert alert-danger alert-dismissible" role="alert"><strong>Kolom ID Transact Line Item TBZ masih kosong. Harap ulangi lagi proses inputnya!</strong><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
                $("#BtnSubmit").attr('disabled', true);
                return true;
            }
            if ($("#ConfirmWOTBZ").val().trim() == "") {
                $("#InfoSubmit").append('<div class="alert alert-danger alert-dismissible" role="alert"><strong>Kolom WO TBZ masih kosong. Harap ulangi lagi proses inputnya!</strong><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
                $("#BtnSubmit").attr('disabled', true);
                return true;
            }
            if ($("#ConfirmProduct").val().trim() == "") {
                $("#InfoSubmit").append('<div class="alert alert-danger alert-dismissible" role="alert"><strong>Kolom Produk masih kosong. Harap ulangi lagi proses inputnya!</strong><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
                $("#BtnSubmit").attr('disabled', true);
                return true;
            }
            if ($("#ConfirmJobID").val().trim() == "") {
                $("#InfoSubmit").append('<div class="alert alert-danger alert-dismissible" role="alert"><strong>Kolom Job ID masih kosong. Harap ulangi lagi proses inputnya!</strong><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
                $("#BtnSubmit").attr('disabled', true);
                return true;
            }

            if ($("#ConfirmPartNo").val().trim() == "") {
                $("#InfoSubmit").append('<div class="alert alert-danger alert-dismissible" role="alert"><strong>Kolom Part No masih kosong. Harap ulangi lagi proses inputnya!</strong><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
                $("#BtnSubmit").attr('disabled', true);
                return true;
            }
            if ($("#ConfirmCategory").val().trim() == "") {
                $("#InfoSubmit").append('<div class="alert alert-danger alert-dismissible" role="alert"><strong>Kolom Kategori masih kosong. Harap ulangi lagi proses inputnya!</strong><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
                $("#BtnSubmit").attr('disabled', true);
                return true;
            }
            if ($("#ConfirmPartDesc").val().trim() == "") {
                $("#InfoSubmit").append('<div class="alert alert-danger alert-dismissible" role="alert"><strong>Kolom Part Description masih kosong. Harap ulangi lagi proses inputnya!</strong><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
                $("#BtnSubmit").attr('disabled', true);
                return true;
            }
            if ($("#ConfirmWOChild").val().trim() == "") {
                $("#InfoSubmit").append('<div class="alert alert-danger alert-dismissible" role="alert"><strong>Kolom WO Child masih kosong. Harap ulangi lagi proses inputnya!</strong><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
                $("#BtnSubmit").attr('disabled', true);
                return true;
            }
            if ($("#ConfirmExpense").val().trim() == "") {
                $("#InfoSubmit").append('<div class="alert alert-danger alert-dismissible" role="alert"><strong>Kolom Expense masih kosong. Harap ulangi lagi proses inputnya!</strong><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
                $("#BtnSubmit").attr('disabled', true);
                return true;
            }
            if ($("#ConfirmQty").val().trim() == "") {
                $("#InfoSubmit").append('<div class="alert alert-danger alert-dismissible" role="alert"><strong>Kolom Qty masih kosong. Harap ulangi lagi proses inputnya!</strong><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
                $("#BtnSubmit").attr('disabled', true);
                return true;
            }
            if ($("#ConfirmUOM").val().trim() == "") {
                $("#InfoSubmit").append('<div class="alert alert-danger alert-dismissible" role="alert"><strong>Kolom UOM masih kosong. Harap ulangi lagi proses inputnya!</strong><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
                $("#BtnSubmit").attr('disabled', true);
                return true;
            }
            if ($("#ConfirmUnitCost").val().trim() == "") {
                $("#InfoSubmit").append('<div class="alert alert-danger alert-dismissible" role="alert"><strong>Kolom Unit Cost masih kosong. Harap ulangi lagi proses inputnya!</strong><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
                $("#BtnSubmit").attr('disabled', true);
                return true;
            }
        break;
    }
    RESET_FORM();
}