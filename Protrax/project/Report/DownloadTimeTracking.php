<?php
require_once("../webtrax/project/CostTracking/Modules/ModuleCostTracking.php");
require_once("project/Report/Modules/ModuleReport.php");
date_default_timezone_set("Asia/Jakarta");
$YearNow = date("Y");
$DateNow = date("m/d/Y");



?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.full.min.js" integrity="sha512-AIOTidJAcHBH2G/oZv9viEGXRqDNmfdPVPYOYKGy3fti0xIplnlgMHUGfuNRzC6FkzIo0iIxgFnr9RikFxK+sw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<link rel="stylesheet" type="text/css" href="lib/datetimepicker-master/jquery.datetimepicker.css"/>
<script src="project/Report/lib/LibReportTimeTracking.js?no=<?php echo base64_encode(date("mdyHis")); ?>"></script>
<div class="row">
    <div class="col-md-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-detail ">
                <li class="breadcrumb-item"><a href="home.php">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Report : Material Tracking</li>
            </ol>
        </nav>
    </div>
</div>
<div class="row">
    <div class="col-md-3">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body pt-2">
                        <div class="row">
                            <div class="col-md-12"><h6>Filter Date</h6></div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="controls">
                                        <label for="txtFilterTanggal1" class="form-label fw-bold">Start Date</label>
                                        <div class="input-group input-group-sm">
                                            <input id="txtFilterTanggal1" name="txtFilterTanggal1" type="text" class="date-picker form-control" aria-describedby="txtFilterTanggal1Val" value="<?php echo $DateNow; ?>" readonly />
                                            <label for="txtFilterTanggal1" class="input-group-text" id="txtFilterTanggal1Val"><span class="bi bi-calendar-date text-dark"></span></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="controls">
                                        <label for="txtFilterTanggal2" class="form-label fw-bold">End Date</label>
                                        <div class="input-group input-group-sm">
                                            <input id="txtFilterTanggal2" name="txtFilterTanggal2" type="text" class="date-picker form-control" aria-describedby="txtFilterTanggal2Val" value="<?php echo $DateNow; ?>" readonly /><label for="txtFilterTanggal2" class="input-group-text" id="txtFilterTanggal2Val"><span class="bi bi-calendar-date text-dark"></span></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12 pt-3">
                <div class="card">
                    <div class="card-body pt-2">
                        <div class="row">
                            <div class="col-md-12"><h6>Custom Search</h6></div>
                            <div class="col-md-12"><div class="form-check">
                                <input class="form-check-input" type="checkbox" id="DateCheckDefault" checked>
                                <label class="form-check-label" for="DateCheckDefault">
                                Gunakan Tanggal
                                </label></div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="FilterCustom" class="form-label fw-bold">Category</label>
                                    <select class="form-select form-select-sm" id="FilterCustom">
                                        <option>Employee Name</option>
                                        <option>WO Child</option>
                                        <option>WO Mapping ID</option>
                                        <option>Quote</option>
                                        <option>Product</option>
                                        <option>Expense Allocation</option>
                                        <option>Production Manager</option>
                                        <option>Division</option>
                                        <option>Quote Category</option>
                                        <option>Part No</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="FilterKeywords" class="form-label fw-bold">Keywords</label>
                                    <input type="text" class="form-control form-control-sm" id="FilterKeywords" placeholder="Keywords">
                                </div>
                            </div>                                
                            <div class="col-md-12 d-grid mt-2">
                                <button class="btn btn-sm btn-dark" id="BtnViewData">View Data</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12 pt-3">
                <div class="card">
                    <div class="card-body pt-2">
                        <div class="row">
                            <div class="col-md-12"><h6>Filter ClosedTime</h6></div>
                            <div class="col-md-12"><div class="form-check">
                                <input class="form-check-input" type="checkbox" id="UsedOpen">
                                <label class="form-check-label" for="UsedOpen">
                                Termasuk "OPEN"
                                </label></div>
                            </div><?php /*
                            <div class="col-12"><div class="form-check">
                                <input class="form-check-input" type="checkbox" id="PartNoDefault2">
                                <label class="form-check-label" for="PartNoDefault2">
                                Termasuk Part No
                                </label></div>
                            </div>*/ ?>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="FilterSeason" class="form-label fw-bold">Closed Time</label>
                                    <select class="form-select form-select-sm" id="FilterSeason">
                                        <?php 
                                        $QListClosedTimeF = GET_LIST_CLOSEDTIME_NOT_OPEN("",$linkMACHWebTrax);
                                        while($RListClosedTimeF = sqlsrv_fetch_array($QListClosedTimeF))
                                        {
                                            $ClosedTime = $RListClosedTimeF['ClosedTime'];
                                            ?>
                                            <option><?php echo $ClosedTime; ?></option>
                                            <?php
                                        }                
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12 d-grid mt-2">
                                <button class="btn btn-sm btn-dark" id="BtnViewDataClosedTime">View Data</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-9 pt-2"><div class="row" id="ContentResult"></div></div>
    <div class="col-md-12 mt-4"></div>
</div>