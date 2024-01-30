<?php 
    session_start();
    include "databaseConnect.php";
    include 'send-mail/send-mail.php';

    $type = "";
    if(isset($_POST['actionType']) && !empty($_POST['actionType'])){
        $type = $_POST['actionType'];
    }

    if($type == "loginUser"){
        $userEmail = "";
        if(isset($_POST['userEmail']) && !empty($_POST['userEmail'])){
            $userEmail = $_POST['userEmail'];
        }else{
            header("location:login.php?err=2");
            exit();
        }

        $userPassword = "";
        if(isset($_POST['userPassword']) && !empty($_POST['userPassword'])){
            $userPassword = $_POST['userPassword'];
        }else{
            header("location:login.php?err=3");
            exit();
        } 

        date_default_timezone_set("Asia/Kolkata");
        $currentDateTime = date("Y-m-d H:i:s"); 
        try {
            $checkSql = "SELECT * FROM tbladmins WHERE adminemail = :adminemail AND adminpassword = :userPassword AND status = 1";
            $stmt = $conn->prepare($checkSql);
            $stmt->bindParam(':adminemail', $userEmail, PDO::PARAM_STR);
            $stmt->bindParam(':userPassword', $userPassword, PDO::PARAM_STR);
            $stmt->execute();
        
            if ($stmt->rowCount() > 0) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $_SESSION['userid'] = $row['id'];
                $_SESSION['username'] = $row['adminname'];
                $_SESSION['permissionType'] = $row['adminpermission'];

                if(isset($_POST['rememberMeCheck']) && $_POST['rememberMeCheck'] == 'on'){
                    setcookie('userEmail',$userEmail, time()+(60*60*24));
                    setcookie('userPassword',$userPassword, time()+(60*60*24));
                }                
                try {
                    $update = "UPDATE tbladmins SET lastlogindatetime = :currentDateTime WHERE id = :userId";
                    $stmt2 = $conn->prepare($update);
                    $stmt2->bindParam(':currentDateTime', $currentDateTime, PDO::PARAM_STR);
                    $stmt2->bindParam(':userId', $row['id'], PDO::PARAM_INT);
                    $stmt2->execute();
                } catch (PDOException $e) {
                    echo "Error: " . $e->getMessage();
                } 
                
                if($row['adminpermission'] == 3){
                    header('location:view-reports.php');
                } else {
                    header('location:index.php');
                }               

            } else {
                header("location: login.php?err=4");
                exit();
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    } elseif($type == "verifyEmailFrogotPass"){
        $verifyEmail = "";
        if(isset($_POST['emailforgotpassword']) && !empty($_POST['emailforgotpassword'])){
            $verifyEmail = $_POST['emailforgotpassword'];
        }else{
            header("location:forgot-password.php?err=5");
            exit();
        }

        try {
            $checkEmail = "SELECT * FROM tbladmins WHERE adminemail = :verifyEmail AND status = 1";
            $stmt = $conn->prepare($checkEmail);
            $stmt->bindParam(':verifyEmail', $verifyEmail, PDO::PARAM_STR);
            $stmt->execute();
        
            if ($stmt->rowCount() > 0) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $user__ = $row['id'];
                $_SESSION['verifiedUserId'] = $user__;
                $randomNumber = random_int(100000, 999999);
                date_default_timezone_set("Asia/Kolkata");
                $currentDateTime = date("Y-m-d H:i:s"); 
                $insertSql = "INSERT INTO otplog (requestuserid, requestemail, otp, otptype, generateddatetime) VALUES (:userId, :verifyEmail, :randomNumber, 2, :currentDateTime)";
                $insertStmt = $conn->prepare($insertSql);
                $insertStmt->bindParam(':userId', $user__, PDO::PARAM_INT);
                $insertStmt->bindParam(':verifyEmail', $verifyEmail, PDO::PARAM_STR);
                $insertStmt->bindParam(':randomNumber', $randomNumber, PDO::PARAM_INT);
                $insertStmt->bindParam(':currentDateTime', $currentDateTime, PDO::PARAM_STR);

                if($insertStmt->execute()){
                    $subject = "Reset Password Verification OTP";
                    $mailBody = "<p>Your Verification Code to Reset Password.</p><p>$randomNumber</p>";
                    $resp = send_mail($verifyEmail, $subject, $mailBody, $attachment='');
                    if($resp){
                        $_SESSION['verifyEmailresetPassword'] = $verifyEmail;
                        $_SESSION['verify-otp-page'] = "otp-verification-page";
                        header("location: verify-user.php?msg=200");
                        exit();
                    }else{
                        header("location: forgot-password.php?err=400");
                        exit();
                    }
                }else{
                    header("location: forgot-password.php?err=400");
                    exit();
                }               
            } else {
                header("location: forgot-password.php?err=6");
                exit();
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        
    } elseif($type == "resetPassword"){
        $newPassword = "";
        if(isset($_POST['newPassword']) && !empty($_POST['newPassword'])){
            $newPassword = $_POST['newPassword'];
        }else{
            header("location:reset-password.php?err=7");
            exit();
        }

        $confirmPassword = "";
        if(isset($_POST['confirmPassword']) && !empty($_POST['confirmPassword'])){
            $confirmPassword = $_POST['confirmPassword'];
        }else{
            header("location:reset-password.php?err=8");
            exit();
        } 

        if (strlen($newPassword) < 8) {
            header("location: reset-password.php?err=9");
            exit();
        }
        if($newPassword != $confirmPassword){
            header("location:reset-password.php?err=10");
            exit();
        }
        try {
            $updatePassSql = "UPDATE tbladmins SET adminpassword = :confirmPassword WHERE id = :userId AND status = 1";
            $stmt = $conn->prepare($updatePassSql);
            $stmt->bindParam(':confirmPassword', $confirmPassword, PDO::PARAM_STR);
            $stmt->bindParam(':userId', $_SESSION['verifiedUserId'], PDO::PARAM_INT);
            
            if ($stmt->execute()) {
                unset($_SESSION['verifiedUserId']);
                unset($_SESSION['reset-pass-page']);
                header("location: login.php?msg=1");
                exit(); 
            } else {
                header("location: reset-password.php?err=11");
                exit();
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }   
    } elseif($type == "verifyOTPFrogotPass"){
        $newOTP = "";
        if(isset($_POST['otpforgotpassword']) && !empty($_POST['otpforgotpassword'])){
            $newOTP = $_POST['otpforgotpassword'];
        }else{
            header("location:verify-user.php?err=1");
            exit();
        }

        $checkOTP = "SELECT otp FROM otplog WHERE requestemail = :requestemail AND otptype = 2 ORDER BY generateddatetime DESC LIMIT 1";
        $stmt = $conn->prepare($checkOTP);
        $stmt->bindParam(':requestemail', $_SESSION['verifyEmailresetPassword'], PDO::PARAM_STR);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if($newOTP == $row['otp']){
                $_SESSION['reset-pass-page'] = "reset-password-page";
                unset($_SESSION['verifyEmailresetPassword']);
                header("location: reset-password.php");
                exit(); 
            }else{
                header("location:verify-user.php?err=2");
                exit();
            }
        }
    }
?>
