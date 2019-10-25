<?php

session_start();

if(!isset($_SESSION['user_id'])) {
	header("Location: /login.php");
}

unset($_SESSION['event_id']);

require 'database.php';


function getFriendsList() {

	global $conn;
	$query = "SELECT user_2 FROM friends WHERE user_1 = :this_id";
	$records = $conn->prepare($query);
	$records->bindParam(':this_id', $_SESSION['user_id']);
	$records->execute();
	$results = $records->fetchAll(PDO::FETCH_ASSOC);

	$processed_results = [];

	foreach($results as $result) {
		array_push($processed_results, $result['user_2']);
	}

	return $processed_results;

}


if($_SERVER['REQUEST_METHOD'] === "POST") {

	$friends = getFriendsList();

	$query = "SELECT id, name, email FROM users WHERE name LIKE CONCAT('%', :search_term, '%')";
	$records = $conn->prepare($query);
	$records->bindParam(':search_term', $_POST['search']);
	$records->execute();
	$results = $records->fetchAll(PDO::FETCH_ASSOC);

}


?>


<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<!------ Include the above in your HEAD tag ---------->

<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" />


<div align="center" class="container">
	Welcome to Event Management System
</div>
<div align="right" class="container">
	User: <?=$_SESSION['email']?>
</div>

<br><br>


<form class="form-horizontal" action='friends.php' method="POST">
  <fieldset>
  	<div class="control-group">
      <label class="control-label" for="search" style="color:green">Search friends!</label>
      <div class="controls">
        <input type="text" id="search" name="search" placeholder="Search" class="input-xlarge" value="<?= $_POST['search'] ?>">
      </div>
    </div>
    <br>

    <div class="control-group">
      <!-- Button -->
      <div class="controls">
        <button class="btn btn-success" name="submit">Search</button>
      </div>
    </div>
    <br>

    <div class="control-group">
      <!-- Button -->
      <div class="controls">
        <button class="btn btn-success" name="view_friends">View my friends</button>
      </div>
    </div>

    <br><br>

    <div class="control-group">
    	<div class="controls">
    		<a href="main.php">Back to main page</a>
    	</div>
    </div>

  </fieldset>
</form>



<?php if($_SERVER['REQUEST_METHOD'] === "POST") { ?>
	<div style="color:green">

			<?php
				$count = 0;
				foreach($results as $record) {
					if(!isset($_POST['view_friends']) OR in_array($record['id'], $friends)) {
						$count++;
					} 
				}
			?>

			Total Results: <?=$count?><br>
	</div>


	<?php foreach($results as $record) { ?>

		<?php if(!isset($_POST['view_friends']) OR in_array($record['id'], $friends)) { ?>

		<center>
					
					<div class="col-md-3 col-sm-6">
				        <div class="product-grid" style="background-color:lightgrey">
							 <div class="product-content">
							 	Name: <?=$record['name']?><br>
							 	Email: <?=$record['email']?><br>
							 	<?php if(in_array($record['id'], $friends)) { ?>
							 		<a href="unfriend.php?friend_id=<?=$record['id']?>" target="_blank" class="list-group-item list-group-item-action active" style="background-color:green">Unfriend</a>
							 	<?php } else { ?>
							 		<a href="addFriend.php?friend_id=<?=$record['id']?>" target="_blank" class="list-group-item list-group-item-action active" style="background-color:green">Add as friend</a>
							 	<?php } ?>
							 </div>
						</div>
						<br>
					</div>
		</center>

		<?php } ?>

	<?php } ?>

<?php } ?>





