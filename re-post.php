<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="http://www2.cs.uregina.ca/~rpk158/CS215/style.css" />
    <title>Repost</title>
  </head>
 
  <?php
        session_start();
		require __DIR__ . "/common.php";

		if (isset($_SESSION["user_id"])) {
			$get_user_id = $_SESSION["user_id"];
		} 
        
		if (isset($_GET['post_id'])) {
			$post_id = (int)$_GET['post_id'];
			$stmt = $conn->prepare("SELECT * FROM post_tb WHERE post_id = ?");
			$stmt->bind_param("i", $post_id);
			$stmt->execute();
			$row = $stmt->get_result()->fetch_assoc();
		} else {
			echo "<div class='top_error'>Your information doesn't exist. Please sign up on <a href='http://www2.cs.uregina.ca/~rpk158/CS215/signup.php'>HERE!</a></div>";
			$row['post_title'] = "";
			$row['post_content'] = "";	
		}

		if ($_SERVER["REQUEST_METHOD"] == "POST") {
			$user_id = $_SESSION["user_id"];
			$repost_id = (int)$_POST['hideId'];
			$post_title = $_POST['post_title'];
			$post_content = $_POST['post_content'];
			$post_date = date("Y-m-d") .", ". date("h:i:sa");

			$ins = $conn->prepare("INSERT INTO post_tb (user_id, post_title, post_content, post_date, post_like, post_dislike, orignal_post_id) VALUES (?, ?, ?, ?, '0', '0', ?)");
			$ins->bind_param("isssi", $user_id, $post_title, $post_content, $post_date, $repost_id);
			if ($ins->execute()) {
				header("Location: http://www2.cs.uregina.ca/~rpk158/CS215/post-list.php");
				exit;
			} else {
				header("Location: http://www2.cs.uregina.ca/~rpk158/CS215/user-detail.php");
				exit;
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
      <h1 class="title">Repost</h1>
    </div>
    <div class="body">
      <form method="post" id="post-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" onsubmit="return validateform()" enctype="multipart/form-data">
	  	<input type="hidden" id="hideId" name="hideId" value="<?php echo e($post_id); ?>">
        <div class="form-group">	
          <label for="username">Title</label>
          <input type="text" id="post_title" name= "post_title" class="form-control" placeholder="title" value="<?php echo e($row['post_title']) ?>"/>
        </div>
        <div class="form-group">
          <label for="content">Content</label>
          <textarea class="form-control" id="post_content" name="post_content" placeholder="content" oninput="myFunction()"><?php echo e($row['post_content']) ?></textarea>
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
