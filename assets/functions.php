<?php
function query($dbConnection, $query, $parameters){
	$stmt = $dbConnection -> prepare($query);
	$stmt -> execute($parameters);
	
	return $stmt;
}

function get_Datetime_Now(){
	$tz_object = new DateTimeZone('Asia/Jerusalem');
	$datetime = new DateTime();
	$datetime->setTimezone($tz_object);     
	
	return $datetime->format('Y\-m\-d\ h:i:s');
}
?>