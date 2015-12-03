<?php

require 'Slim/Slim.php';
require 'db.php';

$app = new Slim();

//category
$app->get('/categories/:level', 'getCategories');
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
function getCategories($level) {
	$sql = "SELECT * FROM categories where niveau=:level";
	try {
		$db = getConnection();
		$stmt = $db->prepare($sql);
		$stmt->bindParam("level", $level);
		$stmt->execute();
		$categories = $stmt->fetchAll(PDO::FETCH_OBJ);
		$db = null;
		echo json_encode($categories);
		exit;
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}';
	}
}

function getCategorie($id) {
	$sql = "SELECT * FROM categories WHERE id=:id";
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
	$sql = "INSERT INTO categories(libelle_en, libelle_fr, description_en, description_fr, image) VALUES (:libelle_en, :libelle_fr, :description_en, :description_fr, :image)";
	try {
		$db = getConnection();
		$stmt = $db->prepare($sql);
		$stmt->bindParam("libelle_en", $categorie->libelle_en);
		$stmt->bindParam("libelle_fr", $categorie->libelle_fr);
		$stmt->bindParam("description_en", $categorie->description_en);
		$stmt->bindParam("description_fr", $categorie->description_fr);
		$stmt->bindParam("image", $categorie->image);
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
	$sql = "UPDATE categories SET libelle_en=:libelle_en, libelle_fr=:libelle_fr, description_en=:description_en, description_fr=:description_fr WHERE id=:id";
	try {
		$db = getConnection();
		$stmt = $db->prepare($sql);
		$stmt->bindParam("libelle_en", $categorie->libelle_en);
		$stmt->bindParam("libelle_fr", $categorie->libelle_fr);
		$stmt->bindParam("description_en", $categorie->description_en);
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
	$sql = "DELETE FROM categories WHERE id=:id";
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
	$sql = "SELECT * FROM categories WHERE UPPER(libelle_en) LIKE :query OR UPPER(libelle_fr) LIKE :query ORDER BY libelle_en,libelle_fr";
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
	$sql = "SELECT * FROM suggestions WHERE id=:id";
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
	$sql = "SELECT * FROM suggestions WHERE categorie_id=:id";
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
	$sql = "INSERT INTO suggestions(libelle_en, libelle_fr, categorie_id) VALUES (:libelle_en, libelle_fr, :id)";
	try {
		$db = getConnection();
		$stmt = $db->prepare($sql);
		$stmt->bindParam("libelle_en", $suggestion->libelle_en);
		$stmt->bindParam("libelle_fr", $suggestion->libelle_fr);
		$stmt->bindParam("id", $suggestion->categorie_id);
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
	$sql = "UPDATE suggestions SET libelle_en=:libelle_en and libelle_fr=:libelle_fr WHERE id=:id";
	try {
		$db = getConnection();
		$stmt = $db->prepare($sql);
		$stmt->bindParam("libelle_en", $suggestion->libelle_en);
		$stmt->bindParam("libelle_fr", $suggestion->libelle_fr);
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
	$sql = "DELETE FROM suggestions WHERE id=:id";
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
	$sql = "SELECT * FROM suggestions WHERE UPPER(libelle_en) LIKE :query or UPPER(libelle_fr) LIKE :query ORDER BY libelle_en, libelle_fr";
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
