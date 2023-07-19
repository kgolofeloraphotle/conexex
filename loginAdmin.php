<?php

include 'config.php';
session_start();

if(isset($_POST['submit'])){

$email = mysqli_real_escape_string($conn, $_POST['email']);
$pass = mysqli_real_escape_string($conn, md5($_POST['password']));

$select = mysqli_query($conn, "SELECT * FROM `user_form` WHERE email = '$email' AND password = '$pass'") or die('query failed');

if(mysqli_num_rows($select) > 0){
	$row = mysqli_fetch_assoc($select);

	$name = $row['name'];
	if ($name === "Admin" || $name === "admin"){

		$_SESSION['Admin_name'] = $row['name'];
		header('location: admin.php');
	}
	else{
		$_SESSION['user_id'] = $row['id'];
		header('location:admin.php');
	}
}else{
	$message[] = 'incorrect email or password!';
}

}

	?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Com	patilble" content="IE=edge">
	<meta name="viewport" content="Width=device-width, initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<title>login</title>
</head>
<div class="form-container" style="position:relative; box-sizing: border-box; ">

	<form action="" method="post" enctype="multipart/form-data">
		<h1>CONNEX STORE</h1>
		<h3>login now</h3>
		<?php
		if(isset($message)){
			foreach($message as $message){
				echo '<div class="message">'.$message.'</div>';
			}
		}

	?>
		<input type="text" name="email" placeholder="enter email" class="box" required>
		<input type="password" name="password" placeholder="enter password" class="box" required>
		<input type="submit" value="login now" name="submit" class="btn">
		<p>don't have an account? <a href="register.php">register now</a></p>
	</form>
</div>