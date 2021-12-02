<?php
$page_title = "Pineapple Inc.";
require_once('includes/header.php');
require_once('includes/database.php');

$err = '';
$success = "";
//start session if it has not already started
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}

if (isset($_POST['remove_cart'])) {
  $title = $_POST['product_key'];
  if (!empty($_SESSION["cart_item"])) {
    foreach ($_SESSION["cart_item"] as $k => $v) {
      if ($title == $k)
        unset($_SESSION["cart_item"][$k]);
      if (empty($_SESSION["cart_item"]))
        unset($_SESSION["cart_item"]);
    }
  }
  echo "<script>alert('Item Removed From Cart successfully'); </script>";
}

if (isset($_SESSION["cart_item"])) {
  $total_quantity = 0;
  $total_price = 0;
}

if (isset($_POST['complete_order'])) {
  if (!isset($_SESSION['user'])) {
    $err = 'Please login or register to complete order.';
  } else {
    if(isset($_SESSION['cart_item'])) {
      // Take all orders and sum and store in DB
      $userID = $_SESSION['user']['id'];
      $cartItems = @$_SESSION['cart_item'];
      $item_price = 0;
      $orderId = generateRandomString(7);

      foreach ($_SESSION["cart_item"] as $item => $v) {
        // print_r($item);
        $item_price = $v["quantity"] * $v["price"];
      }
      $status = 'PAID';
      $products = json_encode($cartItems);
      $conn->query("INSERT into orders(`order_id`, `userId`, `products`, `status`, `total_price`) VALUES('$orderId','$userID', '$products', '$status', '$item_price')") or die($conn->error);
      $success = 'Order Placed Successfully';
      $_SESSION['success_message'] = 'Order Placed Successfully';
      unset($_SESSION['cart_item']);
    }
  }
}
?>
<div class="container">

  <?php

  if (isset($_SESSION['cart_item'])) {
  ?>
    <div class="row">
      <div class="col-12">
        <?php

        if ($err != null) {
        ?>
          <div class="alert alert-info"><?php echo $err; ?></div>
        <?php
        }
        ?>
        <?php

        if ($success != null) {
        ?>
          <div class="alert alert-success"><?php echo $success; ?></div>
        <?php
        }
        ?>
        <div class="table-responsive">
          <table class="table table-striped">
            <thead>
              <tr>
                <th scope="col"> </th>
                <th scope="col">Product</th>
                <th scope="col">Available</th>
                <th scope="col" class="text-center">Quantity</th>
                <th scope="col" class="text-right">Price</th>
                <th> </th>
              </tr>
            </thead>
            <tbody>
              <?php
              foreach ($_SESSION["cart_item"] as $item => $v) {
                // print_r($item);
                $item_price = $v["quantity"] * $v["price"];
              ?>
                <tr>
                  <td><img src="<?php echo $v['image']; ?>" height="70" /> </td>
                  <td><?php echo $v['title']; ?></td>
                  <td>In stock</td>
                  <td><?php echo $v['quantity']; ?></td>
                  <td class="text-right">$<?php echo number_format($item_price, 2); ?></td>
                  <td class="text-right">
                    <form action="" method="POST">
                      <input type="hidden" name="product_key" value="<?php echo $item; ?>" />
                      <button class="btn btn-sm btn-danger" name="remove_cart"><i class="fa fa-trash"></i> X</button>
                    </form>
                  </td>
                </tr>
              <?php
                $total_price += ($v["price"] * $v["quantity"]);
              }
              ?>

              <!-- <tr>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td>Sub-Total</td>
              <td class="text-right">255,90 €</td>
            </tr>
            <tr>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td>Shipping</td>
              <td class="text-right">6,90 €</td>
            </tr> -->
              <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td><strong>Total</strong></td>
                <td class="text-right"><strong>$<?php echo number_format($total_price, 2); ?></strong></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      <div class="col mb-2">
        <div class="row justify-content-between">
          <div class="col-sm-12  col-md-6">
            <button class="btn btn-block btn-light">Continue Shopping</button>
          </div>
          <div class="col-sm-12 col-md-6 text-right">
            <form action="" method="POST">
              <button class="btn btn-lg btn-block btn-success text-uppercase" name="complete_order">Complete Order</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  <?php
  } else {
  ?>
    <div class="row justify-content-center my-5">
      <div class="col-md-8 text-center  my-5">
        <div class="alert alert-info">Shopping Cart is Empty</div>
        <a href="index.php" class="btn btn-success">Start Shopping</a>
      </div>
    </div>
  <?php
  }
  ?>

</div>
<?php
require_once('includes/footer.php');
?>