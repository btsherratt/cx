<?php

cx_require('lib', 'db.php');
cx_require('lib', 'setup.php');
cx_require('lib', 'user_data.php');


class Image {
	public $id;
	public $uid;
	public $alt_text;
	public $url;

	public function __construct($dict) {
		$this->id = $dict['image_id']; // FIXME, hide when not used?
		$this->uid = $dict['image_uid'];
		$this->alt_text = $dict['image_alt_text'];
		$this->url = $this->uid . '.' . $dict['image_type'];
	}
}

function cx_images_add_image($site_id, $alt_text, $image_path, $image_original_filename) {
	$path_parts = pathinfo($image_original_filename);

	$uid = hash_file("sha256", $image_path);

	$target_name = $uid . "." . $path_parts['extension'];
	$path = cx_user_data_path('images', $target_name);

	move_uploaded_file($image_path, $path);

	$creation_time = $update_time = time();
	
	$sql = 'INSERT INTO images (
			image_site_id,
			image_creation_time,
			image_update_time,
			image_uid,
			image_type,
			image_alt_text)
		VALUES (?, ?, ?, ?, ?, ?);';
	cx_db_exec($sql, $site_id, $creation_time, $update_time, $uid, $path_parts['extension'], $alt_text);
}

function cx_images_get(int $limit = 0) {
	$sql = 'SELECT
		image_id,
		image_uid,
		image_type,
		image_alt_text
		FROM images
		ORDER BY image_creation_time DESC';

	if ($limit > 0) {
		$sql .= ' LIMIT ' . $limit;
	}

	$sql .= ';';

	foreach (cx_db_query($sql) as $image) {
		$p = new Image($image);
		yield $p;
	}
}

cx_setup_register(1, function() {
	cx_db_exec('CREATE TABLE images (
			image_id INTEGER PRIMARY KEY,
			image_site_id INTEGER,
			image_creation_time INTEGER,
			image_update_time INTEGER,
			image_uid STRING,
			image_type STRING,
			image_alt_text STRING,

			FOREIGN KEY(image_site_id) REFERENCES sites(site_id)
		);');

	mkdir(cx_user_data_path('images'), recursive: true);
});
