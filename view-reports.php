<?php 
       include "databaseConnect.php";
       include "header.php";
   
       if(isset($_REQUEST['numRecords']) && !empty($_REQUEST['numRecords'])){
           $numRecordPerPage = $_REQUEST['numRecords'];
       } else {
           $numRecordPerPage = 10;
       }
   
       include "pagination.php";
   
       $where = "";
       $patientid = "";
       $searchBypatientid = '';
       $searchBypatientEmail = '';
       $searchBypatientPhone = '';
       $totalRecord = 0;
   
       if ((isset($_REQUEST['searchBypatientid']) && $_REQUEST['searchBypatientid'] != "") ||
           (isset($_REQUEST['searchBypatientEmail']) && !empty($_REQUEST['searchBypatientEmail'])) ||
           (isset($_REQUEST['searchBypatientPhone']) && $_REQUEST['searchBypatientPhone'] != "")) {
   
           if (isset($_REQUEST['searchBypatientid']) && $_REQUEST['searchBypatientid'] != "") {
               $patientid = $_REQUEST['searchBypatientid'];
               $searchBypatientid = $_REQUEST['searchBypatientid'];
           } elseif (isset($_REQUEST['searchBypatientEmail']) && !empty($_REQUEST['searchBypatientEmail'])) {
               $searchBypatientEmail = $_REQUEST['searchBypatientEmail'];
               $patientid = getpatientid($conn, $searchBypatientEmail, '');
           } elseif (isset($_REQUEST['searchBypatientPhone']) && $_REQUEST['searchBypatientPhone'] != "") {
               $searchBypatientPhone = $_REQUEST['searchBypatientPhone'];
               $patientid = getpatientid($conn, '', $searchBypatientPhone);
           }
           $where = "patientid = $patientid";
       }  
   
       $patientName = '';
        if($patientid != ''){
            $getPatientNameSql = "SELECT adminname FROM tbladmins WHERE id = '$patientid'";
            $stmt_new = $conn->query($getPatientNameSql);
        
            if ($stmt_new->rowCount() > 0) {
                $row_new = $stmt_new->fetch(PDO::FETCH_ASSOC);
                $patientName = $row_new['adminname'];
            }
        }
   
       $currentPage = 1;
   
       if(isset($_REQUEST['pg']) && !empty($_REQUEST['pg'])){
           $currentPage = $_REQUEST['pg'];
       }
   
       function getpatientid($conn, $getBYEmail, $getBYPhoneNumber){
           if($getBYEmail != ''){
               $selectSql = "SELECT id FROM tbladmins WHERE adminemail = '$getBYEmail'";
           } elseif($getBYPhoneNumber != ''){
               $selectSql = "SELECT id FROM tbladmins WHERE mobile = $getBYPhoneNumber";
           }        
   
           $stmt = $conn->query($selectSql);
   
           if ($stmt->rowCount() > 0) {
               $row1 = $stmt->fetch(PDO::FETCH_ASSOC);
           }
   
           return $row1['id'];
       }
   ?>
   <style>
       .orInBtwFilters {
           position: absolute;
           left: 237px;
           font-size: 15px;
           top: 10px;
           color: #9d9ba2;
       }
   
       .content-wrapper {
           min-height: 476px !important;
       }
   
       .actionButtons {
           display: flex;
           flex-direction: row;
           gap: 5px;
       }
   </style>
   
   <div class="content-wrapper">
       <div class="content-header">
           <div class="container-fluid">
               <div class="row mb-2">
                   <div class="col-sm-6">
                       <h1 class="m-0">View Reports</h1>
                   </div>
                   <div class="col-sm-6">
                       <ol class="breadcrumb float-sm-right">
                           <li class="breadcrumb-item"><a href="/admin-dr/">Home</a></li>
                           <li class="breadcrumb-item active">View Reports</li>
                       </ol>
                   </div>
               </div>
           </div>
       </div>
   
       <section class="content">
           <div class="container-fluid">
               <div class="card">
                   <?php //if($totalRecord != 0){?>
                   <div class="card-header">
                       <?php if(isset($_GET['status']) && ($_GET['status'] == 200 || $_GET['status'] == 400 || $_GET['status'] == 1)){?>
                       <div id="toastsContainerTopRight" class="toasts-top-right fixed">
                           <div class="toast bg-success fade show" role="alert" aria-live="assertive" aria-atomic="true">
                               <div class="toast-header"><strong
                                       class="mr-auto"><?php if($_GET['status'] == 200){echo "Success!";}else if($_GET['status'] == 400 || $_GET['status'] == 1){echo "Error!";}?>
                                   </strong><button type="button" class="ml-2 mb-1 close" id="k2jni"
                                       onclick="closeMsgBox();"><span aria-hidden="true">×</span></button></div>
                               <div class="toast-body">
                                   <?php 
                                       if($_GET['status'] == 200){
                                           echo "Report Uploaded Successfully.";
                                       }else if($_GET['status'] == 400){
                                           echo "Report Could Not be Uploaded. Please try Later.";
                                       }else if($_GET['status'] == 1){
                                           echo "Somthing Went wrong. Please Try Later.";
                                       }
                                   ?>
                               </div>
                           </div>
                       </div>
                       <?php }?>
                       <form method="post" id="filterForm">
                       </form>
                       <form method="post" id="filterForm_main">
                           <div class='row'>
                               <p id="errorDisplay-top" style="color:red;"></p>
                           </div>
                           <div class="row">
                               <div class="col-md-3">
                                   <div class="form-group">
                                       <input type="text" class="form-control float-right" id="searchBypatientid"
                                           name="searchBypatientid" value="<?=$searchBypatientid?>"
                                           placeholder="Patient ID">
                                   </div>
                               </div>
                               <div class="col-md-3">
                                   <div class="form-group">
                                       <input type="text" class="form-control float-right" id="searchBypatientEmail"
                                           name="searchBypatientEmail" value="<?=$searchBypatientEmail?>"
                                           placeholder="Email">
                                   </div>
                               </div>
                               <div class="col-md-3">
                                   <div class="form-group">
                                       <input type="text" class="form-control float-right" id="searchBypatientPhone"
                                           name="searchBypatientPhone" value="<?=$searchBypatientPhone?>"
                                           placeholder="Phone Number">
                                   </div>
                               </div>
                               <div class="col-md-3">
                                   <button type="button" id="viewBtn" class="btn btn-primary" onclick="sendOTP();">View</button>
                                   <button type="button" class="btn btn-primary" onclick="resetBtnFun();">Reset</button>
                               </div>
                           </div>
                       </form>                   
                   </div>
   
                   <div class="card-body">
                       <div class="row">
                           <div class="col-md-6">
                               <?php if($patientName != ''){?>
                                   <p>Below Reports are of <b><?=$patientName?></b></p>
                               <?php }?>
                           </div>
                           <div class="col-md-6"></div>
                       </div>
                       <?php
                           $startId = ($currentPage - 1) * $numRecordPerPage + 1; 
                           if($where != ''){
                               if (empty($numrecords) || !is_numeric($numrecords)) {
                                   $sql1 = "SELECT count(*) as total FROM tblreports WHERE $where";
                                   $result1 = $conn->query($sql1);
                                   $row1 = $result1->fetch(PDO::FETCH_ASSOC);
                                   $totalRecord = $row1["total"];
                               }
                               $queryLimit = $start . "," . $numRecordPerPage; 
   
                               try {
                                   $selectSql = "SELECT * FROM tblreports WHERE $where ORDER BY reportid DESC LIMIT $numRecordPerPage OFFSET $start";
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
                                   $doctorName = "";
                                   $seenDateTime = "";
                                   while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                       if($row['doctorid'] != NULL){
                                           $getDrSql = "SELECT adminname FROM tbladmins WHERE id = ".$row['doctorid']." AND adminpermission = 3";
                                           $stmtDr = $conn->prepare($getDrSql);
                                           $stmtDr->execute();
                                           $DrName = $stmtDr->fetch(PDO::FETCH_ASSOC);
                                           $doctorName = $DrName['adminname'];
                                           $getSeenStatus = 
                                           $seenDateTime = date('d M Y H:i:s', strtotime($row['seen_at']));
                                       }else{
                                           $doctorName = "-";
                                           $seenDateTime = "-";
                                       }
                                   ?>
                                   <tr>
                                       <td><?=$startId++?></td>
                                       <td><?=$row['reportpath']?></td>
                                       <td><?=date('d M Y H:i:s', strtotime($row['uploaded_at']))?></td>
                                       <td>
                                           <?php if($row['is_seen'] == 0){
                                               echo "<span style='color:red;'>Pending</span>";
                                           } else if($row['is_seen'] == 1){
                                               echo "<span style='color:green;'>Seen</span>";
                                           }?>
                                       </td>
                                       <td><?=$doctorName?></td>
                                       <td><?=$seenDateTime?></td>
                                       <td class="actionButtons">
                                           <form method="post" action="download-report.php" target = '_blank'>
                                               <input type="hidden" name="reportid" value=<?=$row['reportid']?>>
                                               <input type="hidden" name="userID" value=<?=$patientid?>>
                                               <input type = "hidden" name="reportPath" value = <?=$row['reportpath']?>>
                                               <button type="submit" class="btn btn-block btn-outline-primary"
                                                   fdprocessedid="n9lxer">
                                                   <i class=" fas fa-download"></i>
                                               </button>
                                           </form>
   
                                           <form action="view-report-details.php" method="post" target="_blank">
                                               <input type="hidden" name="viewereportid" value="<?php echo $row['reportid']; ?>">
                                               <input type="hidden" name="viewuserId" value="<?php echo $patientid; ?>">
                                               <input type="hidden" name="permissionType" value="<?php echo $permissionType; ?>">
                                               <button type="submit" class="btn btn-block btn-outline-primary"
                                                   fdprocessedid="n9lxer">
                                                   <i class="fas fa-eye"></i>
                                               </button>
                                           </form>
                                       </td>
                                   </tr>
                                   <?php
                                   }
                                   ?>
                               </tbody>
                           </table>
                       </div>
                   </div>
   
                   <div class="card-footer">
                       <div class="row">
                           <div class="col-md-2 col-lg-2 pt-2">
                               <div class="form-group">
                                   <select class="form-control" name="rowcount" onchange="sortOrder(this.value)">
                                       <?php
                                       $numrows_arr = array("8","10","25","50","100","200");
                                       foreach($numrows_arr as $nrow){
                                       ?>
                                       <option value="<?=$nrow?>"
                                           <?php if($numRecordPerPage == $nrow){ echo 'selected'; } ?>>
                                           <?=$nrow?>
                                       </option>
                                       <?php
                                       }
                                       ?>
                                   </select>
                               </div>
                           </div>
                           <div class="col-md-10 col-lg-10 pt-2">
                               <div class="dataTables_paginate paging_simple_numbers table-responsive">
                                   <ul class="pagination">
                                       <?php
                                           $pageName = "view-reports.php";
                                           $parameters = "numRecords=".$numRecordPerPage.
                                                         "&searchBypatientid=".$searchBypatientid.
                                                         '&searchBypatientEmail='.$searchBypatientEmail.
                                                         '&searchBypatientPhone='.$searchBypatientPhone;
                                           printPagination($pageName,$numRecordPerPage,$totalRecord,$parameters);
                                       ?>
                                   </ul>
                               </div>
                           </div>
                       </div>
                   </div>
                   <?php
                               } else {
                                   echo "No Data Found.";
                               }
                           } catch (PDOException $e) {
                               echo "Error: " . $e->getMessage();
                           }
                       }
                       ?>
               </div>
           </div>
       </section>
   </div>



<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    function sortOrder(colName) {
        if ($.isNumeric(colName) == true) {
            $("#rowcount").val(colName);
        }
        $("form#filterForm").submit();
    }

    function resetBtnFun() {
        $('#searchBypatientid').val('');
        $('#searchBypatientEmail').val('');
        $('#searchBypatientPhone').val('');
        window.location.href = "view-reports.php";
    }
</script>
<script>
    $(document).ready(function () {
        if ($('#errorMessage').length) {
            setTimeout(function () {
                $('#errorMessage').fadeOut('slow');
            }, 5000);
        }
    });

    function updateStatus(reportid, doctorid, reportpath) {
        $.ajax({
            url: "update-staus-process.php",
            type: "POST",
            data: {
                reportid_: reportid,
                doctorid_: doctorid
            },
            success: function (response) {
                var resp = JSON.parse(response);
                if (resp.status === 200 && resp.content === 'success') {
                    window.location = '/admin-dr/reports_uploaded/' + reportpath;
                } else if (resp.status === 400 && resp.content === 'failed') {
                    $('#errorDisplay').html(resp.content);
                }
            },
        });
    }

    function sendOTP(){
        var id = $('#searchBypatientid').val();
        var email = $('#searchBypatientEmail').val();
        var phone = $('#searchBypatientPhone').val();

        if (id === '' && email === '' && phone === '') {
            $('#errorDisplay-top').html("Please Enter Patient Id or Email or Phone Number.");
            return false;
        }

        $('#viewBtn').html('<i class="fa fa-spinner fa-spin"></i>');
        $('#viewBtn').prop('disabled', true);

        $.ajax({
            url: "generate-otp-process.php",
            type: "POST",
            data: {
                dr_id: <?=$userId?>,
                id_: id,
                email_: email,
                phone_:phone,
                type: "sendOTP"
            },
            success: function (response) {
                var resp = JSON.parse(response);
                if (resp.status === 200 && resp.message === 'success') {
                    $('#modal-default').modal('show');
                    $('#modalErrorMsg').html("<span style='color:green'>"+resp.content+"</span>");
                    $('#viewBtn').html('View');
                    $('#viewBtn').prop('disabled', false);
                }else if (resp.status === 400 && resp.message === 'failed') {
                    $('#errorDisplay-top').html(resp.content);
                    $('#viewBtn').html('View');
                    $('#viewBtn').prop('disabled', false);
                }else if (resp.status === 500 && resp.message === 'failed') {
                    $('#errorDisplay-top').html(resp.content);
                    $('#viewBtn').html('View');
                    $('#viewBtn').prop('disabled', false);
                }
            },
        });
    }

    function verifyOTp(){
        var otp = $('#verifyOtp').val();

        $('#verifyOTPBtn').html('<i class="fa fa-spinner fa-spin"></i>');
        $('#verifyOTPBtn').prop('disabled', true);

        $.ajax({
            url: "generate-otp-process.php",
            type: "POST",
            data: {
                otp_:otp,
                type: "verifyOTP"
            },
            success: function (response) {
                var resp = JSON.parse(response);
                if (resp.status === 200 && resp.message === 'success') {
                    $('#modal-default').modal('hide');
                    $("#filterForm_main").submit();
                }
                 else if (resp.status === 400 && resp.message === 'failed') {
                    $('#modalErrorMsg').html("<span style='color:red'>"+resp.content+"</span>");
                    $('#verifyOTPBtn').html('Verify');
                    $('#verifyOTPBtn').prop('disabled', false);
                }
            },
        });
    }
</script>
<?php include "footer.php"?>
<div class="modal fade" id="modal-default">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Verify OTP</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p id = "modalErrorMsg"></p>
                <div class="form-group">
                    <input type = "text" class="form-control float-right" id = "verifyOtp" name = "verifyOtp" placeholder= "Enter OTP" />
                </div>                
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="verifyOTPBtn" onclick = "verifyOTp();">verify</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->