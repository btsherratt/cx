<?php

cx_require('lib', 'setup.php');

function cx_user_data_find_all($type) {
	$data_directory = join(DIRECTORY_SEPARATOR, array(CX_PUBLIC_USER_DATA_PATH, $type));

	if (file_exists($data_directory)) {
		foreach (scandir($data_directory) as $path) {
			yield $path;
		}
	}
}

function cx_user_data_path($type, $filename = null) {
	$path = join(DIRECTORY_SEPARATOR, array(CX_PUBLIC_USER_DATA_PATH, $type));
	if ($filename != null) $path = join(DIRECTORY_SEPARATOR, array($path, $filename));
	return $path;
}

//	define('CX_USER_DATA_PATH', $data_folder_path);
//	define('CX_PUBLIC_USER_DATA_PATH', $public_data_folder_path);

//cx_user_data_register_type($type) {
//
//}

//cx_setup_register(2, function() {
//});
