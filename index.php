<?php

include("assets/settings.php");
include("assets/functions.php");

include("styles/$default_style/templates/header.html");

$page = "home";
if(isset($_GET["page"])){
    $page = $_GET["page"];
}

include("pages/$page.php");

include("styles/$default_style/templates/footer.html");

?>