<?php

session_start();

if(!isset($_SESSION['user_id'])) {
	header("Location: /login.php");
}

unset($_SESSION['event_id']);

require 'database.php';

$query = "SELECT name FROM users JOIN registrations ON registrations.event_id = :event_id AND registrations.user_id = users.id JOIN friends ON user_1 = :this_id AND user_2 = users.id";

$records = $conn->prepare($query);
$records->bindParam(':this_id', $_SESSION['user_id']);
$records->bindParam(':event_id', $_GET['event_id']);
$records->execute();
$results = $records->fetchAll(PDO::FETCH_ASSOC);

?>


  	<?php foreach($results as $record) { ?>

  		<form class="form-horizontal" action='friends.php' target="_blank" method="POST" style="background-color:lightgrey">
  			<fieldset>

  				<p style="color:green"><?= $record['name'] ?> </p>
			  	<div class="control-group">
			      <div class="controls">
			        <input type="hidden" name="search" value="<?=$record['name']?>">
			      </div>
			    </div>

			    <div class="control-group">
			      <!-- Button -->
			      <div class="controls">
			        <button class="btn btn-success" name="view_friends">View</button>
			      </div>
			    </div>

	      </fieldset>
		</form>

		<br>

	<?php } ?>




