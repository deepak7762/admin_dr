<?php
    session_start();
    include "databaseConnect.php";

    $type = "";
    if(isset($_POST['callType']) && !empty($_POST['callType'])){
        $type = $_POST['callType'];
    }

    $editId = "";
    if(isset($_POST['editId']) && !empty($_POST['editId'])){
        $editId = $_POST['editId'];
    }

    date_default_timezone_set("Asia/Kolkata");
    $currentDateTime = date("Y-m-d H:i:s");

    $arr = array();

    if($type == 'getInfo'){
        try {
            $selectSql = "SELECT status FROM tbladmins WHERE id = :editId";
            $stmt = $conn->prepare($selectSql);
            $stmt->bindParam(':editId', $editId, PDO::PARAM_INT);
            $stmt->execute();         
            
            if($stmt->rowCount() > 0){
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $_SESSION['updateId'] = $editId;
                $arr = array(                    
                    "status" => $row['status']
                );
            }            
            echo json_encode($arr);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }    
    }else if($type == 'updateInfo'){        
        $ad_status = "";
        if(isset($_POST['ad_status']) && $_POST['ad_status'] != ''){
            $ad_status = $_POST['ad_status'];
        }
        if(isset($_SESSION['updateId']) && !empty($_SESSION['updateId'])){
            try {
                $updateSql = "UPDATE tbladmins SET status = :ad_status WHERE id = :updateId";
                $stmt = $conn->prepare($updateSql);
                $stmt->bindParam(':ad_status', $ad_status, PDO::PARAM_INT);
                $stmt->bindParam(':updateId', $_SESSION['updateId'], PDO::PARAM_INT);
                if($stmt->execute()){
                    unset($_SESSION['updateId']);
                    $arr = array("status" => 200, "content" => "Success! Patient Updated Successfully.");
                } else {
                    unset($_SESSION['updateId']);
                    $arr = array("status" => 400, "content" => "Error! Something went wrong. Try later.");
                }
            } catch (PDOException $e) {
                $arr = array("status" => 400, "content" => "Error: " . $e->getMessage());
            }
        } else {
            $arr = array("status" => 400, "content" => "Error! Session updateId not set.");
        }        
        echo json_encode($arr);        
    }

?>