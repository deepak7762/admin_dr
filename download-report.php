<?php
    include "s3/download-file.php";
    $reportPath ='';
    if(isset($_POST['reportPath']) && !empty($_POST['reportPath'])){
        $url = 'https://mymedirecords.s3.amazonaws.com/';
        $reportPath = 'reports/'.$_POST['reportPath'];
        $reportPath = str_replace($url, '', $reportPath);
        $downloadStatus = download_file($reportPath);
        echo $downloadStatus;
    }
    // include "databaseConnect.php";
    // if(isset($_POST['reportid']) && !empty($_POST['reportid']) && is_numeric($_POST['reportid'])){
    //     $reportid = $_POST['reportid'];
    //     $userID = "";
    //     if(isset($_POST['userID']) && !empty($_POST['userID']) && is_numeric($_POST['userID'])){
    //         $userID = $_POST['userID'];
    //     }

    //     try {
    //         echo $selectSql = "SELECT reportpath FROM tblreports where reportid = $reportid AND patientid = $userID";
    //         $stmt = $conn->prepare($selectSql);
    //         $stmt->execute();

    //         if ($stmt->rowCount() > 0) {
    //             $row = $stmt->fetch(PDO::FETCH_ASSOC);
    //             $pdfFilePath = 'reports_uploaded/'.$row['reportpath'];

    //             if (file_exists($pdfFilePath)) {
    //                 header('Content-Type: application/pdf');
    //                 header('Content-Disposition: attachment; filename="' . basename($pdfFilePath) . '"');
    //                 header('Content-Length: ' . filesize($pdfFilePath));
    //                 readfile($pdfFilePath);
    //             } else {
    //                 header("location:report.php?err=11");
    //             }
    //         }
    //     }catch (PDOException $e) {
    //         echo "Error: " . $e->getMessage();
    //     } 
        
    // }else{
    //     header("location:report.php?err=12");
    // }
    
?>
