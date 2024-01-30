<?php
    session_start();
    include "databaseConnect.php";
    include 'send-mail/send-mail.php';
    
    $type = '';
    if(isset($_POST['type']) && !empty($_POST['type'])){
        $type = $_POST['type'];
    }

    $dr_id = '';
    if(isset($_POST['dr_id']) && !empty($_POST['dr_id'])){
        $dr_id = $_POST['dr_id'];
    }

    $p_email = '';
    if(isset($_POST['email_']) && !empty($_POST['email_'])){
        $p_email = $_POST['email_'];
    }

    $p_id = '';
    if(isset($_POST['id_']) && !empty($_POST['id_'])){
        $p_id = $_POST['id_'];
        $p_email = getPatientEmail($conn, $p_id, "");
    }

    $p_phone = '';
    if(isset($_POST['phone_']) && !empty($_POST['phone_'])){
        $p_phone = $_POST['phone_'];
        $p_email = getPatientEmail($conn, "", $p_phone);
    }
    $randomNumber = random_int(100000, 999999);
    date_default_timezone_set("Asia/Kolkata");
    $currentDateTime = date("Y-m-d H:i:s");
    $arr = array();
    if($type == "sendOTP"){
        if($p_email == false){
            $arr = array(
                "status" => 500, "message" => "failed", "content" => "Error! Wrong Credentials."
            );
        }else{
            $insertSql = "INSERT INTO otplog (requestuserid, requestemail, otp, otptype, generateddatetime)VALUES($dr_id, '$p_email', $randomNumber, 1, '$currentDateTime')" ;
            $insertStmt = $conn->query($insertSql);
            if($insertStmt){
                $subject = "Verification OTP";
                $mailBody = "<p>Your Verification Code.</p><p>$randomNumber</p>";
                $resp = send_mail($p_email, $subject, $mailBody, $attachment='');
                if($resp){
                    $_SESSION['verifyEmail'] = $p_email;
                    $arr = array(
                        "status" => 200, "message" => "success", "content" => "Success! OTP successfully sent on Registered Mail."
                    );
                }else{
                    $arr = array(
                        "status" => 400, "message" => "failed", "content" => "Error! Failed to send OTP."
                    );
                }
            }
        }
        echo json_encode($arr);
    }else if($type == 'verifyOTP'){
        $otp = '';
        if(isset($_POST['otp_']) && !empty($_POST['otp_'])){
            $otp = $_POST['otp_'];
        }
        if(isset($_SESSION['verifyEmail']) && !empty($_SESSION['verifyEmail'])){
            $p_email = $_SESSION['verifyEmail'];
        }else{
            $p_email = "";
        }
        $selectOtp = "SELECT otp FROM otplog WHERE requestemail = '$p_email' and otptype = 1 ORDER BY generateddatetime DESC LIMIT 1";
        $OTPstmt = $conn->query($selectOtp);
        if ($OTPstmt->rowCount() > 0) {
            $getOTP = $OTPstmt->fetch(PDO::FETCH_ASSOC);
            if($getOTP['otp'] == $otp){
                $arr = array(
                    "status" => 200, "message" => "success", "content" => "Success! OTP Verified Successfully."
                );
            }else{
                $arr = array(
                    "status" => 400, "message" => "failed", "content" => "Error! Wrong OTP Value."
                );
            }                       
        }else{
            $arr = array(
                "status" => 400, "message" => "failed", "content" => "Error! Failed to verify OTP."
            );
        }    
        echo json_encode($arr); 
    }

    function getPatientEmail($conn, $id, $getBYPhnoeNumber){
        if($id != ''){
            $selectSql = "select adminemail from tbladmins where id = '$id'";
        }else if($getBYPhnoeNumber != ''){
            $selectSql = "select adminemail from tbladmins where mobile = $getBYPhnoeNumber";
        }        
        $stmt = $conn->query($selectSql);
        if ($stmt->rowCount() > 0) {
            $row1 = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row1['adminemail'];
        }else{
            return false;
        }
        
    }
?>