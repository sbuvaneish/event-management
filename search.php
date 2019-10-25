<?php

session_start();

if(!isset($_SESSION['user_id'])) {
	header("Location: /login.php");
}

unset($_SESSION['event_id']);

require 'database.php';

$message = "";


function getRegisteredEvents() {

	global $conn;

	$query = "SELECT event_id FROM registrations WHERE user_id = :user_id";
	$records = $conn->prepare($query);
	$records->bindParam(':user_id', $_SESSION['user_id']);
	$records->execute();
	$results = $records->fetchAll(PDO::FETCH_ASSOC);
	$processed_results = [];

	foreach($results as $result) {
		array_push($processed_results, $result['event_id']);
	}

	return $processed_results;

}


function validateRecord($record) {

	global $registered_events;

	$now = new DateTime();
	$time = $now->format('Y-m-d H:i:s');



	return ((($time <= $record['deadline']) AND !in_array($record['event_id'], $registered_events) AND ($record['count'] < $record['capacity'])) ? 1 : 0);

} 


if(isset($_POST['submit'])) {

	$registered_events = getRegisteredEvents();

	$query = "SELECT events.id as event_id, user_id, events.name as event_name, users.name as user_name, email, count, capacity, date, deadline FROM events JOIN users ON events.user_id = users.id WHERE (events.name LIKE CONCAT('%', :search_query, '%') OR users.name LIKE CONCAT('%', :search_query, '%'))";

	if($_POST['created'] === 'on') {
		$query = $query . ' ' . 'AND events.user_id = :this_id';
	}

	$records = $conn->prepare($query);
	$records->bindParam(':search_query', $_POST['search']);

	if($_POST['created'] === 'on') {
		$records->bindParam(':this_id', $_SESSION['user_id']);
	}

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

<form class="form-horizontal" action='search.php' method="POST">
  <fieldset>
    
    <div>
    	<p style="color:blue">
    		 <?= $message ?>
    	</p>
    </div>
 
    <div class="control-group">
      <label class="control-label" for="search" style="color:green">Search event or user</label>
      <div class="controls">
        <input type="text" id="search" name="search" placeholder="Search" class="input-xlarge" value="<?= $_POST['search'] ?>">
      </div>
    </div>
    <br>

    <div class="control-group">
      <label class="control-label" style="color:green">Filters</label>
      <div class="controls">
        <input type="checkbox" id="created" name="created" class="input-xlarge" <?php if(isset($_POST['created'])) {echo "checked";}?>>
        <label for="created">View Events I created</label><br>
        <input type="checkbox" id="registered" name="registered" class="input-xlarge" <?php if(isset($_POST['registered'])) {echo "checked";}?>>
        <label for="registered">View Events I registered</label>
      </div>
    </div>

    <div class="control-group">
      <!-- Button -->
      <div class="controls">
        <button class="btn btn-success" name="submit">Search</button>
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

<br><br>


<?php if(isset($_POST['submit'])) { ?>


	<div style="color:green">

		<?php
			$count = 0;
			foreach($results as $record) {
				if(!isset($_POST['registered']) or in_array($record['event_id'], $registered_events)) {
					$count++;
				} 
			}
		?>

		Total Results: <?=$count?><br>
	</div>




	<?php foreach($results as $record) { ?>


		<?php if(!isset($_POST['registered']) or in_array($record['event_id'], $registered_events)) { ?>


		<center>
			
			<div class="col-md-3 col-sm-6">
		        <div class="product-grid" style="background-color:lightgrey">


		        	
		            
			            <div class="product-content">
			                Event: <?=$record['event_name']?><br>
			                Organizer: <?=$record['user_name']?><br>
			                Organizer Email: <?=$record['email']?><br>
			                Event datetime: <?=$record['date']?><br>
			                Deadline: <?=$record['deadline']?><br>

			                <?php if($record['user_id'] === $_SESSION['user_id']) { ?>
			                	<a href="edit.php?event_id=<?=$record['event_id']?>" target="_blank" class="list-group-item list-group-item-action active" style="background-color:orange">Edit</a>
			                	<a href="delete.php?event_id=<?=$record['event_id']?>" target="_blank" class="list-group-item list-group-item-action active" style="background-color:green">Delete</a>

			                <?php } else { ?> 
			                	<a href="edit.php?event_id=<?=$record['event_id']?>&creator_id=<?=$record['user_id']?>" target="_blank" class="list-group-item list-group-item-action active" style="background-color:orange">View</a>
			            	<?php } ?>


			            	<?php if(validateRecord($record) === 1) { ?>
		                	<a href="event_registration.php?event_id=<?=$record['event_id']?>" target="_blank" class="list-group-item list-group-item-action active" style="background-color:red">Register for event</a>
		                	<?php } ?>

		                	<a href="viewFriends.php?event_id=<?=$record['event_id']?>" target="_blank" class="list-group-item list-group-item-action active" style="background-color:blue">View attending friends</a>


			                <br>

			            </div>


		            <br>
		        </div>
		        <br>
		    </div>	

		</center>


		<?php } ?>
		

	<?php } ?>

<?php } ?>

