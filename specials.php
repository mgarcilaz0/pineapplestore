<?php
$page_title = "Pineapple Inc.";
include('includes/header.php');

require_once('includes/database.php');

//SELECT statement
$sql = "SELECT * FROM products where is_special=true ORDER BY created_at DESC";

//execute the query
$query = $conn->query($sql);


//Handle errors
if (!$query) {
    $errno = $conn->errno;
    $error = $conn->error;
    $conn->close();
    require 'includes/footer.php';
    die("Selection failed: ($errno) $error.");
}
?>
<!DOCTYPE HTML>
<html lang="en-us">
<meta name="viewport" content="width=device-width, initial-scale=1">

<head>
    <link rel="stylesheet" type="text/css" href="includes/style.css">
    <title>Specials Page</title>
</head>

<body>
    <div class="bg-success p-5 rounded">
        <div class="container">
            <h1 class="text-white">Special Products</h1>
        </div>
    </div>
    <div class="container">

        <div class="row">
            <?php
            while ($row = $query->fetch_assoc()) {
            ?>
                <div class="col-md-3 my-4 text-center" style="cursor: pointer;">
                    <div class="card" style="width: 18rem;">
                        <img src="<?php echo $row['product_img']; ?>" class="card-img-top" alt="...">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo getProductName($row['product_name']) ?></h5>
                            <p class="card-text"><?php echo substr($row['product_description'], 0, 80) ?>...</p>
                            <h6 class="st">In Stock: <strong><?php echo $row['in_stock'] > 5 ? 'Yes' : 'Low in Stock'; ?></strong></h6>
                            <h5 class="st">Price: $<strong><?php echo number_format($row['product_price'], 2); ?></strong></h5>
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
                <!-- echo "<div class ='img'><a href='productdetails.php?='><img alt='' src = ", $row['product_img'], "</a>";
                        echo "<div class = 'info'>";
                        echo "<a href =productdetails.php?id=", $row['id'], ">", $row['product_name'] . "</a>";
                        echo "<p>", $row['product_description'], "</p>";
                        echo "<div class = 'price'>";
                        echo "<span class = 'st'> Our price: </span><strong>", $row['product_price'], "</strong>";
                        echo "</div>";
                        echo "<div class = 'actions'>";
                        echo "<a href = product.php", $row['product_type'], "</a>";-->
            <?php
            }
            ?>
        </div>
    </div>
</body>
<?php
include('includes/footer.php');
?>

</html>