<?php
    include "databaseConnect.php";
    include "s3/send-files.php";
    include "call_api_function.php";
    session_start();
    $userId = "";
    if(isset($_SESSION['userid']) && !empty($_SESSION['userid'])){
        $userId = $_SESSION['userid'];
    }

    $type = "";
    if(isset($_POST['callType']) && !empty($_POST['callType'])){
        $type = $_POST['callType'];
    }

    date_default_timezone_set("Asia/Kolkata");
    $currentDateTime = date("Y-m-d H:i:s");

    if($type == "uploadReport"){
        $file_name = "";
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["file_p2"]) && $_FILES["file_p2"]["error"] != 4) {
            $fileName = basename($_FILES["file_p2"]["name"]);
            $fileType = $_FILES["file_p2"]["type"];
            
            if($fileType == "application/pdf"){
                $resp = upload_file($_FILES["file_p2"]["tmp_name"],$fileName,$userId);
                $resp = json_decode($resp);
                if($resp->status == 200){
                    $reportPath = $resp->content;
                    $pathParts = explode("reports/", $reportPath);
                    $pathAfterReports = $pathParts[1];
                    try {
                        $uploadFile = "INSERT INTO tblreports (patientid, reportpath, uploaded_at) VALUES ($userId, '$pathAfterReports', '$currentDateTime')";
                        $stmt = $conn->prepare($uploadFile);
                        $res = $stmt->execute();
                        if($res){
                            $lastInsertedId = $conn->lastInsertId();
                           $temp = report_process_api_fun($userId, $lastInsertedId, $pathAfterReports);
                            print_r($temp);die; 
                           header("location:report.php?status=200");
                        }else{
                            header("location:report.php?status=400");
                        }
                    } catch (PDOException $e) {
                        echo "Error: " . $e->getMessage();
                    } 
                }else if($resp['status'] == 400){
                    header("location:report.php?status=400");
                }
            }else{
                header("location:report.php?status=2");
            }
        }else{
            header("location:report.php?status=3");
        }
    }
?>