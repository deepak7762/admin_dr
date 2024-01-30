<?php 
    include "databaseConnect.php";
    include "header.php";
    // include "common-function.php";
  
    if(isset($_REQUEST['numRecords']) && !empty($_REQUEST['numRecords'])){
        $numRecordPerPage = $_REQUEST['numRecords'];
    }else{
        $numRecordPerPage = 10;
    }
    include "pagination.php";

    $where = "1=1";
    $searchAdName = "";
    $startDate = "";
    $endDate = "";
    if (isset($_REQUEST['searchAdName']) && !empty($_REQUEST['searchAdName'])) {
        $searchAdName = $_REQUEST['searchAdName'];
        list($startDate, $endDate) = explode(' - ', str_replace('/', '-', $searchAdName));
        $startDate = date('Y-m-d', strtotime($startDate));
        $endDate = date('Y-m-d', strtotime($endDate));
        $where .= " AND uploaded_at BETWEEN '$startDate 00:00:00' AND '$endDate 23:59:59'";
    }

    $searchByAdType = "";
    if(isset($_REQUEST['searchByAdType']) && $_REQUEST['searchByAdType'] !="" ){
        $searchByAdType = $_REQUEST['searchByAdType'];
        $where .= " AND is_seen = '$searchByAdType'";
    }

    if(isset($_REQUEST['startDate']) && !empty($_REQUEST['startDate']) && isset($_REQUEST['endDate']) && !empty($_REQUEST['endDate']) ){
        $startDate = $_REQUEST['startDate'];
        $endDate = $_REQUEST['endDate'];
        $where .= " AND uploaded_at BETWEEN '$startDate 00:00:00' AND '$endDate 23:59:59'";
    }

    if (empty($numrecords) || !is_numeric($numrecords)) {
        $sql1 = "SELECT count(*) as total FROM \"tblreports\" where $where AND patientid = $userId";
        $result1 = $conn->query($sql1);
        $row1 = $result1->fetch(PDO::FETCH_ASSOC);
        $totalRecord = $row1["total"];
    }
    $queryLimit = $start . "," . $numRecordPerPage; 
?>
<style>
    .dataTables_paginate {
        display: flex;
        flex-direction: row;
        justify-content: end;
    }

    .pagination {
        background-color: #f2f2f2;
    }

    .pagination a {
        color: black;
        padding: 8px 16px;
        text-decoration: none;
    }

    .pagination li {
        padding: 8px;
    }

    .pagination .active {
        background-color: #007bff;
        color: white;
    }

    .pagination li:hover:not(.active) {
        background-color: #ddd;
    }

    .edit-icon-td i {
        color: #007bff;
        font-size: 21px;
    }

    .btn-default {
        background-color: #fff;
        border-color: #fff;
    }

    .btn-default:hover {
        background-color: #fff;
    }

    .red-border {
        border-color: red !important;
    }

    .custom-file {
        border: 3px dashed rgba(0, 0, 0, .15);
        height: 160px;
        width: 467px;
        border-radius: 5px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
    }

    .custom-file .icon {
        font-size: 50px;
        color: #007bff;
        text-align: center;
    }

    .custom-file header {
        font-size: 22px;
        font-weight: 500;
        color: #808080;
    }

    .custom-file-input {
        height: 0px !important;
    }

    .drag-area button {
        padding: 10px 25px;
        font-size: 20px;
        font-weight: 500;
        border: none;
        outline: none;
        background: #fff;
        color: #0fb8ac;
        border-radius: 5px;
        /* cursor: pointer; */
    }

    .btn-box {
        display: flex;
        justify-content: end;
        padding-right: 12px;
        gap: 10px;
    }

    .custom-file-label {
        cursor: pointer;
        height: 156px !important;
        text-align: center;
    }

    .custom-file-label::after {
        display: none !important;
    }

    .upload-btn {
        background: #007bff;
        color: #fff;
        border-color: #007bff;
        padding: 10px 25px 10px 25px;
    }
    .hide{
        display:none;
    }
    .actionButtons{
        display: flex;
        flex-direction: row;
        gap: 6px;
    }
    .table-bordered td, .table thead th{
        white-space: nowrap;
    }
</style>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Report</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/admin-dr/">Home</a></li>
                        <li class="breadcrumb-item active">Report</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <?php if($totalRecord != 0){?>
                <div class="card-header pb-0">
                    <?php if(isset($_GET['status']) && ($_GET['status'] == 200 || $_GET['status'] == 400 || $_GET['status'] == 1)){?>
                    <div id="toastsContainerTopRight" class="toasts-top-right fixed">
                        <div class="toast bg-success fade show" role="alert" aria-live="assertive" aria-atomic="true">
                            <div class="toast-header"><strong class="mr-auto"><?php if($_GET['status'] == 200){echo "Success!";}else if($_GET['status'] == 400 || $_GET['status'] == 1){echo "Error!";}?>
                                    </strong><button type="button"
                                    class="ml-2 mb-1 close"  id="k2jni" onclick = "closeMsgBox();"><span
                                        aria-hidden="true">×</span></button></div>
                            <div class="toast-body">
                                <?php 
                                    if($_GET['status'] == 200){
                                        echo "Report Uploaded Successfully.";
                                    }else if($_GET['status'] == 400){
                                        echo "Report Could Not be Uploaded. Please try Later.";
                                    }else if($_GET['status'] == 1){
                                        echo "Somthing Went wrong.please Try Later.";
                                    }
                                ?>
                            </div>
                        </div>
                    </div>
                    <?php }?>
                    <form method="post" id="filterForm">
                        <input type="hidden" name="numRecords" id="rowcount">
                        <input type="hidden" name="searchAdName" value="<?=$searchAdName?>">
                        <input type="hidden" name="searchByAdType" value="<?=$searchByAdType?>">
                    </form>
                    <form method="post">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="input-group">
                                    <input type="text" class="form-control float-right" id="reservation"
                                        id="searchAdName" name="searchAdName" value="<?=$searchAdName?>">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="far fa-calendar-alt"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <select class="form-control" id="searchByAdType" name="searchByAdType">
                                        <option value="">Report Status</option>
                                        <option value=1 <?php if($searchByAdType == 1){echo "selected";}?>>Seen
                                        </option>
                                        <option value=0 <?php if($searchByAdType == 0){echo "selected";}?>>Pending
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-primary">Search</button>
                                <button type="button" class="btn btn-primary" onclick="resetBtnFun();">Reset</button>
                            </div>
                        </div>
                    </form>
                    <div class="row">
                        <div class="col-md-6">
                            <p>Total <b><?=$totalRecord?></b> Record Found.</p>
                        </div>
                        <div class="col-md-6">
                            <button type="button" class="btn btn-outline-primary float-right" fdprocessedid="a74cte"
                                onclick="showAddAdminModel();"><i class="fas fa-plus"></i> Upload Report</button>

                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <?php 
                        try {
                            $selectSql = "SELECT * FROM tblreports where $where AND patientid = $userId ORDER BY \"reportid\" DESC LIMIT $numRecordPerPage OFFSET $start";
                            $stmt = $conn->prepare($selectSql);
                            $stmt->execute();

                            if ($stmt->rowCount() > 0) {
                        ?>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Report Name</th>
                                    <th>Uploaded At</th>
                                    <th>Report Status</th>
                                    <th>Doctor Name</th>
                                    <th>Seen At</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $i=1;
                                $doctorName = "";
                                $seenDateTime = "";
                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                    if($row['doctorid'] != NULL){
                                        $getDrSql = "select adminname from tbladmins where id = ".$row['doctorid']." AND adminpermission = 3";
                                        $stmtDr = $conn->prepare($getDrSql);
                                        $stmtDr->execute();
                                        $DrName = $stmtDr->fetch(PDO::FETCH_ASSOC);
                                        $doctorName = $DrName['adminname'];
                                        $seenDateTime = date('d M Y H:i:s', strtotime($row['seen_at']));
                                    }else{
                                        $doctorName = "-";
                                        $seenDateTime = "-";
                                    }
                        ?>
                                <tr>
                                    <td><?=$i?></td>
                                    <td><?=$row['reportpath']?></td>
                                    <td><?=date('d M Y H:i:s', strtotime($row['uploaded_at']))?></td>
                                    <td><?php if($row['is_seen'] == 0){echo "<span style='color:red;'>Pending</span>";} else if($row['is_seen'] == 1){echo "<span style='color:green;'>Seen</span>";}?>
                                    </td>
                                    <td><?=$doctorName?></td>
                                    <td><?=$seenDateTime?></td>
                                    <td class="actionButtons">
                                        <form method = "post" action = "download-report.php" target='_blank'>
                                            <input type = "hidden" name="reportid" value = <?=$row['reportid']?>>
                                            <input type = "hidden" name="userID" value = <?=$userId?>>
                                            <input type = "hidden" name="reportPath" value = <?=$row['reportpath']?>>
                                            <button type="submit" class="btn btn-block btn-outline-primary" fdprocessedid="n9lxer">
                                                <i class=" fas fa-download"></i>
                                            </button>
                                        </form>
                                        <form action="view-report-details.php" method="post" target="_blank">
                                            <input type="hidden" name="viewereportid" value="<?php echo $row['reportid']; ?>">
                                            <input type="hidden" name="viewuserId" value="<?php echo $userId; ?>">
                                            <input type="hidden" name="permissionType" value="<?php echo $permissionType; ?>">
                                            <button type="submit" class="btn btn-block btn-outline-primary"
                                                fdprocessedid="n9lxer">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                <?php
                                $i++;
                                }
                        ?>
                            </tbody>
                        </table>
                    </div>
                    <?php
                            } else {
                                echo "No Data Found.";
                            }
                        } catch (PDOException $e) {
                            echo "Error: " . $e->getMessage();
                        } 
                        ?>
                </div>

                <div class="card-footer">
                    <div class="row">
                        <div class="col-md-2 col-lg-2 pt-2">
                            <div class="form-group">
                                <select class="form-control" name="rowcount" onchange="sortOrder(this.value)">
                                    <?php
                                    $numrows_arr = array("8","10","25","50","100","200");
                                    foreach($numrows_arr as $nrow){ ?>
                                    <option value="<?=$nrow?>"
                                        <?php if($numRecordPerPage == $nrow){ echo 'selected'; } ?>><?=$nrow?>
                                    </option>
                                    <?php }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-10 col-lg-10 pt-2">
                            <div class="dataTables_paginate paging_simple_numbers table-responsive">
                                <ul class="pagination">
                                    <?php
                                        $pageName = "report.php";
                                        $parameters = "numRecords=".$numRecordPerPage."&startDate=".$startDate."&endDate=".$endDate."&searchByAdType=".$searchByAdType;				
                                        printPagination($pageName,$numRecordPerPage,$totalRecord,$parameters);
                                    ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <?php }else {?>
                <div class="text-center p-5">No Report Available.</div>
                <?php }?>
            </div>
        </div>

    </section>
</div>

<!-- /.modal -->
<div class="modal fade" id="upload-report-modal">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Upload Report</h4>
                <button type="button" class="close" onclick ="closeModal();">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p class="hide" id="errorPara" style="color:red;"></p>
                <!-- <form action="report-process.php" method="POST" enctype="multipart/form-data" class=""
                    id="P2ReportForm"> -->
                <form action="report-process.php" method="POST" enctype="multipart/form-data" class=""
                    id="P2ReportForm">
                    <div class="container-box">
                        <div class="custom-file">
                            <input type="hidden" value="uploadReport" name="callType">
                            <input type="file" class="custom-file-input custom-file-input-paypal" id="customFile_p2"
                                accept=".pdf" name="file_p2">
                            <label class="custom-file-label custom-file-label-paypal" for="customFile_p2">
                                <div class="icon"><i class="fas fa-cloud-upload-alt"></i></div>
                                <header>Upload Report in Pdf Format.</header>
                            </label>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <p class="mt-3" id="errorMessage" style="color:red;"></p>                            
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-danger" onclick ="closeModal();">Close</button>
                        <button type="submit" class="btn btn-primary">Upload</button>
                    </div>
                </form>
            </div>

        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    function sortOrder(colName) {
        if ($.isNumeric(colName) == true) {
            $("#rowcount").val(colName);
        }
        $("form#filterForm").submit();
    }

    function resetBtnFun() {
        $('#searchAdName').val('');
        $('#searchByAdType').val('');
        window.location.href = "report.php";
    }

    <?php if(isset($_GET['status']) && $_GET['status'] == 3){?>
        $('#upload-report-modal').css({
            'display': 'block'
        });
        $('#upload-report-modal').removeClass('fade');
        $("#errorMessage").html("Error! Please Select File to Upload.");
    <?php }else if(isset($_GET['status']) && $_GET['status'] == 2){?>
        $('#upload-report-modal').css({
            'display': 'block'
        });
        $('#upload-report-modal').removeClass('fade');
        $("#errorMessage").html("Error! Invalid file type. Only Pdf files are allowed.");
    <?php }?>

    function showAddAdminModel() {
        $('#upload-report-modal').modal('show');
    }
    
    function closeMsgBox(){
        $("#toastsContainerTopRight").addClass("hide");
    }
    function closeModal(){
        console.log("coming in");
        $('#upload-report-modal').css({
            'display': 'none'
        });
        window.location.href = "report.php";
    }

</script>
<script>
    $(".custom-file-input-paypal").on("change", function () {
        var fileName = $(this).val().split("\\").pop();
        $(this).siblings(".custom-file-label-paypal").addClass("selected").html(fileName);
    });

    $(document).ready(function () {
        if ($('#errorMessage').length) {
            setTimeout(function () {
                $('#errorMessage').fadeOut('slow');
            }, 5000);
        }
    });

    // function uploadReport(){
    //     var fileName = $("#customFile_p2").val();
    //     $.ajax({
    //         url: "report-process.php",
    //         type: "POST",
    //         data: {
    //             file_name:fileName,
    //             callType: 'uploadReport'
    //         },
    //         success: function (response) {
    //             // var resp = JSON.parse(response);
    //         },
    //     });
    // }
</script>
<?php include "footer.php"?>
<script>
    //Date range picker
    $('#reservation').daterangepicker({
        "locale": {
            "format": "DD/MM/YYYY",
            // other locale options if needed
        },
    });
</script>