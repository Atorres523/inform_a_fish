<?php
session_start();
	$dbhost = "";
	$dbuser = "";
	$dbpass = "";
	$dbname = "";
	$con = mysqli_connect($dbhost,$dbuser,$dbpass,$dbname);
	if(!$con)
	{
		die("failed to connect!");
	}

