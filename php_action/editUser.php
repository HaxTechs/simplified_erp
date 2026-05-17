<?php 	

require_once 'core.php';

$valid['success'] = array('success' => false, 'messages' => array());

if($_POST) {
    $edituserName = trim($_POST['edituserName']);
    $editPassword = $_POST['editPassword'];
    $userid = (int) $_POST['userid'];

    $passwordHash = password_hash($editPassword, PASSWORD_DEFAULT);

    $stmt = $connect->prepare("UPDATE users SET username = ?, password = ? WHERE user_id = ?");
    $stmt->bind_param('ssi', $edituserName, $passwordHash, $userid);

    if($stmt->execute() === TRUE) {
        $valid['success'] = true;
        $valid['messages'] = "Successfully Updated";
    } else {
        $valid['success'] = false;
        $valid['messages'] = "Error while updating user info";
    }

    $stmt->close();
}

$connect->close();

echo json_encode($valid);
 
