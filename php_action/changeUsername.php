<?php 

require_once 'core.php';

if($_POST) {

    $valid['success'] = array('success' => false, 'messages' => array());

    $username = trim($_POST['username']);
    $userId = (int) $_POST['user_id'];

    $stmt = $connect->prepare("UPDATE users SET username = ? WHERE user_id = ?");
    $stmt->bind_param('si', $username, $userId);

    if($stmt->execute() === TRUE) {
        $valid['success'] = true;
        $valid['messages'] = "Successfully Updated";
    } else {
        $valid['success'] = false;
        $valid['messages'] = "Error while updating username";
    }

    $stmt->close();
    $connect->close();

    echo json_encode($valid);
}

?>