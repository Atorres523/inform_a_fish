<?php 
session_start(); //checks if the user is logged in

	include("connection.php");
	include("functions.php");
    

	if($_SESSION['loggedIn']){
      //allows user access to page if they are confirmed to be logged in
	  include("index3.html");
	}
  	else{
      //redirect to the login page if user has not logged in
      header('Location: login.php'); 
	  }  

	//$user_data = check_login($con);

?>

