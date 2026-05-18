<?php 	



require_once 'core.php';

$sql = "SELECT 
			product.product_id,
			product.product_name,
			product.product_image,
			product.quantity,
			product.cost_price,
			product.selling_price,
			product.active,
			product.status,
			brands.brand_name,
			categories.categories_name
		FROM product 
		INNER JOIN brands ON product.brand_id = brands.brand_id 
		INNER JOIN categories ON product.categories_id = categories.categories_id  
		WHERE product.status = 1";

$result = $connect->query($sql);

$output = array('data' => array());

if($result->num_rows > 0) { 

 while($row = $result->fetch_assoc()) {
 	$productId = $row['product_id'];
 	$active = $row['active'] == 1
		? "<label class='label label-success'>Available</label>"
		: "<label class='label label-danger'>Not Available</label>";

	$button = '<!-- Single button -->
	<div class="btn-group">
	  <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
	    Actions <span class="caret"></span>
	  </button>
		  <ul class="dropdown-menu">
		    <li><a type="button" data-toggle="modal" id="editProductModalBtn" data-target="#editProductModal" onclick="editProduct('.$productId.')"> <i class="glyphicon glyphicon-edit"></i> Edit Inventory Item</a></li>
		    <li><a type="button" data-toggle="modal" data-target="#removeProductModal" id="removeProductModalBtn" onclick="removeProduct('.$productId.')"> <i class="glyphicon glyphicon-trash"></i> Remove Inventory Item</a></li>       
		  </ul>
		</div>';

	$brand = $row['brand_name'];
	$category = $row['categories_name'];

	$imageUrl = substr($row['product_image'], 3);
	$productImage = "<img class='img-round' src='".$imageUrl."' style='height:30px; width:50px;'  />";

	$qty = (int)$row['quantity'];
	$qtyDisplay = $qty;
	if ($qty < 10) {
		$qtyDisplay = $qty . " <span class='label label-warning low-stock-badge'><i class=\"glyphicon glyphicon-warning-sign\"></i> Low</span>";
	}

 	$output['data'][] = array( 		
 		$productImage,
 		$row['product_name'], 
 		number_format((float)$row['cost_price'], 2),
 		number_format((float)$row['selling_price'], 2),
 		$qtyDisplay, 		 	
 		$brand,
 		$category,
 		$active,
 		$button 		
 		); 	
 } // /while 

}// if num_rows

$connect->close();

echo json_encode($output);
