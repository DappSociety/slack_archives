<?php
$archive_name = !empty($_GET['archive_name']) ? $_GET['archive_name'] : 'archive';
header('Content-disposition: attachment; filename=' . $archive_name . '.json');
header('Content-type: application/json');
echo $json;