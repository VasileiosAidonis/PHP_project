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
     style="background-position: 50% 50%; background-image: url(&quot;https://images.pexels.com/photos/1182127/pexels-photo-1182127.jpeg?auto=compress&amp;cs=tinysrgb&amp;dpr=3&amp;h=1850&amp;w=860&quot;);"
     data-image-width="1024" data-image-height= auto;>

  <div class="container bg-light text-dark" style="opacity:95%;">
     <?php
        $msg = "Home";
        $path = "user_view.php";
        include('header.php');
     ?>
     <div style="padding-bottom:50px;"></div>
     <form action="" method="POST">
       <div style="border:2px solid #333; margin-left:25%; margin-right:25%; padding-bottom:30px; background-color:#3399FF; border-radius:30px;">
        <div class="h1 p-1 pt-4 text-center" style="border-bottom:1.5px solid #99CCFF;">Submission Form</div>

        <div class="text-center">
           <label for="date_from" class="col-form-label"><h5>Vacation Start</h5></label>
           <input id="date_from"
                  type="date"
                  class="form-control"
                  style="border-radius:10px; width:70%; margin-left:15%; margin-right:15%;"
                  name="date_from"
                  required autocomplete="date_from">
        </div>
        <div class="text-center">
           <label for="date_to" class="col-form-label"><h5>Vacation End</h5></label>
           <input id="date_to"
                  type="date"
                  class="form-control"
                  style="border-radius:10px; width:70%; margin-left:15%; margin-right:15%;"
                  name="date_to"
                  required autocomplete="date_to">
        </div>
        <div class="text-center">
           <label for="reason" class="col-form-label">
               <h5>Reason</h5>
               <div style="font-size:85%; margin-top:-8px;">(maxLength: 200char)</div></label>
           <textarea id="reason"
                     name="reason"
                     maxlength="200"
                     style="border-radius:10px; width:70%; margin-left:15%; margin-right:15%;"
                     class="form-control"
                     rows="5" cols="60">
Type Here..</textarea>
        </div>

      <div class="pt-4 text-center">
           <button type="submit" name="submit" class="btn btn-dark"
                   style="padding-top:10px; padding-bottom: 10px; padding-left:20px; padding-right:20px;">
              Submit
           </button>
      </div>
     </div>
     </form>

    <?php
      if(isset($_POST['submit'])) {

        $user_id = $_COOKIE["id"];
        $date_now = date("Y-m-d");
        $date_from = mysqli_real_escape_string($mysqli, $_REQUEST['date_from']);
        $date_to = mysqli_real_escape_string($mysqli, $_REQUEST['date_to']);
        // Calculating the total days
        $date1 = new DateTime($date_from);
        $date2 = new DateTime($date_to);
        $diff = $date1->diff($date2);
        $total_days = $diff->d;
        // Removing some punctuations from the string
        $reason = mysqli_real_escape_string($mysqli, $_REQUEST['reason']);
        $reason = str_replace("\r\n","", $reason);
        $status_id = 1;

        if ($date2 > $date1){

              $query = "INSERT INTO requests (user_id, date_submitted, date_from, date_to, total_days, reason, status_id)
                        VALUES ('$user_id', '$date_now', '$date_from', '$date_to', '$total_days', '$reason', '$status_id')";
              $added_user = $mysqli->query($query);

              // Fetch email of the User
              $query_user_email = "SELECT * FROM users WHERE id=$user_id";
              $mail = $mysqli->query($query_user_email);
              $email=  mysqli_fetch_assoc($mail);

              // Final validation that the query succeeded
              if($added_user){

                  // //Send mail to the administrator
                  // $to_email = 'billaidon@hotmail.com';
                  // $sub = 'Asking for Vacation';
                  // $msg = '“ Dear supervisor, employee $user_id requested for some time off, starting on
                  //               $date_from and ending on $date_to, stating the reason: $reason
                  //               Click on one of the below links to approve or reject the application:
                  //               {approve_link} - {reject_link} ”';
                  // $header = 'From: $email';
                  // mail($to_email,$sub,$msg,$header);

                  setcookie("new_request", "You request was sent to the administrator", time() + (86400 * 30), "/");
                  header("location: user_view.php");
                  exit();
              }
        } else { ?>
               <div style="padding-top:10px;">
               <div class="alert alert-danger" role="danger"
                    style="border-radius:4px; width:30%; margin-left:35%; margin-right:35%; text-align:center;">
                    Dates are not properly specified
               </div>
  <?php }
      }
  ?>

  <div style="padding-top:130px;">
  <?php include('footer.php'); ?>
  </div>
</div>
</body>
</html>
