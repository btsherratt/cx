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
	public $date;
	public $data;
	public $html_content;
	public $html_excerpt;

	public function __construct($dict) {
		$this->id = $dict['post_id']; // FIXME, hide when not used?
		$this->title = $dict['post_title'];
		//$this->date = $dict['post_date'];
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

function cx_posts_add_post($title, $data) {
	$creation_time = $update_time = time();
	
	if ($slug == null) {
		$slug = cx_post_make_slug($title);
	}

	$sql = 'INSERT INTO posts (
			post_creation_time,
			post_update_time,
			post_title,
			post_data,
			post_data_version)
		VALUES (?, ?, ?, ?, ?);';
	$post_id = cx_db_exec($sql, $creation_time, $update_time, $title, $data, 1);
	return $post_id;
}

function cx_posts_update_post($post_id, $title, $data) {
	$update_time = time();
	
	$sql = 'UPDATE posts
		SET post_update_time = ?,
		post_title = ?,
		post_data = ?
		WHERE post_id == ?;';
		//LIMIT 1;';
	cx_db_exec($sql, $update_time, $title, $data, $post_id);
}

function cx_posts_delete_post($post_id) {
	$sql = 'DELETE FROM posts
		WHERE post_id == ?;';
		//LIMIT 1;';
	cx_db_exec($sql, $post_id);
}





function cx_posts_get() {
	$sql = 'SELECT
		post_id,
		post_title,
		post_data
		FROM posts
		ORDER BY post_update_time DESC;';
	
	foreach (cx_db_query($sql) as $post) {
		$p = new Post($post);
		yield $p;
	}
}

/*function cx_posts_find_post($post_id) {
	$sql = 'SELECT
		post_id,
		post_slug,
		post_title,
		post_data
		FROM posts
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
}*/

/*function cx_pages_get() {
	$sql = 'SELECT
		post_id,
		post_slug,
		post_title,
		post_data
		FROM posts
		WHERE post_is_page == TRUE
		ORDER BY post_creation_time DESC;';

	foreach (cx_db_query($sql) as $post) {
		$p = new Post($post);
		yield $p;
	}
}*/

cx_setup_register(1, function() {
	cx_db_exec('CREATE TABLE posts (
			post_id INTEGER PRIMARY KEY,
			post_creation_time INTEGER,
			post_update_time INTEGER,
			post_title STRING,
			post_data BLOB,
			post_data_version INTEGER
		);');
});
