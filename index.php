<?php 
session_start(); //checks if the user is logged in

	include("connection.php");
	include("functions.php");
    

	if($_SESSION['loggedIn']){
      //allows user access to page if they are confirmed to be logged in
	}
  	else{
      //redirect to the login page if user has not logged in
      header('Location: login.php'); 
	  }  

	//$user_data = check_login($con);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Style3.css">
    <title>Inform a Fish</title>
</head>
<body>
<section class="showcase">
    <header>
      <h2 class="logo">Inform a Fish</h2>
      <div class="toggle"></div>
    </header>
    <video autoplay loop muted playsinline class="video-background ">
      <source src="LakeVideo.mp4" type='video/mp4'>
      <source src="LakeVideo.ogv" type='video/ogg'>
      <source src="LakeVideo.webm" type='video/webm'>
    </video>
    
    <!-- <video src="LakeVideo.mp4" muted loop autoplay></video> -->
    <div class="overlay"></div>
    <div class="text">
      <h2>Never Stop</h2> 
      <h3>Exploring The World</h3>
     <!-- <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
      tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
      quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
      consequat.</p> -->
      <a href="PageUnderConstruction/UnderConstruction.php">Explore</a>
    </div>
    <ul class="social">
      <li><a href="#"><img src="https://i.ibb.co/x7P24fL/facebook.png"></a></li>
      <li><a href="#"><img src="https://i.ibb.co/Wnxq2Nq/twitter.png"></a></li>
      <li><a href="#"><img src="https://i.ibb.co/ySwtH4B/instagram.png"></a></li>
    </ul>
  </section>
  <div class="menu">
    <ul>
      <li><a href="index.php">Home</a></li>
      <!--<li><a href="CatchesTable.php">Catches</a></li>-->
      <li><a href="testTable.php">Catches</a></li>
      <li><a href="weather.html">Weather</a></li>
      <li><a href="lakeFish.php">Lakes and Fish</a></li>
      <li><a href="UserProfile.php">Profile</a><br><br></li>
      <li><a href="logout.php">Logout</a><br><br></li>
	  <li><?php 
	  $page="admin.php";
	  if($_SESSION['is_admin']) //Admin link is only visible to admin
      	echo "<a href='$page'>Admin</a>";
	  else
	  ?></li>
	  	
    </ul>
  </div>
  <script>
    const menuToggle = document.querySelector('.toggle');
    const showcase = document.querySelector('.showcase');

    menuToggle.addEventListener('click', () => {
    menuToggle.classList.toggle('active');
    showcase.classList.toggle('active');
    })
    </script>
</body>
</html>

