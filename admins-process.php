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
            $selectSql = "SELECT adminname, adminemail, mobile, status, adminpermission FROM tbladmins WHERE id = :editId";
            $stmt = $conn->prepare($selectSql);
            $stmt->bindParam(':editId', $editId, PDO::PARAM_INT);
            $stmt->execute();         
            
            if($stmt->rowCount() > 0){
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $_SESSION['updateId'] = $editId;
                $arr = array(
                    "adName" => $row['adminname'],
                    "adEmail" => $row['adminemail'],
                    "adMobile" => $row['mobile'],
                    "status" => $row['status'],
                    "adminpermission" => $row['adminpermission']
                );
            }
            
            echo json_encode($arr);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    
    }else if($type == 'updateInfo'){
        $ad_name = "";
        if(isset($_POST['ad_name']) && !empty($_POST['ad_name'])){
            $ad_name = ucwords($_POST['ad_name']);
        }
        $ad_email = "";
        if(isset($_POST['ad_email']) && !empty($_POST['ad_email'])){
            $ad_email = $_POST['ad_email'];
        }
        $ad_mobile = "";
        if(isset($_POST['ad_mobile']) && !empty($_POST['ad_mobile'])){
            $ad_mobile = $_POST['ad_mobile'];
        }
        $ad_status = "";
        if(isset($_POST['ad_status']) && $_POST['ad_status'] != ''){
            $ad_status = $_POST['ad_status'];
        }
        $ad_permission = "";
        if(isset($_POST['ad_permission']) && !empty($_POST['ad_permission'])){
            $ad_permission = $_POST['ad_permission'];
        }

        if(isset($_SESSION['updateId']) && !empty($_SESSION['updateId'])){
            try {
                $updateSql = "UPDATE tbladmins SET adminname = :ad_name, adminemail = :ad_email, mobile = :ad_mobile, status = :ad_status, adminpermission = :ad_permission WHERE id = :updateId";
                $stmt = $conn->prepare($updateSql);
                $stmt->bindParam(':ad_name', $ad_name, PDO::PARAM_STR);
                $stmt->bindParam(':ad_email', $ad_email, PDO::PARAM_STR);
                $stmt->bindParam(':ad_mobile', $ad_mobile, PDO::PARAM_STR);
                $stmt->bindParam(':ad_status', $ad_status, PDO::PARAM_INT);
                $stmt->bindParam(':ad_permission', $ad_permission, PDO::PARAM_STR);
                $stmt->bindParam(':updateId', $_SESSION['updateId'], PDO::PARAM_INT);
                if($stmt->execute()){
                    unset($_SESSION['updateId']);
                    $arr = array("status" => 200, "content" => "Success! Admin Updated Successfully.");
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
    }else if($type == 'addInfo'){
        $add_name = "";
        if(isset($_POST['add_name']) && !empty($_POST['add_name'])){
            $add_name = ucwords($_POST['add_name']);
        }
        $add_email = "";
        if(isset($_POST['add_email']) && !empty($_POST['add_email'])){
            $add_email = $_POST['add_email'];
        }
        $add_mobile = "";
        if(isset($_POST['add_mobile']) && !empty($_POST['add_mobile'])){
            $add_mobile = $_POST['add_mobile'];
        }
        $add_status = "";
        if(isset($_POST['add_status']) && $_POST['add_status'] != ''){
            $add_status = $_POST['add_status'];
        }
        $add_permission = "";
        if(isset($_POST['add_permission']) && !empty($_POST['add_permission'])){
            $add_permission = $_POST['add_permission'];
        }
        $add_password = "";
        if(isset($_POST['add_password']) && !empty($_POST['add_password'])){
            $add_password = $_POST['add_password'];
        }

        try {            
            $insertSql = "INSERT INTO tbladmins(adminname, adminemail, mobile, adminpassword, Createdat, status, adminpermission) VALUES(:adminname, :adminemail, :mobile, :adminpassword, :Createdat, :status, :adminpermission)";
            $stmt = $conn->prepare($insertSql);
            $stmt->bindParam(':adminname', $add_name);
            $stmt->bindParam(':adminemail', $add_email);
            $stmt->bindParam(':mobile', $add_mobile);
            $stmt->bindParam(':adminpassword', $add_password);
            $stmt->bindParam(':Createdat', $currentDateTime);
            $stmt->bindParam(':status', $add_status);
            $stmt->bindParam(':adminpermission', $add_permission);
            
            if ($stmt->execute()) {
                $arr = array("status" => 200, "content" => "Success! Admin Added Successfully.");
            } else {
                $arr = array("status" => 400, "content" => "Error! Something went wrong. Try later.");
            }        
            echo json_encode($arr);
        } catch (PDOException $e) {
            $arr = array("status" => 400, "content" => "Database Error: " . $e->getMessage());
            echo json_encode($arr);
        }
        
    }else if($type == 'verifyEmail'){
        $user_email = "";
        if(isset($_POST['user_email']) && !empty($_POST['user_email'])){
            $user_email = $_POST['user_email'];
        }

        $checkSql = "select * from tbladmins where adminemail = :adminemail";
        $stmt = $conn->prepare($checkSql);
        $stmt->bindParam(':adminemail', $user_email);
        $stmt->execute();    
        if($stmt->rowCount() > 0){
            $arr = array("status" => 300, "content" => "Error! Email Already Exists.");
        }else{
            $arr = array("status" => 400, "content" => "Success! New Email.");
        }
        echo json_encode($arr);
    }
?>