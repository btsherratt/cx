<?php

cx_require('lib', 'db.php');
cx_require('lib', 'setup.php');

function _cx_default_site_details(...$args) {
	$sql = 'SELECT '. join(',', $args) .'
		FROM sites
		INNER JOIN users ON site_user_id == user_id
		WHERE site_slug == ""
		LIMIT 1;';

	foreach (cx_db_query($sql) as $site) {
		return $site;
	}
}

function cx_sites_add_site($user_id) {
	$owner = $user_id;
	$slug = '';
	$title = 'bts.cx';
	$copyright = 'ben';

	$sql = 'INSERT INTO sites (
			site_user_id,
			site_slug,
			site_title,
			site_copyright
		)
		VALUES (?, ?, ?, ?);';
	cx_db_exec($sql, $owner, $slug, $title, $copyright);
}

function cx_site_name() {
	$details = _cx_default_site_details('site_title');
	return $details['site_title'];
}

function cx_site_byline() {
	return '';
}

function cx_site_author() {
	return 'BTS';
	//$details = _cx_default_site_details('user_name');
	//return $details['user_name'];
}

function cx_site_url() {
	return 'https://bts.cx';
}

cx_setup_register(1, function() {
	cx_db_exec('CREATE TABLE sites (
			site_id INTEGER PRIMARY KEY,
			site_user_id INTEGER,
			site_slug STRING,
			site_title STRING,
			site_copyright STRING,

			FOREIGN KEY(site_user_id) REFERENCES users(user_id)
		);');
});
