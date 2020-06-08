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
				<form method="post" action="forgot_password.php">
				<h3 class="text-center">Recover your password</h3>
                <p>
                    Please enter your email address you used to sign up this site and we will
                    assist you to recover your password
                </p>
				<?php if(count($errors) > 0): ?>
						<div class="alert alert-danger">
							<?php foreach($errors as $error):?>	
								<li><?php echo $error;?></li>	
							<?php endforeach?>
						</div>
					<?php endif;?>
						
						<div class="form-group">
							
							<input type="text" name="email" class="form-control form-control-lg" >
						</div>
						
						<div class="form-group">
							<button type="submit" class="btn btn-primary btn-block btn-lg" name="forgot_password">Recover</button>
						</div>
				</form>
			</div>
		</div>
	</div>
</body>
</html>