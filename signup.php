<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<link rel="stylesheet" href="http://www2.cs.uregina.ca/~rpk158/CS215/style.css" />
		<title>Signup</title>
	</head>
    
	<?php
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
			$email = $_POST['email'];
			$username = $_POST['username'];
			$password = $_POST['password'];
			$birthday = $_POST['birthday'];

			$target_dir = "/home/hercules/r/rpk158/public_html/CS215/uploads/";
			$target_file = "uploads/";
			$path = $target_dir.basename($_FILES["avatar"]["name"]);
			$path_file = $target_file.basename($_FILES["avatar"]["name"]);
            
			$sql = "SELECT * FROM signup_tb WHERE user_email = '$email'";
			$result = $conn->query($sql);
			if ($result->num_rows > 0) {
				echo "<div class='top_error'>Your email already exists. Please login on <a href='login.php'>HERE!</a></div>";
			} else {
				if(move_uploaded_file($_FILES["avatar"]["tmp_name"], $path)) {
					$sql_insert = "INSERT INTO signup_tb (user_imgurl, user_email, username, user_password, user_birthday) VALUES ('$path_file', '$email', '$username', '$password', '$birthday')";
					if ($conn->query($sql_insert) === TRUE) {
						header("Location: login.php");
					} else {
						header("Location: signup.php");
					}
				} else {
					echo $_FILES["avatar"]["error"];
				}
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
			<h1 class="title">SIGNUP</h1>
		</div>
		<div class="body">
			<div class="avatar-form">
				<img src="unnamed.png" alt="avatar" id="avatar_img" style="cursor: pointer" onclick="abatar()" />
			</div>
			<form method="post" id="signup-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" onsubmit="return validateform()" enctype="multipart/form-data">
				<input type="file" name="avatar" id="avatar" style="display:none;" />
				<div class="form-group">
					<label for="email">E-mail address</label>
					<input type="email" id="email" name="email" class="form-control" placeholder="email address"/>
					<small class="form-text error hide">This email already taken!</small>
				</div>
				<div class="form-group">
					<label for="username">Username</label>
					<input type="text" id="username" name="username" class="form-control" placeholder="username"/>
					<small class="form-text error hide">Username is not registered!</small>
				</div>
				<div class="form-group">
					<label for="password">Password</label>
					<input type="password" id="password" name="password" class="form-control" placeholder="password"/>
					<small class="form-text error hide">Password is invalid!</small>
				</div>
				<div class="form-group">
					<label for="password_confirm">Password confirm</label>
					<input type="password" id="password_confirm" name="password_confirm" class="form-control" placeholder="password confirm" />
					<small class="form-text error hide">Password does not match!</small>
				</div>
				<div class="form-group">
					<label for="birthday">Birthday</label>
					<input type="date" id="birthday" name="birthday" class="form-control" placeholder="birthday"/>
				</div>
				<div class="form-group">
					<button type="submit" class="btn btn-primary">Submit</button>
				</div>
			</form>
		</div>
		
		<script> 
		
			function abatar(){
				document.getElementById("avatar").click();
			}

			const inpFile = document.getElementById("avatar");
			const previewImage = document.getElementById("avatar_img");
			inpFile.addEventListener("change", function(){
				const file = this.files[0];
				if (file) {
					const reader = new FileReader();
					reader.addEventListener("load", function() {
						previewImage.setAttribute("src", this.result);
					});
					reader.readAsDataURL(file);
				}
			});
			

			function validateform() {
				var avatar_img = document.getElementById("avatar_img").src;
				var index = avatar_img.lastIndexOf("/");
				if(index !== -1) {     
					avatar_img_name = avatar_img.substring(index+1,avatar_img.length);
				}
				var email = document.getElementById("email").value;
				var username = document.getElementById("username").value;
				var password = document.getElementById("password").value;
				var password_confirm = document.getElementById("password_confirm").value;
				var birthday = document.getElementById("birthday").value;
				
				var atposition=email.indexOf("@");  
				var dotposition=email.lastIndexOf(".");
				var illegalChars = /\W/;
				var paswd = /^(?=.*[0-9])(?=.*[!@#$%^&*])[a-zA-Z0-9!@#$%^&*]/;
				
				if (avatar_img_name == "unnamed.png") {
					alert("Please select your user image!");
					return false;
				}
				
				if (email == "") {
					alert("Please enter E-mail!");
					document.getElementById("email").focus();
					return false;
				} else if (atposition < 1 || dotposition < atposition+2 || dotposition+2 >= email.length) {
					alert("Please enter a valid E-mail!");
					document.getElementById("email").focus();
					return false;
				}
				
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
				
				if (password_confirm == "") {
					alert("Please enter Password confirm!");
					document.getElementById("password_confirm").focus();
					return false;
				} else if ( password_confirm != password ) {
					alert("Password does not match!");
					document.getElementById("password_confirm").focus();
					return false;
				}
				
				if (birthday == "") {
					alert("Please select Birthday!");
					document.getElementById("birthday").focus();
					return false;
				}
			}
		</script>
		
	</body>
</html>
