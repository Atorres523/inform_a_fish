<?php
session_start();
INCLUDE("connection.php");
INCLUDE("functions.php");

if($_SESSION['loggedIn'] && $_SESSION['is_admin']){
//allows user access to page if they are confirmed to be logged in
}
else{
//redirect to the login page
header('Location: login.php'); 
}

if($_SERVER['REQUEST_METHOD'] == "POST")
	{
		$name = $_POST['person_name'];
		$user_name = $_POST['user_name'];
		$password = $_POST['password'];
		$type = $_POST['FishermanType'];

		if(!empty($name) && !empty($user_name) && !empty($password) && !empty($type))
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

				header("Location: login.php");
				die;
			}

			
		}
		else
		{
			echo "All Fields Required";
		}
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="userManagement.css">
    <title>Catches</title>
    

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/fixedheader/3.2.2/css/fixedHeader.bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css">

</head>

<body>
    <br>
    <div class="container">
    <table id="example" class="table table-striped table-bordered nowrap" style="width:100%">
    <a href="admin.php">Return</a><br>
    <h1>User Management</h1>
        <thead>
            <tr>
                <th>Name</th>
                <th>Username</th>
                <th>Type</th>
                </tr>
        </thead>
        <tbody>
            <?php
            date_default_timezone_set('America/Los_Angeles');
            error_reporting(E_ALL);
            ini_set("log_errors", 1);
            ini_set("display_errors", 1);

            $result = mysqli_query($con,"SELECT Fisherman.Name as FLname, Fisherman.Username, FishermanType.Type as FishermanType 
            FROM Fisherman inner join FishermanType on Fisherman.Username = FishermanType.Username"); 


            while($row = mysqli_fetch_array($result))
            {
            echo "<tr>";
            echo "<td>" . $row['FLname'] . "</td>";
            echo "<td>" . $row['Username'] . "</td>";
            echo "<td>" . $row['FishermanType'] . "</td>";
            echo "</tr>";
            }
            echo "</table>";

            ?>

        </div>

        <div class="dropdown">
            <button class="dropbtn">Options</button>
                <div class="dropdown-content">
                <a href="#" id="myBtn">Add User</a> <!-- Add User -->
                    <!-- The Modal -->
                    <div id="myModal" class="modal"> <!-- HAVING TROUBLE WITH MODAL DISSAPPEARING WHEN YOU MOVE MOUSE OFF SCREEN -->

                    <!-- Modal content -->
                    <div class="modal-content">
                    <div class="modal-header">
                        <span class="close">&times;</span>
                        <h2>Add User</h2>
                    </div>
                    <div class="modal-body">
                        <form method="post">
                        <div style="font-size: 20px;margin: 10px;color: white;">Add</div>

                        Full Name:<input id="text" type="text" name="person_name"><br><br>
                        Username: <input id="text" type="text" name="user_name"><br><br>
                        Password: <input id="text" type="password" name="password"><br><br>
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
                        

                        <input id="button" type="submit" value="Add"><br><br>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <h3></h3>
                    </div>
                    </div>

                    </div>
                <a href="#">Edit User</a> <!-- Edit User -->
                <a href="#">Delete User</a> <!-- Delete User -->
            </div>
        </div>  

        </tbody>

    </table>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/fixedheader/3.2.2/js/dataTables.fixedHeader.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/responsive.bootstrap.min.js"></script>

    <script>
        $(document).ready(function() {
            var table = $('#example').DataTable( {
                responsive: true
            } );
        
            new $.fn.dataTable.FixedHeader( table );
        } );
    </script>
    <script>
    if ( window.history.replaceState ) {
        window.history.replaceState( null, null, window.location.href );
    }
</script>



<script>
    // Get the modal
var modal = document.getElementById("myModal");

// Get the button that opens the modal
var btn = document.getElementById("myBtn");

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];

// When the user clicks on the button, open the modal
btn.onclick = function() {
  modal.style.display = "block";
}

// When the user clicks on <span> (x), close the modal
span.onclick = function() {
  modal.style.display = "none";
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }
} 

</script>


</body>
</html>