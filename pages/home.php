<?php
$page = 1;
if(isset($_GET["p"])){
	$page = $_GET["p"];
}

$count = query($dbConnection, "SELECT COUNT(*) FROM visits", null) -> fetchColumn();
$pages = ceil($count/$visits_per_homepage);

$maxpages = $latest_visits / $visits_per_homepage;

if($pages > $maxpages){
	$pages = $maxpages;
}

$page = min($pages, filter_input(INPUT_GET, 'p', FILTER_VALIDATE_INT, array('options' => array('default' => 1, 'min_range' => 1))));
$offset = ($page - 1) * $visits_per_homepage;
$start = $offset + 1;
$end = min(($offset + $visits_per_homepage), $count);

$visits = query($dbConnection, 'SELECT * FROM visits ORDER BY date_time DESC LIMIT ? OFFSET ?', Array($visits_per_homepage, $offset));

include("styles/$default_style/modules/home/header.html");

foreach($visits as $visit){

	$patients = query($dbConnection, 'SELECT * FROM patients WHERE id = ?', Array($visit['patient_id']));
	$patient = $patients -> fetch();
	
	include("styles/$default_style/modules/home/content.html");
}

include("styles/$default_style/modules/home/footer.html");

?>




