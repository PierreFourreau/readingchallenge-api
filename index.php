<?php

require 'Slim/Slim.php';
require 'db.php';

$app = new Slim();

//category
$app->get('/categories', 'getCategories');
$app->get('/categories/:id',	'getCategorie');
$app->get('/categories/search/:query', 'findBylabel');
$app->post('/categories', 'addCategorie');
$app->put('/categories/:id', 'updateCategorie');
$app->delete('/categories/:id',	'deleteCategorie');

//suggestion
$app->get('/suggestionsByCategory/:id', 'getSuggestionsByCategoryId');
$app->get('/suggestions/:id',	'getSuggestionById');
$app->get('/suggestions/search/:query', 'findSuggestionBylabel');
$app->post('/suggestions', 'addSuggestion');
$app->put('/suggestions/:id', 'updateSuggestion');
$app->delete('/suggestions/:id',	'deleteSuggestion');

$app->run();

/**********************************************************************/
/*********				category							  *********/
/**********************************************************************/
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
	$sql = "INSERT INTO reading_challenge_categorie(categorie_label, categorie_label_fr, categorie_description, categorie_description_fr) VALUES (:label, :label_fr, :description, :description_fr)";
	try {
		$db = getConnection();
		$stmt = $db->prepare($sql);  
		$stmt->bindParam("label", $categorie->label);
		$stmt->bindParam("label_fr", $categorie->label_fr);
		$stmt->bindParam("description", $categorie->description);
		$stmt->bindParam("description_fr", $categorie->description_fr);
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
	$sql = "UPDATE reading_challenge_categorie SET categorie_label=:label, categorie_label_fr=:label_fr, categorie_description=:description, categorie_description_fr=:description_fr WHERE categorie_id=:id";
	try {
		$db = getConnection();
		$stmt = $db->prepare($sql);  
		$stmt->bindParam("label", $categorie->label);
		$stmt->bindParam("label_fr", $categorie->label_fr);
		$stmt->bindParam("description", $categorie->description);
		$stmt->bindParam("description_fr", $categorie->description_fr);
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
	$sql = "SELECT * FROM reading_challenge_categorie WHERE UPPER(categorie_label) LIKE :query OR UPPER(categorie_label_fr) LIKE :query ORDER BY categorie_label, categorie_label_fr";
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





/**********************************************************************/
/*********				suggestion							  *********/
/**********************************************************************/
function getSuggestionById($id) {
	$sql = "SELECT * FROM reading_challenge_suggestion WHERE suggestion_id=:id";
	try {
		$db = getConnection();
		$stmt = $db->prepare($sql);  
		$stmt->bindParam("id", $id);
		$stmt->execute();
		$suggestion = $stmt->fetchObject();  
		$db = null;
		echo json_encode($suggestion); 
		exit;
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
}

function getSuggestionsByCategoryId($id) {
	$sql = "SELECT * FROM reading_challenge_suggestion WHERE categorie_id=:id";
	try {
		$db = getConnection();
		$stmt = $db->prepare($sql);  
		$stmt->bindParam("id", $id);
		$stmt->execute();
		$suggestions = $stmt->fetchAll(PDO::FETCH_OBJ);
		$db = null;
		echo json_encode($suggestions); 
		exit;
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
}

function addSuggestion() {
	$request = Slim::getInstance()->request();
	$suggestion = json_decode($request->getBody());
	$sql = "INSERT INTO reading_challenge_suggestion(suggestion_label, categorie_id) VALUES (:label, :id)";
	try {
		$db = getConnection();
		$stmt = $db->prepare($sql);  
		$stmt->bindParam("label", $suggestion->label);
		$stmt->bindParam("id", $suggestion->id_category);
		$stmt->execute();
		$suggestion->id = $db->lastInsertId();
		$db = null;
		echo json_encode($suggestion);
		exit;
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
}

function updateSuggestion($id) {
	$request = Slim::getInstance()->request();
	$body = $request->getBody();
	$suggestion = json_decode($body);
	$sql = "UPDATE reading_challenge_suggestion SET suggestion_label=:label WHERE suggestion_id=:id";
	try {
		$db = getConnection();
		$stmt = $db->prepare($sql);  
		$stmt->bindParam("label", $suggestion->label);
		$stmt->bindParam("id", $id);
		$stmt->execute();
		$db = null;
		echo json_encode($suggestion);
		exit;
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
}

function deleteSuggestion($id) {
	$sql = "DELETE FROM reading_challenge_suggestion WHERE suggestion_id=:id";
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

function findSuggestionBylabel($query) {
	$sql = "SELECT * FROM reading_challenge_suggestion WHERE UPPER(suggestion_label) LIKE :query ORDER BY suggestion_label";
	try {
		$db = getConnection();
		$stmt = $db->prepare($sql);
		$query = "%".$query."%";  
		$stmt->bindParam("query", $query);
		$stmt->execute();
		$suggestions = $stmt->fetchAll(PDO::FETCH_OBJ);
		$db = null;
		echo json_encode($suggestions);
		exit;
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
}
?>