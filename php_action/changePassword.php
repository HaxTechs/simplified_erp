<?php 

require_once 'core.php';

if($_POST) {

    $valid['success'] = array('success' => false, 'messages' => array());

    $currentPassword = $_POST['password'];
    $newPassword = $_POST['npassword'];
    $confirmPassword = $_POST['cpassword'];
    $userId = (int) $_POST['user_id'];

    $stmt = $connect->prepare("SELECT password FROM users WHERE user_id = ?");
    $stmt->bind_param('i', $userId);
    $stmt->execute();
    $stmt->bind_result($hashedPassword);
    $stmt->fetch();
    $stmt->close();

    $passwordValid = false;
    if (password_verify($currentPassword, $hashedPassword)) {
        $passwordValid = true;
    } elseif (md5($currentPassword) === $hashedPassword) {
        $passwordValid = true;
    }

    if ($passwordValid) {
        if ($newPassword === $confirmPassword) {
            $newHash = password_hash($newPassword, PASSWORD_DEFAULT);
            $updateStmt = $connect->prepare("UPDATE users SET password = ? WHERE user_id = ?");
            $updateStmt->bind_param('si', $newHash, $userId);

            if($updateStmt->execute() === TRUE) {
                $valid['success'] = true;
                $valid['messages'] = "Successfully Updated";
            } else {
                $valid['success'] = false;
                $valid['messages'] = "Error while updating the password";
            }

            $updateStmt->close();
        } else {
            $valid['success'] = false;
            $valid['messages'] = "New password does not match with Confirm password";
        }
    } else {
        $valid['success'] = false;
        $valid['messages'] = "Current password is incorrect";
    }

    $connect->close();

    echo json_encode($valid);
}

?>