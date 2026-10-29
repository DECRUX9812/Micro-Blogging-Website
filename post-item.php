<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="http://www2.cs.uregina.ca/~rpk158/CS215/style.css" />
    <title>Post Content</title>
  </head>

  <?php
    session_start();
		require __DIR__ . "/common.php";

    if (isset($_SESSION["user_id"])) {
      $get_user_id = $_SESSION["user_id"];
    } else {
      $get_user_id = "zero";
    }

    
		if (isset($_GET['post_id'])) {
			$post_id = (int)$_GET['post_id'];
      $stmt = $conn->prepare("SELECT p.*, s.user_imgurl, s.username, s.user_birthday FROM post_tb p INNER JOIN signup_tb s ON p.user_id=s.user_id WHERE post_id = ?");
      $stmt->bind_param("i", $post_id);
      $stmt->execute();
      $result = $stmt->get_result();
      $row = $result->fetch_assoc();
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
      <h1 class="title"><?php echo e($row['post_title'] ?? ''); ?></h1>
    </div>
    <div class="body">
      <?php
        if ($result->num_rows > 0) {
          echo "<div class='user-detail'><div class='left'><div class='username'>".e($row['username'])."</div><div class='birthday'>".e($row['user_birthday'])."</div></div>";
          echo "<div class='avatar'><img src='http://www2.cs.uregina.ca/~rpk158/CS215/".e($row['user_imgurl'])."' alt='avatar' /></div></div>";
          echo "<div class='post-list'><div class='card'><div class='card-header'><div class='card-title'>".e($row['post_title'])."</div>";
          echo "<div class='card-actions'><span style='margin-right: 5px;'><img id='like_fix' src='http://www2.cs.uregina.ca/~rpk158/CS215/like.png' width='18' onclick='like()' />".$row['post_like']."</span>";
          echo "<span style='margin-left: 5px'><img id='dislike_fix' src='http://www2.cs.uregina.ca/~rpk158/CS215/dislike.png'  width='18' onclick='dislike()' />".$row['post_dislike']."</span></div></div>";
          echo "<div class='card-body'><p style='text-align: left'>".e($row['post_content'])."</p></div>";
          echo "<div class='card-footer'><div class='card-right'>".e($row['post_date'])."</div></div></div></div>";  
        } else {
          echo "<h2>There is no content that has been posted.</h2>";
        }
      ?>  
      <div class="button_groun">
        <a type="button" href="http://www2.cs.uregina.ca/~rpk158/CS215/post-list.php"  class="btn btn-primary">Post List</a>
      </div>  
    </div>
    
  </body>
</html>
