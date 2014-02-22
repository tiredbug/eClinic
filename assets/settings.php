<?php

$site_title = "Shelbayeh Clinics";
$default_style = "shelbayeh";

$database_server = "127.0.0.1";
$database_name = "karohats_clinic";
$database_user = "root";
$database_password = "";

$latest_visits = 80; //Number of visits to show on the homepage.
$visits_per_homepage = 8; //Number of visits per page on the homepage.

//DO NOT EDIT BELOW THIS LINE.
$dbConnection = new PDO("mysql:dbname=$database_name; host=$database_server", $database_user, $database_password);

$dbConnection->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
$dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
?>