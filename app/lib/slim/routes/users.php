<?php
	namespace rn_assassins_users;
	function listUsers() {
		global $app, $db;
		try {
			$user_query = $db->prepare('SELECT `id` FROM `users`');
			$user_query->execute();

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

	function addUser() {
		global $app, $db;
		try {
			$user_def = array(
				'fname'=>null,
				'lname'=>null,
				'email'=>null);
			$user = array_merge($user_def, json_decode($app->request()->getBody(), true));
			
			$user_query = $db->prepare('INSERT INTO `users` (`fname`, `lname`, `email`) VALUES (?, ?, ?)');
			$user_query->execute(array($user['fname'], $user['lname'], $user['email']));
			if(!$user_query->rowCount())
				throw new Exception('Unable to add new user.');
			else
				$user_id = $db->lastInsertId();

			echo $user_id;
		}
		catch(PDOException $ex) {
			$app->stop(500, $ex->getMessage());
		}
		catch(Exception $ex) {
			$app->stop(400, $ex->getMessage());
		}
	}

	function getUser($user_id) {
		global $app, $db;
		try {
			$user_query = $db->prepare('SELECT * FROM `users` WHERE `id` = ?');
			$user_query->bindParam(1, $user_id, \PDO::PARAM_INT);
			$user_query->execute();
	
			$user = $user_query->fetchObject();
			echo json_encode($user);
		}
		catch(PDOException $ex) {
			$app->stop(500, $ex->getMessage());
		}
		catch(Exception $ex) {
			$app->stop(400, $ex->getMessage());
		}
	}

	function updateUser($user_id) {
		global $app, $db;
		try {
			$user_query = $db->prepare('SELECT * FROM `users` WHERE `id` = ?');
			$user_query->bindParam(1, $user_id, \PDO::PARAM_INT);
			$user_query->execute();
			$user_old = $user_query->fetchObject();

			$user_def = array(
				'fname'=>$user_old->fname,
				'lname'=>$user_old->lname,
				'email'=>$user_old->email);
			$user = array_merge($user_def, json_decode($app->request()->getBody(), true));

			$user_query = $db->prepare('UPDATE `users` SET `fname` = ?, `lname` = ?, `email` = ? WHERE `id` = ?');
			$user_query->execute(array($user['fname'], $user['lname'], $user['email'], $user_id));
			
			if(!$user_query->rowCount())
				throw new \Exception('Unable to/did not update user');

			echo $user_id;
		}
		catch(PDOException $ex) {
			$app->stop(500, $ex->getMessage());
		}
		catch(Exception $ex) {
			$app->stop(400, $ex->getMessage());
		}
	}

	function deleteUser($user_id) {
		global $app, $db;
		try {
			$user_query = $db->prepare('DELETE FROM `users` where `id` = ?');
			$user_query->bindParam(1, $user_id, \PDO::PARAM_INT);
			$user_query->execute();
			
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