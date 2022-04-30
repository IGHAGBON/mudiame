<?php

//check_lecturer_login.php

include('admin/database_connection.php');

session_start();

$lecturer_emailid = '';
$lecturer_password = '';
$error_lecturer_emailid = '';
$error_lecturer_password = '';
$error = 0;

if(empty($_POST["lecturer_emailid"]))
{
	$error_lecturer_emailid = 'Email Address is required';
	$error++;
}
else
{
	$lecturer_emailid = $_POST["lecturer_emailid"];
}

if(empty($_POST["lecturer_password"]))
{	
	$error_lecturer_password = 'Password is required';
	$error++;
}
else
{
	$lecturer_password = $_POST["lecturer_password"];
}

if($error == 0)
{
	$query = "
	SELECT * FROM tbl_lecturer
	WHERE lecturer_emailid = '".$lecturer_emailid."'
	";

	$statement = $connect->prepare($query);
	if($statement->execute())
	{
		$total_row = $statement->rowCount();
		if($total_row > 0)
		{
			$result = $statement->fetchAll();
			foreach($result as $row)
			{
				if(password_verify($lecturer_password, $row["lecturer_password"]))
				{
					$_SESSION["lecturer_id"] = $row["lecturer_id"];
				}
				else
				{
					$error_lecturer_password = "Wrong Password";
					$error++;
				}
			}
		}
		else
		{
			$error_lecturer_emailid = "Wrong Email Address";
			$error++;
		}
	}
}

if($error > 0)
{
	$output = array(
		'error'			=>	true,
		'error_lecturer_emailid'	=>	$error_lecturer_emailid,
		'error_lecturer_password'	=>	$error_lecturer_password
	);
}
else
{
	$output = array(
		'success'		=>	true
	);
}

echo json_encode($output);

?>