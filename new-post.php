<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="style.css" />
    <title>New Post</title>
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

		if (isset($_SESSION["user_id"])) {
			$get_user_id = $_SESSION["user_id"];
		} else {
			$get_user_id = "zero";
		}

		if($get_user_id === "zero")
  		{
    		echo "<div class='top_error'>Your login information doesn't exist. Please login To post<a href='login.php'>HERE!</a></div>";
  		}


		if ($_SERVER["REQUEST_METHOD"] == "POST") {	
			$user_id = $_POST['hideId'];
			if ($user_id == "zero"){
				echo "<div class='top_error'>Your login information doesn't exist. Please login on <a href='login.php'>HERE!</a></div>";
			} else {
				$post_title = $_POST['post_title'];
				$post_content = $_POST['post_content'];
				$post_date = date("Y-m-d") .", ". date("h:i:sa");
				
				$sql_insert = "INSERT INTO post_tb (user_id, post_title, post_content, post_date, post_like, post_dislike) VALUES ('$user_id', '$post_title', '$post_content', '$post_date', '0', '0')";
				if ($conn->query($sql_insert) === TRUE) {
					header("Location: http://www2.cs.uregina.ca/~rpk158/CS215/post-list.php");
				} else {
					header("Location: http://www2.cs.uregina.ca/~rpk158/CS215/new-post.php");
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
			<li><a href='logout.php'>LOGOUT</a></li>
    </ul>
  </nav>
  <body>
    <div class="header">
      <h1 class="title">New Post</h1>
    </div>
    <div class="body">
      <form method="post" id="post-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" onsubmit="return validateform()" enctype="multipart/form-data">
	  	<input type="hidden" id="hideId" name="hideId" value="<?php echo $get_user_id; ?>">
        <div class="form-group">
          <label for="username">Title</label>
          <input type="text" id="post_title" name= "post_title" class="form-control" placeholder="title"/>
        </div>
        <div class="form-group">
          <label for="content">Content</label>
          <textarea class="form-control" id="post_content" name="post_content" placeholder="content" oninput="myFunction()"></textarea>
        </div>
        <div class="form-group">
          <button type="submit" class="btn btn-primary">Submit</button>
        </div>
      </form>
	  <h3 id="remain_chat">Number of characters remaining : 256</h3>
    </div>
	
	<script>
	    function validateform() {
			var post_title = document.getElementById("post_title").value;
			var post_content = document.getElementById("post_content").value;
			
			if (post_title == "") {
				alert("Please enter post title!");
				document.getElementById("post_title").focus();
				return false;
			}
			
			if ( post_content.length > 256 ) {
			    var exceeded_chat = post_content.length - 256;
				alert("Post content must have 256 characters or less!\nRecent characters : " + post_content.length + " characters\nExceeded characters : "+exceeded_chat);
				document.getElementById("post_content").focus();
				return false;
			} else if (post_content == "") {
				alert("Please enter post content!");
				document.getElementById("post_content").focus();
				return false;
			}
		}
		
		function myFunction() {
		    var post_content1 = document.getElementById("post_content").value;
		    var leaved_chat = 256 - post_content1.length;
		    document.getElementById("remain_chat").innerHTML = "Number of characters remaining : " + leaved_chat;
		}
	</script>
	
  </body>
</html>
