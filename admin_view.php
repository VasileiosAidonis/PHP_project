<?php
if(!isset($_COOKIE['name'])){
    header("location: login.php");
    exit();
} else if(isset($_COOKIE['name'])){
    include('connect_to_db.php');

    $user_id = $_COOKIE['id'];
    $query_type = "SELECT * FROM users WHERE id = $user_id";
    $type = $mysqli -> query($query_type);
    $fetch_type = mysqli_fetch_assoc($type);
    if ($fetch_type['role_id'] == 2){
      header("location: login.php");
      exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="js/script.js"></script>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
    <script src='https://kit.fontawesome.com/a076d05399.js'></script>

    <script type="text/javascript" src="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
    <title>Adminstrator Interface</title>
    <link rel="stylesheet" href="dist/app.css" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
                           integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh"
                           crossorigin="anonymous">

  </head>
  <body>
    <div role="img" class="section-background-image"
         style="background-position:-140% 0%; background-image: url(&quot;https://images.pexels.com/photos/929773/pexels-photo-929773.jpeg?auto=compress&amp;cs=tinysrgb&amp;dpr=3&amp;h=1850&amp;w=800&quot;);"
         data-image-width="1024" data-image-height= auto;>

    <div class="container bg-light text-dark" style="opacity:97%;">
      <?php
        $msg = "Create New User";
        $path = "create_user.php";
        include('header.php')
      ?>
      <!-- Fetch the name of the User
           and Alert a success message if a user was created
           or a User was successfully updated -->
      <?php if(count($_COOKIE) > 0) { ?>
              <div class="pr-4 h4" style="text-align: right;">
                  Hello Admin, <?php print_r($_COOKIE["name"]); ?>
              </div>
      <?php } else { ?>
              <div class="pr-4 h4" style="text-align: right;">
                  Hello unknown admin
              </div>
      <?php } ?>
      <!-- Get number of pending emails-->
      <?php $query_pending = $mysqli ->query("SELECT * FROM requests WHERE status_id=1"); ?>
      <div class="p-3"style="border:1px solid #808080; width:25%; border-radius:10px; background-color:#E0E0E0; margin-top:-30px;">
          <div class="pl-3 h5" style="color:#CC0000; font-size:115%;">
            You have <?php echo $query_pending->num_rows; ?> unread emails!
          </div>
          <div class="ml bd-highlight" style="text-align:center;">
             <a href="/vacation_php/admin_email.php" class="btn btn-primary">Manage Emails</a>
          </div>
      </div>

      <div style="padding-bottom:30px;"></div>
      <?php if(isset($_COOKIE['new_user'])){ ?>
              <div class="alert alert-success" role="success"
                   style="border-radius:4px; width:30%; margin-left:35%; margin-right:35%; text-align:center;">
              <?php print_r($_COOKIE["new_user"]);
                    setcookie("new_user", "", time()-3600, "/"); ?>
              </div>
      <?php } ?>
      <?php if(isset($_COOKIE['updated_user'])){ ?>
              <div class="alert alert-success" role="success"
                   style="border-radius:4px; width:30%; margin-left:35%; margin-right:35%; text-align:center;">
              <?php print_r($_COOKIE["updated_user"]);
                    setcookie("updated_user", "", time()-3600, "/"); ?>
              </div>
      <?php } ?>



      <table id="users_table" class="table table-hover" align="center"
             style="width:780px; min-height: 280px; border:5px solid #41330C; border-radius:10px; line-height:40px; font-family:Times New Roman; font-size:120%; background:#FFF2CB;">
          <tr>
              <th colspan="6" style="text-align:center;"><h2>List of current Users</h2></th>
          </tr>
          <tr>
              <th> # </th>
              <th> First name </th>
              <th> Last name </th>
              <th> Email </th>
              <th> User Type </th>
              <th></th>
          </tr>

        <?php
          $query = $mysqli -> query("SELECT * FROM users");
          $query_role = $mysqli -> query("SELECT * FROM roles");

          $fetch_role1 = mysqli_fetch_assoc($query_role);
          $fetch_role2 = mysqli_fetch_assoc($query_role);

          // Pagination
          $page_limit = 7;
          $num_results = $query->num_rows;
          $num_pages = ceil($num_results/$page_limit);

          if (!isset($_GET['page'])) {
            $page = 1;
          } else {
            $page = $_GET['page'];
          }
          $lim_start = ($page-1)*$page_limit;

          $query_lim='SELECT * FROM users LIMIT ' . $lim_start . ',' .  $page_limit;
          $result = $mysqli -> query($query_lim);

          while($rows=mysqli_fetch_assoc($result)) {
        ?>
            <tr style="text:18px;">
                <td><?php echo $rows['id']; ?></td>
                <td><?php echo $rows['first']; ?></td>
                <td><?php echo $rows['last']; ?></td>
                <td><?php echo $rows['email']; ?></td>
                <td><?php if ($rows['role_id']== $fetch_role1['id']){
                               echo $fetch_role1['role_name'];
                          } else if ($rows['role_id']== $fetch_role2['id']){
                               echo $fetch_role2['role_name'];
                          }
                    ?>
                </td>
                <td>
            <?php if ($rows['id'] == 1) { ?>
                    <i class="fas fa-edit" style="font-size:24px;"></i>
                    <i style="border-left:2px solid #333;"></i>
                    <button style="font-size:24px; color:red; padding: 0; border: none; background: none;">
                      <i class="pl-2 far fa-trash-alt"></i>
                    </button>
            <?php } else { ?>
                    <a href="/vacation_php/update_user.php?row_id=<?php echo $rows['id']?>"
                       style="font-size:24px; padding: 0; border: none; text-decoration: none; background: none;">
                      <i class="fas fa-edit"></i>
                    </a>
                    <i style="border-left:2px solid #333;"></i>
                    <button style="font-size:24px; color:red; padding: 0; border: none; background: none;">
                      <i class="pl-2 far fa-trash-alt"></i>
                    </button>
            <?php } ?>
               </td>
            </tr>
        <?php
          }
        ?>
      </table>
      <div style="text-align:center; font-size:18px; border-bottom:2px solid #41330C; margin-left:47%; margin-right:47%;"><b>
         <?php
          for ($page=1;$page<=$num_pages;$page++) {
            echo '<a href="admin_view.php?page=' . $page . '">' . $page . '</a> ';
          }?></b>
      </div>
      <?php include('footer.php')?>
    </div>
  </body>
</html>
