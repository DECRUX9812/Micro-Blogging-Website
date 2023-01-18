<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="http://www2.cs.uregina.ca/~rpk158/CS215/style.css" />
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <title>Post List</title>

  <script>

    //AJAX to submit the like
    function like_val(id) {
      var xmlhttp = new XMLHttpRequest();
      xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("like_"+id).innerHTML = this.responseText;
        }
      };
      xmlhttp.open("GET", "http://www2.cs.uregina.ca/~rpk158/CS215/like_dislike.php/?post_like=" + id, true);
      xmlhttp.send();
    }

    //AJAX to submit the dislike
    function dislike_val(id) {
      var xmlhttp = new XMLHttpRequest();
      xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("dis_like_"+id).innerHTML = this.responseText;
        }
      };
      xmlhttp.open("GET", "http://www2.cs.uregina.ca/~rpk158/CS215/like_dislike.php/?post_dislike=" + id, true);
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

$sql = "SELECT p.*, s.user_imgurl, s.username FROM post_tb p INNER JOIN signup_tb s ON p.user_id=s.user_id order by p.post_id desc limit 20";
$result = $conn->query($sql);

if (isset($_SESSION["user_id"])) {
  $get_user_id = $_SESSION["user_id"];
} else {
  $get_user_id = "zero";
}

if ($get_user_id == "zero"){
  echo "<div class='top_error'>Your login information doesn't exist. Please login on <a href='http://www2.cs.uregina.ca/~rpk158/CS215/login.php'>HERE!</a></div>"; 
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
    <h1 class="title">Post List</h1>
  </div>
  <div class="body">
    <?php
    if ($result->num_rows > 0) {
      echo "<div id='main_body'>";
      while ($row = mysqli_fetch_assoc($result)) {
        $post_content = substr($row['post_content'], 0, 225);
        echo "<div class='post-list'><div class='card'>";
        echo "<div class='card-header'><div class='card-title' id='post_title_". $row['post_id'] ."'>" . $row['post_title'] . "</div><div class='card-actions'>";
        echo "<span style='margin-right: 5px;'><form class='like'><button class='like_but' type='button' onclick='like_val(". $row['post_id'] .");'><img id='like' src='http://www2.cs.uregina.ca/~rpk158/CS215/like.png' style='width:18px;' /></button></form><small id='like_". $row['post_id'] ."'>" . $row['post_like'] . "</small></span>";
        echo "<span style='margin-left: 5px;'><form class='dis_like'><button class='dislike_but' type='button' onclick='dislike_val(". $row['post_id'] .");'><img id='dislike' src='http://www2.cs.uregina.ca/~rpk158/CS215/dislike.png' style='width:18px;' /></button></form><small id='dis_like_". $row['post_id'] ."'>" . $row['post_dislike'] . "</small></span></div></div>";
        echo "<div class='card-body'><p style='text-align: left;' id='post_content_". $row['post_id'] ."'>" . $post_content . " ...</p></div>";
        echo "<div class='card-footer'><a href='http://www2.cs.uregina.ca/~rpk158/CS215/post-item.php/?post_id=" . $row['post_id'] . "' class='card-left' style='text-decoration: none;'>";
        echo "<div class='auth-img'><img src='" . $row['user_imgurl'] . "' alt='avatar'  id='user_imageurl_". $row['post_id'] ."' /></div><div class='auth-name' style='padding-left: 10px;'  id='username_". $row['post_id'] ."'>" . $row['username'] . "</div></a>";
        echo "<div class='card-right' id='post_date_". $row['post_id'] ."'>" . $row['post_date'] . "</div></div>";

        if ($row['orignal_post_id'] != NULL) 
        {
          $orignal_post_id = $row['orignal_post_id'];
          $sql1 = "SELECT * FROM post_tb WHERE post_id = '$orignal_post_id' ";
          $result2 = $conn->query($sql1);
          $row2 = mysqli_fetch_assoc($result2);
          $orignal_user_id= $row2['user_id'];

          $sql3 = "SELECT * FROM signup_tb Where user_id= '$orignal_user_id'";
          $result3 = $conn->query($sql3);
          $row3 = mysqli_fetch_assoc($result3);

          echo "<a href='http://www2.cs.uregina.ca/~rpk158/CS215/post-item.php/?post_id=" . $row2['post_id']."'>CLICK HERE TO SEE ORIGNAL POST POSTED BY  ".$row3['username']."</a>";
        
        }

        echo "<p><a class='repost_but' href='http://www2.cs.uregina.ca/~rpk158/CS215/re-post.php/?post_id=" . $row['post_id'] . "'>REPOST</a></p>";

        echo "</div></div>";
      }
      echo "</div>";
    } else {
      echo "<h2>There is no content that has been posted.</h2>";
    }
    ?>
  </div>

  <script>
    $(document).ready(function(){

      //Periodic check of the like and dislike counts using AJAX
      setInterval(periodicCheck, 5000);
      function periodicCheck() {
        var periodic_check=1;
        $.ajax({
          url: 'http://www2.cs.uregina.ca/~rpk158/CS215/like_dislike.php',
          type: 'post',
          data:{like_dislike:periodic_check},
          success:function(result){
            var data = JSON.parse(result);
            var len = data.length;
            for(var i=0; i<len; i++){
              $('#like_'+data[i]['post_id']).text(data[i]['post_like']);
              $('#dis_like_'+data[i]['post_id']).text(data[i]['post_dislike']);
              console.log(data[i]['post_like']);
              console.log(data[i]['post_dislike']);
            }
          },
        });
      }


      //Periodic check for new posts
      setInterval(periodicCheck_newpost, 5000);
      function periodicCheck_newpost() {
        var periodic_check_newpost=2;
        $.ajax({
          url: 'http://www2.cs.uregina.ca/~rpk158/CS215/like_dislike.php',
          type: 'post',
          data:{newpost_check:periodic_check_newpost},
          success:function(result){
            var data_post = JSON.parse(result);
            var post_len = data_post.length;
            let content = '';

            for(var i=0; i<post_len; i++){
              content += `<div class='post-list'><div class='card'>`;
              content += `<div class='card-header'><div class='card-title' id='post_title_${data_post[i].post_title}'>${data_post[i].post_title}</div><div class='card-actions'>`;
              content += `<span style='margin-right: 5px;'><form class='like'><button class='like_but' type='button' onclick='like_val(${data_post[i].post_id});'><img id='like' src='http://www2.cs.uregina.ca/~rpk158/CS215/like.png' style='width:18px;' /></button></form><small id='like_${data_post[i].post_id}'>${data_post[i].post_like}</small></span>`;
              content += `<span style='margin-left: 5px;'><form class='dis_like'><button class='dislike_but' type='button' onclick='dislike_val(${data_post[i].post_id});'><img id='dislike' src='http://www2.cs.uregina.ca/~rpk158/CS215/dislike.png' style='width:18px;' /></button></form><small id='dis_like_${data_post[i].post_id}'>${data_post[i].post_dislike}</small></span></div></div>`;
              content += `<div class='card-body'><p style='text-align: left;' id='post_content_${data_post[i].post_id}'>${data_post[i].post_content} ...</p></div>`;
              content += `<div class='card-footer'><a href='http://www2.cs.uregina.ca/~rpk158/CS215/post-item.php/?post_id=${data_post[i].post_id}' class='card-left' style='text-decoration: none;'>`;
              content += `<div class='auth-img'><img src='${data_post[i].user_imgurl}' alt='avatar'  id='user_imageurl_${data_post[i].post_id}' /></div><div class='auth-name' style='padding-left: 10px;'  id='username_${data_post[i].post_id}'>${data_post[i].username}</div></a>`;
              content += `<div class='card-right' id='post_date_${data_post[i].post_id}'>${data_post[i].post_date}</div></div>`;
              content += `<p><a class='repost_but' href='http://www2.cs.uregina.ca/~rpk158/CS215/re-post.php/?post_id=${data_post[i].post_id}'>REPOST</a></p></div></div>`;
            }
            $('#main_body').html(content);
          },
        });
      };
    });
  </script>
</body>

</html>