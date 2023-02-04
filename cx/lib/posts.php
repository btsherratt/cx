<?php

cx_require('lib', 'db.php');
cx_require('lib', 'setup.php');
cx_require('third_party', 'parsedown', 'Parsedown.php');

function mk_markdown($markdown) {
	static $Parsedown = new Parsedown();

	return $Parsedown->text($markdown);
}

class Post {
	public $id;
	public $title;
	public $slug;
	public $date;
	public $data;
	public $html_content;
	public $html_excerpt;

	public function __construct($dict) {
		$this->id = $dict['post_id']; // FIXME, hide when not used?
		$this->title = $dict['post_title'];
		$this->slug = $dict['post_slug'];
		$this->date = $dict['post_date'];
		$this->data = $dict['post_data'];
		$this->html_content = mk_markdown($this->data);
		$this->html_excerpt = null;

		// Read more...
		$segments = explode('---', $this->data, 2);
		if (count($segments) > 1) {
			$this->html_excerpt = mk_markdown($segments[0]);
		}
	}
}

function cx_post_make_slug($title) {
	$alnum_title = preg_replace('/[^A-Za-z0-9 ]?/', '', $title);
	
	$slug_components = explode(' ', $alnum_title, 10);
	$slug_components = array_filter($slug_components);
	$slug_components = array_values($slug_components); // re-index

	$slug = join('-', $slug_components);
	$slug = strtolower($slug);

	return $slug;
}

function cx_posts_add_post($site_id, $title, $slug, $date, $data) {
	$creation_time = $update_time = time();
	
	if ($slug == null) {
		$slug = cx_post_make_slug($title);
	}

	if ($date == null) {
		$date = $update_time;
	}

	$sql = 'INSERT INTO posts (
			post_site_id,
			post_creation_time,
			post_update_time,
			post_slug,
			post_date,
			post_is_page,
			post_title,
			post_data,
			post_data_version)
		VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?);';
	cx_db_exec($sql, $site_id, $creation_time, $update_time, $slug, $date, false, $title, $data, 1);
}

function cx_posts_update_post($post_id, $title, $slug, $date, $data) {
	$update_time = time();
	
	if ($slug == null) {
		$slug = cx_post_make_slug($title);
	}

	if ($date == null) {
		$date = $update_time;
	}

	$sql = 'UPDATE posts
		SET post_update_time = ?,
		post_slug = ?,
		post_date = ?,
		post_title = ?,
		post_data = ?
		WHERE post_id == ?;';
		//LIMIT 1;';
	cx_db_exec($sql, $update_time, $slug, $date , $title, $data, $post_id);
}

function cx_posts_delete_post($post_id) {
	$sql = 'DELETE FROM posts
		WHERE post_id == ?;';
		//LIMIT 1;';
	cx_db_exec($sql, $post_id);
}

function cx_posts_get(int $limit = 0) {
	$sql = 'SELECT
		post_id,
		post_slug,
		post_date,
		post_title,
		post_data
		FROM posts
		WHERE post_is_page==FALSE
		ORDER BY post_date DESC';
	
	if ($limit > 0) {
		$sql .= ' LIMIT ' . $limit;
	}

	$sql .= ';';

	foreach (cx_db_query($sql) as $post) {
		$p = new Post($post);
		yield $p;
	}
}

function cx_posts_find_post($post_id) {
	$sql = 'SELECT
		post_id,
		post_slug,
		post_date,
		post_title,
		post_data
		FROM posts
		WHERE post_is_page == FALSE
		AND post_id == ?
		LIMIT 1;';

	foreach (cx_db_query($sql, $post_id) as $post) {
		$p = new Post($post);
		return $p;
	}

	return null;
}

function cx_posts_find_post_id($post_slug) {
	$sql = 'SELECT
		post_id
		FROM posts
		WHERE post_slug == ?
		LIMIT 1;';

	foreach (cx_db_query($sql, $post_slug) as $post) {
		return $post['post_id'];
	}

	return null;
}

function cx_pages_get() {
	$sql = 'SELECT
		post_id,
		post_slug,
		post_date,
		post_title,
		post_data
		FROM posts
		WHERE post_is_page == TRUE
		ORDER BY post_creation_time DESC;';

	foreach (cx_db_query($sql) as $post) {
		$p = new Post($post);
		yield $p;
	}
}

cx_setup_register(1, function() {
	cx_db_exec('CREATE TABLE posts (
			post_id INTEGER PRIMARY KEY,
			post_site_id INTEGER,
			post_creation_time INTEGER,
			post_update_time INTEGER,
			post_slug STRING,
			post_date INTEGER,
			post_is_page BOOLEAN,
			post_title STRING,
			post_data BLOB,
			post_data_version INTEGER,

			FOREIGN KEY(post_site_id) REFERENCES sites(site_id)
		);');
});
