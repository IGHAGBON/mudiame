<?php

//login.php

include('admin/database_connection.php');

session_start();

if(isset($_SESSION["lecturer_id"]))
{
  header('location:index.php');
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Student Attendance System in PHP using Ajax</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</head>
<body>

<div class="jumbotron text-center" style="margin-bottom:0">
  <h1>Student Attendance System</h1>
</div>


<div class="container">
  <div class="row">
    <div class="col-md-4">

    </div>
    <div class="col-md-4" style="margin-top:20px;">
      <div class="card">
        <div class="card-header">lecturer Login</div>
        <div class="card-body">
          <form method="post" id="lecturer_login_form">
            <div class="form-group">
              <label>Enter Email Address</label>
              <input type="text" name="lecturer_emailid" id="lecturer_emailid" class="form-control" />
              <span id="error_lecturer_emailid" class="text-danger"></span>
            </div>
            <div class="form-group">
              <label>Enter Password</label>
              <input type="password" name="lecturer_password" id="lecturer_password" class="form-control" />
              <span id="error_lecturer_password" class="text-danger"></span>
            </div>
            <div class="form-group">
              <input type="submit" name="lecturer_login" id="lecturer_login" class="btn btn-info" value="Login" />
            </div>
          </form>
        </div>
      </div>
    </div>
    <div class="col-md-4">

    </div>
  </div>
</div>

</body>
</html>

<script>
$(document).ready(function(){
  $('#lecturer_login_form').on('submit', function(event){
    event.preventDefault();
    $.ajax({
      url:"check_lecturer_login.php",
      method:"POST",
      data:$(this).serialize(),
      dataType:"json",
      beforeSend:function(){
        $('#lecturer_login').val('Validate...');
        $('#lecturer_login').attr('disabled','disabled');
      },
      success:function(data)
      {
        if(data.success)
        {
          location.href="index.php";
        }
        if(data.error)
        {
          $('#lecturer_login').val('Login');
          $('#lecturer_login').attr('disabled', false);
          if(data.error_lecturer_emailid != '')
          {
            $('#error_lecturer_emailid').text(data.error_lecturer_emailid);
          }
          else
          {
            $('#error_lecturer_emailid').text('');
          }
          if(data.error_lecturer_password != '')
          {
            $('#error_lecturer_password').text(data.error_lecturer_password);
          }
          else
          {
            $('#error_lecturer_password').text('');
          }
        }
      }
    })
  });
});
</script>