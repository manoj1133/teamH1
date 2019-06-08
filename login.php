<?php
session_start();

$UNAME = $_SESSION['UNAME'];	//retrieve USERNAME
$panelMsg = $_SESSION['panelMsg'];
unset($_SESSION['panelMsg']);

if($UNAME != NULL){
	header("Location: profile.php");
	die();
}

// Connect to the database. Please change the password in the following line accordingly
include 'db.php';
$db = init_db();

$result = pg_query($db, "SELECT uid FROM users WHERE username = '$_POST[username]' AND pssword = '$_POST[userpass]'");
$isBannedResult = pg_query($db, "SELECT isBanned FROM users WHERE username = '$_POST[username]' AND pssword = '$_POST[userpass]'");
$isAdminResult = pg_query($db, "SELECT isAdmin FROM users WHERE username = '$_POST[username]' AND pssword = '$_POST[userpass]'");

$isBanned = pg_fetch_result($isBannedResult, 0, 0);
$isAdmin = pg_fetch_result($isAdminResult,0 ,0);


if (isset($_POST['submit'])) {
	$userRow = pg_fetch_assoc($result);
	$userFound = pg_num_rows($result);
	if ($userFound < 1) {
		$panelMsg = "Invaild Usernname or Password";
	} 

	else if ($isBanned == 't') {
		$panelMsg = "User is Banned! Please contact the administrator for more details";
	}

	else if ($isBanned == 'f') {
		if($isAdmin == 't') {
			$_SESSION['ADMIN'] = "true";
		} else {
			$_SESSION['ADMIN'] = NULL;
		}
		$_SESSION['UID'] = $userRow[uid];
		$_SESSION['UNAME'] = $_POST[username];
		$_SESSION['OWNPROJECT'] = NULL;
		$host  = $_SERVER['HTTP_HOST'];
		$uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
		$extra = 'profile.php';
		header("Location: http://$host$uri/$extra");
		exit;
	}
}

	if(isset($_POST['submitCreate'])){
		header("Location: register.php");
	}

?>
  
<html>
<head>
  <title>Login Page</title>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

  <!-- Import CSS Files -->
  <link rel="stylesheet" href="css/w3.css">
  <meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
<!--===============================================================================================-->	
	<link rel="icon" type="image/png" href="images/icons/favicon.ico"/>
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/bootstrap/css/bootstrap.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="fonts/font-awesome-4.7.0/css/font-awesome.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="fonts/iconic/css/material-design-iconic-font.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/animate/animate.css">
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="vendor/css-hamburgers/hamburgers.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/animsition/css/animsition.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/select2/select2.min.css">
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="vendor/daterangepicker/daterangepicker.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="css/util.css">
	<link rel="stylesheet" type="text/css" href="css/main.css">
<!--
  
</head>

<body>	
<!-- Nagivation Bar -->
<?php
if($UNAME == NULL){
	$menu = file_get_contents('menu.html');
	echo $menu;
}
else{
	$menu = file_get_contents('menu-loggedin.html');
	echo $menu;
}
//Display error message pannel
if($panelMsg != ""){
	echo "<div class='w3-panel w3-yellow'><p>" . $panelMsg . "</p></div>";
}
?>

<div class="limiter">
		<div class="container-login100" style="background-image: url('images/bg-01.jpg');">
			<div class="wrap-login100 p-l-55 p-r-55 p-t-65 p-b-54">
				<form class="login100-form validate-form" action="login.php" method="POST">
					<span class="login100-form-title p-b-49">
						Login
					</span>

					<div class="wrap-input100 validate-input m-b-23" data-validate = "Username is reauired">
						<span class="label-input100">Username</span>
						<input class="input100" type="text" name="username" placeholder="Type your username">
						<span class="focus-input100" data-symbol="&#xf206;"></span>
					</div>

					<div class="wrap-input100 validate-input" data-validate="Password is required">
						<span class="label-input100">Password</span>
						<input class="input100" type="password" name="userpass" placeholder="Type your password">
						<span class="focus-input100" data-symbol="&#xf190;"></span>
					</div>
					
					<div class="text-right p-t-8 p-b-31">
						<a href="#">
							Forgot password?
						</a>
					</div>
					
					<div class="container-login100-form-btn">
						<div class="wrap-login100-form-btn">
							<div class="login100-form-bgbtn"></div>
							<button class="login100-form-btn" name="submit" value="Login">
								Login
							</button>
						</div>
					</div>

					<div class="txt1 text-center p-t-54 p-b-20">
						<span>
							Or Sign Up Using
						</span>
					</div>

					<div class="flex-c-m">
						<a href="#" class="login100-social-item bg1">
							<i class="fa fa-facebook"></i>
						</a>

						<a href="#" class="login100-social-item bg2">
							<i class="fa fa-twitter"></i>
						</a>

						<a href="#" class="login100-social-item bg3">
							<i class="fa fa-google"></i>
						</a>
					</div>

					<div class="flex-col-c p-t-155">
						<span class="txt1 p-b-17">
							Or Sign Up Using
						</span>
						<a href="register.php" class="txt2">
							Sign Up
						</a>
					</div>
				</form>
			</div>
		</div>
	</div>
	

	<div id="dropDownSelect1"></div>
	
<!--===============================================================================================-->
	<script src="vendor/jquery/jquery-3.2.1.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/animsition/js/animsition.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/bootstrap/js/popper.js"></script>
	<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/select2/select2.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/daterangepicker/moment.min.js"></script>
	<script src="vendor/daterangepicker/daterangepicker.js"></script>
<!--===============================================================================================-->
	<script src="vendor/countdowntime/countdowntime.js"></script>
<!--===============================================================================================-->
	<script src="js/main.js"></script>

<?php
if($UNAME == NULL){
	$menu = file_get_contents('menu.html');
	echo $menu;
}
else{
	$menu = file_get_contents('menu-loggedin.html');
	echo $menu;
}
//Display error message pannel
if($panelMsg != ""){
	echo "<div class='w3-panel w3-yellow'><p>" . $panelMsg . "</p></div>";
}
?>
