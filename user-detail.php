<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="http://www2.cs.uregina.ca/~rpk158/CS215/style.css" />
    <title>User detail</title>

    <script>

      //old post delete
      function oldpost_delete(id) {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
          if (this.readyState == 4 && this.status == 200) {
            if (this.responseText == 'ok'){
              alert('Deleted successfully!');
              window.location.href = "http://www2.cs.uregina.ca/~rpk158/CS215/user-detail.php";
            }
          }
        };
        xmlhttp.open("GET", "http://www2.cs.uregina.ca/~rpk158/CS215/like_dislike.php/?old_post=" + id, true);
        xmlhttp.send();
      }

    </script>

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
    echo "<div class='top_error'>Your login information doesn't exist. Please login To check your details <a href='login.php'>HERE!</a></div>";
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
      <h1 class="title">User detail</h1>
    </div>
    <div class="body">
      <?php
      if (isset($_SESSION["user_id"])) 
      {
        $user_id = $_SESSION["user_id"];
        $sql_user = "SELECT * FROM signup_tb WHERE user_id = '$user_id'";
        $result_user = $conn->query($sql_user);
        $row_user = mysqli_fetch_array($result_user,MYSQLI_ASSOC);
  
        $sql = "SELECT p.*, s.user_imgurl, s.username, s.user_birthday FROM post_tb p INNER JOIN signup_tb s ON p.user_id=s.user_id WHERE s.user_id = '$user_id'";
        $result = $conn->query($sql);

        echo "<div class='user-detail'><div class='left'><div class='username'>".$row_user['username']."</div><div class='birthday'>".$row_user['user_birthday']."</div></div>";
        echo "<div class='avatar'><img src='".$row_user['user_imgurl']."' alt='avatar' /></div></div>";
      
        if ($result->num_rows > 0) {
          while($row = mysqli_fetch_assoc($result)) {
            echo "<div class='post-list'><div class='card'><div class='card-header'><div class='card-title'>".$row['post_title']."</div>";
            echo "<div class='card-actions'><span style='margin-right: 5px;'><img id='like_fix' src='like.png' width='18' />".$row['post_like']."</span>";
            echo "<span style='margin-left: 5px'><img id='dislike_fix' src='dislike.png'  width='18' />".$row['post_dislike']."</span></div></div>";
            echo "<div class='card-body'><p style='text-align: left'>".$row['post_content']."</p></div>";
            echo "<div class='card-footer'><div class='card-right'>".$row['post_date']."</div><a class='repost_but' href='http://www2.cs.uregina.ca/~rpk158/CS215/re-post.php/?post_id=".$row['post_id']."'>REPOST</a><a style='cursor:pointer;' class='repost_but' onclick='oldpost_delete(". $row['post_id'] .");'>DELETE</a></div>";
            echo "</div></div>";
          }  
        } else {
          echo "<h2>There is no content that has been posted.</h2>";
        }
      }
      
      ?>
      <div class="button_groun">
        <a type="button" href="http://www2.cs.uregina.ca/~rpk158/CS215/post-list.php"  class="btn btn-primary">Post List</a>
        <a type="button" href="http://www2.cs.uregina.ca/~rpk158/CS215/new-post.php"  class="btn btn-primary">New Post</a>  
      </div>         
    </div>
	
  </body>
</html>
