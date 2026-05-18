<?php require_once 'includes/header.php'; ?>

<?php 

$sql = "SELECT * FROM product WHERE status = 1 AND active = 1";
$query = $connect->query($sql);
$countInventory = $query->num_rows;

$orderSql = "SELECT * FROM orders WHERE order_status = 1";
$orderQuery = $connect->query($orderSql);
$countSales = $orderQuery->num_rows;

$totalRevenue = 0;
while ($orderResult = $orderQuery->fetch_assoc()) {
	$totalRevenue += $orderResult['grand_total'];
}

$profitSql = "SELECT COALESCE(SUM(order_item.profit), 0) AS total_profit
	FROM order_item
	INNER JOIN orders ON orders.order_id = order_item.order_id
	WHERE order_item.order_item_status = 1 AND orders.order_status = 1";
$profitResult = $connect->query($profitSql);
$totalProfit = 0;
if ($profitResult && $profitResult->num_rows > 0) {
	$totalProfit = (float) $profitResult->fetch_assoc()['total_profit'];
}

$lowStockSql = "SELECT * FROM product WHERE quantity < 10 AND status = 1 AND active = 1";
$lowStockQuery = $connect->query($lowStockSql);
$countLowStock = $lowStockQuery->num_rows;

$lowStockSummarySql = "SELECT product_name, quantity, selling_price
	FROM product
	WHERE quantity < 10 AND status = 1 AND active = 1
	ORDER BY CAST(quantity AS UNSIGNED) ASC, product_name ASC
	LIMIT 5";
$lowStockSummaryQuery = $connect->query($lowStockSummarySql);

$userwisesql = "SELECT users.username , SUM(orders.grand_total) as totalorder FROM orders INNER JOIN users ON orders.user_id = users.user_id WHERE orders.order_status = 1 GROUP BY orders.user_id";
$userwiseQuery = $connect->query($userwisesql);

$connect->close();

?>

<!-- fullCalendar 2.2.5-->
    <link rel="stylesheet" href="assests/plugins/fullcalendar/fullcalendar.min.css">
    <link rel="stylesheet" href="assests/plugins/fullcalendar/fullcalendar.print.css" media="print">


<div class="row dashboard-grid">
	<?php if (isset($_SESSION['userId']) && $_SESSION['userId'] == 1) { ?>
		<div class="col-lg-4 col-sm-6">
			<a class="dashboard-stat-card dashboard-stat-card--success" href="product.php">
					<span class="dashboard-card__label">Inventaire</span>
				<h2 class="dashboard-card__value"><?php echo number_format($countInventory); ?></h2>
					<span class="dashboard-card__meta">Total des articles en inventaire</span>
				<span class="dashboard-card__icon"><i class="glyphicon glyphicon-ruble"></i></span>
			</a>
		</div>

		<div class="col-lg-4 col-sm-6">
			<a class="dashboard-stat-card dashboard-stat-card--danger" href="product.php">
					<span class="dashboard-card__label">Stock faible</span>
				<h2 class="dashboard-card__value"><?php echo number_format($countLowStock); ?></h2>
					<span class="dashboard-card__meta">Articles à réapprovisionner</span>
				<span class="dashboard-card__icon"><i class="glyphicon glyphicon-warning-sign"></i></span>
			</a>
		</div>
	<?php } ?>

		<div class="col-lg-4 col-sm-6">
			<a class="dashboard-stat-card dashboard-stat-card--primary" href="orders.php?o=manord">
					<span class="dashboard-card__label">Ventes</span>
				<h2 class="dashboard-card__value"><?php echo number_format($countSales); ?></h2>
					<span class="dashboard-card__meta">Total des ventes enregistrées</span>
				<span class="dashboard-card__icon"><i class="glyphicon glyphicon-shopping-cart"></i></span>
			</a>
		</div>

	<div class="col-lg-4 col-sm-6">
		<div class="dashboard-note-card">
				<span class="dashboard-card__label">Aujourd'hui</span>
			<h2 class="dashboard-card__value"><?php echo date('d'); ?></h2>
			<span class="dashboard-card__meta"><?php echo date('l') . ', ' . date('F d, Y'); ?></span>
			<span class="dashboard-card__icon"><i class="glyphicon glyphicon-calendar"></i></span>
		</div>
	</div>

		<div class="col-lg-4 col-sm-6">
			<div class="dashboard-stat-card dashboard-stat-card--warning">
					<span class="dashboard-card__label">Chiffre d'affaires</span>
				<h2 class="dashboard-card__value">CFA <?php echo number_format((float) $totalRevenue, 2); ?></h2>
					<span class="dashboard-card__meta">Total des ventes encaissées</span>
				<span class="dashboard-card__icon"><i class="glyphicon glyphicon-stats"></i></span>
			</div>
		</div>

		<div class="col-lg-4 col-sm-6">
			<div class="dashboard-stat-card dashboard-stat-card--success">
					<span class="dashboard-card__label">Marge brute</span>
				<h2 class="dashboard-card__value">CFA <?php echo number_format($totalProfit, 2); ?></h2>
					<span class="dashboard-card__meta">Bénéfice estimé sur les ventes enregistrées</span>
				<span class="dashboard-card__icon"><i class="glyphicon glyphicon-piggy-bank"></i></span>
			</div>
		</div>
	</div>

	<?php if (isset($_SESSION['userId']) && $_SESSION['userId'] == 1) { ?>
	<div class="row">
		<div class="col-lg-5">
			<div class="panel panel-default dashboard-summary-panel">
				<div class="panel-heading">
						<i class="glyphicon glyphicon-warning-sign"></i> Alertes de stock
				</div>
				<div class="panel-body">
					<?php if ($countLowStock > 0) { ?>
					<ul class="dashboard-alert-list">
						<?php while ($lowStockItem = $lowStockSummaryQuery->fetch_assoc()) { ?>
						<li>
								<div>
									<strong><?php echo htmlspecialchars($lowStockItem['product_name']); ?></strong>
										<span class="dashboard-alert-list__meta">Prix de vente : CFA <?php echo number_format((float) $lowStockItem['selling_price'], 2); ?></span>
								</div>
								<div class="dashboard-alert-list__status">
										<span class="low-stock-badge"><i class="glyphicon glyphicon-warning-sign"></i> Stock faible</span>
									<span class="dashboard-alert-list__qty"><?php echo (int) $lowStockItem['quantity']; ?></span>
								</div>
							</li>
							<?php } ?>
						</ul>
					<?php } else { ?>
						<p class="dashboard-empty-state">Aucun article en stock faible pour le moment.</p>
					<?php } ?>
				</div>
			</div>
		</div>

		<div class="col-lg-7">
			<div class="panel panel-default">
				<div class="panel-heading">
						<i class="glyphicon glyphicon-user"></i> Ventes par utilisateur
				</div>
				<div class="panel-body">
					<table class="table dashboard-user-orders" id="productTable">
				  	<thead>
				  		<tr>
					  			<th>Nom</th>
					  			<th>Montant des ventes</th>
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
