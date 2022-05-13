<?php 
session_start(); //checks if the user is logged in

	include("connection.php");
	include("functions.php");

	if($_SESSION['loggedIn'] && $_SESSION["is_admin"]){ //only allows someone who is logged in and an admin
		//allows user access to page if they are confirmed to be logged in
				include("admin.html");
		}
		else if($_SESSION['loggedIn']){
			// redirect admin view if user is not admin back to User view
				header('Location: index.php');
		}
		else{
		//redirect to the login page
		header('Location: login.php'); 
		}

	

    //echo"hello";

	//$user_data = check_login($con);

?>