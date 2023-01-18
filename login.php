<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<link rel="stylesheet" href="style.css" />
		<title>Login</title>
	</head>

	<?php
	    session_start();
		$servername = "localhost";
		$username = "rpk158";
		$password = "ritesh";
		$dbname = "rpk158";
		// Create connection
		$conn = new mysqli($servername, $username, $password, $dbname);
		// Check connection
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}

		if ($_SERVER["REQUEST_METHOD"] == "POST") {
			$username = $_POST['username'];
			$password = $_POST['password'];
	
			$sql = "SELECT * FROM signup_tb WHERE username = '$username' AND user_password = '$password'";
			$result = $conn->query($sql);
			if ($result->num_rows > 0) {
				$row = mysqli_fetch_array($result,MYSQLI_ASSOC);

				$_SESSION["user_id"] = $row['user_id'];

				header("Location: http://www2.cs.uregina.ca/~rpk158/CS215/user-detail.php");
			} else {
				echo "<div class='top_error'>Your information doesn't exist. Please sign up on <a href='signup.php'>HERE!</a></div>";
			}
		}
	?>
    <nav class="menu">
		<ul>
		    <li><a href="http://www2.cs.uregina.ca/~rpk158/CS215/login.php">Login</a></li>
			<li><a href="http://www2.cs.uregina.ca/~rpk158/CS215/signup.php">Sign Up</a></li>
			<li><a href="http://www2.cs.uregina.ca/~rpk158/CS215/post-list.php">Post List</a></li>
			<li><a href="http://www2.cs.uregina.ca/~rpk158/CS215/user-detail.php">User Detail</a></li>
			<li><a href="http://www2.cs.uregina.ca/~rpk158/CS215/new-post.php">New Post</a></li>
		</ul>
	</nav>
	<body>
	  
		<div class="header">
			<h1 class="title">LOGIN</h1>
		</div>
		<div class="body">
			<form method="post" id="login-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" onsubmit="return validateform()" enctype="multipart/form-data">
				<div class="form-group">
					<label for="username">Username</label>
					<input type="text" class="form-control" name="username" id="username" placeholder="username"/>
					<small class="form-text error hide">Username is not registered!</small>
				</div>
				<div class="form-group">
					<label for="password">Password</label>
					<input type="password" class="form-control" name="password" id="password" placeholder="password"/>
					<small class="form-text error hide">Password is invalid!</small>
				</div>
				<div class="form-group">
					<button type="submit" class="btn btn-primary">Submit</button>
				</div>
			</form>
		</div>
		
		<script>
			function validateform() {
				var username = document.getElementById("username").value;
				var password = document.getElementById("password").value;
				
				var illegalChars = /\W/;
				var paswd = /^(?=.*[0-9])(?=.*[!@#$%^&*])[a-zA-Z0-9!@#$%^&*]/;
				
				if (username == "") {
					alert("Please enter Username!");
					document.getElementById("username").focus();
					return false;
				} else if (illegalChars.test(username)) {
					alert("Please enter valid Username. Use only numbers and alphabets!");
					document.getElementById("username").focus();
					return false;
				}
				
				if (password == "") {
					alert("Please enter Password!");
					document.getElementById("password").focus();
					return false;
				} else if ( password.length < 6 ) {
					alert("Password must have 6 characters or longer!");
					document.getElementById("password").focus();
					return false;
				}  else if (password.indexOf(' ') >= 0){
					alert("Password must have no spaces!");
					document.getElementById("password").focus();
					return false;
				}
				
				if ( password.match(paswd) ) {
					console.log("Password matched!")
				} else { 
					alert("Password must contain at least one numeric digit and a special character!");
					document.getElementById("password").focus();
					return false;
				}
			}
		</script>
	
  </body>
</html>
