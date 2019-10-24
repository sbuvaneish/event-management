<?php

session_start();

if(!isset($_SESSION['user_id'])) {
	header("Location: /login.php");
}

unset($_SESSION['event_id']);

?>

<div align="center" class="container">
	Welcome to Event Management System
</div>
<div align="right" class="container">
	User: <?=$_SESSION['email']?>
</div>

<br><br>

<div class="list-group">
  <a href="edit.php" class="list-group-item list-group-item-action">Create a new event</a><br>
  <a href="search.php" class="list-group-item list-group-item-action">Search for events</a><br>
  <a href="index.php" class="list-group-item list-group-item-action">Logout</a><br>
</div>