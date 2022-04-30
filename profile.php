<?php

//profile.php

include('header.php');

$lecturer_name = '';
$lecturer_address = '';
$lecturer_emailid = '';
$lecturer_password = '';
$lecturer_grade_id = '';
$lecturer_qualification = '';
$lecturer_doj = '';
$lecturer_image = '';
$error_lecturer_name = '';
$error_lecturer_address = '';
$error_lecturer_emailid = '';
$error_lecturer_grade_id = '';
$error_lecturer_qualification = '';
$error_lecturer_doj = '';
$error_lecturer_image = '';
$error = 0;
$success = '';

if(isset($_POST["button_action"]))
{
	$lecturer_image = $_POST["hidden_lecturer_image"];
	if($_FILES["lecturer_image"]["name"] != '')
	{
		$file_name = $_FILES["lecturer_image"]["name"];
		$tmp_name = $_FILES["lecturer_image"]["tmp_name"];
		$extension_array = explode(".", $file_name);
		$extension = strtolower($extension_array[1]);
		$allowed_extension = array('jpg','png');
		if(!in_array($extension, $allowed_extension))
		{
			$error_lecturer_image = "Invalid Image Format";
			$error++;
		}
		else
		{
			$lecturer_image = uniqid() . '.' . $extension;
			$upload_path = 'admin/lecturer_image/' . $lecturer_image;
			move_uploaded_file($tmp_name, $upload_path);
		}
	}

	if(empty($_POST["lecturer_name"]))
	{
		$error_lecturer_name = "lecturer Name is required";
		$error++;
	}
	else
	{
		$lecturer_name = $_POST["lecturer_name"];
	}

	if(empty($_POST["lecturer_address"]))
	{
		$error_lecturer_address = 'Address is required';
		$error++;
	}
	else
	{
		$lecturer_address = $_POST["lecturer_address"];
	}

	if(empty($_POST["lecturer_emailid"]))
	{
		$error_lecturer_emailid = "Email Address is required";
		$error++;
	}
	else
	{
		if(!filter_var($_POST["lecturer_emailid"], FILTER_VALIDATE_EMAIL))
		{
			$error_lecturer_emailid = "Invalid email format";
			$error;
		}
		else
		{
			$lecturer_emailid = $_POST["lecturer_emailid"];
		}
	}
	if(!empty($_POST["lecturer_password"]))
	{
		$lecturer_password = $_POST["lecturer_password"];
	}

	if(empty($_POST["lecturer_grade_id"]))
	{
		$error_lecturer_grade_id = 'Grade is required';
		$error++;
	}
	else
	{
		$lecturer_grade_id = $_POST["lecturer_grade_id"];
	}

	if(empty($_POST["lecturer_qualification"]))
	{
		$error_lecturer_qualification = "Qualification Field is required";
		$error++;
	}
	else
	{
		$lecturer_qualification = $_POST["lecturer_qualification"];
	}

	if(empty($_POST["lecturer_doj"]))
	{
		$error_lecturer_doj = "Date of Join Field is required";
		$error++;
	}
	else
	{
		$lecturer_doj = $_POST["lecturer_doj"];
	}

	if($error == 0)
	{
		if($lecturer_password != '')
		{
			$data = array(
				':lecturer_name'			=>	$lecturer_name,
				':lecturer_address'		=>	$lecturer_address,
				':lecturer_emailid'		=>	$lecturer_emailid,
				':lecturer_password'		=>	password_hash($lecturer_password, PASSWORD_DEFAULT),
				':lecturer_qualification'=>	$lecturer_qualification,
				':lecturer_doj'			=>	$lecturer_doj,
				':lecturer_image'		=>	$lecturer_image,
				':lecturer_grade_id'		=>	$lecturer_grade_id,
				':lecturer_id'			=>	$_POST["lecturer_id"]
			);
			$query = "
			UPDATE tbl_lecturer
		      SET lecturer_name = :lecturer_name,
		      lecturer_address = :lecturer_address,
		      lecturer_emailid = :lecturer_emailid,
		      lecturer_password = :lecturer_password,
		      lecturer_grade_id = :lecturer_grade_id,
		      lecturer_qualification = :lecturer_qualification,
		      lecturer_doj = :lecturer_doj,
		      lecturer_image = :lecturer_image
		      WHERE lecturer_id = :lecturer_id
			";
		}
		else
		{
			$data = array(
				':lecturer_name'			=>	$lecturer_name,
				':lecturer_address'		=>	$lecturer_address,
				':lecturer_emailid'		=>	$lecturer_emailid,
				':lecturer_qualification'=>	$lecturer_qualification,
				':lecturer_doj'			=>	$lecturer_doj,
				':lecturer_image'		=>	$lecturer_image,
				':lecturer_grade_id'		=>	$lecturer_grade_id,
				':lecturer_id'			=>	$_POST["lecturer_id"]
			);
			$query = "
			UPDATE tbl_lecturer
		      SET lecturer_name = :lecturer_name,
		      lecturer_address = :lecturer_address,
		      lecturer_emailid = :lecturer_emailid,
		      lecturer_grade_id = :lecturer_grade_id,
		      lecturer_qualification = :lecturer_qualification,
		      lecturer_doj = :lecturer_doj,
		      lecturer_image = :lecturer_image
		      WHERE lecturer_id = :lecturer_id
			";
		}

		$statement = $connect->prepare($query);
		if($statement->execute($data))
		{
			$success = '<div class="alert alert-success">Profile Details Change Successfully</div>';
		}
	}
}


$query = "
SELECT * FROM tbl_lecturer
WHERE lecturer_id = '".$_SESSION["lecturer_id"]."'
";

$statement = $connect->prepare($query);
$statement->execute();
$result = $statement->fetchAll();

?>

<div class="container" style="margin-top:30px">
  <span><?php echo $success; ?></span>
  <div class="card">
    <form method="post" id="profile_form" enctype="multipart/form-data">
		<div class="card-header">
			<div class="row">
				<div class="col-md-9">Profile</div>
				<div class="col-md-3" align="right">
				</div>
			</div>
		</div>
		<div class="card-body">
			<div class="form-group">
				<div class="row">
					<label class="col-md-4 text-right">lecturer Name <span class="text-danger">*</span></label>
					<div class="col-md-8">
						<input type="text" name="lecturer_name" id="lecturer_name" class="form-control" />
						<span class="text-danger"><?php echo $error_lecturer_name; ?></span>
					</div>
				</div>
			</div>
			<div class="form-group">
				<div class="row">
					<label class="col-md-4 text-right">Address <span class="text-danger">*</span></label>
					<div class="col-md-8">
						<textarea name="lecturer_address" id="lecturer_address" class="form-control"></textarea>
						<span class="text-danger"><?php echo $error_lecturer_address; ?></span>
					</div>
				</div>
			</div>
			<div class="form-group">
				<div class="row">
					<label class="col-md-4 text-right">Email Address <span class="text-danger">*</span></label>
					<div class="col-md-8">
						<input type="text" name="lecturer_emailid" id="lecturer_emailid" class="form-control" />
						<span class="text-danger"><?php echo $error_lecturer_emailid; ?></span>
					</div>
				</div>
			</div>
			<div class="form-group">
				<div class="row">
					<label class="col-md-4 text-right">Password <span class="text-danger">*</span></label>
					<div class="col-md-8">
						<input type="password" name="lecturer_password" id="lecturer_password" class="form-control" placeholder="Leave blank to not change it" />
						<span class="text-danger"></span>
					</div>
				</div>
			</div>
			<div class="form-group">
				<div class="row">
					<label class="col-md-4 text-right">Qualification <span class="text-danger">*</span></label>
					<div class="col-md-8">
						<input type="text" name="lecturer_qualification" id="lecturer_qualification" class="form-control" />
						<span class="text-danger"><?php echo $error_lecturer_qualification; ?></span>
					</div>
				</div>
			</div>
			<div class="form-group">
				<div class="row">
					<label class="col-md-4 text-right">Grade <span class="text-danger">*</span></label>
					<div class="col-md-8">
						<select name="lecturer_grade_id" id="lecturer_grade_id" class="form-control">
                			<option value="">Select Grade</option>
                			<?php
                			echo load_grade_list($connect);
                			?>
                		</select>
						<span class="text-danger"><?php echo $error_lecturer_grade_id; ?></span>
					</div>
				</div>
			</div>
			<div class="form-group">
				<div class="row">
					<label class="col-md-4 text-right">Date of Joining <span class="text-danger">*</span></label>
					<div class="col-md-8">
						<input type="text" name="lecturer_doj" id="lecturer_doj" class="form-control" readonly />
						<span class="text-danger"><?php echo $error_lecturer_doj; ?></span>
					</div>
				</div>
			</div>
			<div class="form-group">
				<div class="row">
					<label class="col-md-4 text-right">Image <span class="text-danger">*</span></label>
					<div class="col-md-8">
						<input type="file" name="lecturer_image" id="lecturer_image" />
						<span class="text-muted">Only .jpg and .png allowed</span><br />
						<span id="error_lecturer_image" class="text-danger"><?php echo $error_lecturer_image; ?></span>
					</div>
				</div>
			</div>
		</div>
		<div class="card-footer" align="center">
			<input type="hidden" name="hidden_lecturer_image" id="hidden_lecturer_image" />
			<input type="hidden" name="lecturer_id" id="lecturer_id" />
			<input type="submit" name="button_action" id="button_action" class="btn btn-success btn-sm" value="Save" />
		</div>     
    </form>
  </div>
</div>
<br />
<br />
</body>
</html>

<script type="text/javascript" src="js/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="css/datepicker.css" />

<style>
    .datepicker
    {
      z-index: 1600 !important; /* has to be larger than 1050 */
    }
</style>

<script>
$(document).ready(function(){
	
<?php
foreach($result as $row)
{
?>
$('#lecturer_name').val("<?php echo $row["lecturer_name"]; ?>");
$('#lecturer_address').val("<?php echo $row["lecturer_address"]; ?>");
$('#lecturer_emailid').val("<?php echo $row["lecturer_emailid"]; ?>");
$('#lecturer_qualification').val("<?php echo $row["lecturer_qualification"]; ?>");
$('#lecturer_grade_id').val("<?php echo $row["lecturer_grade_id"]; ?>");
$('#lecturer_doj').val("<?php echo $row["lecturer_doj"]; ?>");
$('#error_lecturer_image').html("<img src='admin/lecturer_image/<?php echo $row['lecturer_image']; ?>' class='img-thumbnail' width='100' />");
$('#hidden_lecturer_image').val('<?php echo $row["lecturer_image"]; ?>');
$('#lecturer_id').val("<?php echo $row["lecturer_id"];?>");

<?php
}
?>
  
  	$('#lecturer_doj').datepicker({
  		format: "yyyy-mm-dd",
    	autoclose: true
  	});

});
</script>