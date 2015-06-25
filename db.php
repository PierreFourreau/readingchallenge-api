<?php

const DB_SERVER = "127.0.0.1";
const DB_USER = "root";
const DB_PASSWORD = "";
const DB = "test";

function getConnection() {
	$dbhost=DB_SERVER;
	$dbuser=DB_USER;
	$dbpass=DB_PASSWORD;
	$dbname=DB;

	$dbh = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);	
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	return $dbh;
}

?>