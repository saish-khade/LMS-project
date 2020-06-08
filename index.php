<?php 

require_once 'controllers/authController.php'; 


//to verify the user using token
if(isset($_GET['token'])){
    $token=$_GET['token'];
    echo $token;
    verifyUser($token);
}

//to verify the user using token
if(isset($_GET['password-token'])){
    $passwordToken=$_GET['password-token'];
    echo $passwordToken;
    resetPassword($passwordToken);
}


//this below 3 line code is to ensure that user dont come directly to index.php page without logging in...if he had not logged in..he will be redirected to login.php
if(!isset($_SESSION['id'])){
    header('location:login.php');
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Registration system PHP and MySQL</title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
</head>
<body>

	
	<div class="container">
		<div class="row">
			<div class="col-md-4 offset-md-4 form-div login">
                
                <?php if(isset($_SESSION['message'])): ?>
                <div class="alert <?php echo $_SESSION['alert-class']; ?>">
                   <?php 
                    echo $_SESSION['message'];
                    //next two lines are use to confirm that we see you are login message is seen only once
                    unset($_SESSION['message']);
                    unset($_SESSION['alert-class']);
                   ?>
                </div>
                <?php endif; ?>

                <h3>Welcome, <?php echo $_SESSION['username']; ?>!</h3>
                <a href="index.php?logout=1" class="logout">logout</a>
                
                <?php if($_SESSION['verified']) { ?>
                <button class="btn btn-block btn-lg btn-primary">I am verified!</button>
                <?php } ?>

                <?php if($_SESSION['verified'] != True) { ?>
                <div class="alert alert-warning">
                    You need to verify your account.
                    Sign in to your gmail account and click 
                    on the verification link we mailed you 
                    <strong><?php echo $_SESSION['email']; ?></strong>
                </div>
                <?php } ?>
                
                <?php //echo $_SESSION['username'] . $_SESSION['email'] ."<br>" . $_GET['token'] ."<br>" . $_GET['verified']; ?>

			</div>
		</div>
	</div>
</body>
</html>
