<?php

$cx_setup_functions = [];

function cx_setup_register(int $version, $function) {
	global $cx_setup_functions;

	if (isset($version, $cx_setup_functions) == false) {
		$cx_setup_functions[$version] = [];
	}

	$cx_setup_functions[$version][] = $function;
}

function cx_setup_required() {
	return file_exists(CX_DATABASE_FILE) == false;
}

function cx_setup_run() {
	global $cx_setup_functions;
	foreach ($cx_setup_functions as $version => $functions) {
		foreach ($functions as $function) {
			$function();
		}
	}
}
