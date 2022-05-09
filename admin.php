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

    echo"hello";

	//$user_data = check_login($con);

?>