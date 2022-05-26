<?php 

session_start();

	include("connection.php");
	include("functions.php");


	if($_SERVER['REQUEST_METHOD'] == "POST")
	{
		$name = $_POST['person_name'];
		$user_name = $_POST['user_name'];
		$password = $_POST['password'];
		$type = $_POST['FishermanType'];

    $username_query = "SELECT * FROM Fisherman WHERE Username = '$user_name'"; 
    $username_match = mysqli_query($con, $username_query);

    if(mysqli_num_rows($username_match)>0)
    {
      echo '<script type="text/javascript"> alert("Username already exists") </script>';
      
    }

		else if(!empty($name) && !empty($user_name) && !empty($password) && !empty($type))
		{
			//sequel injection prevention
			$validation = $con->prepare("SELECT * FROM Fisherman WHERE Username=?");
			$validation->bind_param('s', $user_name);
			$validation->execute();

			mysqli_stmt_bind_result($validation, $res_name, $res_user, $res_password);

			if($validation->fetch())
			{ 
				echo "user already exists";
			}
			else
			{
				//save to database
				$hash = password_hash($password, PASSWORD_DEFAULT);
				$query = "CALL RegisterFisherman('$name','$user_name','$hash','$type')"; //STORED PROCEDURE RegisterFisherman

				mysqli_query($con, $query);

				$query = "CALL InsertRole('$user_name', 'user')"; //STORED PROCEDURE InsertRole

				mysqli_query($con, $query);

				//header("Location: userManagement.php");
        echo '<script type="text/javascript"> alert("User Inserted") </script>';
				//die;
			
		  }
    }
    else
    {	
      echo "All Fields Required";
    }
	}
	
?>



<!DOCTYPE html>
<html>
<head>
	<title>Sign Up | Inform A Fish</title>
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
			<div style="font-size: 20px;margin: 10px;color: white;">Sign Up</div>

			<input id="text" type="text" name="person_name" placeholder="Full Name" style="color:#888;" onblur="inputBlur(this)"><br><br>
			<input id="text" type="text" name="user_name" placeholder="Username" style="color:#888;" onblur="inputBlur(this)"><br><br>
			<input id="text" type="password" name="password" placeholder="Password" style="color:#888;" onblur="inputBlur(this)"><br><br>
			<!--Fisherman Type: <input id="text" type="text" name="FishermanType"><br><br>-->
			<label for="FishermanType">Fisherman Type: </label><br>
			<select name="FishermanType" id="FishermanType">
				<option hidden selected> -- select an option -- </option>
				<option value="bass">Bass</option>
				<option value="fly">Fly</option>
				<option value="spear">Spear</option>
				<option value="bow">Bow</option>
				<option value="cat">Cat</option>
				<option value="fresh">Fresh Water</option>
				<option value="salt">Salt Water</option>
			</select><br><br>
			

			<input id="button" type="submit" value="Signup"><br><br>

			Already have an account? <a href="login.php">Login</a><br><br>
		</form>
	</div>

	<script> // blurs username signaling that it cannot be changed by user
	function inputBlur(i) {
    if (i.value == "") { i.value = i.defaultValue; i.style.color = "#888"; }
	}
	</script>

</body>
</html>