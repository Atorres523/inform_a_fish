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
			
				$_SESSION['user_name'] = $res_user;
				$_SESSION['loggedIn'] = true; //Sets logged in to true if username and password are correct
				$_SESSION["is_admin"] = false;
				$validation->close();

				//checks if user is an admin
				//If they are they will be redirected to the admin page
				$isAdminStatement = $con->prepare("CALL IsAdmin(?)");
				if (!$isAdminStatement) {
					echo "ERROR!";
				}
				$isAdminStatement->bind_param('s', $res_user);
				$isAdminStatement->execute();
				mysqli_stmt_bind_result($isAdminStatement, $res_admin);
				$isAdminStatement->fetch();

				$_SESSION["is_admin"] = $res_admin;
				
				if($res_admin)
				{
					header('Location: admin.php');
					die;
				}
				
				
				header("Location: index.php");
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
	
	<title>Login | Inform A Fish</title> 
</head>
<body>
<body style = "background: url(https://wallpaperaccess.com/full/275578.jpg)">
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

			<input id="text" type="text" name="user_name" placeholder="Username" style="color:#888;" onblur="inputBlur(this)"><br><br>
			<input id="text" type="password" name="password" placeholder="Password" style="color:#888;" onblur="inputBlur(this)"><br><br>
			<!-- Name: <input id="text" type="text" name="name"><br><br> -->

			<input id="button" type="submit" value="Login"><br><br> 

			Don't have an account? <a href="signup.php">Sign Up</a><br><br>
		</form>
	</div>

	</script>

<script> // blurs username signaling that it cannot be changed by user
function inputBlur(i) {
    if (i.value == "") { i.value = i.defaultValue; i.style.color = "#888"; }
}
</script>

</body>
</html>
