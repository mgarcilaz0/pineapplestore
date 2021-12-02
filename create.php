<?php
$page_title = "Pineapple Inc.";
require_once('includes/header.php');
require_once('includes/database.php');

if (session_status() == PHP_SESSION_NONE) {
  session_start();
}

if (isset($_POST['reg_user'])) {

    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone_number']);
    $pass = mysqli_real_escape_string($conn, $_POST['password']);

    // Check user exists
    $userCheckQuery = "SELECT * FROM users where email='$email' OR phone_number='$phone' LIMIT 1";
    $query = $conn->query($userCheckQuery) or die($conn->error);
    if($query->fetch_row() != null) {
      echo "<div class='alert alert-info'>Email or Phone Number Already Exists</div>";
      return;
    }
    $firstname = explode(' ', $name)[0];
    $lastname = explode(' ', $name)[1];
    $username = explode('@', $email)[0];
    $role = 'user';
    $hashedPassword = md5($pass);
    $insertUserQuery = "INSERT INTO users(firstname, lastname, email, phone_number, username, `password`, `role`) VALUES('$firstname', '$lastname', '$email', '$phone', '$username', '$hashedPassword', '$role')";
    $insertQuery = $conn->query($insertUserQuery) or die($conn->error);
    $_SESSION['user'] = ["firstname" => $firstname, "lastname" => $lastname, "email" => $email, "phone_number" => $phone, "role" => $role, "id" => $insertQuery->last_id];
    header("location: index.php");
    exit;
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
          <h3 class="mb-2">Sign Up</h3>
          <p>Create a new account</p>
          <form action="" method="POST">
            <div class="form-floating">
              <input type="text" class="form-control" required name="name" id="floatingInputname">
              <label for="floatingInputname">Full Name</label>
            </div>
            <div class="form-floating mt-3">
              <input type="email" class="form-control" required name="email" id="floatingInputemail">
              <label for="floatingInputemail">Email</label>
            </div>
            <div class="form-floating mt-3">
              <input type="text" class="form-control" required name="phone_number" id="floatingInput">
              <label for="floatingInput">Phone Number</label>
            </div>
            <div class="form-floating mt-3">
              <input type="password" class="form-control" required name="password" id="floatingPassword" placeholder="Password">
              <label for="floatingPassword">Password</label>
            </div>
            <button class="btn btn-success w-100 mt-3" name="reg_user">Sign Up</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</body>
<?php include_once('includes/footer.php') ?>

</html>