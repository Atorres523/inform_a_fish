<?php 
session_start(); //checks if the user is logged in

	include("connection.php");
	include("functions.php");

	if($_SESSION['loggedIn']){
		//allows user access to page if they are confirmed to be logged in
		}
		else{
		//redirect to the login page
		header('Location: login.php');
		}
	
	


?>
<!DOCTYPE html>
<html>
<head>
	<title>Lake/FIsh</title>
</head>
<body>
	
    <a href="index.php">Return</a><br><br>

	<style type="text/css">
	
	#text{

		height: 25px;
		border-radius: 5px;
		padding: 4px;
		border: solid thin #aaa;
		width: 25%;
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
    
		
		<form method="post">
			<div style="font-size: 20px;margin: 10px;color: white;">Login</div>

			Lake: <input id="text" type="text" name="lake" value = "<?php
			if(isset($_POST['lake']))
			{
				echo htmlentities($_POST['lake']);
			}
			?>"
			><br><br>

			<input id="button" type="submit" value="Search"><br><br>

		
		</form>

        <div style = "position:relative; left:700px; top:-150px;">
        <form method="post">
			<div style="font-size: 20px;margin: 10px;color: white;">Login</div>

			Fish: <input id="text" type="text" name="fish" value = "<?php
			if(isset($_POST['fish']))
			{
				echo htmlentities($_POST['fish']);
			}
			?>"
			><br><br>
			

			<input id="button" type="submit" value="Search"><br><br>

		</form>
        </div>

		
    <br>
    <div class="container">
    <table id="example" class="table table-striped table-bordered nowrap" style="width:100%">
    <h3>Fish In Body of water</h3>

        <tbody>
            <?php
            date_default_timezone_set('America/Los_Angeles');
            error_reporting(E_ALL);
            ini_set("log_errors", 1);
            ini_set("display_errors", 1);

			$lname = $_POST['lake'];
			if(isset($lname))
			{
				
				$lname = $_POST['lake'];

				$query = "CALL fishSwims('$lname')"; 
		
				$result = mysqli_query($con, $query);


				while($row = mysqli_fetch_array($result))
				{
					echo "<tr>";
					echo "<td>" . $row['FishName'] . "</td>";
					echo "</tr>";
				}
				echo "</table>";
			}
            ?>

            <h3>Fish Attributes</h3>           

            <div class="container">
            <table id="example" class="table table-striped table-bordered nowrap" style="width:100%">
            <tr>
            <th>Fish</th>
            <th>Color</th>
            <th>Bait</th>
			<th>Depth</th>
			<th>Region</th>
			<th>TOD</th>
			<th>Temp</th>
            </tr>

            <?php
				
				$fname = $_POST['fish'];
				if(isset($fname))
				{
					

					$query = "CALL fishInfo('$fname')";
		
					$result = mysqli_query($con, $query);

					while($row = mysqli_fetch_array($result))
					{
					echo "<tr>";
					echo "<td>" . $row['FishName'] . "</td>";
					echo "<td>" . $row['Color'] . "</td>";
					echo "<td>" . $row['Bait'] . "</td>";
					echo "<td>" . $row['Depth'] . " in.</td>";
					echo "<td>" . $row['Region'] . "</td>";
					echo "<td>" . $row['TOD'] . "</td>";
					echo "<td>" . $row['Temp'] . " in.</td>";
					echo "</tr>";
					}
					echo "</table>";
				}

            ?>

        </div>

        </tbody>
		
</body>
</html>