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

if (isset($_GET['post_like']) && $get_user_id != "zero") {
  $post_id = $_GET['post_like'];
  $sql_sel = "SELECT * FROM post_tb WHERE post_id = '	$post_id'";
  $result_sel = $conn->query($sql_sel);
  $row_sel = mysqli_fetch_array($result_sel, MYSQLI_ASSOC);
  $post_like = $row_sel['post_like'] + 1;
  $sql_insert = "UPDATE post_tb SET post_like = $post_like WHERE post_id = '$post_id'";
  if ($conn->query($sql_insert) === TRUE) {
    $sql_like = "SELECT post_like FROM post_tb  WHERE post_id = '$post_id'";
    $result = $conn->query($sql_like);
    $row_like = $result->fetch_assoc();
    echo $row_like["post_like"];
  } 
}

if (isset($_GET['post_dislike']) && $get_user_id != "zero") {
  $post_id = $_GET['post_dislike'];
  $sql_sel = "SELECT * FROM post_tb WHERE post_id = '	$post_id'";
  $result_sel = $conn->query($sql_sel);
  $row_sel = mysqli_fetch_array($result_sel, MYSQLI_ASSOC);
  $post_dislike = $row_sel['post_dislike'] + 1;
  $sql_insert = "UPDATE post_tb SET post_dislike = $post_dislike WHERE post_id = '$post_id'";

  if ($conn->query($sql_insert) === TRUE) {
    $sql_dislike = "SELECT post_dislike FROM post_tb  WHERE post_id = '$post_id'";
    $result = $conn->query($sql_dislike);
    $row_dislike = $result->fetch_assoc();
    echo $row_dislike["post_dislike"];
  } 
}

if (isset($_POST['like_dislike'])) {
  $sql = "SELECT post_id, post_like, post_dislike FROM post_tb";
  $result = $conn->query($sql);
  while ($row = mysqli_fetch_array($result)){
    $postt_id = $row["post_id"];
    $post_like = $row["post_like"];
    $post_dislike = $row["post_dislike"];
    
    $data[] = array("post_id"=> $postt_id,
      "post_like"=> $post_like,
      "post_dislike" => $post_dislike);
  }
  echo json_encode($data);
}

if (isset($_POST['newpost_check'])) {
  $sql_newpost = "SELECT p.*, s.user_imgurl, s.username FROM post_tb p INNER JOIN signup_tb s ON p.user_id=s.user_id order by p.post_id desc limit 20";
  $result = $conn->query($sql_newpost);
  while ($row = mysqli_fetch_array($result)){
    $postt_id = $row["post_id"];
    $post_title = $row["post_title"];
    $post_content = substr($row['post_content'], 0, 225);
    $post_like = $row["post_like"];
    $post_dislike = $row["post_dislike"];
    $username = $row["username"];
    $user_imgurl = $row["user_imgurl"];
    $post_date = $row["post_date"];

    $data_newpost[] = array("post_id"=> $postt_id,
      "post_title"=> $post_title,
      "post_content"=> $post_content,
      "post_like"=> $post_like,
      "post_dislike"=> $post_dislike,
      "username"=> $username,
      "user_imgurl"=> $user_imgurl,
      "post_date" => $post_date);
  }
  echo json_encode($data_newpost);
}

if (isset($_GET['old_post']) && $get_user_id != "zero") {
  $post_id = $_GET['old_post'];
  $sql_delete = "DELETE FROM post_tb WHERE post_id = '	$post_id'";
  if ($conn->query($sql_delete) === TRUE) {
    echo("ok");
  } 
}

?>
