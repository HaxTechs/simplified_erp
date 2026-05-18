<?php

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$localhost = "127.0.0.1";
$username = "erpuser";
$password = "password";
$dbname = "simple_erp";
$store_url = "http://localhost:8000/";

// db connection
$connect = new mysqli($localhost, $username, $password, $dbname);
$connect->set_charset('utf8mb4');

// check connection
if ($connect->connect_error) {
    die("Connection Failed: " . $connect->connect_error);
}

function ensureColumnExists($connect, $table, $column, $definition) {
    $column = $connect->real_escape_string($column);
    $table = $connect->real_escape_string($table);
    $result = $connect->query("SHOW COLUMNS FROM `{$table}` LIKE '{$column}'");

    if ($result->num_rows === 0) {
        $connect->query("ALTER TABLE `{$table}` ADD COLUMN `{$column}` {$definition}");
    }
}

function migratePricingSchema($connect) {
    ensureColumnExists($connect, 'product', 'cost_price', "DECIMAL(10,2) NOT NULL DEFAULT 0 AFTER `quantity`");
    ensureColumnExists($connect, 'product', 'selling_price', "DECIMAL(10,2) NOT NULL DEFAULT 0 AFTER `cost_price`");

	    $connect->query(
	        "UPDATE `product`
	         SET `cost_price` = CASE
	                WHEN `cost_price` IS NULL OR `cost_price` = 0
	                THEN CAST(COALESCE(NULLIF(`rate`, ''), '0') AS DECIMAL(10,2))
	                ELSE `cost_price`
	             END,
	             `selling_price` = CASE
	                WHEN `selling_price` IS NULL OR `selling_price` = 0
	                THEN CAST(COALESCE(NULLIF(`rate`, ''), '0') AS DECIMAL(10,2))
	                ELSE `selling_price`
	             END"
	    );

    ensureColumnExists($connect, 'order_item', 'cost_price', "DECIMAL(10,2) NOT NULL DEFAULT 0 AFTER `quantity`");
    ensureColumnExists($connect, 'order_item', 'selling_price', "DECIMAL(10,2) NOT NULL DEFAULT 0 AFTER `cost_price`");
    ensureColumnExists($connect, 'order_item', 'profit', "DECIMAL(10,2) NOT NULL DEFAULT 0 AFTER `total`");

	    $connect->query(
	        "UPDATE `order_item`
	         SET `selling_price` = CASE
	                WHEN `selling_price` IS NULL OR `selling_price` = 0
	                THEN CAST(COALESCE(NULLIF(`rate`, ''), '0') AS DECIMAL(10,2))
                ELSE `selling_price`
             END,
             `cost_price` = CASE
                WHEN `cost_price` IS NULL OR `cost_price` = 0
                THEN CAST(COALESCE(NULLIF(`rate`, ''), '0') AS DECIMAL(10,2))
                ELSE `cost_price`
             END,
             `profit` = ROUND(
                (
                    (CASE
                        WHEN `selling_price` IS NULL OR `selling_price` = 0
                        THEN CAST(COALESCE(NULLIF(`rate`, ''), '0') AS DECIMAL(10,2))
                        ELSE `selling_price`
                     END)
                    -
                    (CASE
                        WHEN `cost_price` IS NULL OR `cost_price` = 0
                        THEN CAST(COALESCE(NULLIF(`rate`, ''), '0') AS DECIMAL(10,2))
                        ELSE `cost_price`
	                     END)
	                ) * CAST(COALESCE(NULLIF(`quantity`, ''), '0') AS DECIMAL(10,2)),
	                2
	             )"
	    );
}

migratePricingSchema($connect);

?>
