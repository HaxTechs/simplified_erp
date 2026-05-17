<?php require_once 'php_action/core.php'; ?>
<?php
$isAdmin = isset($_SESSION['userId']) && (int) $_SESSION['userId'] === 1;
$currentPage = basename($_SERVER['PHP_SELF'], '.php');
$sharedCssFiles = array(
	'custom/css/shared/variables.css',
	'custom/css/shared/base.css',
	'custom/css/shared/layout.css',
	'custom/css/shared/components.css'
);
$pageCssFile = 'custom/css/pages/' . $currentPage . '.css';
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
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
  <?php if (file_exists(dirname(__DIR__) . '/' . $pageCssFile)) { ?>
  <link rel="stylesheet" href="<?php echo $pageCssFile; ?>">
  <?php } ?>

	<!-- DataTables -->
  <link rel="stylesheet" href="assests/plugins/datatables/jquery.dataTables.min.css">

  <!-- file input -->
  <link rel="stylesheet" href="assests/plugins/fileinput/css/fileinput.min.css">

  <!-- jquery -->
	<script src="assests/jquery/jquery.min.js"></script>
  <!-- jquery ui -->  
  <link rel="stylesheet" href="assests/jquery-ui/jquery-ui.min.css">
  <script src="assests/jquery-ui/jquery-ui.min.js"></script>

  <!-- bootstrap js -->
	<script src="assests/bootstrap/js/bootstrap.min.js"></script>

</head>
<body class="app-layout page-<?php echo htmlspecialchars($currentPage, ENT_QUOTES, 'UTF-8'); ?>">
	<div class="app-shell">
		<aside class="app-sidebar" id="appSidebar">
			<div class="sidebar-brand">
				<a class="sidebar-brand__link" href="dashboard.php">
					<span class="sidebar-brand__mark">
						<img src="logo.png" alt="Simple ERP logo">
					</span>
					<span class="sidebar-brand__text">
						<strong>Simple ERP</strong>
						<small>Admin workspace</small>
					</span>
				</a>
			</div>

			<div class="sidebar-nav">
				<div class="sidebar-section">
					<div class="sidebar-section__eyebrow">Overview</div>
					<ul class="sidebar-menu">
						<li id="navDashboard">
							<a href="dashboard.php">
								<i class="glyphicon glyphicon-list-alt"></i>
								<span>Dashboard</span>
							</a>
						</li>
					</ul>
				</div>

				<?php if ($isAdmin) { ?>
				<div class="sidebar-section">
					<div class="sidebar-section__eyebrow">Catalog</div>
					<ul class="sidebar-menu">
						<li id="navBrand">
							<a href="brand.php">
								<i class="glyphicon glyphicon-btc"></i>
								<span>Brand</span>
							</a>
						</li>
						<li id="navCategories">
							<a href="categories.php">
								<i class="glyphicon glyphicon-th-list"></i>
								<span>Category</span>
							</a>
						</li>
						<li id="navProduct">
							<a href="product.php">
								<i class="glyphicon glyphicon-ruble"></i>
								<span>Product</span>
							</a>
						</li>
					</ul>
				</div>
				<?php } ?>

				<div class="sidebar-section">
					<div class="sidebar-section__eyebrow sidebar-section__eyebrow--with-icon" id="navOrder">
						<i class="glyphicon glyphicon-shopping-cart"></i>
						<span>Orders</span>
					</div>
					<ul class="sidebar-menu sidebar-menu--nested">
						<li id="topNavAddOrder">
							<a href="orders.php?o=add">
								<i class="glyphicon glyphicon-plus"></i>
								<span>Add Orders</span>
							</a>
						</li>
						<li id="topNavManageOrder">
							<a href="orders.php?o=manord">
								<i class="glyphicon glyphicon-edit"></i>
								<span>Manage Orders</span>
							</a>
						</li>
					</ul>
				</div>

				<?php if ($isAdmin) { ?>
				<div class="sidebar-section">
					<div class="sidebar-section__eyebrow">Insights</div>
					<ul class="sidebar-menu">
						<li id="navReport">
							<a href="report.php">
								<i class="glyphicon glyphicon-stats"></i>
								<span>Reports</span>
							</a>
						</li>
						<li id="importbrand">
							<a href="importbrand.php">
								<i class="glyphicon glyphicon-import"></i>
								<span>Import Brand</span>
							</a>
						</li>
					</ul>
				</div>
				<?php } ?>

				<div class="sidebar-section sidebar-section--footer">
					<div class="sidebar-section__eyebrow sidebar-section__eyebrow--with-icon" id="navSetting">
						<i class="glyphicon glyphicon-user"></i>
						<span>Account</span>
					</div>
					<ul class="sidebar-menu sidebar-menu--nested">
						<?php if ($isAdmin) { ?>
						<li id="topNavSetting">
							<a href="setting.php">
								<i class="glyphicon glyphicon-wrench"></i>
								<span>Settings</span>
							</a>
						</li>
						<li id="topNavUser">
							<a href="user.php">
								<i class="glyphicon glyphicon-briefcase"></i>
								<span>Users</span>
							</a>
						</li>
						<?php } ?>
						<li id="topNavLogout">
							<a href="logout.php">
								<i class="glyphicon glyphicon-log-out"></i>
								<span>Logout</span>
							</a>
						</li>
					</ul>
				</div>
			</div>
		</aside>

		<div class="app-overlay" id="appOverlay"></div>

		<div class="app-main">
			<header class="app-topbar">
				<button type="button" class="sidebar-toggle" id="sidebarToggle" aria-label="Toggle sidebar">
					<i class="fa fa-bars"></i>
				</button>
				<div class="app-topbar__title">
					<h1>Stock Management System</h1>
					<p>Modernized admin panel layout</p>
				</div>
				<div class="app-topbar__actions">
					<button type="button" class="app-theme-toggle" id="themeToggle" aria-label="Switch to dark mode" title="Switch to dark mode">
						<i class="fa fa-moon-o" id="themeToggleIcon"></i>
					</button>
					<a class="app-topbar__logout" href="logout.php">
						<i class="glyphicon glyphicon-log-out"></i>
						<span>Logout</span>
					</a>
				</div>
			</header>

			<main class="app-content">
