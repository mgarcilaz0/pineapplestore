<?php
$page_title = "Pineapple Inc.";
require_once('includes/header.php');
require_once('includes/database.php');

?>
<!DOCTYPE HTML>
<html lang="en-us">
<meta name="viewport" content="width=device-width, initial-scale=1">

<head>
	<link rel="stylesheet" type="text/css" href="includes/style.css">
	<title><?php $page_title ?></title>
</head>
<?php
//if product id cannot retrieved, terminate the script.
if (!filter_has_var(INPUT_GET, "id")) {
	$conn->close();
	require_once('includes/footer.php');
	die("Your request cannot be processed since there was a problem retrieving product id.");
}

//retrieve book id from a query string variable.
$id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_NUMBER_INT);

//MySQL SELECT statement
$sql = "SELECT * FROM products WHERE id=$id";

//execute the query
$query = @$conn->query($sql);

//Handle errors
if (!$query) {
	$errno = $conn->errno;
	$error = $conn->error;
	$conn->close();
	require 'includes/footer.php';
	die("Selection failed: ($errno) $error.");
}

if (!$row = $query->fetch_assoc()) {
	$conn->close();
	require 'includes/footer.php';
	die("Product not found.");
}
?>

<!-- <table id="bookdetails" class="bookdetails">
    <tr>
        <td class="col1">
            <img src="<?php echo $row[''] ?>" alt="" width="150">
        </td>
        <td class="col2">
            <h4>Name:</h4>
            <h4>Description:</h4>
            <h4>Quantity:</h4>
            <h4>Price:</h4>
        </td>
        <td class="col3">
            <p><?php echo $row['product_name'] ?></p>
            <p><?php echo $row['product_description'] ?></p>
            <p><?php echo $row['product_serialnumber'] ?></p>
            <p><?php echo $row['product_price'] ?></p>
        </td>
    </tr>
</table> -->
<div class="container mt-5 mb-5">
	<div class="row d-flex justify-content-center">
		<div class="col-md-12">
			<h2> Product Details </h2>
			<div class="card">
				<div class="row">
					<div class="col-md-6">
						<div class="images p-3">
							<div class="text-center p-4">
								<img id="main-image" src="<?php echo $row['product_img']; ?>" width="400" />
							</div>
							<!-- <div class="thumbnail text-center"> <img onclick="change_image(this)" src="https://i.imgur.com/Rx7uKd0.jpg" width="70">
								<img onclick="change_image(this)" src="https://i.imgur.com/Dhebu4F.jpg" width="70">
							</div> -->
						</div>
					</div>
					<div class="col-md-6">
						<div class="product p-4">

							<div class="mt-4 mb-3">
								<!-- <span class="text-uppercase text-muted brand">Orianz</span> -->
								<h5 class="text-uppercase"><?php echo $row['product_name'] ?></h5>
								<div class="price d-flex flex-row align-items-center">
									<h5 class="act-price">Price: <strong>$<?php echo number_format($row['product_price'], 2); ?></strong></h5>
									<!-- <div class="ml-2"> <small class="dis-price">$59</small> <span>40% OFF</span> </div> -->
								</div>
							</div>
							<p class="about">
								<?php echo $row['product_description']; ?>
							</p>
							<form action="add_cart.php" method="POST">
								<input type="hidden" name="quantity" value="1">
								<input type="hidden" name="product_title" value="<?php echo getProductName($row['product_name']); ?>" />
								<input type="hidden" name="price" value="<?php echo $row['product_price']; ?>" />
								<input type="hidden" name="product_image" value="<?php echo $row['product_img']; ?>" />
								<button class="btn btn-success" name="add_to_cart">Add to Cart</button>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php
require_once('includes/footer.php');
?>