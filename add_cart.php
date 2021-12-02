<?php

if (session_status() == PHP_SESSION_NONE) {
  session_start();
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
  $_SESSION['success_message'] = 'Item added to Cart Successfully';
  // echo "<script>alert('Item added to Cart Successfully'); </script>";
}



header('Location: ' . $_SERVER['HTTP_REFERER']);