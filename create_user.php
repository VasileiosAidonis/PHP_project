<?php
ob_start();
if(!isset($_COOKIE['name'])){
    header("location: login.php");
    exit();
} else {
    include('connect_to_db.php');
}
?>

<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Creation Page</title>
  <link rel="stylesheet" href="dist/app.css" />
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
                         integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh"
                         crossorigin="anonymous">
</head>

<body>
  <div role="img" class="section-background-image"
       style="background-position:-140% 0%; background-image: url(&quot;https://images.pexels.com/photos/929773/pexels-photo-929773.jpeg?auto=compress&amp;cs=tinysrgb&amp;dpr=3&amp;h=1850&amp;w=800&quot;);"
       data-image-width="1024" data-image-height= auto;>

  <div class="container bg-light text-dark" style="opacity:95%;">
     <?php
        $msg = "Home";
        $path = "admin_view.php";
        include('header.php');
     ?>
     <div style="padding-bottom:30px;"></div>
     <form action="" method="POST">
       <div style="border:2px solid #333;  margin-left:25%; margin-right:25%; padding-bottom:30px; background-color:#8BFFC2; border-radius:30px;">
        <div class="h1 p-1 pt-4 text-center">New User</div>

        <div class="text-center">
           <label for="first" class="col-form-label">First Name</label>
           <input id="first"
                  type="text"
                  class="form-control"
                  style="border-radius:10px; width:70%; margin-left:15%; margin-right:15%;"
                  name="first"
                  required autocomplete="first">
        </div>
        <div class="text-center">
           <label for="last" class="col-form-label">Last Name</label>
           <input id="last"
                  type="text"
                  class="form-control"
                  style="border-radius:10px; width:70%; margin-left:15%; margin-right:15%;"
                  name="last"
                  required autocomplete="last">
        </div>
        <div class="text-center">
           <label for="email" class="col-form-label">Email</label>
           <input id="email"
                  type="email"
                  class="form-control"
                  style="border-radius:10px; width:70%; margin-left:15%; margin-right:15%;"
                  name="email"
                  required autocomplete="email">
        </div>
        <div class="text-center">
           <label for="password" class="col-form-label">Password</label>
           <input id="password"
                  type="password"
                  class="form-control"
                  style="border-radius:10px; width:70%; margin-left:15%; margin-right:15%;"
                  name="password"
                  required autocomplete="password">
        </div>
        <div class="text-center">
           <label for="password_ver" class="col-form-label">Confirm Password</label>
           <input id="password_ver"
                  type="password"
                  class="form-control"
                  style="border-radius:10px; width:70%; margin-left:15%; margin-right:15%;"
                  name="password_ver"
                  required autocomplete="password_ver">
        </div>
        <div class="text-center">
           <label for="role_id" class="col-form-label">User Type</label>
           <select id="role_id"
                   style="border-radius:10px; padding:7px; width:70%; margin-left:15%; margin-right:15%;"
                   name="role_id">
              <option value="1">admin</option>
              <option value="2">employee</option>
           </select>
      </div>
      <div class="pt-3 text-center">
           <button type="submit" name="submit" class="btn btn-primary"
                   style="padding-top:10px; padding-bottom: 10px; padding-left:20px; padding-right:20px;">
              Create
           </button>
      </div>
     </div>
     </form>

    <?php
      if(isset($_POST['submit'])) {

        $first_name = mysqli_real_escape_string($mysqli, $_REQUEST['first']);
        $last_name = mysqli_real_escape_string($mysqli, $_REQUEST['last']);
        $email = mysqli_real_escape_string($mysqli, $_REQUEST['email']);
        $password = mysqli_real_escape_string($mysqli, $_REQUEST['password']);
        $password_confirmation = mysqli_real_escape_string($mysqli, $_REQUEST['password_ver']);
        $user_type = mysqli_real_escape_string($mysqli, $_REQUEST['role_id']);

        // Check if passwords match, return an error
        // Could use aray of errors as well if we checked for more
        if ($password == $password_confirmation){

              $query_email = "SELECT * FROM users WHERE email='$email'";
              $unique_email = $mysqli->query($query_email);
              // Check if email is unique, or return an error
              if ($unique_email->num_rows ==0) {

                  $query = "INSERT INTO users (first, last, email, password, role_id)
                            VALUES ('$first_name', '$last_name', '$email', '$password', '$user_type')";
                  $added_user = $mysqli->query($query);
                  // Final validation that the query succeeded
                  if($added_user){
                      setcookie("new_user", "User has been created successfully", time() + (86400 * 30), "/");

                      header("location: admin_view.php");
                      exit();
                  }
              } else { ?>
                  <div style="padding-top:10px;">
                  <div class="alert alert-danger" role="danger"
                       style="border-radius:4px; width:30%; margin-left:35%; margin-right:35%; text-align:center;">
                       Email has already been used
                  </div>
      <?php   }
        } else { ?>
            <div style="padding-top:10px;">
            <div class="alert alert-danger" role="danger"
                 style="border-radius:4px; width:30%; margin-left:35%; margin-right:35%; text-align:center;">
                 Passwords do not match
            </div>
        <?php
        }
      }
    ?>
  <div style="padding-top:70px;"></div>
  <?php include('footer.php'); ?>
  </div>
</body>
</html>
