<?php
	namespace rn_assassins_game_dir;
	function listGames() {
		global $app, $db;
		$games = [];

		try {
			$game_rows = $db->query('SELECT * FROM `games`');
			foreach($game_rows as $game)
				array_push($games, $game);

			$app->response()->header('Content-type', 'application/json');
			echo json_encode($games);
		}
		catch(PDOException $ex) {
			$app->stop(500, $ex->getMessage());
		}
	}

	function addGame() {
		global $app, $db;
		try {
			$game_def = array(
				'title'=>null,
				'start'=>null,
				'desc'=>'');
			$game = array_merge($game_def, json_decode($app->request()->getBody(), true));

			$game_query = $db->prepare('INSERT INTO `games` (`title`, `start`, `desc`) VALUES (?, ?, ?)');
			$game_query->execute(array($game['title'], $game['start'], $game['desc']));
			if(!$game_query->rowCount())
				throw new \Exception('Unable to insert new game.');
			else
				$game_id = $db->lastInsertId();
			
			echo $game_id;
		}
		catch(PDOException $ex) {
			$app->stop(500, $ex->getMessage());
		}
		catch(Exception $ex) {
			$app->stop(400, $ex->getMessage());
		}
	}
?>