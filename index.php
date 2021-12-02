<?php
$page_title = "Pineapple Inc.";
include('includes/header.php');

require_once('includes/database.php');

//SELECT statement
$sql = "SELECT * FROM products";

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

// Add Cart
if (isset($_POST['add_to_cart'])) {
    $title = $_POST['product_title'];
    $quantity = $_POST['quantity'];
    $image = $_POST['product_image'];
    $price = $_POST['price'];

    $cartKey = $title . "_" . $price;
    $itemArray = array($cartKey => ["title" => $title, "quantity" => $quantity, "image" => $image, "price" => (float) $price]);

    if (!empty($_SESSION["cart_item"])) {
        if (in_array($cartKey, array_keys($_SESSION["cart_item"]))) {
            foreach ($_SESSION["cart_item"] as $k => $v) {
                if ($cartKey == $k) {
                    if (empty($_SESSION["cart_item"][$k]["quantity"])) {
                        $_SESSION["cart_item"][$k]["quantity"] = 0;
                    }
                    $_SESSION["cart_item"][$k]["quantity"] += $quantity;
                }
            }
        } else {
            $_SESSION["cart_item"] = array_merge($_SESSION["cart_item"], $itemArray);
        }
    } else {
        $_SESSION["cart_item"] = $itemArray;
    }
    echo "<script>alert('Item added to Cart Successfully'); </script>";
}

?>
<!DOCTYPE HTML>
<html lang="en-us">
<meta name="viewport" content="width=device-width, initial-scale=1">

<head>
    <link rel="stylesheet" type="text/css" href="includes/style.css">
    <title></title>
</head>

<body>
<div id="slider">
    <!-- Defining the main content section -->
    <!-- Promo slider -->
    <section id="slider-wrapper">
        <!-- Flickity HTML init -->
        <div class="slideshow">
            <!-- carousel control buttons -->
            <button class="slide-btn slide-btn-1"></button>
            <button class="slide-btn slide-btn-2"></button>
            <button class="slide-btn slide-btn-3"></button>
            <button class="slide-btn slide-btn-4"></button>
            <!-- carousel wrapper which contains all images -->
            <div class="slideshow-wrapper">
                <div class="slide">
                    <img class="slide-img" src="https://images.prismic.io/teenageengineering/0019cc38-a796-4cec-b012-b0820b570ef5_computer-1_store_front+%281%29.png?auto=compress,format&w=512&h=1">
                </div>
                <div class="slide">
                    <img class="slide-img" src="https://images.prismic.io/teenageengineering/0019cc38-a796-4cec-b012-b0820b570ef5_computer-1_store_front+%281%29.png?auto=compress,format&w=1080&h=1">
                </div>
                <div class="slide">
                    <img class="slide-img" src="https://images.prismic.io/teenageengineering/5f5a139a-fe12-4e6c-bce1-b8a5b484d169_computer-1_store_back.png?auto=compress,format&w=1080&h=1">
                </div>
                <div class="slide">
                    <img class="slide-img" src="https://images.prismic.io/teenageengineering/d379b4fd-3721-4cd4-bec2-f3a7c13e099f_computer-1_store_layout.png?auto=compress,format&w=1080&h=1">
                </div>
            </div>
        </div>
</div>

<div id="htmlcaption-1" class="nivo-html-caption">

    <p style="text-align: -webkit-center"> A community for PC builders and modders. A great place to
        find your next affordable rig. </p>
    <p style="text-align: -webkit-center">Our mission is to allow computer enthusiasts no matter expertise levels to allow for a jump start into building their own personalized PC </p>

    </section>
</div>
    <section class="row justify-content-center">
        <div class="col-md-8">
            <h1 class="text-center my-4">Products</h1>
            <hr />
            <div class="row">
                <?php
                while ($row = $query->fetch_assoc()) {
                ?>
                    <div class="col-md-3 my-4 text-center">
                        <div class="card" style="width: 18rem;">
                            <img src="<?php echo $row['product_img']; ?>" class="card-img-top" alt="...">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo getProductName($row['product_name']) ?></h5>
                                <p class="card-text"><?php echo substr($row['product_description'], 0, 80) ?>...</p>
                                <h6 class="st">In Stock: <strong><?php echo $row['in_stock'] > 5 ? 'Yes' : 'Low in Stock'; ?></strong></h6>
                                <h5 class="st">Price: $<strong><?php echo number_format($row['product_price'],2); ?></strong></h5>
                                <form action="" method="POST">
                                    <input type="hidden" name="quantity" value="1">
                                    <input type="hidden" name="product_title" value="<?php echo getProductName($row['product_name']); ?>" />
                                    <input type="hidden" name="price" value="<?php echo $row['product_price']; ?>" />
                                    <input type="hidden" name="product_image" value="<?php echo $row['product_img']; ?>" />
                                    <button class="btn btn-success" name="add_to_cart">Add to Cart</button>
                                </form>
                            </div>
                            <div class="card-footer">
                                <a href="productdetails.php?id=<?php echo $row['id']; ?>">View Product Details</a>
                            </div>
                        </div>
                    </div>
                <?php
                }
                ?>
            </div>
        </div>
    </section>
    <div class="bg-success p-5 rounded">
        <div class="container">
            <h1 class="text-white">Special Products</h1>
            <a href="specials.php" class="btn btn-warning">See our Specials Page</a>
        </div>
    </div>
</body>
<?php
include('includes/footer.php');
?>

</html>