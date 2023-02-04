<?php

cx_require('lib', 'db.php');
cx_require('lib', 'setup.php');

function _cx_default_site_details(...$args) {
	$sql = 'SELECT '. join(',', $args) .'
		FROM sites
		LIMIT 1;';

	foreach (cx_db_query($sql) as $site) {
		return $site;
	}
}

function cx_sites_add_site($url, $title, $byline, $copyright) {
	$sql = 'INSERT INTO sites (
			site_url,
			site_title,
			site_byline,
			site_copyright
		)
		VALUES (?, ?, ?, ?);';
	$site_id = cx_db_exec($sql, $url, $title, $byline, $copyright);
	return $site_id;
}

function cx_sites_site_add_user($site_id, $user_id, $owner = false) {
	$sql = 'INSERT INTO site_authorship (
			site_authorship_site_id,
			site_authorship_user_id,
			site_authorship_owner
		)
		VALUES (?, ?, ?);';
	cx_db_exec($sql, $site_id, $user_id, $owner);
}

function cx_site_name() {
	$details = _cx_default_site_details('site_title');
	return $details['site_title'];
}

function cx_site_byline() {
	$details = _cx_default_site_details('site_byline');
	return $details['site_byline'];
}

function cx_site_author() {
	$details = _cx_default_site_details('site_copyright');
	return $details['site_copyright'];
}

function cx_site_url() {
	$details = _cx_default_site_details('site_url');
	return $details['site_url'];
}

cx_setup_register(1, function() {
	cx_db_exec('CREATE TABLE sites (
			site_id INTEGER PRIMARY KEY,
			site_url STRING,
			site_title STRING,
			site_byline STRING,
			site_copyright STRING
		);');
	
	cx_db_exec('CREATE TABLE site_metadata (
			site_metadata_site_id INTEGER,
			site_metadata_key STRING,
			site_metadata_value STRING,

			PRIMARY KEY (site_metadata_site_id, site_metadata_key),
			FOREIGN KEY (site_metadata_site_id) REFERENCES sites(site_id)
		);');

	cx_db_exec('CREATE TABLE site_authorship (
			site_authorship_id INTEGER PRIMARY KEY,
			site_authorship_site_id INTEGER,
			site_authorship_user_id INTEGER,
			site_authorship_owner BOOLEAN,

			FOREIGN KEY(site_authorship_site_id) REFERENCES sites(site_id),
			FOREIGN KEY(site_authorship_user_id) REFERENCES users(user_id)
		);');
});
