<?php 

session_start();

	include("connection.php");
	include("functions.php");

	
	if($_SERVER['REQUEST_METHOD'] == "POST")
	{
		
		//something was posted
		$user_name = $_POST['user_name'];
		$password = $_POST['password'];

		$validation = $con->prepare("SELECT * FROM Fisherman WHERE Username=?");
    	$validation->bind_param('s', $user_name);
    	$validation->execute();

    	mysqli_stmt_bind_result($validation, $res_name, $res_user, $res_password);

    	$genericErrorMsg = "Invalid username and/or password";
		if ($validation->fetch() && password_verify($password, $res_password)) 
		{
				$_SESSION['user_name'] = $user_data['Username'];
				header("Location: index3.php");
				die;			
		}
		else
		{
			echo "$genericErrorMsg";
		}
	}


?>


<!DOCTYPE html>
<html>
<head>
	<title>Login</title>
</head>
<body>

	<style type="text/css">
	
	#text{

		height: 25px;
		border-radius: 5px;
		padding: 4px;
		border: solid thin #aaa;
		width: 100%;
	}

	#button{

		padding: 10px;
		width: 100px;
		color: white;
		background-color: lightblue;
		border: none;
	}

	#box{

		background-color: grey;
		margin: auto;
		width: 300px;
		padding: 20px;
	}

	</style>

	<div id="box">
		
		<form method="post">
			<div style="font-size: 20px;margin: 10px;color: white;">Login</div>

			Username: <input id="text" type="text" name="user_name"><br><br>
			Password: <input id="text" type="password" name="password"><br><br>

			<input id="button" type="submit" value="Login"><br><br>

			<a href="signup.php">Click to Signup</a><br><br>
		</form>
	</div>
</body>
</html>