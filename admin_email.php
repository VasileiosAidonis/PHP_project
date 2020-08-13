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

  <div class="container bg-light text-dark" style="opacity:95%;">
     <?php
        $msg = "Home";
        $path = "admin_view.php";
        include('header.php');
     ?>
     <div style="padding-top:40px;"></div>
     <?php if(isset($_COOKIE['accepted'])){ ?>
             <div class="alert alert-success" role="success"
                  style="border-radius:4px; width:30%; margin-left:35%; margin-right:35%; text-align:center;">
             <?php print_r($_COOKIE["accepted"]);
                   setcookie("accepted", "", time()-3600, "/"); ?>
             </div>
     <?php } ?>
     <?php if(isset($_COOKIE['rejected'])){ ?>
             <div class="alert alert-dark" role="danger"
                  style="border-radius:4px; width:30%; margin-left:35%; margin-right:35%; text-align:center;">
             <?php print_r($_COOKIE["rejected"]);
                   setcookie("rejected", "", time()-3600, "/"); ?>
             </div>
     <?php } ?>

     <table id="requests_table" class="table table-hover" align="center"
            style="width:800px; border:5px solid #41330C; border-radius:10px; line-height:40px; font-family:Times New Roman; font-size:110%; background:#FFF2CB;">
         <tr>
             <th colspan="3" style="text-align:center;"><h2>Emails</h2></th>
         </tr>
         <t>
             <th> # </th>
             <th> From </th>
             <th> Reason </th>
         </t>

       <?php
         $query = $mysqli -> query("SELECT * FROM requests WHERE status_id=1");

         // Pagination
         $page_limit = 3;
         $num_results = $query->num_rows;
         $num_pages = ceil($num_results/$page_limit);

         if (!isset($_GET['page'])) {
           $page = 1;
         } else {
           $page = $_GET['page'];
         }
         $lim_start = ($page-1)*$page_limit;

         $query_lim='SELECT * FROM requests WHERE status_id=1 LIMIT ' . $lim_start . ',' .  $page_limit;
         $result = $mysqli -> query($query_lim);

         $counter = 1;
         while($rows=mysqli_fetch_assoc($result))
         {
                  $query_email = $mysqli -> query("SELECT * FROM users WHERE id='".$rows['user_id']."'");
                  $rows2=mysqli_fetch_assoc($query_email);
       ?>
           <tr style="text:18px;">
               <td><?php echo $counter; ?></td>
               <td><?php echo $rows2['email']; ?></td>
               <!-- Get Dates without hours -->
               <?php $df = new DateTime($rows['date_from']);
                     $dt = new DateTime($rows['date_to']);
               ?>
               <td>“ Dear supervisor, employee <i><?php echo $rows2['first']; ?></i> requested for some time off,<br>  &nbsp; starting on
                    <a style="color:red;"><?php echo date_format($df, 'Y-m-d'); ?> </a >and ending on <a style="color:red;">
                    <?php echo date_format($dt, 'Y-m-d'); ?> </a>,
                    stating the reason:<br> &nbsp;<i> <?php echo $rows['reason']; ?> </i><br> &nbsp;
                    <u>Click on</u> one of the below links to approve or reject the application:
                    <form method="POST">
                       <input id="accepted<?php echo $rows['id'] ?>" type="submit" name="submit"
                              onclick="getID(this.id)"
                              value="Accept"
                              style="border:none; background-color:inherit; color:green;">
                       &nbsp; &nbsp;   -
                       <input id="rejected<?php echo $rows['id'] ?>" type="submit" name="submit"
                              onclick="getID(this.id)"
                              value="Reject"
                              style="border:none; background-color:inherit; color:red;">
                           ”
                     </form>
               </td>
           </tr>
       <?php
           $counter +=1;
         }
       ?>
     </table>
     <div style="text-align:center; font-size:18px; border-bottom:2px solid #41330C; margin-left:47%; margin-right:47%;"><b>
         <?php
         for ($page=1;$page<=$num_pages;$page++) {
           echo '<a href="admin_email.php?page=' . $page . '">' . $page . '</a> ';
         }?></b>
     </div>
     <?php include('footer.php'); ?>
     <?php
      // Get request ID as a cookie with a javascript function
         echo "<script>
                 function getID(btn_id) {
                   my_id = btn_id.substring(8);
                   document.cookie='uid='+my_id;
                 }
               </script>";

         if(isset($_POST['submit'])) {

             $admin_choice = mysqli_real_escape_string($mysqli, $_REQUEST['submit']);

             $request_id = $_COOKIE["uid"];
             $query = $mysqli -> query("SELECT * FROM requests WHERE id='".$request_id."'");
             $the_request = mysqli_fetch_assoc($query);
             // Fetch the email of the User
             $query2 = $mysqli -> query("SELECT * FROM users WHERE id='".$the_request['user_id']."'");
             $user_email = mysqli_fetch_assoc($query2);

             if ($admin_choice == "Accept"){
                  $update_req = "UPDATE requests SET status_id=2 WHERE id='".$request_id."'";
                  $mysqli->query($update_req);

                  // //Send mail to the employee
                  // $to_email = '$user_email['email']';
                  // $sub = 'Answer from Admin';
                  // $msg = '“ Dear employee, your supervisor has accepted your application
                  //           submitted on $the_request['date_submitted']. ”';
                  // $header = 'From: billaidon@hotmail.com';
                  // mail($to_email,$sub,$msg,$header);

                  setcookie("accepted", "Vacation has been approved", time() + (86400 * 30), "/");
                  header("location: admin_email.php");
                  exit();
             } else if ($admin_choice == "Reject") {
                  $update_req = "UPDATE requests SET status_id=3 WHERE id='".$request_id."'";
                  $mysqli->query($update_req);

                  // //Send mail to the employee
                  // $to_email = '$user_email['email']';
                  // $sub = 'Answer from Admin';
                  // $msg = '“ Dear employee, your supervisor has rejected your application
                  //           submitted on $the_request['date_submitted']. ”';
                  // $header = 'From: billaidon@hotmail.com';
                  // mail($to_email,$sub,$msg,$header);

                  setcookie("rejected", "Vacation has NOT been approved", time() + (86400 * 30), "/");
                  header("location: admin_email.php");
                  exit();
             }
         }
      ?>
</body>
</html>
