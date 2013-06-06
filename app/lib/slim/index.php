<?php
require 'Slim/Slim.php';
require 'routes/users.php';
require 'routes/game_dir.php';
require 'routes/game_desc.php';
require 'routes/game_log.php';
require 'routes/game_match.php';
require 'routes/game_usr.php';

try {
	\Slim\Slim::registerAutoloader();
	$db = new PDO('mysql:host=localhost;port=3306;dbname=rn_assassins', 'testuser', 'testpwd');
	// $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
	$app = new \Slim\Slim(array(
		'debug' => true
		));
}
catch(Exception $ex) {
	header('HTTP/1.1 500 Internal Server Error');
	exit();
}

/********************** 
 ****** ROUTING *******
 *********************/
$app->get('/', function() { echo 'You\'re not in the right place.'; });

/* SYSTEM USERS */
$app->get('/users', 'rn_assassins_users\listUsers');						// GET:		list all users in the system
$app->post('/users', 'rn_assassins_users\addUser');							// POST:	add a user to the system

$app->get('/users/:uid', 'rn_assassins_users\getUser');						// GET:		get information on user
$app->put('/users/:uid', 'rn_assassins_users\updateUser');					// PUT:		update user information
$app->delete('/users/:uid', 'rn_assassins_users\deleteUser');				// DELETE:	delete a user

/* GAME DIRECTORY */
$app->get('/games', 'rn_assassins_game_dir\listGames');						// GET:		list games
$app->post('/games', 'rn_assassins_game_dir\addGame');						// POST:	make new game

/* GAME DESCRIPTION */
$app->get('/games/:gid', 'rn_assassins_game_desc\getGame');					// GET:		get all data for game #gid
$app->put('/games/:gid', 'rn_assassins_game_desc\updateGame');				// PUT:		edit basic game data (name, start, et c.)
$app->delete('/games/:gid', 'rn_assassins_game_desc\deleteGame');			// DELETE:	delete game, cascades

/* GAME LOGGING */
$app->get('/games/:gid/log', 'rn_assassins_game_log\listLog');				// GET:		retrieves event log for game
$app->post('/games/:gid/log', 'rn_assassins_game_log\addLog');				// POST:	adds entry to event log for game
$app->delete('/games/:gid/log', 'rn_assassins_game_log\flushLog');			// DELETE:	flushes log for a game

$app->get('/games/:gid/log/:entry', 'rn_assassins_game_log\getLog');		// GET:		retrieves event for game
// $app->put('/games/:gid/log/:entry', 'rn_assassins_game_log\updateLog');		// PUT:		updates event for game
$app->delete('/games/:gid/log/:entry', 'rn_assassins_game_log\deleteLog');	// DELETE:	deletes event for game

/* GAME MATCHING */
$app->get('/games/:gid/map', 'rn_assassins_game_match\listMap');			// GET:		get all assignments for a game
$app->post('/games/:gid/map', 'rn_assassins_game_match\addMap');			// POST:	push assignments for a game
$app->delete('/games/:gid/map', 'rn_assassins_game_match\flushMap');		// DELETE:	flushes assignments for a game

/* GAME USERS */
$app->get('/games/:gid/users', 'rn_assassins_game_usr\listUsers');			// GET:		get roster for a game
$app->post('/games/:gid/users', 'rn_assassins_game_usr\addUser');			// POST:	add a user to the roster for a game
$app->delete('/games/:gid/users', 'rn_assassins_game_usr\flushUsers');		// DELETE:	flushes roster for a game

$app->delete('/games/:gid/users/:uid', 'rn_assassins_game_usr\deleteUser');	// DELETE:	removes a user from a roster

$app->run();