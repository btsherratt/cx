<?php

cx_require('lib', 'db.php');
cx_require('lib', 'setup.php');

class SitePost {
	public $id;
	public $post;
	public $date;
	public $slug;

	public function __construct($dict) {
		$this->id = $dict['site_content_id']; // FIXME, hide when not used?
		$this->post = new Post($dict);
		$this->date = $dict['site_content_date'];
		$this->slug = $dict['site_content_slug'];
	}
}

function _cx_make_slug($title) {
	$alnum_title = preg_replace('/[^A-Za-z0-9 ]?/', '', $title);
	
	$slug_components = explode(' ', $alnum_title, 10);
	$slug_components = array_filter($slug_components);
	$slug_components = array_values($slug_components); // re-index

	$slug = join('-', $slug_components);
	$slug = strtolower($slug);

	return $slug;
}

function _cx_default_site_details(...$args) {
	$sql = 'SELECT '. join(',', $args) .'
		FROM sites
		LIMIT 1;';

	foreach (cx_db_query($sql) as $site) {
		return $site;
	}
}

function cx_sites_find_site($host) {
	$sql = 'SELECT
			site_id
			FROM sites
			WHERE site_host == ?
			LIMIT 1;';

	foreach (cx_db_query($sql, $host) as $site) {
		return $site['site_id'];
	}

	return null;
}

function cx_sites_add_site($host, $title, $byline, $copyright) {
	$sql = 'INSERT INTO sites (
			site_host,
			site_title,
			site_byline,
			site_copyright
		)
		VALUES (?, ?, ?, ?);';
	$site_id = cx_db_exec($sql, $host, $title, $byline, $copyright);
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

function cx_sites_site_add_content($site_id, $post_id, $date, $is_page = false) {
	
}

function cx_sites_site_update_content($content_id, $date, $is_page = false) {
	
}

function cx_sites_site_delete_content($content_id) {
	
}

function cx_site_get_pages($site_id) {
	$sql = 'SELECT
		site_content_date,
		post_id,
		post_slug,
		post_title,
		post_data
		FROM site_content
		INNER JOIN posts ON site_content_post_id = post_id
		WHERE site_content_site_id == ?
		AND site_content_is_page == TRUE;';

	foreach (cx_db_query($sql, $site_id) as $site_post) {
		$p = new SitePost($site_post);
		yield $p;
	}
}

function cx_site_get_posts($site_id, int $limit = 0) {
	$sql = 'SELECT
		site_content_date,
		post_id,
		post_slug,
		post_title,
		post_data
		FROM site_content
		INNER JOIN posts ON site_content_post_id = post_id
		WHERE site_content_site_id == ?
		AND site_content_is_page == FALSE
		ORDER BY site_content_date DESC';

	if ($limit > 0) {
		$sql .= ' LIMIT ' . $limit;
	}

	$sql .= ';';

	foreach (cx_db_query($sql, $site_id) as $site_post) {
		$p = new SitePost($site_post);
		yield $p;
	}
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

function cx_site_host() {
	$details = _cx_default_site_details('site_host');
	return $details['site_host'];
}

cx_setup_register(1, function() {
	cx_db_exec('CREATE TABLE sites (
			site_id INTEGER PRIMARY KEY,
			site_host STRING,
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

	cx_db_exec('CREATE TABLE site_content (
			site_content_id INTEGER PRIMARY KEY,
			site_content_site_id INTEGER,
			site_content_post_id INTEGER,
			site_content_date INTEGER,
			site_content_slug STRING,
			site_content_is_page BOOLEAN,
			
			FOREIGN KEY(site_content_site_id) REFERENCES sites(site_id),
			FOREIGN KEY(site_content_post_id) REFERENCES posts(post_id)
		);');
});
