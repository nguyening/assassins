<?php
	namespace rn_assassins_game_log;
	function listLog($game_id) {
		global $app, $db;
		try {
			$log_query = $db->prepare('SELECT * FROM `games_events` WHERE `game_id` = ?');
			$log_query->bindParam(1, $game_id, \PDO::PARAM_INT);
			$log_query->execute();

			$events = $log_query->fetchAll(\PDO::FETCH_OBJ);

			$app->response()->header('Content-type', 'application/json');
			echo json_encode($events);
		}
		catch(PDOException $ex) {
			$app->stop(500, $ex->getMessage());
		}
		catch(Exception $ex) {
			$app->stop(400, $ex->getMessage());
		}
	}

	function addLog($game_id) {
		global $app, $db;
		try {
			$event_def = array(
				'uid1'=>null,
				'uid2'=>null,
				'type'=>0,
				'desc'=>'');
			$event = array_merge($event_def, json_decode($app->request()->getBody(), true));
			
			$event_query = $db->prepare('INSERT INTO `games_events` (`game_id`, `uid_1`, `uid_2`, `type`, `desc`) VALUES (?, ?, ?, ?, ?)');
			$event_query->execute(array($game_id, $event['uid1'], $event['uid2'], $event['type'], $event['desc']));

			if(!$event_query->rowCount())
				throw new \Exception('Unable to insert new game event.');
			else
				$log_id = $db->lastInsertId();

			echo $log_id;
		}
		catch(PDOException $ex) {
			$app->stop(500, $ex->getMessage());
		}
		catch(Exception $ex) {
			$app->stop(400, $ex->getMessage());
		}
	}

	function flushLog($game_id) {
		global $app, $db;
		try {
			$game_query = $db->prepare('DELETE FROM `games_events` WHERE `game_id` = ?');
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

	function getLog($game_id, $log_id) {
		global $app, $db;
		try {
			$game_query = $db->prepare('SELECT * FROM `games_events` WHERE `game_id` = ? AND `id` = ?');
			$game_query->execute(array($game_id, $log_id));
	
			// $game_query->fetchObject();		
			// json_encode()
		}
		catch(PDOException $ex) {
			$app->stop(500, $ex->getMessage());
		}
		catch(Exception $ex) {
			$app->stop(400, $ex->getMessage());
		}
	}

	function deleteLog($game_id, $log_id) {
		global $app, $db;
		try {
			$game_query = $db->prepare('DELETE FROM `games_events` where `id` = ?');
			$game_query->bindParam(1, $log_id, \PDO::PARAM_INT);
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