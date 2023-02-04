<?php

cx_require('lib', 'db.php');
cx_require('lib', 'setup.php');

function cx_users_find_user($id = null, $name = null, $password_hash = null) {
	if ($id != null) {
		$sql = 'SELECT
			user_id
			FROM users
			WHERE user_id == ?
			LIMIT 1;';

		foreach (cx_db_query($sql, $id) as $user) {
			return $user['user_id'];
		}
	} else if ($name != null && $password_hash != null) {
		$sql = 'SELECT
			user_id
			FROM users
			WHERE user_name == ?
			AND user_password_hash == ?
			LIMIT 1;';

		foreach (cx_db_query($sql, $name, $password_hash) as $user) {
			return $user['user_id'];
		}
	}

	return null;
}

function cx_users_hash_password_for_user($username, $password) {
	$sql = 'SELECT
		user_salt
		FROM users
		WHERE user_name == ?
		LIMIT 1;';

	$salt = '';
	foreach (cx_db_query($sql, $username) as $user) {
		$salt = $user['user_salt'];
	}

	$password_hash = sha1($salt . $password);
	return $password_hash;
}

function cx_users_add_user($name, $password) {
	$creation_time = time();//
	$salt = sha1(random_bytes(100));
	$password_hash = sha1($salt . $password);

	$sql = 'INSERT INTO users (
			user_creation_time,
			user_name,
			user_salt,
			user_password_hash
		)
		VALUES (?, ?, ?, ?);';
	$user_id = cx_db_exec($sql, $creation_time, $name, $salt, $password_hash);
	return $user_id;
}

cx_setup_register(1, function() {
	cx_db_exec('CREATE TABLE users (
			user_id INTEGER PRIMARY KEY,
			user_creation_time INTEGER,
			user_name STRING,
			user_salt STRING,
			user_password_hash STRING
		);');
});
