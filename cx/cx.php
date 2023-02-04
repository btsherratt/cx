<?php

function cx_require(...$segments) {
	array_unshift($segments, CX_PATH);
	require_once(join(DIRECTORY_SEPARATOR, $segments));
}

define('CX_PATH', __DIR__);

cx_require('lib', 'admin.php');
cx_require('lib', 'form.php');
cx_require('lib', 'http.php');
cx_require('lib', 'images.php');
cx_require('lib', 'posts.php');
cx_require('lib', 'sessions.php');
cx_require('lib', 'setup.php');
cx_require('lib', 'site.php');
cx_require('lib', 'system.php');
cx_require('lib', 'template.php');
cx_require('lib', 'url.php');
cx_require('lib', 'user_data.php');
cx_require('lib', 'users.php');

function cx($db_path, $data_folder_path, $public_data_folder_path) {
	define('CX_DATABASE_FILE', $db_path);
	define('CX_USER_DATA_PATH', $data_folder_path);
	define('CX_PUBLIC_USER_DATA_PATH', $public_data_folder_path);

	if (cx_setup_required()) {
		cx_setup_run();

		require('../setup.php');
		$new_author = cx_users_add_user(CX_SETUP_USER, CX_SETUP_PASSWORD);
		$new_site = cx_sites_add_site(CX_SETUP_URL, CX_SETUP_TITLE, CX_SETUP_BYLINE, CX_SETUP_COPYRIGHT);
		cx_sites_site_add_user($new_site, $new_author, true);

		exit;
	}

	$path = '/';

	if (isset($_SERVER['REQUEST_URI'])) {
		$route_details = parse_url($_SERVER['REQUEST_URI']);
		if (isset($route_details['path'])) {
			$path = $route_details['path'];
		}
	}

	$script_name = $_SERVER['SCRIPT_NAME'];
	$script_name_len = strlen($script_name);
	if (substr_compare($path, $script_name, 0, $script_name_len) == 0) {
		$path = substr($path, $script_name_len);
	}

	cx_route($path);
}

function cx_route($path) {
	$path_components = explode('/', $path, 10);
	$path_components = array_filter($path_components);
	$path_components = array_values($path_components); // re-index

	$template = null;
	$template_class = 'public';
	$template_variables = [];

	if (count($path_components) == 0) {
		$template = 'list';
	} else if (count($path_components) >= 1 && $path_components[0] == 'feed') {
		header('Content-type: application/atom+xml;');
		$template = 'atom';
	} else if (count($path_components) >= 1 && $path_components[0] == 'cx') {
		if (count($path_components) >= 2 && $path_components[1] == 'login') {
			if (cx_admin_logged_in()) {
				cx_http_redirect(cx_url_admin('/'));
				exit(0);
			} else {
				$username = cx_form_input_sanitized('id');
				$password = cx_form_input_sanitized('password');
	
				if ($username != null && $password != null && cx_admin_login($username, $password)) {
					cx_http_redirect(cx_url_admin('/'));
					exit(0);
				}
	
				$template_class = 'admin';
				$template = 'login';
			}
		} else {
			if (cx_admin_logged_in() == false) {
				cx_http_redirect(cx_url_admin('/login/'));
				exit(0);
			} else {
				if (count($path_components) >= 2 && $path_components[1] == 'logout') {
					cx_admin_logout();
					cx_http_redirect(cx_url_admin('/'));
					exit(0);
				} else if (count($path_components) >= 3 && $path_components[1] == 'posts' && $path_components[2] == 'add') {
					$template_variables['post_id'] = '0';
					$template_variables['post_title'] = '';
					$template_variables['post_slug'] = '';
					$template_variables['post_date'] = '';
					$template_variables['post_data'] = '';

					$template_class = 'admin';
					$template = 'post';
				} else if (count($path_components) >= 3 && $path_components[1] == 'posts' && $path_components[2] == 'edit') {
					$post = cx_posts_find_post($_GET['id']);

					$template_variables['post_id'] = $post->id;
					$template_variables['post_title'] = $post->title;
					$template_variables['post_slug'] = $post->slug;
					$template_variables['post_date'] = $post->date;
					$template_variables['post_data'] = $post->data;

					$template_class = 'admin';
					$template = 'post';
				} else if (count($path_components) >= 3 && $path_components[1] == 'posts' && $path_components[2] == 'update') {
					$title = cx_form_input_sanitized('post_title');
					$slug = cx_form_input_sanitized('post_slug');
					if (isset($slug) == false) $slug = null;
					$date = cx_form_input_sanitized_date_time('post_date');
					if (isset($date) == false) $date = null;
					$data = cx_form_input_sanitized('post_data');
					
					if (isset($_GET['id']) == false or $_GET['id'] == 0) {
						cx_posts_add_post(1, $title, $slug, $date, $data);
					} else {
						$id = $_GET['id'];
						cx_posts_update_post($id, $title, $slug, $date, $data);
					}
					
					cx_http_redirect(cx_url_admin('/'));
					exit(0);
				} else if (count($path_components) >= 3 && $path_components[1] == 'posts' && $path_components[2] == 'delete') {
					cx_posts_delete_post($_GET['id']);
					cx_http_redirect(cx_url_admin('/'));
					exit(0);
				} else if (count($path_components) >= 3 && $path_components[1] == 'images' && $path_components[2] == 'add') {
					$template_variables['image_id'] = '0';
					$template_variables['image_alt_text'] = '';
					
					$template_class = 'admin';
					$template = 'image';
				} else if (count($path_components) >= 3 && $path_components[1] == 'images' && $path_components[2] == 'update') {
					$alt_text = cx_form_input_sanitized('image_alt_text');

					$filename = $_FILES['image_file']['tmp_name'];
					$original_filename = $_FILES['image_file']['name'];

					cx_images_add_image(1, $alt_text, $filename, $original_filename);
					
					cx_http_redirect(cx_url_admin('/'));
					exit(0);
				} else {
					$template_class = 'admin';
					$template = 'main';
				}
			}
		}
	} else if (count($path_components) >= 3) { // FIXME sometime, needs more flexibility...
		$year = $path_components[0];
		$month = $path_components[1];
		$slug = $path_components[2];

		$template = 'post';
		$template_variables['post_id'] = cx_posts_find_post_id($slug);
	}
	
	if ($template != null) {
		$output = cx_template_render($template_class, $template, $template_variables);
		echo($output);
	} else {
		http_response_code(404);
		exit(0);
	}
}
