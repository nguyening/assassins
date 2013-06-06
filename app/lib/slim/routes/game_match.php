<?php
	namespace rn_assassins_game_match;
	function listMap($game_id) {
		global $app, $db;
		try {
			$map_query = $db->prepare('SELECT * FROM `gamemaps` WHERE `game_id` = ?');
			$map_query->bindParam(1, $game_id, \PDO::PARAM_INT);
			$map_query->execute();

			$map = $map_query->fetchAll(\PDO::FETCH_OBJ);

			$app->response()->header('Content-type', 'application/json');
			echo json_encode($map);
		}
		catch(PDOException $ex) {
			$app->stop(500, $ex->getMessage());
		}
		catch(Exception $ex) {
			$app->stop(400, $ex->getMessage());
		}
	}

	function addMap($game_id) {
		global $app, $db;
		try {
			$match_def = array(
				'uid1'=>null,
				'uid2'=>null,
				'method'=>'');
			$match = array_merge($match_def, json_decode($app->request()->getBody(), true));
			
			$map_query = $db->prepare('INSERT INTO `gamemaps` (`game_id`, `uid_1`, `uid_2`, `method`) VALUES (?, ?, ?, ?)');
			$map_query->execute(array($game_id, $match['uid1'], $match['uid2'], $match['method']));

			if(!$map_query->rowCount())
				throw new \Exception('Unable to insert new game event.');
			
			echo $game_id;
		}
		catch(PDOException $ex) {
			$app->stop(500, $ex->getMessage());
		}
		catch(Exception $ex) {
			$app->stop(400, $ex->getMessage());
		}
	}

	function flushMap($game_id) {
		global $app, $db;
		try {
			$game_query = $db->prepare('DELETE FROM `gamemaps` WHERE `game_id` = ?');
			$game_query->bindParam(1, $game_id, \PDO::PARAM_INT);
			$game_query->execute();
			
			$rows_deleted = $game_query->rowCount();
			echo $rows_deleted;
		}
		catch(PDOException $ex) {
			$app->stop(500, $ex->getMessage());
		}
		catch(Exception $ex) {
			$app->stop(400, $ex->getMessage());
		}
	}

?>