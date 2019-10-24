<?php

session_start();
$_SESSION = array();
session_destroy();

?>

<div align="center" class="container">
	Welcome to Event Management System
</div>

<br><br>

<div class="container">
<a href="register.php">Register Here</a>
<br>
<a href="login.php">Login Here</a>
</div>
