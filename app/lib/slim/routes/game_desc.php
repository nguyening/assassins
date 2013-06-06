<?php
	namespace rn_assassins_game_desc;
	function getGame($game_id) {
		global $app, $db;
		try {
			$game_query = $db->prepare('SELECT * FROM `games` WHERE `id` = ?');
			$game_query->bindParam(1, $game_id, \PDO::PARAM_INT);
			$game_query->execute();
			$game = $game_query->fetchObject();

			$app->response()->header('Content-type', 'application/json');
			echo json_encode($game);
		}
		catch(PDOException $ex) {
			$app->stop(500, $ex->getMessage());
		}
		catch(Exception $ex) {
			$app->stop(400, $ex->getMessage());
		}
	}

	function updateGame($game_id) {
		global $app, $db;
		try {
			$game_query = $db->prepare('SELECT * FROM `games` WHERE `id` = ?');
			$game_query->bindParam(1, $game_id, \PDO::PARAM_INT);
			$game_query->execute();
			$game_old = $game_query->fetchObject();

			$game_def = array(
				'title'=>$game_old->title,
				'start'=>$game_old->start,
				'desc'=>$game_old->desc);
			$game = array_merge($game_def, json_decode($app->request()->getBody(), true));

			$game_query = $db->prepare('UPDATE `games` SET `title` = ?, `start` = ?, `desc` = ? WHERE `id` = ?');
			$game_query->execute(array($game['title'], $game['start'], $game['desc'], $game_id));

			if(!$game_query->rowCount())
				throw new \Exception('Unable to/did not update game');

			echo $game_id;
		}
		catch(PDOException $ex) {
			$app->stop(500, $ex->getMessage());
		}
		catch(Exception $ex) {
			$app->stop(400, $ex->getMessage());
		}
	}

	function deleteGame($game_id) {
		global $app, $db;
		try {
			$game_query = $db->prepare('DELETE FROM `games` where `id` = ?');
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