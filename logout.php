<?php

session_start();

if(isset($_SESSION['user_name'])) //replace with $user_name
{
	unset($_SESSION['user_name']);

}

$_SESSION['loggedIn'] = false; //Tells website user is no longer logged in

header("Location: login.php"); //Sends user back to login
die;