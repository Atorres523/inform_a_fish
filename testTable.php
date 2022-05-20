<?php
session_start();
INCLUDE("connection.php");
INCLUDE("functions.php");

if($_SESSION['loggedIn']){
//allows user access to page if they are confirmed to be logged in
}
else{
//redirect to the login page
header('Location: login.php'); 
}
?>
<?php


// if the submit button is pressed then go into if statement
if (isset($_POST["submit"])) { //Enter Catch

    // checks to see is all the fields have data inputed into them if not then enter again with all 
    // required info
    if(!empty($_POST['FishName']) &&!empty($_POST['BodyID'])) {

        // give each entered input their own variable to use later if needed
        $uname = $_SESSION['user_name'];
        $fish = $_POST['FishName'];
        $body = $_POST['BodyID'];
        $DateCaught = $_POST['DateCaught'];
        $SizeCaught = $_POST['SizeCaught'];
        $NativeOrStocked = $_POST['NativeOrStocked'];
        $bait = $_POST['Bait'];
        $SpotID = $_POST['SpotID'];
        //check if DateCaught is empty
        if(empty($DateCaught)){ //If DateCaught is empty, default to current time and date
            $DateCaught = date("Y-m-d H:i:s");
            }

        // pass in the info into a queue that will insert data
        $query = "CALL FishermanCatches('$uname', '$body', '$fish', '$DateCaught', '$SizeCaught', '$NativeOrStocked', '$bait', '$SpotID')"; //STORED PROCEDURE FishermanCatches
        // execute whats in the queue
        $run = mysqli_query($con, $query) or die(mysql_error());
        if($run){

            // print out the info you printed, not necessary 
           /* echo "You entered: " . htmlspecialchars($_POST['Uname']) . " <br>";
            echo "You entered: " . htmlspecialchars($_POST['FishName']) . " <br>";
            echo "You entered: " . htmlspecialchars($DateCaught) . " <br>";
            echo "You entered: " . htmlspecialchars($_POST['BodyID']) . " <br>";
            echo "You entered: " . htmlspecialchars($_POST['SizeCaught']) . " <br>";
            echo "You entered: " . htmlspecialchars($_POST['NativeOrStocked']) . " <br>";
            echo "You entered: " . htmlspecialchars($_POST['Bait']) . " <br>";
            echo "You entered: " . htmlspecialchars($_POST['SpotID']) . " <br>";*/

            //echo "<meta http-equiv='refresh' content='0'>";
            //echo "Form Submitted";
        }
    }
    else
        echo "All fields required";

    
}
?>
<?php
  if(isset($_POST['delete'])) //Delete Catch
  {
    $user_name = $_SESSION['user_name'];
    $date = $_POST['DateCaught'];


    $catch_query = "SELECT * FROM CatchesTable WHERE Username = '$user_name' and DateCaught = '$date'"; 
    $catch_match = mysqli_query($con, $catch_query);

    if(mysqli_num_rows($catch_match)>0)
    {
      $query = "CALL DropCatch('$user_name','$date')"; //STORED PROCEDURE DropCatch
      $query_run = mysqli_query($con, $query);
      if($query_run)
      {
        echo '<script type="text/javascript"> alert("Catch Deleted") </script>';
				//header("Location: userManagement.php");
      }
      else
      {
        echo '<script type="text/javascript"> alert("Catch Not Deleted") </script>';
      }
      
    }
    else{
      echo '<script type="text/javascript"> alert("Catch Does Not exists") </script>';
     
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
    <a href="index.php">Return</a><br>
    <h1>Catch History</h1>
        <thead>
            <tr>
                <th>Name</th>
                <th>Username</th>
                <th>Type</th>
                <th>FishName</th>
                <th>BodyName</th>
                <th>DateCaught</th>
                <th>SizeCaught</th>
                <th>Native Or Stocked</th>
                <th>Bait</th>
                <th>SpotID</th>
                </tr>
        </thead>
        <tbody>
            <?php
            date_default_timezone_set('America/Los_Angeles');
            error_reporting(E_ALL);
            ini_set("log_errors", 1);
            ini_set("display_errors", 1);

            $result = mysqli_query($con,"SELECT * FROM CatchesTable"); //VIEW CatchesTable


            while($row = mysqli_fetch_array($result))
            {
            echo "<tr>";
            echo "<td>" . $row['FLname'] . "</td>";
            echo "<td>" . $row['Username'] . "</td>";
            echo "<td>" . $row['FishermanType'] . "</td>";
            echo "<td>" . $row['FishName'] . "</td>";
            echo "<td>" . $row['Bname'] . "</td>";
            echo "<td>" . $row['DateCaught'] . "</td>";
            echo "<td>" . $row['SizeCaught'] . " in.</td>";
            echo "<td>" . $row['NativeOrStocked'] . "</td>";
            echo "<td>" . $row['Bait'] . "</td>";
            echo "<td>" . $row['SpotID'] . "</td>";
            echo "</tr>";
            }
            echo "</table>";
            ?>

            <h3>Top Record Catches</h3>           

            <div class="container">
            <table id="example" class="table table-striped table-bordered nowrap" style="width:100%">
            <tr>
            <th>Name</th>
            <th>Username</th>
            <th>Amount Caught</th>
            </tr>

            <?php

                $result2 = mysqli_query($con,"select * FROM MostFishCaught"); //VIEW MostFishCaught

                while($row = mysqli_fetch_array($result2))
                {
                echo "<tr>";
                echo "<td>" . $row['Name'] . "</td>";
                echo "<td>" . $row['Username'] . "</td>";
                echo "<td>" . $row['Amount'] . "</td>";
                echo "</tr>";
                }
                echo "</table>";

            ?>

             <h3>Fish in the Morning</h3>           

            <div class="container">
            <table id="example" class="table table-striped table-bordered nowrap" style="width:100%">
            <tr>
            <th>FishName</th>
            <th>Color</th>
            <th>Bait</th>
            <th>Depth</th>
            <th>Region</th>
            <th>TimeOfDay</th>
            <th>Temperature</th>
            </tr>

            <?php

                $result_fish = mysqli_query($con,"select * FROM morningFish"); //VIEW fish in the morning

                while($row = mysqli_fetch_array($result_fish))
                {
                echo "<tr>";
                echo "<td>" . $row['FishName'] . "</td>";
                echo "<td>" . $row['Color'] . "</td>";
                echo "<td>" . $row['Bait'] . "</td>";
                echo "<td>" . $row['Depth'] . "</td>";
                echo "<td>" . $row['Region'] . "</td>";
                echo "<td>" . $row['TOD'] . "</td>";
                echo "<td>" . $row['Temp'] . "</td>";
                echo "</tr>";
                }
                echo "</table>";

            ?>

        </div>

        </tbody>
        <div class="container">
        <button class = "modal-button" href="#myModal1">Enter Catch</button><!-- Enter Catch -->
              </div><br> 
              
                <!-- The Modal -->
                <div id="myModal1" class="modal"> 

                  <!-- Modal content -->
                  <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <span class="close">&times;</span>
                      <h2>Enter Catch</h2>
                    </div>
                    <div class="modal-body">
                      <form method="post">
                      <div style="font-size: 20px;margin: 10px;color: white;"></div>

                        <form action="testTable.php" method="POST">
                        <label>Username: </label><br><input type="text" readonly="true" style="color:#888;" onblur="inputBlur(this)" value=<?php echo $_SESSION['user_name']; ?>><br><br>
                        <!--<label>Fish Caught: </label><br><input type="text" name="FishName"><br><br>-->
                        <label>Fish Caught: </label><br> <!-- Fish Dropdown List - all Fish present in database --> 
                       <select name="FishName">
                           <option hidden selected> -- </option>
                           <?php
                           $user_results = mysqli_query($con, "select FishName from Fish"); // grabs all Fish Names from Fish
                            while($rows = mysqli_fetch_array($user_results))
                            {
                                $fish = $rows['FishName'];
                                echo "<option value='$fish'>$fish</option>";
                            }
                            ?>
                            </select><br><br>
                        <label>Date: </label><br><input type="text" name="DateCaught"><br><br>
                        <label for="BodyID">Body: </label><br>
                            <select name="BodyID" id="BodyID">
                            <option hidden selected> -- </option>
                                <option value='1'>Lake Evans</option>
                                <option value='2'>Castaic Lake</option>
                                <option value='3'>Pyramid Lake</option>
                                <option value='4'>Quail Lake</option>
                                <option value='5'>Lake Isabella</option>
                                <option value='6'>California Aqueduct</option>
                                <option value='7'>San Luis Ressivor</option>
                                <option value='8'>Oneill Forebay</option>
                                <option value='9'>Lake Webb</option>
                                <option value='10'>Lake Ming</option>
                            </select><br><br>
                        
                    <!-- <label>Fish Size: <input type="text" name="SizeCaught"></label> -->
                        <label for="SizeCaught">Size: </label><br>
                            <select name="SizeCaught">
                            <option hidden selected> -- </option>
                                <?php for ($i = 1; $i <= 100; $i++) : ?>
                                    <option value="<?php echo $i; ?>"><?php echo $i; ?> in.</option>
                                <?php endfor; ?>
                            </select><br><br>

                        <label for="NativeOrStocked">Native or Stocked: </label><br>
                            <select name="NativeOrStocked" id="NativeOrStocked">
                                <option hidden selected> -- </option>
                                <option value="Native">Native</option>
                                <option value="Stocked">Stocked</option>
                            </select><br><br>
                        <!--<label>Bait: </label><br><input type="text" name="Bait"><br><br>-->
                        <label>Bait: </label><br> <!-- Baits Dropdown List - all baits present in database --> 
                       <select name="Bait">
                           <option hidden selected> -- </option>
                           <?php
                           $user_results = mysqli_query($con, "select distinct Bait from Fish"); // grabs all Baits from Fish
                            while($rows = mysqli_fetch_array($user_results))
                            {
                                $bait = $rows['Bait'];
                                echo "<option value='$bait'>$bait</option>";
                            }
                            ?>
                            </select><br><br>
                        <!-- <label for="Bait">Bait:</label>
                            <select name="Bait" id="Bait">
                                <option value="worms">Worms</option>
                                <option value="Freshshad">Fresh shad</option>
                                <option value="LiveBait">Live Bait</option>
                                <option value="jig">Jig</option>
                                <option value="micetails">Berkely Micetails</option>
                                <option value="bladebaits">Blade Baits</option>
                                <option value="spooks">Spooks</option>
                            </select> -->
                        <label>SpotID: </label><br><input type="text" name="SpotID"><br><br>
                        <?php include 'coordinates.html'; ?>
                        <button type="submit" name="submit">submit</button>

                    </form><br>  
                        
                     </div>
                      <!-- <div class="modal-footer">
                        <h3></h3>
                      </div> -->
                </div>    
                 </div> 
                        </div>

                 <div class="container">
                 <button class = "modal-button" href="#myModal2">Delete Catch</button><!-- Delete Catch -->
              </div><br> 
              
                <!-- The Modal -->
                <div id="myModal2" class="modal"> 

                  <!-- Modal content -->
                  <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <span class="close">&times;</span>
                      <h2>Delete Catch</h2>
                    </div>
                    <div class="modal-body">
                      <form method="post">
                      <div style="font-size: 20px;margin: 10px;color: white;"></div>

                        <form action="testTable.php" method="POST">
                       
                        <label>Username: </label><br><input type="text" readonly="true" style="color:#888;" onblur="inputBlur(this)" value=<?php echo $_SESSION['user_name']; ?>><br><br>
                        
                        <label>Date: </label><br> 
                       <select name="DateCaught">
                           <option hidden selected> -- </option>
                           <?php
                           $user_name = $_SESSION['user_name'];
                           $user_results = mysqli_query($con, "select DateCaught from Catches where Username = '$user_name'"); // grabs all Dates from Catches
                           if(mysqli_num_rows($user_results)>0)
                              while($rows = mysqli_fetch_array($user_results))
                              {
                                  $date = $rows['DateCaught'];
                                  echo "<option value='$date'>$date</option>";
                              }
                            else
                            {
                              echo "<option disabled>(Empty)</option>";
                            }
                            
                            ?>
                            </select><br><br>

                        <button type="submit" name="delete">Delete</button>

                    </form><br>  
                        
                     </div>
                      <!-- <div class="modal-footer">
                        <h3></h3>
                      </div> -->
                </div>    
                 </div> 

                        </div>




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

<script> // blurs username signaling that it cannot be changed by user
function inputBlur(i) {
    if (i.value == "") { i.value = i.defaultValue; i.style.color = "#888"; }
}
</script>

</body>
</html>
