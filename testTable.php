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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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

        </div>

        </tbody>

        <h1>Enter Catch</h1>
<form action="testTable.php" method="POST">
    <label>Username: </label><br><input type="text" name="Uname"><br><br>
    <label>Fish Caught: </label><br><input type="text" name="FishName"><br><br>
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
                <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
            <?php endfor; ?>
        </select><br><br>

    <label for="NativeOrStocked">Native or Stocked: </label><br>
        <select name="NativeOrStocked" id="NativeOrStocked">
            <option hidden selected> -- </option>
            <option value="Native">Native</option>
            <option value="Stocked">Stocked</option>
        </select><br><br>
    <label>Bait: </label><br><input type="text" name="Bait"><br><br>
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
    <button type="submit" name="submit">submit</button>

</form><br>

<?php


// if the submit button is pressed then go into if statement
if (isset($_POST["submit"])) {

    // checks to see is all the fields have data inputed into them if not then enter again with all 
    // required info
    if(!empty($_POST['Uname']) &&!empty($_POST['FishName']) &&!empty($_POST['BodyID'])) {

        // give each entered input their own variable to use later if needed
        $uname = $_POST['Uname'];
        $fish = $_POST['FishName'];
        $body = $_POST['BodyID'];
        $DateCaught = $_POST['DateCaught'];
        $SizeCaught = $_POST['SizeCaught'];
        $NativeOrStocked = $_POST['NativeOrStocked'];
        $bait = $_POST['Bait'];
        $SpotID = $_POST['SpotID'];
        //check if DateCaught is empty
        if(empty($DateCaught)){
            $DateCaught = date("Y-m-d H:i:s");
            }

        // pass in the info into a queue that will insert data
        $query = "FishermanCatches('$uname', '$body', '$fish', '$DateCaught', '$SizeCaught', '$NativeOrStocked', '$bait', '$SpotID')"; //STORED PROCEDURE FishermanCatches
        // execute whats in the queue
        $run = mysqli_query($con, $query) or die(mysql_error());
        if($run){

            // print out the info you printed, not necessary 
            echo "You entered: " . htmlspecialchars($_POST['Uname']) . " <br>";
            echo "You entered: " . htmlspecialchars($_POST['FishName']) . " <br>";
            echo "You entered: " . htmlspecialchars($DateCaught) . " <br>";
            echo "You entered: " . htmlspecialchars($_POST['BodyID']) . " <br>";
            echo "You entered: " . htmlspecialchars($_POST['SizeCaught']) . " <br>";
            echo "You entered: " . htmlspecialchars($_POST['NativeOrStocked']) . " <br>";
            echo "You entered: " . htmlspecialchars($_POST['Bait']) . " <br>";
            echo "You entered: " . htmlspecialchars($_POST['SpotID']) . " <br>";

            echo "<meta http-equiv='refresh' content='0'>";
            //echo "Form Submitted";
        }
    }
    else
        echo "All fields required";

    
}
?>

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

</body>
</html>
