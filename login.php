<?php
$page_title = "Pineapple Inc.";
require_once('includes/header.php');
require_once('includes/database.php');

$errorMessage = "";
$retainEmail = "";
if (isset($_POST['login_user'])) {
  $email = htmlspecialchars($_POST['email']);
  $password = htmlspecialchars($_POST['password']);
  $hashPassword = md5($password);
  $checkExists = $conn->query("SELECT * from users where email='$email' AND password='$hashPassword'");

  if($checkExists->num_rows) {
    $row = $checkExists->fetch_assoc();
      $_SESSION['user'] = ["firstname" => $row['firstname'], "lastname" => $row['lastname'], "email" => $email, "phone_number" => $row['phone_number'], "role" => $row['role'], "id" => $row['id']];
    header("location: index.php");
    exit;
  }else {
    $retainEmail = $email;
    $errorMessage = "Invalid Email or Password";
  }
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - <?php echo getAppName(); ?></title>

</head>

<body class="h-100">
  <div class="row justify-content-center my-8 mt-8">
    <div class="col-md-3">
      <div class="card mt-5 mb-5">
        <div class="card-body">
          <h3 class="mb-2">Sign In</h3>
          <p>Login in to your account</p>
          <?php 
            if($errorMessage != null) {
          ?>
            <div class="alert alert-danger"><?php echo $errorMessage; ?></div>
          <?php
            }
          ?>
          <form action="" method="POST">
            <div class="form-floating">
              <input type="email" name="email" class="form-control form-control-sm" id="floatingInput" value="<?php echo $retainEmail; ?>" placeholder="name@example.com">
              <label for="floatingInput">Email address</label>
            </div>
            <div class="form-floating mt-3">
              <input type="password" name="password" class="form-control" id="floatingPassword" placeholder="Password">
              <label for="floatingPassword">Password</label>
            </div>
            <button class="btn btn-success w-100 mt-3" name="login_user">Login</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</body>
<?php include_once('includes/footer.php') ?>

</html>