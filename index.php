<?php 
require_once 'php_action/db_connect.php';

session_start();

if(isset($_SESSION['userId'])) {
	header('location:'.$store_url.'dashboard.php');		
}

$errors = array();
$currentPage = basename($_SERVER['PHP_SELF'], '.php');
$sharedCssFiles = array(
	'custom/css/shared/variables.css',
	'custom/css/shared/base.css',
	'custom/css/shared/layout.css',
	'custom/css/shared/components.css'
);
$pageCssFile = 'custom/css/pages/' . $currentPage . '.css';

if($_POST) {		

	$username = trim($_POST['username']);
	$password = $_POST['password'];

	if(empty($username) || empty($password)) {
		if($username == "") {
			$errors[] = "Username is required";
		} 

		if($password == "") {
			$errors[] = "Password is required";
		}
	} else {
		$stmt = $connect->prepare("SELECT user_id, password FROM users WHERE username = ?");
		$stmt->bind_param('s', $username);
		$stmt->execute();
		$stmt->store_result();

		if($stmt->num_rows == 1) {
			$stmt->bind_result($user_id, $hashedPassword);
			$stmt->fetch();

			$passwordValid = false;
			if (password_verify($password, $hashedPassword)) {
				$passwordValid = true;
			} elseif (md5($password) === $hashedPassword) {
				$passwordValid = true;
				$rehash = password_hash($password, PASSWORD_DEFAULT);
				$updateStmt = $connect->prepare("UPDATE users SET password = ? WHERE user_id = ?");
				$updateStmt->bind_param('si', $rehash, $user_id);
				$updateStmt->execute();
				$updateStmt->close();
			}

			if ($passwordValid) {
				$_SESSION['userId'] = $user_id;
				header('location:'.$store_url.'dashboard.php');
				exit;
			} else {
				$errors[] = "Incorrect username/password combination";
			}
		} else {
			$errors[] = "Username does not exist";
		}

		$stmt->close();
	}
} // /if $_POST
?>

<!DOCTYPE html>
<html>
<head>
	<script>
		(function() {
			try {
				var savedTheme = window.localStorage.getItem('simpleErpTheme');
				var theme = savedTheme === 'dark' ? 'dark' : 'light';
				document.documentElement.setAttribute('data-theme', theme);
			} catch (error) {
				document.documentElement.setAttribute('data-theme', 'light');
			}
		})();
	</script>
	<title>Stock Management System</title>

	<!-- bootstrap -->
	<link rel="stylesheet" href="assests/bootstrap/css/bootstrap.min.css">
	<!-- bootstrap theme-->
	<link rel="stylesheet" href="assests/bootstrap/css/bootstrap-theme.min.css">
	<!-- font awesome -->
	<link rel="stylesheet" href="assests/font-awesome/css/font-awesome.min.css">

  <!-- shared custom css -->
  <?php foreach ($sharedCssFiles as $cssFile) { ?>
  <link rel="stylesheet" href="<?php echo $cssFile; ?>">
  <?php } ?>
  <?php if (file_exists(__DIR__ . '/' . $pageCssFile)) { ?>
  <link rel="stylesheet" href="<?php echo $pageCssFile; ?>">
  <?php } ?>

  <!-- jquery -->
	<script src="assests/jquery/jquery.min.js"></script>
  <!-- jquery ui -->  
  <link rel="stylesheet" href="assests/jquery-ui/jquery-ui.min.css">
  <script src="assests/jquery-ui/jquery-ui.min.js"></script>

  <!-- bootstrap js -->
	<script src="assests/bootstrap/js/bootstrap.min.js"></script>
</head>
<body class="page-<?php echo htmlspecialchars($currentPage, ENT_QUOTES, 'UTF-8'); ?>">
	<div class="container">
		<div class="row vertical">
			<div class="col-md-5 col-md-offset-4">
				<div class="panel panel-info login-panel">
					<div class="panel-heading">
						<h3 class="panel-title">Please Sign in</h3>
					</div>
					<div class="panel-body">

						<div class="messages">
							<?php if($errors) {
								foreach ($errors as $key => $value) {
									echo '<div class="alert alert-warning" role="alert">
									<i class="glyphicon glyphicon-exclamation-sign"></i>
									'.$value.'</div>';										
									}
								} ?>
						</div>

						<form class="form-horizontal" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" id="loginForm">
							<fieldset>
							  <div class="form-group">
									<label for="username" class="col-sm-2 control-label">Username</label>
									<div class="col-sm-10">
									  <input type="text" class="form-control" id="username" name="username" placeholder="Username" autocomplete="off" />
									</div>
								</div>
								<div class="form-group">
									<label for="password" class="col-sm-2 control-label">Password</label>
									<div class="col-sm-10">
									  <input type="password" class="form-control" id="password" name="password" placeholder="Password" autocomplete="off" />
									</div>
								</div>								
								<div class="form-group">
									<div class="col-sm-offset-2 col-sm-10">
									  <button type="submit" class="btn btn-default"> <i class="glyphicon glyphicon-log-in"></i> Sign in</button>
									</div>
								</div>
							</fieldset>
						</form>
					</div>
					<!-- panel-body -->
				</div>
				<!-- /panel -->
			</div>
			<!-- /col-md-4 -->
		</div>
		<!-- /row -->
	</div>
	<!-- container -->	
</body>
</html>







	
