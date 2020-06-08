<?php require_once 'controllers/authController.php'; ?>
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
				<form method="post" action="login.php">
				<h3 class="text-center">Login for college</h3>
				<?php if(count($errors) > 0): ?>
						<div class="alert alert-danger">
							<?php foreach($errors as $error):?>	
								<li><?php echo $error;?></li>	
							<?php endforeach?>
						</div>
					<?php endif;?>
						
						<div class="form-group">
							<label>Username</label>
							<input type="text" name="username" class="form-control form-control-lg" value="<?php echo $username; ?>">
						</div>
						<div class="form-group">
							<label>Password</label>
							<input type="password" name="password" class="form-control form-control-lg">
						</div>
						<div class="form-group">
							<button type="submit" class="btn btn-primary btn-block btn-lg" name="login_user">Login</button>
						</div>
						<p class="text-center"> 
							Not yet a member? <a href="register.php">Sign up</a>
						</p>
						<div style="font-size: 0.8em; text-align:center;"><a href="forgot_password.php">Forgot your Password?</a></div>
				</form>
			</div>
		</div>
	</div>
</body>
</html>