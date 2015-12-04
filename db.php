<?php

const DB_SERVER = "localhost";
const DB_USER = "root";
const DB_PASSWORD = "";
const DB = "readingchallenge-admin";

function getConnection() {
	$dbhost=DB_SERVER;
	$dbuser=DB_USER;
	$dbpass=DB_PASSWORD;
	$dbname=DB;

	$dbh = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
	//$dbh->exec("set names utf8");
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	return $dbh;
}

?>
