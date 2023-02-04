<?php

function cx_template_render($class, $name, $variables = []) {
	global $cx_template_base, $cx_template_content;
	
	$base_template = null;

	$output = '';
	while ($name != null) {
		$segments = array(CX_PATH, 'templates', $class, $name . '.php');
		$path = join(DIRECTORY_SEPARATOR, $segments);
		
		$base_template = null;

		$cx_template_base_previous = $cx_template_base;
		$cx_template_base = function($name) use (&$base_template ) {
			$base_template = $name;
		};

		$cx_template_content_previous = $cx_template_content;
		$cx_template_content = function() use ($output) {
			return $output;
		};

		cx_require('lib', 'url.php'); // For templates

		extract($variables);
		ob_start();
		include($path);
		$output = ob_get_contents();
		ob_end_clean();

		$cx_template_base = $cx_template_base_previous;
		$cx_template_content = $cx_template_content_previous;

		$name = $base_template;
	}

	return $output;
}

function cx_template_base($name) {
	global $cx_template_base;
	$cx_template_base($name);
}

function cx_template_content() {
	global $cx_template_content;
	return $cx_template_content();
}
