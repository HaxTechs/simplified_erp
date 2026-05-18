<?php 	

require_once 'core.php';

$valid['success'] = array('success' => false, 'messages' => array());

if($_POST) {
	$productId = $_POST['productId'];
	$productName 		= $_POST['editProductName']; 
  $quantity 			= $_POST['editQuantity'];
  $costPrice 		= $_POST['editCostPrice'];
  $sellingPrice 	= $_POST['editSellingPrice'];
  $brandName 			= $_POST['editBrandName'];
  $categoryName 	= $_POST['editCategoryName'];
  $productStatus 	= $_POST['editProductStatus'];

				
	$sql = "UPDATE product SET product_name = '$productName', brand_id = '$brandName', categories_id = '$categoryName', quantity = '$quantity', cost_price = '$costPrice', selling_price = '$sellingPrice', rate = '$sellingPrice', active = '$productStatus', status = 1 WHERE product_id = $productId ";

	if($connect->query($sql) === TRUE) {
		$valid['success'] = true;
		$valid['messages'] = "Inventory item updated successfully";	
	} else {
		$valid['success'] = false;
		$valid['messages'] = "Error while updating inventory item";
	}

} // /$_POST
	 
$connect->close();

echo json_encode($valid);
 
