<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
require_once('functions.php');
if(!empty($_POST['archive_name'])) echo getArchive($_POST);