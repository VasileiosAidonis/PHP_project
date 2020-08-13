<?php
include('connect_to_db.php');
// Check connection
if ($mysqli -> connect_errno) {
  echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
  exit();
}
?>
<!-- Unset ALl Cookies when in login page-->
<?php
  $past = time() - 3600;
  foreach ( $_COOKIE as $key => $value ) {
      setcookie( $key, $value, $past, '/' );
  }
?>

<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login</title>
  <link rel="stylesheet" href="dist/app.css" />
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
                         integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh"
                         crossorigin="anonymous">
</head>

<body style="background-image:linear-gradient(-90deg, #6BB5FF, #ACD6FF, black, #760C0C, black, #ACD6FF, #6BB5FF)">

<div class="container bg-light text-dark" style="opacity:95%;">
  <div role="img" class="section-background-image"
       style="background-position: 50% 50%; background-image: url(&quot;https://images.pexels.com/photos/929773/pexels-photo-929773.jpeg?auto=compress&amp;cs=tinysrgb&amp;dpr=3&amp;h=1850&amp;w=800&quot;);"
       data-image-width="1024" data-image-height= auto;>
  <form action="" method="POST">

      <a class="navbar-brand d-flex pt-4">
         <h3 class="pr-3 pl-4"><b>VA</b></h3> <div class="h2 pl-3 pr-4 justify-content-start" style="border-left:2px solid #333;">Vacation</div>
      </a>
      <div>
        <div class="h1 p-1 pt-5 text-center">Login</div>
        <div class="text-center">
            <label for="username" class="col-form-label">Email</label>
            <input id="username"
                   type="email"
                   class="form-control"
                   style="border-radius:10px; width:40%; margin-left:30%; margin-right:30%;"
                   name="email"
                   required autocomplete="email">
        </div>
        <div class="text-center">
            <label for="password" class="col-form-label">Password</label>
            <input id="password"
                   type="password"
                   class="form-control"
                   style="border-radius:10px; width:40%; margin-left:30%; margin-right:30%;"
                   name="password"
                   required autocomplete="password">
        </div>

        <div class="pt-3 text-center">
             <button type="submit" name="submit" class="btn btn-primary">
               Let's Go
             </button>
        </div>

        <?php if(isset($_POST['submit'])) {
                   // Fetch info of the User trying to login
                   $email = $_POST['email'];
                   $password = $_POST['password'];

                   /**
                   * Query to find if user exists and redirect him
                   * to the appropriate page based on his role
                   */
                   $query = "SELECT * FROM users WHERE email ='".$email."' AND password='".$password."' LIMIT 1";
                   $res = $mysqli->query($query);
                   $user = mysqli_fetch_assoc($res);

                   if($res->num_rows == 1 ) {

                      setcookie("name", $user['first'], time() + (86400 * 30), "/");
                      setcookie("id", $user['id'], time() + (86400 * 30), "/");

                      if ($user['role_id'] == 1) {
                         header("location: admin_view.php");
                         exit();
                      } else if ($user['role_id'] == 2){
                         header("location: user_view.php");
                         exit();
                      }
                   } else {?>
                       <div style="padding-top: 10px;"></div>
                       <div class="alert alert-danger" role="alert"
                            style="border-radius:4px; width:30%; margin-left:35%; margin-right:35%;">
                         <?php  echo "Invalid Credential"; ?>
                       </div>
            <?php }
            }
        ?>
        <div class="pt-1 text-center" style="padding-bottom:450px;"></div>
     </div>
  </form>

  <footer class="page-footer font-small blue">
    <div style="padding-top:20px"></div>
    <hr style="border:1px solid #F9F9F9;">
    <div class="footer-copyright text-center py-3">© 2020 All Rights Reserved
    </div>
  </footer>
</div>
 <script src="dist/app.js"></script>

</body>
</html>
