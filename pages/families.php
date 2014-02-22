<?php

/*
Modes:
	1: Default. Show all families.
	2: Search by a Family ID or show a single family.
	3: Create a new family.
	4: Update an existing family.
	5: Delete a family.
	6: Search by a Family Name.
*/

$mode = 1;
if(isset($_GET["mode"])){
	$mode = $_GET["mode"];
}

//1: Default. Show all families.
if($mode == 1){
	$families = query($dbConnection, 'SELECT * FROM families', null);
		
	if(empty(query($dbConnection, 'SELECT * FROM families', null)->fetch())){
		echo("<div class='alert alert-info'>There are no families in the system.</div>");
	} 
	
	else{
	 	include("styles/$default_style/modules/families/show_all/header.html");
		
		foreach($families as $family){
			include("styles/$default_style/modules/families/show_all/content.html");
		}
		
		include("styles/$default_style/modules/families/show_all/footer.html");
	}
}

//2: Search by a Family ID or show a single family.
elseif($mode == 2){
	if(isset($_GET["fid"])){
	
		$families = query($dbConnection, 'SELECT * FROM families WHERE id = ?', Array($_GET["fid"]));
		$family = $families -> fetch();		
		
 		include("styles/$default_style/modules/families/show_single.html");
 		include("styles/$default_style/modules/families/delete_modal.html");
		include("styles/$default_style/modules/families/update_modal.html");
		
		$patients = query($dbConnection, 'SELECT * FROM patients WHERE family_id = ?', Array($_GET["fid"]));
		
		if(empty(query($dbConnection, 'SELECT * FROM patients WHERE family_id = ?', Array($_GET["fid"]))->fetch())){
			echo("<div class='alert alert-info'>There are no members registered to this family.</div>");
		} 
		
		else{
	 		include("styles/$default_style/modules/families/show_members/header.html");
		
	 		foreach($patients as $patient){
				include("styles/$default_style/modules/families/show_members/content.html");
				include("styles/$default_style/modules/families/show_members/delete_modal.html");
			}
		
			include("styles/$default_style/modules/families/show_members/footer.html");
		}
	}
	
	else{
		include("styles/$default_style/modules/families/search/search_by_id_form.html");
	}
}

//3: Create a new family.
elseif($mode == 3){
	$status = 1;
	if(isset($_GET["status"])){
		$status = $_GET["status"];
	}
	
	if($status == 1){
		include("styles/$default_style/modules/families/new_form.html");
	}
	
	elseif($status == 2){
		$query = "INSERT INTO families (id, name, address, phone_number, mobile_number) VALUES (?, ?, ?, ?, ?)";
		$parameters = Array($_POST["id"], $_POST["name"], $_POST["address"], $_POST["phone"], $_POST["mobile"]);
							
		try{
			query($dbConnection, $query, $parameters);
			echo("<div class='alert alert-success'>You have successfully added a new family. Click <a href='index.php?page=families&mode=2&fid=$_POST[id]' class='alert-link'>here</a> to access the new family's file.</div>");
		}
		 
		catch(Exception $e){
			 echo "<div class='alert alert-danger'>There has been an error. Please contact the administrator.</div>";
		 }
	}
	
	else{
		echo("Misuse detected. Please contact the administrator.");
	}
}

//4: Update an existing family.
elseif($mode == 4){
	$status = 1;
	if(isset($_GET["status"])){
		$status = $_GET["status"];
	}
	
	if($status == 2){
		$query = "UPDATE families SET id = ?, name = ?, address = ?, phone_number = ?, mobile_number = ? WHERE id = ?";
		$parameters = Array($_POST["id"], $_POST["name"], $_POST["address"], $_POST["phone"], $_POST["mobile"], $_POST["old_id"]);
		
		try{
			query($dbConnection, $query, $parameters);
		  	echo("<div class='alert alert-info'>You have successfully updated a family. Click <a href='index.php?page=families&mode=2&fid=$_POST[id]' class='alert-link'>here</a> to access the newly updated family's file.</div>");
		}
		 
		catch(Exception $e){
			 echo "<div class='alert alert-danger'>There has been an error. Please contact the administrator.</div>";
		 }
		 
		$query = "UPDATE patients SET family_id = ? WHERE family_id = ?";
		$parameters = Array($_POST["id"], $_POST["old_id"]);
		
		try{
			query($dbConnection, $query, $parameters);
		}
		
		catch(Exception $e){
			echo "<div class='alert alert-danger'>There has been an error. Please contact the administrator.</div>";
		}
	}
	
	else{
		echo("Misuse detected. Please contact the administrator.");
	}
}

//5: Delete a family.
elseif($mode == 5){
	$query = "DELETE FROM families WHERE id = ?";
	$parameters = Array($_GET["fid"]);

	try{
		query($dbConnection, $query, $parameters);
		echo("<div class='alert alert-danger'>You have successfully deleted a family. Click <a href='index.php?page=families' class='alert-link'>here</a> to show all families.</div>");

	}
	
	catch(Exception $e){
		echo "<div class='alert alert-danger'>There has been an error. Please contact the administrator.</div>";
	}
			
	
	$query = "DELETE FROM patients WHERE family_id = ?";
	$parameters = Array($_GET["fid"]);
	
	try{
		query($dbConnection, $query, $parameters);
	}
	
	catch(Exception $e){
		echo "<div class='alert alert-danger'>There has been an error. Please contact the administrator.</div>";
	}
}

//6: Search by a Family Name.
elseif($mode == 6){
	if(isset($_GET["fname"])){
		$query = "SELECT * FROM families WHERE name LIKE ?";
		$parameters = Array("%$_GET[fname]%");
	
		$families = query($dbConnection, $query, $parameters);

		if(empty(query($dbConnection, $query, $parameters)->fetch())){
			echo("<div class='alert alert-info'>There is no family with the provided name in the system.</div>");
		} 
		
		else{		
			include("styles/$default_style/modules/families/search/search_by_name_results_header.html");
			
	 		foreach($families as $family){
	 			include("styles/$default_style/modules/families/search/search_by_name_results_content.html");
			}
			
			include("styles/$default_style/modules/families/search/search_by_name_results_footer.html");		
		}
	}
	
	else{
		include("styles/$default_style/modules/families/search/search_by_name_form.html");
	}
}

else {
	echo("Misuse detected. Please contact the administrator.");
}

?>