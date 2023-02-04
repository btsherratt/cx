<?php

cx_require('lib', 'sessions.php');
cx_require('lib', 'users.php');

define('CX_ADMIN_SESSION_LIFETIME', (86400 * 1)); // 86400 = 1 day

function cx_admin_logged_in() {
	return isset($_COOKIE['cx_session']) && cx_sessions_active_session_user($_COOKIE['cx_session'], CX_ADMIN_SESSION_LIFETIME) != null;
}

function cx_admin_login($username, $password) {
	$password_hash = cx_users_hash_password_for_user($username, $password);

	$user = cx_users_find_user(name: $username, password_hash: $password_hash);
	if ($user == null) {
		return false;
	} else {
		$session_uid = cx_sessions_create_session($user);
		setcookie('cx_session', $session_uid, time() + CX_ADMIN_SESSION_LIFETIME, "/");
		return true;
	}
}

function cx_admin_logout() {
	if (isset($_COOKIE['cx_session'])) {
		cx_sessions_close_session($_COOKIE['cx_session']);
	}
	setcookie('cx_session', null, time(), "/");
}
