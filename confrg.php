<?php
/* DAtatbase credentials. Assuming you are running MySQL
server with default setting (user &#39; with no password) */
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'connex');
/*Attempt to connect MySQL Databse*/
$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
//check connection
if($link === false){
	die("ERROR: Could not connect. " . mysqli_connect_error());
}