<?php

cx_require('lib', 'db.php');
cx_require('lib', 'setup.php');

function cx_sessions_create_session($user) {
	$uid = sha1(random_bytes(100));
	$update_time = $creation_time = time();

	$sql = 'INSERT INTO sessions (
		session_uid,
		session_user_id,
		session_creation_time,
		session_update_time,
		session_closed_time)
	VALUES (?, ?, ?, ?, ?);';
	cx_db_exec($sql, $uid, $user, $creation_time, $update_time, -1);

	return $uid;
}

function cx_sessions_close_session($uid) {
	$close_time = time();

	$sql = 'UPDATE sessions
		SET session_closed_time = ?
		WHERE session_uid == ?;';
		//LIMIT 1;';

	cx_db_exec($sql, $close_time, $uid);
}

function cx_sessions_active_session_user($uid, $lifetime) {
	$sql = 'SELECT
		session_user_id
		FROM sessions
		WHERE session_uid == ?
		AND session_update_time >= ?
		AND session_closed_time == -1
		LIMIT 1;';

	$update_time = time() - $lifetime;

	foreach (cx_db_query($sql, $uid, $update_time) as $session) {
		return $session['session_user_id'];
	}

	return null;
}

cx_setup_register(1, function() {
	cx_db_exec('CREATE TABLE sessions (
		session_id INTEGER PRIMARY KEY,
		session_uid STRING,
		session_user_id INTEGER,
		session_creation_time INTEGER,
		session_update_time INTEGER,
		session_closed_time INTEGER,
		
		FOREIGN KEY(session_user_id) REFERENCES users(user_id))');
});


