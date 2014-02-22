<?php

/*
Modes:
	1: Show a visit.
	2: Create a new visit.
	3: Update an existing visit.
	4: Delete a visit.
*/

$mode = 1;
if(isset($_GET["mode"])){
	$mode = $_GET["mode"];
}

if($mode == 1){
	if(isset($_GET["visitID"])){
		$visit_data = mysql_query("SELECT * FROM visits WHERE id='" . $_GET["visitID"] . "'") 
		or die(mysql_error());
		
		$visit = mysql_fetch_array($visit_data);
		
		$visit_examination = explode("~!@#$%^&*()_+", $visit["physical_examination"], 6);
		
		$patient_data = mysql_query("SELECT * FROM patients WHERE id='" . $visit["patient_id"] . "'") 
		or die(mysql_error());
		
		$patient = mysql_fetch_array($patient_data);
		
		$previous_visits_data = mysql_query("SELECT * FROM visits WHERE patient_id='" . $visit["patient_id"] . "' AND date_time < '" . $visit["date_time"] . "'") 
		or die(mysql_error());
		
		include("styles/$default_style/modules/visits/previous_visits_header.html");	
		
		while($previous_visit = mysql_fetch_array($previous_visits_data)){
			include("styles/$default_style/modules/visits/previous_visits_content.html");
		}
				
		include("styles/$default_style/modules/visits/previous_visits_footer.html");
		
		include("styles/$default_style/modules/visits/allergies.html");	
		
		include("styles/$default_style/modules/visits/show_single.html");	
	}
	
	else{
		echo("Misuse detected. Please contact the administrator.");
	}
}

elseif($mode == 2){
	$status = 1;
	if(isset($_GET["status"])){
		$status = $_GET["status"];
	}
	
	if($status == 1){		
		include("styles/$default_style/modules/visits/new/header.html");
		
		$patients = mysql_query("SELECT * FROM patients WHERE id='" . $_GET["patientID"] . "'") 
		or die(mysql_error());
			
		$patient = mysql_fetch_array($patients);
			
		$previous_visits_data = mysql_query("SELECT * FROM visits WHERE patient_id='" . $_GET["patientID"] . "'") 
		or die(mysql_error());
		
		include("styles/$default_style/modules/visits/new/previous_visits_header.html");	
		
		while($previous_visit = mysql_fetch_array($previous_visits_data)){
			include("styles/$default_style/modules/visits/new/previous_visits_content.html");
		}
				
		include("styles/$default_style/modules/visits/new/previous_visits_footer.html");
		
		include("styles/$default_style/modules/visits/allergies.html");	
		
		include("styles/$default_style/modules/visits/new/form_header.html");
		
		$medication_categories = mysql_query("SELECT * FROM medication_categories") 
		or die(mysql_error());
			
		while($category = mysql_fetch_array($medication_categories)){
			$medications = mysql_query("SELECT * FROM medications WHERE category_id='" . $category["id"] . "'") 
			or die(mysql_error());
			
			include("styles/$default_style/modules/visits/new/medication_header.html");
			
			while($medication = mysql_fetch_array($medications)){
				include("styles/$default_style/modules/visits/new/medication.html");
			}
			
			include("styles/$default_style/modules/visits/new/medication_footer.html");
		}
		
		include("styles/$default_style/modules/visits/new/form_footer.html");
			
		include("styles/$default_style/modules/visits/new/footer.html");
	}
	
	elseif($status == 2){	
		$physical_examination = $_POST["headandface_exam"] . "~!@#$%^&*()_+" . $_POST["heart_exam"] . "~!@#$%^&*()_+" . $_POST["chest_exam"] . "~!@#$%^&*()_+" . $_POST["abdomen_exam"]. "~!@#$%^&*()_+" . $_POST["limbs_exam"]. "~!@#$%^&*()_+" . $_POST["others_exam"];		
		
		$sql = "INSERT INTO 
					visits (patient_id, 
							date_time, 
							temperature, 
							bp, 
							pulse, 
							respiratory_rate, 
							weight, 
							height, 
							bmi, 
							main_complaints, 
							physical_examination, 
							lab_study, 
							diagnosis, 
							treatment,
							next_appointment) 
						
					VALUES ('$_POST[patient_id]',
							'$_POST[date_time]',
							'$_POST[temperature]',
							'$_POST[bp]',
							'$_POST[pulse]',
							'$_POST[respiratory_rate]',
							'$_POST[weight]',
							'$_POST[height]',
							'$_POST[bmi]',
							'$_POST[main_complaints]',
							'$physical_examination',
							'$_POST[lab_study]',
							'$_POST[diagnosis]',
							'$_POST[treatment]',
							'$_POST[next_appointment]')";
		
		if(mysql_query($sql)){
			$new_visits = mysql_query("SELECT * FROM visits ORDER BY id DESC") 
			or die(mysql_error());
			
			$new_visit = mysql_fetch_array($new_visits);
			
			echo("<div class='alert alert-success'>You have successfully created a visit. Click <a href='index.php?page=visits&visitID=$new_visit[id]' class='alert-link'>here</a> to access the new visit.</div>");
		}
		 
		else{
			 die('Error: ' . mysql_error());
		}
		
		$next_visits_data = mysql_query("SELECT * FROM visits WHERE patient_id='" . $_POST["patient_id"] . "' AND date_time > '" . $_POST["date_time"] . "'") 
		or die(mysql_error());	
		
		$next_visit = mysql_fetch_array($next_visits_data);
		
		if(!$next_visit){
			$sql = "UPDATE patients SET 
						weight='" . $_POST["weight"] . "', 
						height='" . $_POST["height"] . "',
						bmi='" . $_POST["bmi"] . "'
						
						WHERE id='" . $_POST["patient_id"] . "'";
					
			if(!mysql_query($sql)){
				 die('Error: ' . mysql_error());
			}
		}		


	}
	
	else{
		echo("Misuse detected. Please contact the administrator.");
	}
}

elseif($mode == 3){
	$status = 1;
	if(isset($_GET["status"])){
		$status = $_GET["status"];
	}
	
	if($status == 1){		
	
		$visits = mysql_query("SELECT * FROM visits WHERE id='" . $_GET["visitID"] . "'") 
		or die(mysql_error());
			
		$visit = mysql_fetch_array($visits);	
		
		$visit_examination = explode("~!@#$%^&*()_+", $visit["physical_examination"], 6);
		
		include("styles/$default_style/modules/visits/update/header.html");
		
		$patients = mysql_query("SELECT * FROM patients WHERE id='" . $visit["patient_id"] . "'") 
		or die(mysql_error());
			
		$patient = mysql_fetch_array($patients);
			
		$previous_visits_data = mysql_query("SELECT * FROM visits WHERE patient_id='" . $visit["patient_id"] . "'") 
		or die(mysql_error());
		
		include("styles/$default_style/modules/visits/update/previous_visits_header.html");	
		
		while($previous_visit = mysql_fetch_array($previous_visits_data)){
			include("styles/$default_style/modules/visits/update/previous_visits_content.html");
		}
				
		include("styles/$default_style/modules/visits/update/previous_visits_footer.html");
		
		include("styles/$default_style/modules/visits/allergies.html");	
		
		include("styles/$default_style/modules/visits/update/form_header.html");
		
		$medication_categories = mysql_query("SELECT * FROM medication_categories") 
		or die(mysql_error());
			
		while($category = mysql_fetch_array($medication_categories)){
			$medications = mysql_query("SELECT * FROM medications WHERE category_id='" . $category["id"] . "'") 
			or die(mysql_error());
			
			include("styles/$default_style/modules/visits/update/medication_header.html");
			
			while($medication = mysql_fetch_array($medications)){
				include("styles/$default_style/modules/visits/update/medication.html");
			}
			
			include("styles/$default_style/modules/visits/update/medication_footer.html");
		}
		
		include("styles/$default_style/modules/visits/update/form_footer.html");
			
		include("styles/$default_style/modules/visits/update/footer.html");
	}
	
	elseif($status == 2){	
		$physical_examination = $_POST["headandface_exam"] . "~!@#$%^&*()_+" . $_POST["heart_exam"] . "~!@#$%^&*()_+" . $_POST["chest_exam"] . "~!@#$%^&*()_+" . $_POST["abdomen_exam"] . "~!@#$%^&*()_+" . $_POST["limbs_exam"] . "~!@#$%^&*()_+" . $_POST["others_exam"];	
				
		$sql = "UPDATE visits SET 
					patient_id='" . $_POST["patient_id"] . "', 
					date_time='" . $_POST["date_time"] . "', 
					temperature='" . $_POST["temperature"] . "', 
					bp='" . $_POST["bp"] . "', 
					pulse='" . $_POST["pulse"] . "', 
					respiratory_rate='" . $_POST["respiratory_rate"] . "', 
					weight='" . $_POST["weight"] . "', 
					height='" . $_POST["height"] . "', 
					bmi='" . $_POST["bmi"] . "', 
					main_complaints='" . $_POST["main_complaints"] . "', 
					physical_examination='" . $physical_examination . "', 
					lab_study='" . $_POST["lab_study"] . "', 
					diagnosis='" . $_POST["diagnosis"] . "', 
					treatment='" . $_POST["treatment"] . "', 
					next_appointment='" . $_POST["next_appointment"] . "' 
					
					WHERE id='" . $_POST["id"] . "'";
				
		if(mysql_query($sql)){
			echo("<div class='alert alert-info'>You have successfully updated a visit. Click <a href='index.php?page=visits&visitID=$_POST[id]' class='alert-link'>here</a> to access the newly updated visit.</div>");
		}
		 
		else{
			die('Error: ' . mysql_error());
		}	
		
		$next_visits_data = mysql_query("SELECT * FROM visits WHERE patient_id='" . $_POST["patient_id"] . "' AND date_time > '" . $_POST["date_time"] . "'") 
		or die(mysql_error());	
		
		$next_visit = mysql_fetch_array($next_visits_data);
		
		if(!$next_visit){
			$sql = "UPDATE patients SET 
						weight='" . $_POST["weight"] . "', 
						height='" . $_POST["height"] . "',
						bmi='" . $_POST["bmi"] . "'
						
						WHERE id='" . $_POST["patient_id"] . "'";
					
			if(!mysql_query($sql)){
				 die('Error: ' . mysql_error());
			}
		}		
	}
	
	else{
		echo("Misuse detected. Please contact the administrator.");
	}
}

elseif($mode == 4){
	$sql = "DELETE FROM visits WHERE id='" . $_GET["visitID"] . "'";
		
	if(mysql_query($sql)){
		echo("<div class='alert alert-danger'>You have successfully deleted a visit. Click <a href='index.php?page=patients&mode=3&patientID=$_GET[patientID]' class='alert-link'>here</a> to access the patient's remaining</div>");
	}

	else{
		die('Error: ' . mysql_error());
	}
}

elseif($mode == 5){
	include("styles/$default_style/modules/visits/search.html");
}

else {
	echo("Misuse detected. Please contact the administrator.");
}
?>
