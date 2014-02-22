<?php

/*
Modes:
	1: Search by a Patient ID.
	2: Show patient's profile.
	3: Show patient's visits.
	4: Create a new patient.
	5: Update an existing patient.
	6: Delete a patient.
	7: Search by a Patient Name.
*/

$mode = 1;
if(isset($_GET["mode"])){
	$mode = $_GET["mode"];
}

if($mode == 1){
	include("styles/$default_style/modules/patients/search.html");
}

elseif($mode == 2){
	$families_data = mysql_query("SELECT * FROM families") 
	or die(mysql_error());

	$data = mysql_query("SELECT * FROM patients WHERE id='" . $_GET["patientID"] . "'") 
	or die(mysql_error());

 	$patient = mysql_fetch_array($data);
	
 	include("styles/$default_style/modules/patients/profile.html");
	
	include("styles/$default_style/modules/patients/update/header.html");
	
	while($family = mysql_fetch_array($families_data)){
		include("styles/$default_style/modules/patients/update/content.html");
	}
	
	include("styles/$default_style/modules/patients/update/footer.html");
}

elseif($mode == 3){
	$data = mysql_query("SELECT * FROM visits WHERE patient_id='" . $_GET["patientID"] . "'") 
	or die(mysql_error());
	
	include("styles/$default_style/modules/patients/visits_header.html");
	
	while($visit = mysql_fetch_array($data)){
		include("styles/$default_style/modules/patients/visits_content.html");
	}
	
	include("styles/$default_style/modules/patients/visits_footer.html");
	
	$families_data = mysql_query("SELECT * FROM families") 
	or die(mysql_error());

	$data = mysql_query("SELECT * FROM patients WHERE id='" . $_GET["patientID"] . "'") 
	or die(mysql_error());

 	$patient = mysql_fetch_array($data);
	
	include("styles/$default_style/modules/patients/update/header.html");
	
	while($family = mysql_fetch_array($families_data)){
		include("styles/$default_style/modules/patients/update/content.html");
	}
	
	include("styles/$default_style/modules/patients/update/footer.html");
}

elseif($mode == 4){
	$status = 1;
	if(isset($_GET["status"])){
		$status = $_GET["status"];
	}
	
	if($status == 1){
		$data = mysql_query("SELECT * FROM families") 
		or die(mysql_error());
	
		include("styles/$default_style/modules/patients/new/header.html");
		
		while($family = mysql_fetch_array($data)){
			include("styles/$default_style/modules/patients/new/content.html");
		}
		
		include("styles/$default_style/modules/patients/new/footer.html");
	}
	
	elseif($status == 2){
		$sql = "INSERT INTO 
					patients (id, 
						family_id, 
						name, 
						dob, 
						sex, 
						smoking, 
						weight, 
						height, 
						bmi, 
						surgical_procedures_medical_history, 
						blood_group, 
						food_allergies, 
						medicine_allergies, 
						immunizations_history, 
						medication_history) 
						
					VALUES ('$_POST[id]',
						'$_POST[family_id]',
						'$_POST[name]',
						'$_POST[dob]',
						'$_POST[sex]',
						'$_POST[smoking]',
						'$_POST[weight]',
						'$_POST[height]',
						'$_POST[bmi]',
						'$_POST[surgical_procedures_medical_history]',
						'$_POST[blood_group]',
						'$_POST[food_allergies]',
						'$_POST[medicine_allergies]',
						'$_POST[immunizations_history]',
						'$_POST[medication_history]')";
		
		if(mysql_query($sql)){
			echo("<div class='alert alert-success'>You have successfully created a patient. Click <a href='index.php?page=patients&mode=2&patientID=$_POST[id]' class='alert-link'>here</a> to access the patient's file.</div>");
		}
		 
		else{
			 die('Error: ' . mysql_error());
		}
	}
	
	else{
		echo("Misuse detected. Please contact the administrator.");
	}
}

elseif($mode == 5){
	$status = 1;
	if(isset($_GET["status"])){
		$status = $_GET["status"];
	}
	
	if($status == 2){
		$sql = "UPDATE patients SET 
					id='" . $_POST["id"] . "', 
					family_id='" . $_POST["family_id"] . "', 
					name='" . $_POST["name"] . "', 
					dob='" . $_POST["dob"] . "', 
					sex='" . $_POST["sex"] . "', 
					smoking='" . $_POST["smoking"] . "', 
					weight='" . $_POST["weight"] . "', 
					height='" . $_POST["height"] . "', 
					bmi='" . $_POST["bmi"] . "', 
					surgical_procedures_medical_history='" . $_POST["surgical_procedures_medical_history"] . "', 
					blood_group='" . $_POST["blood_group"] . "', 
					food_allergies='" . $_POST["food_allergies"] . "', 
					medicine_allergies='" . $_POST["medicine_allergies"] . "', 
					immunizations_history='" . $_POST["immunizations_history"] . "', 
					medication_history='" . $_POST["medication_history"] . "' 
					
					WHERE id='" . $_POST["old_id"] . "'";
				
		if(mysql_query($sql)){
			echo("<div class='alert alert-info'>You have successfully updated a patient. Click <a href='index.php?page=patients&mode=2&patientID=$_POST[id]' class='alert-link'>here</a> to access the newly updated patient's file.</div>");
		}
		 
		else{
			die('Error: ' . mysql_error());
		}
		 
		$sql = "UPDATE visits SET 
					patient_id='" . $_POST["id"] . "' 
					
					WHERE patient_id='" . $_POST["old_id"] . "'";
				
		if(!mysql_query($sql)){
			 die('Error: ' . mysql_error());
		}
	}
	
	else{
		echo("Misuse detected. Please contact the administrator.");
	}
}

elseif($mode == 6){
	$sql = "DELETE FROM patients WHERE id='" . $_GET["patientID"] . "'";
		
	if(mysql_query($sql)){
		echo("<div class='alert alert-danger'>You have successfully deleted a patient. Click <a href='index.php?page=families&mode=2&familyID=$_GET[familyID]' class='alert-link'>here</a> to access the deleted patient's family's file.</div>");
	}

	else{
		die('Error: ' . mysql_error());
	}
	
	$sql = "DELETE FROM visits WHERE patient_id='" . $_GET["patientID"] . "'";
	
	if(!mysql_query($sql)){
		die('Error: ' . mysql_error());
	}
}

elseif($mode == 7){
	if(isset($_GET["patientName"])){
		$patients_data = mysql_query("SELECT * FROM patients WHERE name LIKE '%" . $_GET["patientName"] . "%'") 
		or die(mysql_error());
		
		include("styles/$default_style/modules/patients/search_name_results_header.html");
		
 		while($info = mysql_fetch_array($patients_data)){
			
			$family_data = mysql_query("SELECT * FROM families WHERE id='" . $info["family_id"] . "'") 
			or die(mysql_error());	
			
			$family = mysql_fetch_array($family_data);
			
			include("styles/$default_style/modules/patients/search_name_results_content.html");
		}
		
		include("styles/$default_style/modules/patients/search_name_results_footer.html");		
	}
	
	else{
		include("styles/$default_style/modules/patients/search_name_form.html");
	}
}

else {
	echo("Misuse detected. Please contact the administrator.");
}

?>