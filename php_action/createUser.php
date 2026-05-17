<?php 	

require_once 'core.php';

$valid['success'] = array('success' => false, 'messages' => array());

if($_POST) {	

	$userName = trim($_POST['userName']);
	$upassword = $_POST['upassword'];
	$uemail = trim($_POST['uemail']);

	$passwordHash = password_hash($upassword, PASSWORD_DEFAULT);

	$stmt = $connect->prepare("INSERT INTO users (username, password, email) VALUES (?, ?, ?)");
	$stmt->bind_param('sss', $userName, $passwordHash, $uemail);

	if($stmt->execute() === TRUE) {
		$valid['success'] = true;
		$valid['messages'] = "Successfully Added";	
	} else {
		$valid['success'] = false;
		$valid['messages'] = "Error while adding the members";
	}

	$stmt->close();

} // if in_array

	echo json_encode($valid);
 
