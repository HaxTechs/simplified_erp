<?php require_once 'includes/header.php'; ?>

<?php 

$sql = "SELECT * FROM product WHERE status = 1";
$query = $connect->query($sql);
$countProduct = $query->num_rows;

$orderSql = "SELECT * FROM orders WHERE order_status = 1";
$orderQuery = $connect->query($orderSql);
$countOrder = $orderQuery->num_rows;

$totalRevenue = 0;
while ($orderResult = $orderQuery->fetch_assoc()) {
	$totalRevenue += $orderResult['paid'];
}

$lowStockSql = "SELECT * FROM product WHERE quantity <= 3 AND status = 1";
$lowStockQuery = $connect->query($lowStockSql);
$countLowStock = $lowStockQuery->num_rows;

$userwisesql = "SELECT users.username , SUM(orders.grand_total) as totalorder FROM orders INNER JOIN users ON orders.user_id = users.user_id WHERE orders.order_status = 1 GROUP BY orders.user_id";
$userwiseQuery = $connect->query($userwisesql);
$userwieseOrder = $userwiseQuery->num_rows;

$connect->close();

?>

<!-- fullCalendar 2.2.5-->
    <link rel="stylesheet" href="assests/plugins/fullcalendar/fullcalendar.min.css">
    <link rel="stylesheet" href="assests/plugins/fullcalendar/fullcalendar.print.css" media="print">


<div class="row dashboard-grid">
	<?php if (isset($_SESSION['userId']) && $_SESSION['userId'] == 1) { ?>
	<div class="col-lg-4 col-sm-6">
		<a class="dashboard-stat-card dashboard-stat-card--success" href="product.php">
			<span class="dashboard-card__label">Inventory</span>
			<h2 class="dashboard-card__value"><?php echo number_format($countProduct); ?></h2>
			<span class="dashboard-card__meta">Total products in the catalog</span>
			<span class="dashboard-card__icon"><i class="glyphicon glyphicon-ruble"></i></span>
		</a>
	</div>

	<div class="col-lg-4 col-sm-6">
		<a class="dashboard-stat-card dashboard-stat-card--danger" href="product.php">
			<span class="dashboard-card__label">Attention</span>
			<h2 class="dashboard-card__value"><?php echo number_format($countLowStock); ?></h2>
			<span class="dashboard-card__meta">Products that are low on stock</span>
			<span class="dashboard-card__icon"><i class="glyphicon glyphicon-warning-sign"></i></span>
		</a>
	</div>
	<?php } ?>

	<div class="col-lg-4 col-sm-6">
		<a class="dashboard-stat-card dashboard-stat-card--primary" href="orders.php?o=manord">
			<span class="dashboard-card__label">Orders</span>
			<h2 class="dashboard-card__value"><?php echo number_format($countOrder); ?></h2>
			<span class="dashboard-card__meta">Total processed orders</span>
			<span class="dashboard-card__icon"><i class="glyphicon glyphicon-shopping-cart"></i></span>
		</a>
	</div>

	<div class="col-lg-4 col-sm-6">
		<div class="dashboard-note-card">
			<span class="dashboard-card__label">Today</span>
			<h2 class="dashboard-card__value"><?php echo date('d'); ?></h2>
			<span class="dashboard-card__meta"><?php echo date('l') . ', ' . date('F d, Y'); ?></span>
			<span class="dashboard-card__icon"><i class="glyphicon glyphicon-calendar"></i></span>
		</div>
	</div>

	<div class="col-lg-4 col-sm-6">
		<div class="dashboard-stat-card dashboard-stat-card--warning">
			<span class="dashboard-card__label">Revenue</span>
			<h2 class="dashboard-card__value">INR <?php echo number_format((float) $totalRevenue, 2); ?></h2>
			<span class="dashboard-card__meta">Total paid amount across orders</span>
			<span class="dashboard-card__icon"><i class="glyphicon glyphicon-stats"></i></span>
		</div>
	</div>
</div>

<?php if (isset($_SESSION['userId']) && $_SESSION['userId'] == 1) { ?>
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				<i class="glyphicon glyphicon-user"></i> User Wise Order
			</div>
			<div class="panel-body">
				<table class="table dashboard-user-orders" id="productTable">
			  	<thead>
			  		<tr>
			  			<th>Name</th>
			  			<th>Orders in Rupees</th>
			  		</tr>
			  	</thead>
			  	<tbody>
					<?php while ($orderResult = $userwiseQuery->fetch_assoc()) { ?>
						<tr>
							<td><?php echo $orderResult['username']; ?></td>
							<td><?php echo number_format((float) $orderResult['totalorder'], 2); ?></td>
						</tr>
					<?php } ?>
				</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
<?php } ?>

<!-- fullCalendar 2.2.5 -->
<script src="assests/plugins/moment/moment.min.js"></script>
<script src="assests/plugins/fullcalendar/fullcalendar.min.js"></script>


<script type="text/javascript">
	$(function () {
			// top bar active
	$('#navDashboard').addClass('active');

      //Date for the calendar events (dummy data)
      var date = new Date();
      var d = date.getDate(),
      m = date.getMonth(),
      y = date.getFullYear();

      $('#calendar').fullCalendar({
        header: {
          left: '',
          center: 'title'
        },
        buttonText: {
          today: 'today',
          month: 'month'          
        }        
      });


    });
</script>

<?php require_once 'includes/footer.php'; ?>
