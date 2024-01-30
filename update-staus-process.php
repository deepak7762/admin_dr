<?php 
    include "databaseConnect.php";

    $reportid ="";
    if(isset($_POST['reportid_']) && !empty($_POST['reportid_'])){
        $reportid = $_POST['reportid_'];
    }

    $doctorid ='';
    if(isset($_POST['doctorid_']) && !empty($_POST['doctorid_'])){
        $doctorid = $_POST['doctorid_'];
    }

    date_default_timezone_set("Asia/Kolkata");
    $currentDateTime = date("Y-m-d H:i:s");
    $arr = array();

    $updateSql = "update tblreports set is_seen = 1, doctorid = $doctorid, seen_at = '$currentDateTime' where reportid = $reportid";
    $updateQuery = $conn->query($updateSql);
    if ($updateQuery) {
        $arr = array("status" => 200, "content" => "success");
    }else{
        $arr = array("status" => 400, "content" => "failed");
    }
    echo json_encode($arr); 
?>