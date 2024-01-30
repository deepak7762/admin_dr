<?php 
    include "databaseConnect.php";

    $callType = "";
    if(isset($_POST['callType']) && !empty($_POST['callType'])){
        $callType = $_POST['callType'];
    }

    if($callType == "registerUser"){

        $Uname = "";
        if(isset($_POST['name_']) && !empty($_POST['name_'])){
            $Uname = $_POST['name_'];
        }else{
            header("location:new-user.php");
        }
    
        $Uemail = "";
        if(isset($_POST['email_']) && !empty($_POST['email_'])){
            $Uemail = $_POST['email_'];
        }else{
            header("location:new-user.php");
        }
    
        $Uphone = "";
        if(isset($_POST['phone_']) && !empty($_POST['phone_'])){
            $Uphone = $_POST['phone_'];
        }else{
            header("location:new-user.php");
        }
    
        $Utype = "";
        if(isset($_POST['type_']) && !empty($_POST['type_'])){
            $Utype = $_POST['type_'];
        }else{
            header("location:new-user.php");
        }
    
        $Upassword = "";
        if(isset($_POST['password_']) && !empty($_POST['password_'])){
            $Upassword = $_POST['password_'];
        }else{
            header("location:new-user.php");
        }
    
        $com_pass = "";
        if(isset($_POST['c_password_']) && !empty($_POST['c_password_'])){
            $com_pass = $_POST['c_password_'];
        }else{
            header("location:new-user.php");
        }

        if($Upassword != $com_pass){
            header("location:new-user.php");
        }

        date_default_timezone_set("Asia/Kolkata");
        $currentDateTime = date("Y-m-d H:i:s");
        $arr = array();

        $insertSql = "INSERT INTO tbladmins (adminname, adminemail, mobile, adminpassword, Createdat, status, adminpermission) VALUES (:Uname, :Uemail, :Uphone, :com_pass, :currentDateTime, 1, :Utype)";
        $stmt = $conn->prepare($insertSql);
        $stmt->bindParam(':Uname', $Uname);
        $stmt->bindParam(':Uemail', $Uemail);
        $stmt->bindParam(':Uphone', $Uphone);
        $stmt->bindParam(':com_pass', $com_pass);
        $stmt->bindParam(':currentDateTime', $currentDateTime);
        $stmt->bindParam(':Utype', $Utype);

        if($stmt->execute()){
            $arr = array(
                'status' => 200, 'message' => 'success', 'content' => 'Success! User Registered Successfully.'
            );
        }else{
            $arr = array(
                'status' => 400, 'message' => 'failed', 'content' => 'Error! User Registration Failed.'
            );
        }
        echo json_encode($arr);

    }else if($callType == "checkEmail"){
        $Uemail = "";
        if(isset($_POST['email_']) && !empty($_POST['email_'])){
            $Uemail = $_POST['email_'];
        }

        $checkSql = "SELECT adminemail FROM tbladmins WHERE adminemail = :Uemail";
        $stmt = $conn->prepare($checkSql);
        $stmt->bindParam(':Uemail', $Uemail);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            $arr = array(
                'status' => 200, 'message' => 'already', 'content' => 'Error! Email Already Exists.'
            );
        }else{
            $arr = array(
                'status' => 400, 'message' => 'newEmail', 'content' => 'Success! Email in new.'
            );
        }
        echo json_encode($arr);
    }

    
?>