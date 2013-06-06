<?php
	namespace rn_assassins_game_usr;
	function listUsers($game_id) {
		global $app, $db;
		try {
			$user_query = $db->prepare('SELECT `user_id` FROM `games_users` WHERE `game_id` = ?');
			$user_query->execute(array($game_id));

			$users = $user_query->fetchAll(\PDO::FETCH_COLUMN);

			$app->response()->header('Content-type', 'application/json');
			echo json_encode($users);
		}
		catch(PDOException $ex) {
			$app->stop(500, $ex->getMessage());
		}
		catch(Exception $ex) {
			$app->stop(400, $ex->getMessage());
		}
	}

	function flushUsers($game_id) {
		global $app, $db;
		try {
			$game_query = $db->prepare('DELETE FROM `games_users` WHERE `game_id` = ?');
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

	function addUser($game_id) {
		global $app, $db;
		try {
			$user = json_decode($app->request()->getBody(), true);
			
			$user_query = $db->prepare('INSERT INTO `games_users` (`game_id`, `user_id`) VALUES (?, ?)');
			$user_query->execute(array($game_id, $user['user_id']));
			if(!$user_query->rowCount())
				throw new Exception('Unable to add user to this game.');
			
			echo $game_id;
		}
		catch(PDOException $ex) {
			$app->stop(500, $ex->getMessage());
		}
		catch(Exception $ex) {
			$app->stop(400, $ex->getMessage());
		}
	}

	function deleteUser($game_id, $user_id) {
		global $app, $db;
		try {
			$user_query = $db->prepare('DELETE FROM `games_users` where `user_id` = ? AND `game_id` = ?');
			$user_query->execute(array($user_id, $game_id));
			
			$rows_deleted = $user_query->rowCount();
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