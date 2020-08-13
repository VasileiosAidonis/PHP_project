<?php
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
    <title>Request List</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

</head>
<body class="bg-light text-dark">
  <div role="img" class="section-background-image"
       style="background-position: 50% 50%; background-image: url(&quot;https://images.pexels.com/photos/1182127/pexels-photo-1182127.jpeg?auto=compress&amp;cs=tinysrgb&amp;dpr=3&amp;h=1850&amp;w=860&quot;);"
       data-image-width="1024" data-image-height= auto;>

    <div class="container" style="background: white; opacity:94%;">
    <?php
      $msg = "Submit Request";
      $path = "create_request_form.php";
      include('header.php');
    ?>
    <?php if(count($_COOKIE) > 0) { ?>
            <div class="pr-4 h4" style="text-align: right;">
                Hello, <?php print_r($_COOKIE["name"]); ?>
            </div>
    <?php } else { ?>
            <div class="pr-4 h4" style="text-align: right;">
                Hello unknown
            </div>
    <?php } ?>
    <div style="padding-bottom:30px;"></div>
    <?php if(isset($_COOKIE['new_request'])){ ?>
            <div class="alert alert-success" role="success"
                 style="border-radius:4px; width:32%; margin-left:34%; margin-right:34%; text-align:center;">
            <?php print_r($_COOKIE["new_request"]);
                  setcookie("new_request", "", time()-3600, "/"); ?>
            </div>
    <?php } ?>
    <div class="p-2 pb-1 h4"
         style="border:2px solid #660000; background-color: brown; color:white; margin-left: 25%; margin-right: 25%; text-align:center; border-radius:10px;">
      Make a request for your next vacation
    </div>

    <table id="requests_table" class="table table-hover" align="center"
           style="width:1000px; min-height: 280px; border:5px solid #41330C; border-radius:10px; line-height:40px; font-family:Times New Roman; font-size:110%; background:#FFF2CB;">
        <tr>
            <th colspan="5" style="text-align:center;"><h2>Past Applications</h2></th>
        </tr>
        <tbody>
          <tr>
            <th> # </th>
            <th> Date submitted </th>
            <th> Dates requested </th>
            <th> Days requested </th>
            <th> Status </th>
          </tr>

      <?php
        $user_id = $_COOKIE["id"];
        $query = $mysqli -> query("SELECT * FROM requests
                                   WHERE user_id = $user_id
                                   ORDER BY id DESC");
        $query_status = $mysqli -> query("SELECT * FROM status");

        $fetch_status1 = mysqli_fetch_assoc($query_status);
        $fetch_status2 = mysqli_fetch_assoc($query_status);
        $fetch_status3 = mysqli_fetch_assoc($query_status);

        $counter = 1;
        while($rows=mysqli_fetch_assoc($query)) {
      ?>
          <tr style="text:18px;">
              <td><?php echo $counter; ?></td>
              <td><?php echo $rows['date_submitted']; ?></td>
              <td><ul>
                    <li value="from">From: <?php echo $rows['date_from'];?></li>
                    <li style="margin-top:-16px;"
                        value="to">To: &nbsp; &nbsp; <?php echo $rows['date_to']; ?></li>
              </ul></td>
              <td class="pl-4"><?php echo $rows['total_days']; ?></td>
              <td class="pl-4" style="font-size:108%;">
                        <?php if ($rows['status_id'] == $fetch_status1['id']){ ?>
                                <a style="color:#6666FF;">
                           <?php echo $fetch_status1['msg_status'];?> </a><?php
                              } else if ($rows['status_id']== $fetch_status2['id']){?>
                                <button id="btn<?php echo $counter; ?>"
                                        style="background-color: white; color:black; border:2px solid green;"
                                        onclick="popEmail(this.id)"><a style="color:green;">
                                 <?php echo $fetch_status2['msg_status']; ?> </a></button>
                                <div id="<?php echo $counter; ?>" style="display:none;">
                                 “ Dear employee, your supervisor<br> has accepted your application<br>
                                   submitted on <?php echo $rows['date_submitted']; ?>. ”
                                </div>
                           <?php
                              } else if ($rows['status_id']== $fetch_status3['id']){?>
                                 <button id="btn<?php echo $counter; ?>"
                                         style="background-color: white; color:black; border:2px solid red;"
                                         onclick="popEmail(this.id)"><a style="color:red;">
                                  <?php echo $fetch_status3['msg_status']; ?> </a></button>
                                 <div id="<?php echo $counter; ?>" style="display:none;">
                                  “ Dear employee, your supervisor<br> has rejected your application<br>
                                    submitted on <?php echo $rows['date_submitted']; ?>. ”
                                  </div>
                        <?php } ?>
              </td>
          </tr>
      <?php
          $counter +=1;
        }
      ?>
      </tbody>
    </table>
    <p id="demo"></p>
    <script>
      function popEmail(btn_id) {
            var my_id = btn_id.substring(3);
            var x = document.getElementById(my_id);
            if (x.style.display === "none") {
              x.style.display = "block";
            } else {
              x.style.display = "none";
            }
      }
    </script>
    <div style="padding-bottom:230px;"></div>

    <?php include('footer.php')?>
    </div>
  <script src="dist/app.js"></script>
  </div>
</body>
</html>
