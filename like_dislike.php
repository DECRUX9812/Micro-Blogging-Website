<?php
session_start();
require __DIR__ . "/common.php";

if (isset($_SESSION["user_id"])) {
  $get_user_id = $_SESSION["user_id"];
} else {
  $get_user_id = "zero";
}

if (isset($_GET['post_like']) && $get_user_id != "zero") {
  $post_id = (int)$_GET['post_like'];
  $stmt = $conn->prepare("UPDATE post_tb SET post_like = post_like + 1 WHERE post_id = ?");
  $stmt->bind_param("i", $post_id);
  if ($stmt->execute()) {
    $stmt->close();
    $sel = $conn->prepare("SELECT post_like FROM post_tb WHERE post_id = ?");
    $sel->bind_param("i", $post_id);
    $sel->execute();
    $row_like = $sel->get_result()->fetch_assoc();
    echo $row_like["post_like"];
    $sel->close();
  }
}

if (isset($_GET['post_dislike']) && $get_user_id != "zero") {
  $post_id = (int)$_GET['post_dislike'];
  $stmt = $conn->prepare("UPDATE post_tb SET post_dislike = post_dislike + 1 WHERE post_id = ?");
  $stmt->bind_param("i", $post_id);
  if ($stmt->execute()) {
    $stmt->close();
    $sel = $conn->prepare("SELECT post_dislike FROM post_tb WHERE post_id = ?");
    $sel->bind_param("i", $post_id);
    $sel->execute();
    $row_dislike = $sel->get_result()->fetch_assoc();
    echo $row_dislike["post_dislike"];
    $sel->close();
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
  $post_id = (int)$_GET['old_post'];
  // Ownership check: users may only delete their own posts.
  $stmt = $conn->prepare("DELETE FROM post_tb WHERE post_id = ? AND user_id = ?");
  $stmt->bind_param("ii", $post_id, $get_user_id);
  if ($stmt->execute() && $stmt->affected_rows > 0) {
    echo("ok");
  }
  $stmt->close();
}

?>
