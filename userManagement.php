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

if(isset($_POST['add'])) //Add User
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
<?php
  if(isset($_POST['delete'])) //Delete User
  {
    $user_name = $_POST['user_name'];


    $username_query = "SELECT * FROM Fisherman WHERE Username = '$user_name'"; 
    $username_match = mysqli_query($con, $username_query);

    if(mysqli_num_rows($username_match)>0)
    {
      $query = "CALL DropFisherman('$user_name')"; //STORED PROCEDURE DropFisherman;
      $query_run = mysqli_query($con, $query);
      if($query_run)
      {
        echo '<script type="text/javascript"> alert("User Deleted") </script>';
				//header("Location: userManagement.php");
      }
      else
      {
        echo '<script type="text/javascript"> alert("User Not Deleted") </script>';
      }
      
    }
    else{
      echo '<script type="text/javascript"> alert("Username Does Not exists") </script>';
     
    }
  }
?>
<?php
  if(isset($_POST['edit'])) //Update User
  {
    //echo '<script type="text/javascript"> alert("Update Button Pushed") </script>';
    $user_name = $_POST['user_name']; // grab username from input
    if(!empty($user_name)) // if username from input is not empty, continue
    {
      $sql = "SELECT * FROM Fisherman WHERE Username = '$user_name'"; // grab conditional query from database

      $getResults = mysqli_query($con, $sql); // put results from query into &getResults

        if(mysqli_num_rows($getResults)>0) // if more than 0 rows come back, continue
        {

          //echo '<script type="text/javascript"> alert("Username Found") </script>'; // alert for testing
         // $new_user_name = $_POST['new_user_name']; // grab new username from input
          $name = $_POST['person_name']; // grab name from input
          $password = $_POST['password']; // grab password from input
          $type = $_POST['FishermanType']; // grab type from input

          if(!empty($name)){
            //echo '<script type="text/javascript"> alert("name") </script>';
          $query = "UPDATE Fisherman SET Fisherman.Name = '$name' WHERE Username = '$user_name'"; 
          mysqli_query($con, $query);
          }

         /* if(!empty($new_user_name)){
          $query = "UPDATE Fisherman SET Fisherman.Username = '$new_user_name' WHERE Username = '$user_name'"; 
          $query_run = mysqli_query($con, $query);
          mysqli_query($con, $query);
          } */

          /* if(!empty($password)){
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $query = "UPDATE Fisherman SET Fisherman.Password = '$hash' WHERE Username = '$user_name'"; 
            mysqli_query($con, $query);
          } */

          if(!empty($type)){
            $query = "UPDATE FishermanType SET FishermanType.Type = '$type' WHERE Username = '$user_name'"; 
            mysqli_query($con, $query);
          }

            //echo '<script type="text/javascript"> alert("User Info Updated") </script>';      
        }
        else{
          echo '<script type="text/javascript"> alert("Username Not Found") </script>';
        }
      }
      else
      {
        echo '<script type="text/javascript"> alert("Must Enter a Username") </script>';
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
    <title>User Management</title>
    

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
        
              
              <div class="container">
                <button class = "modal-button" href="#myModal1">Add User</button><!-- Add User -->
              </div><br> 
              
                <!-- The Modal -->
                <div id="myModal1" class="modal"> 

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
                        

                        <input id="button" type="submit" name="add" value="Add"><br><br>
                        </form>
                     </div>
                      <!-- <div class="modal-footer">
                        <h3></h3>
                      </div> -->
                </div>    
                 </div> 
                <div class="container">
                  <button class="modal-button" href="#myModal2">Update User</button><!-- Edit User -->
                </div><br>
                <!-- The Modal -->
                <div id="myModal2" class="modal"> 

                  <!-- Modal content -->
                  <div class="modal-content">
                    <div class="modal-header">
                      <span class="close">&times;</span>
                      <h2>Update User</h2>
                    </div>
                    <div class="modal-body">
                      <form method="post">
                       
                      <div style="font-size: 20px;margin: 10px;color: white;"></div>
                        
                        Username: <input id="text" type="text" name="user_name"><br>
                        <h3>Update User Info</h3>
                        Name:     <input id="text" type="text" name="person_name"><br><br>
                       <!--  New Username: <input id="text" type="text" name="new_user_name"><br><br> -->
                        <!-- New Password:  <input id="text" type="password" name="password"><br><br> -->
                        <label for="FishermanType">Fisherman Type: </label><br>
                        <select name="FishermanType" id="FishermanType">
                            <option hidden disabled selected value> -- select an option -- </option>
                            <option value="bass">Bass</option>
                            <option value="fly">Fly</option>
                            <option value="spear">Spear</option>
                            <option value="bow">Bow</option>
                            <option value="cat">Cat</option>
                            <option value="fresh">Fresh Water</option>
                            <option value="salt">Salt Water</option>
                        </select><br><br>
                        
                      <input id="button" type="submit" name="edit" value="Edit"><br><br> 
                      </form>
                        

                        
                     </div>
                      <!--<div class="modal-footer">
                        <h3></h3>
                      </div> -->
                </div>    
                 </div> 
                <div class="container">
                  <button class="modal-button" href="#myModal3">Delete User</button> <!-- Delete User -->
                </div>
                <!-- The Modal -->
                <div id="myModal3" class="modal"> 

                  <!-- Modal content -->
                  <div class="modal-content">
                    <div class="modal-header">
                      <span class="close">&times;</span>
                      <h2>Delete User</h2>
                    </div>
                    <div class="modal-body">
                      <form method="POST"> <!-- form method delete -->
                      <div style="font-size: 20px;margin: 10px;color: white;"></div>

                        Username: <input id="text" type="text" name="user_name"><br><br>
                        
                        <input id="button" type="submit" name="delete" value="Delete"><br><br>
                        </form>
                     </div>
                     <!-- <div class="modal-footer">
                        <h3></h3>
                      </div> -->
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
// Get the button that opens the modal
var btn = document.querySelectorAll("button.modal-button");

// All page modals
var modals = document.querySelectorAll('.modal');

// Get the <span> element that closes the modal
var spans = document.getElementsByClassName("close");

// When the user clicks the button, open the modal
for (var i = 0; i < btn.length; i++) {
 btn[i].onclick = function(e) {
    e.preventDefault();
    modal = document.querySelector(e.target.getAttribute("href"));
    modal.style.display = "block";
 }
}

// When the user clicks on <span> (x), close the modal
for (var i = 0; i < spans.length; i++) {
 spans[i].onclick = function() {
    for (var index in modals) {
      if (typeof modals[index].style !== 'undefined') modals[index].style.display = "none";    
    }
 }
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
    if (event.target.classList.contains('modal')) {
     for (var index in modals) {
      if (typeof modals[index].style !== 'undefined') modals[index].style.display = "none";    
     }
    }
}

</script>


</body>
</html>