<?php

/*
Modes:
	1: Default. Show all medications.
	2: Create a new category.
	3: Update an existing category.
	4: Delete a category.
	5: Register a new medication.
	6: Update an existing medication.
	7: Delete a medication.
*/

$mode = 1;
if(isset($_GET["mode"])){
	$mode = $_GET["mode"];
}

//1: Default. Show all medications.
if($mode == 1){
	$categories = mysql_query("SELECT * FROM medication_categories") 
	or die(mysql_error()); 
 
 	include("styles/$default_style/modules/medications/show_all/categories_header.html");
	
	while($category = mysql_fetch_array($categories)){
		include("styles/$default_style/modules/medications/update_category.html");
		
		include("styles/$default_style/modules/medications/show_all/medications_header.html");

		$medications = mysql_query("SELECT * FROM medications WHERE category_id='" . $category["id"] . "'") 
		or die(mysql_error()); 
		
		while($medication = mysql_fetch_array($medications)){
			include("styles/$default_style/modules/medications/show_all/medications_content.html");
			
			include("styles/$default_style/modules/medications/update/header.html");
			
			$inner_categories = mysql_query("SELECT * FROM medication_categories") 
			or die(mysql_error());
			 
			while($inner_category = mysql_fetch_array($inner_categories)){
				include("styles/$default_style/modules/medications/update/content.html");
			}
			
			include("styles/$default_style/modules/medications/update/footer.html");
		}
		
		include("styles/$default_style/modules/medications/show_all/medications_footer.html");
	}
	
	include("styles/$default_style/modules/medications/show_all/categories_footer.html");
}

//2: Create a new category.
elseif($mode == 2){
	$status = 1;
	if(isset($_GET["status"])){
		$status = $_GET["status"];
	}
	
	if($status == 1){
		include("styles/$default_style/modules/medications/new_category.html");
	}
	
	elseif($status == 2){
		$sql = "INSERT INTO medication_categories (name) VALUES ('$_POST[name]')";
		
		if(mysql_query($sql)){
			echo("<div class='alert alert-success'>You have successfully created a medication category. Click <a href='index.php?page=medications' class='alert-link'>here</a> to show all medications.</div>");
		}
		 
		else{
			 die('Error: ' . mysql_error());
		}
	}
	
	else{
		echo("Misuse detected. Please contact the administrator.");
	}
}

//3: Update an existing category.
elseif($mode == 3){
	$status = 1;
	if(isset($_GET["status"])){
		$status = $_GET["status"];
	}
	
	if($status == 2){
		$sql = "UPDATE medication_categories SET name='" . $_POST["name"] . "' WHERE id='" . $_POST["id"] . "'";
				
		if(mysql_query($sql)){
		  	echo("<div class='alert alert-info'>You have successfully updated a medication category. Click <a href='index.php?page=medications' class='alert-link'>here</a> to show all medications.</div>");
		}
		 
		else{
			 die('Error: ' . mysql_error());
		 }		 
	}
	
	else{
		echo("Misuse detected. Please contact the administrator.");
	}

}

//4: Delete a category.
elseif($mode == 4){
	$sql = "DELETE FROM medication_categories WHERE id='" . $_GET["categoryID"] . "'";
	
	if(mysql_query($sql)){
		echo("<div class='alert alert-danger'>You have successfully deleted a medication category. Click <a href='index.php?page=medications' class='alert-link'>here</a> to show all medications.</div>");
	}
	 
	else{
		 die('Error: ' . mysql_error());
	}
	
	$sql = "DELETE FROM medications WHERE category_id='" . $_GET["categoryID"] . "'";
	
	if(!mysql_query($sql)){
		die('Error: ' . mysql_error());
	}
}

//Register a new medication.
elseif($mode == 5){
	$status = 1;
	if(isset($_GET["status"])){
		$status = $_GET["status"];
	}
	
	if($status == 1){
		$categories = mysql_query("SELECT * FROM medication_categories") 
		or die(mysql_error());
	
		include("styles/$default_style/modules/medications/new/header.html");
		
		while($category = mysql_fetch_array($categories)){
			include("styles/$default_style/modules/medications/new/content.html");
		}
		
		include("styles/$default_style/modules/medications/new/footer.html");
	}
	
	elseif($status == 2){
		$sql = "INSERT INTO medications (category_id, name, description) VALUES ('$_POST[category_id]','$_POST[name]','$_POST[description]')";
		
		if(mysql_query($sql)){
			echo("<div class='alert alert-success'>You have successfully registered a new medication. Click <a href='index.php?page=medications' class='alert-link'>here</a> to show all medications.</div>");
		}
		 
		else{
			 die('Error: ' . mysql_error());
		}
	}
	
	else{
		echo("Misuse detected. Please contact the administrator.");
	}
}

//Update an existing medication.
elseif($mode == 6){
	$status = 1;
	if(isset($_GET["status"])){
		$status = $_GET["status"];
	}
	
	if($status == 2){
		$sql = "UPDATE medications SET 
					category_id='" . $_POST["category_id"] . "', 
					name='" . $_POST["name"] . "', 
					description='" . $_POST["description"] . "'
					
					WHERE id='" . $_POST["id"] . "'";
				
		if(mysql_query($sql)){
			echo("<div class='alert alert-info'>You have successfully updated a medication. Click <a href='index.php?page=medications' class='alert-link'>here</a> to show all medications.</div>");
		}
		 
		else{
			die('Error: ' . mysql_error());
		}
	}
	
	else{
		echo("Misuse detected. Please contact the administrator.");
	}
}

//Delete a medication.
elseif($mode == 7){
	$sql = "DELETE FROM medications WHERE id='" . $_GET["medicationID"] . "'";
	
	if(mysql_query($sql)){
		echo("<div class='alert alert-danger'>You have successfully deleted a medication. Click <a href='index.php?page=medications' class='alert-link'>here</a> to show all medications.</div>");
	}
	 
	else{
		 die('Error: ' . mysql_error());
	}	
}

else {
	echo("Misuse detected. Please contact the administrator.");
}

?>