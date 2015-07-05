<?php

require 'Slim/Slim.php';
require 'db.php';

$app = new Slim();

$app->get('/categories', 'getCategories');
$app->get('/categories/:id',	'getCategorie');
$app->get('/categories/search/:query', 'findBylabel');
$app->post('/categories', 'addCategorie');
$app->put('/categories/:id', 'updateCategorie');
$app->delete('/categories/:id',	'deleteCategorie');

$app->run();

function getCategories() {
	$sql = "select * FROM reading_challenge_categorie";
	try {
		$db = getConnection();
		$stmt = $db->query($sql);  
		$categories = $stmt->fetchAll(PDO::FETCH_OBJ);
		$db = null;
		echo json_encode($categories);
		exit;
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
}

function getCategorie($id) {
	$sql = "SELECT * FROM reading_challenge_categorie WHERE categorie_id=:id";
	try {
		$db = getConnection();
		$stmt = $db->prepare($sql);  
		$stmt->bindParam("id", $id);
		$stmt->execute();
		$categorie = $stmt->fetchObject();  
		$db = null;
		echo json_encode($categorie); 
		exit;
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
}

function addCategorie() {
	$request = Slim::getInstance()->request();
	$categorie = json_decode($request->getBody());
	$sql = "INSERT INTO reading_challenge_categorie(categorie_label) VALUES (:label)";
	try {
		$db = getConnection();
		$stmt = $db->prepare($sql);  
		$stmt->bindParam("label", $categorie->label);
		$stmt->execute();
		$categorie->id = $db->lastInsertId();
		$db = null;
		echo json_encode($categorie);
		exit;
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
}

function updateCategorie($id) {
	$request = Slim::getInstance()->request();
	$body = $request->getBody();
	$categorie = json_decode($body);
	$sql = "UPDATE reading_challenge_categorie SET categorie_label=:label WHERE categorie_id=:id";
	try {
		$db = getConnection();
		$stmt = $db->prepare($sql);  
		$stmt->bindParam("label", $categorie->label);
		$stmt->bindParam("id", $id);
		$stmt->execute();
		$db = null;
		echo json_encode($categorie);
		exit;
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
}

function deleteCategorie($id) {
	$sql = "DELETE FROM reading_challenge_categorie WHERE categorie_id=:id";
	try {
		$db = getConnection();
		$stmt = $db->prepare($sql);  
		$stmt->bindParam("id", $id);
		$stmt->execute();
		$db = null;
		exit;
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
}

function findBylabel($query) {
	$sql = "SELECT * FROM reading_challenge_categorie WHERE UPPER(categorie_label) LIKE :query ORDER BY categorie_label";
	try {
		$db = getConnection();
		$stmt = $db->prepare($sql);
		$query = "%".$query."%";  
		$stmt->bindParam("query", $query);
		$stmt->execute();
		$categories = $stmt->fetchAll(PDO::FETCH_OBJ);
		$db = null;
		echo json_encode($categories);
		exit;
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
}
?>