<!DOCTYPE html>
<html>
<head>
	<title>OJT</title>
	<link rel="stylesheet" type="text/css" href="/web-systems-development/ServerSide/css/style_server_index.css">
	<link href="https://fonts.googleapis.com/css?family=Poppins:600&display=swap" rel="stylesheet">
	<script src="https://kit.fontawesome.com/a81368914c.js"></script>
	<meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
	<img class="wave" src="/web-systems-development/ServerSide/img/wave.png">
	<div class="container">
		<div class="img">
			<img src="/web-systems-development/ServerSide/img/bg.svg">
		</div>
		<div class="login-content">
			<form id="login-form" method="POST" action="/web-systems-development/ServerSide/php/admin_login.php" class="login-form">
				<img src="/web-systems-development/ServerSide/img/Saint_Louis_University_PH_Logo.svg.png">
				<h2 class="title">Welcome</h2>
           		<div class="input-div one">
           		   <div class="i">
           		   		<i class="fas fa-user"></i>
           		   </div>
           		   <div class="div">	
           		   		<input type="text" id="username" name="username" placeholder="Username">
           		   </div>
           		</div>
           		<div class="input-div pass">
           		   <div class="i"> 
           		    	<i class="fas fa-lock"></i>
           		   </div>
           		   <div class="div">
           		    	<input type="password" id="password" name="password" placeholder="Password">
            	   </div>
            	</div>
				<div class="input-div role">
					<div class="i">
						<i class="fas fa-user"></i>
					</div>
					<div class="div">
						<select id="user-type" name="user-type">
							<option value="Administrator">Administrator</option>
						</select>
					</div>
				</div>
            	<a href="#">Forgot Password?</a>
            	<input type="submit" class="btn" value="Login">

				<label class="remember-me">
					<input type="checkbox" name="remember" id="remember">
					Remember me
				</label>
            </form>
        </div>
    </div>
</body>
</html>