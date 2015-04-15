<?php
/*
$mysqli = new mysqli('localhost', 'root', '', 'test');


if ($mysqli->connect_error) {
    die('Connect Error (' . $mysqli->connect_errno . ') '
            . $mysqli->connect_error);
}


$query = "INSERT INTO `Clicks` SET X='{$_POST['x']}', Y='{$_POST['y']}'";

$result = $mysqli->query($query);
*/
if($mysqli->errno) {
	echo $mysqli->error;
} else {
	echo "Saved at: " . $mysqli->insert_id;
	print_r(parse_url($_SERVER["HTTP_REFERER"]));
}